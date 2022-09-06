<?php
# Database Configuration
define( 'DB_NAME', 'snapshot_noexam' );
define( 'DB_USER', 'root' );
define( 'DB_PASSWORD', '' );
define( 'DB_HOST', '127.0.0.1' );
define( 'DB_HOST_SLAVE', '127.0.0.1' );
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', 'utf8_unicode_ci');
$table_prefix = 'wp_';

define( 'WP_DEBUG', false );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false ); 

# Security Salts, Keys, Etc
define('AUTH_KEY',         ',oX`K1|F+xq{;c+pz)))8aR>.nf$zbQ7(i,I`bNp3.P5hH;<}juIF)@HQV>;cZ9k');
define('SECURE_AUTH_KEY',  'l!u*A]S2Rifne:7YCGgZV;W/YUm$3$R)UKrbIv[xm_G|E;}uXpn!sL;) /h_.7;X');
define('LOGGED_IN_KEY',    'FILOf r&FE/u$f7F,2-9-Gl2HTajo@=JcBVD|2$#@l>swpIZ10|$TnCG=nvAR`-Q');
define('NONCE_KEY',        'J,uMAS+:#!@g{afuc[}`whv|%cGEc[(7+2z2?&+S3w3@-4Oha,t63^;&[-W4Yf6t');
define('AUTH_SALT',        'nayNcdZSi33/s|yv7G&yVC.+wIE!Gr[%}?5!(C=APobfIm#o3tO^#|P#eXMSG$2X');
define('SECURE_AUTH_SALT', 'o_UK0HLO`hZK+L-|=u<Cp~_MBBp+8V+mI$Ph>%)7:]EOj_r| |X]XUZk;0.ZwCb]');
define('LOGGED_IN_SALT',   'mboAwHZ9GU?reO-5njpIH%>mSh*+vjlX.O-%+=xw0nN+NNjG1v`.n|Oi#Rg5>1_v');
define('NONCE_SALT',       '4JPpb[<2`CjBdo=lmj|qf(yRC<,5BH6YIb,AU/+p)KHEIsaUuU>dJIG<e1nyqrgd');

# Localized Language Stuff

define( 'WP_CACHE', TRUE );

define( 'PWP_NAME', 'noexam' );

define( 'FS_METHOD', 'direct' );

define( 'FS_CHMOD_DIR', 0775 );

define( 'FS_CHMOD_FILE', 0664 );

define( 'PWP_ROOT_DIR', '/nas/wp' );

define( 'WPE_APIKEY', '4d30baeb264f477817c3cdc102b940395368ede4' );

define( 'WPE_FOOTER_HTML', "" );

define( 'WPE_CLUSTER_ID', '34024' );

define( 'WPE_CLUSTER_TYPE', 'pod' );

define( 'WPE_ISP', true );

define( 'WPE_BPOD', false );

define( 'WPE_RO_FILESYSTEM', false );

define( 'WPE_LARGEFS_BUCKET', 'largefs.wpengine' );

define( 'WPE_CDN_DISABLE_ALLOWED', false );

define( 'DISALLOW_FILE_EDIT', FALSE );

define( 'DISALLOW_FILE_MODS', FALSE );

define( 'DISABLE_WP_CRON', true );

define( 'WPE_FORCE_SSL_LOGIN', false );

define( 'FORCE_SSL_LOGIN', false );
// define('FORCE_SSL_ADMIN', true);

/*SSLSTART*/ if ( isset($_SERVER['HTTP_X_WPE_SSL']) && $_SERVER['HTTP_X_WPE_SSL'] ) $_SERVER['HTTPS'] = 'on'; /*SSLEND*/

define( 'WPE_EXTERNAL_URL', false );

define( 'WP_POST_REVISIONS', FALSE );

define( 'WPE_WHITELABEL', 'wpengine' );

define( 'WP_TURN_OFF_ADMIN_BAR', false );

define( 'WPE_BETA_TESTER', false );

umask(0002);

$wpe_cdn_uris=array ( );

$wpe_no_cdn_uris=array ( );

$wpe_content_regexs=array ( );

$wpe_all_domains=array ( 0 => 'noexam.com', 1 => 'noexam.wpengine.com', 2 => 'www.noexam.dev', );

$wpe_varnish_servers=array ( 0 => 'pod-34024', );

$wpe_ec_servers=array ( );

$wpe_largefs=array ( );

//$wpe_netdna_domains=array ( 0 =>  array ( 'match' => 'www.noexam.dev', 'zone' => 'lfgok25vr8k38ia9z17sg8ga', 'secure' => true, 'dns_check' => '0', ), 1 =>  array ( 'match' => 'noexam.com', 'zone' => '4bdlfo2jewqu3ww15d3kx6j3', 'secure' => true, 'dns_check' => '0', ), );

$wpe_netdna_push_domains=array ( );

$wpe_domain_mappings=array ( );

$memcached_servers=array ( );

define( 'WP_AUTO_UPDATE_CORE', false );

$wpe_special_ips=array ( 0 => '166.78.99.202', );

//$wpe_netdna_domains_secure=array ( 0 =>  array ( 'match' => 'www.noexam.dev', 'zone' => 'lfgok25vr8k38ia9z17sg8ga', 'secure' => true, 'dns_check' => '0', ), 1 =>  array ( 'match' => 'noexam.dev', 'zone' => '4bdlfo2jewqu3ww15d3kx6j3', 'secure' => true, 'dns_check' => '0', ), );

define( 'WPE_CACHE_TYPE', 'generational' );

define( 'WP_SITEURL', 'http://www.noexam.dev' );

define( 'WP_HOME', 'http://www.noexam.dev' );

define( 'WPE_LBMASTER_IP', '166.78.99.202' );

//define( 'WPE_SFTP_PORT', 2222 );
define('WPLANG','');

# WP Engine ID


# WP Engine Settings






# That's It. Pencils down
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
require_once(ABSPATH . 'wp-settings.php');

$_wpe_preamble_path = null; if(false){}
