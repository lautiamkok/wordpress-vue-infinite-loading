<?php
/**
 * The template used for displaying recent updates.
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

// Get the featured post & its cover image.
$featuredPost = get_field('featured_post', $post->ID);

$recentUpdates = array();
$query_args = array(
    'post_type' => array(
      'publication',
      'video',
      'event',
      'speech',
      // 'book'
    ),
    'post_status' => array('publish'),
    'posts_per_page' => 4,
    'orderby' => 'date',
    'order' => 'DESC',

    // Exclude specific posts.
    'post__not_in' => array(
        $featuredPost->ID,
        $post->ID,
     ),

     // ONLY GET POST ID's, VERY LEAN QUERY
    // 'fields' => 'ids'
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

    // Get image alt.
    // https://stackoverflow.com/questions/19267650/get-wordpress-featured-image-alt
    $alt = get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true);
    $desc = get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_caption', true);

    // Get image description.
    // https://wordpress.stackexchange.com/questions/111937/how-to-show-featured-image-caption-only-if-it-exist
    $description = null;
    $thumbnail = get_posts(array('p' => get_post_thumbnail_id(), 'post_type' => 'attachment'));
    if ($thumbnail && isset($thumbnail[0])) {
        $description = $thumbnail[0]->post_content;
    }

    // Image data.
    $post_image = array(
        'id' => get_post_thumbnail_id() ,
        'url'  => get_the_post_thumbnail_url(),
        'caption' => get_the_post_thumbnail_caption(),
        'alt' => $alt,
        'description' => $description,
    );

    // Get parent.
    $slug = get_post_type() . 's';

    $args = array(
      'name' => $slug,
      'post_type' => 'page',
      'post_status' => 'publish',
      'numberposts' => 1
    );
    $parent = get_posts($args);

    // If it is speech type.
    if (get_post_type() === 'speech') {
        $slug = get_post_type() . 'es';

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
    // If it is event type.
    if (get_post_type() === 'event') {
        $slug = get_post_type() . 's';

        // Get title link.
        $externalLinks = get_field('external_links', $post_id); // get all the rows
        $internalLinks = get_field('internal_links', $post_id); // get all the rows
        $internalFiles = get_field('internal_files', $post_id); // get all the rows
        if ($externalLinks) {
            $post_url  = $externalLinks[0]['link'];
            $newTab = true;
        }
        if ($internalLinks) {
            $post_url  = $internalLinks[0]['link'];
        }
        if ($internalFiles) {
            $newFetch = true;
            $post_url  = $internalFiles[0]['link'];
        }

        // If none, then point to its parent - events.
        if ((count($externalLinks) === 0) && (count($internalLinks) === 0) && (count($internalFiles) === 0)) {
            $post_url = get_permalink($parent[0]);
        }
    }


    // Push the post data into the array.
    $recentUpdates[] = array(
        'id' => $post_id, // cannot use numbers, or hyphens between words for Zurb data toggle attribute
        'slug' => $post_slug,
        'title' => $post_title,
        'url' => $post_url,
        'date' => $post_date,
        'image' => $post_image,
        'parent' => $parent[0],
        'newTab' => $newTab,
    );
}

// Reset the post to the original after loop. otherwise the current page
// becomes the last item from the while loop.
wp_reset_query();
?>

<!-- row latest -->
<div class="row row-latest-header" data-aos="fade-up">
    <div class="grid-container">
        <div class="grid-x grid-padding-x align-bottom">

            <div class="large-3 cell">
               <h2 class="heading"><?php echo translateText(get_field('recent_updates', 'option'), $lang); ?></h2>
            </div>

            <div class="large-9 cell">
                <nav class="nav-topic">
                    <ul class="menu menu-header align-right simple show-for-large">
                        <li class="heading-topic"><?php echo translateText(get_field('browse_by_topic', 'option'), $lang); ?></li>
                        <?php
                        // Include the filter nav.
                        get_template_part('template-parts/page/recent-updates', 'browse-by-topics');
                        ?>
                    </ul>

                    <ul class="menu menu-header align-left simple show-for-medium-only">
                        <li class="heading-topic"><?php echo translateText(get_field('browse_by_topic', 'option'), $lang); ?></li>
                        <?php
                        // Include the filter nav.
                        get_template_part('template-parts/page/recent-updates', 'browse-by-topics');
                        ?>
                    </ul>
                </nav>
            </div>

            <div class="large-12 cell">
                <hr class="divider-horizontal" />
            </div>

        </div>
    </div>
</div>
<!-- row latest -->

<!-- row latest -->
<div class="row row-card">
    <div class="grid-container">
        <div class="grid-x grid-padding-x">

            <?php foreach ($recentUpdates as $key => $recentUpdate) { ?>
            <?php
            // Get post by id.
            // https://developer.wordpress.org/reference/functions/get_post/
            $itemPost = get_post($recentUpdate['id']);
            ?>

            <!-- cell -->
            <div class="large-3 medium-6 cell cell-card">

                <!-- container card -->
                <div class="container-card bar-bottom" data-aos="fade-up">

                    <!-- card -->
                    <div class="card card-latest">
                        <div class="card-image scale-fade">
                            <div class="image-background square-image trigger-url" style="background-image: url(<?php echo $recentUpdate['image']['url']; ?>)">
                                <a href="<?php echo $recentUpdate['url']; ?>" class="button-for-trigger"<?php if ($recentUpdate['newTab']) { ?> target="_blank"<?php } ?>>
                                    <img src="<?php echo $recentUpdate['image']['url']; ?>" alt="<?php echo $recentUpdate['image']['alt']; ?>">
                                </a>
                            </div>
                        </div>
                        <div class="card-section">
                            <span class="category-card"><a href="<?php echo get_permalink($recentUpdate['parent']->ID); ?>" class="button-green uppercase"><?php echo translateTitle($recentUpdate['parent'], $lang); ?></a></span>
                            <h4 class="heading-title"><a href="<?php echo $recentUpdate['url']; ?>" class="button-title"<?php if ($recentUpdate['newTab']) { ?> target="_blank"<?php } ?>><?php echo translateTitle($itemPost, $lang); ?></a></h4>
                        </div>
                    </div>
                    <!-- card -->

                </div>
                <!-- container card -->

            </div>
            <!-- cell -->
            <?php } ?>

        </div>
    </div>
</div>
<!-- row latest -->
