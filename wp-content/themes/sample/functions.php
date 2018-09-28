<?php
/**
 * Sample functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Sample
 * @since 1.0
 * @version 1.0
 */

/**
 * Make sure menus is added to the theme.
 * https://wordpress.stackexchange.com/questions/160593/menu-is-not-visible-in-appearance
 */
function sample_theme_setup() {
    register_nav_menus(array(
        'header' => 'Header menu',
        'footer' => 'Footer menu'
    ));
}
add_action('after_setup_theme', 'sample_theme_setup');

/**
 * Inc function - custom meta title.
 *
 */
include 'inc/custom-meta-title.php';

/**
 * Enqueue scripts and styles.
 */
function sample_scripts() {

    // Load our CSS bundle.
    wp_enqueue_style('sample-master-style', get_template_directory_uri() . '/assets/dist/bundle.min.css', array(), '1.0.0');

    // Load style.css.
    wp_enqueue_style('sample-local-style', get_template_directory_uri() . '/style.css', array(), '1.0.0');

    // Load our JS bundle.
    wp_enqueue_script('sample-script', get_template_directory_uri() . '/assets/dist/bundle.min.js', array(), '20150330');
}
add_action('wp_enqueue_scripts', 'sample_scripts');

/**
 * Remove the WP default jquery load.
 * @ref: http://wordpress.stackexchange.com/questions/95699/how-can-i-modify-what-is-being-output-in-wp-head-whether-by-a-theme-or-wordpres
 */
function replace_jquery() {
    if (!is_admin()) {
        wp_deregister_script('jquery');
        // wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js', false, '1.9.1');
        wp_register_script('jquery', '');
        wp_enqueue_script('jquery');
    }
}
add_action('init', 'replace_jquery');

/**
 * Remove the WP default jquery load.
 * @ref: http://www.themelab.com/remove-code-wordpress-header/
 */
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'rest_output_link_wp_head');

/**
 * Metabox - General settings.
 *
 */
include 'metabox/extended-general-settings/functions.php';

/**
 * Metabox - Feature.
 *
 */
// include 'metabox/feature/functions.php';

// Add Excerpts to Your Pages in WordPress.
// http://www.wpbeginner.com/plugins/add-excerpts-to-your-pages-in-wordpress/
// http://www.wpbeginner.com/wp-themes/how-to-display-post-excerpts-in-wordpress-themes/
add_action('init', 'my_add_excerpts_to_pages');
function my_add_excerpts_to_pages() {
    add_post_type_support('video', 'excerpt');
    add_post_type_support('event', 'excerpt');
    add_post_type_support('book', 'excerpt');
}

/**
 * Replaces the default excerpt editor with TinyMCE
 */
// include 'inc/richtext-excerpt.php';

/**
 * Subscribe.
 */
include 'inc/form-subscribe.php';

/**
 * Message.
 */
include 'inc/form-message.php';

/**
 * Comment.
 */
include 'inc/form-comment.php';

/**
 * Exclude pages from search.
 */
include 'inc/search-exclusion.php';

/**
 * SEO metabox.
 */
include 'inc/metabox-seo.php';

/**
 * Social metabox.
 */
include 'inc/metabox-social.php';

/**
 * HTML minifier.
 *
 */
include 'inc/html-minifier.php';

/**
 * APIs.
 *
 */
include 'inc/api-publications.php';
include 'inc/api-speeches.php';
include 'inc/api-videos.php';
include 'inc/api-events.php';
include 'inc/api-books.php';
include 'inc/api-global-search.php';

/**
 * Enable featured image.
 * https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
 * http://www.wpbeginner.com/beginners-guide/how-to-add-featured-image-or-post-thumbnails-in-wordpress/
 */
add_theme_support('post-thumbnails');

/**
 * Add ACF options.
 * https://www.advancedcustomfields.com/resources/options-page/
 */
// if( function_exists('acf_add_options_page') ) {
//     acf_add_options_page();
// }

