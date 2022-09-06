<?php
/**	
 *	Copyright 2013 TechAbly.com (email: rahul@techably.com)
 *	
 *	This program is a free software; you can redistribute it and/or modify it under the terms of the GNU General Public License, or (at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 *
 */

/*
Plugin Name: WP Last Modified
Plugin URI: http://techably.com/wp-last-modified/10534/
Description: Display Last updated / Last modified date & time in WordPress Posts and Pages. Also supports Meta Revised tag.
Version: 0.1
Author: Rahul Arora
Author URI: http://techably.com/
License: GPLv2
*/

/**
 *	WordPress Revised Meta Tag Plugin (wp-last-modified.php)
 *
 *	Main Plugin File
 *
 *	@package WordPress
 *	@subpackage WP Last Modifed
 */

define('WP_LAST_MODIFIED_PATH', plugin_dir_path( __FILE__ ));
require_once( WP_LAST_MODIFIED_PATH . 'includes/admin.php');

$options = get_option('wp_last_modified_options');

/**
 * Returns the meta revised tag string
 * 
 * @return meta revised tag stringh with date
 */
function wp_last_modified_meta() {
	$date = '';
	if(is_single() || is_page() && !(is_front_page())) {
		$date = get_the_modified_date('l, F j, Y, g:i a');
		$meta = '<meta name="revised" content="%s" />' . "\n";
		if($date)
			$stuff = sprintf($meta, $date);
		else
			$stuff = '';
	}
	return $stuff;
}

/**
 * Returns the last modified date
 * 
 * @return the post / page modified date
 * @param	$format	date format
 * @param	$label	date pretext
 */
function wp_last_modified_stamp($format, $label) {
	$date = '';
	$label = '<div class="updated">' . $label;
	$date = the_modified_date($format, $label, '</div>', false);
	return $date;
}

add_action('wp_head', 'wp_last_modified_meta_echo', 0);
add_filter('the_content', 'wp_last_modified_stamp_echo', 20);
add_action('admin_menu', 'wp_last_modified_options_page');

/**
 * Prints the Meta revised tag
 * 
 * @return 	$content	Content with modified date
 * @param	$content 	---DO---
 */
function wp_last_modified_stamp_echo($content) {
	$options = get_option('wp_last_modified_options');
	$format = $options['select_field2'];
	$label = $options['text_field1'];
	if($format == 1)
		$format = 'l, F j, Y';
	elseif($format == 2)
		$format = 'jS F, Y, g:i A';
	elseif($format == 3)
		$format = 'jS F, Y';
	elseif($format == 4)
		$format = 'm/j/y';
	else
		$format = 'j/m/y';
	if($options['check_field3']) {
		$stamp = wp_last_modified_stamp($format, $label);
		if($options['select_field1']==1)
			$content = $stamp. $content;
		else
			$content = $content . $stamp;
	}
	return $content;
}
/**
 * Prints the last modified date
 * 
 * @param	$format	date format
 * @param	$label	date pretext
 */
function wp_last_modified_meta_echo() {
	$options = get_option('wp_last_modified_options');
	$inposts = $options['check_field1'];
	$inpages = $options['check_field2'];
	if($inposts) {
		if(is_single())
			echo wp_last_modified_meta();
	} else echo '';
	if($inpages) {
		if(is_page() && !(is_front_page()))
			echo wp_last_modified_meta();
	} else echo '';
}

/**
 * Returns Link array with Settings page link
 * 
 * @return 	$links 	link array with settings link
 * @param	$links 	---DO---
 */
function wp_last_modified_settings_link($links) { 
  $settings_link = '<a href="options-general.php?page=wp-last-modified/includes/admin.php">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
 
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'wp_last_modified_settings_link' );

?>