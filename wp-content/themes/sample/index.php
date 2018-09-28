<?php
/**
 * The main template file
 *
 * Page Templates within the Template Hierarchy
 * index.php — If no specific page templates are assigned or found, WordPress defaults back to using the theme’s index file to render pages.
 *
 * https://developer.wordpress.org/themes/template-files-section/page-template-files/
 *
 * @package WordPress
 * @subpackage Sample
 * @since 1.0
 * @version 1.0
 */

get_header(); ?>

<main role="main">

    <!-- row article -->
    <div class="row row-article" style="background-color: #f9f9f9; padding-top: 40px; padding-bottom: 40px;">

        <div class="grid-container">
            <div class="grid-x grid-padding-x">
                <div class="medium-12 cell cell-banner">

                    <div class="article-item text-center">
                        <h1 class="title-article">Oops! No template to display this page yet.</h1>
                        <p>Please contact your developer.</p>
                    </div>

                </div>
            </div>
        </div>

    </div>
    <!-- row article -->

</main>

<?php get_footer();
