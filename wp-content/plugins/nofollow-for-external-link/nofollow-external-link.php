<?php
/*
Plugin Name: Nofollow for external link
Plugin URI: http://www.cybernetikz.com
Description: Just simple, if you activate this plugins, <code>rel=&quot;nofollow&quot;</code> and <code>target=&quot;_blank&quot;</code> will be added automatically, for all the external links of your website <strong>posts</strong> or <strong>pages</strong>. Also you can <strong>exclude domains</strong>, not to add <code>rel=&quot;nofollow&quot;</code> for the selected external links.
Version: 1.2.0
Author: CyberNetikz
Author URI: http://www.cybernetikz.com
License: GPL2
*/

if( !defined('ABSPATH') ) die('-1');

function cn_nf_install() {
	add_option( 'cn_nf_exclude_domains', '');
	add_option( 'cn_nf_apply_to_menu', '1');
}
register_activation_hook(__FILE__,'cn_nf_install');

function cn_nf_uninstall() {
	delete_option( 'cn_nf_apply_to_menu' ); 
}
register_deactivation_hook(__FILE__,'cn_nf_uninstall');

function cn_nf_admin_sidebar() {

	$banners = array(
		array(
			'url' => 'http://www.cybernetikz.com/wordpress-magento-plugins/wordpress-plugins/?utm_source=nofollow-for-external-link&utm_medium=banner&utm_campaign=wordpress-plugins',
			'img' => 'banner-1.jpg',
			'alt' => 'Banner 1',
		),
		array(
			'url' => 'http://www.cybernetikz.com/portfolio/web-development/wordpress-website/?utm_source=nofollow-for-external-link&utm_medium=banner&utm_campaign=wordpress-plugins',
			'img' => 'banner-2.jpg',
			'alt' => 'Banner 2',
		),
		array(
			'url' => 'http://www.cybernetikz.com/seo-consultancy/?utm_source=nofollow-for-external-link&utm_medium=banner&utm_campaign=wordpress-plugins',
			'img' => 'banner-3.jpg',
			'alt' => 'Banner 3',
		),
	);
	//shuffle( $banners );
	?>
	<div class="cn_admin_banner">
	<?php
	$i = 0;
	foreach ( $banners as $banner ) {
		echo '<a target="_blank" href="' . esc_url( $banner['url'] ) . '"><img width="261" height="190" src="' . plugins_url( 'images/' . $banner['img'], __FILE__ ) . '" alt="' . esc_attr( $banner['alt'] ) . '"/></a><br/><br/>';
		$i ++;
	}
	?>
	</div>
<?php
}

function cn_nf_admin_style() {
	global $pluginsURI;
	wp_register_style( 'cn_nf_admin_css', plugins_url( 'nofollow-for-external-link/css/admin-style.css' ) , false, '1.0' );
	wp_enqueue_style( 'cn_nf_admin_css' );
}
add_action( 'admin_enqueue_scripts', 'cn_nf_admin_style' );

function register_cn_nf_settings() {
	register_setting( 'cn-nf-settings-group', 'cn_nf_exclude_domains' );
	register_setting( 'cn-nf-settings-group', 'cn_nf_apply_to_menu' );
}
add_action( 'admin_init', 'register_cn_nf_settings' );

function cn_nf_plugin_menu() {
	add_options_page('Nofollow for external link', 'NoFollow ExtLink', 'manage_options', 'cn_nf_option_page', 'cn_nf_option_page_fn');
}
add_action( 'admin_menu', 'cn_nf_plugin_menu');

