<?php
/**
 * The template used for displaying page nav
 *
 * @package WordPress
 * @subpackage Andrew
 * @since 1.0
 * @version 1.0
 */
?>

<?php
// Get all tags.
// https://developer.wordpress.org/reference/functions/get_tags/
$args = array(
    'orderby' => 'name',
    'order' => 'ASC',
    'hide_empty' => 0,
    'hierarchical' => true
);

$tags = get_tags($args);

// Get requested language.
$lang = get_query_var('lang');
?>

<?php if (count($tags) > 0) { ?>
<!-- <ul class="nested vertical menu menu-sub children"> -->
    <?php foreach ($tags as $key => $tag) { ?>
    <?php
    $classes = array();
    $tag_url = get_tag_link($tag->term_id);
    if ($lang) {
        $pattern = "/http:\/\/127.0.0.1\/projects\/andrew-wordpress\//";
        $pattern = json_encode(site_url() . '/');
        $replacement = switchUrl(site_url(), $lang);
        $string = $tag_url;
        $tag_url = preg_replace($pattern, $replacement, $string);
    }

    if ($tag_url === $current_url . '/') {
        array_push($classes, "current");
    }

    // Get the requested category.
    $current_page = get_queried_object();
    $tag_slug = $current_page->slug;
    if ($tag->slug === $tag_slug) {
        array_push($classes, "current");
    }

    if (($key + 1) === (count($tags) - 1)) {
        array_push($classes, "last");
    }

    ?>
    <?php if ($tag->slug !== 'uncategorized') { ?>
    <li<?php if (count($classes) > 0){ ?> class="child <?php echo implode(' ', array_unique($classes)); ?>"<?php } ?>><a href="<?php echo $tag_url; ?>"><?php echo translateText($tag->name, $lang); ?></a></li>
    <?php } ?>
    <?php } ?>
<!-- </ul> -->
<?php } ?>
