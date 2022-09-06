<?php
/*
Plugin Name: Collapse-O-Matic
Text Domain: jquery-collapse-o-matic
Plugin URI: http://plugins.twinpictures.de/plugins/collapse-o-matic/
Description: Collapse-O-Matic adds an [expand] shortcode that wraps content into a lovely, jQuery collapsible div.
Version: 1.7.2
Author: twinpictures, baden03
Author URI: http://twinpictures.de/
License: GPL2
*/

/**
 * Class WP_Collapse_O_Matic
 * @package WP_Collapse_O_Matic
 * @category WordPress Plugins
 */

if(!defined('PLUGIN_OVEN_URL')){
	define( 'PLUGIN_OVEN_URL', 'http://plugins.twinpictures.de' );
}
if(!defined('PLUGIN_OVEN_CC')){
	define( 'PLUGIN_OVEN_CC', 'Collapse Commander' );
}

class WP_Collapse_O_Matic {

	/**
	 * Current version
	 * @var string
	 */
	var $version = '1.7.2';

	/**
	 * Used as prefix for options entry
	 * @var string
	 */
	var $domain = 'colomat';

	/**
	 * Name of the options
	 * @var string
	 */
	var $options_name = 'WP_Collapse_O_Matic_options';

	/**
	 * @var array
	 */
	var $options = array(
		'style' => 'light',
		'cid' => '',
		'tag' => 'span',
		'trigclass' => '',
		'targtag' => 'div',
		'targclass' => '',
		'duration' => 'fast',
		'tabindex' => '0',
		'slideEffect' => 'slideFade',
		'custom_css' => '',
		'script_check' => '',
		'css_check' => '',
		'script_location' => 'footer',
		'cc_download_key' => '',
		'cc_email' => '',
		'filter_content' => '',
	);

	var $license_group = 'colomat_licenseing';

        var $license_name = 'WP_Collapse_O_Matic_license';

        var $license_options = array(
                'collapse_commander_license_key' => '',
                'collapse_commander_license_status' => ''
        );

	/**
	 * PHP5 constructor
	 */
	function __construct() {
		// set option values
		$this->_set_options();

		// load text domain for translations
		load_plugin_textdomain( 'jquery-collapse-o-matic' );

		//load the script and style if viewing the front-end
		add_action('wp_enqueue_scripts', array( $this, 'collapsTronicInit' ) );

		// add actions
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'plugin_actions' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		if($this->options['script_location'] == 'footer' ){
			add_action( 'wp_footer', array( $this, 'colomat_js_vars' ) );
		}
		else{
			add_action('wp_head', array( $this, 'colomat_js_vars' ) );
		}
		add_shortcode('expand', array($this, 'shortcode'));

		//add expandsub shortcodes
		for ($i=1; $i<30; $i++) {
			add_shortcode('expandsub'.$i, array($this, 'shortcode'));
		}

		// Add shortcode support for widgets
		add_filter('widget_text', 'do_shortcode');
	}

	//global javascript vars
	function colomat_js_vars(){
		echo "<script type='text/javascript'>\n";
		echo "var colomatduration = '".$this->options['duration']."';\n";
		echo "var colomatslideEffect = '".$this->options['slideEffect']."';\n";
		echo "</script>";
		if( !empty( $this->options['custom_css'] ) ){
			echo "\n<style>\n";
			echo $this->options['custom_css'];
			echo "\n</style>\n";
		}
	}

	/**
	 * Callback init
	 */
	function collapsTronicInit() {
		//collapse script
		$load_in_footer = false;
		if($this->options['script_location'] == 'footer' ){
			$load_in_footer = true;
		}
		wp_register_script('collapseomatic-js', plugins_url('js/collapse.js', __FILE__), array('jquery'), '1.6.4', $load_in_footer);
		if( empty($this->options['script_check']) ){
			wp_enqueue_script('collapseomatic-js');
		}

		//css
		if ($this->options['style'] !== 'none') {
			wp_register_style( 'collapseomatic-css', plugins_url('/'.$this->options['style'].'_style.css', __FILE__) , array (), '1.6' );
			if( empty($this->options['css_check']) ){
				wp_enqueue_style( 'collapseomatic-css' );
			}
		}
	}

	/**
	 * Callback admin_menu
	 */
	function admin_menu() {
		if ( function_exists( 'add_options_page' ) AND current_user_can( 'manage_options' ) ) {
			// add options page
			$page = add_options_page('Collapse-O-Matic Options', 'Collapse-O-Matic', 'manage_options', 'collapse-o-matic-options', array( $this, 'options_page' ));
		}
	}

	/**
	 * Callback admin_init
	 */
	function admin_init() {
		// register settings
		register_setting( $this->domain, $this->options_name );
		register_setting( $this->license_group, $this->license_name, array($this, 'edd_sanitize_license') );
	}

