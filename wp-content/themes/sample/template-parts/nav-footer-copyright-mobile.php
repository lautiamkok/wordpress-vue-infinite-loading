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
$menu_items = wp_get_nav_menu_items('nav-footer-copyright');

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

$items = array();
if ($menu_items) {
    foreach ($menu_items as $key => $menu_item) {

        // Store what you need.
        $item = array(
            'id' => $menu_item->ID,
            'title' => $menu_item->title,
            'url' => $menu_item->url,
            'children' => array()
        );

        // Item does not have a parent so menu_item_parent equals 0 (false).
        if (!$menu_item->menu_item_parent) {
            // Push the item to the array.
            array_push($items, $item);
        }

        // Item that has a parent.
        if ($menu_item->menu_item_parent) {
            // Search key by column 'id'.
            $key = array_search($menu_item->menu_item_parent, array_column($items, 'id'));

            // Push sub item to the children array.
            array_push($items[$key]['children'], $item);
        }
    }
}

// Get requested language.
$lang = get_query_var('lang');
?>

<?php if (count($items) > 0) { ?>
    <?php foreach ($items as $key => $menu_item) { ?>
    <?php
    $classes = array();
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
    ?>
    <li<?php if (count($classes) > 0){ ?> class="<?php echo implode(' ', array_unique($classes)); ?>"<?php } ?>><a href="<?php echo $menu_item['url'];?>"><span class="bold propagate"><?php echo translateText($menu_item['title'], $lang);?></span></a>
    </li>
    <?php } ?>
<?php } ?>
