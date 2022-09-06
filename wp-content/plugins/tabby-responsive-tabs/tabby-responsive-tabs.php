<?php
/*
Plugin Name: Tabby Responsive Tabs
Plugin URI: http://cubecolour.co.uk/tabby-responsive-tabs
Description: Create responsive tabs inside your posts, pages or custom post types by adding simple shortcodes. An easy to use admin page can be added to customise the tab styles with the optional Tabby Responsive Tabs Customiser add-on plugin.
Author: cubecolour
Version: 1.2.3
Author URI: http://cubecolour.co.uk

	Tabby Responsive Tabs WordPress plugin Copyright 2013-2015 Michael Atkins

	Licenced under the GNU GPL:

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

	Original version of Responsive Tabs jQuery script by Pete Love:
	http://www.petelove.co.uk/responsiveTabs/
	http://codepen.io/petelove666/pen/zbLna
	MIT license: http://blog.codepen.io/legal/licensing/

	Permission is hereby granted, free of charge, to any person
	obtaining a copy of this software and associated documentation
	files (the "Software"), to deal in the Software without restriction,
	including without limitation the rights to use, copy, modify,
	merge, publish, distribute, sublicense, and/or sell copies of
	the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:

	The above copyright notice and this permission notice shall
	be included in all copies or substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
	EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
	OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
	NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
	HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
	WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
	DEALINGS IN THE SOFTWARE.

*/

// ==============================================
//  Prevent Direct Access of this file
// ==============================================

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if this file is accessed directly

// ==============================================
//	Get Plugin Version
// ==============================================

function cc_tabby_plugin_version() {
	if ( ! function_exists( 'get_plugins' ) )
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	$plugin_folder = get_plugins( '/' . plugin_basename( dirname( __FILE__ ) ) );
	$plugin_file = basename( ( __FILE__ ) );
	return $plugin_folder[$plugin_file]['Version'];
}

// ==============================================
//	Add Links in Plugins Table
// ==============================================

add_filter( 'plugin_row_meta', 'cc_tabby_meta_links', 10, 2 );
function cc_tabby_meta_links( $links, $file ) {

	$plugin = plugin_basename(__FILE__);

// create the links
	if ( $file == $plugin ) {

		$supportlink = 'https://wordpress.org/support/plugin/tabby-responsive-tabs';
		$donatelink = 'http://cubecolour.co.uk/wp';
		$reviewlink = 'https://wordpress.org/support/view/plugin-reviews/tabby-responsive-tabs?rate=5#postform';
		$twitterlink = 'http://twitter.com/cubecolour';
		$customiselink = 'http://cubecolour.co.uk/tabby-responsive-tabs-customiser';
		$iconstyle = 'style="-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;"';

		if ( is_plugin_active( 'tabby-responsive-tabs-customiser/tabby-customiser.php' ) ) {
			$customiselink = admin_url( 'options-general.php?page=tabby-settings' );
		}

		return array_merge( $links, array(
			'<a href="' . $supportlink . '"> <span class="dashicons dashicons-lightbulb" ' . $iconstyle . 'title="Tabby Responsive Tabs Support"></span></a>',
			'<a href="' . $twitterlink . '"><span class="dashicons dashicons-twitter" ' . $iconstyle . 'title="Cubecolour on Twitter"></span></a>',
			'<a href="' . $reviewlink . '"><span class="dashicons dashicons-star-filled"' . $iconstyle . 'title="Give a 5 Star Review"></span></a>',
			'<a href="' . $donatelink . '"><span class="dashicons dashicons-heart"' . $iconstyle . 'title="Donate"></span></a>',
			'<a href="' . $customiselink . '"><span class="dashicons dashicons-admin-appearance" ' . $iconstyle . 'title="Tabby Responsive Tabs Customizer"></span></a>'
		) );
	}

	return $links;
}

// ==============================================
//
//	Register & enqueue the stylesheet
//	If you want to use custom styles, copy the content of the tabby.css to your child theme
//	and stop the default styles from loading by this by adding the following line to the theme's functions.php or custom site functions plugin:
//
//	remove_action('wp_print_styles', 'cc_tabby_css', 30);
//
//	Alternatively use the tabby responsive tabs customiser plugin
//	available from from http:cubecolour.co.uk/tabby-responsive-tabs-customiser
//
//	Note: wp_print_styles has been deprecated since WP v3.3, so in a future version of this plugin this may be replaced with:
//	add_action( 'wp_enqueue_scripts', 'cc_tabby_css' );
//	However not breaking backwards compatibility for existing users is currently more important

