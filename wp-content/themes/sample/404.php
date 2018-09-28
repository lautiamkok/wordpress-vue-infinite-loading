<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package WordPress
 * @subpackage Sample
 * @since 1.0
 * @version 1.0
 */

get_header(); ?>

<main>

    <!-- row article -->
    <div class="row row-article" style="background-color: #f9f9f9; padding-top: 40px; padding-bottom: 40px;">

        <div class="grid-container">
            <div class="grid-x grid-padding-x">
                <div class="medium-12 cell cell-banner">

                    <div class="article-item text-center">
                        <h1 class="title-article">Oops! That page can't be found.</h1>

                        <h2 class="title-article">Sorry!</h2>
                        <p>It looks like nothing was found at this location.</p>
                        <p>Maybe try sending us a message or giving us a call?</p>
                    </div>

                </div>
            </div>
        </div>

    </div>
    <!-- row article -->

</main>

<?php get_footer();
