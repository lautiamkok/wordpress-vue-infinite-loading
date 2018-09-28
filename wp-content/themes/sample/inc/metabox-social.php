<?php
/**
 * Add custom metaboxes for social media sharing.
 * https://wordpress.org/plugins/meta-box/
 * https://docs.metabox.io/quick-start/
 */
add_filter('rwmb_meta_boxes', 'social_meta_boxes');
function social_meta_boxes($meta_boxes) {
    // Better has an underscore as last sign
    $prefix = 'social_meta_';

    $meta_boxes[] = array(
        'title'  => 'Social Meta',
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
            // IMAGE ADVANCED - RECOMMENDED
            array(
                'name' => esc_html__('Image', $prefix),
                'id' => "{$prefix}image",
                'type' => 'image_advanced',

                // Delete image from Media Library when remove it from post meta?
                // Note: it might affect other posts if you use same image for multiple posts
                'force_delete'     => false,

                // Maximum image uploads
                'max_file_uploads' => 1,

                // Display the "Uploaded 1/2 files" status
                'max_status'       => true,

                // Image size that displays in the edit page.
                // 'image_size'       => 'medium',
           ),
        ),
    );
    return $meta_boxes;
}
