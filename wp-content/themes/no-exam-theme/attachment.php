<?php
/**
 * The attachment template.
 *
 * @package Hellish Simplicity
 * @since Hellish Simplicity 1.6
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
				<h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'hellish' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
				<div class="entry-meta"><?php
					// Get attachment
					$metadata = wp_get_attachment_metadata();
					printf( ' <span class="attachment-meta full-size-link"><a href="%1$s" title="%2$s">%3$s (%4$s &times; %5$s)</a></span>',
						esc_url( wp_get_attachment_url() ),
						esc_attr__( 'Link to full-size image', 'hellish' ),
						__( 'Full resolution', 'hellish' ),
						absint( $metadata['width'] ),
						absint( $metadata['height'] )
					);

					// Edit link
					edit_post_link( __( 'Edit', 'hellish' ), '<span class="sep"> | </span><span class="edit-link">', '</span>' );
					?>
				</div><!-- .entry-meta -->
			</header><!-- .entry-header -->

			<div class="entry-attachment">
				<div class="attachment"><?php
					$post = get_post();
					printf( '<a href="%1$s" title="%2$s" rel="attachment">%3$s</a>',
						wp_get_attachment_url(),
						the_title_attribute( array( 'echo' => false ) ),
						wp_get_attachment_image( $post->ID, 'attachment-page' )
					);
					?>

				</div><!-- .attachment -->
			</div><!-- .entry-attachment -->

			<?php if ( has_excerpt() ) : ?>
			<div class="entry-caption">
				<?php the_excerpt(); ?>
			</div>
			<?php endif; ?>

			<?php if ( ! empty( $post->post_content ) ) : ?>
			<div class="entry-description">
				<?php the_content(); ?>
				<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'twentythirteen' ), 'after' => '</div>' ) ); ?>
			</div><!-- .entry-description -->
			<?php endif; ?>

		</article><!-- #post-<?php the_ID(); ?> --><?php

		// If comments are open or we have at least one comment, load up the comment template
		if ( comments_open() || '0' != get_comments_number() )
			comments_template( '', true );

	}

}
?>

	</div><!-- #site-content -->
</div><!-- #content-area -->

<?php get_footer(); ?>