	/**
	 * Callback shortcode
	 */
	function shortcode($atts, $content = null){
		$options = $this->options;
		if( !empty($this->options['script_check']) ){
			wp_enqueue_script('collapseomatic-js');
		}
		if( !empty($this->options['css_check']) ){
			wp_enqueue_style( 'collapseomatic-css' );
		}
		//find a random number, if no id is assigned
		$ran = rand(1, 10000);
		extract(shortcode_atts(array(
			'title' => '',
			'cid' => $options['cid'],
			'swaptitle' => '',
			'alt' => '',
			'swapalt' => '',
			'notitle' => '',
			'id' => 'id'.$ran,
			'tag' => $options['tag'],
			'trigclass' => $options['trigclass'],
			'targtag' => $options['targtag'],
			'targclass' => $options['targclass'],
			'targpos' => '',
			'trigpos' => 'above',
			'rel' => '',
			'group' => '',
			'togglegroup' => '',
			'expanded' => '',
			'excerpt' => '',
			'swapexcerpt' => false,
			'excerptpos' => 'below-trigger',
			'excerpttag' => 'div',
			'excerptclass' => '',
			'findme' => '',
			'scrollonclose' => '',
			'startwrap' => '',
			'endwrap' => '',
			'elwraptag' => '',
			'elwrapclass' => '',
			'filter' => $options['filter_content'],
			'tabindex' => $options['tabindex']
		), $atts, 'expand'));
		if(!empty($cid)){
			$args = array(
				'post_type'	=> 'expand-element',
				'p'		=> $cid,
			);
			$query_commander = new WP_Query( $args );
			if ( $query_commander->have_posts() ) {
				while ( $query_commander->have_posts() ) {
					$query_commander->the_post();
					$title = get_the_title();

					//meta values
					$meta_values = get_post_meta( $cid );
					foreach($meta_values as $key => $value){
						if(!empty($value) && $key[0] != '_'){
							${substr($key, 9)} = $value[0];
						}
					}
					if(!empty($triggertext)){
						$title = $triggertext;
					}
					if(!empty($highlander) && !empty($rel)){
						$rel .= '-highlander';
					}

					//content
					$content = get_the_content();
				}
			}
			wp_reset_postdata();
		}

		//content filtering
		if(empty($filter) || $filter == 'false'){
			$content = do_shortcode($content);
		}
		else{
			$content = apply_filters( 'the_content', $content );
			$content = str_replace( ']]>', ']]&gt;', $content );
		}

		$ewo = '';
		$ewc = '';

		//id does not allow spaces
		$id = preg_replace('/\s+/', '_', $id);

		//placeholders
		$placeholder_arr = array('%(%', '%)%', '%{%', '%}%');
		$swapout_arr = array('<', '>', '[', ']');

		$title = do_shortcode(str_replace($placeholder_arr, $swapout_arr, $title));
		if($swaptitle){
			$swaptitle = do_shortcode(str_replace($placeholder_arr, $swapout_arr, $swaptitle));
		}
		if($startwrap){
			$startwrap = do_shortcode(str_replace($placeholder_arr, $swapout_arr, $startwrap));
		}
		if($endwrap){
			$endwrap = do_shortcode(str_replace($placeholder_arr, $swapout_arr, $endwrap));
		}
		//need to check for a few versions, because of new option setting. can be removed after a few revisiosn.
		if(empty($targtag)){
			$targtag = 'div';
		}

		if($elwraptag){
			$ewclass = '';
			if($elwrapclass){
				$ewclass = 'class="'.$elwrapclass.'"';
			}
			$ewo = '<'.$elwraptag.' '.$ewclass.'>';
			$ewc = '</'.$elwraptag.'>';
		}

		$eDiv = '';
		if($content){
			$inline_class = '';
			$collapse_class = 'collapseomatic_content ';
			if($targpos == 'inline'){
				$inline_class = 'colomat-inline ';
				$collapse_class = 'collapseomatic_content_inline ';
			}
			$eDiv = '<'.$targtag.' id="target-'.$id.'" class="'.$collapse_class.$inline_class.$targclass.'">'.$content.'</'.$targtag.'>';
		}

		if($excerpt){
			$excerpt = str_replace($placeholder_arr, $swapout_arr, $excerpt);
			if(empty($filter) || $filter == 'false'){
				$excerpt = do_shortcode($excerpt);
			}
			else{
				$excerpt = apply_filters( 'the_content', $excerpt );
				$excerpt = str_replace( ']]>', ']]&gt;', $excerpt );
			}

			if($targpos == 'inline'){
				$excerpt .= $eDiv;
				$eDiv = '';
			}
			if($excerptpos == 'above-trigger'){
				$nibble = '<'.$excerpttag.' id="excerpt-'.$id.'" class="'.$excerptclass.'">'.$excerpt.'</'.$excerpttag.'>';
			}
			else{
				$nibble = '<'.$excerpttag.' id="excerpt-'.$id.'" class="collapseomatic_excerpt '.$excerptclass.'">'.$excerpt.'</'.$excerpttag.'>';
			}
			//swapexcerpt
			if($swapexcerpt !== false){
				$swapexcerpt = str_replace($placeholder_arr, $swapout_arr, $swapexcerpt);
				if(empty($filter) || $filter == 'false'){
					$swapexcerpt = do_shortcode($swapexcerpt);
				}
				else{
					$swapexcerpt = apply_filters( 'the_content', $swapexcerpt );
					$swapexcerpt = str_replace( ']]>', ']]&gt;', $swapexcerpt );
				}
				$nibble .= '<'.$excerpttag.' id="swapexcerpt-'.$id.'" style="display:none;">'.$swapexcerpt.'</'.$excerpttag.'>';
			}
		}
		$altatt = '';
		if($alt){
			$altatt = 'alt="'.$alt.'" title="'.$alt.'"';
		}
		else if( !$notitle ){
			$altatt = 'title="'.$title.'"';
		}
		$relatt = '';
		if($rel){
			$relatt = 'rel="'.$rel.'"';
		}

		$groupatt = '';
		//legacy
		if($group && !$togglegroup){
			$togglegroup = $group;
		}

		if($togglegroup){
			$groupatt = 'data-togglegroup="'.$togglegroup.'"';
		}
		$inexatt = '';
		if(!empty($tabindex) || $tabindex == 0 ){
			$inexatt = 'tabindex="'.$tabindex.'"';
		}
		if($expanded){
			$trigclass .= ' colomat-close';
		}
		$anchor = '';
		if($findme){
			$trigclass .= ' find-me';
			$offset = '';
			if($findme != 'true' && $findme != 'auto'){
				$offset = $findme;
			}
			//$anchor = '<input type="hidden" id="find-'.$id.'" name="'.$offset.'"/>';
			$anchor = 'data-findme="'.$offset.'"';
		}
		$closeanchor = '';
		if($scrollonclose && (is_numeric($scrollonclose) || $scrollonclose == 0)){
			$trigclass .= ' scroll-to-trigger';
			$closeanchor = '<input type="hidden" id="scrollonclose-'.$id.'" name="'.$scrollonclose.'"/>';
		}

		//deal with image from collapse-commander
		if( !empty($trigtype) && $trigtype == 'image' && !empty($triggerimage) && strtolower($tag) == 'img' ){
			$imageclass = 'collapseomatic noarrow' . $trigclass;
			$image_atts = array( 'id' => $id, 'class' => $imageclass, 'alt' => $alt );
			if(!$notitle){
				$image_atts['title'] = $alt;
			}
			$link = $closeanchor.wp_get_attachment_image( $triggerimage, 'full', false, $image_atts );
		}
		else{
			if(!empty($trigtype) && $trigtype == 'image' && !empty($triggerimage)){
				$title =  wp_get_attachment_image( $triggerimage, 'full' );
			}
			$link = $closeanchor.'<'.$tag.' class="collapseomatic '.$trigclass.'" id="'.$id.'" '.$relatt.' '.$inexatt.' '.$altatt.' '.$anchor.' '.$groupatt.'>'.$startwrap.$title.$endwrap.'</'.$tag.'>';
		}

		//swap image
		if( !empty($trigtype) && $trigtype == 'image' && !empty($swapimage) && strtolower($tag) == 'img' ){
			$link .= wp_get_attachment_image( $swapimage, 'full', false, array( 'id' => 'swap-'.$id, 'class' => 'colomat-swap', 'alt' => $swapalt, 'style' => 'display:none;' ) );
		}
		else{
			if(!empty($trigtype) && $trigtype == 'image' && !empty($swapimage)){
				$swaptitle = wp_get_attachment_image( $swapimage, 'full' );
			}
		}
		//swap title
		if($swaptitle){
			$link .= "<".$tag." id='swap-".$id."' alt='".$swapalt."' class='colomat-swap' style='display:none;'>".$startwrap.$swaptitle.$endwrap."</".$tag.">";
		}

		if($excerpt){
			if($excerptpos == 'above-trigger'){
				if($trigpos == 'below'){
					$retStr = $ewo.$eDiv.$nibble.$link.$ewc;
				}
				else{
					$retStr = $ewo.$nibble.$link.$eDiv.$ewc;
				}
			}
			else if($excerptpos == 'below-trigger'){
				if($trigpos == 'below'){
					$retStr =  $ewo.$eDiv.$link.$nibble.$ewc;
				}
				else{
					$retStr = $ewo.$link.$nibble.$eDiv.$ewc;
				}
			}
			else{
				if($trigpos == 'below'){
					$retStr = $ewo.$eDiv.$link.$nibble.$ewc;
				}
				else{
					$retStr = $ewo.$link.$eDiv.$nibble.$ewc;
				}
			}
		}
		else{
			if($trigpos == 'below'){
				$retStr = $ewo.$eDiv.$link.$ewc;
			}
			else{
				$retStr = $ewo.$link.$eDiv.$ewc;
			}
		}
		return $retStr;
	}

