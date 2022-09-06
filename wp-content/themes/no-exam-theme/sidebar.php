<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package Hellish Simplicity
 * @since Hellish Simplicity 1.1
 */
?>
<div id="sidebar" role="complementary">
    
    <?php
                        
	if ( ! dynamic_sidebar( 'sidebar' ) ) { ?>
	<aside>
        
		<h4 class="widget-title">Recent Posts</h4>
		<ul><?php
			$recent_posts = wp_get_recent_posts();
			foreach( $recent_posts as $recent ){
				echo '<li><a href="' . get_permalink($recent['ID'] ) . '" title="Look ' . esc_attr( $recent['post_title'] ) . '" >' . $recent['post_title'] . '</a></li>';
			}
		?></ul>
	</aside>
	<aside>
		<h4 class="widget-title"><?php _e( 'Archives', 'hellish' ); ?></h4>
		<ul>
			<?php wp_get_archives( 'type=monthly' ); ?>
		</ul>
	<aside>
	<aside>
		<h4 class="widget-title"><?php _e( 'Search', 'hellish' ); ?></h4>
		<?php get_search_form(); ?>
	</aside><?php
	}
	?>

</div><!-- #sidebar -->
