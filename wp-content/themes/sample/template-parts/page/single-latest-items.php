<?php
/**
 * The template used for displaying the latest items
 *
 * @package WordPress
 * @subpackage Andrew
 * @since 1.0
 * @version 1.0
 */
?>

<?php
// Get the latest post.
$itemsLatest = array();
$query_args = array(
    'post_type' => $type,
    'post_status' => array('publish'),
    'posts_per_page' => 4,
    'orderby' => 'date',

    // Exclude specific posts.
    'post__not_in' => array(
        $post->ID
     ),

     // ONLY GET POST ID's, VERY LEAN QUERY
    'fields' => 'ids'
);

// Create a new instance of WP_Query
$the_query = new WP_Query($query_args);

while ($the_query->have_posts()) {
    $the_query->the_post();

    $post_id = get_the_ID();
    $post_title = get_the_title();
    $post_date = get_the_date('j F Y');

    // Get the slug.
    // https://wordpress.stackexchange.com/questions/11426/how-can-i-get-page-slug
    $post = get_post($post_id);
    $post_slug = $post->post_name;

    // Get the taxonomy that attached to the post.
    $taxonomy = get_the_terms($post, 'publication-source');

    // Push the post data into the array.
    $itemsLatest[] = array(
        'id' => $post_id,
        'slug' => $post_slug,
        'title' => $post_title,
        'url' => get_permalink($post_id),
        'date' => $post_date,
        'taxonomy' => $taxonomy
    );
}

// Reset the post to the original after loop. otherwise the current page
// becomes the last item from the while loop.
wp_reset_query();

// Get requested language.
$lang = get_query_var('lang');
?>

<?php if (count($itemsLatest) > 0) {?>

<div class="published-item">
    <h5 class="heading-published"><?php echo translateText(get_field($headingLatest, 'option'), $lang); ?></h5>
    <a href="<?php echo get_permalink($parent[0]);?>" class="button-green uppercase"><?php echo translateText(get_field('see_all', 'option'), $lang); ?></a>
</div>

<?php foreach ($itemsLatest as $key => $itemLatest) { ?>

<?php
// Get post by id.
// https://developer.wordpress.org/reference/functions/get_post/
$itemPost = get_post($itemLatest['id']);
?>

<div class="published-item">
    <h5 class="heading-published"><a href="<?php echo $itemLatest['url']; ?>"><?php echo translateTitle($itemPost, $lang); ?></a></h5>
    <span class="published-date"><?php echo $itemLatest['date']; ?></span>
    <div class="published-publishers">
        <?php if ($itemLatest['taxonomy'] && count($itemLatest['taxonomy']) > 0) { ?>
            <?php foreach ($itemLatest['taxonomy'] as $key => $itemSource) { ?>
                <span class="published-publisher"><?php echo $itemSource->name; ?></span>
            <?php } ?>
        <?php } ?>

        <?php
        // Get content source name.
        $contentSource = get_field('content_source', $itemLatest['id']);
        ?>

        <?php if ($contentSource) { ?>
            <span class="published-publisher"><?php echo $contentSource; ?></span>
        <?php } ?>
    </div>
</div>

<?php } ?>

<?php } ?>