	// Add link to options page from plugin list
	function plugin_actions($links) {
		$new_links = array();
		$new_links[] = '<a href="options-general.php?page=collapse-o-matic-options">' . __('Settings', 'jquery-collapse-o-matic') . '</a>';
		return array_merge($new_links, $links);
	}

	/**
	 * Admin options page
	 */
	function options_page() {
		$like_it_arr = array(
			__('really tied the room together', 'jquery-collapse-o-matic'),
			__('made you feel all warm and fuzzy on the inside', 'jquery-collapse-o-matic'),
			__('restored your faith in humanity... even if only for a fleeting second', 'jquery-collapse-o-matic'),
			__('rocked your world', 'provided a positive vision of future living', 'jquery-collapse-o-matic'),
			__('inspired you to commit a random act of kindness', 'jquery-collapse-o-matic'),
			__('encouraged more regular flossing of the teeth', 'jquery-collapse-o-matic'),
			__('helped organize your life in the small ways that matter', 'jquery-collapse-o-matic'),
			__('saved your minutes--if not tens of minutes--writing your own solution', 'jquery-collapse-o-matic'),
			__('brightened your day... or darkened if if you are trying to sleep in', 'jquery-collapse-o-matic'),
			__('caused you to dance a little jig of joy and joyousness', 'jquery-collapse-o-matic'),
			__('inspired you to tweet a little @twinpictues social love', 'jquery-collapse-o-matic'),
			__('tasted great, while also being less filling', 'jquery-collapse-o-matic'),
			__('caused you to shout: "everybody spread love, give me some mo!"', 'jquery-collapse-o-matic'),
			__('helped you keep the funk alive', 'jquery-collapse-o-matic'),
			__('<a href="http://www.youtube.com/watch?v=dvQ28F5fOdU" target="_blank">soften hands while you do dishes</a>', 'jquery-collapse-o-matic'),
			__('helped that little old lady <a href="http://www.youtube.com/watch?v=Ug75diEyiA0" target="_blank">find the beef</a>', 'jquery-collapse-o-matic')
		);
		$rand_key = array_rand($like_it_arr);
		$like_it = $like_it_arr[$rand_key];
	?>
		<div class="wrap">
			<div class="icon32" id="icon-options-custom" style="background:url( <?php echo plugins_url( 'images/collapse-o-matic-icon.png', __FILE__ ) ?> ) no-repeat 50% 50%"><br></div>
			<h2>Collapse-O-Matic</h2>
		</div>

		<div class="postbox-container metabox-holder meta-box-sortables" style="width: 69%">
			<div style="margin:0 5px;">
				<div class="postbox">
					<div class="handlediv" title="<?php _e( 'Click to toggle', 'jquery-collapse-o-matic' ) ?>"><br/></div>
					<h3 class="hndle"><?php _e( 'Default Collapse-O-Matic Settings', 'jquery-collapse-o-matic' ) ?></h3>
					<div class="inside">
						<form method="post" action="options.php">
							<?php
								settings_fields( $this->domain );
								$options = $this->options;
							?>
							<fieldset class="options">
								<table class="form-table">
								<tr>
									<th><?php _e( 'Style', 'jquery-collapse-o-matic' ) ?>:</th>
									<td><label><select id="<?php echo $this->options_name ?>[style]" name="<?php echo $this->options_name ?>[style]">
										<?php
											if(empty($options['style'])){
												$options['style'] = 'light';
											}
											$st_array = array(
												__('Light', 'jquery-collapse-o-matic') => 'light',
												__('Dark', 'jquery-collapse-o-matic') => 'dark',
												__('None', 'jquery-collapse-o-matic') => 'none'
											);
											foreach( $st_array as $key => $value){
												$selected = '';
												if($options['style'] == $value){
													$selected = 'SELECTED';
												}
												echo '<option value="'.$value.'" '.$selected.'>'.$key.'</option>';
											}
										?>
										</select>
										<br /><span class="description"><?php _e('Select Light for sites with lighter backgrounds. Select Dark for sites with darker backgrounds. Select None to handle styling yourself.', 'jquery-collapse-o-matic'); ?></span></label>
									</td>
								</tr>

								<?php if( is_plugin_active( 'collapse-commander/collapse-commander.php' ) ) : ?>
								<tr>
									<th><?php _e( 'CID Attribute', 'jquery-collapse-o-matic' ) ?>:</th>
									<td><label><input type="text" id="<?php echo $this->options_name ?>[cid]" name="<?php echo $this->options_name ?>[cid]" value="<?php echo $options['cid']; ?>" />
										<br /><span class="description"><?php printf( __('Default %sCollapse Commander%s ID', 'jquery-collapse-o-matic'), '<a href="http://plugins.twinpictures.de/premium-plugins/collapse-commander/" target="_blank">', '</a>'); ?></span></label>
									</td>
								</tr>
								<?php endif; ?>

								<tr>
									<th><?php _e( 'Tag Attribute', 'jquery-collapse-o-matic' ) ?>:</th>
									<td><label><input type="text" id="<?php echo $this->options_name ?>[tag]" name="<?php echo $this->options_name ?>[tag]" value="<?php echo $options['tag']; ?>" />
										<br /><span class="description"><?php printf(__('HTML tag use to wrap the trigger text. See %sTag Attribute%s in the documentation for more info.', 'jquery-collapse-o-matic'), '<a href="http://plugins.twinpictures.de/plugins/collapse-o-matic/documentation/#tag" target="_blank">', '</a>'); ?></span></label>
									</td>
								</tr>

								<tr>
									<th><?php _e( 'Trigclass Attribute', 'jquery-collapse-o-matic' ) ?>:</th>
									<td><label><input type="text" id="<?php echo $this->options_name ?>[trigclass]" name="<?php echo $this->options_name ?>[trigclass]" value="<?php echo $options['trigclass']; ?>" />
										<br /><span class="description"><?php printf(__('Default class assigned to the trigger element. See %sTrigclass Attribute%s in the documentation for more info.', 'jquery-collapse-o-matic'), '<a href="http://plugins.twinpictures.de/plugins/collapse-o-matic/documentation/#trigclass" target="_blank">', '</a>'); ?></span></label>
									</td>
								</tr>

								<tr>
									<th><?php _e( 'Tabindex Attribute', 'jquery-collapse-o-matic' ) ?>:</th>
									<td><label><input type="text" id="<?php echo $this->options_name ?>[tabindex]" name="<?php echo $this->options_name ?>[tabindex]" value="<?php echo $options['tabindex']; ?>" />
										<br /><span class="description"><?php printf(__('Default tabindex value to be assigned to the trigger element. See %sTabindex Attribute%s in the documentation for more info.', 'jquery-collapse-o-matic'), '<a href="http://plugins.twinpictures.de/plugins/collapse-o-matic/documentation/#tabindex" target="_blank">', '</a>'); ?></span></label>
									</td>
								</tr>

								<tr>
									<th><?php _e( 'Targtag Attribute', 'jquery-collapse-o-matic' ) ?>:</th>
									<td><label><input type="text" id="<?php echo $this->options_name ?>[targtag]" name="<?php echo $this->options_name ?>[targtag]" value="<?php echo $options['targtag']; ?>" />
										<br /><span class="description"><?php printf(__('HTML tag use for the target element. See %sTargtag Attribute%s in the documentation for more info.', 'jquery-collapse-o-matic'), '<a href="http://plugins.twinpictures.de/plugins/collapse-o-matic/documentation/#targtag" target="_blank">', '</a>'); ?></span></label>
									</td>
								</tr>

								<tr>
									<th><?php _e( 'Targclass Attribute', 'jquery-collapse-o-matic' ) ?>:</th>
									<td><label><input type="text" id="<?php echo $this->options_name ?>[targclass]" name="<?php echo $this->options_name ?>[targclass]" value="<?php echo $options['targclass']; ?>" />
										<br /><span class="description"><?php printf(__('Default class assigned to the target element. See %sTargclass Attribute%s in the documentation for more info.', 'jquery-collapse-o-matic'), '<a href="http://plugins.twinpictures.de/plugins/collapse-o-matic/documentation/#targclass" target="_blank">', '</a>'); ?></span></label>
									</td>
								</tr>

								<tr>
									<?php
										if(empty($options['duration'])){
												$options['duration'] = 'fast';
										}
									?>
									<th><?php _e( 'Collapse/Expand Duration', 'jquery-collapse-o-matic' ) ?>:</th>
									<td><label><input type="text" id="<?php echo $this->options_name ?>[duration]" name="<?php echo $this->options_name ?>[duration]" value="<?php echo $options['duration']; ?>" />
										<br /><span class="description"><?php printf(__('A string or number determining how long the animation will run. See %sDuration%s in the documentation for more info.', 'jquery-collapse-o-matic'), '<a href="http://plugins.twinpictures.de/plugins/collapse-o-matic/documentation/#duration" target="_blank">', '</a>'); ?></span></label>
									</td>
								</tr>

								<tr>
									<th><?php _e( 'Animation Effect', 'jquery-collapse-o-matic' ) ?>:</th>
									<td><label><select id="<?php echo $this->options_name ?>[slideEffect]" name="<?php echo $this->options_name ?>[slideEffect]">
										<?php
											if(empty($options['slideEffect'])){
												$options['slideEffect'] = 'slideFade';
											}
											$se_array = array(
												__('Slide Only', 'jquery-collapse-o-matic') => 'slideToggle',
												__('Slide & Fade', 'jquery-collapse-o-matic') => 'slideFade'
											);
											foreach( $se_array as $key => $value){
												$selected = '';
												if($options['slideEffect'] == $value){
													$selected = 'SELECTED';
												}
												echo '<option value="'.$value.'" '.$selected.'>'.$key.'</option>';
											}
										?>
										</select>
										<br /><span class="description"><?php printf(__('Animation effect to use while collapsing and expanding. See %sAnimation Effect%s in the documentation for more info.', 'jquery-collapse-o-matic'), '<a href="http://plugins.twinpictures.de/plugins/collapse-o-matic/documentation/#animation-effect" target="_blank">', '</a>'); ?></span></label>
									</td>
								</tr>

								<tr>
									<th><?php _e( 'Custom Style', 'jquery-collapse-o-matic' ) ?>:</th>
									<td><label><textarea id="<?php echo $this->options_name ?>[custom_css]" name="<?php echo $this->options_name ?>[custom_css]" style="width: 100%; height: 150px;"><?php echo $options['custom_css']; ?></textarea>
										<br /><span class="description"><?php _e( 'Custom CSS style for <em>ultimate flexibility</em>', 'jquery-collapse-o-matic' ) ?></span></label>
									</td>
								</tr>

								<tr>
									<th><?php _e( 'Content Filter', 'jquery-collapse-o-matic' ) ?>:</th>
									<td><label><input type="checkbox" id="<?php echo $this->options_name ?>[filter_content]" name="<?php echo $this->options_name ?>[filter_content]" value="1"  <?php echo checked( $options['filter_content'], 1 ); ?> /> <?php _e('Apply filter', 'jquery-collapse-o-matic'); ?>
										<br /><span class="description"><?php _e('Apply the_content filter to target content.', 'jquery-collapse-o-matic'); ?></span></label>
									</td>
								</tr>

								<tr>
									<th><?php _e( 'Shortcode Loads Scripts', 'jquery-collapse-o-matic' ) ?>:</th>
									<td><label><input type="checkbox" id="<?php echo $this->options_name ?>[script_check]" name="<?php echo $this->options_name ?>[script_check]" value="1"  <?php echo checked( $options['script_check'], 1 ); ?> /> <?php _e('Only load scripts with shortcode.', 'jquery-collapse-o-matic'); ?>
										<br /><span class="description"><?php _e('Only load Collapse-O-Matic scripts if [expand] shortcode is used.', 'jquery-collapse-o-matic'); ?></span></label>
									</td>
								</tr>

								<tr>
									<th><?php _e( 'Shortcode Loads CSS', 'jquery-collapse-o-matic' ) ?>:</th>
									<td><label><input type="checkbox" id="<?php echo $this->options_name ?>[css_check]" name="<?php echo $this->options_name ?>[css_check]" value="1"  <?php echo checked( $options['css_check'], 1 ); ?> /> <?php _e('Only load CSS with shortcode.', 'jquery-collapse-o-matic'); ?>
										<br /><span class="description"><?php _e('Only load Collapse-O-Matic CSS if [expand] shortcode is used.', 'jquery-collapse-o-matic'); ?></span></label>
									</td>
								</tr>

								<tr>
									<th><?php _e( 'Script Load Location', 'jquery-collapse-o-matic' ) ?>:</th>
									<td><label><select id="<?php echo $this->options_name ?>[script_location]" name="<?php echo $this->options_name ?>[script_location]">
										<?php
											if(empty($options['script_location'])){
												$options['script_location'] = 'footer';
											}
											$sl_array = array(
												__('Header', 'jquery-collapse-o-matic') => 'header',
												__('Footer', 'jquery-collapse-o-matic') => 'footer'
											);
											foreach( $sl_array as $key => $value){
												$selected = '';
												if($options['script_location'] == $value){
													$selected = 'SELECTED';
												}
												echo '<option value="'.$value.'" '.$selected.'>'.$key.'</option>';
											}
										?>
										</select>
										<br /><span class="description"><?php _e('Where should the script be loaded, in the Header or the Footer?', 'jquery-collapse-o-matic'); ?></span></label>
									</td>
								</tr>
								<?php if( !is_plugin_active( 'collapse-commander/collapse-commander.php' ) ) : ?>
								<tr>
									<th><strong><?php _e( 'Take Command!', 'jquery-collapse-o-matic' ) ?></strong></th>
									<td><?php printf(__( '%sCollapse Commander%s is an add-on plugin that introduces an advanced management interface to better organize expand elements and simplify expand shortcodes.', 'jquery-collapse-o-matic' ), '<a href="http://plugins.twinpictures.de/premium-plugins/collapse-commander/?utm_source=collapse-o-matic&utm_medium=plugin-settings-page&utm_content=collapse-commander&utm_campaign=collapse-o-matic-commander">', '</a>'); ?>
									</td>
								</tr>
								<?php endif; ?>
								<tr>
									<th><strong><?php _e( 'Level Up!', 'jquery-collapse-o-matic' ) ?></strong></th>
									<td><?php printf(__( '%sCollapse-Pro-Matic%s is our premium plugin that offers additional attributes and features for <i>ultimate</i> flexibility.', 'jquery-collapse-o-matic' ), '<a href="http://plugins.twinpictures.de/premium-plugins/collapse-pro-matic/?utm_source=collapse-o-matic&utm_medium=plugin-settings-page&utm_content=collapse-pro-matic&utm_campaign=collapse-o-matic-pro">', '</a>'); ?>
									</td>
								</tr>
								</table>
							</fieldset>

							<p class="submit">
								<input class="button-primary" type="submit" value="<?php _e( 'Save Changes' ) ?>" />
							</p>
						</form>
					</div>
				</div>
			</div>
		</div>

		<div class="postbox-container side metabox-holder meta-box-sortables" style="width:29%;">
			<div style="margin:0 5px;">
				<div class="postbox">
					<div class="handlediv" title="<?php _e( 'Click to toggle', 'jquery-collapse-o-matic' ) ?>"><br/></div>
					<h3 class="hndle"><?php _e( 'About' ) ?></h3>
					<div class="inside">
						<h4><img src="<?php echo plugins_url( 'images/collapse-o-matic-icon.png', __FILE__ ) ?>" width="16" height="16"/> Collapse-O-Matic Version <?php echo $this->version; ?></h4>
						<p><?php _e( 'Remove clutter, save space. Display and hide additional content in a SEO friendly way. Wrap any content&mdash;including other shortcodes&mdash;into a lovely jQuery expanding and collapsing element.', 'jquery-collapse-o-matic') ?></p>
						<?php /*<p style="padding: 5px; border: 1px dashed #cccc66; background: #EEE;"><strong>Last Chance for 2015 Prices:</strong> <a href="http://plugins.twinpictures.de/premium-plugins/collapse-pro-matic/?utm_source=collapse-o-matic&utm_medium=plugin-settings-page&utm_content=collapse-pro-matic&utm_campaign=collapse-pro-year-end">Update to Collapse-Pro-Matic</a> before January 2016 to take advantage of 2015 pricing.</p> */ ?>
						<ul>
							<li><?php printf( __( '%sDetailed documentation%s, complete with working demonstrations of all shortcode attributes, is available for your instructional enjoyment.', 'jquery-collapse-o-matic'), '<a href="http://plugins.twinpictures.de/plugins/collapse-o-matic/documentation/" target="_blank">', '</a>'); ?></li>
							<li><?php printf( __( '%sFree Opensource Support%s', 'jquery-collapse-o-matic'), '<a href="http://wordpress.org/support/plugin/jquery-collapse-o-matic" target="_blank">', '</a>'); ?></li>
							<li><?php printf( __( 'If this plugin %s, please consider %sreviewing it at WordPress.org%s to help others.', 'jquery-collapse-o-matic'), $like_it, '<a href="http://wordpress.org/support/view/plugin-reviews/jquery-collapse-o-matic" target="_blank">', '</a>' ) ?></li>
							<li><a href="http://wordpress.org/extend/plugins/jquery-collapse-o-matic/" target="_blank">WordPress.org</a> | <a href="http://plugins.twinpictures.de/plugins/collapse-o-matic/" target="_blank">Twinpictues Plugin Oven</a></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>

		<?php if( is_plugin_active( 'collapse-commander/collapse-commander.php' ) ) : ?>

		<div class="postbox-container side metabox-holder" style="width:29%;">
			<div style="margin:0 5px;">
				<div class="postbox">
					<h3 class="handle"><?php _e( 'Register Collapse Commander', 'jquery-collapse-o-matic') ?></h3>
					<div class="inside">
                                            <p><?php printf( __('To receive plugin updates you must register your plugin. Enter your Collapse Commander licence key below. Licence keys may be viewed and manged by logging into %syour account%s.', 'colpromat'), '<a href="http://plugins.twinpictures.de/your-account/" target="_blank">', '</a>'); ?></p>
						<form method="post" action="options.php">
                                                    <?php
                                                        settings_fields( $this->license_group );
                                                        $options = get_option($this->license_name);
                                                        $cc_licence = ( !isset( $options['collapse_commander_license_key'] ) ) ? '' : $options['collapse_commander_license_key'];
						     ?>
							<fieldset>
								<table style="width: 100%">
									<tbody>
										<tr>
											<th><?php _e( 'License Key', 'colpromat' ) ?>:</th>
											<td><label for="<?php echo $this->license_name ?>[collapse_commander_license_key]"><input type="text" id="<?php echo $this->license_name ?>[collapse_commander_license_key]" name="<?php echo $this->license_name ?>[collapse_commander_license_key]" value="<?php esc_attr_e( $cc_licence ); ?>" style="width: 100%" />
												<br /><span class="description"><?php _e('Enter your license key', 'colpromat'); ?></span></label>
											</td>

										</tr>

										<?php if( isset($options['collapse_commander_license_key']) ) { ?>
										    <tr valign="top">
											<th><?php _e('License Status', 'colpromat'); ?>:</th>
											<td>
											    <?php if( isset($options['collapse_commander_license_status']) && $options['collapse_commander_license_status'] == 'valid' ) { ?>
												<span style="color:green;"><?php _e('active'); ?></span><br/>
												<input type="submit" class="button-secondary" name="edd_cc_license_deactivate" value="<?php _e('Deactivate License'); ?>"/>
											    <?php } else {
												    if( isset($options['collapse_commander_license_status']) ){ ?>
													<span style="color: red"><?php echo $options['collapse_commander_license_status']; ?></span><br/>
												<?php } else { ?>
													<span style="color: grey">inactive</span><br/>
												<?php } ?>
												    <input type="submit" class="button-secondary" name="edd_cc_license_activate" value="<?php _e('Activate License'); ?>"/>
											    <?php } ?>
											    </td>
										    </tr>
										<?php } ?>
									</tbody>
								</table>
							</fieldset>
							<?php submit_button( __( 'Register', 'colpromat') ); ?>
						</form>
					</div>
				</div>
			</div>
		</div>
		<?php else: ?>
		<div class="postbox-container side metabox-holder meta-box-sortables" style="width:29%;">
			<div style="margin:0 5px;">
				<div class="postbox">
					<div class="handlediv" title="<?php _e( 'Click to toggle', 'jquery-collapse-o-matic' ) ?>"><br/></div>
					<h3 class="hndle">Collapse Commander</h3>
						<div class="inside">
							<p>A brief and not-exactly-sober overview of <a href="http://plugins.twinpictures.de/premium-plugins/collapse-commander/?utm_source=collapse-o-matic&utm_medium=plugin-settings-page&utm_content=collapse-commander&utm_campaign=collapse-o-matic-commander">Collapse Commander</a>, a new add-on plugin for Collapse-O-Matic and Collapse-Pro-Matic that adds and advanded expand shortcode management system.</p>
							<iframe width="100%" height="300" src="//www.youtube.com/embed/w9X4nXpAEfo" frameborder="0" allowfullscreen></iframe>
						</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
		<?php endif; ?>
	<?php
	}

