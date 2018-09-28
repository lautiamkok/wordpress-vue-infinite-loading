<?php
/**
 * The template for displaying single page
 *
 * https://developer.wordpress.org/themes/template-files-section/custom-post-type-template-files/
 * https://codex.wordpress.org/Post_Type_Templates
 *
 * @package WordPress
 * @subpackage Sample
 * @since 1.0
 * @version 1.0
 */
get_header(); ?>

<main role="main">

	<?php // Show the selected frontpage content.
	if ( have_posts() ) :
		while ( have_posts() ) : the_post();
			get_template_part( 'template-parts/page/content', 'video-page' );
		endwhile;
	else : // I'm not sure it's possible to have no posts when this page is shown, but WTH.
		get_template_part( 'template-parts/post/content', 'none' );
	endif; ?>

</main>

<?php get_footer();
