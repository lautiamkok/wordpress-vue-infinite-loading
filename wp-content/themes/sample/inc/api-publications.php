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
 * Usage: http://127.0.1.1/repo-github/wordpress-vue-infinite-loading/wp-json/publications/v1/lang/en/parent/2/page/1
 * https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/
 * https://webdevstudios.com/2015/07/09/creating-simple-json-endpoint-wordpress/
 */
function api_publications($data) {
    $output = array();

    // e.g. 1, 2, 3,...
    $paged = $data['page_number'] ? $data['page_number'] : 1;
    $lang = $data['lang'];
    $query_args = array(
        'post_type' => 'publication',
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
        $post_date = get_the_date('j M Y');
        $post_url = switchLink(get_permalink($post_id), $lang);

        // Get the taxonomy that attached to the post.
        $taxonomy = get_the_terms($post, 'publication-source');

        // Push the post data into the array.
        $output[] = array(
            'id' => "post" . $post_id, // cannot use numbers, or hyphens between words for Zurb data toggle attribute
            'slug' => $post_slug,
            'title' => $post_title,
            'url' => $post_url,
            'date' => $post_date,
            'taxonomy' => $taxonomy
        );
    }

    // Reset the post to the original after loop. otherwise the current page
    // becomes the last item from the while loop.
    wp_reset_query();

    return $output;
}
add_action('rest_api_init', function () {
    register_rest_route('publications/v1', '/lang/(?P<lang>[a-zA-Z0-9-]+)/parent/(?P<parent_id>\d+)/page/(?P<page_number>\d+)', array(
        'methods' => 'GET',
        'callback' => 'api_publications',
    ));
});

/**
 * Custom JSON API endpoint.
 * Usage: http://127.0.1.1/repo-github/wordpress-vue-infinite-loading/wp-json/publications/v1/lang/en/category/finance/page/1
 * https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/
 * https://webdevstudios.com/2015/07/09/creating-simple-json-endpoint-wordpress/
 */
function api_publications_by_category($data) {
    $output = array();

    // e.g. 1, 2, 3,...
    $paged = $data['page_number'] ? $data['page_number'] : 1;
    $lang = $data['lang'];
    $category_slug = $data['category_slug'];
    $query_args = array(
        'post_type' => 'publication',
        'post_status' => array('publish'),
        'posts_per_page' => 4,
        // 'post_parent' => 2,
        'paged' => $paged,
        'orderby' => 'date',

        // Specific year.
        // 'year' => 2016,

        // Specific category.
        // 'cat' => $cat_id, // e.g. 1, 2, 3, 4, ...
        // 'category_name' => $category_slug, // .e.g. news, events, ...

        'tax_query' => array(
            array (
                'taxonomy' => 'publication-category',
                'field' => 'slug', //this is by slug
                'terms' => $category_slug, // slug name
            )
        ),
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
        $post_date = get_the_date('j M Y');
        $post_url = switchLink(get_permalink($post_id), $lang);

        // Get the taxonomy that attached to the post.
        $taxonomy = get_the_terms($post, 'publication-source');

        // Push the post data into the array.
        $output[] = array(
            'id' => "post" . $post_id, // cannot use numbers, or hyphens between words for Zurb data toggle attribute
            'slug' => $post_slug,
            'title' => $post_title,
            'url' => $post_url,
            'date' => $post_date,
            'taxonomy' => $taxonomy
        );
    }

    // Reset the post to the original after loop. otherwise the current page
    // becomes the last item from the while loop.
    wp_reset_query();

    return $output;
}
add_action('rest_api_init', function () {
    register_rest_route('publications/v1', '/lang/(?P<lang>[a-zA-Z0-9-]+)/category/(?P<category_slug>[a-zA-Z0-9-]+)/page/(?P<page_number>\d+)', array(
        'methods' => 'GET',
        'callback' => 'api_publications_by_category',
    ));
});

/**
 * Custom JSON API endpoint.
 * Usage: http://127.0.1.1/repo-github/wordpress-vue-infinite-loading/wp-json/publications/v1/lang/en/source/star-online/page/1
 * https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/
 * https://webdevstudios.com/2015/07/09/creating-simple-json-endpoint-wordpress/
 */
function api_publications_by_source($data) {
    $output = array();

    // e.g. 1, 2, 3,...
    $paged = $data['page_number'] ? $data['page_number'] : 1;
    $lang = $data['lang'];
    $source_slug = $data['source_slug'];
    $query_args = array(
        'post_type' => 'publication',
        'post_status' => array('publish'),
        'posts_per_page' => 4,
        // 'post_parent' => 2,
        'paged' => $paged,
        'orderby' => 'date',

        // Specific year.
        // 'year' => 2016,

        // Specific category.
        // 'cat' => $cat_id, // e.g. 1, 2, 3, 4, ...
        // 'category_name' => $category_slug, // .e.g. news, events, ...

        'tax_query' => array(
            array (
                'taxonomy' => 'publication-source',
                'field' => 'slug', //this is by slug
                'terms' => $source_slug, // slug name
            )
        ),
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
        $post_date = get_the_date('j M Y');
        $post_url = switchLink(get_permalink($post_id), $lang);

        // Get the taxonomy that attached to the post.
        $taxonomy = get_the_terms($post, 'publication-source');

        // Push the post data into the array.
        $output[] = array(
            'id' => "post" . $post_id, // cannot use numbers, or hyphens between words for Zurb data toggle attribute
            'slug' => $post_slug,
            'title' => $post_title,
            'url' => $post_url,
            'date' => $post_date,
            'taxonomy' => $taxonomy
        );
    }

    // Reset the post to the original after loop. otherwise the current page
    // becomes the last item from the while loop.
    wp_reset_query();

    return $output;
}
add_action('rest_api_init', function () {
    register_rest_route('publications/v1', '/lang/(?P<lang>[a-zA-Z0-9-]+)/source/(?P<source_slug>[a-zA-Z0-9-]+)/page/(?P<page_number>\d+)', array(
        'methods' => 'GET',
        'callback' => 'api_publications_by_source',
    ));
});

