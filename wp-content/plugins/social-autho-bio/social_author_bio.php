<?php
/*
Plugin Name: Social Author Bio
Plugin URI: http://nickpowers.info/wordpress-plugins/social-author-bio/
Description: Social Author Bio adds bio box on posts with social icons
Author: Nick Powers
Version: 2.4
Author URI: http://www.nickpowers.info
*/

define( 'SOCIALAUTHORBIO_INCLUDE', plugin_dir_path(__FILE__).'include/' );


if (!class_exists('WPAlchemy_MetaBox')) {
	require_once( SOCIALAUTHORBIO_INCLUDE . 'php/MetaBox.php' );
}

// Require class files
if (!class_exists('WPPluginAnalytics')) {
	require_once( SOCIALAUTHORBIO_INCLUDE . 'php/WPPluginAnalytics.php' );
}

// Call new classes
if (class_exists('WPPluginAnalytics')) {

	new WPPluginAnalytics('social-autho-bio', __FILE__);
}

// Configure Meta - Page/Post
$custom_checkbox = new WPAlchemy_MetaBox(array
(
	'id' => 'socialauthorbio_custom_checkbox_meta',
	'title' => 'Disable Social Author Bio',
	'types' => array( 'post', 'page' ),
	'template' => SOCIALAUTHORBIO_INCLUDE . 'php/checkbox-meta.php',
));

$SocialAuthorBio = new SocialAuthorBio( $custom_checkbox );

class SocialAuthorBio {

	private	$socials,
		$size,
		$custom,
		$custom_size,
		$icons,
		$googlecount = 0,
		$custom_checkbox;
	

	function SocialAuthorBio( $obj1 ) {

		$this->__construct( $obj1 );
	}

	function __construct( $obj1 ) {

		// Setup Meta Objects
		$this->custom_checkbox = $obj1;

		// Initial Variable Init
		$this->socials = array();
		$this->set_socials();
		$this->size = count($this->socials);

		$this->custom = array();
		$this->set_custom();
		$this->custom_size = count($this->custom);


		// Plugin Settings Link
		$plugin = plugin_basename(__FILE__);
		add_filter( "plugin_action_links_$plugin", array( &$this, 'filterPluginActions' ), 10, 2 );

		// Social Icons Filter
		add_filter( 'social_author_icons', array ( &$this, 'social_icons'), 10, 1);

		// Add shortcodes
		add_shortcode( 'social-bio', array($this, 'shortcode') );
		add_shortcode( 'social_bio_icons', array($this, 'shortcode_icons') );

		// Admin Menu
		add_action('admin_menu', array(&$this, 'admin_menu'));

		// Style
		add_action("wp_head", array (&$this, "style"));

		// User Profile Extras
		add_action("show_user_profile", array (&$this, "profile_fields"));
		add_action("edit_user_profile", array (&$this, "profile_fields"));
		add_action("personal_options_update", array (&$this, "save_profile_fields"));
		add_action("edit_user_profile_update", array (&$this, "save_profile_fields"));

		// The Content Hook (for page and post)
		add_action("the_content", array (&$this, "display_content"));

		// Google Authorship Link
		add_action( 'loop_end', array( &$this, 'google_author' ) );

		// Test Comments
		add_action ( 'comment_author_byline', array( &$this, 'comment_author' ) );
	}

	function shortcode_icons( $atts, $content=null, $code="" ) {

		// Initialize variables
		$output = '';
		$id = get_the_author_meta('ID');

		if ( !empty($atts['id']) ) {
			$id = $atts['id'];
		}

		// Get options
		$options["shortcode_icons"] = get_option("bio_on_shortcode_icons");

		// Display shortcode icons with current page/post author
		if ($options["shortcode_icons"] && empty($atts['id']) && !get_the_author_meta("social_bio_status")) {
			$output = apply_filters('social_author_icons', get_the_author_meta( 'ID' ) );
				
		}
		// Display shortcode with specified ID
		elseif ($options["shortcode_icons"] && !get_the_author_meta("social_bio_status", $id)){
			$output = apply_filters('social_author_icons', $atts['id'] );
		}

		return $output;
	}

	function shortcode( $atts, $content=null, $code="" ) {

		// Initialize variables
		$output = '';
		$id = get_the_author_meta('ID');

		if ( !empty($atts['id']) ) {
			$id = $atts['id'];
		}

		// Get options
		$options["shortcode"] = get_option("bio_on_shortcode");

		// Display shortcode with current page/post author
		if ($options["shortcode"] && empty($atts['id']) && !get_the_author_meta("social_bio_status")) {
	
			$output = $this->display(get_the_author_meta('ID'));
				
		}
		// Display shortcode with specified ID
		elseif ($options["shortcode"] && !get_the_author_meta("social_bio_status", $id)){
			$output = $this->display($atts['id']);
		}

		return $output;
	}
	
	function display_content($content) {

		// Initialize Variables
		$output = '';
		$box = '';

		// Get Options
		$options["page"] = get_option("bio_on_page");
		$options["post"] = get_option("bio_on_post");
		$options["where"] = get_option("bio_where");

		// Display bio on bottom of page/post
		if (!get_the_author_meta("social_bio_status") && ((is_single() && $options["post"]) || (is_page() && $options["page"]))) {
			$box = $this->display(get_the_author_meta('ID'));
		}

		if( "0" == $options["where"] ) {
			$output = $content;
			$output .= $box;
		}
		else
		{
			$output = $box;
			$output .= $content;
		}

		return $output;
	}
	
