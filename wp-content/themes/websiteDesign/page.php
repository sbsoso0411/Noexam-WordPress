<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>

            <!--Main Body area start-->
            <div class="row-fluid bodybottom innerpage">
                <div class="container">
                
                    <div class="span9 innerContect">
						<?php while ( have_posts() ) : the_post(); ?>
                        <?php get_template_part( 'content', 'page' ); ?>
                        <?php //comments_template( '', true ); ?>
                        <?php endwhile; // end of the loop. ?>
                    </div>
                    
                    
                    <div class="span3 innerSideber">
                    <!--banner_form start-->
                        <div class="banner_form">
                            <div class="banner_form_top"></div>
                            
                            <div class="banner_form_mid">
                            	<div class="formText">
                            		<?php dynamic_sidebar( 'sidebar-13' ); ?>
                                </div>
                            	<?php dynamic_sidebar( 'sidebar-5' ); ?>
                            </div>
                            
                            <div class="banner_form_down"></div>
                        </div>
                        
                        <br clear="all" />
                        <!--banner_form end-->
                    
                    
                    	<?php get_sidebar(); ?>
                    </div>
                    
                </div>
            </div>
            <!--Main Body area end-->
    

<?php get_footer(); ?>