<?php
/**
 * Create custom meta title for static pages.
 *
 * http://stackoverflow.com/questions/9055009/wordpress-wp-title-blank-on-index-page
 */
function custom_meta_title( $title, $sep ) {
    global $paged, $page;

    if ( is_feed() ) {
        return $title;
    }

    // Add the site name.
    $title .= get_bloginfo( 'name', 'display' );

    // Add the site description for the home/front page.
    $site_description = get_bloginfo( 'description', 'display' );
    if ( $site_description && ( is_home() || is_front_page() ) ) {
        $title = "$title $sep $site_description";
    }

    // Add a page number if necessary.
    if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
        $title = "$title $sep " . sprintf( __( 'Page %s', 'angel' ), max( $paged, $page ) );
    }

    return $title;
}
add_filter( 'wp_title', 'custom_meta_title', 10, 2 );
