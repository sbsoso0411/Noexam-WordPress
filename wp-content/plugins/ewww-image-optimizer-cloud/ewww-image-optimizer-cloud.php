<?php
/**
 * Integrate cloud image optimization into WordPress.
 * @version 2.8.3
 * @package EWWW_Image_Optimizer_Cloud
 */
/*
Plugin Name: EWWW Image Optimizer Cloud
Plugin URI: https://ewww.io/
Description: Reduce file sizes for images within WordPress including NextGEN Gallery and GRAND FlAGallery via paid cloud service.
Author: Shane Bishop
Text Domain: ewww-image-optimizer-cloud
Version: 2.8.3
Author URI: https://ewww.io/
License: GPLv3
*/
// Constants
define('EWWW_IMAGE_OPTIMIZER_DOMAIN', 'ewww-image-optimizer-cloud');
// this is the full system path to the plugin file itself
define('EWWW_IMAGE_OPTIMIZER_PLUGIN_FILE', __FILE__);
// this is the path of the plugin file relative to the plugins/ folder
define('EWWW_IMAGE_OPTIMIZER_PLUGIN_FILE_REL', 'ewww-image-optimizer-cloud/ewww-image-optimizer-cloud.php');
// this is the full system path to the plugin folder
define('EWWW_IMAGE_OPTIMIZER_PLUGIN_PATH', plugin_dir_path(__FILE__));
// this is the full system path to the plugin images for testing 
define( 'EWWW_IMAGE_OPTIMIZER_IMAGES_PATH', plugin_dir_path( __FILE__ ) . 'images/' ); 

require_once(EWWW_IMAGE_OPTIMIZER_PLUGIN_PATH . 'common.php');

// check to see if the cloud constant is defined (which would mean we've already run init) and then set it properly if not
function ewww_image_optimizer_cloud_init() {
	ewwwio_debug_message( '<b>' . __FUNCTION__ . '()</b>' );
	ewww_image_optimizer_disable_tools();
	if ( ! defined( 'EWWW_IMAGE_OPTIMIZER_CLOUD' ) ) {
		define('EWWW_IMAGE_OPTIMIZER_CLOUD', TRUE);
	}
	if ( ! defined( 'EWWW_IMAGE_OPTIMIZER_NOEXEC' ) ) {
		define('EWWW_IMAGE_OPTIMIZER_NOEXEC', TRUE);
	}
	if ( ! ewww_image_optimizer_get_option( 'ewww_image_optimizer_cloud_key' ) && empty( $_POST['ewww_image_optimizer_cloud_key'] ) ) {
		add_action( 'network_admin_notices', 'ewww_image_optimizer_cloud_key_missing' );
		add_action( 'admin_notices', 'ewww_image_optimizer_cloud_key_missing' );
	}
	if ( ewww_image_optimizer_get_option( 'ewww_image_optimizer_jpg_level' ) == 10 ) {
		ewww_image_optimizer_set_option( 'ewww_image_optimizer_jpg_level', 20 );
	}
	if ( ewww_image_optimizer_get_option( 'ewww_image_optimizer_png_level' ) == 10 ) {
		ewww_image_optimizer_set_option( 'ewww_image_optimizer_png_level', 20 );
	}
	ewwwio_memory( __FUNCTION__ );
}

// stub function from core
function ewww_image_optimizer_exec_init() {
}

// another stub
function ewww_image_optimizer_tool_init( $hook = false, $admin = true ) {
}

// set some default option values
function ewww_image_optimizer_set_defaults() {
	ewwwio_debug_message( '<b>' . __FUNCTION__ . '()</b>' );
	add_site_option( 'ewww_image_optimizer_jpegtran_copy', TRUE );
	add_site_option( 'ewww_image_optimizer_jpg_level', '20' );
	add_site_option( 'ewww_image_optimizer_png_level', '20' );
	add_site_option( 'ewww_image_optimizer_gif_level', '10' );
	add_site_option( 'ewww_image_optimizer_pdf_level', '10' );
}

