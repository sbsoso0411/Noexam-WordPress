<?php
/**
 * Remove plugin settings data
 *
 * @since 1.7
 *
 */

//if uninstall not called from WordPress exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}
global $wpdb;

$smushit_keys = array(
	'auto',
	'original',
	'lossy',
	'backup',
	'resize',
	'resize-sizes',
	'nextgen',
	'keep_exif',
	'resmush-list',
	'skip-redirect',
	'nextgen-resmush-list',
	'super_smushed',
	'super_smushed_nextgen',
	'settings_updated',
	'skip-redirect',
	'hide_smush_welcome',
	'hide_upgrade_notice',
	'hide_update_info',
	'install-type',
	'lossy-updated',
	'version'
);

//Cache Keys
$cache_keys = array(
	'smush_global_stats',
);

$cache_smush_group   = array(
	'exceeding_items',
	'wp-smush-resize_savings',
);
$cache_nextgen_group = array(
	'wp_smush_images',
	'wp_smush_images_smushed',
	'wp_smush_images_unsmushed',
	'wp_smush_stats_nextgen',

);

if ( ! is_multisite() ) {
	//Delete Options
	foreach ( $smushit_keys as $key ) {
		$key = 'wp-smush-' . $key;
		delete_option( $key );
		delete_site_option( $key );
	}
	//Delete Cache data
	foreach ( $cache_keys as $key ) {
		wp_cache_delete( $key );
	}

	foreach ( $cache_smush_group as $s_key ) {
		wp_cache_delete( $s_key, 'smush' );
	}

	foreach ( $cache_nextgen_group as $n_key ) {
		wp_cache_delete( $n_key, 'nextgen' );
	}

}

//Delete Post meta
$meta_type  = 'post';
$meta_key   = 'wp-smpro-smush-data';
$meta_value = '';
$delete_all = true;

if ( is_multisite() ) {
	$offset = 0;
	$limit  = 100;
	while ( $blogs = $wpdb->get_results( "SELECT blog_id FROM {$wpdb->blogs} LIMIT $offset, $limit", ARRAY_A ) ) {
		if ( $blogs ) {
			foreach ( $blogs as $blog ) {
				switch_to_blog( $blog['blog_id'] );
				delete_metadata( $meta_type, null, $meta_key, $meta_value, $delete_all );
				delete_metadata( $meta_type, null, 'wp-smush-lossy', '', $delete_all );
				delete_metadata( $meta_type, null, 'wp-smush-resize_savings', '', $delete_all );
				foreach ( $smushit_keys as $key ) {
					$key = 'wp-smush-' . $key;
					delete_option( $key );
					delete_site_option( $key );
				}
				//Delete Cache data
				foreach ( $cache_keys as $key ) {
					wp_cache_delete( $key );
				}

				foreach ( $cache_smush_group as $s_key ) {
					wp_cache_delete( $s_key, 'smush' );
				}

				foreach ( $cache_nextgen_group as $n_key ) {
					wp_cache_delete( $n_key, 'nextgen' );
				}
			}
			restore_current_blog();
		}
		$offset += $limit;
	}
} else {
	delete_metadata( $meta_type, null, $meta_key, $meta_value, $delete_all );
	delete_metadata( $meta_type, null, 'wp-smush-lossy', '', $delete_all );
	delete_metadata( $meta_type, null, 'wp-smush-resize_savings', '', $delete_all );
}
//@todo: Add procedure to delete backup files
?>