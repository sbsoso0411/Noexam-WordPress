<?php
/**
 * Template Name: page with top logo4
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
                    <div class="span7">
                        <h1>Term Life Insurance</h1>
<p>Get Up To $350,000 With No Medical Exam. Secure Your Coverage Today.</p>
                    </div>
                </div>
            </div>
            <!--outer freePro end-->
            
            <!--outer banner start-->
            <div class="row-fluid bannerArea">
                <div class="banner">
                    <div class="container banner_box">
                    
                        <!--banner_form start-->
                        
                        <div class="banner_form">
                            <div class="banner_form_top"></div>
                            
                            <div class="banner_form_mid">
                            	<?php dynamic_sidebar( 'sidebar-5' ); ?>
                            </div>
                            
                            <div class="banner_form_down"></div>
                        </div>
                        <!--banner_form end-->
                        
                        <!--banner_contect start-->
                        <div class="span6 bannerCon">
                            <ul class="span8">
                            	<?php dynamic_sidebar( 'sidebar-6' ); ?>
                            </ul>
                            
                            <div class="bluearea span12">
                                <div class="blueareaArrow">
                                    <?php dynamic_sidebar( 'sidebar-7' ); ?>
                                </div>
                            </div>
                            
                            <div class="bannerPic">
                            	<?php the_post_thumbnail('full', array('class' => '')); ?>
                            </div>
                        </div>
                        <!--banner_contect end-->
                        
                        <br clear="all" />
                        
                    </div>
                </div>
            </div>
            <!--outer banner end-->
            
<!--Body area start-->
            <div class="row-fluid bodyArea">
                <div class="container">
                
                	<?php dynamic_sidebar( 'sidebar-8' ); ?>
                    
                </div>
            </div>
            <!--Body area end-->
            
            
    

<?php get_footer(); ?>