	/**
	 * Set options from save values or defaults
	 */
	function _set_options() {
		// set options
		$saved_options = get_option( $this->options_name );

		// backwards compatible (old values)
		if ( empty( $saved_options ) ) {
			$saved_options = get_option( $this->domain . 'options' );
		}

		// set all options
		if ( ! empty( $saved_options ) ) {
			foreach ( $this->options AS $key => $option ) {
				$this->options[ $key ] = ( empty( $saved_options[ $key ] ) ) ? '' : $saved_options[ $key ];
			}
		}
	}

	function edd_sanitize_license( $new ) {
            //collapse commander
            $options = get_option($this->license_name);
            $old_cc = ( !isset( $options['collapse_commander_license_key'] ) ) ? '' : $options['collapse_commander_license_key'];
            $old_cc_status = ( !isset( $options['collapse_commander_license_status'] ) ) ? '' : $options['collapse_commander_license_status'];

            if( !empty($old_cc) && $old_cc != $new['collapse_commander_license_key'] ) {
                    $new['collapse_commander_license_status'] = '';
            }
            else{
                $new['collapse_commander_license_status'] = $old_cc_status;
            }

            if( isset( $_POST['edd_cc_license_activate'] ) ) {
                $new['collapse_commander_license_status'] = $this->plugin_oven_activate_license( urlencode( PLUGIN_OVEN_CC ), $new['collapse_commander_license_key'], 'activate_license');
            }

            if( isset( $_POST['edd_cc_license_deactivate'] ) ) {
                $new['collapse_commander_license_status'] = $this->plugin_oven_activate_license( urlencode( PLUGIN_OVEN_CC ), $new['collapse_commander_license_key'], 'deactivate_license');
            }
            return $new;
        }


