<?php
/**
 * Displays content for events page
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
$langForApi = $lang ? $lang : 'en';

// Send $type to templates.
set_query_var('type', 'event');
?>

<div id="api-posts" class="row row-list" data-filter="">

    <?php
    // Include the filter nav.
    get_template_part('template-parts/page/search', 'local-for-small-only');
    ?>

    <!-- row heading -->
    <div class="row row-heading" data-aos="fade-in">
        <div class="grid-container">
            <div class="grid-x grid-padding-x align-bottom">

                <div class="large-12 cell">
                   <h2 class="heading large"><?php echo translateTitle($post, $lang); ?></h2>
                </div>

            </div>
        </div>
    </div>
    <!-- row heading -->

    <!-- vue template -->
    <div data-posts-endpoint="<?php echo $endpoint; ?>">
        <?php
        // JSON API endpoint.
        $endpoint = site_url() . '/wp-json/events/v1/lang/' . $langForApi . '/parent/' . $post->ID .'/page/';
        ?>
        <a href="#" id="button-load-posts" class="button button-load hide" data-posts-endpoint="<?php echo $endpoint; ?>" v-on:click.prevent="fetchPosts"><i class="material-icons">add</i><span>Load More</span></a>

        <?php get_template_part( 'template-parts/page/vue', 'events' ); ?>
    </div>
    <!-- vue template -->

</div>
