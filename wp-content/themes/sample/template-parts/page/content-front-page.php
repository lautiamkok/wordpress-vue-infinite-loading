<?php
/**
 * Displays content for front page
 *
 * @package WordPress
 * @subpackage Andrew
 * @since 1.0
 * @version 1.0
 */
?>

<?php
// Get requested language.
$lang = get_query_var('lang');
?>

<?php
// Include the latest.
get_template_part('template-parts/page/home', 'featured-item');
?>

<?php
// Include the latest.
get_template_part('template-parts/page/recent', 'updates');
?>

<!-- row twin -->
<div class="row row-twin">

    <!-- grid container -->
    <div class="grid-container">

        <!-- grid -->
        <div class="grid-x grid-padding-x align-stretch">

            <!-- cell -->
            <div class="large-7 cell">

                <!-- grid -->
                <div class="grid-x grid-padding-x">

                    <!-- cell video -->
                    <div class="small-12 cell cell-video" data-aos="fade-up">

                        <?php
                        // Include the latest.
                        get_template_part('template-parts/page/home', 'latest-video');
                        ?>

                    </div>
                    <!-- cell video -->

                    <!-- cell book -->
                    <div class="small-12 cell cell-book" data-aos="fade-up">

                        <?php
                        // Include the latest.
                        get_template_part('template-parts/page/home', 'latest-book');
                        ?>

                    </div>
                    <!-- cell book -->



                </div>
                <!-- grid -->

            </div>
            <!-- cell -->

            <!-- cell -->
            <div class="large-5 cell cell-about" data-aos="fade-up">

                <?php
                // Include the latest.
                get_template_part('template-parts/page/home', 'speaking-enquiries');
                ?>

                <!-- about -->
                <div class="container-about hide-for-small-only bar-bottom">

                    <?php
                    // Include the latest.
                    get_template_part('template-parts/page/home', 'about-andrew');
                    ?>

                </div>
                <!-- about -->

                <!-- publications -->
                <div class="container-publications hide-for-small-only">

                    <?php
                    // Send $type to templates.
                    set_query_var('type', 'publication');

                    // Send $taxonomy to templates.
                    set_query_var('taxonomy', 'publication-category');

                    // Send $taxonomySource to templates.
                    set_query_var('taxonomySource', 'publication-source');

                    // Get the post by slug.
                    // https://stackoverflow.com/questions/14979837/wordpress-query-single-post-by-slug
                    $slug = 'publications';
                    $args = array(
                      'name' => $slug,
                      'post_type' => 'page',
                      'post_status' => 'publish',
                      'numberposts' => 1
                    );
                    $parent = get_posts($args);

                    // Send $type to templates.
                    set_query_var('parent', $parent);

                    // Send $headingLatest to templates.
                    set_query_var('headingLatest', 'latest_publications');

                    // Include the latest.
                    get_template_part('template-parts/page/single', 'latest-items');
                    ?>

                </div>
                <!-- publications -->

            </div>
            <!-- cell -->

        </div>
        <!-- grid -->

    </div>
    <!-- grid container -->

</div>
<!-- row twin -->
