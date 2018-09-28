<?php
/**
 * Adds a meta box to the post editing screen - everywhere on the page/post.
 *
 * @ usage in any your home page template:
 *
 * <?php
        // Retrieves the stored value from the database
        $meta_title = get_post_meta(get_the_ID(), 'java_meta_title', true);
        $meta_feature = get_post_meta(get_the_ID(), 'java_meta_feature', true);

        // Checks and displays the retrieved value
        if(!empty($meta_title)) {
            echo $meta_title;
            echo $meta_feature;
        }
    ?>
 * @ http://themefoundation.com/wordpress-meta-boxes-guide/
 */

/**
 * Add custom meta box to a specific page in the WP admin.
 *
 * @ http://themefoundation.com/wordpress-meta-boxes-guide/
 * @ http://www.farinspace.com/page-specific-wordpress-meta-box/
 */
function feature_meta_init() {

    // Get post/page ID.
    $post_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'] ;

    // Get post/page slug.
    $post = get_post($post_id);
    $slug = $post->post_name;
    $type = $post->post_type;


    // checks for post/page slug.
    if ($type == 'post') {
        add_meta_box('meta_feature', __('Feature', 'feature-meta'), 'feature_meta_callback', array('post', 'page'));
    }
    add_action('add_metaboxe_feature', 'meta_feature');
}
add_action('admin_init','feature_meta_init');

/**
 * Outputs the content of the meta box.
 */
function feature_meta_callback($post) {
    // Better has an underscore as last sign.
    $prefix = 'java_';

    wp_nonce_field(basename(__FILE__), 'feature_meta_nonce');
    $stored_meta = get_post_meta($post->ID);
    // print_r($stored_meta);
    ?>
    <div>
        <div class="form-group">
            <div class="">
                <input type="checkbox" name="<?php echo $prefix; ?>meta_feature" id="<?php echo $prefix; ?>meta_feature"<?php if (isset($stored_meta["{$prefix}meta_feature"]) && $stored_meta["{$prefix}meta_feature"][0] === 'on') {?> checked="checked"<?php }?> />Feature?
            </div>
        </div>
    </div>
    <?php
}

/**
 * Saves the custom meta input.
 */
function meta_feature ($post_id) {
    // Better has an underscore as last sign.
    $prefix = 'java_';

    // Checks save status
    $is_autosave = wp_is_post_autosave($post_id);
    $is_revision = wp_is_post_revision($post_id);
    $is_valid_nonce = (isset($_POST[ 'feature_meta_nonce' ]) && wp_verify_nonce($_POST[ 'feature_meta_nonce' ], basename(__FILE_))) ? 'true' : 'false';

    // Exits script depending on save status
    if ($is_autosave || $is_revision || !$is_valid_nonce) {
        return;
    }

    // Checks for input and sanitizes/saves if needed
    if( isset( $_POST[ "{$prefix}meta_feature" ] ) && $_POST[ "{$prefix}meta_feature" ] === 'on') {
        update_post_meta( $post_id, "{$prefix}meta_feature", sanitize_text_field( $_POST[ "{$prefix}meta_feature" ] ) );
    } else {
        delete_post_meta($post_id, "{$prefix}meta_feature");
    }
}
add_action('save_post', 'meta_feature');