	/************************************
	* this illustrates how to activate
	* a license key
	*************************************/

	function plugin_oven_activate_license($plugin_name, $license_key, $edd_action) {
            // data to send in our API request
            $api_params = array(
                    'edd_action'    => $edd_action,
                    'license' 	    => $license_key,
                    'item_name'     => $plugin_name,
                    'url'           => home_url()
            );

            // Call the custom API.
			$response = wp_remote_get( esc_url_raw( add_query_arg( $api_params, PLUGIN_OVEN_URL ) ), array( 'timeout' => 15, 'sslverify' => false ) );

            // make sure the response came back okay
            if ( is_wp_error( $response ) )
                    return false;

            // decode the license data
            $license_data = json_decode( wp_remote_retrieve_body( $response ) );

            // $license_data->license will be either "valid" or "invalid"
            return $license_data->license;
	}

} // end class WP_Collapse_O_Matic


/**
 * Create instance
 */
$WP_Collapse_O_Matic = new WP_Collapse_O_Matic;

//clean unwanted p and br tags from shortcodes
//http://www.wpexplorer.com/clean-up-wordpress-shortcode-formatting
if (!function_exists('tp_clean_shortcodes')) {
	function tp_clean_shortcodes($content){
		$array = array (
		    '<p>[' => '[',
		    ']</p>' => ']',
		    ']<br />' => ']'
		);
		$content = strtr($content, $array);
		return $content;
	}
	add_filter('the_content', 'tp_clean_shortcodes');
}

?>
