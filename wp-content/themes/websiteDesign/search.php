<?php
/**
 * The template for displaying Search Results pages.
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
                            	<?php dynamic_sidebar( 'sidebar-14' ); ?>
                            </div>
                        </div>
                        <!--banner_contect end-->
                        
                        <br clear="all" />
                        
                    </div>
                </div>
            </div>
            <!--outer banner end-->
            
            <!--Main Body area start-->
            <style>
            	.entry-header img.wp-post-image{
					display:none;
				}
            </style>
            
            <div class="row-fluid bodybottom innerpage">
                <div class="container">
                
                    <div class="span9 innerContect" >
						<?php if ( have_posts() ) : ?>
                        <h2><?php printf( __( 'Search Results for: %s', 'twentytwelve' ), '<span>' . get_search_query() . '</span>' ); ?></h2>
                        
                        <?php /* Start the Loop */ ?>
						<?php while ( have_posts() ) : the_post(); ?>
                            <?php get_template_part( 'content', get_post_format() ); ?>
                        <?php endwhile; ?>
            
                        <?php twentytwelve_content_nav( 'nav-below' ); ?>
                        <?php else : ?>
                        
                        <h2><?php _e( 'Nothing Found', 'twentytwelve' ); ?></h2>
                        <p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'twentytwelve' ); ?></p>
						<?php get_search_form(); ?>
                        
                        <?php endif; ?>
                            
                    </div>
                    
                    <div class="span3 innerSideber">
                    	<?php get_sidebar(); ?>
                    </div>
                    
                </div>
            </div>
            <!--Main Body area end-->
            
	

<?php //get_sidebar(); ?>
<?php get_footer(); ?>