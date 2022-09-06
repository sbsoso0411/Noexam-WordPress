<?php

/**
 * Handles all the stats related functions
 *
 * @package WP Smush
 * @subpackage NextGen Gallery
 * @version 1.0
 *
 * @author Umesh Kumar <umesh@incsub.com>
 *
 * @copyright (c) 2016, Incsub (http://incsub.com)
 */
if ( ! class_exists( 'WpSmushNextGenStats' ) ) {

	class WpSmushNextGenStats extends WpSmushNextGen {

		/**
		 * @var array Contains the total Stats, for displaying it on bulk page
		 */
		var $stats = array();
		private $is_pro_user;

		function __construct() {

			global $WpSmush;
			$this->is_pro_user = $WpSmush->is_pro();

			//Update Total Image count
			add_action( 'ngg_added_new_image', array( $this, 'image_count' ), 10 );

			//Update images list in cache
			add_action( 'wp_smush_nextgen_image_stats', array( $this, 'update_cache' ) );

			//Add the resizing stats to Global stats
			add_action( 'wp_smush_image_nextgen_resized',  array( $this, 'update_stats' ), '', 2 );

			//Get the stats for single image, update the global stats
			add_action( 'wp_smush_nextgen_image_stats', array( $this, 'update_stats' ), '', 2 );
		}

		/**
		 * Refreshes the total image count when a new image is added to nextgen gallery
		 * Should be called only if image count need to be updated, use total_count(), otherwise
		 *
		 */
		function image_count() {
			// Force the cache refresh for top-commented posts.
			$this->total_count( $force_refresh = true );
		}

		/**
		 * Get the images id for nextgen gallery
		 *
		 * @param bool $force_refresh Optional. Whether to force the cache to be refreshed.
		 * Default false.
		 *
		 * @return int|WP_Error Total Image count,
		 * WP_Error object otherwise.
		 */
		function total_count( $force_refresh = false, $return_ids = false ) {
			// Check for the  wp_smush_images in the 'nextgen' group.
			$attachment_ids = wp_cache_get( 'wp_smush_images', 'nextgen' );

			// If nothing is found, build the object.
			if ( true === $force_refresh || false === $attachment_ids ) {
				//Get the nextgen image ids
				$attachment_ids = $this->get_nextgen_attachments();

				if ( ! is_wp_error( $attachment_ids ) ) {
					// In this case we don't need a timed cache expiration.
					wp_cache_set( 'wp_smush_images', $attachment_ids, 'nextgen' );
				}
			}

			return $return_ids ? $attachment_ids : count( $attachment_ids );
		}

		/**
		 *
		 *
		 * @param bool $return_ids Whether to return the ids array, set to false by default
		 *
		 * @return int|mixed|void Returns the images ids or the count
		 */
		/**
		 * Returns the ngg images list(id and meta ) or count
		 *
		 * @param string $type Whether to return smushed images or unsmushed images
		 * @param bool|false $count Return count only
		 * @param bool|false $force_update true/false to update the cache or not
		 *
		 * @return bool|mixed Returns assoc array of image ids and meta or Image count
		 */
		function get_ngg_images( $type = 'smushed', $count = false, $force_update = false ) {

			global $wpdb, $wpsmushit_admin;
			$limit  = $wpsmushit_admin->nextgen_query_limit();
			$offset = 0;

			//Check type of images being queried
			if ( ! in_array( $type, array( 'smushed', 'unsmushed' ) ) ) {
				return false;
			}

			// Check for the  wp_smush_images_smushed in the 'nextgen' group.
			$images = wp_cache_get( 'wp_smush_images_' . $type, 'nextgen' );

			// If nothing is found, build the object.
			if ( ! $images || $force_update ) {
				// Query Attachments for meta key
				while ( $attachments = $wpdb->get_results( "SELECT pid, meta_data FROM $wpdb->nggpictures LIMIT $offset, $limit" ) ) {
					foreach ( $attachments as $attachment ) {
						//Check if it has `wp_smush` key
						if ( class_exists( 'Ngg_Serializable' ) ) {
							$serializer = new Ngg_Serializable();
							$meta       = $serializer->unserialize( $attachment->meta_data );
						} else {
							$meta = unserialize( $attachment->meta_data );
						}

						//Store pid in image meta
						if ( is_array( $meta ) && empty( $meta['pid'] ) ) {
							$meta['pid'] = $attachment->pid;
						} elseif ( is_object( $meta ) && empty( $meta->pid ) ) {
							$meta->pid = $attachment->pid;
						}

						//Check meta for wp_smush
						if ( ! is_array( $meta ) || empty( $meta['wp_smush'] ) ) {
							$unsmushed_images[ $attachment->pid ] = $meta;
							continue;
						}
						$smushed_images[ $attachment->pid ] = $meta;
					}
					//Set the offset
					$offset += $limit;
				}
				if ( ! empty( $smushed_images ) ) {
					wp_cache_set( 'wp_smush_images_smushed', $smushed_images, 'nextgen', 300 );
				}
				if ( ! empty( $unsmushed_images ) ) {
					wp_cache_set( 'wp_smush_images_unsmushed', $unsmushed_images, 'nextgen', 300 );
				}
			}

			if ( $type == 'smushed' ) {
				$smushed_images = ! empty( $smushed_images ) ? $smushed_images : $images;

				if ( ! $smushed_images ) {
					return 0;
				} else {
					return $count ? count( $smushed_images ) : $smushed_images;
				}
			} else {

				$unsmushed_images = ! empty( $unsmushed_images ) ? $unsmushed_images : $images;
				if ( ! $unsmushed_images ) {
					return 0;
				} else {
					return $count ? count( $unsmushed_images ) : $unsmushed_images;
				}
			}
		}

		/**
		 * Display the smush stats for the image
		 *
		 * @param $pid Image Id stored in nextgen table
		 * @param bool $wp_smush_data Stats, stored after smushing the image
		 * @param string $image_type Used for determining if not gif, to show the Super Smush button
		 * @param bool $text_only Return only text instead of button (Useful for Ajax)
		 * @param bool $echo Whether to echo the stats or not
		 *
		 * @uses WpSmushNextGenAdmin::column_html(), WpSmush::get_restore_link(), WpSmush::get_resmush_link()
		 *
		 * @return bool|null|string|void
		 */
		function show_stats( $pid, $wp_smush_data = false, $image_type = '', $text_only = false, $echo = true ) {
			global $WpSmush, $wpsmushnextgenadmin;
			if ( empty( $wp_smush_data ) ) {
				return false;
			}
			$button_txt  = $stats = '';
			$show_button = $show_resmush = $show_restore = false;

			$bytes          = isset( $wp_smush_data['stats']['bytes'] ) ? $wp_smush_data['stats']['bytes'] : 0;
			$bytes_readable = ! empty( $bytes ) ? $WpSmush->format_bytes( $bytes ) : '';
			$percent        = isset( $wp_smush_data['stats']['percent'] ) ? $wp_smush_data['stats']['percent'] : 0;
			$percent        = $percent < 0 ? 0 : $percent;

			if ( isset( $wp_smush_data['stats']['size_before'] ) && $wp_smush_data['stats']['size_before'] == 0 && ! empty( $wp_smush_data['sizes'] ) ) {
				$status_txt  = __( 'Already Optimized', 'wp-smushit' );
			} else {
				if ( $bytes == 0 || $percent == 0 ) {
					$status_txt = __( 'Already Optimized', 'wp-smushit' );

					//Add resmush option if needed
					$show_resmush = $this->show_resmush( $show_resmush, $wp_smush_data );
					if ( $show_resmush ) {
						$status_txt .= '<br />' . $WpSmush->get_resmsuh_link( $pid, 'nextgen' );
					}

				} elseif ( ! empty( $percent ) && ! empty( $bytes_readable ) ) {
					$status_txt = sprintf( __( "Reduced by %s (  %01.1f%% )", 'wp-smushit' ), $bytes_readable, number_format_i18n( $percent, 2, '.', '' ) );

					$show_resmush = $this->show_resmush( $show_resmush, $wp_smush_data );

					if ( $show_resmush ) {
						$status_txt .= '<br />' . $WpSmush->get_resmsuh_link( $pid, 'nextgen' );
					}

					//Restore Image: Check if we need to show the restore image option
					$show_restore = $this->show_restore_option( $pid, $wp_smush_data );

					if ( $show_restore ) {
						if ( $show_resmush ) {
							//Show Separator
							$status_txt .= ' | ';
						} else {
							//Show the link in next line
							$status_txt .= '<br />';
						}
						$status_txt .= $WpSmush->get_restore_link( $pid, 'nextgen' );
					}
					//Show detailed stats if available
					if ( ! empty( $wp_smush_data['sizes'] ) ) {
						if ( $show_resmush || $show_restore ) {
							//Show Separator
							$status_txt .= ' | ';
						} else {
							//Show the link in next line
							$status_txt .= '<br />';
						}
						//Detailed Stats Link
						$status_txt .= '<a href="#" class="smush-stats-details">' . esc_html__( "Smush stats", 'wp-smushit' ) . ' [<span class="stats-toggle">+</span>]</a>';

						//Get metadata For the image
						// Registry Object for NextGen Gallery
						$registry = C_Component_Registry::get_instance();

						//Gallery Storage Object
						$storage = $registry->get_utility( 'I_Gallery_Storage' );

						// get an array of sizes available for the $image
						$sizes = $storage->get_image_sizes();

						$image = $storage->object->_image_mapper->find( $pid );

						$full_image = $storage->get_image_abspath( $image, 'full' );

						//Stats
						$stats = $this->get_detailed_stats( $pid, $wp_smush_data, array( 'sizes' => $sizes ), $full_image );

						if ( ! $text_only ) {
							$status_txt .= $stats;
						}
					}
				}
			}

			//IF current compression is lossy
			if ( ! empty( $wp_smush_data ) && ! empty( $wp_smush_data['stats'] ) ) {
				$lossy    = ! empty( $wp_smush_data['stats']['lossy'] ) ? $wp_smush_data['stats']['lossy'] : '';
				$is_lossy = $lossy == 1 ? true : false;
			}

			//Check if Lossy enabled
			$opt_lossy     = WP_SMUSH_PREFIX . 'lossy';
			$opt_lossy_val = get_option( $opt_lossy, false );

			//Check if premium user, compression was lossless, and lossy compression is enabled
			if ( !$show_resmush && $this->is_pro_user && ! $is_lossy && $opt_lossy_val && ! empty( $image_type ) && $image_type != 'image/gif' ) {
				// the button text
				$button_txt  = __( 'Super-Smush', 'wp-smushit' );
				$show_button = true;
			}
			if ( $text_only ) {
				//For ajax response
				return array(
					'status' => $status_txt,
					'stats'  => $stats
				);
			}

			//If show button is true for some reason, column html can print out the button for us
			$text = $wpsmushnextgenadmin->column_html( $pid, $status_txt, $button_txt, $show_button, true, $echo );
			if ( ! $echo ) {
				return $text;
			}
		}

		/**
		 * Updated the global smush stats for NextGen gallery
		 *
		 * @param $stats Compression stats fo respective image
		 *
		 */
		function update_stats( $image_id, $stats ) {

			global $WpSmush;

			$stats = ! empty( $stats['stats'] ) ? $stats['stats'] : '';

			$smush_stats = get_option( 'wp_smush_stats_nextgen', array() );

			if ( ! empty( $stats ) ) {

				//Compression Bytes
				$smush_stats['bytes'] = ! empty( $smush_stats['bytes'] ) ? ( $smush_stats['bytes'] + $stats['bytes'] ) : $stats['bytes'];

				//Human Readable
				$smush_stats['human'] = ! empty( $smush_stats['bytes'] ) ? $WpSmush->format_bytes( $smush_stats['bytes'] ) : '';

				//Size of images before the compression
				$smush_stats['size_before'] = ! empty( $smush_stats['size_before'] ) ? ( $smush_stats['size_before'] + $stats['size_before'] ) : $stats['size_before'];

				//Size of image after compression
				$smush_stats['size_after'] = ! empty( $smush_stats['size_after'] ) ? ( $smush_stats['size_after'] + $stats['size_after'] ) : $stats['size_after'];

				//Compression Percentage
				$smush_stats['percent'] = ! empty( $smush_stats['size_before'] ) && !empty( $smush_stats['size_after'] ) && $smush_stats['size_before'] > 0 ? ( $smush_stats['bytes'] / $smush_stats['size_before'] ) * 100 : $stats['percent'];
			}

			update_option( 'wp_smush_stats_nextgen', $smush_stats );

			//Cahce the results, we don't need a timed cache expiration.
			wp_cache_set( 'wp_smush_stats_nextgen', $smush_stats, 'nextgen' );
		}

		/**
		 * Updated the global smush stats for NextGen gallery
		 *
		 * @param $stats Compression stats fo respective image
		 *
		 */
		function update_resize_stats( $image_id, $stats ) {
			global $WpSmush;

			$stats = ! empty( $stats['stats'] ) ? $stats['stats'] : '';

			$smush_stats = get_option( 'wp_smush_stats_nextgen', array() );

			if ( ! empty( $stats ) ) {

				//Compression Bytes
				$smush_stats['bytes'] = ! empty( $smush_stats['bytes'] ) ? ( $smush_stats['bytes'] + $stats['bytes'] ) : $stats['bytes'];

				//Human Readable
				$smush_stats['human'] = ! empty( $smush_stats['bytes'] ) ? $WpSmush->format_bytes( $smush_stats['bytes'] ) : '';

				//Size of images before the compression
				$smush_stats['size_before'] = ! empty( $smush_stats['size_before'] ) ? ( $smush_stats['size_before'] + $stats['size_before'] ) : $stats['size_before'];

				//Size of image after compression
				$smush_stats['size_after'] = ! empty( $smush_stats['size_after'] ) ? ( $smush_stats['size_after'] + $stats['size_after'] ) : $stats['size_after'];

				//Compression Percentage
				$smush_stats['percent'] = ! empty( $smush_stats['size_before'] ) && !empty( $smush_stats['size_after'] ) && $smush_stats['size_before'] > 0 ? ( $smush_stats['bytes'] / $smush_stats['size_before'] ) * 100 : $stats['percent'];
			}
			update_option( 'wp_smush_stats_nextgen', $smush_stats );

			//Cahce the results, we don't need a timed cache expiration.
			wp_cache_set( 'wp_smush_stats_nextgen', $smush_stats, 'nextgen' );
		}

		/**
		 * Get the Nextgen Smush stats
		 * @return bool|mixed|void
		 */
		function get_smush_stats() {
			global $WpSmush;

			//Clear up the stats
			if( 0 == $this->total_count() ) {
				delete_option('wp_smush_stats_nextgen');
				wp_cache_delete( 'wp_smush_stats_nextgen', 'nextgen' );
			}

			// Check for the  wp_smush_images_smushed in the 'nextgen' group.
			$smushed_stats = wp_cache_get( 'wp_smush_stats_nextgen', 'nextgen' );

			// If nothing is found, build the object.
			if ( false === $smushed_stats ) {
				// Check for the  wp_smush_images in the 'nextgen' group.
				$smushed_stats = get_option( 'wp_smush_stats_nextgen', array() );

				if ( ! is_wp_error( $smushed_stats ) ) {
					// In this case we don't need a timed cache expiration.
					wp_cache_set( 'wp_smush_stats_nextgen', $smushed_stats, 'nextgen' );
				}
			}
			if ( empty( $smushed_stats['bytes'] ) || $smushed_stats['bytes'] < 0 ) {
				$smushed_stats['bytes'] = 0;
			}

			if ( ! empty( $smushed_stats['size_before'] ) && $smushed_stats['size_before'] > 0 ) {
				$smushed_stats['percent'] = ( $smushed_stats['bytes'] / $smushed_stats['size_before'] ) * 100;
			}

			//Round off precentage
			$smushed_stats['percent'] = ! empty( $smushed_stats['percent'] ) ? round( $smushed_stats['percent'], 2 ) : 0;

			$smushed_stats['human'] = $WpSmush->format_bytes( $smushed_stats['bytes'] );

			return $smushed_stats;
		}

		/**
		 * Updates the cache for Smushed and Unsmushed images
		 */
		function update_cache() {
			$this->get_ngg_images( 'smushed', '', true );
		}

		/**
		 * Returns the Stats for a image formatted into a nice table
		 * @param $image_id
		 * @param $wp_smush_data
		 * @param $attachment_metadata
		 * @param $full_image
		 *
		 * @return string
		 */
		function get_detailed_stats( $image_id, $wp_smush_data, $attachment_metadata, $full_image ) {
			global $WpSmush;

			$stats      = '<div id="smush-stats-' . $image_id . '" class="smush-stats-wrapper hidden">
				<table class="wp-smush-stats-holder">
					<thead>
						<tr>
							<th><strong>' . esc_html__( 'Image size', 'wp-smushit' ) . '</strong></th>
							<th><strong>' . esc_html__( 'Savings', 'wp-smushit' ) . '</strong></th>
						</tr>
					</thead>
					<tbody>';
			$size_stats = $wp_smush_data['sizes'];

			//Reorder Sizes as per the maximum savings
			uasort( $size_stats, array( $this, "cmp" ) );

			if ( ! empty( $attachment_metadata['sizes'] ) ) {
				//Get skipped images
				$skipped = $this->get_skipped_images( $size_stats, $full_image );

				if ( ! empty( $skipped ) ) {
					foreach ( $skipped as $img_data ) {
						$skip_class = $img_data['reason'] == 'size_limit' ? ' error' : '';
						$stats .= '<tr>
					<td>' . strtoupper( $img_data['size'] ) . '</td>
					<td class="smush-skipped' . $skip_class . '">' . $WpSmush->skip_reason( $img_data['reason'] ) . '</td>
				</tr>';
					}

				}
			}
			//Show Sizes and their compression
			foreach ( $size_stats as $size_key => $size_value ) {
				$size_value = ! is_object( $size_value ) ? (object) $size_value : $size_value;
				if ( $size_value->bytes > 0 ) {
					$stats .= '<tr>
					<td>' . strtoupper( $size_key ) . '</td>
					<td>' . $WpSmush->format_bytes( $size_value->bytes ) . ' ( ' . $size_value->percent . '% )</td>
				</tr>';
				}
			}
			$stats .= '</tbody>
				</table>
			</div>';

			return $stats;
		}

		/**
		 * Compare Values
		 *
		 * @param $a
		 * @param $b
		 *
		 * @return int
		 */
		function cmp( $a, $b ) {
			if ( is_object( $a ) ) {
				//Check and typecast $b if required
				$b = is_object( $b ) ? $b : (object) $b;

				return $a->bytes < $b->bytes;
			} else if ( is_array( $a ) ) {
				$b = is_array( $b ) ? $b : (array) $b;

				return $a['bytes'] < $b['bytes'];
			}
		}

		/**
		 * Return a list of images not smushed and reason
		 *
		 * @param $image_id
		 * @param $size_stats
		 * @param $attachment_metadata
		 *
		 * @return array
		 */
		function get_skipped_images( $size_stats, $full_image ) {
			$skipped = array();

			//If full image was not smushed, reason 1. Large Size logic, 2. Free and greater than 1Mb
			if ( ! array_key_exists( 'full', $size_stats ) ) {
				//For free version, Check the image size
				if ( ! $this->is_pro_user ) {
					//For free version, check if full size is greater than 1 Mb, show the skipped status
					$file_size = file_exists( $full_image ) ? filesize( $full_image ) : '';
					if ( ! empty( $file_size ) && ( $file_size / WP_SMUSH_MAX_BYTES ) > 1 ) {
						$skipped[] = array(
							'size'   => 'full',
							'reason' => 'size_limit'
						);
					} else {
						$skipped[] = array(
							'size'   => 'full',
							'reason' => 'large_size'
						);
					}
				} else {
					//Paid version, Check if we have large size
					if ( array_key_exists( 'large', $size_stats ) ) {
						$skipped[] = array(
							'size'   => 'full',
							'reason' => 'large_size'
						);
					}

				}
			}

			return $skipped;
		}

		/**
		 * Check if image can be resmushed
		 * @param $status_txt
		 *
		 * @return string
		 */
		function show_resmush( $show_resmush, $wp_smush_data ) {
			global $WpSmush;
			//Resmush: Show resmush link, Check if user have enabled smushing the original and full image was skipped
			if ( $WpSmush->smush_original ) {
				//IF full image was not smushed
				if ( ! empty( $wp_smush_data ) && empty( $wp_smush_data['sizes']['full'] ) ) {
					$show_resmush = true;
				}
			}
			if ( ! $WpSmush->keep_exif ) {
				//If Keep Exif was set to tru initially, and since it is set to false now
				if ( ! empty( $wp_smush_data['stats']['keep_exif'] ) && $wp_smush_data['stats']['keep_exif'] == 1 ) {
					$show_resmush = true;
				}
			}

			return $show_resmush;
		}

	}//End of Class

}//End Of if class not exists