/**
 * Custom JSON API endpoint.
 * Usage: http://127.0.1.1/repo-github/wordpress-vue-infinite-loading/wp-json/publications/v1/lang/en/year/2016/page/1
 * https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/
 * https://webdevstudios.com/2015/07/09/creating-simple-json-endpoint-wordpress/
 */
function api_publications_by_year($data) {
    $output = array();

    // e.g. 1, 2, 3,...
    $paged = $data['page_number'] ? $data['page_number'] : 1;
    $lang = $data['lang'];
    $year = $data['year'];
    $query_args = array(
        'post_type' => 'publication',
        'post_status' => array('publish'),
        'posts_per_page' => 4,
        // 'post_parent' => 2,
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

    while ($the_query->have_posts()) {
        $the_query->the_post();

        $post_id = get_the_ID();

        // Get the slug.
        // https://wordpress.stackexchange.com/questions/11426/how-can-i-get-page-slug
        $post = get_post($post_id);
        $post_slug = $post->post_name;

        $post_title = translateTitle($post, $lang);
        $post_date = get_the_date('j M Y');
        $post_url = switchLink(get_permalink($post_id), $lang);

        // Get the taxonomy that attached to the post.
        $taxonomy = get_the_terms($post, 'publication-source');

        // Push the post data into the array.
        $output[] = array(
            'id' => "post" . $post_id, // cannot use numbers, or hyphens between words for Zurb data toggle attribute
            'slug' => $post_slug,
            'title' => $post_title,
            'url' => $post_url,
            'date' => $post_date,
            'taxonomy' => $taxonomy
        );
    }

    // Reset the post to the original after loop. otherwise the current page
    // becomes the last item from the while loop.
    wp_reset_query();

    return $output;
}
add_action('rest_api_init', function () {
    register_rest_route('publications/v1', '/lang/(?P<lang>[a-zA-Z0-9-]+)/year/(?P<year>[a-zA-Z0-9-]+)/page/(?P<page_number>\d+)', array(
        'methods' => 'GET',
        'callback' => 'api_publications_by_year',
    ));
});

/**
 * Custom JSON API endpoint.
 * Usage: http://127.0.1.1/repo-github/wordpress-vue-infinite-loading/wp-json/publications/v1/lang/en/keywords/something+amazing/page/1
 * https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/
 * https://webdevstudios.com/2015/07/09/creating-simple-json-endpoint-wordpress/
 */
function api_publications_by_keyword($data) {
    $output = array();

    // add_filter( 'posts_search', '__search_by_title_only', 500, 2 );

    // e.g. 1, 2, 3,...
    $paged = $data['page_number'] ? $data['page_number'] : 1;
    $lang = $data['lang'];
    $keywords = $data['keywords'];
    $query_args = array(
        's' => $keywords,
        'post_type' => 'publication',
        'post_status' => array('publish'),
        'posts_per_page' => 4,
        'paged' => $paged,
        'orderby' => 'date',
    );

    // Create a new instance of WP_Query
    $the_query = new WP_Query($query_args);

    // remove_filter( 'posts_search', '__search_by_title_only', 500 );

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
        $post_date = get_the_date('j M Y');
        $post_url = switchLink(get_permalink($post_id), $lang);

        // Get the taxonomy that attached to the post.
        $taxonomy = get_the_terms($post, 'publication-source');

        // Push the post data into the array.
        $output[] = array(
            'id' => "post" . $post_id, // cannot use numbers, or hyphens between words for Zurb data toggle attribute
            'slug' => $post_slug,
            'title' => $post_title,
            'url' => $post_url,
            'date' => $post_date,
            'taxonomy' => $taxonomy
        );
    }

    // Reset the post to the original after loop. otherwise the current page
    // becomes the last item from the while loop.
    wp_reset_query();

    return $output;
}
add_action('rest_api_init', function () {
    // Include spaces in the search.
    // https://wordpress.stackexchange.com/questions/269149/rest-api-custom-endpoint-with-space-character
    register_rest_route('publications/v1', '/lang/(?P<lang>[a-zA-Z0-9-]+)/keywords/(?P<keywords>([a-zA-Z0-9-\+])+)/page/(?P<page_number>\d+)', array(
        'methods' => 'GET',
        'callback' => 'api_publications_by_keyword',
    ));
});
