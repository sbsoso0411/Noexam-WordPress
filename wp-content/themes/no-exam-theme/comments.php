<?php
/**
 * Template for displaying Comments.
 *
 * @package Hellish Simplicity
 * @since Hellish Simplicity 1.1
 */

 
/*
 * Show pre comments navigation
 */
function hellish_comments_navigation( $id = '' ) {
	if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) {
		?>
	<nav role="navigation" id="<?php echo $id; ?>" class="site-navigation comment-navigation">
		<h1 class="assistive-text"><?php _e( 'Comment navigation', 'hellish' ); ?></h1>
		<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'hellish' ) ); ?></div>
		<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'hellish' ) ); ?></div>
	</nav><!-- #comment-nav-<?php echo $id; ?> .site-navigation .comment-navigation --><?php
	}
}


/*
 * Bail out now if the user needs to enter a password
 */
if ( post_password_required() )
	return;
?>

<div id="comments" class="comments-area">

<?php

/*
 * Display the comments if any exist
 */
if ( have_comments() ) { ?>
	<h3 class="comments-title"><?php
		printf(
			_nx(
				'One thought on &ldquo;%2$s&rdquo;',
				'%1$s thoughts on &ldquo;%2$s&rdquo;',
				get_comments_number(),
				'comments title',
				'hellish'
			),
			number_format_i18n( get_comments_number() ),
			'<span>' . get_the_title() . '</span>'
		);
	?></h3><?php

	hellish_comments_navigation( 'comment-nav-above' );
	?>

	<ol class="commentlist"><?php wp_list_comments(); ?></ol><!-- .commentlist -->

	<?php
	hellish_comments_navigation( 'comment-nav-below' );

}

/*
 * If comments are closed, then leave a notice
 */
if (
	! comments_open() &&
	'0' != get_comments_number() &&
	post_type_supports( get_post_type(), 'comments' )
) {
	echo '<p class="nocomments">' . __( 'Comments are closed.', 'hellish' ) . '</p>';
}

/*
 * Display the main comment form
 */
comment_form();

?>

</div><!-- #comments .comments-area -->