	function filterPluginActions ($links, $file) {

		// Create Settings Link on Plugin Admin Page
		$settings_link = '<a href="admin.php?page=sabp-general">Settings</a>';
		array_push($links, $settings_link);

		return $links;
	}

	function admin_menu() {

		// Main Menu
		add_menu_page('Social Author Bio', 'Social Author Bio', 'manage_options', 'sabp-general','',plugins_url('social-autho-bio/images/admin/icon.png'));

		// Sub Menus
		add_submenu_page('sabp-general', 'General', 'General', 'manage_options', 'sabp-general', array( $this, 'admin_general' ));
		add_submenu_page('sabp-general', 'Custom Links', 'Custom Links', 'manage_options', 'sabp-customlinks', array( $this, 'custom_links' ));
		add_submenu_page('sabp-general', 'Advanced HTML/Style', 'Advanced HTML/Style', 'manage_options', 'sabp-html', array( $this, 'admin_html' ));
		
	}

	function admin_general() {

		// Initialize Variables
		$message = '';
		$output = '';

		// Update options from post
		if (isset($_POST["action"]) && $_POST["action"] == "update") {
			// Page
			(isset($_POST["show_pages"]) && $_POST["show_pages"] == "on") ? update_option("bio_on_page", "checked") : update_option("bio_on_page", "");

			// Post
			(isset($_POST["show_posts"]) && $_POST["show_posts"]) == "on" ? update_option("bio_on_post", "checked") : update_option("bio_on_post", "");

			// Shortcode
			(isset($_POST["show_shortcode"]) && $_POST["show_shortcode"]) == "on" ? update_option("bio_on_shortcode", "checked") : update_option("bio_on_shortcode", "");

			// Shortcode Icons
			(isset($_POST["show_shortcode_icons"]) && $_POST["show_shortcode_icons"]) == "on" ? update_option("bio_on_shortcode_icons", "checked") : update_option("bio_on_shortcode_icons", "");

			// Google
			( isset( $_POST['google_authorship'] ) && 'on' == $_POST['google_authorship'] ) ? update_option( 'bio_googleauthor', 'checked' ) : update_option( 'bio_googleauthor', '' );

			// Prefix
			if (isset($_POST["bio_prefix"])) update_option("bio_prefix", $_POST["bio_prefix"]);

			// Where to play bio box
			if( isset($_POST["bio_where"] ) ) update_option("bio_where", $_POST["bio_where"] );

			// Access Level
			if (isset($_POST["bio_who"])) update_option("bio_who", $_POST["bio_who"]);

			// Background Color
			if (isset($_POST["bio_bg"])) update_option("bio_bg", $_POST["bio_bg"]);

			// Background Color
			if (isset($_POST["bio_avatarsize"])) update_option("bio_avatarsize", $_POST["bio_avatarsize"]);

			// Enabled Socials
			for ($loop = 0; $loop < $this->size; ++$loop) {

				$temp = 'bio_social_' .$this->socials[$loop][1];

				if (isset($_POST[$temp]) && $_POST[$temp] == "on") {

					update_option($temp, "checked");
				}
				else {

					update_option($temp, "");
				}
			}

			// Enabled Custom Socials
			for ($loop = 0; $loop < $this->custom_size; ++$loop) {

				$temp = 'bio_social_' .$this->custom[$loop][1];

				if (isset($_POST[$temp]) && $_POST[$temp] == "on") {

					update_option($temp, "checked");
				}
				else {

					update_option($temp, "");
				}
			}


			// Saved Message
			$message = '<div id="message" class="updated fade"><p><strong>Options Saved</strong></p></div>';
		}

		// Get Options
		$options["page"]	= get_option("bio_on_page");
		$options["post"]	= get_option("bio_on_post");
		$options["shortcode"]	= get_option("bio_on_shortcode");
		$options["shortcode_icons"]	= get_option("bio_on_shortcode_icons");
		$options['googleauthor']= get_option( 'bio_googleauthor', 'checked' );
		$options["bio_prefix"]	= get_option("bio_prefix");
		$options["bio_bg"]	= get_option("bio_bg");
		$options["bio_who"]	= get_option("bio_who",1);
		$options["bio_where"]	= get_option("bio_where",0);
		$options["bio_avatarsize"] = get_option( "bio_avatarsize", "80");

		for ($loop = 0; $loop < $this->size; ++$loop) {

			$temp = 'bio_social_' .$this->socials[$loop][1];
			$options[$temp] = get_option($temp);
		}

		for ($loop = 0; $loop < $this->custom_size; ++$loop) {

			$temp = 'bio_social_' .$this->custom[$loop][1];
			$options[$temp] = get_option($temp);
		}

		$output .= '<div class="wrap">'. $message.
		'<div id="icon-options-general" class="icon32"><br /></div>
		<h2>Social Author Bio Settings</h2>
		<form method="post" action="">
		<input type="hidden" name="action" value="update" />
		<h3>When to Display Social Author Bio</h3>
		<input name="show_pages" type="checkbox" id="show_pages" '.$options["page"].' /> Pages<br />
		<input name="show_posts" type="checkbox" id="show_posts" '.$options["post"].' /> Posts<br />
		<input name="show_shortcode" type="checkbox" id="show_shortcode" '.$options["shortcode"].' /> Shortcode [social-bio]<br/>
		<input name="show_shortcode_icons" type="checkbox" id="show_shortcode_icons" '.$options["shortcode_icons"].' /> Shortcode [social_bio_icons]<br/>
		<h3>Where on page to display Social Author Bio</h3>
		<i>(when auto displayed on page/post) &nbsp;</i>';

		// Show Radio Options for where to display Social Author Bio Box
		if( $options["bio_where"] == 0)	{ $output .= '<input type="radio" name="bio_where" value="0" CHECKED> Bottom '; }
		else				{ $output .= '<input type="radio" name="bio_where" value="0"> Bottom '; }
		if( $options["bio_where"] == 1)	{ $output .= '<input type="radio" name="bio_where" value="1" CHECKED> Top'; }
		else				{ $output .= '<input type="radio" name="bio_where" value="1"> Top'; }

		$output .='
		
		<h3>Which role to start using Social Author Bio</h3>
		<select name="bio_who">';

		// Show Options Values for Access Level to use plugin
		if ($options["bio_who"] == 1)	{ $output .= '<option value="1" SELECTED>Contributor</option>'; }
		else				{ $output .= '<option value="1">Contributor</option>'; }
		if ($options["bio_who"] == 2)	{ $output .= '<option value="2" SELECTED>Subscriber</option>'; }
		else				{ $output .= '<option value="2">Subscriber</option>'; }
		if ($options["bio_who"] == 3)	{ $output .= '<option value="3" SELECTED>Author</option>'; }
		else				{ $output .= '<option value="3">Author</option>'; }
		if ($options["bio_who"] == 4)	{ $output .= '<option value="4" SELECTED>Editor</option>'; }
		else				{ $output .= '<option value="4">Editor</option>'; }
		if ($options["bio_who"] == 5)	{ $output .= '<option value="5" SELECTED>Administrator</option>'; }
		else				{ $output .= '<option value="5">Administrator</option>'; }

		$output .= '</select><br/>
		<h3>Background color</h3>
		Color: #<input name="bio_bg" type="text" id="bio_bg" value="'.$options["bio_bg"].'" /><br/>
		<h3>Prefix to display before author name</h3>
		Prefix: <input name="bio_prefix" type="text" id="bio_prefix" value="'.$options["bio_prefix"].'" /><br />
		<h3>Avatar size</h3>
		<input name="bio_avatarsize" type="text" id="bio_avatarsize" value="'.$options["bio_avatarsize"].'" size="3"/><br />
		
		<h3>Google authorship (rich snippets)</h3>
		<input name="google_authorship" type="checkbox" id="google_authorship"'.$options['googleauthor'].' /> Enable<br /><br />
		
		<h3>Select social icons to allow authors to choose from:</h3>
		<table><tr>';

		$count = 0;

		// Show Icons and Allow Admin to enable/disable Social Links
		for ($loop = 0; $loop < $this->size; ++$loop) {

			$temp = 'bio_social_' .$this->socials[$loop][1];

			++$count;
			$output .= '<td align="center"><img src="'.plugins_url($this->socials[$loop][3], __FILE__).'"><br>
			<input name="'.$temp.'" type="checkbox" id="'.$temp.'" '.$options[$temp].' /></td>';

			if (!($count % 5) && ($count < $this->size)) {
				$output .= '</tr><tr>';
			}
		}
		$output .= '</tr></table>';

		$count = 0;

		if (isset($this->custom[0])) {
			$output .= '<h3>Select <stonrg>custom</strong> social icons to allow authors to choose from:</h3>
			<table><tr><br/>';

			// Show Custom Icons and Allow Admin to enable/disable Social Links
				for ($loop = 0; $loop < $this->custom_size; ++$loop) {
	
				$temp = 'bio_social_' .$this->custom[$loop][1];

				++$count;
				$output .= '<td align="center"><img src="'.$this->custom[$loop][3].'"><br>
				<input name="'.$temp.'" type="checkbox" id="'.$temp.'" '.$options[$temp].' /></td>';

				if (!($count % 5) && ($count < $this->custom_size)) {
					$output .= '</tr><tr>';
				}
			}
		}

		$output .= '</tr></table><br/>'.
		'<input type="submit" class="button-primary" value="Save Changes" />
		</form>
		</div>';

		echo $output;
	}