// Remove array the key that has empty value - recursively.
// https://stackoverflow.com/questions/7696548/php-how-to-remove-empty-entries-of-an-array-recursively
function arrayFilter($array) {
     if(!empty($array)) {
         return array_filter($array);
     }
}

// Create tree menu.
function createTree(array $list, array $parents){
    $tree = array();
    foreach ($parents as $k => $parent){
        if(isset($list[$parent['id']])){
            $parent['children'] = createTree($list, $list[$parent['id']]);
        }
        $tree[] = $parent;
    }
    return $tree;
}

// Enable categories and tags for pages.
// https://stackoverflow.com/questions/14323582/wordpress-how-to-add-categories-and-tags-on-pages
function myplugin_settings() {
    // Add tag metabox to page
    register_taxonomy_for_object_type('post_tag', 'page');
    // Add category metabox to page
    register_taxonomy_for_object_type('category', 'page');
}
 // Add to the admin_init hook of your theme functions.php file
add_action('init', 'myplugin_settings');

// Creates Publications Custom Post Type: publication.
// https://www.elegantthemes.com/blog/tips-tricks/creating-custom-post-types-in-wordpress
// http://wordpress.stackexchange.com/questions/35221/how-to-get-template-drop-down-menu-in-page-attributes-of-custom-post-type
// http://wordpress.stackexchange.com/questions/262280/custom-page-type-template-under-page-attributes
// https://developer.wordpress.org/resource/dashicons/#admin-page
function publications_init() {
    $args = array(
        'labels' => array(
            'name' => __('Publications'),
            'singular_name' => __('Publication'),
            'all_items' => 'All Publications'
        ),
        'public' => true,
        'show_ui' => true,
        'capability_type' => 'page',
        'hierarchical' => true,
        // 'rewrite' => array('slug' => 'Publications'),
        'query_var' => true,
        'menu_icon' => 'dashicons-star-filled',
        'supports' => array(
            'title',
            'editor',
            // 'excerpt',
            // 'trackbacks',
            //'custom-fields',
            'comments',
            'revisions',
            'thumbnail',
            'author',
            'page-attributes',
            ),

        // Adding category & tag.
        // http://www.wpbeginner.com/wp-tutorials/how-to-add-categories-to-a-custom-post-type-in-wordpress/
        // https://wordpress.stackexchange.com/questions/62260/how-to-add-tags-to-custom-post-type
        'taxonomies'  => array(
            //'category',
            // 'post_tag'
            ),
        );
    register_post_type( 'publication', $args );
}
add_action( 'init', 'publications_init' );

// Creates Speeches Custom Post Type: speech.
function speeches_init() {
    $args = array(
        'labels' => array(
            'name' => __('Speeches'),
            'singular_name' => __('Speech'),
            'all_items' => 'All Speeches'
        ),
        'public' => true,
        'show_ui' => true,
        'capability_type' => 'page',
        'hierarchical' => true,
        // 'rewrite' => array('slug' => 'Speeches'),
        'query_var' => true,
        'menu_icon' => 'dashicons-star-filled',
        'supports' => array(
            'title',
            'editor',
            // 'excerpt',
            // 'trackbacks',
            //'custom-fields',
            'comments',
            'revisions',
            'thumbnail',
            'author',
            'page-attributes',
            ),

        // Adding category & tag.
        // http://www.wpbeginner.com/wp-tutorials/how-to-add-categories-to-a-custom-post-type-in-wordpress/
        // https://wordpress.stackexchange.com/questions/62260/how-to-add-tags-to-custom-post-type
        'taxonomies'  => array(
            //'category',
            //'post_tag'
            ),
        );
    register_post_type( 'speech', $args );
}
add_action( 'init', 'speeches_init' );

// Creates Events Custom Post Type: event.
function events_init() {
    $args = array(
        'labels' => array(
            'name' => __('Events'),
            'singular_name' => __('Event'),
            'all_items' => 'All Events'
        ),
        'public' => true,
        'show_ui' => true,
        'capability_type' => 'page',
        'hierarchical' => true,
        // 'rewrite' => array('slug' => 'Events'),
        'query_var' => true,
        'menu_icon' => 'dashicons-star-filled',
        'supports' => array(
            'title',
            'editor',
            // 'excerpt',
            // 'trackbacks',
            //'custom-fields',
            'comments',
            'revisions',
            'thumbnail',
            'author',
            'page-attributes',
            )
        );
    register_post_type( 'event', $args );
}
add_action( 'init', 'events_init' );

