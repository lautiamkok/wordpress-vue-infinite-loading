<?php
/**
 * The template used for displaying latest news.
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

$recentUpdates = array();
$query_args = array(
    'post_type' => array(
      'publication',
      'video',
      // 'event',
      'speech',
      // 'book'
    ),
    'post_status' => array('publish'),
    'posts_per_page' => 1,
    'orderby' => 'date',
    'order' => 'DESC',
);

// Create a new instance of WP_Query
$the_query = new WP_Query($query_args);

while ($the_query->have_posts()) {
    $the_query->the_post();

    $newTab = false;
    $post_id = get_the_ID();
    $post_title = translateTitle($post, $lang);
    $post_date = get_the_date('l, j F Y');
    $post_url = get_permalink($post_id);

    // Get the slug.
    // https://wordpress.stackexchange.com/questions/11426/how-can-i-get-page-slug
    $post = get_post($post_id);
    $post_slug = $post->post_name;

    // If it is speech type.
    if (get_post_type() === 'speech') {
        // Get title link.
        $externalLink = get_field('external_link', $post_id);
        $internalLink = get_field('internal_link', $post_id);
        $internalFile = get_field('internal_file', $post_id);
        if ($externalLink) {
            $post_url  = $externalLink;
            $newTab = true;
        }
        if ($internalLink) {
            $post_url  = $internalLink;
        }
        if ($internalFile) {
            $newTab = true;
            $post_url  = $internalFile;
        }
    }

    // Push the post data into the array.
    $recentUpdates[] = array(
        'id' => $post_id, // cannot use numbers, or hyphens between words for Zurb data toggle attribute
        'slug' => $post_slug,
        'title' => $post_title,
        'url' => $post_url,
        'date' => $post_date,
        'newTab' => $newTab,
    );
}

// Reset the post to the original after loop. otherwise the current page
// becomes the last item from the while loop.
wp_reset_query();
?>

<a href="<?php echo $recentUpdates[0]['url']; ?>" class="flex-centre"<?php if ($recentUpdates[0]['newTab']) { ?> target="_blank"<?php } ?>>
    <span class="propagate"><?php echo $recentUpdates[0]['title']; ?></span>
    <i class="material-icons mi-small">keyboard_arrow_right</i>
</a>
