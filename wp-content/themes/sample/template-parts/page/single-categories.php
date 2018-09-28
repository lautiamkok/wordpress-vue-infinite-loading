<?php
/**
 * The template used for displaying categories
 *
 * @package WordPress
 * @subpackage Andrew
 * @since 1.0
 * @version 1.0
 */
?>

<?php
// Get content source.
// Get the taxonomy that attached to the post.
$itemsCategory = get_the_terms($post, $taxonomyCategory);

// Get requested language.
$lang = get_query_var('lang');
?>

<ul class="inline-list">
    <li class="heading-list"><?php echo translateText(get_field('category', 'option'), $lang); ?></li>
    <?php if ($itemsCategory && count($itemsCategory) > 0) { ?>
        <?php foreach ($itemsCategory as $key => $itemCategory) { ?>
            <li><a href="<?php echo get_permalink($parent[0]) . $itemCategory->slug . '/'; ?>"><?php echo translateText($itemCategory->name, $lang); ?></a></li>
        <?php } ?>
    <?php } ?>
</ul>
