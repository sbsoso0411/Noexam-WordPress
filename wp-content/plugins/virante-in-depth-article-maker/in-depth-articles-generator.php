<?php
/*
Plugin Name: In Depth Articles Generator
Plugin URI: http://www.virante.org/blog/2013/08/07/google-in-depth-article-search-results-first-look-and-reactions/
Description: Generates posts metadata for your pages to better present search results to users.
Version: 1.4
Author: Virante
Author URI: http://www.virante.org/
*/

define( 'IDG_URL', plugins_url('/', __FILE__) );
define( 'IDG_DIR', dirname(__FILE__) );
define( 'IDG_VERSION', '1.0' );
define( 'IDG_OPTION', 'idg_ext' );

require_once( IDG_DIR . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'class.client.php' );
require_once( IDG_DIR . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'functions.plugin.php' );
require_once( IDG_DIR . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'functions.tpl.php' );

// Activation, uninstall
register_activation_hook( __FILE__, 'IDG_Install' );
register_deactivation_hook ( __FILE__, 'IDG_Uninstall' );

function IDG_Init() {
	global $idg;

	// Load translations
	load_plugin_textdomain ( 'in-depth-articles-generator', false, basename(rtrim(dirname(__FILE__), '/')) . '/languages' );

	// Load client
	$idg['client'] = new inDepthArticlesGenerator_Client();

	// Admin
	if ( is_admin() ) {
		require_once( IDG_DIR . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'class.admin.php' );
		require_once( IDG_DIR . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'class.admin.page.php' );
		$idg['admin'] = new myExtension_Admin();
		$idg['admin_page'] = new myExtension_Admin_Page();
	}
}
?>
