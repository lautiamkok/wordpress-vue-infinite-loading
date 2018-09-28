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
 * Usage: http://127.0.1.1/repo-github/wordpress-vue-infinite-loading/wp-json/search/v1/lang/en/keywords/something+amazing/page/1
 * https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/
 * https://webdevstudios.com/2015/07/09/creating-simple-json-endpoint-wordpress/
 */
function api_global_search($data) {
    $output = array();

    // e.g. 1, 2, 3,...
    $paged = $data['page_number'] ? $data['page_number'] : 1;
    $lang = $data['lang'];
    $keywords = $data['keywords'];
    $query_args = array(
        's' => $keywords,
        'post_type' => array(
            'publication',
            'video',
            'event',
            'speech',
            'book'
        ),
        'post_status' => array('publish'),
        'posts_per_page' => 4,
        'paged' => $paged,
        'orderby' => 'date',
    );

    // Create a new instance of WP_Query
    $the_query = new WP_Query($query_args);

    if (!$the_query->have_posts()) {
        return null;
    }

    // Get the total number of the search result.
    $allsearch = new WP_Query("s=" . $keywords ."&showposts=0");
    $total = $allsearch ->found_posts;

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
        $post_type = get_post_type();

        // Get post url.
        $post_url = get_permalink($post_id);

        // For type: publication
        if ($post_type === 'publication') {

            // Get the taxonomy that attached to the post.
            $taxonomy = get_the_terms($post, 'publication-source');
            $taxonomy = translateTextInArray($items = $taxonomy, $search = 'name', $lang);

            // Push the post data into the array.
            $output[] = array(
                'id' => "post" . $post_id, // cannot use numbers, or hyphens between words for Zurb data toggle attribute
                'slug' => $post_slug,
                'title' => $post_title,
                'url' => $post_url,
                'date' => $post_date,
                'taxonomy' => $taxonomy,
                'type' => $post_type,
                'total' => $total,
            );
        }

        // For type: publication
        if ($post_type === 'video') {

            // Get content source name.
            $contentSource = get_field('content_source', $post_id);

            // Push the post data into the array.
            $output[] = array(
                'id' => "post" . $post_id, // cannot use numbers, or hyphens between words for Zurb data toggle attribute
                'slug' => $post_slug,
                'title' => $post_title,
                'url' => $post_url,
                'date' => $post_date,
                'contentSource' => $contentSource,
                'type' => $post_type,
                'total' => $total,
            );
        }

        // For type: publication
        if ($post_type === 'book') {

            // Get content source name.
            $contentSource = get_field('content_source', $post_id);

            // Get ACF repeater data.
            $externalLinks = get_field('external_links', $post_id); // get all the rows
            $internalFiles = get_field('internal_files', $post_id); // get all the rows

            // Push the post data into the array.
            $output[] = array(
                'id' => "post" . $post_id, // cannot use numbers, or hyphens between words for Zurb data toggle attribute
                'slug' => $post_slug,
                'title' => $post_title,
                'excerpt' => $post_excerpt,
                'url' => $post_url,
                'date' => $post_date,
                'contentSource' => $contentSource,
                'externalLinks' => $externalLinks,
                'internalFiles' => $internalFiles,
                'type' => $post_type,
                'total' => $total,
            );
        }

        // For type: publication
        if ($post_type === 'event') {

            // Get content source name.
            $contentSource = get_field('content_source', $post_id);

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
                'url' => $post_url,
                'date' => $post_date,
                'contentSource' => $contentSource,
                'externalLinks' => $externalLinks,
                'internalLinks' => $internalLinks,
                'internalFiles' => $internalFiles,
                'type' => $post_type,
                'total' => $total,
            );
        }

        // For type: publication
        if ($post_type === 'speech') {

            // Get the taxonomy that attached to the post.
            $taxonomy = get_the_terms($post, 'speech-source');
            $taxonomy = translateTextInArray($items = $taxonomy, $search = 'name', $lang);

            // Get title link.
            $externalLink = get_field('external_link', $post_id);
            $internalLink = get_field('internal_link', $post_id);
            $internalFile = get_field('internal_file', $post_id);

            $titleLink = null;
            if ($externalLink) {
                $titleLink = $externalLink;
            }
            if ($internalLink) {
                $titleLink = $internalLink;
            }
            if ($internalFile) {
                $titleLink = $internalFile;
            }

            // Push the post data into the array.
            $output[] = array(
                'id' => "post" . $post_id, // cannot use numbers, or hyphens between words for Zurb data toggle attribute
                'slug' => $post_slug,
                'title' => $post_title,
                'url' => $titleLink,
                'date' => $post_date,
                'taxonomy' => $taxonomy,
                'type' => $post_type,
                'total' => $total,
            );
        }
    }

    // Reset the post to the original after loop. otherwise the current page
    // becomes the last item from the while loop.
    wp_reset_query();

    return $output;
}
add_action('rest_api_init', function () {
    // Include spaces in the search.
    // https://wordpress.stackexchange.com/questions/269149/rest-api-custom-endpoint-with-space-character
    register_rest_route('search/v1', '/lang/(?P<lang>[a-zA-Z0-9-]+)/keywords/(?P<keywords>([a-zA-Z0-9-\+])+)/page/(?P<page_number>\d+)', array(
        'methods' => 'GET',
        'callback' => 'api_global_search',
    ));
});