	function custom_links() {

		// Initialize Variables
		$message	= '';
		$output		= '';
		$link		= array();
		$links		= array();

		// Update options from post
		if (isset($_POST["action"]) && $_POST["action"] == "update") {


			for ($loop = 1; $loop < 11; ++$loop) {


				$name_post	= 'bio_custom_name'.$loop;
				$image_post	= 'bio_custom_image'.$loop;
				$prepend_post	= 'bio_custom_prepend'.$loop;
				$append_post	= 'bio_custom_append'.$loop;
				

				// Post Variables
				if (isset($_POST[$name_post]) && !empty($_POST[$name_post])) {
				

					// Initialize Variables
					$name		= preg_replace( "/[^A-Za-z0-9]/","", $_POST[$name_post]);
					$bio		= strtolower('bio_'.$name);
					$display	= 'display_'.$bio;


					if (isset($_POST[$image_post]))		{ $image = $_POST[$image_post];		}
					else 					{ $image = '';				}
					if (isset($_POST[$prepend_post]))	{ $prepend= $_POST[$prepend_post];	}
					else					{ $prepend = '';			}
					if (isset($_POST[$append_post]))	{ $append = $_POST[$append_post];	}
					else					{ $append = '';				}

					array_push( $links, array ($name, $bio, $display, $image, '', $prepend, $append) );
				}
			}

			update_option('bio_customlinks', $links);

			$message = '<div id="message" class="updated fade"><p><strong>Options Saved</strong></p></div>';


		}

		// Get Options
		$links	= get_option("bio_customlinks");

		$output .= '<div class="wrap">'. $message.
		'<div id="icon-link-manager" class="icon32"><br /></div>
		<h2>Social Author Bio Custom Links</h2>
		<form method="post" action="">
		<input type="hidden" name="action" value="update" />';

	
		for ($loop =1; $loop < 11; ++$loop) {

			// Initialize Variables
			$name		= '';
			$image		= '';
			$prepend	= '';
			$append		= '';

			if (isset($links[$loop-1])) {

			
				$link		= $links[$loop-1];

				$name 		= $link[0];
				$image		= $link[3];
				$prepend	= $link[5];
				$append		= $link[6];
			}

			$output .= '<table class="widefat">
			<thead><tr><th colspan="4">Custom Link '.$loop.'</th></th></tr></thead>
			<tbody><tr><th>Link Name</th><th>Image URL</th></tr>
			<tr><td><input type="text" name="bio_custom_name'.$loop.'" id="bio_custom_name'.$loop.'" size="80" maxlength="160" value="'.$name.'" /></td>
			<td><input type="text" name="bio_custom_image'.$loop.'" id="bio_custom_image'.$loop.'" size="80" maxlength="160" value="'.$image.'" /></td></tr>
			</tbody>

			<thead><tr><th>Prepend URL Text</th><th>Append URL Text</th></tr></thead>
			<tbody><tr><td><input type="text" name="bio_custom_prepend'.$loop.'" id="bio_custom_prepend'.$loop.'" size="80" maxlength="160" value="'.$prepend.'" /></td>
			<td><input type="text" name="bio_custom_append'.$loop.'" id="bio_custom_append'.$loop.'" size="80" maxlength="160" value="'.$append.'" /></td></tr></tbody>
			</table><br/><br/>';

		}

		$output .= '<input type="submit" class="button-primary" value="Save Changes" />
		</form>
		</div>';

		echo $output;
	}

