<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>
    
    
    </div>
    <!--Content area end-->
    
    
    <!--Footer area start-->
    <div class="row-fluid footer">
    	<div class="container">
        
        	<!--Footer Menu start-->
        	<div class="footerMenu">
            
            	<!--leftMenu start-->
                
                <div class="leftMenu">
                	
                </div>
                <!--leftMenu end-->
                
                <!--rightMenu start-->
                <div class="rightMenu">
                	 <?php wp_nav_menu( array( 'theme_location' => 'footerright', 'menu_class' => '' ) ); ?>
                </div>
                <!--rightMenu end-->
                
                <br clear="all" />
                
            </div>
            <!--Footer Menu end-->
            
            <!--Footer Content start-->
            <div class="container footerCon">
            	<?php dynamic_sidebar( 'sidebar-12' ); ?>
            </div>
            
            <br clear="all" />
            <!--Footer Content end-->
            
        </div>
    </div>
    <!--Footer area end-->
    
    
    <!--FooterBottom area start-->
    <div class="row-fluid footer_bottom">
    	<div class="container">&copy; No Exam Life Insurance, Inc 2014. All Rights Reserved</div>
    </div>
    <!--FooterBottom area end-->

<?php wp_footer(); ?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-40781470-1', 'simplifiedissuelifeinsurance.com');
  ga('send', 'pageview');

</script>

<!----- bootstrap js start ----->
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery.js"></script>
<script language="javascript" src="<?php echo get_template_directory_uri(); ?>/js/bootstrap.js"></script>
<!----- bootstrap js end ----->


<?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<script type="text/javascript">
adroll_adv_id = "DJ6QM6MNPJDAVEUXW5CJVG";
adroll_pix_id = "OGVLNHAZW5E7HK37Y6QQS2";
(function () {
var oldonload = window.onload;
window.onload = function(){
   __adroll_loaded=true;
   var scr = document.createElement("script");
   var host = (("https:" == document.location.protocol) ? "https://s.adroll.com" : "http://a.adroll.com");
   scr.setAttribute('async', 'true');
   scr.type = "text/javascript";
   scr.src = host + "/j/roundtrip.js";
   ((document.getElementsByTagName('head') || [null])[0] ||
    document.getElementsByTagName('script')[0].parentNode).appendChild(scr);
   if(oldonload){oldonload()}};
}());
</script>
</body>
</html>