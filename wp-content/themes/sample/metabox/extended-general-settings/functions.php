<?php
/**
 * Add more input fields in general settings.
 */
add_action('admin_init', 'extended_general_settings');
function extended_general_settings() {
    add_settings_section(
        'other_site_details', // Section ID
        'Other Site Details', // Section Title
        'extended_general_settings_description_callback', // Callback
        'general' // What Page?  This makes the section show up on the General Settings Page
    );

    add_settings_field( // Content
        'meta_description', // Option ID
        'Meta Description', // Label
        'extended_generals_setting_textarea_callback', // !important - This is where the args go!
        'general', // Page it will be displayed (General Settings)
        'other_site_details', // Name of our section
        array( // The $args
            'meta_description' // Should match Option ID
        )
    );

    add_settings_field( // Keywords
        'meta_keywords', // Option ID
        'Meta Keywords', // Label
        'extended_generals_setting_textarea_callback', // !important - This is where the args go!
        'general', // Page it will be displayed (General Settings)
        'other_site_details', // Name of our section
        array( // The $args
            'meta_keywords' // Should match Option ID
        )
    );

    add_settings_field( // Telephone
        'telephone', // Option ID
        'Telephone', // Label
        'extended_general_settings_textbox_callback', // !important - This is where the args go!
        'general', // Page it will be displayed (General Settings)
        'other_site_details', // Name of our section
        array( // The $args
            'telephone' // Should match Option ID
        )
    );

    add_settings_field( // Email
        'email', // Option ID
        'Email', // Label
        'extended_general_settings_textbox_callback', // !important - This is where the args go!
        'general', // Page it will be displayed
        'other_site_details', // Name of our section (General Settings)
        array( // The $args
            'email' // Should match Option ID
        )
    );

    add_settings_field( // Address
        'address', // Option ID
        'Address', // Label
        'extended_generals_setting_textarea_callback', // !important - This is where the args go!
        'general', // Page it will be displayed (General Settings)
        'other_site_details', // Name of our section
        array( // The $args
            'address' // Should match Option ID
        )
    );

    add_settings_field( // Opening Hours
        'opening_hours', // Option ID
        'Opening Hours', // Label
        'extended_generals_setting_textarea_callback', // !important - This is where the args go!
        'general', // Page it will be displayed
        'other_site_details', // Name of our section (General Settings)
        array( // The $args
            'opening_hours' // Should match Option ID
        )
    );

    add_settings_field( // Copyright
        'copyright', // Option ID
        'Copyright', // Label
        'extended_generals_setting_textarea_callback', // !important - This is where the args go!
        'general', // Page it will be displayed
        'other_site_details', // Name of our section (General Settings)
        array( // The $args
            'copyright' // Should match Option ID
        )
    );

    add_settings_field( // additional 1
        'additional_1', // Option ID
        'Additional 1', // Label
        'extended_generals_setting_textarea_callback', // !important - This is where the args go!
        'general', // Page it will be displayed
        'other_site_details', // Name of our section (General Settings)
        array( // The $args
            'additional_1' // Should match Option ID
        )
    );


    add_settings_field( // additional 1
        'social_meta_image_id', // Option ID
        'Social Image', // Label
        'media_selector_settings_page_callback', // !important - This is where the args go!
        'general', // Page it will be displayed
        'other_site_details', // Name of our section (General Settings)
        array( // The $args
            'social_meta_image_id' // Should match Option ID
        )
    );

    register_setting('general','meta_description', 'esc_attr');
    register_setting('general','meta_keywords', 'esc_attr');
    register_setting('general','telephone', 'esc_attr');
    register_setting('general','email', 'esc_attr');
    register_setting('general','address', 'esc_attr');
    register_setting('general','opening_hours', 'esc_attr');
    register_setting('general','copyright', 'esc_attr');
    register_setting('general','additional_1', 'esc_attr');
    register_setting('general','social_meta_image_id', 'esc_attr');
}

function extended_general_settings_description_callback() { // Section Callback
    echo '<p>Add additional site info below here:</p>';
}

function extended_general_settings_textbox_callback($args) {  // Textbox Callback
    $option = get_option($args[0]);
    echo '<input type="text" id="'. $args[0] .'" name="'. $args[0] .'" value="' . $option . '" class="regular-text ltr"/>';
}

function extended_generals_setting_textarea_callback($args) {  // Textbox Callback
    $option = get_option($args[0]);
    echo '<textarea rows="6" cols="40" id="'. $args[0] .'" name="'. $args[0] .'" class="regular-text ltr">' . $option . '</textarea>';
}

// Add media selector.
// https://stackoverflow.com/questions/48315755/how-to-add-media-selector-to-add-settings-field-in-wordpress
// http://jeroensormani.com/how-to-include-the-wordpress-media-selector-in-your-plugin/
function media_selector_settings_page_callback($args) {
    $option = get_option($args[0]);
    wp_enqueue_media();
    ?>

    <input id="upload_image_button" type="button" class="button" value="<?php _e( 'Upload image' ); ?>" />
    <input type='hidden' name='<?php echo $args[0]; ?>' id='image_attachment_id' value='<?php echo $option; ?>'>

    <?php
    // Get the image id.
    $image = wp_get_attachment_url($option);
    if ($image === false) {
        $image = 'http://via.placeholder.com/500x300/cccccc/ffffff';
    }
    ?>
    <div class='image-preview-wrapper' style="margin-top: 10px;">
        <img id='image-preview' src='<?php echo $image; ?>' height='100'>
    </div>
    <?php
}

add_action( 'admin_footer', 'media_selector_print_scripts' );
function media_selector_print_scripts() {
    $my_saved_attachment_post_id = get_option( 'media_selector_attachment_id', 0 );
    ?><script type='text/javascript'>
        jQuery( document ).ready( function( $ ) {
            // Uploading files
            // Make sure wp.media has data.
            if (wp.media !== undefined) {
                var file_frame;
                var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
                var set_to_post_id = <?php echo $my_saved_attachment_post_id; ?>; // Set this
                jQuery('#upload_image_button').on('click', function( event ){
                    event.preventDefault();
                    // If the media frame already exists, reopen it.
                    if ( file_frame ) {
                        // Set the post ID to what we want
                        file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
                        // Open frame
                        file_frame.open();
                        return;
                    } else {
                        // Set the wp.media post id so the uploader grabs the ID we want when initialised
                        wp.media.model.settings.post.id = set_to_post_id;
                    }
                    // Create the media frame.
                    file_frame = wp.media.frames.file_frame = wp.media({
                        title: 'Select a image to upload',
                        button: {
                            text: 'Use this image',
                        },
                        multiple: false // Set to true to allow multiple files to be selected
                    });
                    // When an image is selected, run a callback.
                    file_frame.on( 'select', function() {
                        // We set multiple to false so only get one image from the uploader
                        attachment = file_frame.state().get('selection').first().toJSON();
                        // Do something with attachment.id and/or attachment.url here
                        $( '#image-preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
                        $( '#image_attachment_id' ).val( attachment.id );
                        // Restore the main post ID
                        wp.media.model.settings.post.id = wp_media_post_id;
                    });
                        // Finally, open the modal
                        file_frame.open();
                });
                // Restore the main ID when the add media button is pressed
                jQuery( 'a.add_media' ).on( 'click', function() {
                    wp.media.model.settings.post.id = wp_media_post_id;
                });
            }
        });
    </script><?php
}
