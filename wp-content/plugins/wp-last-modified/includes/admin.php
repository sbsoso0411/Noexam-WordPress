<?php

/**
 *	WP Last Modified Admin (includes/admin.php)
 *
 *	Admin Settings file
 *
 *	@package WordPress
 *	@subpackage WP Last Modifed
 */

/**
 * CAUTION: DO NOT MODIFY THIS PAGE
 */

function wp_last_modified_options_page() {
	add_options_page('WP Last Modified: Options', 'WP Last Modified', 'manage_options', __FILE__, 'wp_last_modified_settings' );
}

function wp_last_modified_settings() {
	?>
	<div class="wrap">
	<?php screen_icon(); ?>
		<h2>WP Last Modified: Plugin Options</h2>
		<form action="options.php" method="post">
		<?php
			settings_fields('wp_last_modified_options');
			do_settings_sections('wp_last_modified');
			wp_last_modified_support();
		?>
			<div class="wp-last-modified-clear"></div>
			<input type="submit" name="Submit" id="submit" class="button button-primary" value="Save Changes" />
		</form>
	</div>
	<?php
}

add_action('admin_init', 'wp_last_modified_admin_init');
function wp_last_modified_admin_init() {
	register_setting('wp_last_modified_options', 'wp_last_modified_options', 'wp_last_modified_validate_options');
	add_settings_section('wp_last_modified_luts', 'Last Modified Timestamp', 'wp_last_modified_luts_text', 'wp_last_modified');	
	add_settings_field('wp_last_modified_luts_field1', 'Show Last Updated Timestamp', 'wp_last_modified_setting_luts1', 'wp_last_modified', 'wp_last_modified_luts');
	add_settings_field('wp_last_modified_luts_field2', 'Select position', 'wp_last_modified_setting_luts2', 'wp_last_modified', 'wp_last_modified_luts');
	add_settings_field('wp_last_modified_luts_field3', 'Select format', 'wp_last_modified_setting_luts3', 'wp_last_modified', 'wp_last_modified_luts');
	add_settings_field('wp_last_modified_luts_field4', 'Verb to display eg. "Last updated:"', 'wp_last_modified_setting_luts4', 'wp_last_modified', 'wp_last_modified_luts');
	add_settings_section('wp_last_modified_rdmi', 'Revise Date Meta Information', 'wp_last_modified_rdmi_text', 'wp_last_modified');
	add_settings_field('wp_last_modified_rdmi_field1', 'Posts', 'wp_last_modified_setting_rdmi1', 'wp_last_modified', 'wp_last_modified_rdmi');
	add_settings_field('wp_last_modified_rdmi_field2', 'Pages', 'wp_last_modified_setting_rdmi2', 'wp_last_modified', 'wp_last_modified_rdmi');
}

function wp_last_modified_rdmi_text() {
	echo "Inserts the revised date meta information in the <code>&lt;head&gt;</code> section of your posts and pages.<br/>The inserted code will look like: <code>&lt;meta name=&quot;revised&quot; content=&quot;Sunday, May 12, 2013, 8:56 pm&quot; /&gt;</code><br/><br/><b>Activate Revised date meta for:</b>";
}

function wp_last_modified_luts_text() {
	echo "Inserts a last updated timestamp above or below your posts and pages.<br/>Eg. Last Updated: Sunday, May 12, 2013";
}

function wp_last_modified_setting_rdmi1() {
	$options = get_option('wp_last_modified_options');
	$check_field1 = $options['check_field1'];
	$html = '<input id="check_field1" name="wp_last_modified_options[check_field1]" type="checkbox" value="1"' . checked( 1, $options['check_field1'], false ) . '/>';
	$html.= ' <label for="check_field1">Check to activate</label>';
	echo $html;
}

function wp_last_modified_setting_rdmi2() {
	$options = get_option('wp_last_modified_options');
	$check_field2 = $options['check_field2'];
	$html = '<input id="check_field2" name="wp_last_modified_options[check_field2]" type="checkbox" value="1"' . checked( 1, $options['check_field2'], false ) . '/>';
	$html.= ' <label for="check_field2">Check to activate</label>';
	echo $html;
}

function wp_last_modified_setting_luts1() {
	$options = get_option('wp_last_modified_options');
	$check_field3 = $options['check_field3'];
	$html = '<input id="check_field3" name="wp_last_modified_options[check_field3]" type="checkbox" value="1"' . checked( 1, $options['check_field3'], false ) . '/>';
	$html.= ' <label for="check_field3">Check to activate</label>';
	echo $html;
}

