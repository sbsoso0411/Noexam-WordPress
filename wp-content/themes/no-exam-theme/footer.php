<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package Hellish Simplicity
 * @since Hellish Simplicity 1.1
 */
?>

</div><!-- #main .site-main -->

 <div class="footer-wrapper" id="footer">
        <div class="container">
                <ul>
                    <li><a href="https://www.noexam.com/">Home</a></li>
                    <li><a href="<?php bloginfo('url'); ?>/about/">About</a></li>
                    
                    <li><a href="<?php bloginfo('url'); ?>/blog/">Blog</a></li>
                    <li><a href="https://www.noexam.com/medical-conditions-medications/">Applying With a Pre Existing Condition</a></li>
                    <li><a href="https://www.noexam.com/guide/">Life Insurance Guide</a></li>
                    <li><a href="<?php bloginfo('url'); ?>/privacy-policy/">Privacy</a></li>
                </ul>           
                <p>© NoExam.com 2016, All Rights Reserved. 24 Sloane St Suite B Roswell, GA 30075 Tel: 888-407-0714
<?php 

ini_set('default_socket_timeout', 3);
echo file_get_contents('https://www.shopperapproved.com/feeds/schema.php/?siteid=12271&token=7CkgVXPN');

?>
</p>
            
        
        </div>
    </div>
<!-- Google Code for Remarketing Tag -->
<!--------------------------------------------------
Remarketing tags may not be associated with personally identifiable information or placed on pages related to sensitive categories. See more information and instructions on how to setup the tag on: http://google.com/ads/remarketingsetup
--------------------------------------------------->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 1021976105;
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/1021976105/?value=0&amp;guid=ON&amp;script=0"/>
</div>
</noscript>

<?php wp_footer(); ?> 
</body>
</html>