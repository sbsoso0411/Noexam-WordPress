<?php

/**
 * Primary class used to load the theme
 *
 * Some of the code used here was adapted from the Underscores theme from Automattic.
 * 
 * @copyright Copyright (c), Ryan Hellyer
 * @license http://www.gnu.org/licenses/gpl.html GPL
 * @author Ryan Hellyer <ryanhellyer@gmail.com>
 * @package Hellish Simplicity
 * @since Hellish Simplicity 1.5
 */
class Hellish_Setup {

	/**
	 * Constructor
	 * Add methods to appropriate hooks and filters
	 */
	public function __construct() {
		global $content_width;
		$content_width = 680;

		add_action( 'admin_init',                           array( $this, 'register_setting' ) );
		add_action( 'admin_menu',                           array( $this, 'admin_menu' ) );
		add_action( 'after_setup_theme',                    array( $this, 'theme_setup' ) );
		add_action( 'widgets_init',                         array( $this, 'widgets_init' ) );
		add_action( 'wp_enqueue_scripts',                   array( $this, 'stylesheet' ) );
		add_action( 'admin_init',                           array( $this, 'editor_stylesheet' ) );
		add_action( 'wp_enqueue_scripts',                   array( $this, 'comment_reply' ) );
		add_action( 'customize_register',                   array( $this, 'customize_register' ) );
		add_action( 'customize_render_control_header-text', array( $this, 'customizer_help' ) );
		add_filter( 'wp_title',                             array( $this, 'title_tag' ), 10, 2 );
	}

