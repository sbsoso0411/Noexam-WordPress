<?php
/**
 * @package WP Smush
 * @subpackage Admin
 * @version 1.0
 *
 * @author Saurabh Shukla <saurabh@incsub.com>
 * @author Umesh Kumar <umesh@incsub.com>
 *
 * @copyright (c) 2016, Incsub (http://incsub.com)
 */

//Include Bulk UI
require_once WP_SMUSH_DIR . 'lib/class-wp-smush-ui.php';

//Load Shared UI
if ( ! class_exists( 'WDEV_Plugin_Ui' ) ) {
	require_once WP_SMUSH_DIR . 'assets/shared-ui/plugin-ui.php';
}

if ( ! class_exists( 'WpSmushitAdmin' ) ) {
	/**
	 * Show settings in Media settings and add column to media library
	 *
	 */

	/**
	 * Class WpSmushitAdmin
	 *
	 * @property int $remaining_count
	 * @property int $total_count
	 * @property int $smushed_count
	 * @property int $exceeding_items_count
	 */
	class WpSmushitAdmin extends WpSmush {

		/**
		 * @var array Settings
		 */
		public $settings;

		public $bulk;

		/**
		 * @var Total count of Attachments for Smushing
		 */
		public $total_count;

		/**
		 * @var Smushed attachments out of total attachments
		 */
		public $smushed_count;

		/**
		 * @var Smushed attachments out of total attachments
		 */
		public $remaining_count;

		/**
		 * @var Smushed attachments out of total attachments
		 */
		public $super_smushed;

		public $smushed_attachments = array();

		public $mime_types = array( 'image/jpg', 'image/jpeg', 'image/gif', 'image/png' );

		/**
		 * @array Stores the stats for all the images
		 */
		public $stats;

		public $bulk_ui = '';

		/**
		 * @var int Limit for allowed number of images per bulk request
		 */
		private $max_free_bulk = 50; //this is enforced at api level too

		public $upgrade_url = 'https://premium.wpmudev.org/project/wp-smush-pro/';

		//Stores unsmushed ids
		private $ids = '';

		//Stores all lossless smushed ids
		public $resmush_ids = array();

		/**
		 * @var int Number of attachments exceeding free limit
		 */
		public $exceeding_items_count = 0;

		private $attachments = '';

		/**
		 * Constructor
		 */
		public function __construct() {

			// hook scripts and styles
			add_action( 'admin_init', array( $this, 'register' ) );

			// hook custom screen
			add_action( 'admin_menu', array( $this, 'screen' ) );

			//Handle Smush Bulk Ajax
			add_action( 'wp_ajax_wp_smushit_bulk', array( $this, 'process_smush_request' ) );

			//Handle Smush Single Ajax
			add_action( 'wp_ajax_wp_smushit_manual', array( $this, 'smush_manual' ) );

			//Handle Restore operation
			add_action( 'wp_ajax_smush_restore_image', array( $this, 'restore_image' ) );

			//Handle Restore operation
			add_action( 'wp_ajax_smush_resmush_image', array( $this, 'resmush_image' ) );

			//Handle Restore operation
			add_action( 'wp_ajax_scan_for_resmush', array( $this, 'scan_images' ) );

			add_filter( 'plugin_action_links_' . WP_SMUSH_BASENAME, array(
				$this,
				'settings_link'
			) );
			add_filter( 'network_admin_plugin_action_links_' . WP_SMUSH_BASENAME, array(
				$this,
				'settings_link'
			) );
			//Attachment status, Grid view
			add_filter( 'attachment_fields_to_edit', array( $this, 'filter_attachment_fields_to_edit' ), 10, 2 );

			// Smush Upgrade
			add_action( 'admin_notices', array( $this, 'smush_upgrade' ) );

			// New Features Notice
			add_action( 'admin_notices', array( $this, 'smush_updated' ) );

			//Handle the smush pro dismiss features notice ajax
			add_action( 'wp_ajax_dismiss_upgrade_notice', array( $this, 'dismiss_upgrade_notice' ) );

			//Handle the smush pro dismiss features notice ajax
			add_action( 'wp_ajax_dismiss_welcome_notice', array( $this, 'dismiss_welcome_notice' ) );

			//Handle the smush pro dismiss features notice ajax
			add_action( 'wp_ajax_dismiss_update_info', array( $this, 'dismiss_update_info' ) );

			//Update the Super Smush count, after the smushing
			add_action( 'wp_smush_image_optimised', array( $this, 'update_lists' ), '', 2 );

			//Delete ReSmush list
			add_action( 'wp_ajax_delete_resmush_list', array( $this, 'delete_resmush_list' ), '', 2 );

			add_action( 'admin_init', array( $this, 'init_settings' ) );

			$this->bulk_ui = new WpSmushBulkUi();

		}

		function init_settings() {
			$this->settings = array(
				'auto'      => array(
					'label' => esc_html__( 'Automatically smush my images on upload', 'wp-smushit' ),
					'desc'  => esc_html__( 'When you upload images to the media library, we’ll automatically optimize them.', 'wp-smushit' )
				),
				'keep_exif' => array(
					'label' => esc_html__( 'Preserve image EXIF data', 'wp-smushit' ),
					'desc'  => esc_html__( 'EXIF data stores camera settings, focal length, date, time and location information in image files. EXIF data makes image files larger but if you are a photographer you may want to preserve this information.', 'wp-smushit' )
				),
				'resize'    => array(
					'label' => esc_html__( 'Resize original images', 'wp-smushit' ),
					'desc'  => esc_html__( 'Save a ton of space by not storing over-sized images on your server. Set image maximum width and height and large images will be automatically scaled before being added to the media library. ', 'wp-smushit' )
				),
				'lossy'     => array(
					'label' => esc_html__( 'Super-smush my images', 'wp-smushit' ),
					'desc'  => esc_html__( 'Compress images up to 10x more than regular smush with almost no visible drop in quality.', 'wp-smushit' )
				),
				'original'  => array(
					'label' => esc_html__( 'Include my original full-size images', 'wp-smushit' ),
					'desc'  => esc_html__( 'WordPress crops and resizes every image you upload for embedding on your site. By default, Smush only compresses these cropped and resized images, not your original full-size images. To save space on your server, activate this setting to smush your original images, too. Note: This doesn’t usually improve page speed.', 'wp-smushit' )
				),
				'backup'    => array(
					'label' => esc_html__( 'Make a copy of my original images', 'wp-smushit' ),
					'desc'  => esc_html__( 'Save your original full-size images so you can restore them at any point. Note: Activating this setting will significantly increase the size of your uploads folder by nearly twice as much.', 'wp-smushit' )
				)
			);

			//Show NextGen settings only if Nextgen is installed
			if ( class_exists( 'C_NextGEN_Bootstrap' ) ) {
				$this->settings['nextgen'] = array(
					'label' => esc_html__( 'Enable NextGen Gallery integration', 'wp-smushit' ),
					'desc'  => esc_html__( 'Allow smushing images directly through NextGen Gallery settings.', 'wp-smushit' )
				);
			}
		}

		/**
		 * Adds smush button and status to attachment modal and edit page if it's an image
		 *
		 *
		 * @param array $form_fields
		 * @param WP_Post $post
		 *
		 * @return array $form_fields
		 */
		function filter_attachment_fields_to_edit( $form_fields, $post ) {
			if ( ! wp_attachment_is_image( $post->ID ) ) {
				return $form_fields;
			}
			$form_fields['wp_smush'] = array(
				'label'         => __( 'WP Smush', 'wp-smushit' ),
				'input'         => 'html',
				'html'          => $this->smush_status( $post->ID ),
				'show_in_edit'  => true,
				'show_in_modal' => true,
			);

			return $form_fields;
		}

		/**
		 * Add Bulk option settings page
		 */
		function screen() {
			global $admin_page_suffix;

			$admin_page_suffix = add_media_page( 'Bulk WP Smush', 'WP Smush', 'edit_others_posts', 'wp-smush-bulk', array(
				$this->bulk_ui,
				'ui'
			) );

			//For Nextgen gallery Pages, check later in enqueue function
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );

		}

		/**
		 * Register js and css
		 */
		function register() {

			global $WpSmush;

			wp_register_script( 'wp-smushit-admin-js', WP_SMUSH_URL . 'assets/js/wp-smushit-admin.js', array(
				'jquery'
			), WP_SMUSH_VERSION );

			/* Register Style. */
			wp_register_style( 'wp-smushit-admin-css', WP_SMUSH_URL . 'assets/css/wp-smushit-admin.css', array(), $WpSmush->version );

			//Dismiss Update Info
			$this->dismiss_update_info();
		}

		/**
		 * enqueue js and css
		 */
		function enqueue() {

			global $pagenow;

			$current_screen = get_current_screen();
			$current_page   = $current_screen->base;

			/**
			 * Allows to disable enqueuing smush files on a particular page
			 */
			$enqueue_smush = apply_filters( 'wp_smush_enqueue', true );

			//If we upgrade/install message is dismissed and for pro users
			if ( get_option( 'wp-smush-hide_upgrade_notice' ) || get_site_option( 'wp-smush-hide_upgrade_notice' ) || $this->is_pro() ) {
				//Do not enqueue, unless it is one of the required screen
				if ( ! $enqueue_smush || ( $current_page != 'nggallery-manage-images' && $current_page != 'gallery_page_wp-smush-nextgen-bulk' && $pagenow != 'post.php' && $pagenow != 'post-new.php' && $pagenow != 'upload.php' ) ) {

					return;
				}
			}

			wp_enqueue_script( 'wp-smushit-admin-js' );

			//Style
			wp_enqueue_style( 'wp-smushit-admin-css' );

			$this->load_shared_ui( $current_page );

			//Enqueue Google Fonts for Tooltip On Media Pages, These are loaded by shared UI, but we
			// aren't loading shared UI on media library pages
			if ( ! wp_style_is( 'wdev-plugin-google_fonts', 'enqueued' ) ) {
				wp_enqueue_style(
					'wdev-plugin-google_fonts',
					'https://fonts.googleapis.com/css?family=Roboto+Condensed:400,700|Roboto:400,500,300,300italic'
				);
			}

			// localize translatable strings for js
			$this->localize();
		}

		/**
		 * Localize Translations
		 */
		function localize() {
			global $current_screen;
			$current_page = ! empty( $current_screen ) ? $current_screen->base : '';

			$bulk   = new WpSmushitBulk();
			$handle = 'wp-smushit-admin-js';

			$wp_smush_msgs = array(
				'resmush'       => esc_html__( 'Super-Smush', 'wp-smushit' ),
				'smush_now'     => esc_html__( 'Smush Now', 'wp-smushit' ),
				'error_in_bulk' => esc_html__( '{{errors}} image(s) were skipped due to an error.', 'wp-smushit' ),
				'all_resmushed' => esc_html__( 'All images are fully optimised.', 'wp-smushit' ),
				'restore'       => esc_html__( "Restoring image..", "wp-smushit" ),
				'smushing'      => esc_html__( "Smushing image..", "wp-smushit" ),
				'checking'      => esc_html__( "Checking images..", "wp-smushit" ),
			);

			wp_localize_script( $handle, 'wp_smush_msgs', $wp_smush_msgs );

			//Load the stats on selected screens only
			if ( $current_page == 'media_page_wp-smush-bulk' ) {

				//Setup all the stats
				$this->setup_global_stats();

				//Get attachments if all the images are not smushed
				$this->attachments = $this->remaining_count > 0 ? $bulk->get_attachments() : array();

				//Localize smushit_ids variable, if there are fix number of ids
				$this->ids = ! empty( $_REQUEST['ids'] ) ? array_map( 'intval', explode( ',', $_REQUEST['ids'] ) ) : $this->attachments;

				//Get resmush list, If we have a resmush list already, localize those ids
				if ( $resmush_ids = get_option( "wp-smush-resmush-list" ) ) {

					//get the attachments, and get lossless count
					$this->resmush_ids = $resmush_ids;

				}

				//Array of all smushed, unsmushed and lossless ids
				$data = array(
					'count_smushed' => $this->smushed_count,
					'count_total'   => $this->total_count,
					'unsmushed'     => $this->ids,
					'resmush'       => $this->resmush_ids,
				);
			} else {
				$data = array(
					'count_smushed' => '',
					'count_total'   => '',
					'unsmushed'     => '',
					'resmush'       => '',
				);

			}

			$data['resize_sizes'] = $this->get_max_image_dimensions();

			$data['timeout'] = WP_SMUSH_TIMEOUT * 1000; //Convert it into ms

			wp_localize_script( 'wp-smushit-admin-js', 'wp_smushit_data', $data );


		}

		/**
		 * Runs the expensive queries to get our global smush stats
		 *
		 * @param bool $force_update Whether to Force update the Global Stats or not
		 *
		 */
		function setup_global_stats( $force_update = false ) {
			global $wpsmush_stats;
			$this->total_count         = $wpsmush_stats->total_count();
			$this->smushed_attachments = $wpsmush_stats->smushed_count( true );
			$this->smushed_count       = !empty( $this->smushed_attachments ) ? count( $this->smushed_attachments ) : 0;
			$this->remaining_count     = $this->remaining_count();
			$this->stats               = $this->global_stats( $force_update );
		}

		/**
		 * Check if form is submitted and process it
		 *
		 * @return null
		 */
		function process_options() {

			if ( ! is_admin() ) {
				return false;
			}

			// we aren't saving options
			if ( ! isset( $_POST['wp_smush_options_nonce'] ) ) {
				return false;
			}

			// the nonce doesn't pan out
			if ( ! wp_verify_nonce( $_POST['wp_smush_options_nonce'], 'save_wp_smush_options' ) ) {
				return false;
			}

			//Store that we need not redirect again on plugin activation
			update_site_option( 'wp-smush-hide_smush_welcome', true );

			// var to temporarily assign the option value
			$setting = null;

			//Store Option Name and their values in an array
			$settings = array();


			// process each setting and update options
			foreach ( $this->settings as $name => $text ) {

				// formulate the index of option
				$opt_name = WP_SMUSH_PREFIX . $name;

				// get the value to be saved
				$setting = isset( $_POST[ $opt_name ] ) ? 1 : 0;

				$settings[ $opt_name ] = $setting;

				// update the new value
				update_option( $opt_name, $setting );

				// unset the var for next loop
				unset( $setting );
			}

			//Update Resize width and height settings if set
			$resize_sizes['width']  = isset( $_POST['wp-smush-resize_width'] ) ? intval( $_POST['wp-smush-resize_width'] ) : 0;
			$resize_sizes['height'] = isset( $_POST['wp-smush-resize_height'] ) ? intval( $_POST['wp-smush-resize_height'] ) : 0;

			// update the resize sizes
			update_option( WP_SMUSH_PREFIX . 'resize_sizes', $resize_sizes );

			//Store the option in table
			update_option( 'wp-smush-settings_updated', 1 );

			//Delete Show Resmush option
			if ( isset( $_POST['wp-smush-keep_exif'] ) && ! isset( $_POST['wp-smush-original'] ) && ! isset( $_POST['wp-smush-lossy'] ) ) {
				//@todo: Update Resmush ids
			}

		}

		/**
		 * Returns number of images of larger than 1Mb size
		 *
		 * @param bool $force_update Whether to Force update the Global Stats or not
		 *
		 * @return int
		 */
		function get_exceeding_items_count( $force_update = false ) {
			$count = wp_cache_get( 'exceeding_items', 'wp_smush' );
			if ( ! $count || $force_update ) {
				$count = 0;
				//Check images bigger than 1Mb, used to display the count of images that can't be smushed
				foreach ( $this->attachments as $attachment ) {
					if ( file_exists( get_attached_file( $attachment ) ) ) {
						$size = filesize( get_attached_file( $attachment ) );
					}
					if ( empty( $size ) || ! ( ( $size / WP_SMUSH_MAX_BYTES ) > 1 ) ) {
						continue;
					}
					$count ++;
				}
				wp_cache_set( 'exceeding_items', $count, 'wp_smush', 3000 );
			}

			return $count;
		}

		/**
		 * Processes the Smush request and sends back the next id for smushing
		 */
		function process_smush_request() {

			global $WpSmush, $wpsmush_stats;

			// turn off errors for ajax result
			@error_reporting( 0 );

			$should_continue = true;

			if ( empty( $_REQUEST['attachment_id'] ) ) {
				wp_send_json_error( 'missing id' );
			}

			if ( ! $this->is_pro() ) {
				//Free version bulk smush, check the transient counter value
				$should_continue = $this->check_bulk_limit();
			}

			//If the bulk smush needs to be stopped
			if ( ! $should_continue ) {
				wp_send_json_error(
					array(
						'error'    => 'bulk_request_image_limit_exceeded',
						'continue' => false
					)
				);
			}

			$send_error = false;

			$attachment_id = (int) ( $_REQUEST['attachment_id'] );

			$original_meta = wp_get_attachment_metadata( $attachment_id, true );

			//Resize the dimensions of the image
			/**
			 * Filter whether the existing image should be resized or not
			 *
			 * @since 2.3
			 *
			 * @param bool $should_resize , Set to True by default
			 *
			 * @param $attachment_id Image Attachment ID
			 *
			 */
			if ( $should_resize = apply_filters( 'wp_smush_resize_media_image', true, $attachment_id ) ) {
				$updated_meta  = $this->resize_image( $attachment_id, $original_meta );
				$original_meta = ! empty( $updated_meta ) ? $updated_meta : $original_meta;
				wp_update_attachment_metadata( $attachment_id, $original_meta );
			}

			$smush = $WpSmush->resize_from_meta_data( $original_meta, $attachment_id );

			//Get the updated Global Stats
			$this->setup_global_stats( true );

			$stats = $this->stats;

			$stats['total'] = $wpsmush_stats->total_count();

			if ( is_wp_error( $smush ) ) {

				$send_error = true;

				$error = $smush->get_error_message();
				//Check for timeout error and suggest to filter timeout
				if ( strpos( $error, 'timed out' ) ) {
					$error = '<p class="wp-smush-error-message">' . esc_html__( "Smush request timed out, You can try setting a higher value for `WP_SMUSH_API_TIMEOUT`.", "wp-smushit" ) . '</p>';
				}
			} else {
				//Check if a resmush request, update the resmush list
				if ( ! empty( $_REQUEST['is_bulk_resmush'] ) && 'false' != $_REQUEST['is_bulk_resmush'] && $_REQUEST['is_bulk_resmush'] ) {
					$this->update_resmush_list( $attachment_id );
				}
			}
			$resmush_count = empty( $this->resmush_ids ) ? count( $this->resmush_ids = get_option( "wp-smush-resmush-list" ) ) : count( $this->resmush_ids );

			$stats['smushed'] = !empty( $this->resmush_ids ) ? $this->smushed_count - $resmush_count : $this->smushed_count;

			if ( $WpSmush->lossy_enabled ) {
				$stats['super_smushed'] = $wpsmush_stats->super_smushed_count();
			}

			$stats['tooltip_text'] = ! empty( $stats['total_images'] ) ? sprintf( esc_html__( "%d images", "wp-smushit" ), $stats['total_images'] ) : '';

			//Send ajax response
			$send_error ? wp_send_json_error( array( 'stats' => $stats, 'error_msg' => $error ) ) : wp_send_json_success( array( 'stats' => $stats ) );

		}

		/**
		 * Handle the Ajax request for smushing single image
		 *
		 * @uses smush_single()
		 */
		function smush_manual() {

			// turn off errors for ajax result
			@error_reporting( 0 );

			if ( ! current_user_can( 'upload_files' ) ) {
				wp_die( __( "You don't have permission to work with uploaded files.", 'wp-smushit' ) );
			}

			if ( ! isset( $_GET['attachment_id'] ) ) {
				wp_die( __( 'No attachment ID was provided.', 'wp-smushit' ) );
			}

			//Pass on the attachment id to smush single function
			$this->smush_single( $_GET['attachment_id'] );
		}

		/**
		 * Smush single images
		 *
		 * @param $attachment_id
		 * @param bool $return Return/Echo the stats
		 *
		 * @return array|string|void
		 */
		function smush_single( $attachment_id, $return = false ) {

			global $WpSmush;

			$attachment_id = absint( (int) ( $attachment_id ) );

			$original_meta = wp_get_attachment_metadata( $attachment_id );

			//Send image for resizing
			$updated_meta = $this->resize_image( $attachment_id, $original_meta );

			$original_meta = ! empty( $updated_meta ) ? $updated_meta : $original_meta;

			//Update the details, as soon as we are done with resizing
			wp_update_attachment_metadata( $attachment_id, $original_meta );

			//Smush the image
			$smush = $WpSmush->resize_from_meta_data( $original_meta, $attachment_id );

			//Get the button status
			$status = $WpSmush->set_status( $attachment_id, false, true );

			//Send Json response if we are not suppose to return the results

			/** Send stats **/
			if ( is_wp_error( $smush ) ) {
				if ( $return ) {
					return array( 'error' => $smush->get_error_message() );
				} else {
					wp_send_json_error( array( 'error_msg' => '<p class="wp-smush-error-message">' . $smush->get_error_message() . '</p>' ) );
				}
			} else {
				if ( $return ) {
					return $status;
				} else {
					wp_send_json_success( $status );
				}
			}
		}

		/**
		 * Check bulk sent count, whether to allow further smushing or not
		 *
		 * @return bool
		 */
		function check_bulk_limit( $reset = false ) {

			$transient_name = WP_SMUSH_PREFIX . 'bulk_sent_count';

			//Do not go through this, if we need to reset
			if ( ! $reset ) {
				$bulk_sent_count = get_transient( $transient_name );

				//If bulk sent count is not set
				if ( false === $bulk_sent_count ) {

					//start transient at 0
					set_transient( $transient_name, 1, 200 );

					return true;

				} else if ( $bulk_sent_count < $this->max_free_bulk ) {

					//If lte $this->max_free_bulk images are sent, increment
					set_transient( $transient_name, $bulk_sent_count + 1, 200 );

					return true;

				} else { //Bulk sent count is set and greater than $this->max_free_bulk

					$reset = true;

				}
			}

			//Reset the transient
			if ( $reset ) {
				//clear it and return false to stop the process
				set_transient( $transient_name, 0, 60 );

				return false;
			}
		}

		/**
		 * Returns remaining count
		 *
		 * @return int
		 */
		function remaining_count() {
			return $this->total_count - $this->smushed_count;
		}

		/**
		 * Display Thumbnails, if bulk action is choosen
		 *
		 * @Note: Not in use right now, Will use it in future for Media Bulk action
		 *
		 */
		function selected_ui( $send_ids, $received_ids ) {
			if ( empty( $received_ids ) ) {
				return;
			}

			?>
			<div id="select-bulk" class="wp-smush-bulk-wrap">
				<p>
					<?php
					printf(
						__(
							'<strong>%d of %d images</strong> were sent for smushing:',
							'wp-smushit'
						),
						count( $send_ids ), count( $received_ids )
					);
					?>
				</p>
				<ul id="wp-smush-selected-images">
					<?php
					foreach ( $received_ids as $attachment_id ) {
						$this->attachment_ui( $attachment_id );
					}
					?>
				</ul>
			</div>
			<?php
		}

		/**
		 * Display the bulk smushing button
		 *
		 * @param bool $resmush
		 *
		 * @param bool $return Whether to return the button content or print it
		 *
		 * @return Returns or Echo the content
		 */
		function setup_button( $resmush = false, $return = false ) {
			$button   = $this->button_state( $resmush );
			$disabled = ! empty( $button['disabled'] ) ? ' disabled="disabled"' : '';
			$content  = '<button class="button button-primary ' . $button['class'] . '"
			        name="smush-all" ' . $disabled . '>
				<span>' . $button['text'] . '</span>
			</button>';

			//If We need to return the content
			if ( $return ) {
				return $content;
			}

			echo $content;
		}

		/**
		 * Get all the attachment meta, sum up the stats and return
		 *
		 * @param bool $force_update , Whether to forcefully update the Cache
		 *
		 * @return array|bool|mixed
		 */
		function global_stats( $force_update = false ) {

			if ( ! $force_update && $stats = wp_cache_get( 'global_stats', 'wp_smush' ) ) {
				if ( ! empty( $stats ) ) {
					return $stats;
				}
			}

			global $wpdb, $WpSmush, $wpsmush_stats;

			$smush_data = array(
				'size_before' => 0,
				'size_after'  => 0,
				'percent'     => 0,
				'human'       => 0
			);

			/**
			 * Allows to set a limit of mysql query
			 * Default value is 2000
			 */
			$limit      = $this->query_limit();
			$offset     = 0;
			$query_next = true;

			while ( $query_next ) {

				$global_data = $wpdb->get_col( $wpdb->prepare( "SELECT meta_value FROM $wpdb->postmeta WHERE meta_key=%s LIMIT $offset, $limit", "wp-smpro-smush-data" ) );

				if ( ! empty( $global_data ) ) {
					$smush_data['count'] = 0;
					$smush_data['total_images'] = 0;
					foreach ( $global_data as $data ) {
						$data = maybe_unserialize( $data );
						if ( ! empty( $data['stats'] ) ) {
							$smush_data['count'] += 1;
							$smush_data['total_images'] += ! empty( $data['sizes'] ) ? count( $data['sizes'] ) : 0;
							$smush_data['size_before'] += ! empty( $data['stats']['size_before'] ) ? (int) $data['stats']['size_before'] : 0;
							$smush_data['size_after'] += ! empty( $data['stats']['size_after'] ) ? (int) $data['stats']['size_after'] : 0;
						}
					}
				}

				$smush_data['bytes'] = $smush_data['size_before'] - $smush_data['size_after'];

				//Update the offset
				$offset += $limit;

				//Compare the Offset value to total images
				if ( ! empty( $this->total_count ) && $this->total_count < $offset ) {
					$query_next = false;
				} elseif ( ! $global_data ) {
					//If we didn' got any results
					$query_next = false;
				}

			}

			$resize_savings = $wpsmush_stats->resize_savings( false );

			$smush_data['resize_savings'] = ! empty( $resize_savings['bytes'] ) ? $resize_savings['bytes'] : 0;

			if ( ! isset( $smush_data['bytes'] ) || $smush_data['bytes'] < 0 ) {
				$smush_data['bytes'] = 0;
			}

			//Add the resize savings to bytes
			$smush_data['bytes'] += $smush_data['resize_savings'];
			$smush_data['size_before'] += $resize_savings['size_before'];
			$smush_data['size_after'] += $resize_savings['size_after'];

			//Add the size before and after

			$smush_data['resize_savings'] = $this->format_bytes( $smush_data['resize_savings'] );

			if ( $smush_data['size_before'] > 0 ) {
				$smush_data['percent'] = ( $smush_data['bytes'] / $smush_data['size_before'] ) * 100;
			}

			//Round off precentage
			$smush_data['percent'] = round( $smush_data['percent'], 2 );

			$smush_data['human'] = $WpSmush->format_bytes( $smush_data['bytes'] );

			//Update Cache
			wp_cache_set( 'smush_global_stats', $smush_data, '', DAY_IN_SECONDS );

			return $smush_data;
		}

		/**
		 * Returns Bulk smush button id and other details, as per if bulk request is already sent or not
		 *
		 * @param $resmush
		 *
		 * @return array
		 */

		private function button_state( $resmush ) {
			$button = array(
				'cancel' => false,
			);
			if ( $this->is_pro() && $resmush ) {

				$button['text']  = __( 'Bulk Smush Now', 'wp-smushit' );
				$button['class'] = 'wp-smush-button wp-smush-resmush wp-smush-all';

			} else {

				// if we have nothing left to smush, disable the buttons
				if ( $this->smushed_count === $this->total_count ) {
					$button['text']     = __( 'All Done!', 'wp-smushit' );
					$button['class']    = 'wp-smush-finished disabled wp-smush-finished';
					$button['disabled'] = 'disabled';

				} else {

					$button['text']  = __( 'Bulk Smush Now', 'wp-smushit' );
					$button['class'] = 'wp-smush-button';

				}
			}

			return $button;
		}

		/**
		 * Get the smush button text for attachment
		 *
		 * @param $id Attachment ID for which the Status has to be set
		 *
		 * @return string
		 */
		function smush_status( $id ) {
			$response = trim( $this->set_status( $id, false ) );

			return $response;
		}


		/**
		 * Adds a smushit pro settings link on plugin page
		 *
		 * @param $links
		 *
		 * @return array
		 */
		function settings_link( $links ) {

			$settings_page = admin_url( 'upload.php?page=wp-smush-bulk' );
			$settings      = '<a href="' . $settings_page . '">' . __( 'Settings', 'wp-smushit' ) . '</a>';

			//Added a fix for weird warning in multisite, "array_unshift() expects parameter 1 to be array, null given"
			if ( ! empty( $links ) ) {
				array_unshift( $links, $settings );
			} else {
				$links = array( $settings );
			}

			return $links;
		}

		/**
		 * Shows Notice for free users, displays a discount coupon
		 */
		function smush_upgrade() {

			//Return, If a pro user, or not super admin, or don't have the admin privilleges
			if ( $this->is_pro() || ! current_user_can( 'edit_others_posts' ) || ! is_super_admin() ) {
				return;
			}

			//No need to show it on bulk smush
			if ( isset( $_GET['page'] ) && 'wp-smush-bulk' == $_GET['page'] ) {
				return;
			}

			//Return if notice is already dismissed
			if ( get_option( 'wp-smush-hide_upgrade_notice' ) || get_site_option( 'wp-smush-hide_upgrade_notice' ) ) {
				return;
			}

			$install_type = get_site_option( 'wp-smush-install-type', false );

			if ( ! $install_type ) {
				if ( $this->smushed_count > 0 ) {
					$install_type = 'existing';
				} else {
					$install_type = 'new';
				}
				update_site_option( 'wp-smush-install-type', $install_type );
			}

			//Container Header
			echo $this->bulk_ui->installation_notice();
		}

		/**
		 * Get the smushed attachments from the database, except gif
		 *
		 * @global object $wpdb
		 *
		 * @return object query results
		 */
		function get_smushed_attachments() {

			global $wpdb, $wpsmush_stats;

			$allowed_images = "( 'image/jpeg', 'image/jpg', 'image/png' )";

			$limit      = $this->query_limit();
			$offset     = 0;
			$query_next = true;

			while ( $query_next ) {
				// get the attachment id, smush data
				$sql = "SELECT p.ID as attachment_id, p.post_mime_type as type, ms.meta_value as smush_data"
				       . " FROM $wpdb->posts as p"
				       . " LEFT JOIN $wpdb->postmeta as ms"
				       . " ON (p.ID= ms.post_id AND ms.meta_key='wp-smpro-smush-data')"
				       . " WHERE"
				       . " p.post_type='attachment'"
				       . " AND p.post_mime_type IN " . $allowed_images
				       . " ORDER BY p . ID DESC"
				       // add a limit
				       . " LIMIT " . $limit;
				$results = $wpdb->get_results( $sql );

				//Update the offset
				$offset += $limit;
				if ( $wpsmush_stats->total_count() && $wpsmush_stats->total_count() < $offset ) {
					$query_next = false;
				} else if ( ! $results || empty( $results ) ) {
					$query_next = false;
				}
			}

			return $results;
		}

		/**
		 * Store a key/value to hide the smush features on bulk page
		 */
		function dismiss_welcome_notice() {
			update_site_option( 'wp-smush-hide_smush_welcome', 1 );
			wp_send_json_success();
		}

		/**
		 * Store a key/value to hide the smush features on bulk page
		 */
		function dismiss_upgrade_notice( $ajax = true ) {
			update_site_option( 'wp-smush-hide_upgrade_notice', 1 );
			//No Need to send json response for other requests
			if ( $ajax ) {
				wp_send_json_success();
			}
		}

		/**
		 * Remove the Update info
		 *
		 * @param bool $remove_notice
		 *
		 */
		function dismiss_update_info( $remove_notice = false ) {

			//From URL arg
			if ( isset( $_GET['dismiss_smush_update_info'] ) && 1 == $_GET['dismiss_smush_update_info'] ) {
				$remove_notice = true;
			}

			//From Ajax
			if( !empty( $_REQUEST['action'] ) && 'dismiss_update_info' == $_REQUEST['action']) {
				$remove_notice = true;
			}

			//Update Db
			if( $remove_notice ) {
				update_site_option( 'wp-smush-hide_update_info', 1 );
			}

		}

		/**
		 * Restore the image and its sizes from backup
		 */
		function restore_image( $attachment = '', $resp = true ) {

			if ( empty( $attachment ) ) {
				//Check Empty fields
				if ( empty( $_POST['attachment_id'] ) || empty( $_POST['_nonce'] ) ) {
					wp_send_json_error( array(
						'error'   => 'empty_fields',
						'message' => esc_html__( "Error in processing restore action, Fields empty.", "wp-smushit" )
					) );
				}
				//Check Nonce
				if ( ! wp_verify_nonce( $_POST['_nonce'], "wp-smush-restore-" . $_POST['attachment_id'] ) ) {
					wp_send_json_error( array(
						'error'   => 'empty_fields',
						'message' => esc_html__( "Image not restored, Nonce verification failed.", "wp-smushit" )
					) );
				}
			}

			//Store the restore success/failure for all the sizes
			$restored = array();

			//Process Now
			$image_id = empty( $attachment ) ? absint( (int) $_POST['attachment_id'] ) : $attachment;

			//Restore Full size -> get other image sizes -> restore other images

			//Get the Original Path
			$file_path = get_attached_file( $image_id );

			//Get the backup path
			$backup_name = $this->get_image_backup_path( $file_path );

			//If file exists, corresponding to our backup path
			if ( file_exists( $backup_name ) ) {
				//Restore
				$restored[] = @copy( $backup_name, $file_path );

				//Delete the backup
				@unlink( $backup_name );
			} elseif ( file_exists( $file_path . '_backup' ) ) {
				//Restore from other backups
				$restored[] = @copy( $file_path . '_backup', $file_path );
			}

			//Get other sizes and restore
			//Get attachment data
			$attachment_data = wp_get_attachment_metadata( $image_id );

			//Get the sizes
			$sizes = ! empty( $attachment_data['sizes'] ) ? $attachment_data['sizes'] : '';

			//Loop on images to restore them
			foreach ( $sizes as $size ) {
				//Get the file path
				if ( empty( $size['file'] ) ) {
					continue;
				}

				//Image Path and Backup path
				$image_size_path  = path_join( dirname( $file_path ), $size['file'] );
				$image_bckup_path = $this->get_image_backup_path( $image_size_path );

				//Restore
				if ( file_exists( $image_bckup_path ) ) {
					$restored[] = @copy( $image_bckup_path, $image_size_path );
					//Delete the backup
					@unlink( $image_bckup_path );
				} elseif ( file_exists( $image_size_path . '_backup' ) ) {
					$restored[] = @copy( $image_size_path . '_backup', $image_size_path );
				}
			}
			//If any of the image is restored, we count it as success
			if ( in_array( true, $restored ) ) {

				//Remove the Meta, And send json success
				delete_post_meta( $image_id, $this->smushed_meta_key );

				//Get the Button html without wrapper
				$button_html = $this->set_status( $image_id, false, false, false );

				if ( $resp ) {
					wp_send_json_success( array( 'button' => $button_html ) );
				} else {
					return true;
				}
			}
			if ( $resp ) {
				wp_send_json_error( array( 'message' => '<div class="wp-smush-error">' . __( "Unable to restore image", "wp-smushit" ) . '</div>' ) );
			}

			return false;
		}

		/**
		 * Restore the image and its sizes from backup
		 *
		 * @uses smush_single()
		 *
		 */
		function resmush_image() {

			//Check Empty fields
			if ( empty( $_POST['attachment_id'] ) || empty( $_POST['_nonce'] ) ) {
				wp_send_json_error( array(
					'error'   => 'empty_fields',
					'message' => '<div class="wp-smush-error">' . esc_html__( "Image not smushed, fields empty.", "wp-smushit" ) . '</div>'
				) );
			}
			//Check Nonce
			if ( ! wp_verify_nonce( $_POST['_nonce'], "wp-smush-resmush-" . $_POST['attachment_id'] ) ) {
				wp_send_json_error( array(
					'error'   => 'empty_fields',
					'message' => '<div class="wp-smush-error">' . esc_html__( "Image couldn't be smushed as the nonce verification failed, try reloading the page.", "wp-smushit" ) . '</div>'
				) );
			}

			$image_id = intval( $_POST['attachment_id'] );

			$smushed = $this->smush_single( $image_id, true );

			//If any of the image is restored, we count it as success
			if ( ! empty( $smushed['status'] ) ) {

				//Send button content
				wp_send_json_success( array( 'button' => $smushed['status'] . $smushed['stats'] ) );

			} elseif ( ! empty( $smushed['error'] ) ) {

				//Send Error Message
				wp_send_json_error( array( 'message' => '<div class="wp-smush-error">' . __( "Unable to smush image", "wp-smushit" ) . '</div>' ) );

			}
		}

		/**
		 * Scans all the smushed attachments to check if they need to be resmushed as per the
		 * current settings, as user might have changed one of the configurations "Lossy", "Keep Original", "Preserve Exif"
		 */
		function scan_images() {

			global $WpSmush, $wpsmushnextgenadmin, $wpsmush_stats;

			check_ajax_referer( 'save_wp_smush_options', 'wp_smush_options_nonce' );

			$resmush_list = array();

			//Default Notice, to be displayed at the top of page
			//Show a message, at the top
			$message = esc_html__( 'Yay! All images are optimised as per your current settings.', 'wp-smushit' );
			$resp    = '<div class="wp-smush-notice wp-smush-resmush-message" tabindex="0"><i class="dev-icon dev-icon-tick"></i> ' . $message . '
				<i class="dev-icon dev-icon-cross"></i>
				</div>';

			//Scanning for NextGen or Media Library
			$type = isset( $_REQUEST['type'] ) ? sanitize_text_field( $_REQUEST['type'] ) : '';

			//If a user manually runs smush check
			$return_ui = isset( $_REQUEST['get_ui'] ) && 'true' == $_REQUEST['get_ui'] ? true : false;

			//Save Settings
			$this->process_options();

			//Update the variables
			$WpSmush->initialise();

			//Logic: If none of the required settings is on, don't need to resmush any of the images
			//We need at least one of these settings to be on, to check if any of the image needs resmush
			//Allow to smush Upfront images as well
			$upfront_active = class_exists( 'Upfront' );

			//Initialize Media Library Stats
			if ( 'nextgen' != $type && empty( $this->remaining_count ) ) {
				$this->setup_global_stats();
			}

			//Intialize NextGen Stats
			if ( 'nextgen' == $type && is_object( $wpsmushnextgenadmin ) && empty( $wpsmushnextgenadmin->remaining_count ) ) {
				$wpsmushnextgenadmin->setup_stats();
			}

			$key = 'nextgen' == $type ? 'wp-smush-nextgen-resmush-list' : 'wp-smush-resmush-list';

			$remaining_count = 'nextgen' == $type ? $wpsmushnextgenadmin->remaining_count : $this->remaining_count;

			if ( 0 == $remaining_count && ! $WpSmush->lossy_enabled && ! $WpSmush->smush_original && $WpSmush->keep_exif && ! $upfront_active ) {
				delete_option( $key );
				wp_send_json_success( array( 'notice' => $resp ) );
			}

			//Set to empty by default
			$ajax_response = '';

			//Get Smushed Attachments
			if ( 'nextgen' != $type ) {

				//Get list of Smushed images
				$attachments = ! empty( $this->smushed_attachments ) ? $this->smushed_attachments : $wpsmush_stats->smushed_count( true );
			} else {
				global $wpsmushnextgenstats;

				//Get smushed attachments list from nextgen class, We get the meta as well
				$attachments = $wpsmushnextgenstats->get_ngg_images();

			}

			//Check if any of the smushed image needs to be resmushed
			if ( ! empty( $attachments ) && is_array( $attachments ) ) {
				foreach ( $attachments as $attachment_k => $attachment ) {

					//For NextGen we get the metadata in the attachment data itself
					if ( ! empty( $attachment['wp_smush'] ) ) {
						$smush_data = $attachment['wp_smush'];
					} else {
						//Check the current settings, and smush data for the image
						$smush_data = get_post_meta( $attachment, $this->smushed_meta_key, true );
					}

					if ( ! empty( $smush_data['stats'] ) ) {

						//If we need to optmise losslessly, add to resmush list
						$smush_lossy = $WpSmush->lossy_enabled && ! $smush_data['stats']['lossy'];

						//If we need to strip exif, put it in resmush list
						$strip_exif = ! $WpSmush->keep_exif && isset( $smush_data['stats']['keep_exif'] ) && ( 1 == $smush_data['stats']['keep_exif'] );

						//If Original image needs to be smushed
						$smush_original = $WpSmush->smush_original && empty( $smush_data['sizes']['full'] );

						if ( $smush_lossy || $strip_exif || $smush_original ) {
							$resmush_list[] = 'nextgen' == $type ? $attachment_k : $attachment;
							continue;
						}
					}
				}

				//Check for Upfront images that needs to be smushed
				if ( $upfront_active && 'nextgen' != $type ) {
					$upfront_attachments = $this->get_upfront_images( $resmush_list );
					if ( ! empty( $upfront_attachments ) && is_array( $upfront_attachments ) ) {
						foreach ( $upfront_attachments as $u_attachment_id ) {
							if ( ! in_array( $u_attachment_id, $resmush_list ) ) {
								//Check if not smushed
								$upfront_images = get_post_meta( $u_attachment_id, 'upfront_used_image_sizes', true );
								if ( ! empty( $upfront_images ) && is_array( $upfront_images ) ) {
									//Iterate over all the images
									foreach ( $upfront_images as $image ) {
										//If any of the element image is not smushed, add the id to resmush list
										//and skip to next image
										if ( empty( $image['is_smushed'] ) || 1 != $image['is_smushed'] ) {
											$resmush_list[] = $u_attachment_id;
											break;
										}
									}
								}
							}
						}
					}
				}//End Of Upfront loop

				//Store the resmush list in Options table
				update_option( $key, $resmush_list );
			}

			//Delete resmush list if empty
			if ( empty( $resmush_list ) ) {
				//Delete the resmush list
				delete_option( $key );
			}

			//Return the Remsmush list and UI to be appended to Bulk Smush UI
			if ( $return_ui ) {
				if ( 'nextgen' != $type ) {
					//Set the variables
					$this->resmush_ids = $resmush_list;

					$resmush_count =  count( $this->resmush_ids );

					$smushed = !empty( $this->resmush_ids ) ? $this->smushed_count - $resmush_count : $this->smushed_count;

				} else {
					//To avoid the php warning
					$wpsmushnextgenadmin->resmush_ids = $resmush_list;
				}

				if ( ( $count = count( $resmush_list ) ) > 0 || $this->remaining_count > 0 ) {

					if ( $count ) {
						$show = true;

						$count += 'nextgen' == $type ? $wpsmushnextgenadmin->remaining_count : $this->remaining_count;

						$ajax_response = $this->bulk_ui->bulk_resmush_content( $count, $show );
					}
				}
			}

			if ( ! empty( $resmush_list ) || $remaining_count > 0 ) {
				$message = sprintf( esc_html__( "You have images that need smushing. %sBulk smush now!%s", "wp-smushit" ), '<a href="#" class="wp-smush-trigger-bulk">', '</a>' );
				$resp    = '<div class="wp-smush-notice wp-smush-resmush-message wp-smush-resmush-pending" tabindex="0"><i class="dev-icon dev-icon-tick"></i> ' . $message . '
							<i class="dev-icon dev-icon-cross"></i>
						</div>';
			}

			//If there is a Ajax response return it, else return null
			$return           = ! empty( $ajax_response ) ? array(
				"resmush_ids" => $resmush_list,
				"content"     => $ajax_response
			) : '';

			//Include the count
			if( !empty( $count) && $count ) {
				$return['count'] = $count;
			}

			$return['notice']      = $resp;
			$return['super_smush'] = $WpSmush->lossy_enabled;
			if ( $WpSmush->lossy_enabled && 'nextgen' == $type ) {
				$ss_count                    = $wpsmush_stats->super_smushed_count( 'nextgen', $wpsmushnextgenstats->get_ngg_images( 'smushed' ) );
				$return['super_smush_stats'] = sprintf( '<strong><span class="smushed-count">%d</span>/%d</strong>', $ss_count, $wpsmushnextgenadmin->total_count );
			}

			wp_send_json_success( $return );

		}

		/**
		 * Remove the given attachment id from resmush list and updates it to db
		 *
		 * @param $attachment_id
		 * @param string $mkey
		 *
		 */
		function update_resmush_list( $attachment_id, $mkey = 'wp-smush-resmush-list' ) {
			$resmush_list = get_option( $mkey );

			//If there are any items in the resmush list, Unset the Key
			if ( ! empty( $resmush_list ) && count( $resmush_list ) > 0 ) {
				$key = array_search( $attachment_id, $resmush_list );
				if ( $resmush_list ) {
					unset( $resmush_list[ $key ] );
				}
				$resmush_list = array_values( $resmush_list );
			}

			//If Resmush List is empty
			if ( empty( $resmush_list ) || 0 == count( $resmush_list ) ) {
				//Delete resmush list
				delete_option( $mkey );
			} else {
				update_option( $mkey, $resmush_list );
			}
		}

		/**
		 * Get the attachment ids with Upfront images
		 *
		 * @param array $skip_ids
		 *
		 * @return array|bool
		 */
		function get_upfront_images( $skip_ids = array() ) {

			$query = array(
				'fields'         => 'ids',
				'post_type'      => 'attachment',
				'post_status'    => 'any',
				'post_mime_type' => $this->mime_types,
				'order'          => 'ASC',
				'posts_per_page' => - 1,
				'meta_key'       => 'upfront_used_image_sizes',
				'no_found_rows'  => true
			);

			//Skip all the ids which are already in resmush list
			if ( ! empty( $skip_ids ) && is_array( $skip_ids ) ) {
				$query['post__not_in'] = $skip_ids;
			}

			$results = new WP_Query( $query );

			if ( ! is_wp_error( $results ) && $results->post_count > 0 ) {
				return $results->posts;
			} else {
				return false;
			}
		}

		/**
		 * Returns current user name to be displayed
		 * @return string
		 */
		function get_user_name() {
			//Get username
			$current_user = wp_get_current_user();
			$name         = ! empty( $current_user->first_name ) ? $current_user->first_name : $current_user->display_name;

			return $name;
		}

		/**
		 * Format Numbers to short form 1000 -> 1k
		 *
		 * @param $number
		 *
		 * @return string
		 */
		function format_number( $number ) {
			if ( $number >= 1000 ) {
				return $number / 1000 . "k";   // NB: you will want to round this
			} else {
				return $number;
			}
		}

		/**
		 * Add/Remove image id from Super Smushed images count
		 *
		 * @param int $id Image id
		 *
		 * @param string $op_type Add/remove, whether to add the image id or remove it from the list
		 *
		 * @return bool Whether the Super Smushed option was update or not
		 *
		 */
		function update_super_smush_count( $id, $op_type = 'add', $key = 'wp-smush-super_smushed' ) {

			//Get the existing count
			$super_smushed = get_option( $key, false );

			//Initialize if it doesn't exists
			if ( ! $super_smushed || empty( $super_smushed['ids'] ) ) {
				$super_smushed = array(
					'ids' => array()
				);
			}

			//Insert the id, if not in there already
			if ( 'add' == $op_type && ! in_array( $id, $super_smushed['ids'] ) ) {

				$super_smushed['ids'][] = $id;

			} elseif ( 'remove' == $op_type && false !== ( $k = array_search( $id, $super_smushed['ids'] ) ) ) {

				//Else remove the id from the list
				unset( $super_smushed['ids'][ $k ] );

				//Reset all the indexes
				$super_smushed['ids'] = array_values( $super_smushed['ids'] );

			}

			//Add the timestamp
			$super_smushed['timestamp'] = current_time( 'timestamp' );

			update_option( $key, $super_smushed );

			//Update to database
			return true;
		}

		/**
		 * Checks if the image compression is lossy, stores the image id in options table
		 *
		 * @param int $id Image Id
		 *
		 * @param array $stats Compression Stats
		 *
		 * @param string $key Meta Key for storing the Super Smushed ids (Optional for Media Library)
		 *                    Need To be specified for NextGen
		 *
		 * @return bool
		 */
		function update_lists( $id, $stats, $key = '' ) {
			//If Stats are empty or the image id is not provided, return
			if ( empty( $stats ) || empty( $id ) || empty( $stats['stats'] ) ) {
				return false;
			}

			//Update Super Smush count
			if ( isset( $stats['stats']['lossy'] ) && 1 == $stats['stats']['lossy'] ) {
				if ( empty( $key ) ) {
					update_post_meta( $id, 'wp-smush-lossy', 1 );
				} else {
					$this->update_super_smush_count( $id, 'add', $key );
				}
			}

			//Check and update re-smush list for media gallery
			if ( ! empty( $this->resmush_ids ) && in_array( $id, $this->resmush_ids ) ) {
				$this->update_resmush_list( $id );
			}

		}

		/**
		 * Delete the resmush list for Nextgen or the Media Library
		 */
		function delete_resmush_list() {

			$key = ! empty( $_POST['type'] ) && 'nextgen' == $_POST['type'] ? 'wp-smush-nextgen-resmush-list' : 'wp-smush-resmush-list';
			//Delete the resmush list
			delete_option( $key );
			wp_send_json_success();
		}

		/**
		 * Allows to bulk restore the images, if there is any backup for them
		 */
		function bulk_restore() {
			global $wpsmush_stats;
			$smushed_attachments = !empty( $this->smushed_attachments ) ? $this->smushed_attachments : $wpsmush_stats->smushed_count( true );
			foreach ( $smushed_attachments as $attachment ) {
				$this->restore_image( $attachment->attachment_id, false );
			}
		}

		/**
		 * Loads the Shared UI to on all admin pages
		 *
		 * @param $current_page
		 */
		function load_shared_ui( $current_page ) {
			//If class method exists, load shared UI
			if ( class_exists( 'WDEV_Plugin_Ui' ) ) {

				if ( method_exists( 'WDEV_Plugin_Ui', 'load' ) ) {

					//Load Shared UI
					WDEV_Plugin_Ui::load( WP_SMUSH_URL . '/assets/shared-ui/', false );

					if ( ( 'media_page_wp-smush-bulk' != $current_page && 'gallery_page_wp-smush-nextgen-bulk' != $current_page ) ) {

						//Don't add thhe WPMUD class to body to other admin pages
						remove_filter(
							'admin_body_class',
							array( 'WDEV_Plugin_Ui', 'admin_body_class' )
						);

					}
				}
			}
		}

		/** Get the Maximum Width and Height settings for WrodPress
		 *
		 * @return array, Array of Max. Width and Height for image
		 *
		 */
		function get_max_image_dimensions() {
			global $_wp_additional_image_sizes;

			$width = $height = 0;
			$limit = 9999; //Post-thumbnail

			$image_sizes = get_intermediate_image_sizes();

			// Create the full array with sizes and crop info
			foreach ( $image_sizes as $size ) {
				if ( in_array( $size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
					$size_width  = get_option( "{$size}_size_w" );
					$size_height = get_option( "{$size}_size_h" );
				} elseif ( isset( $_wp_additional_image_sizes[ $size ] ) ) {
					$size_width  = $_wp_additional_image_sizes[ $size ]['width'];
					$size_height = $_wp_additional_image_sizes[ $size ]['height'];
				}

				//Skip if no width and height
				if ( ! isset( $size_width, $size_height ) ) {
					continue;
				}

				//If within te limit, check for a max value
				if ( $size_width <= $limit ) {
					$width = max( $width, $size_width );
				}

				if ( $size_height <= $limit ) {
					$height = max( $height, $size_height );
				}
			}

			return array(
				'width'  => $width,
				'height' => $height
			);
		}

		/**
		 * Perform the resize operation for the image
		 *
		 * @param $attachment_id
		 *
		 * @param $meta
		 *
		 * @return mixed
		 */
		function resize_image( $attachment_id, $meta ) {
			if ( empty( $attachment_id ) || empty( $meta ) ) {
				return $meta;
			}
			global $wpsmush_resize;

			return $wpsmush_resize->auto_resize( $attachment_id, $meta );
		}

		/**
		 * Limit for all the queries
		 *
		 * @return int|mixed|void
		 *
		 */
		function query_limit() {
			$limit = apply_filters( 'wp_smush_query_limit', 1000 );
			$limit = intval( $limit );

			return $limit;
		}

		/**
		 * Filter the number of results fetched at once for NextGen queries
		 *
		 * @return int|mixed|void
		 *
		 */
		function nextgen_query_limit() {
			$limit = apply_filters( 'wp_smush_nextgen_query_limit', 1000 );
			$limit = intval( $limit );

			return $limit;
		}

		/**
		 * Show Update info in admin Notice
		 *
		 */
		function smush_updated() {
			//@todo: Update Smush Update Notice for next release
			//Make sure to not display this message for next release
			$plugin_data = get_plugin_data( WP_SMUSH_DIR . 'wp-smush.php', false, false );
			$version     = ! empty( $plugin_data['Version'] ) ? $plugin_data['Version'] : '';

			//If Versions Do not match
			if ( empty( $version ) || $version != WP_SMUSH_VERSION ) {
				return true;
			}

			//Do not display it for other users
			if ( ! is_super_admin() || ! current_user_can( 'manage_options' ) ) {
				return true;
			}

			//If dismissed, Delete the option on Plugin Activation, For alter releases
			if ( 1 == get_site_option( 'wp-smush-hide_update_info' ) ) {
				return true;
			}

			//Get Plugin dir, Return if it's WP Smush Pro installation
			$dir_path = get_plugin_dir();
			if ( ! empty( $dir_path ) && strpos( $dir_path, 'wp-smush-pro' ) !== false ) {
				return true;
			}

			//Do not show for new installations
			$install_type = get_site_option( 'wp-smush-install-type', false );

			if ( ! $install_type || 'new' == $install_type ) {
				return true;
			}

			//Do not display the notice on Bulk Smush Screen
			global $current_screen;
			if ( ! empty( $current_screen->base ) && ( 'media_page_wp-smush-bulk' == $current_screen->base || 'gallery_page_wp-smush-nextgen-bulk' == $current_screen->base ) ) {
				return true;
			}

			//Handles the dismiss action
			$js_url = WP_SMUSH_URL . 'assets/js/notice.js';
			$upgrade_url = add_query_arg(
				array(
					'utm_source'   => 'Smush-Free',
					'utm_medium'   => 'Banner',
					'utm_campaign' => 'now-with-resizing'
				),
				$this->upgrade_url
			);

			$settings_link = '<a href="' . admin_url( 'upload.php?page=wp-smush-bulk#wp-smush-settings-box' ) . '" title="' . esc_html__( "Settings", "wp-smushit" ) . '">';
			$upgrade_link  = '<a href="' . esc_url(   $upgrade_url ) . '" title="' . esc_html__( "WP Smush Pro", "wp-smushit" ) . '">';
			?>
			<div class="notice notice-info is-dismissible wp-smush-update-info">
				<p><?php printf( esc_html__( "Woohoo! Your latest Smush update %s allows you to %sauto resize%s all your images, how cool is that! %sFind out more here >>%s", 'wp-smushit' ), WP_SMUSH_VERSION, $settings_link, '</a>', $upgrade_link, '</a>' ); ?></p>
			</div>

			<script src="<?php echo esc_url( $js_url ) . '?v=' . WP_SMUSH_VERSION; ?>"></script><?php
		}

	}

	global $wpsmushit_admin;
	$wpsmushit_admin = new WpSmushitAdmin();
}