function cn_nf_option_page_fn() {
	$cn_nf_exclude_domains = get_option('cn_nf_exclude_domains');
	$cn_nf_apply_to_menu = get_option('cn_nf_apply_to_menu');
	?>
	<div class="wrap">
	<h2>Nofollow for external link Options</h2>
	<div class="content_wrapper">
	<div class="left">
	<form method="post" action="options.php" enctype="multipart/form-data">
		<?php settings_fields( 'cn-nf-settings-group' ); ?>
		<table class="form-table">
        
			<tr valign="top">
			<th scope="row">Apply nofollow to Menu</th>
			<td><input <?php echo ($cn_nf_apply_to_menu == 1)?'checked="checked"':''; ?>  type="checkbox" name="cn_nf_apply_to_menu" id="cn_nf_apply_to_menu" value="1" /><br />
			<em>If you check this box then <code>rel="nofollow"</code> and <code>target="_blank"</code> will be added to all external links of your <a href="nav-menus.php">Theme Menus</a></em></td>
			</tr>
        
			<tr valign="top">
			<th scope="row">Exclude Domains</th>
			<td><textarea name="cn_nf_exclude_domains" id="cn_nf_exclude_domains" class="large-text" placeholder="mydomain.com, my-domain.org, another-domain.net"><?php echo $cn_nf_exclude_domains?></textarea>
            <br /><em>Domain name <strong>must be</strong> comma(,) separated. <!--<br />Example: facebook.com, google.com, youtube.com-->Don't need to add <code>http://</code> or <code>https://</code><br /><code>rel="nofollow"</code> will not added to "Exclude Domains"</em></td>
			</tr>
		</table>
		<p class="submit">
		<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>
	</form>
    </div>
    <div class="right">
    <?php cn_nf_admin_sidebar(); ?>
    </div>
    </div>
	</div>
	<?php 
}

function cn_nf_url_parse( $content ) {

	$regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>";
	if(preg_match_all("/$regexp/siU", $content, $matches, PREG_SET_ORDER)) {
		if( !empty($matches) ) {
			
			//$ownDomain = get_option('home');
			$ownDomain = $_SERVER['HTTP_HOST'];
			
			$exclude_domains_list = array();
			if(get_option('cn_nf_exclude_domains')!='') {
				$exclude_domains_list = explode(",",get_option('cn_nf_exclude_domains'));
			}
			
			for ($i=0; $i < count($matches); $i++)
			{
			
				$tag  = $matches[$i][0];
				$tag2 = $matches[$i][0];
				$url  = $matches[$i][0];
					
				// bypass #more type internal link
				$res = preg_match('/href(\s)*=(\s)*"[#|\/]*[a-zA-Z0-9-_\/]+"/',$url);
				if($res) {
					continue;
				}
				
				$pos = strpos($url,$ownDomain);
				if ($pos === false) {
					
					$domainCheckFlag = true;
					
					if(count($exclude_domains_list)>0) {
						$exclude_domains_list = array_filter($exclude_domains_list);
						foreach($exclude_domains_list as $domain) {
							$domain = trim($domain);
							if($domain!='') {
								$domainCheck = strpos($url,$domain);
								if($domainCheck === false) {
									continue;
								} else {
									$domainCheckFlag = false;
									break;
								}
							}
						}	
					}
					
					$noFollow = '';
	
					// add target=_blank to url
					$pattern = '/target\s*=\s*"\s*_blank\s*"/';
					preg_match($pattern, $tag2, $match, PREG_OFFSET_CAPTURE);
					if( count($match) < 1 )
						$noFollow .= ' target="_blank"';
						
					//exclude domain or add nofollow
					if($domainCheckFlag) {
						$pattern = '/rel\s*=\s*"\s*[n|d]ofollow\s*"/';
						preg_match($pattern, $tag2, $match, PREG_OFFSET_CAPTURE);
						if( count($match) < 1 )
							$noFollow .= ' rel="nofollow"';
					}
					
					// add nofollow/target attr to url
					$tag = rtrim ($tag,'>');
					$tag .= $noFollow.'>';
					$content = str_replace($tag2,$tag,$content);
				}
			}
		}
	}
	
	$content = str_replace(']]>', ']]&gt;', $content);
	return $content;
}

add_filter( 'the_content', 'cn_nf_url_parse');

if( get_option('cn_nf_apply_to_menu') ) {
	add_filter( 'wp_nav_menu_items', 'cn_nf_url_parse' );
}