	function admin_html() {


		// Initialize Variables
		$message		= '';
		$output			= '';
		$default_format		= '<div id="author-bio-box">%avatar%<span class="author-name">%prefix% %name% (<a href="%author_link%">%post_count% Posts</a>)</span>'.
					'<p>%author_desc%</p><div class="bio-socials">%socials%</div></div><br/>';
		$default_style		= '<style type="text/css">'."\n".
					'#author-bio-box {'."\n".
					"\t".'float:left;'."\n".
					"\t".'width:632px;'."\n".
					"\t".'background: #%bgcolor%;'."\n".
					"\t".'border: 1px solid #bbb;'."\n".
					"\t".'box-shadow: 5px 5px 2px #888;'."\n".
					"\t".'padding: 5px;'."\n".
					'}'."\n".

					'#author-bio-box img {'."\n".
					"\t".'float: left;'."\n".
					"\t".'margin-right: 10px;'."\n".
					"\t".'margin-bottom: 2px;'."\n".
					'}'."\n".

					'#author-bio-box .author-name {'."\n".
					"\t".'font-weight: bold;'."\n".
					"\t".'margin: 0px;'."\n".
					"\t".'font-size: 14px;'."\n".
					'}'."\n".

					'#author-bio-box p {'."\n".
					"\t".'font-size: 10px;'."\n".
					"\t".'line-height: 14px;'."\n".
					'}'."\n".

					'#author-bio-box thead th {'."\n".
					"\t".'border: 0;'."\n".
					'}'."\n".

					'#author-bio-box tbody {'."\n".
					"\t".'border: 0;'."\n".
					'}'."\n".

					'.bio-spacer { min-height:44px; padding: 1px; display: block; clear: both; border:1px;}'."\n".

					'.bio-socials {'."\n".
					"\t".'border-top:solid 1px;'."\n".
					"\t".'border-bottom:none;'."\n".
					"\t".'border-left:none;'."\n".
					"\t".'border-right:none;'."\n".
					"\t".'width: 628px;'."\n".
					"\t".'height: 32px;'."\n".
					"\t".'clear: both;'."\n".
					'}'."\n".

					'</style>';


		// Update options from post
		if (isset($_POST["action"]) && $_POST["action"] == "update") {

			// Format
			update_option("bio_format", $_POST["format"]);

			// Style
			update_option("bio_style", $_POST["style"]);

			// Saved Message
			$message = '<div id="message" class="updated fade"><p><strong>Options Saved</strong></p></div>';
		}

		// Get Options
		$options['format']	= get_option('bio_format', $default_format);
		$options['style']	= get_option('bio_style', $default_style);

		$output .= '<div class="wrap">'. $message.
		'<div id="icon-options-general" class="icon32"><br /></div>
		<h2>Social Author Bio HTML Settings</h2>
		<form method="post" action="">
		<input type="hidden" name="action" value="update" />

		You can insert the following variables into the HTML template by surrounding them with percent signs (%).<br/><br/>

		%avatar% - An image tag of the user profile<br/>
		%prefix% - The prefix provided in the general settings section<br/>
		%name% - The authors name as defined in the user profile<br/>
		%author_link% - The URL of the authors link (archive on WordPress)<br/>
		%post_count% - The numver of posts authored by this author<br/>
		%author_desc% - The Biographical info defined in the user profile<br/>
		%socials% - The social links<br/>
		%home% - The home URL<br/>

		<h3>Edit HTML for Social Author Bio</h3>
		<textarea name="format" cols="160" rows="3">'.htmlspecialchars_decode(stripslashes($options["format"]), ENT_QUOTES).'</textarea><br/><br/>

		You can insert the following variables into the Style template by surrounding them with percent signs (%).<br/><br/>

		%bgcolor% - The Background color provided in the general settings section<br/>

		<h3>Edit Style for Social Author Bio</h3>
		<textarea name="style" cols="80" rows="15">'.htmlspecialchars_decode(stripslashes($options["style"]), ENT_QUOTES).'</textarea><br/><br/>

		<input type="submit" class="button-primary" value="Save Changes" />
		</form>
		</div>';


		echo $output;
	}

