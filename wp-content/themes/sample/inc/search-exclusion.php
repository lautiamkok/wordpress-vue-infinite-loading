<?php
// Exclude pages from the search result.
// https://www.johnparris.com/exclude-certain-pages-from-wordpress-search-results/
// http://wordpress.stackexchange.com/questions/142811/exclude-pages-from-wordpress-search-result-page
// https://codex.wordpress.org/Plugin_API/Action_Reference/pre_get_posts#Exclude_Pages_from_Search_Results
add_filter( 'pre_get_posts', 'exclude_pages_from_search' );
function exclude_pages_from_search ($query) {
    if ($query->is_search) {
        // Get page by slug.
        // https://codex.wordpress.org/Function_Reference/get_page_by_path
        // http://wordpress.stackexchange.com/questions/102841/how-to-get-page-id-of-a-page-using-page-slug
        $page_slug = 'about';
        $slug_type = 'page';
        $output_type = ARRAY_A;
        $page = get_page_by_path($page_slug, $output_type, $slug_type);

        $ids_parent = array($page['ID']);

        // Get all pages belong to a parent.
        // https://codex.wordpress.org/Function_Reference/get_children
        // http://stackoverflow.com/questions/6137559/wordpress-limit-get-pages-to-show-5-items
        $output_type = ARRAY_A;
        $output_type = OBJECT;
        $args = array(
            // 'numberposts' => 6,
            'order' => 'ASC',
            'post_parent' => $page['ID'],
            'post_status' => 'publish',
            'post_type' => 'page',
        );
        $pages = get_children($args, $output_type);

        $ids_children = array();
        foreach ($pages as $key => $value) {
            $ids_children[] = $key;
        }

        // Merge set 1.
        $set_1 = array_merge($ids_children, $ids_parent);

        // Second set.
        $page_slug = 'services';
        $slug_type = 'page';
        $output_type = ARRAY_A;
        $page = get_page_by_path($page_slug, $output_type, $slug_type);

        $ids_parent = array($page['ID']);

        // Get all pages belong to a parent.
        // https://codex.wordpress.org/Function_Reference/get_children
        // http://stackoverflow.com/questions/6137559/wordpress-limit-get-pages-to-show-5-items
        $output_type = ARRAY_A;
        $output_type = OBJECT;
        $args = array(
            // 'numberposts' => 6,
            'order' => 'ASC',
            'post_parent' => $page['ID'],
            'post_status' => 'publish',
            'post_type' => 'page',
        );
        $pages = get_children($args, $output_type);

        $ids_children = array();
        foreach ($pages as $key => $value) {
            $ids_children[] = $key;
        }

        // Merge set 2.
        $set_2 = array_merge($ids_children, $ids_parent);

        $query->set( 'post__not_in', array_merge($set_1, $set_2));
    }

    return $query;
}
