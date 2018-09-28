<?php
/**
 * The template used for displaying the filter
 *
 * @package WordPress
 * @subpackage Andrew
 * @since 1.0
 * @version 1.0
 */
?>

<?php
// Get the menu items by menu name.
$types = wp_get_nav_menu_items('nav-header');

// Get requested language.
$lang = get_query_var('lang');
?>

<?php if (count($types) > 0) { ?>
    <?php foreach ($types as $key => $type) { ?>
    <?php
    $array = array_filter(explode('/', $type->url));
    $end = end($array);

    if ($end === 'speeches') {
        $end = 'speech';
    } else {
        $end = substr($end, 0, -1);
    }
    ?>
    <li><a href="#" data-cat="<?php echo $end; ?>" v-on:click.prevent="filterByCat"><?php echo translateText($type->title, $lang); ?></a></li>
    <?php } ?>
<?php } ?>