	function style() {

		// Initialize Variables
		$default_style		= '<style type="text/css">'."\n".
					'#author-bio-box {'."\n".
					"\t".'width:632px;'."\n".
					"\t".'background: #%bgcolor%;'."\n".
					"\t".'border: 1px solid #bbb;'."\n".
					"\t".'box-shadow: 5px 5px 2px #888;'."\n".
					"\t".'padding: 5px;'."\n".
					'}'."\n".

					'#author-bio-box img {'."\n".
					"\t".'float: left;'."\n".
					"\t".'margin-right: 10px;'."\n".
					"\t".'margin-bottom: 2px;'."\n".
					'}'."\n".

					'#author-bio-box .author-name {'."\n".
					"\t".'font-weight: bold;'."\n".
					"\t".'margin: 0px;'."\n".
					"\t".'font-size: 14px;'."\n".
					'}'."\n".

					'#author-bio-box p {'."\n".
					"\t".'font-size: 10px;'."\n".
					"\t".'line-height: 14px;'."\n".
					'}'."\n".

					'#author-bio-box thead th {'."\n".
					"\t".'border: 0;'."\n".
					'}'."\n".

					'#author-bio-box tbody {'."\n".
					"\t".'border: 0;'."\n".
					'}'."\n".

					'.bio-spacer { min-height:44px; padding: 1px; display: block; clear: both; border:1px;}'."\n".

					'.bio-socials {'."\n".
					"\t".'border-top:solid 1px;'."\n".
					"\t".'border-bottom:none;'."\n".
					"\t".'border-left:none;'."\n".
					"\t".'border-right:none;'."\n".
					"\t".'width: 628px;'."\n".
					"\t".'height: 32px;'."\n".
					"\t".'clear: both;'."\n".
					'}'."\n".

					'</style>';
		// Get Options
		$options["bio_bg"]	= get_option("bio_bg", '#FFFFFF');
		$options['style']	= get_option('bio_style', $default_style);

		// Search
		$token = array ('%bgcolor%');

		// Replace
		$replace = array ($options["bio_bg"]);

		// Parse Search and Replace
		

		$output = htmlspecialchars_decode(stripslashes(str_replace($token, $replace, $options['style'])));

		echo $output;
	}

