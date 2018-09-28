<?php
/**
 * The taxonomy template file
 *
 * https://codex.wordpress.org/Category_Templates
 * http://www.wpbeginner.com/wp-themes/how-to-create-category-templates-in-wordpress/

 * @package WordPress
 * @subpackage Sample
 * @since 1.0
 * @version 1.0
 */

get_header(); ?>

<main role="main">

    <?php // Show the selected frontpage content.
    if ( have_posts() ) :
        get_template_part( 'template-parts/page/content', 'videos-category-page' );
    else : // I'm not sure it's possible to have no posts when this page is shown, but WTH.
        get_template_part( 'template-parts/post/content', 'none' );
    endif; ?>

</main>

<?php get_footer();
