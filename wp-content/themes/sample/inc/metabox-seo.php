<?php
/**
 * Add custom metaboxes for SEO.
 * https://wordpress.org/plugins/meta-box/
 * https://docs.metabox.io/quick-start/
 */
add_filter('rwmb_meta_boxes', 'seo_meta_boxes');
function seo_meta_boxes($meta_boxes) {
    // Better has an underscore as last sign
    $prefix = 'seo_meta_';

    $meta_boxes[] = array(
        'title'  => 'SEO Meta',
        'post_types' => array('page', 'post', 'publication', 'video'),
        'fields' => array(
            array(
                'id'   => $prefix . 'title',
                'name' => 'Title',
                'type' => 'textarea',
            ),
            array(
                'id'   => $prefix . 'description',
                'name' => 'Description',
                'type' => 'textarea',
            ),
            array(
                'id'   => $prefix . 'keywords',
                'name' => 'Keywords',
                'type' => 'textarea',
            ),
        ),
    );
    return $meta_boxes;
}