	function profile_fields($user) {

		// Initialize Varibales
		$output = '';

		// Get Options
		$options["bio_who"] = get_option("bio_who",1);
		$options['googleauthor'] = get_option( 'bio_googleauthor', 'checked' );

		$access = array('user roles', 'edit_posts','read','edit_published_posts', 'moderate_comments','activate_plugins');
		$authorized = $access[$options["bio_who"]];

		if (user_can($user->ID, $authorized)) {

			for ($loop = 0; $loop < $this->size; ++$loop) {
				$temp = 'bio_social_'.$this->socials[$loop][1];
				$options[$temp] = get_option($temp);
			}

			for ($loop = 0; $loop < $this->custom_size; ++$loop) {
				$temp = 'bio_social_'.$this->custom[$loop][1];
				$options[$temp] = get_option($temp);
			}

			$output .= '<h3>Social Author information</h3>
			<table class="form-table">
			<tr>
			<th><label for="social_bio_status">Turn on/off Social Author Bio</label></th>
			<td>';

			if (!get_the_author_meta("social_bio_status", $user->ID)) {

				$output .= '<input type="radio" name="social_bio_status" value="0" checked />On&nbsp;&nbsp;'.
				'<input type="radio" name="social_bio_status" value="1" />Off</td>';
			}
			else {

				$output .= '<input type="radio" name="social_bio_status" value="0" />On&nbsp;&nbsp;'.
				'<input type="radio" name="social_bio_status" value="1" checked />Off</td>';
			}

			if ( 'checked' == $options['googleauthor'] ) {
				$output .= '<tr><th><label for="googleauthor">Google Plus ID</label></th>'.
				'<td><img src="'.plugins_url("images/admin/author_info.png", __FILE__).'">&nbsp;'.
				'<input type="text" name="googleauthorid" class="regular-text" value="'.get_the_author_meta( 'googleauthorid', $user->ID ).'" />';

				if ( get_the_author_meta( 'displaygoogleauth', $user->ID ) ) {
					$output .= '<input type="radio" name="displaygoogleauth" value="1" checked /> On&nbsp;&nbsp;'.
					'<input type="radio" name="displaygoogleauth" value="0" /> Off<br/>';
				}
				else {
					$output .= '<input type="radio" name="displaygoogleauth" value="1" /> On&nbsp;&nbsp;'.
					'<input type="radio" name="displaygoogleauth" value="0" checked /> Off<br/>';
				}


				$output .= '<span class="description">Will be used to display your Google profile image in Google search results</span><br />'.
				'<span class="description">Add a "Contributor to" link to <a href="'.get_author_posts_url( $user->ID ).'">'.get_author_posts_url( $user->ID ).'</a>'.
				' on your Google profile.</span></td></tr>';
			}


			$output .= '</tr><tr><th><label for="website_icon">Website Icon</label></th><td>';

			switch (get_the_author_meta("website_icon", $user->ID)) {

				case 1:
					$output .= '<input type="radio" name="website_icon" value="0" /><img src="'.plugins_url("images/Wordpress.png", __FILE__).'">&nbsp;&nbsp;'.
					'<input type="radio" name="website_icon" value="1" checked /><img src="'.plugins_url("images/Blogger.png", __FILE__).'">&nbsp;&nbsp;'.
					'<input type="radio" name="website_icon" value="2" /><img src="'.plugins_url("images/Website.png", __FILE__).'"><br/>';
					break;
				case 2:
					$output .= '<input type="radio" name="website_icon" value="0" /><img src="'.plugins_url("images/Wordpress.png", __FILE__).'">&nbsp;&nbsp;'.
					'<input type="radio" name="website_icon" value="1" /><img src="'.plugins_url("images/Blogger.png", __FILE__).'">&nbsp;&nbsp;'.
					'<input type="radio" name="website_icon" value="2" checked /><img src="'.plugins_url("images/Website.png", __FILE__).'"><br/>';
					break;
				default:
					$output .= '<input type="radio" name="website_icon" value="0" checked /><img src="'.plugins_url("images/Wordpress.png", __FILE__).'">&nbsp;&nbsp;'.
					'<input type="radio" name="website_icon" value="1" /><img src="'.plugins_url("images/Blogger.png", __FILE__).'">&nbsp;&nbsp;'.
					'<input type="radio" name="website_icon" value="2" /><img src="'.plugins_url("images/Website.png", __FILE__).'"><br/>';
			}

			$output .= '<span class="description">Please choose website icon type to display.</span></td></tr>';

			for ($loop = 0; $loop < $this->size; ++$loop) {

				$temp = 'bio_social_'.$this->socials[$loop][1];

				if ($options[$temp]) {

					$output .= '<tr><th><label for="'.$this->socials[$loop][1].'">'.$this->socials[$loop][0].'</label></th>'.
					'<td><img src="'.plugins_url($this->socials[$loop][3], __FILE__).'"> &nbsp;'.
					'<input type="text" name="'.$this->socials[$loop][1].'" id="'.$this->socials[$loop][1].'" value="'.
					get_the_author_meta($this->socials[$loop][1], $user->ID).'" class="regular-text" />';

					if (get_the_author_meta($this->socials[$loop][2], $user->ID)) {

						$output .= '<input type="radio" name="'.$this->socials[$loop][2].'" value="1" checked /> On&nbsp;&nbsp;'.
						'<input type="radio" name="'.$this->socials[$loop][2].'" value="0" /> Off<br/>';
					}
					else {

						$output .= '<input type="radio" name="'.$this->socials[$loop][2].'" value="1" /> On&nbsp;&nbsp;'.
						'<input type="radio" name="'.$this->socials[$loop][2].'" value="0" checked /> Off<br/>';
					}

					$output .= '<span class="description">';

					$prep = 0;
					if (!empty($this->socials[$loop][5])) {

						$output .= 'Prepended with '.$this->socials[$loop][5];
						$prep = 1;
					}

					if (!empty($this->socials[$loop][6])) {

						if ($prep == 1) {
							$output .= '<br/>';
						}

						$output .= 'Appended with '.$this->socials[$loop][6];
					}
					$output .= '</span></td></tr>';
				}
			}

			for ($loop = 0; $loop < $this->custom_size; ++$loop) {

				$temp = 'bio_social_'.$this->custom[$loop][1];

				if ($options[$temp]) {

					$output .= '<tr><th><label for="'.$this->custom[$loop][1].'">'.$this->custom[$loop][0].'</label></th>'.
					'<td><img src="'.$this->custom[$loop][3].'"> &nbsp;'.
					'<input type="text" name="'.$this->custom[$loop][1].'" id="'.$this->custom[$loop][1].'" value="'.
					get_the_author_meta($this->custom[$loop][1], $user->ID).'" class="regular-text" />';

					if (get_the_author_meta($this->custom[$loop][2], $user->ID)) {

						$output .= '<input type="radio" name="'.$this->custom[$loop][2].'" value="1" checked /> On&nbsp;&nbsp;'.
						'<input type="radio" name="'.$this->custom[$loop][2].'" value="0" /> Off<br/>';
					}
					else {

						$output .= '<input type="radio" name="'.$this->custom[$loop][2].'" value="1" /> On&nbsp;&nbsp;'.
						'<input type="radio" name="'.$this->custom[$loop][2].'" value="0" checked /> Off<br/>';
					}

					$output .= '<span class="description">';

					$prep = 0;
					if (!empty($this->custom[$loop][5])) {

						$output .= 'Prepended with '.$this->custom[$loop][5];
						$prep = 1;
					}

					if (!empty($this->custom[$loop][6])) {

						if ($prep == 1) {
							$output .= '<br/>';
						}

						$output .= 'Appended with '.$this->custom[$loop][6];
					}
					$output .= '</span></td></tr>';
				}
			}

			$output .= '</table>';

			echo $output;
		}
	}

