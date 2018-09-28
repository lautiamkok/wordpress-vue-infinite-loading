<?php
/**
 * Andrew APIs
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Andrew
 * @since 1.0
 * @version 1.0
 */

/**
 * Custom JSON API endpoint.
 * Usage: http://127.0.1.1/repo-github/wordpress-vue-infinite-loading/wp-json/events/v1/lang/en/parent/2/page/1
 * https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/
 * https://webdevstudios.com/2015/07/09/creating-simple-json-endpoint-wordpress/
 */
function api_events($data) {
    $output = array();

    // e.g. 1, 2, 3,...
    $paged = $data['page_number'] ? $data['page_number'] : 1;
    $lang = $data['lang'];
    $query_args = array(
        'post_type' => 'event',
        'post_status' => array('publish'),
        'posts_per_page' => 4,
        'paged' => $paged,
        'orderby' => 'date',

        // Specific year.
        // 'year' => 2016,

        // Specific category.
        // 'cat' => '', // e.g. 1, 2, 3, 4, ...
        // 'category_name' => 'events', // .e.g. news, events, ...

        // Exclude meta specific key.
        // https://core.trac.wordpress.org/ticket/18158
        // 'meta_query' => array(
        //     array(
        //         'key' => 'java_meta_feature',
        //         'compare' => 'NOT EXISTS'
        //     ),
        // )
    );

    // Create a new instance of WP_Query
    $the_query = new WP_Query($query_args);

    if (!$the_query->have_posts()) {
        return null;
    }

    while ($the_query->have_posts()) {
        $the_query->the_post();

        $post_id = get_the_ID();

        // Get the slug.
        // https://wordpress.stackexchange.com/questions/11426/how-can-i-get-page-slug
        $post = get_post($post_id);
        $post_slug = $post->post_name;

        $post_title = translateTitle($post, $lang);
        $post_excerpt = translateText(get_the_excerpt(), $lang);
        $post_date = get_the_date('j M Y');

        // Get content source name.
        $contentSource = get_field('content_source', $post_id);

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

        // Get ACF repeater data.
        $externalLinks = get_field('external_links', $post_id); // get all the rows
        $internalLinks = get_field('internal_links', $post_id); // get all the rows
        $internalFiles = get_field('internal_files', $post_id); // get all the rows

        // Push the post data into the array.
        $output[] = array(
            'id' => "post" . $post_id, // cannot use numbers, or hyphens between words for Zurb data toggle attribute
            'slug' => $post_slug,
            'title' => $post_title,
            'excerpt' => $post_excerpt,
            'url' => get_permalink($post_id),
            'date' => $post_date,
            'contentSource' => $contentSource,
            'image' => $post_image,
            'externalLinks' => $externalLinks,
            'internalLinks' => $internalLinks,
            'internalFiles' => $internalFiles,
        );
    }

    // Reset the post to the original after loop. otherwise the current page
    // becomes the last item from the while loop.
    wp_reset_query();

    return $output;
}
add_action('rest_api_init', function () {
    register_rest_route('events/v1', '/lang/(?P<lang>[a-zA-Z0-9-]+)/parent/(?P<parent_id>\d+)/page/(?P<page_number>\d+)', array(
        'methods' => 'GET',
        'callback' => 'api_events',
    ));
});