// display a notice in the admin for a missing key
function ewww_image_optimizer_cloud_key_missing() {
	ewwwio_debug_message( '<b>' . __FUNCTION__ . '()</b>' );
	if ( ! function_exists( 'is_plugin_active_for_network' ) && is_multisite() ) {
		// need to include the plugin library for the is_plugin_active function
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}
	if ( is_multisite() && is_plugin_active_for_network( EWWW_IMAGE_OPTIMIZER_PLUGIN_FILE_REL ) ) {
		$options_page = 'settings.php';
	} else {
		$options_page = 'options-general.php';
	}
	echo "<div id='ewww-image-optimizer-cloud-key-missing' class='error'><p><strong>" . esc_html__( 'EWWW I.O. Cloud requires an API key to optimize images.', EWWW_IMAGE_OPTIMIZER_DOMAIN ) . "</strong> <a href='https://ewww.io/plans/'>" . esc_html__('Purchase an API key.', EWWW_IMAGE_OPTIMIZER_DOMAIN ) . "</a> <a href='$options_page?page=" . plugin_basename(EWWW_IMAGE_OPTIMIZER_PLUGIN_FILE) . "'>" . esc_html__( 'Then, enter it on the settings page.', EWWW_IMAGE_OPTIMIZER_DOMAIN) . "</a></p></div>";
}

// checks which tools should be skipped, and returns an array with each tool
function ewww_image_optimizer_skip_tools() {
	ewwwio_debug_message( '<b>' . __FUNCTION__ . '()</b>' );
	$skip['jpegtran'] = true;
	$skip['optipng'] = true;
	$skip['gifsicle'] = true;
	$skip['pngout'] = true;
	// except these which are off by default
	$skip['pngquant'] = true;
	$skip['webp'] = true;
	return $skip;
}

// check the mimetype of the given file ($path) with various methods
// valid values for $type are 'b' for binary or 'i' for image
function ewww_image_optimizer_mimetype( $path, $case ) {
	ewwwio_debug_message( '<b>' . __FUNCTION__ . '()</b>' );
	ewwwio_debug_message( "testing mimetype: $path" );
	if ( $case === 'i' && preg_match( '/^RIFF.+WEBPVP8/', file_get_contents( $path, NULL, NULL, 0, 16 ) ) ) { 
		return 'image/webp';
	}
	if ( $case === 'i' && strpos( $path, 's3' ) === 0 ) {
		$pathextension = strtolower( pathinfo( $path, PATHINFO_EXTENSION ) );
		switch ( $pathextension ) {
			case 'jpg':
			case 'jpeg':
				ewwwio_debug_message( 's3 type: image/jpeg' );
				return 'image/jpeg';
			case 'png':
				ewwwio_debug_message( 's3 type: image/png' );
				return 'image/png';
			case 'gif':
				ewwwio_debug_message( 's3 type: image/gif' );
				return 'image/gif';
			case 'pdf':
				ewwwio_debug_message( 's3 type: application/pdf' );
				return 'application/pdf';
		}
	}
	if ( function_exists( 'finfo_file' ) && defined( 'FILEINFO_MIME' ) ) {
		// create a finfo resource
		$finfo = finfo_open( FILEINFO_MIME );
		// retrieve the mimetype
		$type = explode( ';', finfo_file( $finfo, $path ) );
		$type = $type[0];
		finfo_close( $finfo );
		ewwwio_debug_message( "finfo_file: $type" );
	}
	// see if we can use the getimagesize function
	if ( empty( $type ) && function_exists( 'getimagesize' ) && $case === 'i' ) {
		// run getimagesize on the file
		$type = getimagesize( $path );
		// make sure we have results
		if( false !== $type ) {
			// store the mime-type
			$type = $type['mime'];
		}
		ewwwio_debug_message( "getimagesize: $type" );
	}
	// see if we can use mime_content_type
	if ( empty( $type ) && function_exists( 'mime_content_type' ) ) {
		// retrieve and store the mime-type
		$type = mime_content_type( $path );
		ewwwio_debug_message( "mime_content_type: $type" );
	}
	// if we are dealing with a binary, and found an executable
	if ( $case === 'b' && preg_match( '/executable/', $type ) ) {
		ewwwio_memory( __FUNCTION__ );
		return $type;
	// otherwise, if we are dealing with an image
	} elseif ( $case === 'i' ) {
		ewwwio_memory( __FUNCTION__ );
		return $type;
	// if all else fails, bail
	} else {
		ewwwio_debug_message( 'no mime functions and not a binary' );
		ewwwio_memory( __FUNCTION__ );
		return false;
	}
}