	function save_profile_fields($user_id) {

		if (!current_user_can( 'edit_user', $user_id )) return false;

		if (isset($_POST['social_bio_status'])) update_user_meta($user_id, 'social_bio_status', $_POST['social_bio_status']);
		if (isset($_POST['website_icon'])) update_user_meta($user_id, 'website_icon', $_POST['website_icon']);
		if ( isset( $_POST['googleauthorid'] ) ) update_user_meta( $user_id, 'googleauthorid', $_POST['googleauthorid'] );
		if ( isset( $_POST['displaygoogleauth'] ) ) update_user_meta( $user_id, 'displaygoogleauth', $_POST['displaygoogleauth'] );


		for ($loop = 0; $loop < $this->size; ++$loop) {

			if (isset($_POST[$this->socials[$loop][1]])) update_user_meta($user_id, $this->socials[$loop][1], $_POST[$this->socials[$loop][1]]);
			if (isset($_POST[$this->socials[$loop][2]])) update_user_meta($user_id, $this->socials[$loop][2], $_POST[$this->socials[$loop][2]]);
		}

		for ($loop = 0; $loop < $this->custom_size; ++$loop) {

			if (isset($_POST[$this->custom[$loop][1]])) update_user_meta($user_id, $this->custom[$loop][1], $_POST[$this->custom[$loop][1]]);
			if (isset($_POST[$this->custom[$loop][2]])) update_user_meta($user_id, $this->custom[$loop][2], $_POST[$this->custom[$loop][2]]);
		}
	}

	function set_custom() {

		// Get Options
		$this->custom  = get_option("bio_customlinks");
		
	}

	function set_socials() {

		array_push( $this->socials, array(
					'Facebook',
					'facebook',
					'display_facebook',
					'images/Facebook.png',
					'Please complete your Facebook URL (Wall, Group, Fan Page, App).',
					'http://www.facebook.com/',
					'' ));

		array_push( $this->socials, array(
					'Twitter',
					'twitter',
					'display_twitter',
					'images/Twitter.png',
					'Please enter your Twitter username.','http://twitter.com/#!/',
					'' ));

		array_push( $this->socials, array(
					'Google+',
					'google_plus',
					'display_google_plus',
					'images/Google_Plus.png',
					'Penter enter your Google ID.',
					'https://plus.google.com/',
					'' ));

		array_push( $this->socials, array(
					'Digg',
					'digg',
					'display_digg',
					'images/Digg.png',
					'Penter enter your profile name.',
					'http://digg.com/',
					'' ));

		array_push( $this->socials, array(
					'MySpace',
					'myspace',
					'display_myspace',
					'images/Myspace.png',
					'Please complete your MySpace URL.',
					'http://www.myspace.com/',
					'' ));

		array_push( $this->socials, array(
					'Yahoo',
					'yahoo',
					'display_yahoo',
					'images/Yahoo.png',
					'Please enter your Yahoo ID.',
					'http://profiles.yahoo.com/',
					'' ));

		array_push( $this->socials, array(
					'LinkedIn',
					'linkedin',
					'display_linkedin',
					'images/Linkedin.png',
					'Please complete your LinkedIn URL.',
					'http://www.linkedin.com/',
					'' ));

		array_push( $this->socials, array(
					'Technorati',
					'technorati',
					'display_technorati',
					'images/Technorati.png',
					'Please enter your Technorati username.',
					'http://technorati.com/people/technorati/',
					'' ));

		array_push( $this->socials, array(
					'Yahoo Msgr',
					'yahoomsgr',
					'display_yahoomsgr',
					'images/Yahoo_IM.png',
					'Please enter your Yahoo Messenger ID.',
					'ymsgr:sendIM?',
					'' ));

		array_push( $this->socials, array(
					'AIM',
					'aim',
					'display_aim',
					'images/Aim.png',
					'Please enter your AIM username.',
					'aim:goim?screename=',
					'' ));

		array_push( $this->socials, array(
					'MSN',
					'msn',
					'display_msn',
					'images/Msn.png',
					'Please enter your AIM screenname.',
					'msnim:chat?contact=',
					'' ));

		array_push( $this->socials, array(
					'Skype',
					'skype',
					'display_skype',
					'images/Skype.png',
					'Please enter your Skype screenname.',
					'skype:',
					'' ));

		array_push( $this->socials, array(
					'ICQ',
					'icq',
					'display_icq',
					'images/ICQ.png',
					'Please enter your ICQ UIN.',
					'icq:message?uin=',
					'' ));

		array_push( $this->socials, array(
					'eMail',
					'bio_email',
					'display_bio_email',
					'images/eMail.png',
					'Please enter your eMail address.',
					'mailto:',
					'' ));

		array_push( $this->socials, array(
					'Reverbnation',
					'bio_reverbnation',
					'display_reverbnation',
					'images/Reverbnation.png',
					'Please complete your Reverbnation URL.',
					'http://www.reverbnation.com/',
					'' ));

		array_push( $this->socials, array(
					'Soundcloud',
					'bio_soundcloud',
					'display_bio_soundcloud',
					'images/Soundcloud.png',
					'Please complete your Soundcloud URL.',
					'http://soundcloud.com/',
					'' ));

		array_push( $this->socials, array(
					'iCompositions',
					'bio_icompositions',
					'display_bio_icompositions',
					'images/iCompositions.png',
					'Please enter your iCompositions artist name',
					'http://www.icompositions.com/artists/',
					'' ));

		array_push( $this->socials, array(
					'YouTube',
					'bio_youtube',
					'display_bio_youtube',
					'images/YouTube.png',
					'Please enter your YouTube user name.',
					'http://www.youtube.com/user/',
					'' ));

		array_push( $this->socials, array(
					'Pinterest',
					'bio_pinterest',
					'display_bio_pinterest',
					'images/Pinterest.png',
					'',
					'http://pinterest.com/',
					'/' ));
  
	}

