<?php
/**
 * Template Name: page with top logo and apply
 *
 * Description: Twenty Twelve loves the no-sidebar look as much as
 * you do. Use this page template to remove the sidebar from any page.
 *
 * Tip: to remove the sidebar from all posts and pages simply remove
 * any active widgets from the Main Sidebar area, and the sidebar will
 * disappear everywhere.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
get_header(); ?>

            <div id="primary" class="site-content">
		<div id="content" role="main">
        
        	<!--outer freePro start-->
            <div class="row-fluid freePro">
                <div class="container freePro-part">
                
                	<p>The New Way To Shop For Life Insurance</p>
                    
                    <div class="row-fluid">
                        <div class="applyBox">
                            <div class="span2"><img src="<?php echo get_template_directory_uri(); ?>/images/national.jpg" alt="" /></div>
                            <div class="span2">A.M Best Rating <span class="teXt">&ldquo;A / Excellent&rdquo;</span></div>
                            <div class="span2">No Medical Exam <span><img src="<?php echo get_template_directory_uri(); ?>/images/tick.jpg" alt="" /></span></div>
                            <div class="span2">Instant Approval <span><img src="<?php echo get_template_directory_uri(); ?>/images/tick.jpg" alt="" /></span></div>
                            <div class="span2">Monthly Rates <span class="teXt">Starting from <br /> $19/Month</span></div>
                            <div class="span2 abblyBnt"><a rel="nofollow" href="#" >Click Here To Apply</a></div>
                        </div>
                    </div>
                    
                    <div class="row-fluid">
                        <div class="applyBox">
                            <div class="span2"><img src="<?php echo get_template_directory_uri(); ?>/images/national.jpg" alt="" /></div>
                            <div class="span2">A.M Best Rating <span class="teXt">&ldquo;A / Excellent&rdquo;</span></div>
                            <div class="span2">No Medical Exam <span><img src="<?php echo get_template_directory_uri(); ?>/images/tick.jpg" alt="" /></span></div>
                            <div class="span2">Instant Approval <span><img src="<?php echo get_template_directory_uri(); ?>/images/tick.jpg" alt="" /></span></div>
                            <div class="span2">Monthly Rates <span class="teXt">Starting from <br /> $29/Month</span></div>
                            <div class="span2 abblyBnt"><a rel="nofollow" href="http://secure.simplifiedissuelifeinsurance.com" >Click Here To Apply</a></div>
                        </div>
                    </div>
                    
                </div>
            </div>
            <!--outer freePro end-->
            
            <!--Review area start-->
            <div class="row-fluid reviewArea">
                <div class="container">
                
                    <!--Use shortcodes for review section-->
                    <h3>Customer Feedback</h3>
                   <?php echo do_shortcode('[WPCR_INSERT]'); ?>
                   <h2>No Exam Life Insurance Quotes</h2>
            <p>You can compare life insurance carriers above before applying for a no exam quote online. Once you complete the application, you will be given a price along with the option to secure your coverage immediately. <strong>Your no medical life insurance quote depends on the results of your health questionnaire</strong>. A new era of life insurance is here, and it requires no medical exam.</p>
                </div>
            </div>
            <!--Review area end-->
            
            

<?php get_footer(); ?>