<?php
/**
 * Displays content for search (global) page
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

// Get search keywords.
$keywords = cleanUpKeywords(get_search_query());

// Get total number search results.
// global $wp_query;
// echo $wp_query->found_posts.' results found.';
?>

<div id="api-posts" class="row row-list" data-filter="">

    <?php
    // // JSON API endpoint.
    // $endpoint = site_url() . '/wp-json/search/v1/keywords/';

    // // Send $endpoint to templates.
    // set_query_var('endpoint', $endpoint);
    ?>
    <?php
    // // Include the filter nav.
    // get_template_part('template-parts/page/search', 'local-for-small-only');
    ?>

    <!-- row heading -->
    <div class="row row-heading with-nav" data-aos="fade-in">
        <div class="grid-container">
            <div class="grid-x grid-padding-x align-bottom">

                <div class="large-12 cell">

                    <div class="container-heading">

                        <div class="grid-x grid-padding-x align-middle">

                            <div class="small-12 cell">
                                <h2 class="heading large inline medium-for-small"><?php printf(__('Search Results for: %s', 'andrew'), get_search_query()); ?></h2>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="large-12 cell show-for-small-only">
                   <nav class="nav-nav3">
                        <ul class="menu align-left menu-main dropdown" data-dropdown-menu>
                            <li><a href="#"><?php echo translateText(get_field('sort_by_type', 'option'), $lang); ?> <i class="material-icons mi-arrow-down">keyboard_arrow_down</i></a>
                                <ul class="nested vertical menu menu-sub children">
                                    <?php
                                    // Include the filter nav.
                                    get_template_part( 'template-parts/page/filter', 'types' );
                                    ?>
                                </ul>
                            </li>

                            <li><a href="#"><?php echo translateText(get_field('sort_by_year', 'option'), $lang); ?> <i class="material-icons mi-arrow-down">keyboard_arrow_down</i></a>
                                <ul class="nested vertical menu menu-sub children">
                                    <?php
                                    $type = array(
                                        'publication',
                                        'video',
                                        'event',
                                        'speech',
                                        'book'
                                    );
                                    // Send $type to templates.
                                    set_query_var('type', $type);
                                    ?>
                                    <?php
                                    // Include the filter nav.
                                    get_template_part( 'template-parts/page/filter', 'years' );
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

    <!-- row card -->
    <div class="row row-result">
        <div class="grid-container">
            <div class="grid-x grid-padding-x align-stretch">

                <!-- cell -->
                <div class="large-9 medium-6 cell">

                    <?php
                    // JSON API endpoint.
                    $endpoint = site_url() . '/wp-json/search/v1/lang/' . $langForApi . '/keywords/' . $keywords .'/page/';
                    ?>
                    <a href="#" id="button-load-posts" class="button button-load hide" data-posts-endpoint="<?php echo $endpoint; ?>" v-on:click.prevent="fetchPosts"><i class="material-icons">add</i><span>Load More</span></a>

                    <?php
                    // JSON API endpoint.
                    $endpoint = site_url() . '/wp-json/search/v1/lang/' . $langForApi . '/keywords/' . $keywords .'/type/';
                    ?>
                    <a href="#" id="button-load-catposts" class="button button-load hide" data-posts-endpoint="<?php echo $endpoint; ?>" data-cat="" v-on:click.prevent="fetchCatPosts"><i class="material-icons">add</i><span>Load More</span></a>

                    <?php
                    // JSON API endpoint.
                    $endpoint = site_url() . '/wp-json/search/v1/lang/' . $langForApi . '/keywords/' . $keywords .'/year/';
                    ?>
                    <a href="#" id="button-load-yearposts" class="button button-load hide" data-posts-endpoint="<?php echo $endpoint; ?>" data-year="" v-on:click.prevent="fetchYearPosts"><i class="material-icons">add</i><span>Load More</span></a>

                    <p class="result-counts">{{ total }} RESULTS  <a href="#" id="button-load-posts" class="button-load button-reset uppercase" data-posts-endpoint="<?php echo $endpoint; ?>" v-on:click.prevent="resetSearch" v-if="reset === true"><span>Show All</span></a></p>

                    <template v-if="items">

                        <!-- vue - loop -->
                        <!-- https://laracasts.com/discuss/channels/vue/use-v-for-without-wrapping-element -->
                        <template v-for="item in items">


                            <template v-if="item.type ==='publication'">
                                <?php get_template_part('template-parts/page/vue', 'search-result-publication'); ?>
                            </template>

                            <template v-if="item.type ==='speech'">
                                <?php get_template_part('template-parts/page/vue', 'search-result-speech'); ?>
                            </template>

                            <template v-if="item.type ==='video'">
                                <?php get_template_part('template-parts/page/vue', 'search-result-video'); ?>
                            </template>

                            <template v-if="item.type ==='event'">
                                <?php get_template_part('template-parts/page/vue', 'search-result-event'); ?>
                            </template>

                            <template v-if="item.type ==='book'">
                                <?php get_template_part('template-parts/page/vue', 'search-result-book'); ?>
                            </template>

                        </template>
                        <!-- vue - loop -->

                        <div class="container-spinner">
                            <div v-if="loading === true" class="sk-circle">
                              <div class="sk-circle1 sk-child"></div>
                              <div class="sk-circle2 sk-child"></div>
                              <div class="sk-circle3 sk-child"></div>
                              <div class="sk-circle4 sk-child"></div>
                              <div class="sk-circle5 sk-child"></div>
                              <div class="sk-circle6 sk-child"></div>
                              <div class="sk-circle7 sk-child"></div>
                              <div class="sk-circle8 sk-child"></div>
                              <div class="sk-circle9 sk-child"></div>
                              <div class="sk-circle10 sk-child"></div>
                              <div class="sk-circle11 sk-child"></div>
                              <div class="sk-circle12 sk-child"></div>
                           </div>
                        </div>

                    </template>

                </div>
                <!-- cell -->

                <!-- cell -->
                <div class="large-3 medium-6 cell hide-for-small-only">

                    <nav class="nav-side">

                        <!-- menu -->
                        <ul class="vertical menu accordion-menu menu-side" data-accordion-menu>
                            <!-- <li><a href="#">SEARCH BY KEYWORD</a>
                                <ul class="menu vertical nested menu-sub">
                                    <form class="form-search">
                                      <div class="grid-container full-height">
                                        <div class="grid-x grid-padding-x align-middle small-padding-collapse">

                                            <div class="flex-item container-input position-relative active">
                                                <input type="text" placeholder="Search">
                                            </div>

                                            <div class="flex-item">
                                                <a href="#" class="button-search float-right flip flex-search"><i class="fi-magnifying-glass"></i></a>
                                            </div>

                                        </div>
                                      </div>
                                    </form>
                                </ul>
                            </li> -->
                            <li><a href="#"><?php echo translateText(get_field('sort_by_type', 'option'), $lang); ?></a>
                                <ul class="menu vertical nested menu-sub">
                                    <?php
                                    // Include the filter nav.
                                    get_template_part( 'template-parts/page/filter', 'types' );
                                    ?>
                                </ul>
                            </li>

                            <li><a href="#"><?php echo translateText(get_field('sort_by_year', 'option'), $lang); ?></a>
                                <ul class="menu vertical nested menu-sub">
                                    <?php
                                    $type = array(
                                        'publication',
                                        'video',
                                        'event',
                                        'speech',
                                        'book'
                                    );
                                    // Send $type to templates.
                                    set_query_var('type', $type);
                                    ?>
                                    <?php
                                    // Include the filter nav.
                                    get_template_part( 'template-parts/page/filter', 'years' );
                                    ?>
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
    <!-- row card -->

</div>
