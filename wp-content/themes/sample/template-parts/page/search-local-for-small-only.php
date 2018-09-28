<?php
/**
 * Displays content for local search on small screens
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

// Fix the search heading on the category templates.
$postType = get_post_type($post);
if ($postType === 'publication') {
    // Get the post by slug.
    // https://stackoverflow.com/questions/14979837/wordpress-query-single-post-by-slug
    $slug = 'publications';
    $args = array(
      'name' => $slug,
      'post_type' => 'page',
      'post_status' => 'publish',
      'numberposts' => 1
    );
    $post = get_posts($args);
    $post = $post[0];
}
if ($postType === 'speech') {
    // Get the post by slug.
    // https://stackoverflow.com/questions/14979837/wordpress-query-single-post-by-slug
    $slug = 'speeches';
    $args = array(
      'name' => $slug,
      'post_type' => 'page',
      'post_status' => 'publish',
      'numberposts' => 1
    );
    $post = get_posts($args);
    $post = $post[0];
}
?>

<!-- local search - small only -->
<div class="row row-search hide-for-medium hide">

    <div class="grid-container full full-height">

        <!-- grid-x -->
        <div class="grid-x small-padding-collapse">

            <div class="small-12 cell cell-search">
                <!-- menu -->
                <div class="container-button">
                    <a href="#" class="button-close button-hide-local-search"><i class="material-icons">close</i></a></li>
                </div>
                <!-- menu -->
            </div>

            <div class="small-12 cell cell-search">
                <h5 class="heading heading-search"><?php echo translateText(get_field('search', 'option'), $lang); ?> <?php echo translateTitle($post, $lang); ?></h5>
            </div>

            <!-- cell/ menu-block-aside -->
            <div class="small-12 cell cell-search">

                <div class="container-search">
                    <form class="form-search" v-on:submit.prevent="searchByKeywords">
                      <div class="grid-container full-height">
                        <div class="grid-x grid-padding-x align-middle small-padding-collapse">
                          <div class="small-10 cell">
                            <label>
                              <input type="text" placeholder="Search" class="input-local-search">
                            </label>
                          </div>
                          <div class="small-2 cell">
                            <a href="#" class="button-search button-show-search float-right flip button-local-search" data-posts-endpoint="<?php echo $endpoint; ?>" v-on:click.prevent="searchByKeywords"><i class="fi-magnifying-glass"></i></a>
                          </div>
                        </div>
                      </div>
                    </form>
                </div>
            </div>
            <!-- cell/ menu-block-aside -->

        </div>
        <!-- grid-x -->

    </div>

</div>
<!-- local search - small only -->