/**
 * Process an image.
 *
 * Returns an array of the $file, $results, $converted to tell us if an image changes formats, and the $original file if it did.
 *
 * @param   string $file		Full absolute path to the image file
 * @param   int $gallery_type		1=wordpress, 2=nextgen, 3=flagallery, 4=aux_images, 5=image editor, 6=imagestore, 7=retina
 * @param   boolean $converted		tells us if this is a resize and the full image was converted to a new format
 * @param   boolean $new		tells the optimizer that this is a new image, so it should attempt conversion regardlress of previous results
 * @param   boolean $fullsize		indicates that this is a full size image, not a resized version
 * @returns array
 */
function ewww_image_optimizer( $file, $gallery_type = 4, $converted = false, $new = false, $fullsize = false ) {
	ewwwio_debug_message( '<b>' . __FUNCTION__ . '()</b>' );
	// if the plugin gets here without initializing, we need to run through some things first
	if ( ! defined( 'EWWW_IMAGE_OPTIMIZER_CLOUD' ) ) {
		ewww_image_optimizer_init();
	}
	$bypass_optimization = apply_filters( 'ewww_image_optimizer_bypass', false, $file ); 
	if ( true === $bypass_optimization ) { 
		// tell the user optimization was skipped 
		$msg = __( "Optimization skipped", EWWW_IMAGE_OPTIMIZER_DOMAIN ); 
		ewwwio_debug_message( "optimization bypassed: $file" );
		// send back the above message 
		return array( false, $msg, $converted, $file ); 
	}
	// initialize the original filename 
	$original = $file;
	$result = '';
	// check that the file exists
	if ( FALSE === is_file( $file ) ) {
		// tell the user we couldn't find the file
		$msg = sprintf( __( 'Could not find %s', EWWW_IMAGE_OPTIMIZER_DOMAIN ), $file );
		ewwwio_debug_message( "file doesn't appear to exist: $file" );
		// send back the above message
		return array( false, $msg, $converted, $original );
	}
	// check that the file is writable
	if ( FALSE === is_writable( $file ) ) {
		// tell the user we can't write to the file
		$msg = sprintf( __( '%s is not writable', EWWW_IMAGE_OPTIMIZER_DOMAIN ), $file );
		ewwwio_debug_message( "couldn't write to the file $file" );
		// send back the above message
		return array( false, $msg, $converted, $original );
	}
	if ( function_exists( 'fileperms' ) )
		$file_perms = substr( sprintf( '%o', fileperms( $file ) ), -4 );
	$file_owner = 'unknown';
	$file_group = 'unknown';
	if ( function_exists( 'posix_getpwuid' ) ) {
		$file_owner = posix_getpwuid( fileowner( $file ) );
		$file_owner = $file_owner['name'];
	}
	if ( function_exists( 'posix_getgrgid' ) ) {
		$file_group = posix_getgrgid( filegroup( $file ) );
		$file_group = $file_group['name'];
	}
	ewwwio_debug_message( "permissions: $file_perms, owner: $file_owner, group: $file_group" );
	$type = ewww_image_optimizer_mimetype( $file, 'i' );
	if ( strpos( $type, 'image' ) === FALSE && strpos( $type, 'pdf' ) === FALSE ) {
		ewwwio_debug_message( 'could not find any functions for mimetype detection' );
		//otherwise we store an error message since we couldn't get the mime-type
		return array( false, __( 'Unsupported file type: ' . $type, EWWW_IMAGE_OPTIMIZER_DOMAIN ), $converted, $original );
		$msg = __( 'Missing finfo_file(), getimagesize() and mime_content_type() PHP functions', EWWW_IMAGE_OPTIMIZER_DOMAIN );
		return array( false, $msg, $converted, $original );
	}
	if ( ewww_image_optimizer_get_option( 'ewww_image_optimizer_lossy_skip_full' ) && $fullsize ) {
		$skip_lossy = true;
	} else {
		$skip_lossy = false;
	}
	if ( ini_get( 'max_execution_time' ) < 90 && ewww_image_optimizer_stl_check() ) {
		set_time_limit( 0 );
	}
	// if the full-size image was converted
	if ( $converted ) {
		ewwwio_debug_message( 'full-size image was converted, need to rebuild filename for meta' );
		$filenum = $converted;
		// grab the file extension
		preg_match( '/\.\w+$/', $file, $fileext );
		// strip the file extension
		$filename = str_replace( $fileext[0], '', $file );
		// grab the dimensions
		preg_match( '/-\d+x\d+(-\d+)*$/', $filename, $fileresize );
		// strip the dimensions
		$filename = str_replace( $fileresize[0], '', $filename );
		// reconstruct the filename with the same increment (stored in $converted) as the full version
		$refile = $filename . '-' . $filenum . $fileresize[0] . $fileext[0];
		// rename the file
		rename( $file, $refile );
		ewwwio_debug_message( "moved $file to $refile" );
		// and set $file to the new filename
		$file = $refile;
		$original = $file;
	}
	// get the original image size
	$orig_size = filesize( $file );
	ewwwio_debug_message( "original filesize: $orig_size" );
	if ( $orig_size < ewww_image_optimizer_get_option( 'ewww_image_optimizer_skip_size' ) ) {
		// tell the user optimization was skipped
		$msg = __( "Optimization skipped", EWWW_IMAGE_OPTIMIZER_DOMAIN );
		ewwwio_debug_message( "optimization bypassed due to filesize: $file" );
		// send back the above message
		return array( false, $msg, $converted, $file );
	}
	if ( $type == 'image/png' && ewww_image_optimizer_get_option( 'ewww_image_optimizer_skip_png_size' ) && $orig_size > ewww_image_optimizer_get_option( 'ewww_image_optimizer_skip_png_size' ) ) {
		// tell the user optimization was skipped
		$msg = __( "Optimization skipped", EWWW_IMAGE_OPTIMIZER_DOMAIN );
		ewwwio_debug_message( "optimization bypassed due to filesize: $file" );
		// send back the above message
		return array( false, $msg, $converted, $file );
	}
	// initialize $new_size with the original size
	$new_size = 0;
	// set the optimization process to OFF
	$optimize = false;
	// toggle the convert process to ON
	$convert = true;
	// allow other plugins to mangle the image however they like prior to optimization
	do_action( 'ewww_image_optimizer_pre_optimization', $file, $type );
	// run the appropriate optimization/conversion for the mime-type
	switch( $type ) {
		case 'image/jpeg':
			// if jpg2png conversion is enabled, and this image is in the wordpress media library
			if ( ( ewww_image_optimizer_get_option( 'ewww_image_optimizer_jpg_to_png' ) && $gallery_type == 1 ) || ! empty( $_GET['ewww_convert'] ) ) {
				// generate the filename for a PNG
				// if this is a resize version
				if ( $converted ) {
					// just change the file extension
					$pngfile = preg_replace( '/\.\w+$/', '.png', $file );
				// if this is a full size image
				} else {
					// get a unique filename for the png image
					list( $pngfile, $filenum ) = ewww_image_optimizer_unique_filename( $file, '.png' );
				}
			} else {
				// otherwise, set it to OFF
				$convert = false;
				$pngfile = '';
			}
			// check for previous optimization, so long as the force flag is on and this isn't a new image that needs converting
			if ( empty( $_REQUEST['ewww_force'] ) && ! ( $new && $convert ) ) {
				if ( $results_msg = ewww_image_optimizer_check_table( $file, $orig_size ) ) {
					return array( $file, $results_msg, $converted, $original );
				}
			}
			if ( ewww_image_optimizer_get_option( 'ewww_image_optimizer_jpg_level' ) > 0 ) {
				list($file, $converted, $result, $new_size) = ewww_image_optimizer_cloud_optimizer($file, $type, $convert, $pngfile, 'image/png', $skip_lossy);
				if ($converted) {
					$converted = $filenum;
					ewww_image_optimizer_webp_create( $file, $new_size, 'image/png', null );
				} else {
					ewww_image_optimizer_webp_create( $file, $new_size, $type, null );
				}
			} else {
				ewww_image_optimizer_webp_create( $file, $orig_size, $type, null ); 
			}
			break;
		case 'image/png':
			// png2jpg conversion is turned on, and the image is in the wordpress media library
			if ( ( ewww_image_optimizer_get_option( 'ewww_image_optimizer_png_to_jpg' ) || ! empty( $_GET['ewww_convert'] ) ) && $gallery_type == 1 && ! $skip_lossy && ( ! ewww_image_optimizer_png_alpha( $file ) || ewww_image_optimizer_jpg_background() ) ) {
				ewwwio_debug_message( 'PNG to JPG conversion turned on' );
				// if the user set a fill background for transparency
				$background = '';
				if ($background = ewww_image_optimizer_jpg_background()) {
					// set background color for GD
					$r = hexdec('0x' . strtoupper(substr($background, 0, 2)));
                                        $g = hexdec('0x' . strtoupper(substr($background, 2, 2)));
					$b = hexdec('0x' . strtoupper(substr($background, 4, 2)));
					// set the background flag for 'convert'
					$background = "-background " . '"' . "#$background" . '"';
				} else {
					$r = '255';
					$g = '255';
					$b = '255';
				}
				// if the user manually set the JPG quality
				if ($quality = ewww_image_optimizer_jpg_quality()) {
					// set the quality for GD
					$gquality = $quality;
					// set the quality flag for 'convert'
					$cquality = "-quality $quality";
				} else {
					$cquality = '';
					$gquality = '92';
				}
				// if this is a resize version
				if ($converted) {
					// just replace the file extension with a .jpg
					$jpgfile = preg_replace('/\.\w+$/', '.jpg', $file);
				// if this is a full version
				} else {
					// construct the filename for the new JPG
					list($jpgfile, $filenum) = ewww_image_optimizer_unique_filename($file, '.jpg');
				}
			} else {
				ewwwio_debug_message( 'PNG to JPG conversion turned off' );
				// turn the conversion process OFF
				$convert = false;
				$jpgfile = '';
				$r = null;
				$g = null;
				$b = null;
				$gquality = null;
			}
			// check for previous optimization, so long as the force flag is on and this isn't a new image that needs converting
			if ( empty( $_REQUEST['ewww_force'] ) && ! ( $new && $convert ) ) {
				if ( $results_msg = ewww_image_optimizer_check_table( $file, $orig_size ) ) {
					return array( $file, $results_msg, $converted, $original );
				}
			}
			if ( ewww_image_optimizer_get_option('ewww_image_optimizer_png_level') > 0 ) {
				list($file, $converted, $result, $new_size) = ewww_image_optimizer_cloud_optimizer($file, $type, $convert, $jpgfile, 'image/jpeg', $skip_lossy, array('r' => $r, 'g' => $g, 'b' => $b, 'quality' => $gquality));
				if ($converted) {
					$converted = $filenum;
					ewww_image_optimizer_webp_create( $file, $new_size, 'image/jpeg', null ); 
				} else { 
 					ewww_image_optimizer_webp_create( $file, $new_size, $type, null ); 
 				}
			} else {
				ewww_image_optimizer_webp_create( $file, $orig_size, $type, null );
			}
			break;
		case 'image/gif':
			// if gif2png is turned on, and the image is in the wordpress media library
			if ( ( ( ewww_image_optimizer_get_option( 'ewww_image_optimizer_gif_to_png' ) && $gallery_type == 1 ) || ! empty( $_GET['ewww_convert'] ) ) && ! ewww_image_optimizer_is_animated( $file ) ) {
				// generate the filename for a PNG
				// if this is a resize version
				if ($converted) {
					// just change the file extension
					$pngfile = preg_replace('/\.\w+$/', '.png', $file);
				// if this is the full version
				} else {
					// construct the filename for the new PNG
					list($pngfile, $filenum) = ewww_image_optimizer_unique_filename($file, '.png');
				}
			} else {
				// turn conversion OFF
				$convert = false;
				$pngfile = '';
			}
			// check for previous optimization, so long as the force flag is on and this isn't a new image that needs converting
			if ( empty( $_REQUEST['ewww_force'] ) && ! ( $new && $convert ) ) {
				if ( $results_msg = ewww_image_optimizer_check_table( $file, $orig_size ) ) {
					return array( $file, $results_msg, $converted, $original );
				}
			}
			if ( ewww_image_optimizer_get_option('ewww_image_optimizer_gif_level') > 0 ) {
				list($file, $converted, $result, $new_size) = ewww_image_optimizer_cloud_optimizer($file, $type, $convert, $pngfile, 'image/png', $skip_lossy);
				if ($converted) {
					$converted = $filenum;
					ewww_image_optimizer_webp_create( $file, $new_size, 'image/png', null ); 
 				}
			}
			break;
		case 'application/pdf':
			if ( empty( $_REQUEST['ewww_force'] ) ) {
				if ( $results_msg = ewww_image_optimizer_check_table( $file, $orig_size ) ) {
					return array( $file, $results_msg, false, $original );
				}
			}
			if ( ewww_image_optimizer_get_option( 'ewww_image_optimizer_pdf_level' ) > 0 ) {
				list( $file, $converted, $result, $new_size ) = ewww_image_optimizer_cloud_optimizer( $file, $type );
			}
			break;
		default:
			// if not a JPG, PNG, or GIF, tell the user we don't work with strangers
			return array( $file, __( 'Unknown type: ' . $type, EWWW_IMAGE_OPTIMIZER_DOMAIN ), $converted, $original );
	}
	// allow other plugins to run operations on the images after optimization.
	// NOTE: it is recommended to do any image modifications prior to optimization, otherwise you risk un-optimizing your images here.
	do_action( 'ewww_image_optimizer_post_optimization', $file, $type );
	// if their cloud api license limit has been exceeded
	if ( $result == 'exceeded' ) {
		return array( $file, __( 'License exceeded', EWWW_IMAGE_OPTIMIZER_DOMAIN ), $converted, $original );
	}
	if ( ! empty( $new_size ) ) {
		$results_msg = ewww_image_optimizer_update_table ( $file, $new_size, $orig_size, $new );
		ewwwio_memory( __FUNCTION__ );
		return array( $file, $results_msg, $converted, $original );
	}
	ewwwio_memory( __FUNCTION__ );
	// otherwise, send back the filename, the results (some sort of error message), the $converted flag, and the name of the original image
	return array( $file, $result, $converted, $original );
}

