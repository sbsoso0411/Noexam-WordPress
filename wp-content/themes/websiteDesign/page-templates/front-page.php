<?php
/**
 * Template Name: Front Page Template
 *
 * Description: A page template that provides a key component of WordPress as a CMS
 * by meeting the need for a carefully crafted introductory page. The front page template
 * in Twenty Twelve consists of a page content area for adding text, images, video --
 * anything you'd like -- followed by front-page-only widgets in one or two columns.
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
                        <?php dynamic_sidebar( 'sidebar-4' ); ?>
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
            
            <!--Loan for area start-->
            <div class="row-fluid loanFor">
                <div class="container loanForBox">
                    <div class="span6 loanForCon">
                    	<?php dynamic_sidebar( 'sidebar-9' ); ?>
                    </div>
                    <div class="span6 loanpic">
                    	<?php dynamic_sidebar( 'sidebar-10' ); ?>
                    </div>
                </div>
            </div>
            <!--Loan for area end-->
            
        	<!--Main Body area start-->
            <div class="row-fluid bodybottom">
                <div class="container">
                    
                    <?php while ( have_posts() ) : the_post(); ?>
					<?php if ( has_post_thumbnail() ) : ?>
                        <div class="entry-page-image">
                            <?php //the_post_thumbnail(); ?>
                        </div><!-- .entry-page-image -->
                    <?php endif; ?>
    
                    <?php get_template_part( 'content', 'page' ); ?>
                
                

			<?php endwhile; // end of the loop. ?>
                    
                </div>
            </div>
            <!--Main Body area end-->
            

			
<?php //get_sidebar( 'front' ); ?>
<?php get_footer(); ?>