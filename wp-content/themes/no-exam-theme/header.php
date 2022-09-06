<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Hellish Simplicity
 * @since Hellish Simplicity 1.1
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
<link rel="icon" href="/favicon.ico" type="image/x-icon">
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<meta name="p:domain_verify" content="a3b1b4a104d402442aa168b65af68622"/>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php wp_head(); ?>
    
            <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>

    
    <link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" media="screen" />

    	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/stylesheets/base.css">
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/stylesheets/skeleton.css">
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/stylesheets/layout.css">
    <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/stylesheets/responsivemobilemenu.css" type="text/css"/>
    <link type="text/css" rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/stylesheets/lightSlider.css"/>
    
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/responsivemobilemenu.js"></script>
    <script src="<?php bloginfo('template_directory'); ?>/js/jquery.lightSlider.js"></script>
    <script src="<?php bloginfo('template_directory'); ?>/js/jquery.anchor.js"></script>
    <script src="<?php bloginfo('template_directory'); ?>/js/script.js"></script>
    <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-40781470-1', 'auto', {'allowLinker': true});
ga('require', 'linker');
ga('linker:autoLink', ['secure.noexam.com', 'get.noexam.com'] );
  ga('send', 'pageview');

</script>

<script type = "text/javascript">
	$(document).ready(function(){
//		$('input').on('keyup', function(){
//			var inputStr = $(this).val();
//			inputStr = inputStr.replace(/\//g,"");
//			$(this).val(inputStr);
//		})
	})
</script>

<script src="<?php bloginfo('template_directory'); ?>/js/TweenMax.min.js"></script>
		 <script async language="Javascript" type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/simpleSlide.js"></script>
<script type="text/javascript">
	var $mcGoal = {'settings':{'uuid':'cd28b7f7a73e06ae60061942e','dc':'us13'}};
	(function() {
		 var sp = document.createElement('script'); sp.type = 'text/javascript'; sp.async = true; sp.defer = true;
		sp.src = ('https:' == document.location.protocol ? 'https://s3.amazonaws.com/downloads.mailchimp.com' : 'http://downloads.mailchimp.com') + '/js/goal.min.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(sp, s);
	})(); 
</script>
<!-- Hotjar Tracking Code for https://www.noexam.com -->
<script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:311997,hjsv:5};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'//static.hotjar.com/c/hotjar-','.js?sv=');
</script>
<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '260127064326809');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=260127064326809&ev=PageView&noscript=1"
/></noscript>
<!-- DO NOT MODIFY -->
<!-- End Facebook Pixel Code -->
</head>
<body <?php body_class(); ?>>
    <!--
<header id="site-header" role="banner">
	<div class="hgroup">
		<h1>
			<a href="https://www.noexam.com/" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
				<?php
					// Output header text (need fallback to keep WordPress.org them demo happy)
					$header_text = get_option( 'header-text' );
					if ( $header_text ) {
						echo $header_text; // Not escaped, since needs to include HTML
					} else {
						echo 'Hellish<span>Simplicity</span><small>.tld</small>';
					}
				?>
			</a>
		</h1>
		<h2><?php bloginfo( 'description' ); ?></h2>
	</div>
</header>
    
    -->
    
<div class="nav-wrapper">
<div class="container">

	 
    <div class="sixteen columns">
        <div id="nav-bar">
            <div id="logo"><a href="https://www.noexam.com/"><img src="<?php bloginfo('template_directory'); ?>/images/noexam-logo.png" width="160px"></a></div>
            <div class="main-nav">
            <nav>
		     <div class="rmm" data-menu-style = "minimal">
                 
             </div>
			 </nav>
             </div>
            <div class="phonenumber">
            <img src="<?php bloginfo('template_directory'); ?>/images/norton.png">
            <h3>Speak to a licensed agent</h3>
            <h2>888 407 0714 </h2>
            </div>
		</div>
    </div>
</div>
</div>
<!-- begin olark code -->
<script data-cfasync="false" type='text/javascript'>/*<![CDATA[*/window.olark||(function(c){var f=window,d=document,l=f.location.protocol=="https:"?"https:":"http:",z=c.name,r="load";var nt=function(){
f[z]=function(){
(a.s=a.s||[]).push(arguments)};var a=f[z]._={
},q=c.methods.length;while(q--){(function(n){f[z][n]=function(){
f[z]("call",n,arguments)}})(c.methods[q])}a.l=c.loader;a.i=nt;a.p={
0:+new Date};a.P=function(u){
a.p[u]=new Date-a.p[0]};function s(){
a.P(r);f[z](r)}f.addEventListener?f.addEventListener(r,s,false):f.attachEvent("on"+r,s);var ld=function(){function p(hd){
hd="head";return["<",hd,"></",hd,"><",i,' onl' + 'oad="var d=',g,";d.getElementsByTagName('head')[0].",j,"(d.",h,"('script')).",k,"='",l,"//",a.l,"'",'"',"></",i,">"].join("")}var i="body",m=d[i];if(!m){
return setTimeout(ld,100)}a.P(1);var j="appendChild",h="createElement",k="src",n=d[h]("div"),v=n[j](d[h](z)),b=d[h]("iframe"),g="document",e="domain",o;n.style.display="none";m.insertBefore(n,m.firstChild).id=z;b.frameBorder="0";b.id=z+"-loader";if(/MSIE[ ]+6/.test(navigator.userAgent)){
b.src="javascript:false"}b.allowTransparency="true";v[j](b);try{
b.contentWindow[g].open()}catch(w){
c[e]=d[e];o="javascript:var d="+g+".open();d.domain='"+d.domain+"';";b[k]=o+"void(0);"}try{
var t=b.contentWindow[g];t.write(p());t.close()}catch(x){
b[k]=o+'d.write("'+p().replace(/"/g,String.fromCharCode(92)+'"')+'");d.close();'}a.P(2)};ld()};nt()})({
loader: "static.olark.com/jsclient/loader0.js",name:"olark",methods:["configure","extend","declare","identify"]});
/* custom configuration goes here (www.olark.com/documentation) */
olark.identify('3739-808-10-6651');/*]]>*/</script>
<noscript><a href="https://www.olark.com/site/3739-808-10-6651/contact" title="Contact us" target="_blank">Questions? Feedback?</a></noscript>
<!-- end olark code -->
<div id="main" class="site-main">
