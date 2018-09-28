<?php
/**
 * Displays content for video page
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

// Send $type to templates.
set_query_var('type', 'video');

// Send $taxonomyCategory to templates.
set_query_var('taxonomyCategory', 'video-category');

// Send $taxonomySource to templates.
set_query_var('taxonomySource', 'video-source');

// Get the post by slug.
// https://stackoverflow.com/questions/14979837/wordpress-query-single-post-by-slug
$slug = 'videos';
$args = array(
  'name' => $slug,
  'post_type' => 'page',
  'post_status' => 'publish',
  'numberposts' => 1
);
$parent = get_posts($args);

// Send $type to templates.
set_query_var('parent', $parent);

// Get YouTube url & ID.
$youtubeUrl = get_field('source', $post->ID);
$youTubeID = get_youtube_id($youtubeUrl);
?>

<!-- row heading -->
<div class="row row-heading article" data-aos="fade-in">
    <div class="grid-container">
        <div class="grid-x grid-padding-x align-bottom">

            <div class="large-6 large-offset-2 cell-for-print cell">
                <span class="article-parent"><?php echo translateTitle($parent[0], $lang); ?></span>

                <h2 class="heading large article-title"><?php echo translateTitle($post, $lang); ?></h2>

                <div class="container-meta for-article">
                    <div class="grid-x grid-padding-x align-middle">

                        <?php
                        // Include the latest.
                        get_template_part('template-parts/page/single', 'attributes');
                        ?>

                    </div>

                </div>

                <!-- Go to www.addthis.com/dashboard to customize your tools -->
                <div class="addthis_inline_share_toolbox float-right-for-medium container-addthis show-for-small-only hide-for-print"></div>

            </div>

        </div>
    </div>
</div>
<!-- row heading -->

<!-- row article -->
<div class="row row-article">
    <div class="grid-container">
        <div class="grid-x grid-padding-x align-middle">

            <!-- cell -->
            <div class="large-6 large-offset-2 cell-for-print cell">

                <div class="responsive-embed widescreen collapse-margin-bottom">
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo $youTubeID; ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                </div>

                <div class="container-text">
                    <?php echo translateContent($post, $lang); ?>
                </div>

                <div class="container-meta for-article">
                    <div class="grid-x grid-padding-x align-middle">

                        <?php
                        // Include the latest.
                        get_template_part('template-parts/page/single', 'attributes');
                        ?>

                    </div>

                </div>

                <div class="addthis_inline_share_toolbox float-right-for-medium container-addthis show-for-small-only hide-for-print"></div>

                <div class="container-list">
                    <?php
                    // Include the latest.
                    get_template_part('template-parts/page/single', 'categories');
                    ?>
                </div>

            </div>
            <!-- cell -->


            <!-- cell -->
            <div class="large-2 cell hide-for-medium-only hide-for-small-only hide-for-print">

                <!-- publications -->
                <div class="container-publications hide-for-small-only">

                    <?php
                    // Send $headingLatest to templates.
                    set_query_var('headingLatest', 'latest_videos');

                    // Include the latest.
                    get_template_part('template-parts/page/single', 'latest-items');
                    ?>

                </div>
                <!-- publications -->

            </div>
            <!-- cell -->



        </div>
    </div>
</div>
<!-- row article -->

<div class="row row-publications article show-for-small-only hide-for-print" data-aos="fade-up">
    <div class="grid-container">
        <div class="grid-x grid-padding-x align-bottom">

            <div class="small-12 cell">

                <!-- publications -->
                <div class="container-publications">

                    <?php
                    // Include the latest.
                    get_template_part('template-parts/page/single', 'latest-items');
                    ?>

                </div>
                <!-- publications -->

            </div>

        </div>
    </div>
</div>

<?php
// Include the latest.
get_template_part('template-parts/page/recent', 'updates');
?>