function wp_last_modified_setting_luts2() {
	$options = get_option('wp_last_modified_options');
	$select_field1 = $options['select_field1'];
	?>
    <select id="select_field1" name="wp_last_modified_options[select_field1]">
        <option value="1" <?php selected($options['select_field1'], 1); ?>>Above the Post</option>
        <option value="2" <?php selected($options['select_field1'], 2); ?>>Below the Post</option>
    </select>
    <?php
}

function wp_last_modified_setting_luts3() {
	$options = get_option('wp_last_modified_options');
	$select_field2 = $options['select_field2'];
	?>
    <select id="select_field2" name="wp_last_modified_options[select_field2]">
        <option value="1" <?php selected($options['select_field2'], 1); ?>>Sunday, May 12, 2013</option>
        <option value="2" <?php selected($options['select_field2'], 2); ?>>12th May 2013, 8:56 PM</option>
        <option value="3" <?php selected($options['select_field2'], 3); ?>>12th May 2013</option>
        <option value="4" <?php selected($options['select_field2'], 4); ?>>05/12/2013</option>
        <option value="5" <?php selected($options['select_field2'], 5); ?>>12/05/2013</option>
    </select>
    <?php
}

function wp_last_modified_setting_luts4() {
	$options = get_option('wp_last_modified_options');
	$text_field1 = $options['text_field1'];
	?>
    <input type="text" id="text_field1" name="wp_last_modified_options[text_field1]" value="<?php echo $options['text_field1']; ?>" />
    <?php
}

function wp_last_modified_validate_options($input) {
	$valid = array();
	$valid['check_field1'] = $input['check_field1'];
	$valid['check_field2'] = $input['check_field2'];
	$valid['check_field3'] = $input['check_field3'];
	$valid['select_field1'] = $input['select_field1'];
	$valid['select_field2'] = $input['select_field2'];
	$valid['text_field1'] = $input['text_field1'];
	return $valid;
}

function wp_last_modified_support() {
?>
<style type="text/css">
.wp-last-modified-share-buttons{
	padding:10px;
	background:#f9f9f9;
	border:1px solid #eee;
	display:inline-block;
	margin-bottom:15px;
	height:25px;
}
.wp-last-modified-share-buttons li{
	float:left;
	min-width:65px;
	width:95px;
	margin-right:5px;
}
.wp-last-modified-plugin-support{
	display:block;
	padding:3px 6px;
	background:#75ae11;
	color:#fff;
	text-decoration: none;
	border-radius:2px;
	-moz-border-radius:2px;
	-webkit-border-radius:2px;
}
.wp-last-modified-plugin-support:hover{
	background:#D54E21;
	color:#fff;
}
.wp-last-modified-clear{clear:both;}
.wp-last-modified-clearfix:after {
	visibility:hidden;
	display:block;
	font-size:0;
	content:" ";
	clear:both;
	height:0;
}
* html .wp-last-modified-clearfix { zoom:1; }

:first-child+html .wp-last-modified-clearfix { zoom:1; }
</style>
<ul class="wp-last-modified-share-buttons wp-last-modified-clearfix">
	<li><iframe src="//www.facebook.com/plugins/like.php?href=http://techably.com/wp-last-modified/10534/&amp;send=false&amp;layout=button_count&amp;width=120&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font=arial&amp;height=21&amp;" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:120px; height:21px;" allowTransparency="true"></iframe></li>
	<li><a href="https://twitter.com/share" class="twitter-share-button" data-url="http://techably.com/wp-last-modified/10534/" data-text="WordPress Revised Meta Tag Plugin" data-count="" data-via="w3bits_" data-related="w3bits_" data-hashtags="" data-dnt="true">Tweet</a></li>
	<li><div class="g-plusone" data-size="medium" data-href="http://techably.com/wp-last-modified/10534/"></div></li>
	<li><a class="wp-last-modified-plugin-support" href="http://techably.com/wp-last-modified/10534/" target="_blank">Plugin Support</a></li>
</ul>
<?php
}

function wp_last_modified_admin_scripts() {
        wp_register_script( 'plus1', 'https://apis.google.com/js/plusone.js', false, array() );
        wp_register_script( 'tweet', 'https://platform.twitter.com/widgets.js', false, array() );
        wp_enqueue_script( 'plus1' );
        wp_enqueue_script( 'tweet' );
}
add_action( 'admin_enqueue_scripts', 'wp_last_modified_admin_scripts' );

?>