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
// Get the menu items by menu name.
$menu_items = wp_get_nav_menu_items('nav-header');

// Get ancestor's slug.
global $post;

// Get current template file.
// http://wordpress.stackexchange.com/questions/10537/get-name-of-the-current-template-file
global $template;

// When any single Post page is being displayed.
// https://codex.wordpress.org/Conditional_Tags
// https://developer.wordpress.org/reference/functions/is_single/
// https://developer.wordpress.org/reference/functions/is_page/
if (is_single() || basename($template) === 'index.php') {
    // Get blog id/ slug globally.
    // @ref: http://stackoverflow.com/questions/35645803/wordpress-how-to-get-postss-parent-page
    $blog_id = get_option('page_for_posts');
    $parent_url = get_permalink($blog_id);
} elseif (is_page()) { // When any single Page is being displayed.
    // Get ancestor's id
    $parent_id = get_ancestors($post->ID, 'page');
    $parent_url = get_permalink(reset($parent_id));
}

// Get current url.
// https://kovshenin.com/2012/current-url-in-wordpress/
global $wp;
$current_url = home_url($wp->request) . '/';

// print_r($menu_items);

$items = array();
if ($menu_items) {
    foreach ($menu_items as $key => $menu_item) {
        $url = $menu_item->url;
        if ($lang) {
            $pattern = "/http:\/\/127.0.0.1\/projects\/andrew-wordpress\//";
            $pattern = json_encode(site_url() . '/');
            $replacement = switchUrl(site_url(), $lang);
            $string = $menu_item->url;
            $url = preg_replace($pattern, $replacement, $string);
        }

        // Store what you need.
        $item = array(
            'id' => $menu_item->ID,
            'title' => $menu_item->title,
            'url' => $url,
            'parent_id' => $menu_item->menu_item_parent,
        );
        array_push($items, $item);
    }
}

$groupByParent = array();
if (count($items) > 0) {
    foreach ($items as $item){
        $groupByParent[$item['parent_id']][] = $item;
    }
    $parents = $groupByParent[0];
    $tree = createTree($groupByParent, $parents);
}

// Get requested language.
$lang = get_query_var('lang');
?>

<?php if (count($items) > 0) { ?>
    <?php foreach ($tree as $key => $menu_item) { ?>
    <?php
    $classes = [];
    if ($menu_item['url'] == $current_url || $menu_item['url'] == $parent_url) {
        array_push($classes, "current");
    }

    if ($post->ancestors) {
        $parent = get_post(end($post->ancestors));
        $parent_slug = $parent->post_name;
    }

    if ($parent_slug === strtolower($menu_item['title'])) {
        array_push($classes, "current");
    }

    $array = array_filter(explode('/', $menu_item['url']));
    $end = end($array);
    if ($parent_slug === $end) {
        array_push($classes, "current");
    }

    // Get the requested category.
    $tax = $wp_query->get_queried_object();
    $requestedTax = get_post_types_by_taxonomy($tax->taxonomy);
    if ('speeches' === $end && current($requestedTax) === 'speech') {
        array_push($classes, "current");
    }

    if ('publications' === $end && current($requestedTax) === 'publication') {
        array_push($classes, "current");
    }

    if ('speeches' === $end && $post->post_type === 'speech') {
        array_push($classes, "current");
    }

    // Get post id from the menu url.
    // https://developer.wordpress.org/reference/functions/url_to_postid/
    $id = url_to_postid($menu_item['url']);
    $postById = get_post($id);
    if ($postById->post_name === 'videos' && $post->post_type === 'video') {
        array_push($classes, "current");
    }
    if ($postById->post_name === 'publications' && $post->post_type === 'publication') {
        array_push($classes, "current");
    }
    ?>
    <li<?php if (count($classes) > 0) {?> class="<?php echo implode(' ', $classes);?>"<?php } ?> id="li<?php echo $menu_item['title'];?>"><a href="<?php echo $menu_item['url'];?>"><?php echo translateText ($menu_item['title'], $lang);?><?php if ('speeches' === $end || 'publications' === $end) {?><i class="material-icons mi-arrow-down">keyboard_arrow_down</i><?php } ?></a>

        <?php if ('speeches' === $end) {?>
            <ul class="nested vertical menu menu-sub children">
            <?php
            // Include the page nav.
            get_template_part('template-parts/nav', 'categories-speech');
            ?>
            </ul>

        <?php } ?>

        <?php if ('publications' === $end) {?>
            <ul class="nested vertical menu menu-sub children">
            <?php
            // Include the page nav.
            get_template_part('template-parts/nav', 'categories-publication');
            ?>
            </ul>

        <?php } ?>

    </li>
    <?php } ?>
<?php } ?>