// creates webp images alongside JPG and PNG files
// needs a filename, the filesize, mimetype, and the path to the cwebp binary (null for cloud)
function ewww_image_optimizer_webp_create( $file, $orig_size, $type, $tool, $recreate = false ) {
	ewwwio_debug_message( '<b>' . __FUNCTION__ . '()</b>' );
	$webpfile = $file . '.webp';
	if ( ! ewww_image_optimizer_get_option( 'ewww_image_optimizer_webp' ) ) {
		return;
	} elseif ( is_file( $webpfile ) && empty( $_REQUEST['ewww_force'] ) && ! $recreate ) {
		ewwwio_debug_message( 'webp file exists, not forcing or recreating' );
		return;
	}
	ewww_image_optimizer_cloud_optimizer( $file, $type, false, $webpfile, 'image/webp' );
	$webp_size = ewww_image_optimizer_filesize( $webpfile );
	ewwwio_debug_message( "webp is $webp_size vs. $type is $orig_size" );
	if ( is_file( $webpfile ) && $orig_size < $webp_size ) {
		ewwwio_debug_message( 'webp file was too big, deleting' );
		unlink( $webpfile );
	}
	ewwwio_memory( __FUNCTION__ );
}

function ewww_image_optimizer_translation_site_url( $output ) {
	$output = preg_replace( '/translate.wordpress.org.projects.wp-plugins.ewww-image-optimizer\//', 'translate.wordpress.org/projects/wp-plugins/ewww-image-optimizer-cloud/', $output );
	return $output;
}
add_filter( 'ewww_image_optimizer_settings', 'ewww_image_optimizer_translation_site_url' );
