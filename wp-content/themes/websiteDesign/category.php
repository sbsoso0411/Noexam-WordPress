<?php
/**
 * The template for displaying Category pages.
 *
 * Used to display archive-type pages for posts in a category.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>

	<!--outer freePro start-->
            <div class="row-fluid freePro">
                <div class="container freePro-part">
                    <div class="span4 innerToptext">
                        <?php dynamic_sidebar( 'sidebar-13' ); ?>
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
                        <div class="span8 bannerCon innerbanner">
                            <div class="bannerPic">
                            	<?php
								if ( has_post_thumbnail()) {
								   $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full');
								   echo get_the_post_thumbnail($post->ID, 'full'); 
								 }
								 
								 else {
										echo get_the_post_thumbnail('31', 'full'); 
									}
									?>
                            </div>
                        </div>
                        <!--banner_contect end-->
                        
                        <br clear="all" />
                        
                    </div>
                </div>
            </div>
            <!--outer banner end-->
            
            
            <!--Main Body area start-->
            <div class="row-fluid bodybottom innerpage">
                <div class="container">
                
                    <div class="span9 innerContect">
						<?php if ( have_posts() ) : ?>
                        <h2><?php printf( __( 'Category Archives: %s', 'twentytwelve' ), '<span>' . single_cat_title( '', false ) . '</span>' ); ?></h2>
                        <?php if ( category_description() ) : // Show an optional category description ?>
                            <div class="archive-meta"><?php echo category_description(); ?></div>
                        <?php endif; ?>
                        
                        <?php
							/* Start the Loop */
							while ( have_posts() ) : the_post();
				
								/* Include the post format-specific template for the content. If you want to
								 * this in a child theme then include a file called called content-___.php
								 * (where ___ is the post format) and that will be used instead.
								 */
								get_template_part( 'content', get_post_format() );
				
							endwhile;
				
							twentytwelve_content_nav( 'nav-below' );
							?>
				
						<?php else : ?>
							<?php get_template_part( 'content', 'none' ); ?>
						<?php endif; ?>
                        
                    </div>
                    
                    <div class="span3 innerSideber">
                    	<?php get_sidebar(); ?>
                    </div>
                    
                </div>
            </div>
            <!--Main Body area end-->
            

	<!-- #primary -->

<?php //get_sidebar(); ?>
<?php get_footer(); ?>