// Creates Events Custom Post Type: video.
function videos_init() {
    $args = array(
        'labels' => array(
            'name' => __('Videos'),
            'singular_name' => __('Video'),
            'all_items' => 'All Videos'
        ),
        'public' => true,
        'show_ui' => true,
        'capability_type' => 'page',
        'hierarchical' => true,
        // 'rewrite' => array('slug' => 'Videos'),
        'query_var' => true,
        'menu_icon' => 'dashicons-star-filled',
        'supports' => array(
            'title',
            'editor',
            // 'excerpt',
            // 'trackbacks',
            //'custom-fields',
            'comments',
            'revisions',
            'thumbnail',
            'author',
            'page-attributes',
            ),

        // Adding category & tag.
        // http://www.wpbeginner.com/wp-tutorials/how-to-add-categories-to-a-custom-post-type-in-wordpress/
        // https://wordpress.stackexchange.com/questions/62260/how-to-add-tags-to-custom-post-type
        'taxonomies'  => array(
            //'category',
            //'post_tag'
            ),
        );
    register_post_type( 'video', $args );
}
add_action( 'init', 'videos_init' );

// Creates Events Custom Post Type: book.
function books_init() {
    $args = array(
        'labels' => array(
            'name' => __('Books'),
            'singular_name' => __('Book'),
            'all_items' => 'All Books'
        ),
        'public' => true,
        'show_ui' => true,
        'capability_type' => 'page',
        'hierarchical' => true,
        // 'rewrite' => array('slug' => 'Books'),
        'query_var' => true,
        'menu_icon' => 'dashicons-star-filled',
        'supports' => array(
            'title',
            'editor',
            // 'excerpt',
            // 'trackbacks',
            //'custom-fields',
            'comments',
            'revisions',
            'thumbnail',
            'author',
            'page-attributes',
            )
        );
    register_post_type( 'book', $args );
}
add_action( 'init', 'books_init' );

// Fix 404 on category pagination.
// https://teamtreehouse.com/community/wordpress-pagination-gives-404-unless-i-set-blog-pages-show-at-most-to-1-in-reading
add_action('pre_get_posts', function($q) {
    if(!is_admin() && $q->is_main_query() && !$q->is_tax()) {
        $q->set('post_type', array(
            'post',
            'page',
            'publication',
            'speech',
            'event',
            'book',
            'video',
        ));
    }
});

/**
 * Create a new set of categories for publication.
 * https://codex.wordpress.org/Function_Reference/register_taxonomy
 * https://developer.wordpress.org/reference/functions/register_taxonomy/
 * https://www.wpbeginner.com/wp-tutorials/create-custom-taxonomies-wordpress/
 * https://www.smashingmagazine.com/2012/01/create-custom-taxonomies-wordpress/
 * https://wordpress.stackexchange.com/questions/84921/how-do-i-query-a-custom-post-type-with-a-custom-taxonomy
 */
add_action('init', 'create_publication_categories');
function create_publication_categories() {
    $args = array(
        'label' => __('Categories'),
        'has_archive' =>  true,
        'hierarchical' => true,
        'rewrite' => array(
            'slug' => 'publications',
            'with_front' => false
        ),
    );
    $postTypes = array(
        'publication'
    );
    $taxonomy = 'publication-category';
    register_taxonomy($taxonomy, $postTypes, $args);
}

/**
 * Create a new set of categories (source) for publication.
 */
add_action('init', 'create_publication_source');
function create_publication_source() {
    $args = array(
        'label' => __('Source'),
        'has_archive' =>  true,
        'hierarchical' => true,
        'rewrite' => array(
            'slug' => 'publication-source',
            'with_front' => false
        ),
    );
    $postTypes = array(
        'publication'
    );
    $taxonomy = 'publication-source';
    register_taxonomy($taxonomy, $postTypes, $args);
}

