<?php
/**
 * Displays content for videos page
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
set_query_var('type', 'video');
set_query_var('taxonomy_category', 'video-category');
?>

<div id="api-posts" class="row row-list" data-filter="">

    <?php
    // JSON API endpoint.
    $endpoint = site_url() . '/wp-json/videos/v1/keywords/';

    // Send $endpoint to templates.
    set_query_var('endpoint', $endpoint);
    ?>
    <?php
    // Include the filter nav.
    get_template_part('template-parts/page/search', 'local-for-small-only');
    ?>

    <!-- row heading -->
    <div class="row row-heading with-nav" data-aos="fade-in" style="position:relative;z-index:1">
        <div class="grid-container">
            <div class="grid-x grid-padding-x align-bottom">

                <div class="large-12 cell">

                    <div class="container-heading">

                        <div class="grid-x grid-padding-x align-middle">

                            <div class="small-8 cell hide-for-search">
                                <h2 class="heading large"><?php echo translateTitle($post, $lang); ?></h2>
                            </div>

                            <!-- cell -->
                            <div class="small-4 cell expand-for-search show-for-small-only">

                                <form class="form-search">
                                  <div class="grid-container full-height">
                                    <div class="grid-x grid-padding-x align-middle small-padding-collapse">

                                        <div class="flex-item">
                                            <a href="#" class="button-search button-show-local-search float-right flip flex-search local-search"><i class="fi-magnifying-glass"></i></a>
                                        </div>

                                        <div class="flex-item container-input position-relative">
                                            <input type="text" placeholder="Search" class="hide">
                                        </div>

                                    </div>
                                  </div>
                                </form>

                            </div>
                            <!-- cell -->

                        </div>

                    </div>

                </div>

                <div class="large-12 cell show-for-small-only">
                   <nav class="nav-nav3">
                        <ul class="menu align-left menu-main dropdown" data-dropdown-menu>
                            <li><a href="#">ALL TOPICS <i class="material-icons mi-arrow-down">keyboard_arrow_down</i></a>
                                <ul class="nested vertical menu menu-sub children">
                                    <?php
                                    // Include the filter nav.
                                    get_template_part('template-parts/page/filter', 'topics');
                                    ?>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div>

            </div>
        </div>
    </div>
    <!-- row heading -->

    <!-- row list -->
    <div class="row row-card">
        <div class="grid-container">
            <div class="grid-x grid-padding-x align-stretch">

                <!-- cell -->
                <div class="large-9 medium-6 cell">

                    <!-- vue template -->
                    <div data-posts-endpoint="<?php echo $endpoint; ?>">
                        <?php
                        // JSON API endpoint.
                        $endpoint = site_url() . '/wp-json/videos/v1/lang/' . $langForApi . '/parent/' . $post->ID .'/page/';
                        ?>
                        <a href="#" id="button-load-posts" class="button button-load hide" data-posts-endpoint="<?php echo $endpoint; ?>" v-on:click.prevent="fetchPosts"><i class="material-icons">add</i><span>Load More</span></a>

                        <?php
                        // JSON API endpoint.
                        $endpoint = site_url() . '/wp-json/videos/v1/lang/' . $langForApi . '/category/';
                        ?>
                        <a href="#" id="button-load-catposts" class="button button-load hide" data-posts-endpoint="<?php echo $endpoint; ?>" data-cat="" v-on:click.prevent="fetchCatPosts"><i class="material-icons">add</i><span>Load More</span></a>

                        <?php get_template_part( 'template-parts/page/vue', 'videos' ); ?>
                    </div>
                    <!-- vue template -->

                </div>
                <!-- cell -->

                <!-- cell -->
                <div class="large-3 medium-6 cell hide-for-small-only">

                    <nav class="nav-side">

                        <!-- menu -->
                        <ul class="vertical menu accordion-menu menu-side" data-accordion-menu>
                            <li><a href="#"><?php echo translateText(get_field('search_by_keyword', 'option'), $lang); ?></a>
                                <ul class="menu vertical nested menu-sub">
                                    <div class="container-search">
                                        <?php
                                        // JSON API endpoint.
                                        $endpoint = site_url() . '/wp-json/videos/v1/lang/' . $langForApi . '/keywords/';

                                        // Send $endpoint to templates.
                                        set_query_var('endpoint', $endpoint);
                                        ?>
                                        <?php
                                        // Include the filter nav.
                                        get_template_part( 'template-parts/page/filter', 'search' );
                                        ?>
                                    </div>
                                </ul>
                            </li>

                            <li><a href="#"><?php echo translateText(get_field('browse_by_topic', 'option'), $lang); ?></a>
                                <ul class="menu vertical nested menu-sub">
                                    <?php
                                    // Include the filter nav.
                                    get_template_part( 'template-parts/page/filter', 'topics' );
                                    ?>
                                    <!-- <li><a href="#" class="child">Sample 1</a></li>
                                    <li><a href="#" class="child">Sample 2</a></li>
                                    <li><a href="#" class="child">Sample 3</a></li>
                                    <li><a href="#" class="child">Sample 4</a></li>
                                    <li><a href="#" class="child">Sample 5</a></li>
                                    <li><a href="#" class="child">Sample 6</a></li> -->
                                </ul>
                            </li>
                        </ul>
                        <!-- menu -->

                    </nav>


                </div>
                <!-- cell -->

            </div>
        </div>
    </div>
    <!-- row list -->

</div>
