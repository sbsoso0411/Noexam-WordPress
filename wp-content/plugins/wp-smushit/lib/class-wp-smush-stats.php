<?php
/**
 * @package WP Smush
 * @subpackage Admin
 * @version 2.3
 *
 * @author Umesh Kumar <umesh@incsub.com>
 *
 * @copyright (c) 2016, Incsub (http://incsub.com)
 */
if ( ! class_exists( 'WpSmushStats' ) ) {

	/**
	 * Class WpSmushStats
	 */
	class WpSmushStats {
		function __construct() {
			//Update resize savings
			add_action( 'wp_smush_image_resized', array( $this, 'resize_savings' ) );
		}

		/**
		 * Total Image count
		 * @return int
		 */
		function total_count() {
			global $wpsmushit_admin;

			//Remove the Filters added by WP Media Folder
			$this->remove_wmf_filters();

			$count = 0;

			$counts = wp_count_attachments( $wpsmushit_admin->mime_types );
			foreach ( $wpsmushit_admin->mime_types as $mime ) {
				if ( isset( $counts->$mime ) ) {
					$count += $counts->$mime;
				}
			}

			// send the count
			return $count;
		}

		/**
		 * Optimised images count
		 *
		 * @param bool $return_ids
		 *
		 * @return array|int
		 */
		function smushed_count( $return_ids ) {
			global $wpsmushit_admin;

			//Don't query again, if the variable is already set
			if ( ! $return_ids && ! empty( $wpsmushit_admin->smushed_count ) && $wpsmushit_admin->smushed_count > 0 ) {
				return $wpsmushit_admin->smushed_count;
			}

			$query = array(
				'fields'         => 'ids',
				'post_type'      => 'attachment',
				'post_status'    => 'inherit',
				'post_mime_type' => $wpsmushit_admin->mime_types,
				'order'          => 'ASC',
				'posts_per_page' => - 1,
				'meta_key'       => 'wp-smpro-smush-data',
				'no_found_rows'  => true
			);

			//Remove the Filters added by WP Media Folder
			$this->remove_wmf_filters();

			$results = new WP_Query( $query );

			if ( ! is_wp_error( $results ) && $results->post_count > 0 ) {
				if ( ! $return_ids ) {
					//return Post Count
					return $results->post_count;
				} else {
					//Return post ids
					return $results->posts;
				}
			} else {
				return false;
			}
		}

		/**
		 * Returns/Updates the number of images Super Smushed
		 *
		 * @param string $type media/nextgen, Type of images to get/set the super smushed count for
		 *
		 * @param array $attachments Optional, By default Media attachments will be fetched
		 *
		 * @return array|mixed|void
		 *
		 */
		function super_smushed_count( $type = 'media', $attachments = '' ) {

			if ( 'media' == $type ) {
				$count = $this->media_super_smush_count();
			} else {
				$key = 'wp-smush-super_smushed_nextgen';

				//Flag to check if we need to re-evaluate the count
				$revaluate = false;

				$super_smushed = get_option( $key, false );

				//Check if need to revalidate
				if ( ! $super_smushed || empty( $super_smushed ) || empty( $super_smushed['ids'] ) ) {

					$super_smushed = array(
						'ids' => array()
					);

					$revaluate = true;
				} else {
					$last_checked = $super_smushed['timestamp'];

					$diff = $last_checked - current_time( 'timestamp' );

					//Difference in hour
					$diff_h = $diff / 3600;

					//if last checked was more than 1 hours.
					if ( $diff_h > 1 ) {
						$revaluate = true;
					}
				}
				//Do not Revaluate stats if nextgen attachments are not provided
				if ( 'nextgen' == $type && empty( $attachments ) && $revaluate ) {
					$revaluate = false;
				}

				//Need to scan all the image
				if ( $revaluate ) {
					//Get all the Smushed attachments ids
					$super_smushed_images = $this->get_lossy_attachments( $attachments, false );

					if ( ! empty( $super_smushed_images ) && is_array( $super_smushed_images ) ) {
						//Iterate over all the attachments to check if it's already there in list, else add it
						foreach ( $super_smushed_images as $id ) {
							if ( ! in_array( $id, $super_smushed['ids'] ) ) {
								$super_smushed['ids'][] = $id;
							}
						}
					}

					$super_smushed['timestamp'] = current_time( 'timestamp' );

					update_option( $key, $super_smushed );
				}

				$count = ! empty( $super_smushed['ids'] ) ? count( $super_smushed['ids'] ) : 0;
			}

			return $count;
		}

		/**
		 * Updates the Meta for existing smushed images and retrieves the count of Super Smushed images
		 *
		 * @return int Count of Super Smushed images
		 *
		 */
		function media_super_smush_count( $return_ids = false ) {
			global $wpsmushit_admin;
			$lossy_update = false;
			//Check if we have updated the stats for existing images, One time
			if ( ! $lossy_updated = get_option( WP_SMUSH_PREFIX . 'lossy-updated' ) ) {

				//Get all the smushed attachments
				$attachments = $this->get_lossy_attachments( '', false );
				if ( ! empty( $attachments ) ) {
					foreach ( $attachments as $attachment ) {
						update_post_meta( $attachment, WP_SMUSH_PREFIX . 'lossy', 1 );
					}
				}
			}
			//Get all the attachments with wp-smush-lossy
			$limit         = $wpsmushit_admin->query_limit();
			$get_posts     = true;
			$super_smushed = array();
			$args          = array(
				'fields'                 => 'ids',
				'post_type'              => 'attachment',
				'post_status'            => 'any',
				'post_mime_type'         => $wpsmushit_admin->mime_types,
				'orderby'                => 'ID',
				'order'                  => 'DESC',
				'posts_per_page'         => $limit,
				'offset'                 => 0,
				'meta_query'             => array(
					array(
						'key'   => 'wp-smush-lossy',
						'value' => 1
					)
				),
				'update_post_term_cache' => false,
				'no_found_rows'          => true,
			);
			//Loop Over to get all the attachments
			while ( $get_posts ) {

				//Remove the Filters added by WP Media Folder
				$this->remove_wmf_filters();

				$query = new WP_Query( $args );

				if ( ! empty( $query->post_count ) && sizeof( $query->posts ) > 0 ) {
					//Merge the results
					$super_smushed = array_merge( $super_smushed, $query->posts );

					//Update the offset
					$args['offset'] += $limit;
				} else {
					//If we didn't get any posts from query, set $get_posts to false
					$get_posts = false;
				}

				//If total Count is set, and it is alread lesser than offset, don't query
				if ( ! empty( $this->total_count ) && $this->total_count < $args['offset'] ) {
					$get_posts = false;
				}
			}
			if ( ! $lossy_updated ) {
				update_option( 'wp-smush-lossy-updated', true );
			}

			return $return_ids ? $super_smushed : count( $super_smushed );
		}

		/**
		 * Remove any pre_get_posts_filters added by WP Media Folder plugin
		 */
		function remove_wmf_filters() {
			//remove any filters added b WP media Folder plugin to get the all attachments
			if ( class_exists( 'Wp_Media_Folder' ) ) {
				global $wp_media_folder;
				if ( is_object( $wp_media_folder ) ) {
					remove_filter( 'pre_get_posts', array( $wp_media_folder, 'wpmf_pre_get_posts1' ) );
					remove_filter( 'pre_get_posts', array( $wp_media_folder, 'wpmf_pre_get_posts' ), 0, 1 );
				}
			}
		}

		/**
		 * Get the savings from image resizing, And force update if set to true
		 *
		 * @param bool $force_update , Whether to Re-Calculate all the stats or not
		 *
		 * @param bool $format Format the Bytes in readable format
		 *
		 * @return array|bool|mixed|string Array of {
		 *      'bytes',
		 *      'before_size',
		 *      'after_size'
		 * }
		 *
		 */
		function resize_savings( $force_update = true, $format = false ) {
			$savings = '';

			if ( ! $force_update ) {
				$savings = wp_cache_get( WP_SMUSH_PREFIX . 'resize_savings', 'wp-smush' );
			}
			//If nothing in cache, Calculate it
			if ( empty( $savings ) || $force_update ) {
				global $wpsmushit_admin;
				$savings = array(
					'bytes'     => 0,
					'size_before' => 0,
					'size_after'  => 0,
				);

				//Get the List of resized images
				$resized_images = $this->resize_images();

				//Iterate over them
				foreach ( $resized_images as $id ) {
					$meta = get_post_meta( $id, WP_SMUSH_PREFIX . 'resize_savings', true );
					if ( ! empty( $meta ) && ! empty( $meta['bytes'] ) ) {
						$savings['bytes'] += intval( $meta['bytes'] );
						$savings['size_before'] += intval( $meta['size_before'] );
						$savings['size_after'] += intval( $meta['size_after'] );
					}
				}

				if ( $format ) {
					$savings['bytes'] = $wpsmushit_admin->format_bytes( $savings['bytes'] );
				}

				wp_cache_set( WP_SMUSH_PREFIX . 'resize_savings', $savings, 'wp-smush' );
			}

			return $savings;
		}

		/**
		 * Get all the resized images
		 *
		 * @return array Array of post ids of all the resized images
		 *
		 */
		function resize_images() {
			global $wpsmushit_admin;
			$limit          = $wpsmushit_admin->query_limit();
			$limit          = ! empty( $wpsmushit_admin->total_count ) && $wpsmushit_admin->total_count < $limit ? $wpsmushit_admin->total_count : $limit;
			$get_posts      = true;
			$resized_images = array();
			$args           = array(
				'fields'                 => 'ids',
				'post_type'              => 'attachment',
				'post_status'            => 'inherit',
				'post_mime_type'         => $wpsmushit_admin->mime_types,
				'orderby'                => 'ID',
				'order'                  => 'DESC',
				'posts_per_page'         => $limit,
				'offset'                 => 0,
				'meta_key'               => WP_SMUSH_PREFIX . 'resize_savings',
				'update_post_term_cache' => false,
				'no_found_rows'          => true,
			);
			//Loop Over to get all the attachments
			while ( $get_posts ) {

				//Remove the Filters added by WP Media Folder
				$this->remove_wmf_filters();

				$query = new WP_Query( $args );

				if ( ! empty( $query->post_count ) && sizeof( $query->posts ) > 0 ) {
					//Merge the results
					$resized_images = array_merge( $resized_images, $query->posts );

					//Update the offset
					$args['offset'] += $limit;
				} else {
					//If we didn't get any posts from query, set $get_posts to false
					$get_posts = false;
				}

				//If total Count is set, and it is alread lesser than offset, don't query
				if ( ! empty( $wpsmushit_admin->total_count ) && $wpsmushit_admin->total_count < $args['offset'] ) {
					$get_posts = false;
				}
			}

			return $resized_images;
		}

		/**
		 * Returns the ids and meta which are losslessly compressed
		 *
		 * Called only if the meta key isn't updated for old images, else it is not used
		 *
		 * @return array
		 */
		function get_lossy_attachments( $attachments = '', $return_count = true ) {

			$lossy_attachments = array();
			$count             = 0;

			if ( empty( $attachments ) ) {
				//Fetch all the smushed attachment ids
				$attachments = $this->smushed_count( true );
			}

			//If we dont' have any attachments
			if ( empty( $attachments ) || 0 == count( $attachments ) ) {
				return 0;
			}

			//Check if image is lossless or lossy
			foreach ( $attachments as $attachment ) {

				//Check meta for lossy value
				$smush_data = ! empty( $attachment->smush_data ) ? maybe_unserialize( $attachment->smush_data ) : '';

				//For Nextgen Gallery images
				if ( empty( $smush_data ) && is_array( $attachment ) && ! empty( $attachment['wp_smush'] ) ) {
					$smush_data = ! empty( $attachment['wp_smush'] ) ? $attachment['wp_smush'] : '';
				}

				//Return if not smushed
				if ( empty( $smush_data ) ) {
					continue;
				}

				//if stats not set or lossy is not set for attachment, return
				if ( empty( $smush_data['stats'] ) || ! isset( $smush_data['stats']['lossy'] ) ) {
					continue;
				}

				//Add to array if lossy is not 1
				if ( 1 == $smush_data['stats']['lossy'] ) {
					$count ++;
					if ( ! empty( $attachment->attachment_id ) ) {
						$lossy_attachments[] = $attachment->attachment_id;
					} elseif ( is_array( $attachment ) && ! empty( $attachment['pid'] ) ) {
						$lossy_attachments[] = $attachment['pid'];
					}
				}
			}
			unset( $attachments );

			if ( $return_count ) {
				return $count;
			}

			return $lossy_attachments;
		}
	}

	/**
	 * Initialise class
	 */
	global $wpsmush_stats;
	$wpsmush_stats = new WpSmushStats();
}