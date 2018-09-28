<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Sample
 * @since 1.0
 * @version 1.0
 */

?>

<?php
// Get requested language.
$lang = get_query_var('lang');
?>

<footer id="footer" class="hide-for-print">
    <div class="row row-footer">
        <div class="row row-top hide-for-small-only">
            <div class="grid-container">
                <div class="grid-x grid-padding-x small-padding-collapse">

                    <!-- cell -->
                    <div class="large-3 medium-6 cell">

                        <nav class="nav-bold">
                            <?php
                            // Include the page nav.
                            get_template_part( 'template-parts/nav', 'footer-left' );
                            ?>
                        </nav>

                    </div>
                    <!-- cell -->

                    <!-- cell -->
                    <?php
                    // Include the page nav.
                    get_template_part( 'template-parts/nav', 'footer-middle' );
                    ?>
                    <!-- cell -->

                    <!-- cell -->
                    <div class="large-3 medium-6 cell">

                        <nav class="nav-bold">
                            <?php
                            // Include the page nav.
                            get_template_part( 'template-parts/nav', 'footer-right' );
                            ?>
                        </nav>

                    </div>
                    <!-- cell -->


                </div>
            </div>
        </div>

    </div>

</footer>

<?php wp_footer(); ?>

</body>
</html>