// Print styles now added separately and only to pages with at least one tab group
// ==============================================

//Screen Styles
function cc_tabby_css() {
	wp_register_style( 'tabby', plugins_url( '/css/tabby.css' , __FILE__ ), '', cc_tabby_plugin_version() );
}

add_action('wp_print_styles', 'cc_tabby_css', 30);

//Print Styles
function cc_tabby_register_print_css() {
	wp_register_style( 'tabby-print', plugins_url( '/css/tabby-print.css' , __FILE__ ), '', cc_tabby_plugin_version() );
}

add_action('wp_enqueue_scripts', 'cc_tabby_register_print_css');

// ==============================================
// Trigger the script if it has not already been triggered on the page
// ==============================================

function cc_tabbytrigger() {

	static $tabbytriggered = FALSE; // static so only sets the value the first time it is run

	if ($tabbytriggered == FALSE) {
		echo "\n" . "<script>jQuery(document).ready(function($) { RESPONSIVEUI.responsiveTabs(); })</script>" .  "\n";

		$tabbytriggered = TRUE;
	}
}

// ==============================================
//	SHORTCODE FOR TABBY
//	use [tabby]
// ==============================================

function cc_shortcode_tabby( $atts, $content = null ) {

	// initialise $firsttab flag so we can tell whether we are building the first tab

	global $reset_firsttab_flag;
	static $firsttab = TRUE;

	if ($GLOBALS["reset_firsttab_flag"] === TRUE) {
		$firsttab = TRUE;
		$GLOBALS["reset_firsttab_flag"] = FALSE;
	}

	// extract title & whether open
	extract(shortcode_atts(array(
		"title" => '',
		"open" => '',
		"icon" => '',
	), $atts, 'tabbytab'));

	$tabtarget = sanitize_title_with_dashes( remove_accents( wp_kses_decode_entities( $title ) ) );

	//initialise urltarget
	$urltarget = '';

	//grab the value of the 'target' url parameter if there is one
	if ( isset ( $_REQUEST['target'] ) ) {
		$urltarget = sanitize_title_with_dashes( $_REQUEST['target'] );
	}

	//	Set Tab Panel Class - add active class if the open attribute is set or the target url parameter matches the dashed version of the tab title
	$tabcontentclass = "tabcontent";

	if ( isset( $class ) ) {
		$tabcontentclass .= " " . $class . "-content";
	}

	if ( ( $open ) || ( isset( $urltarget ) && ( $urltarget == $tabtarget ) ) ) {
		$tabcontentclass .= " responsive-tabs__panel--active";
	}

	$addtabicon = '';

	if ( $icon ) {
		$addtabicon = '<span class="fa fa-' . $icon . '"></span>';
	}

// test whether this is the first tab in the group
	if ( $firsttab ) {

// Set flag so we know subsequent tabs are not the first in the tab group
		$firsttab = FALSE;

// Build output if we are making the first tab
		return '<div class="responsive-tabs">' . "\n" . '<h2 class="tabtitle">' . $addtabicon . $title . '</h2>' . "\n" . '<div class="' . $tabcontentclass . '">' . "\n";
	}

    else {
// Build output if we are making a non-first tab
		return  "\n" . '</div><h2 class="tabtitle">' . $addtabicon . $title . '</h2>' . "\n" . '<div class="' . $tabcontentclass . '">' . "\n";
	}
}

add_shortcode('tabby', 'cc_shortcode_tabby');

// ==============================================
//	SHORTCODE TO BE USED AFTER FINAL TABBY TAB
//	use [tabbyending]
// ==============================================

function cc_shortcode_tabbyending( $atts, $content = null ) {

	// add screen & print-only styles
	if ( wp_style_is( 'tabby', $list = 'registered' ) ) {
		wp_enqueue_style( 'tabby' );
	}

	wp_enqueue_style( 'tabby-print' );

	wp_enqueue_script('tabby', plugins_url('js/tabby.js', __FILE__), array('jquery'), cc_tabby_plugin_version(), true);

	add_action('wp_footer', 'cc_tabbytrigger', 20);

	$GLOBALS["reset_firsttab_flag"] = TRUE;

	global $cc_add_tabby_css;
	$cc_add_tabby_css = true;

	return '</div></div>';
}

add_shortcode('tabbyending', 'cc_shortcode_tabbyending');