/**
 * Custom JSON API endpoint.
 * Usage: http://127.0.1.1/repo-github/wordpress-vue-infinite-loading/wp-json/search/v1/lang/en/keywords/something+amazing/year/2016/page/1
 * https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/
 * https://webdevstudios.com/2015/07/09/creating-simple-json-endpoint-wordpress/
 */
function api_global_search_by_year($data) {
    $output = array();

    // e.g. 1, 2, 3,...
    $paged = $data['page_number'] ? $data['page_number'] : 1;
    $lang = $data['lang'];
    $keywords = $data['keywords'];
    $year = $data['year'];
    $query_args = array(
        's' => $keywords,
        'post_type' => array(
            'publication',
            'video',
            'event',
            'speech',
            'book'
        ),
        'post_status' => array('publish'),
        'posts_per_page' => 4,
        'paged' => $paged,
        'orderby' => 'date',

        // Specific year.
        'year' => $year,
    );

    // Create a new instance of WP_Query
    $the_query = new WP_Query($query_args);

    if (!$the_query->have_posts()) {
        return null;
    }

    // Get the total number of the search result.
    $allsearch = new WP_Query("s=" . $keywords ."&year=" . $year . "&showposts=0");
    $total = $allsearch ->found_posts;

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
        $post_type = get_post_type();

        // Get post url.
        $post_url = get_permalink($post_id);

        // For type: publication
        if ($post_type === 'publication') {

            // Get the taxonomy that attached to the post.
            $taxonomy = get_the_terms($post, 'publication-source');
            $taxonomy = translateTextInArray($items = $taxonomy, $search = 'name', $lang);

            // Push the post data into the array.
            $output[] = array(
                'id' => "post" . $post_id, // cannot use numbers, or hyphens between words for Zurb data toggle attribute
                'slug' => $post_slug,
                'title' => $post_title,
                'url' => $post_url,
                'date' => $post_date,
                'taxonomy' => $taxonomy,
                'type' => $post_type,
                'total' => $total,
            );
        }

        // For type: publication
        if ($post_type === 'video') {

            // Get content source name.
            $contentSource = get_field('content_source', $post_id);

            // Push the post data into the array.
            $output[] = array(
                'id' => "post" . $post_id, // cannot use numbers, or hyphens between words for Zurb data toggle attribute
                'slug' => $post_slug,
                'title' => $post_title,
                'url' => $post_url,
                'date' => $post_date,
                'contentSource' => $contentSource,
                'type' => $post_type,
                'total' => $total,
            );
        }

        // For type: publication
        if ($post_type === 'book') {

            // Get content source name.
            $contentSource = get_field('content_source', $post_id);

            // Get ACF repeater data.
            $externalLinks = get_field('external_links', $post_id); // get all the rows
            $internalFiles = get_field('internal_files', $post_id); // get all the rows

            // Push the post data into the array.
            $output[] = array(
                'id' => "post" . $post_id, // cannot use numbers, or hyphens between words for Zurb data toggle attribute
                'slug' => $post_slug,
                'title' => $post_title,
                'excerpt' => $post_excerpt,
                'url' => $post_url,
                'date' => $post_date,
                'contentSource' => $contentSource,
                'externalLinks' => $externalLinks,
                'internalFiles' => $internalFiles,
                'type' => $post_type,
                'total' => $total,
            );
        }

        // For type: publication
        if ($post_type === 'event') {

            // Get content source name.
            $contentSource = get_field('content_source', $post_id);

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
                'url' => $post_url,
                'date' => $post_date,
                'contentSource' => $contentSource,
                'externalLinks' => $externalLinks,
                'internalLinks' => $internalLinks,
                'internalFiles' => $internalFiles,
                'type' => $post_type,
                'total' => $total,
            );
        }

        // For type: publication
        if ($post_type === 'speech') {

            // Get the taxonomy that attached to the post.
            $taxonomy = get_the_terms($post, 'speech-source');
            $taxonomy = translateTextInArray($items = $taxonomy, $search = 'name', $lang);

            // Get title link.
            $externalLink = get_field('external_link', $post_id);
            $internalLink = get_field('internal_link', $post_id);
            $internalFile = get_field('internal_file', $post_id);

            $titleLink = null;
            if ($externalLink) {
                $titleLink = $externalLink;
            }
            if ($internalLink) {
                $titleLink = $internalLink;
            }
            if ($internalFile) {
                $titleLink = $internalFile;
            }

            // Push the post data into the array.
            $output[] = array(
                'id' => "post" . $post_id, // cannot use numbers, or hyphens between words for Zurb data toggle attribute
                'slug' => $post_slug,
                'title' => $post_title,
                'url' => $titleLink,
                'date' => $post_date,
                'taxonomy' => $taxonomy,
                'type' => $post_type,
                'total' => $total,
            );
        }
    }

    // Reset the post to the original after loop. otherwise the current page
    // becomes the last item from the while loop.
    wp_reset_query();

    return $output;
}
add_action('rest_api_init', function () {
    // Include spaces in the search.
    // https://wordpress.stackexchange.com/questions/269149/rest-api-custom-endpoint-with-space-character
    register_rest_route('search/v1', '/lang/(?P<lang>[a-zA-Z0-9-]+)/keywords/(?P<keywords>([a-zA-Z0-9-\+])+)/year/(?P<year>[a-zA-Z0-9-]+)/page/(?P<page_number>\d+)', array(
        'methods' => 'GET',
        'callback' => 'api_global_search_by_year',
    ));
});

