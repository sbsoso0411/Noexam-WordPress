<?php
/**
 * The Template for displaying all single posts.
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
                        <?php get_template_part( 'content', get_post_format() ); ?>
                        <?php comments_template(); ?>
                        <?php endwhile; // end of the loop. ?>
                    </div>
                    
                    
                    <div class="span3 innerSideber">
<div class="widget-area">

                            	<?php dynamic_sidebar( 'blogside' ); ?>
			</div>
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
    



<?php //get_sidebar(); ?>
<?php get_footer(); ?>