/**
 * Create a new set of categories for speeches.
 */
add_action('init', 'create_speech_categories');
function create_speech_categories() {
    $args = array(
        'label' => __('Categories'),
        'has_archive' =>  true,
        'hierarchical' => true,
        'rewrite' => array(
            'slug' => 'speeches',
            'with_front' => false
        ),
    );
    $postTypes = array(
        'speech'
    );
    $taxonomy = 'speech-category';
    register_taxonomy($taxonomy, $postTypes, $args);
}

/**
 * Create a new set of categories (source) for speech.
 */
add_action('init', 'create_speech_source');
function create_speech_source() {
    $args = array(
        'label' => __('Source'),
        'has_archive' =>  true,
        'hierarchical' => true,
        'rewrite' => array(
            'slug' => 'speech-source',
            'with_front' => false
        ),
    );
    $postTypes = array(
        'speech'
    );
    $taxonomy = 'speech-source';
    register_taxonomy($taxonomy, $postTypes, $args);
}

/**
 * Create a new set of categories for videos.
 */
add_action('init', 'create_video_categories');
function create_video_categories() {
    $args = array(
        'label' => __('Categories'),
        'has_archive' =>  true,
        'hierarchical' => true,
        'rewrite' => array(
            'slug' => 'videos',
            'with_front' => false
        ),
    );
    $postTypes = array(
        'video'
    );
    $taxonomy = 'video-category';
    register_taxonomy($taxonomy, $postTypes, $args);
}

/**
 * Create a new set of categories (source) for video.
 */
add_action('init', 'create_video_source');
function create_video_source() {
    $args = array(
        'label' => __('Source'),
        'has_archive' =>  true,
        'hierarchical' => true,
        'rewrite' => array(
            'slug' => 'video-source',
            'with_front' => false
        ),
    );
    $postTypes = array(
        'video'
    );
    $taxonomy = 'video-source';
    register_taxonomy($taxonomy, $postTypes, $args);
}

/**
 * Create a new set of categories (source) for event.
 */
add_action('init', 'create_event_source');
function create_event_source() {
    $args = array(
        'label' => __('Source'),
        'has_archive' =>  true,
        'hierarchical' => true,
        'rewrite' => array(
            'slug' => 'event-source',
            'with_front' => false
        ),
    );
    $postTypes = array(
        'event'
    );
    $taxonomy = 'event-source';
    register_taxonomy($taxonomy, $postTypes, $args);
}

/**
 * Write search URL.
 * http://wpengineer.com/2258/change-the-search-url-of-wordpress/
 */
function change_search_url_rewrite() {
    if ( is_search() && ! empty( $_GET['s'] ) ) {
        wp_redirect( home_url( "/search/" ) . urlencode( get_query_var( 's' ) ) );
        exit();
    }
}
add_action( 'template_redirect', 'change_search_url_rewrite' );

/**
 * Add new rewrite rule
 */
function create_new_url_querystring() {
    // https://wordpress.stackexchange.com/questions/60173/rewrite-rule-for-multilingual-website-like-qtranslate
    add_rewrite_rule(
        '^cn/?$',
        'index.php?&page_id=2&lang=cn',
        'top'
    );

    add_rewrite_rule(
        '^cn/(.?.+?)(?:/([0-9]+))?/?$',
        'index.php?pagename=$matches[1]&page=$matches[2]&lang=cn',
        'top'
    );

    add_rewrite_rule(
        '^cn/search/(.+)/?$',
        'index.php?s=$matches[1]&lang=cn',
        'top'
    );

    add_rewrite_rule(
        'cn/video/(.+?)(?:/([0-9]+))?/?$',
        'index.php?video=$matches[1]&page=$matches[2]&lang=cn',
        'top'
    );

    add_rewrite_rule(
        'cn/publication/(.+?)(?:/([0-9]+))?/?$',
        'index.php?publication=$matches[1]&page=$matches[2]&lang=cn',
        'top'
    );

    // Tax pages.
    add_rewrite_rule(
        'cn/publications/([^/]+)/?$',
        'index.php?publication-category=$matches[1]&lang=cn',
        'top'
    );

    add_rewrite_rule(
        'cn/speeches/([^/]+)/?$',
        'index.php?speech-category=$matches[1]&lang=cn',
        'top'
    );

    add_rewrite_rule(
        'cn/videos/([^/]+)/?$',
        'index.php?speech-category=$matches[1]&lang=cn',
        'top'
    );
}
add_action('init', 'create_new_url_querystring');

