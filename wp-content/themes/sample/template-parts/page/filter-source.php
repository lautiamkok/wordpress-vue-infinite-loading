<?php
/**
 * The template used for displaying the filter
 *
 * @package WordPress
 * @subpackage Andrew
 * @since 1.0
 * @version 1.0
 */
?>

<?php
// Get all categories.
// https://developer.wordpress.org/reference/functions/get_terms/
$args = array(
    'orderby' => 'term_order',
    'order' => 'ASC',
    'hide_empty' => 0,
    'hierarchical' => true,
    'taxonomy' => 'publication-source',
);
$categories = get_terms($args);

// Get requested language.
$lang = get_query_var('lang');
?>

<?php if (count($categories) > 0) { ?>
    <?php foreach ($categories as $key => $category) { ?>
    <?php
    $classes = array();
    $category_url = get_category_link($category->term_taxonomy_id);
    if ($lang) {
        $pattern = "/http:\/\/127.0.0.1\/projects\/andrew-wordpress\//";
        $pattern = json_encode(site_url() . '/');
        $replacement = switchUrl(site_url(), $lang);
        $string = $category_url;
        $category_url = preg_replace($pattern, $replacement, $string);
    }

    if ($category_url === $current_url . '/') {
        array_push($classes, "current");
    }

    // Get the requested category.
    $current_page = get_queried_object();
    $category_slug = $current_page->slug;
    if ($category->slug === $category_slug) {
        array_push($classes, "current");
    }

    if (($key + 1) === (count($categories) - 1)) {
        array_push($classes, "last");
    }

    ?>
    <li<?php if (count($classes) > 0){ ?> class="child <?php echo implode(' ', array_unique($classes)); ?>"<?php } ?>><a href="<?php echo $category_url; ?>" data-cat="<?php echo $category->slug; ?>" v-on:click.prevent="filterBySource"><?php echo translateText($category->name, $lang); ?></a></li>
    <?php } ?>
<?php } ?>
