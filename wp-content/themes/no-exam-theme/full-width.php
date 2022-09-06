<?php
/**
 * Template Name: Full width
 * Description: A Page Template that displays at full width
 *
 * @package Hellish Simplicity
 * @since Hellish Simplicity 1.4
 */

get_header(); ?>

<div id="content-area">
	<div id="site-content" role="main"><?php

if ( have_posts() ) {

	// Start of the Loop
	while ( have_posts() ) {
		the_post();
		?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="entry-header">
				<h1 class="entry-title"><?php the_title(); ?></h1>
			</header><!-- .entry-header -->
		
			<div class="entry-content"><?php

				the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'hellish' ) );
				wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'hellish' ), 'after' => '</div>' ) );
				?>
			</div><!-- .entry-content --><?php

			// Comments info.
			if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) { ?>
			<span class="sep"> | </span>
			<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'hellish' ), __( '1 Comment', 'hellish' ), __( '% Comments', 'hellish' ) ); ?></span><?php
			}

			// Edit link
			edit_post_link( __( 'Edit', 'hellish' ), '<span class="sep"> | </span><span class="edit-link">', '</span>' );
			?>

		</article><!-- #post-<?php the_ID(); ?> --><?php

		// If comments are open or we have at least one comment, load up the comment template
		if ( comments_open() || '0' != get_comments_number() )
			comments_template( '', true );

	}

	get_template_part( 'template-parts/numeric-pagination' );

}
else {
	get_template_part( 'template-parts/no-results' );
}
?>

	</div><!-- #site-content -->
</div><!-- #content-area -->

<?php get_footer(); ?>