/**
 * Custom JSON API endpoint.
 * Usage: http://127.0.1.1/repo-github/wordpress-vue-infinite-loading/wp-json/search/v1/lang/en/keywords/something+amazing/type/video/page/1
 * https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/
 * https://webdevstudios.com/2015/07/09/creating-simple-json-endpoint-wordpress/
 */
function api_global_search_by_type($data) {
    $output = array();

    // e.g. 1, 2, 3,...
    $paged = $data['page_number'] ? $data['page_number'] : 1;
    $lang = $data['lang'];
    $keywords = $data['keywords'];
    $type = $data['type'];
    $query_args = array(
        's' => $keywords,
        'post_type' => $type,
        'post_status' => array('publish'),
        'posts_per_page' => 4,
        'paged' => $paged,
        'orderby' => 'date',
    );

    // Create a new instance of WP_Query
    $the_query = new WP_Query($query_args);

    if (!$the_query->have_posts()) {
        return null;
    }

    // Get the total number of the search result.
    $allsearch = new WP_Query("s=" . $keywords . "&post_type=" . $type . "&showposts=0");
    $total = $allsearch ->found_posts;

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
        $post_type = get_post_type();

        // Get post url.
        $post_url = get_permalink($post_id);

        // For type: publication
        if ($post_type === 'publication') {

            // Get the taxonomy that attached to the post.
            $taxonomy = get_the_terms($post, 'publication-source');
            $taxonomy = translateTextInArray($items = $taxonomy, $search = 'name', $lang);

            // Push the post data into the array.
            $output[] = array(
                'id' => "post" . $post_id, // cannot use numbers, or hyphens between words for Zurb data toggle attribute
                'slug' => $post_slug,
                'title' => $post_title,
                'url' => $post_url,
                'date' => $post_date,
                'taxonomy' => $taxonomy,
                'type' => $post_type,
                'total' => $total,
            );
        }

        // For type: publication
        if ($post_type === 'video') {

            // Get content source name.
            $contentSource = get_field('content_source', $post_id);

            // Push the post data into the array.
            $output[] = array(
                'id' => "post" . $post_id, // cannot use numbers, or hyphens between words for Zurb data toggle attribute
                'slug' => $post_slug,
                'title' => $post_title,
                'url' => $post_url,
                'date' => $post_date,
                'contentSource' => $contentSource,
                'type' => $post_type,
                'total' => $total,
            );
        }

        // For type: publication
        if ($post_type === 'book') {

            // Get content source name.
            $contentSource = get_field('content_source', $post_id);

            // Get ACF repeater data.
            $externalLinks = get_field('external_links', $post_id); // get all the rows
            $internalFiles = get_field('internal_files', $post_id); // get all the rows

            // Push the post data into the array.
            $output[] = array(
                'id' => "post" . $post_id, // cannot use numbers, or hyphens between words for Zurb data toggle attribute
                'slug' => $post_slug,
                'title' => $post_title,
                'excerpt' => $post_excerpt,
                'url' => $post_url,
                'date' => $post_date,
                'contentSource' => $contentSource,
                'externalLinks' => $externalLinks,
                'internalFiles' => $internalFiles,
                'type' => $post_type,
                'total' => $total,
            );
        }

        // For type: publication
        if ($post_type === 'event') {

            // Get content source name.
            $contentSource = get_field('content_source', $post_id);

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
                'url' => $post_url,
                'date' => $post_date,
                'contentSource' => $contentSource,
                'externalLinks' => $externalLinks,
                'internalLinks' => $internalLinks,
                'internalFiles' => $internalFiles,
                'type' => $post_type,
                'total' => $total,
            );
        }

        // For type: publication
        if ($post_type === 'speech') {

            // Get the taxonomy that attached to the post.
            $taxonomy = get_the_terms($post, 'speech-source');
            $taxonomy = translateTextInArray($items = $taxonomy, $search = 'name', $lang);

            // Get title link.
            $externalLink = get_field('external_link', $post_id);
            $internalLink = get_field('internal_link', $post_id);
            $internalFile = get_field('internal_file', $post_id);

            $titleLink = null;
            if ($externalLink) {
                $titleLink = $externalLink;
            }
            if ($internalLink) {
                $titleLink = $internalLink;
            }
            if ($internalFile) {
                $titleLink = $internalFile;
            }

            // Push the post data into the array.
            $output[] = array(
                'id' => "post" . $post_id, // cannot use numbers, or hyphens between words for Zurb data toggle attribute
                'slug' => $post_slug,
                'title' => $post_title,
                'url' => $titleLink,
                'date' => $post_date,
                'taxonomy' => $taxonomy,
                'type' => $post_type,
                'total' => $total,
            );
        }
    }

    // Reset the post to the original after loop. otherwise the current page
    // becomes the last item from the while loop.
    wp_reset_query();

    return $output;
}
add_action('rest_api_init', function () {
    // Include spaces in the search.
    // https://wordpress.stackexchange.com/questions/269149/rest-api-custom-endpoint-with-space-character
    register_rest_route('search/v1', '/lang/(?P<lang>[a-zA-Z0-9-]+)/keywords/(?P<keywords>([a-zA-Z0-9-\+])+)/type/(?P<type>[a-zA-Z0-9-]+)/page/(?P<page_number>\d+)', array(
        'methods' => 'GET',
        'callback' => 'api_global_search_by_type',
    ));
});