	/**
	 * Comment reply script
	 */
	public function comment_reply() {
		if ( is_singular() ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	/**
	 * Register settings
	 */
	public function register_setting() {
		add_option( 'header-text', 'Hellish<span>Simplicity</span><small>.tld</small>' );
		register_setting(
			'header',
			'header-text',
			array( $this, 'sanitize_options' )
		);
	}

	/**
	 * Validate inputs
	 * Perform security checks on inputted data
	 *
	 * @param array $input The data to be santised
	 */
	public function sanitize_options( $input ) {
		$allowed_tags = wp_kses_allowed_html( 'post' );
		return wp_kses( $input, $allowed_tags, '' );
	}

	/**
	 * Add the admin menu item
	 */
	public function admin_menu() {

		/**
		 * Adds the "Header" page to the admin area
		 */
		$theme_page = add_theme_page(
			__( 'Header', 'hellish' ),   // Page title
			__( 'Header', 'hellish' ),   // Menu title
			'edit_theme_options',        // Capability
			'header',                    // Menu slug
			array( $this, 'admin_page' ) // The page content
		);
		add_action( 'admin_head-' . $theme_page, array( $this, 'admin_scripts_and_styles' ), 99 );

		/**
		 * Adds the "Customize" page to the admin area
		 */
		add_theme_page(
			__( 'Customize', 'hellish' ),
			__( 'Customize', 'hellish' ),
			'edit_theme_options',
			'customize.php'
		);
	}
	
	/*
	 * Add scripts and styles to the theme settings page
	 */
	public function admin_scripts_and_styles() {
		?>
		<script>
		jQuery(document).ready(function($) {
			$('#header-text').keyup(function(event) {
				var header_text = $('#header-text').val();
				$('#header-preview').html(header_text);
			});
		});
		</script>
		<style>
		#header-preview {
			float: left;
			font-family: sans-serif;
			font-size: 55px;
			font-weight: bold;
			letter-spacing: -.05em;
			text-shadow: 1px 1px 0 rgba(0,0,0,0.3);
			margin: 0 0 0 2%;
		}
		#header-preview a {
			color: #000;
		}
		#header-preview a:hover {
			text-decoration: none;
		}
		#header-preview span {
			color: #d90000;
		}
		#header-preview small {
			font-size: 28px;
		}
		#header-text {
			width: 100%;
			max-width: 600px;
		}
		</style><?php
	}

	/**
	 * The admin page contents
	 */
	public function admin_page() { ?>
	<div class="wrap">
		<div id="icon-themes" class="icon32"><br /></div>
		<h2><?php _e( 'Custom Header', 'hellish' ); ?></h2><?php

		// Display notice when page is updated
		if ( isset( $_REQUEST['settings-updated'] ) ) { ?>
		<div class="updated fade"><p><strong><?php _e( 'Options saved', 'hellish' ); ?></strong></p></div><?php
		} ?>

		<form id="hellish-form" action="options.php" method="post">
			<?php settings_fields( 'header' ); ?>

			<h3><?php _e( 'Header Text', 'hellish' ); ?></h3>

			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row">Preview</th>
						<td>
							<div id="header-preview"><?php
								// Output the header text (contains HTML, therefore not escaped before output)
								echo get_option( 'header-text' );
							?></div>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">Header text</th>
						<td>
							<p>
								<textarea id="header-text" name="header-text"><?php
									// Output the header text
									echo esc_textarea( get_option( 'header-text' ) );
								?></textarea>
							</p>
							<p>
								<?php _e( 'Example text:', 'hellish' ); ?> <code>Hellish&lt;span&gt;Simplicity&lt;/span&gt;&lt;small&gt;.com&lt;/small&gt;</code>
							</p>
						</td>
					</tr>
				</tbody>
			</table>

			<p>
				<input type="submit" class="button" id="save" name="save" value="<?php _e( 'Save &raquo;', 'hellish' ) ?>" />
			</p>
		</form>
	</div><?php

	}

	/**
	 * Load editor stylesheet
	 */
	public function editor_stylesheet() {
		add_editor_style( 'editor-style.css' );
	}

	/**
	 * Load stylesheet
	 */
	public function stylesheet() {
		if ( ! is_admin() ) {
			wp_enqueue_style( 'style', get_stylesheet_directory_uri() . '/style-compiled.css' );
		}
	}

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 */
	public function theme_setup() {
	
		// Make theme available for translation
		load_theme_textdomain( 'hellish', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head
		add_theme_support( 'automatic-feed-links' );

		// Enable support for Post Thumbnails
		add_theme_support( 'post-thumbnails' );
		add_image_size( 'excerpt-thumb', 250, 350 );
		add_image_size( 'attachment-page', 1000, 1500 );
	}

	/**
	 * Register widgetized area and update sidebar with default widgets
	 */
	public function widgets_init() {
		register_sidebar(
			array(
				'name'          => __( 'Sidebar', 'hellish' ),
				'id'            => 'sidebar',
				'before_widget' => '<aside id="%1$s" class="%2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			)
		);
	}


	/**
	 * Implements Page Styler theme options into Theme Customizer
	 *
	 * @param $wp_customize Theme Customizer object
	 */
	public function customize_register( $wp_customize ) {
	
		// Theme Footer
		$wp_customize->add_setting( 'header-text', array(
			'type'              => 'option',
			'sanitize_callback' => array( $this, 'sanitize_options' ),
			'capability'        => 'edit_theme_options',
		) );
		$wp_customize->add_section( 'header_text', array(
			'title'             => __( 'Header Text', 'hellish' ),
			'priority'          => 10,
		) );
		$wp_customize->add_control( 'header-text', array(
			'section'           => 'header_text',
			'label'             => __( 'Header text', 'hellish' ),
			'type'              => 'text',
		) );
	
	}

	/**
	 * Adding extra helpful information to the customizer
	 */
	public function customizer_help() {
		echo '
		<li>
			<p>
				' . __( 'Example text:', 'hellish' ) . ' <code>Hellish&lt;span&gt;Simplicity&lt;/span&gt;&lt;small&gt;.tld&lt;/small&gt;</code>
			</p>
		</li>';
	}

	/**
	 * Adding extra functionality to title tags
	 * For more advanced title tag functionality, please use an SEO plugin
	 *
	 * @param string $title Default title text for current view.
	 * @param string $sep Optional separator
	 * @return string Filtered title
	 */
	public function title_tag( $title ) {

		// Add the site name.
		$title .= get_bloginfo( 'name' );

		return $title;
	}

}
new Hellish_Setup;

function wpdocs_custom_excerpt_length( $length ) {
    return 15;
}
add_filter( 'excerpt_length', 'wpdocs_custom_excerpt_length', 999 );
