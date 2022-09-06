<?php
/*
Plugin Name: WP Add Custom CSS
Plugin URI: http://www.danieledesantis.net
Description: Add custom css to the whole website and to specific posts, pages and custom post types.
Version: 1.0.0
Author: Daniele De Santis
Author URI: http://www.danieledesantis.net
Text Domain: wp-add-custom-css
Domain Path: /languages/
License: GPL2
*/

/*
Copyright 2014-2016  Daniele De Santis  (email : hello@danieledesantis.net)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!defined('ABSPATH')) die ('No direct access allowed');

if(!class_exists('Wpacc'))
{
    class Wpacc
    {
		private $options;
		
		public function __construct() {
      add_action('admin_menu', array($this, 'add_menu'));
    	add_action( 'admin_init', array( $this, 'init_settings' ) );
			add_action( 'add_meta_boxes', array($this, 'add_meta_box' ) );
			add_action( 'save_post', array( $this, 'single_save' ) );
			add_action('init', array($this, 'init'));
			add_filter('query_vars', array($this, 'add_wp_var'));
			add_action( 'wp_enqueue_scripts', array($this, 'add_custom_css'), 999 );
			add_action('wp_head', array($this, 'single_custom_css'));
		}			
		
		public function init() {
			load_plugin_textdomain( 'wp-add-custom-css', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}
		
		public static function uninstall() {
			self::delete_options();	
			self::delete_custom_meta();
    }
		
		public function add_meta_box( $post_type ) {
			$this->options = get_option( 'wpacc_settings' );			
			$post_types = array('post', 'page');			
			if ( isset($this->options['selected_post_types']) ) {
				$post_types = array_merge( $post_types, $this->options['selected_post_types'] );
			}			
    	if ( in_array( $post_type, $post_types )) {
				add_meta_box('wp_add_custom_css', __( 'Custom CSS', 'wp-add-custom-css' ), array( $this, 'render_meta_box_content' ), $post_type, 'advanced', 'high');
			}
		}
		
		public function single_save( $post_id ) {
			if ( ! isset( $_POST['wp_add_custom_css_box_nonce'] ) || ! wp_verify_nonce( $_POST['wp_add_custom_css_box_nonce'], 'single_add_custom_css_box' ) ) {
				return;
			}
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { 
				return;
			}
			if ( 'page' == $_POST['post_type'] ) {
				if ( ! current_user_can( 'edit_page', $post_id ) )
					return;		
			} else {	
				if ( ! current_user_can( 'edit_post', $post_id ) )
					return;
			}

			$single_custom_css = wp_kses( $_POST['single_custom_css'], array( '\'', '\"' ) );
			update_post_meta( $post_id, '_single_add_custom_css', $single_custom_css );
		}
		
		public function render_meta_box_content( $post ) {
			wp_nonce_field( 'single_add_custom_css_box', 'wp_add_custom_css_box_nonce' );
	  	$single_custom_css = get_post_meta( $post->ID, '_single_add_custom_css', true );
			echo '<p>'.  sprintf( __( 'Add custom CSS rules for this %s', 'wp-add-custom-css' ), $post->post_type ). '</p> ';
			echo '<textarea id="single_custom_css" name="single_custom_css" style="width:100%; min-height:200px;">' . esc_attr( $single_custom_css ) . '</textarea>';
		}
		
		public function add_menu() {
			global $wpacc_settings_page;
			$wpacc_settings_page = add_menu_page( __('Wordpress Add Custom CSS', 'wp-add-custom-css'), __('Add Custom CSS', 'wp-add-custom-css'), 'manage_options', 'wp-add-custom-css_settings', array($this, 'create_settings_page'), plugin_dir_url( __FILE__ ) . '/images/icon.png');
		}
		
		public function create_settings_page() {
			$this->options = get_option( 'wpacc_settings' );
			?>
			<div class="wrap">
      	<h2><?php echo __('Wordpress Add Custom CSS', 'wp-add-custom-css'); ?></h2>
        <form id="worpress_custom_css_form" method="post" action="options.php">
        <?php settings_fields( 'wpacc_group' ); ?>
        <?php do_settings_sections( 'wp-add-custom-css_settings' ); ?>
				<?php submit_button( __('Save', 'wp-add-custom-css') ); ?>
				</form>
				<h3><?php echo __('Credits', 'wp-add-custom-css'); ?></h3>
				<ul>
					<li><?php echo __('"WP Add Custom CSS" is a plugin by', 'wp-add-custom-css'); ?> <a href="http://www.danieledesantis.net/" target="_blank" title="Daniele De Santis">Daniele De Santis</a></li>
				</ul>
			</div>
      <?php
		}
		
		public function print_section_info() {
			echo __('Write here the CSS rules you want to apply to the whole website.', 'wp-add-custom-css');
    }
		
		public function main_css_input() {
    	$custom_rules = isset( $this->options['main_custom_style'] ) ? esc_attr( $this->options['main_custom_style'] ) : '';
			echo '<textarea name="wpacc_settings[main_custom_style]" style="width:100%; min-height:300px;">' . $custom_rules . '</textarea>';
    }
		
		public function print_section_2_info() {
			echo __('Enable page specific CSS for the post types below.', 'wp-add-custom-css');
    }
		
		public function post_types_checkboxes() {
			$available_post_types = get_post_types( array('public' => true, '_builtin' => false), 'objects' );
			foreach ( $available_post_types as $post_type ) {
				if ( isset( $this->options['selected_post_types'] ) ) {
					$checked = in_array( $post_type->name, $this->options['selected_post_types'] ) ? ' checked' : '';
				} else {
					$checked = '';
				}
				echo '<div style="margin-bottom:10px"><input type="checkbox" name="wpacc_settings[selected_post_types][]" value="' . $post_type->name . '"' . $checked . '>' . $post_type->label . '</div>'; // output checkbox
			}
    }
		
		public function init_settings() {
			register_setting(
				'wpacc_group',
				'wpacc_settings'
			);	
			add_settings_section(
					'wpacc_main_style',
					__('Main CSS', 'wp-add-custom-css'),
					array( $this, 'print_section_info' ),
					'wp-add-custom-css_settings'
			);
			add_settings_field(
					'main_custom_style',
					__('CSS rules', 'wp-add-custom-css'),
					array( $this, 'main_css_input' ),
					'wp-add-custom-css_settings',
					'wpacc_main_style'          
			);
			add_settings_section(
					'wpacc_post_types',
					__('Post types', 'wp-add-custom-css'),
					array( $this, 'print_section_2_info' ),
					'wp-add-custom-css_settings'
			);
			add_settings_field(
					'selected_post_types',
					__('Available post types', 'wp-add-custom-css'),
					array( $this, 'post_types_checkboxes' ),
					'wp-add-custom-css_settings',
					'wpacc_post_types'          
			);
		}
		
		public function delete_options() {
			unregister_setting(
				'wpacc_group',
				'wpacc_settings'
			);
			delete_option('wpacc_settings');	
		}
		
		public function delete_custom_meta() {
			delete_post_meta_by_key('_single_add_custom_css');			
		}
		
		public static function add_wp_var($public_query_vars) {
    	$public_query_vars[] = 'display_custom_css';
    	return $public_query_vars;
		}
		
		public static function display_custom_css(){
    	$display_css = get_query_var('display_custom_css');
    	if ($display_css == 'css'){
				include_once (plugin_dir_path( __FILE__ ) . '/css/custom-css.php');
      	exit;
    	}
		}
		
		public function add_custom_css() {
			$this->options = get_option( 'wpacc_settings' );
			if ( isset($this->options['main_custom_style']) && $this->options['main_custom_style'] != '') {
				if ( function_exists('icl_object_id') ) {
					$css_base_url = site_url();
				} else {
					$css_base_url = get_bloginfo('url');
				}
				wp_register_style( 'wp-add-custom-css', $css_base_url . '?display_custom_css=css' );
				wp_enqueue_style( 'wp-add-custom-css' );	
			}
		}
		
		public function single_custom_css() {
			if ( is_single() || is_page() ) {
				global $post;				
				$enabled_post_types = array('post', 'page');				
				$this->options = get_option( 'wpacc_settings' );
				if ( isset($this->options['selected_post_types']) ) {
					$enabled_post_types = array_merge( $enabled_post_types, $this->options['selected_post_types'] );
				}
				if ( ! in_array( $post->post_type, $enabled_post_types )) {
					return;
				}				
				$single_custom_css = get_post_meta( $post->ID, '_single_add_custom_css', true );
				if ( $single_custom_css !== '' ) {
					$single_custom_css = str_replace ( '&gt;' , '>' , $single_custom_css );
					$output = "<style type=\"text/css\">\n" . $single_custom_css . "\n</style>\n";
					echo $output;
				}
			}
		}
		
		
    }
}

if(class_exists('Wpacc')) {
	add_action('template_redirect', array('Wpacc', 'display_custom_css'));
	register_uninstall_hook(__FILE__, array('Wpacc', 'uninstall'));
	$wpacc = new Wpacc();
}

if(isset($wpacc)) {	
    function wpacc_settings_link($links) {
        $settings_link = '<a href="admin.php?page=wp-add-custom-css_settings">' . __('Settings', 'wp-add-custom-css') . '</a>';
        array_unshift($links, $settings_link);
        return $links; 
    }
    add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'wpacc_settings_link');
}
?>