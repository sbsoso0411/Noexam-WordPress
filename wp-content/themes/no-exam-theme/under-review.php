<?php
/**
 * Template Name: under-review
 * Description: A Page Template that displays at full width
 *
 * @package Hellish Simplicity
 * @since Hellish Simplicity 1.4
 */
$_POST['page'] = 8;
// Header
include("header2.php"); 
// Helpers
require("inc/helpers.php");
// Storage
require("inc/storage.php");
require("data/questions.php");

// save
$leadtype = 137;
$sessionId = session_id();
SendToVamDB($sessionId,$leadtype,$Questions['sagicor']);
$path = getcwd()."/wp-content/themes/no-exam-theme/storage";
DeleteStorageFile($path,$sessionId);
DestroySessionData();
?>


<div id="form-area">
	<div class="container">
    	<div class="form-container row">
        	<div class="sixteen columns">
            	<div>
	
<?php

if ( have_posts() ) {

	// Start of the Loop
	while ( have_posts() ) {
		the_post();
		?>

		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="entry-header">
				<h1 class="entry-title" style="text-align:center; margin:100px 0 0;"><?php the_title(); ?></h1>
			</header><!-- .entry-header -->
		
			<div class="entry-content"><?php

				the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'hellish' ) );
				wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'hellish' ), 'after' => '</div>' ) );
				?>
                
                <?
?>
<html>
	<body>
		<p style="text-align:center; margin-bottom:100px; float:left; width:100%;">You are not eligible for this product. An agent will contact you shortly to discuss other options.</p>
	</body>
</html>
                
			</div><!-- .entry-content --><?php

			// Comments info.
			if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) { ?>
			<span class="sep"> | </span>
			<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'hellish' ), __( '1 Comment', 'hellish' ), __( '% Comments', 'hellish' ) ); ?></span><?php
			}

			// Edit link
			//edit_post_link( __( 'Edit', 'hellish' ), '<span class="sep"> | </span><span class="edit-link">', '</span>' );
			?>

		</div><!-- #post-<?php the_ID(); ?> --><?php

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

	
	</div>
            </div>
        	
        </div>
        <div class="form-footer">
       <img src="<?php bloginfo('template_directory'); ?>/images/security-logo.png" alt="" border="0" usemap="#Bottom">
         <map name="Bottom">
            <area shape="rect" coords="0,3,112,58" href="https://www.comodo.com/" target="_blank">
            <area shape="rect" coords="121,4,272,59" href="http://www.bbb.org/atlanta/business-reviews/insurance-life/no-exam-life-insurance-in-marietta-ga-27496155" target="_blank">
            <area shape="rect" coords="286,4,391,61" href="http://secure.trust-guard.com/privacy/8683" target="_blank">
            <area shape="rect" coords="401,4,527,60" href="http://www.shopperapproved.com/reviews/noexam.com/" target="_blank">
          </map>
        </div>
    </div>
</div><!-- #content-area -->

<div id="footer" class="footer-wrapper">
        <div class="container"><p class="footer-text">&copy; NoExam.com 2014, All Rights Reserved.</p></div>
    </div>
<a title="Web Analytics" href="http://clicky.com/100764367"><img alt="Web Analytics" src="//static.getclicky.com/media/links/badge.gif" border="0" /></a>
<script src="//static.getclicky.com/js" type="text/javascript"></script>
<script type="text/javascript">try{ clicky.init(100764367); }catch(e){}</script>
<noscript><p><img alt="Clicky" width="1" height="1" src="//in.getclicky.com/100764367ns.gif" /></p></noscript>
<?php //get_footer(); ?>