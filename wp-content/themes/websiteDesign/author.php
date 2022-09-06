<?php
/**
 * The template for displaying Author Archive pages.
 *
 * Used to display archive-type pages for posts by an author.
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
                            	<?php the_post_thumbnail('full', array('class' => '')); ?>
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

						<?php
                            /* Queue the first post, that way we know
                             * what author we're dealing with (if that is the case).
                             *
                             * We reset this later so we can run the loop
                             * properly with a call to rewind_posts().
                             */
                            the_post();
                        ?>
                        
                        <h2><?php printf( __( 'Author Archives: %s', 'twentytwelve' ), '<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( "ID" ) ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' ); ?></h2>
                        
                        <?php
				/* Since we called the_post() above, we need to
				 * rewind the loop back to the beginning that way
				 * we can run the loop properly, in full.
				 */
				rewind_posts();
			?>

			<?php twentytwelve_content_nav( 'nav-above' ); ?>

			<?php
			// If a user has filled out their description, show a bio on their entries.
			if ( get_the_author_meta( 'description' ) ) : ?>
			<div class="author-info">
				<div class="author-avatar">
					<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'twentytwelve_author_bio_avatar_size', 60 ) ); ?>
				</div><!-- .author-avatar -->
				<div class="author-description">
					<h2><?php printf( __( 'About %s', 'twentytwelve' ), get_the_author() ); ?></h2>
					<p><?php the_author_meta( 'description' ); ?></p>
				</div><!-- .author-description	-->
			</div><!-- .author-info -->
			<?php endif; ?>

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', get_post_format() ); ?>
			<?php endwhile; ?>

			<?php twentytwelve_content_nav( 'nav-below' ); ?>

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

<?php //get_sidebar(); ?>
<?php get_footer(); ?>