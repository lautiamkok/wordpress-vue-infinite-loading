<?php
/**
 * The template for displaying search results pages.
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
        get_template_part( 'template-parts/page/content', 'search-page' );
    else :
       get_template_part( 'template-parts/page/content', 'search-page' );
    endif;
    ?>

</main>

<?php get_footer(); ?>
