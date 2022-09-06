<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();
	
delete_option('show_unique');	

global $wpdb;

$table_name = $wpdb->prefix . "PopularityPostsWidget";
if ($wpdb->get_var("show tables like '$table_name'") == $table_name) {
	$wpdb->query("DROP TABLE ".$table_name);
}

$table_name_cache = $wpdb->prefix . "PopularityPostsWidgetCache";
if ($wpdb->get_var("show tables like '$table_name_cache'") == $table_name_cache) {
	$wpdb->query("DROP TABLE ".$table_name_cache);
}

?>