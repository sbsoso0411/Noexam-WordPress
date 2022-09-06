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
if ( ! class_exists( 'WpSmushResize' ) ) {

	/**
	 * Class WpSmushResize
	 */
	class WpSmushResize {

		/**
		 * @var int Specified width for resizing images
		 *
		 */
		public $max_w = 0;

		/**
		 * @var int Specified Height for resizing images
		 *
		 */
		public $max_h = 0;

		/**
		 * @var bool If resizing is enabled or not
		 */
		public $resize_enabled = false;

		function __construct() {
			/**
			 * Initialize class variables, after all stuff has been loaded
			 */
			add_action( 'wp_loaded', array( $this, 'initialize' ) );

		}

		/**
		 * Get the settings for resizing
		 */
		function initialize() {
			//If resizing is enabled
			$this->resize_enabled = get_option( WP_SMUSH_PREFIX . 'resize' );

			$resize_sizes = get_option( WP_SMUSH_PREFIX . 'resize_sizes', array() );

			//Resize width and Height
			$this->max_w = ! empty( $resize_sizes['width'] ) ? $resize_sizes['width'] : 0;
			$this->max_h = ! empty( $resize_sizes['height'] ) ? $resize_sizes['height'] : 0;
		}

		/**
		 * Check whether Image should be resized or not
		 *
		 * @param string $params
		 * @param string $action
		 *
		 * @return bool
		 */
		private function should_resize( $id = '' ) {

			//If resizing not enabled, or if both max width and height is set to 0, return
			if ( ! $this->resize_enabled || ( $this->max_w == 0 && $this->max_h == 0 ) ) {
				return false;
			}

			$file_path = get_attached_file( $id );

			if ( ! empty( $file_path ) ) {

				// Skip: if "noresize" is included in the filename, Thanks to Imsanity
				if ( strpos( $file_path, 'noresize' ) !== false ) {
					return false;
				}

				//If file doesn't exists, return
				if ( ! file_exists( $file_path ) ) {
					return false;
				}

			}

			//Check for a supported mime type
			global $wpsmushit_admin;

			//Get image mime type
			$mime = get_post_mime_type( $id );

			$mime_supported = in_array( $mime, $wpsmushit_admin->mime_types );

			//If type of upload doesn't matches the criteria return
			if ( ! empty( $mime ) && ! $mime_supported = apply_filters( 'wp_smush_resmush_mime_supported', $mime_supported, $mime ) ) {
				return false;
			}

			//Check if already resized
			$resize_meta = get_post_meta( $id, WP_SMUSH_PREFIX . 'resize_savings', true );
			if ( ! empty( $resize_meta ) ) {
				return false;
			}

			return true;
		}

		/**
		 * Handles the Auto resizing of new uploaded images
		 *
		 * @param array $upload
		 * @param string $action
		 *
		 * @return array $upload
		 */
		function auto_resize( $id, $meta ) {

			if ( empty( $id ) || ! wp_attachment_is_image( $id ) ) {
				return $meta;
			}

			//Do not perform resize while restoring images/ Editing images
			if ( ! empty( $_REQUEST['do'] ) && ( 'restore' == $_REQUEST['do'] || 'scale' == $_REQUEST['do'] ) ) {
				return $meta;
			}

			//Check if the image should be resized or not
			$should_resize = $this->should_resize( $id );

			/**
			 * Filter whether the uploaded image should be resized or not
			 *
			 * @since 2.3
			 *
			 * @param bool $should_resize
			 *
			 * @param array $upload {
			 *    Array of upload data.
			 *
			 * @type string $file Filename of the newly-uploaded file.
			 * @type string $url URL of the uploaded file.
			 * @type string $type File type.
			 * }
			 *
			 * @param string $context The type of upload action. Values include 'upload' or 'sideload'.
			 *
			 */
			if ( ! $should_resize = apply_filters( 'wp_smush_resize_uploaded_image', $should_resize, $id, $meta ) ) {
				return $meta;
			}

			//Good to go
			$file_path = get_attached_file( $id );

			$original_file_size = filesize( $file_path );

			$resize = $this->perform_resize( $file_path, $original_file_size, $id, $meta );

			//If resize wasn't successful
			if ( ! $resize ) {
				return $meta;
			}

			//Else Replace the Original file with resized file
			$replaced = $this->replcae_original_image( $file_path, $resize, $id, $meta );

			if ( $replaced ) {
				//Clear Stat Cache, Else the size obtained is same as the original file size
				clearstatcache();

				//Updated File size
				$u_file_size = filesize( $file_path );

				$savings['bytes']     = $original_file_size > $u_file_size ? $original_file_size - $u_file_size : 0;
				$savings['size_before'] = $original_file_size;
				$savings['size_after']  = $u_file_size;

				//Store savings in meta data
				if ( ! empty( $savings ) ) {
					update_post_meta( $id, WP_SMUSH_PREFIX . 'resize_savings', $savings );
				}

				$meta['width']  = ! empty( $resize['width'] ) ? $resize['width'] : $meta['width'];
				$meta['height'] = ! empty( $resize['height'] ) ? $resize['height'] : $meta['height'];

				/**
				 * Called after the image have been successfully resized
				 * Can be used to update the stored stats
				 */
				do_action( 'wp_smush_image_resized', $id, $savings );

			}

			return $meta;

		}

		/**
		 * Generates the new image for specified width and height,
		 * Checks if the size of generated image is greater,
		 *
		 * @param $file_path Original File path
		 *
		 * @return bool, If the image generation was succesfull
		 */
		function perform_resize( $file_path, $original_file_size, $id, $meta = '', $unlink = true ) {

			/**
			 * Filter the resize image dimensions
			 *
			 * @since 2.3
			 *
			 * @param array $sizes {
			 *    Array of sizes containing max width and height for all the uploaded images.
			 *
			 * @type int $width Maximum Width For resizing
			 * @type int $height Maximum Height for resizing
			 * }
			 *
			 * @param string $file_path Original Image file path
			 *
			 * @param array $upload {
			 *    Array of upload data.
			 *
			 * @type string $file Filename of the newly-uploaded file.
			 * @type string $url URL of the uploaded file.
			 * @type string $type File type.
			 * }
			 *
			 */
			$sizes = apply_filters( 'wp_smush_resize_sizes', array(
				'width'  => $this->max_w,
				'height' => $this->max_h
			), $file_path, $id );

			$data = image_make_intermediate_size( $file_path, $sizes['width'], $sizes['height'] );

			//If the image wasn't resized
			if ( empty( $data['file'] ) || is_wp_error( $data ) ) {
				return false;
			}

			//Check if file size is lesser than original image
			$resize_path = path_join( dirname( $file_path ), $data['file'] );
			if ( ! file_exists( $resize_path ) ) {
				return false;
			}

			$data['file_path'] = $resize_path;

			$file_size = filesize( $resize_path );
			if ( $file_size > $original_file_size ) {
				//Don't Unlink for nextgen images
				if( $unlink ) {
					$this->maybe_unlink( $resize_path, $meta );
				}

				return false;
			}

			//Store filesize
			$data['filesize'] = $file_size;

			return $data;
		}

		/**
		 * Replace the original file with resized file
		 *
		 * @param $upload
		 *
		 * @param $resized
		 *
		 */
		function replcae_original_image( $file_path, $resized, $attachment_id = '', $meta = '' ) {
			$replaced = false;

			//Take Backup, if we have to, off by default
			$this->backup_image( $file_path, $attachment_id, $meta );

			$replaced = @copy( $resized['file_path'], $file_path );
			$this->maybe_unlink( $resized['file_path'], $meta );

			return $replaced;
		}

		/**
		 * Creates a WordPress backup of original image, Disabled by default
		 *
		 * @param $upload
		 *
		 * @param $attachment_id
		 *
		 * @param $meta
		 */
		function backup_image( $path, $attachment_id, $meta ) {

			/**
			 * Allows to turn on the backup for resized image
			 */
			$backup = apply_filters( 'wp_smush_resize_create_backup', false );

			//If we don't have a attachment id, return
			if ( empty( $attachment_id ) || ! $backup ) {
				return;
			}

			//Creating Backup
			$backup_sizes = get_post_meta( $attachment_id, '_wp_attachment_backup_sizes', true );

			if ( ! is_array( $backup_sizes ) ) {
				$backup_sizes = array();
			}

			//There is alrready a backup, no need to create one
			if ( ! empty( $backup_sizes['full-orig'] ) ) {
				return;
			}

			//Create a copy of original
			if ( empty( $path ) ) {
				$path = get_attached_file( $attachment_id );
			}

			$path_parts = pathinfo( $path );
			$filename   = $path_parts['filename'];
			$filename .= '-orig';

			//Backup Path
			$backup_path = path_join( $path_parts['dirname'], $filename ) . ".{$path_parts['extension']}";

			//Create a copy
			if ( file_exists( $path ) ) {
				$copy_created = @copy( $path, $backup_path );
				if ( $copy_created ) {
					$backup_sizes['full-orig'] = array(
						'file'   => basename( $backup_path ),
						'width'  => $meta['width'],
						'height' => $meta['height']
					);
					//Save in Attachment meta
					update_post_meta( $attachment_id, '_wp_attachment_backup_sizes', $backup_sizes );
				}
			}
		}

		/**
		 * @param $filename
		 *
		 * @return mixed
		 */
		function file_name( $filename ) {
			if ( empty( $filename ) ) {
				return $filename;
			}

			return $filename . 'tmp';
		}

		/**
		 * Do not unlink the resized file if the name is similar to one of the image sizes
		 *
		 * @param $path Image File Path
		 * @param $meta Image Meta
		 *
		 * @return bool
		 */
		function maybe_unlink( $path, $meta ) {
			if ( empty( $path ) ) {
				return true;
			}

			//Unlink directly if meta value is not specified
			if ( empty( $meta ) || empty( $meta['sizes'] ) ) {
				@unlink( $path );
			}

			$unlink = true;
			//Check if the file name is similar to one of the image sizes
			$path_parts = pathinfo( $path );
			$filename   = ! empty( $path_parts['basename'] ) ? $path_parts['basename'] : $path_parts['filename'];
			foreach ( $meta['sizes'] as $image_size ) {
				if ( false === strpos( $image_size['file'], $filename ) ) {
					continue;
				}
				$unlink = false;
			}
			if ( $unlink ) {
				@unlink( $path );
			}

			return true;

		}
	}

	/**
	 * Initialise class
	 */
	global $wpsmush_resize;
	$wpsmush_resize = new WpSmushResize();
}