<?php
/**
 * Displays content for publications taxonomy page
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
?>

<?php
// Get current tax.
// https://stackoverflow.com/questions/12289169/how-do-i-get-current-taxonomy-term-id-on-wordpress
$tax = $wp_query->get_queried_object();
?>

<div id="api-posts" class="row row-list" data-filter="category">

    <?php
    // JSON API endpoint.
    $endpoint = site_url() . '/wp-json/publications/v1/lang/' . $langForApi . '/keywords/';

    // Send $endpoint to templates.
    set_query_var('endpoint', $endpoint);
    ?>
    <?php
    // Include the filter nav.
    get_template_part('template-parts/page/search', 'local-for-small-only');
    ?>

    <!-- row heading -->
    <div class="row row-heading with-nav" data-aos="fade-in">
        <div class="grid-container">
            <div class="grid-x grid-padding-x align-bottom">

                <div class="large-12 cell">

                    <div class="container-heading">

                        <div class="grid-x grid-padding-x align-middle">

                            <div class="medium-12 small-10 cell hide-for-search">
                                <h2 class="heading large inline">Publications</h2>
                                <h3 class="heading inline small-for-small block-for-small-only"><i class="material-icons large hide-for-small-only">keyboard_arrow_right</i><?php echo translateText($tax->name, $lang); ?></h3>
                            </div>

                            <!-- cell -->
                            <div class="small-2 cell expand-for-search show-for-small-only">

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

            </div>
        </div>
    </div>
    <!-- row heading -->

    <!-- row list -->
    <div class="row row-list">
        <div class="grid-container">
            <div class="grid-x grid-padding-x align-stretch">

                <!-- cell -->
                <div class="large-9 medium-6 cell">

                    <!-- vue template -->
                    <?php
                    // JSON API endpoint.
                    $endpoint = site_url() . '/wp-json/publications/v1/lang/' . $langForApi . '/category/';
                    ?>
                    <a href="#" id="button-load-catposts" class="button button-load hide" data-posts-endpoint="<?php echo $endpoint; ?>" data-cat="<?php echo $tax->slug; ?>" v-on:click.prevent="fetchCatPosts"><i class="material-icons">add</i><span>Load More</span></a>

                    <?php get_template_part( 'template-parts/page/vue', 'publications' ); ?>
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
                                        $endpoint = site_url() . '/wp-json/publications/v1/lang/' . $langForApi . '/keywords/';

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
