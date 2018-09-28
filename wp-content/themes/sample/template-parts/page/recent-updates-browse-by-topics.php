<?php
/**
 * The template used for displaying browse by topics.
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

// Get all categories.
// https://developer.wordpress.org/reference/functions/get_terms/
$args = array(
    'orderby' => 'term_order',
    'order' => 'ASC',
    'hide_empty' => 0,
    'hierarchical' => true,
    'taxonomy' => 'publication-category',
);
$categories = get_terms($args);
?>

<?php foreach ($categories as $key => $category) { ?>
    <li><a href="<?php echo switchUrl(site_url(), $lang); ?>/publications/<?php echo $category->slug; ?>/"><?php echo translateText($category->name, $lang); ?></a></li>
<?php } ?>
