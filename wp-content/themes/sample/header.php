<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
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

// Get and set page meta.
// https://developer.wordpress.org/reference/functions/get_post_meta/
$meta_title = get_post_meta($post->ID, 'seo_meta_title');
$meta_description = get_post_meta($post->ID, 'seo_meta_description');
$meta_keyword = get_post_meta($post->ID, 'seo_meta_keywords');

// var_dump($post->ID);
// var_dump($meta_title);

// Title
if ($meta_title && count($meta_title) > 0 && !empty($meta_title[0])) {
    $meta_title = $meta_title[0];
} else {
    $meta_title = wp_title('|', false, 'right');
}

// Description.
if ($meta_description && count($meta_description) > 0 && !empty($meta_description[0])) {
    $meta_description = $meta_description[0];
} else {
    $meta_description = get_option('meta_description');
}

// Keywords.
if ($meta_keyword && count($meta_keyword) > 0 && !empty($meta_keyword[0])) {
    $meta_keyword = $meta_keyword[0];
} else {
    $meta_keyword = get_option('meta_keywords');
}
?>

<?php
// Get and set social meta.
$args = array();
$social_title = rwmb_meta('social_meta_title', $args, $post->ID);
$social_description = rwmb_meta('social_meta_description', $args, $post->ID);
$social_image = rwmb_meta('social_meta_image', $args, $post->ID);

// Title.
if (!$social_title) {
    $social_title = $meta_title;
}

// Description.
if (!$social_description) {
    $social_description = $meta_description;
}

// Image.
if ($social_image && count($social_image) > 0 && is_array($social_image)) {
    $image_array = reset($social_image);
    $social_image = $image_array['url'];
} else {
    $social_image = wp_get_attachment_url(get_option('social_meta_image_id'));
}

// Get the permalink of current page.
// https://wordpress.stackexchange.com/questions/274569/how-to-get-url-of-current-page-displayed
global $wp;
$current_url = home_url($wp->request);
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js for-andrew">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- standard meta content -->
    <meta name="description" content="<?php echo $meta_description;?>" />
    <meta name="keywords" content="<?php echo $meta_keyword; ?>" />

    <!-- social meta content -->
    <meta property="og:title" content="<?php echo $social_title; ?>">
    <meta property="og:description" content="<?php echo $social_description;?>">
    <meta property="og:image" content="<?php echo $social_image; ?>"/>
    <meta property="og:url" content="<?php echo $current_url; ?>/">
    <meta name="twitter:card" content="<?php echo $social_description;?>">

    <title><?php echo translateText($meta_title, $lang); ?></title>

    <?php wp_head(); ?>

    <link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favico-16.png" type="image/x-icon" />
</head>

<body <?php body_class(); ?>>

<header class="hide-for-print">

    <!-- row nav -->
    <div class="row row-nav2 hide-for-small-only hide-for-medium-only" data-aos="fade-in" style="position:relative;z-index:3">
        <div class="grid-container">
            <div class="grid-x grid-padding-x align-middle">

                <div class="large-8 medium-8 cell">
                    <nav class="nav-nav2">
                        <ul class="menu align-left menu-mainx dropdown" data-dropdown-menu>
                            <?php
                            // Include the page nav.
                            get_template_part( 'template-parts/nav', 'header' );
                            ?>
                        </ul>
                    </nav>
                </div>

                <!-- cell -->
                <div class="large-4 medium-4 cell">

                    <?php
                    // Include the global search form.
                    get_template_part( 'template-parts/nav', 'search' );
                    ?>

                </div>
                <!-- cell -->

            </div>
        </div>
    </div>
    <!-- row nav -->

</header>