	function social_icons($ID) {
		// Init Variables
		$author_socials = '';

		// Get options
		$options['googleauthor'] = get_option( 'bio_googleauthor', 'checked' );

		if ( !is_author() && 'checked' == $options['googleauthor'] && get_the_author_meta( 'googleauthorid', $ID ) ) {
			$author_socials .= '<a rel="author" href="'.get_author_posts_url( $ID ).'"><img class="bio-img" src="'.plugins_url("images/admin/author_info.png", __FILE__).'" alt="Author Info"></a>';
		}

		$website_url = get_the_author_meta("user_url", $ID);

		if( !empty( $website_url ) ) {
			switch(get_the_author_meta("website_icon", $ID)) {

				case 1:
					$author_socials .= '<a href="'.get_the_author_meta("user_url", $ID).'" target="_blank"><img src="'.plugins_url("images/Blogger.png", __FILE__).'"></a>';
					break;
				case 2:
					$author_socials .= '<a href="'.get_the_author_meta("user_url", $ID).'" target="_blank"><img src="'.plugins_url("images/Website.png", __FILE__).'"></a>';
					break;
				default:
					$author_socials .= '<a href="'.get_the_author_meta("user_url", $ID).'" target="_blank"><img src="'.plugins_url("images/Wordpress.png", __FILE__).'"></a>';
			}
		}

		for ($loop = 0; $loop < $this->size; ++$loop) {

			$temp = 'bio_social_'.$this->socials[$loop][1];
			$options[$temp] = get_option($temp);

			if (get_the_author_meta($this->socials[$loop][2], $ID) && $options[$temp]) {

				$author_socials .= '<a href="'.$this->socials[$loop][5].get_the_author_meta($this->socials[$loop][1], $ID).$this->socials[$loop][6].'" target="_blank">'.
				'<img class="bio-img" src="'.plugins_url($this->socials[$loop][3], __FILE__).'"></a>';
			}
		}

		for ($loop = 0; $loop < $this->custom_size; ++$loop) {

			$temp = 'bio_social_'.$this->custom[$loop][1];
			$options[$temp] = get_option($temp);

			if (get_the_author_meta($this->custom[$loop][2], $ID) && $options[$temp]) {

				$author_socials .= '<a href="'.$this->custom[$loop][5].get_the_author_meta($this->custom[$loop][1], $ID).$this->custom[$loop][6].'" target="_blank">'.
				'<img class="bio-img" src="'.$this->custom[$loop][3].'"></a>';
			}
		}

		return $author_socials;
	}

	function display($ID) {


		$disable = false;

		$id = get_the_id();
		$mymeta = $this->custom_checkbox->the_meta($id);

		if ( isset( $mymeta['DisableSocialAuthorBio'] ) && 'Disable' == $mymeta['DisableSocialAuthorBio'] ) {
			$disable = true;
		}

		if( !$disable ) {

			// Set Defaults
			$default_format		= '<div id="author-bio-box">%avatar%<span class="author-name">%prefix% %name% (<a href="%author_link%">%post_count% Posts</a>)</span>'
						. '<p>%author_desc%</p><div class="bio-socials">%socials%</div></div><br/>';

			// Get Options
			$options['page']	= get_option('bio_on_page');
			$options['post']	= get_option('bio_on_post');
			$options['shortcode']	= get_option('bio_on_shortcode');
			$options['prefix']	= get_option('bio_prefix');
			$options['format']	= get_option('bio_format', $default_format);
			$options['bio_avatarsize']	= get_option('bio_avatarsize', "80");

			$avatar 		= get_avatar( $ID, $options['bio_avatarsize'] );
			$author_socials		= '';

			// Search
			$token = array (	'%avatar%',
						'%prefix%',
						'%name%',
						'%author_link%',
						'%post_count%',
						'%author_desc%',
						'%socials%',
						'%home%');

			// Replace
			$replace = array (	$avatar,
						$options['prefix'],
						get_the_author_meta("display_name", $ID),
						get_author_posts_url($ID),
						count_user_posts($ID),
						get_the_author_meta("description", $ID),
						$this->social_icons($ID),
						get_home_url() );

			// Parse Search and Replace
			$bio_box = htmlspecialchars_decode(stripslashes(str_replace($token, $replace, $options["format"])));
			return $bio_box;
		}
		else {
			return '';
		}
	}

	function google_author() {

		// Get Options
		$options['googleauthor'] = get_option( 'bio_googleauthor', 'checked' );
		$options['sabpluginlink'] = get_option( 'sabpluginlink', 'checked' );

		if ( is_author() && !$this->googlecount ) {
			if ( 'checked' == $options['googleauthor'] && get_the_author_meta( 'displaygoogleauth', get_the_author_meta( 'ID' ) ) ) { 
				$googleID = get_the_author_meta( 'googleauthorid', get_the_author_meta( 'ID' ) );
				$googleURL = 'https://plus.google.com/'.$googleID;
				$icons = apply_filters('social_author_icons', get_the_author_meta( 'ID' ) );

				echo '<a rel="me" href="'.$googleURL.'" target="_blank" style="outline:none;">';
				echo '<img src="'.$this->google_avatar( $googleID ).'" alt="Google Circle" /><br>Join my Circle on Google+</a>';
			}

			if ( 'checked' == $options['sabpluginlink']) {echo '<p style="font-size:10px;"></p>';
			}

			echo $icons; 

			$this->googlecount++;
		}
	}


	function google_avatar( $id ) {
		$headers = get_headers("https://profiles.google.com/s2/photos/profile/".$id."?sz=48", 1);
		$PicUrl = $headers['Location'];

		return $PicUrl;
	}

	function comment_author_byline() {
		if(is_singular()){
			echo '< span class="author-byline" >Published by: . $get_comment_author . < /span >';
		}
	}

}


?>