/**
 * Usage: get_query_var('lang');
 */
function query_vars($query_vars) {
    $query_vars[] = "lang";
    return $query_vars;
}
add_filter('query_vars', 'query_vars');

/**
 * Cancel canonical redirect on home page.
 * https://wordpress.stackexchange.com/questions/311476/add-rewrite-rule-to-call-front-page-php
 */
add_action('template_redirect', function() {
    if (is_front_page()) {
        remove_action('template_redirect', 'redirect_canonical');
    }
}, 0);

/**
 * Decide which language to display.
 * @param  [type] $string
 * @param  string $lang
 * @return [type]
 */
function translateText($string, $lang = '') {

    $trimmed = trim($string);
    $array = explode('=', $trimmed);

    // Return the last item.
    if ($lang === 'cn') {
        return trim(end($array));
    }

    // Default.
    return trim(current($array));
}

/**
 * Translate text in an array.
 * @param  [type] $items
 * @param  [type] $search
 * @param  [type] $lang
 * @return [type]
 */
function translateTextInArray($items, $search, $lang) {

    $items = objectToArray($items);

    if (!is_array($items)) {
        return $items;
    }

    foreach($items as $key => $item) {
        $items[$key][$search] = translateText($item[$search], $lang);
    }
    return $items;
}

/**
 * Convert object to array - recursive.
 * @param  [type] $data
 * @return [type]
 */
function objectToArray($data) {
    if(is_array($data) || is_object($data)) {
        $result = array();
        foreach ($data as $key => $value) {
            $result[$key] = objectToArray($value);
        }
        return $result;
    }
    return $data;
}

function switchUrl($url, $lang) {
    // Return the requested language - cn.
    if ($lang === 'cn') {
        return $url . '/cn/';
    }

    // Default.
    return $url . '/';
}

function switchLink($url, $lang) {
    // Return the requested language - cn.
    if ($lang === 'cn') {
        $pattern = "/http:\/\/127.0.0.1\/projects\/sample-wordpress\//";
        $pattern = json_encode(site_url() . '/');
        $replacement = switchUrl(site_url(), $lang);
        $string = $url;
        $url = preg_replace($pattern, $replacement, $string);
    }

    // Default.
    return $url;
}

function switchToChinese($lang) {
    // Get current url.
    // https://kovshenin.com/2012/current-url-in-wordpress/
    global $wp;
    $current_url = home_url($wp->request) . '/';

    $pattern = "/http:\/\/127.0.0.1\/projects\/sample-wordpress\//";
    $pattern = json_encode(site_url() . '/');

    // Replacement.
    $replacement = site_url() . '/cn/';
    if ($lang) {
        $replacement = site_url() . '/';
    }

    $string = $current_url;
    return preg_replace($pattern, $replacement, $string);
}

function switchToEnglish() {
    // Get current url.
    // https://kovshenin.com/2012/current-url-in-wordpress/
    global $wp;
    $current_url = home_url($wp->request) . '/';

    $pattern = "/http:\/\/127.0.0.1\/projects\/sample-wordpress\//";
    $pattern = json_encode(site_url() . '/cn/');

    // Replacement.
    $replacement = site_url() . '/';

    $string = $current_url;
    return preg_replace($pattern, $replacement, $string);
}


function translateTitle($post, $lang = '') {
    // Return the cn version.
    if ($lang === 'cn' && get_field( "title", $post->ID)) {
        return get_field( "title", $post->ID);
    }

    // Default.
    return $post->post_title;
}

