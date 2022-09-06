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
    
    
    
    
    
    <!--FooterBottom area start-->
    <div class="row-fluid footer_bottom">
    	<div class="container">&copy; SILI 2013. All Rights Reserved</div>
    </div>
    <!--FooterBottom area end-->

<?php wp_footer(); ?>


<!----- bootstrap js start ----->
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery.js"></script>
<script language="javascript" src="<?php echo get_template_directory_uri(); ?>/js/bootstrap.js"></script>
<!----- bootstrap js end ----->


<?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->

</body>
</html>