function translateContent($post, $lang = '') {

    // Return the cn version.
    if ($lang === 'cn' && get_field( "content", $post->ID)) {
        return get_field( "content", $post->ID);
    }

    // Default.
    return wpautop($post->post_content);
}
/**
 * Flush on the init hook is bad idea. Use it in after_switch_theme instead.
 * https://wordpress.stackexchange.com/questions/311992/404-for-a-custom-taxonomy
 */
add_action('after_switch_theme', 'custom_taxonomy_flush_rewrite');
function custom_taxonomy_flush_rewrite() {
    global $wp_rewrite;
    $wp_rewrite->flush_rules();
}

/**
 * Get posts years.
 * https://wordpress.stackexchange.com/questions/145148/get-list-of-years-when-posts-have-been-published
 */
function get_posts_years($postType = 'post') {
    global $wpdb;
    $result = array();

    // If it is multiple types.
    if (is_array($postType)) {
        $query = "SELECT YEAR(post_date) FROM " . $wpdb->posts . "
                WHERE post_status = 'publish' ";

        $query .= "AND post_type = '" . $postType[0] . "' ";

        array_shift($postType);
        foreach ($postType as $key => $type) {
            $query .= "OR post_type = '" . $type . "' ";
        }

        $query .= "GROUP BY YEAR(post_date) DESC";
    } else {
        $query = "SELECT YEAR(post_date) FROM " . $wpdb->posts . "
            WHERE post_status = 'publish'
            AND post_type = '" . $postType . "'
            GROUP BY YEAR(post_date) DESC";
    }
    $years = $wpdb->get_results(
        $wpdb->prepare(
            $query,
            $wpdb->posts
        ),
        ARRAY_N
    );
    if ( is_array( $years ) && count( $years ) > 0 ) {
        foreach ( $years as $year ) {
            $result[] = $year[0];
        }
    }
    return $result;
}

/**
 * Get the post type by taxonomy.
 * https://wordpress.stackexchange.com/questions/172645/get-the-post-type-a-taxonomy-is-attached-to
 */
function get_post_types_by_taxonomy($tax = 'category') {
    global $wp_taxonomies;
    return ( isset( $wp_taxonomies[$tax] ) ) ? $wp_taxonomies[$tax]->object_type : array();
}

/**
 * Get youtube id.
 * Output: C4kxS1ksqtw
 */
function get_youtube_id($url) {
    // $url = "http://www.youtube.com/watch?v=C4kxS1ksqtw&feature=relate";
    parse_str(parse_url($url, PHP_URL_QUERY ), $arrayVars);
    return $arrayVars['v'];
    // Output: C4kxS1ksqtw
}

/**
 * Create excerpt.
 * https://gist.github.com/wpscholar/8363040
 * @param string $content The content to be transformed
 * @param int    $length  The number of words
 * @param string $more    The text to be displayed at the end, if shortened
 * @return string
 */
function makeExcerpt( $content, $length = 40, $more = '...' ) {
    $excerpt = strip_tags( trim( $content ) );
    $words = str_word_count( $excerpt, 2 );
    if ( count( $words ) > $length ) {
        $words = array_slice( $words, 0, $length, true );
        end( $words );
        $position = key( $words ) + strlen( current( $words ) );
        $excerpt = substr( $excerpt, 0, $position ) . $more;
    }
    return $excerpt;
}


/**
 * Clean up search keywords.
 * https://stackoverflow.com/questions/11330480/strip-php-variable-replace-white-spaces-with-dashes
 * https://stackoverflow.com/questions/2368539/php-replacing-multiple-spaces-with-a-single-space
 * @param  [type] $string
 * @return [type]
 */
function cleanUpKeywords($string, $symbol = '+') {
    //Lower case everything
    $string = strtolower($string);

    //Make alphanumeric (removes all other characters)
    $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);

    //Clean up multiple dashes or whitespaces
    $string = preg_replace("/[\s-]+/", " ", $string);

    //Convert whitespaces and underscore to dash
    $string = preg_replace("/[\s_]/", $symbol, $string);

    return $string;
}
