<?php
// Subscribe form.
// http://www.sutanaryan.com/wordpress-custom-registration-without-using-a-plugin/
// https://wordpress.stackexchange.com/questions/247531/create-register-form-without-a-plugin
if(!function_exists('email_subscription')) {
    add_action('init', 'email_subscription');
    function email_subscription() {
        $err = '';
        $success = '';
        global $wpdb;

        if(isset($_POST['task']) && $_POST['task'] == 'register') {
            $email = $wpdb->escape(trim($_POST['subscriber-email']));

            if($email == "") {
                $err = 'Please don\'t leave the required fields.';
            } else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $err = 'Invalid email address.';
            } else if(email_exists($email) ) {
                $err = 'Email already exist.';
            } else {

                // Make user_login unique so WP will not return error.
                // https://wordpress.stackexchange.com/questions/167554/how-to-override-wordpress-registration-and-insert-an-auto-generated-username
                // $check = username_exists($email);

                $user_id = wp_insert_user(
                    array (
                        // 'first_name' => apply_filters('pre_user_first_name', $first_name),
                        // 'last_name' => apply_filters('pre_user_last_name', $last_name),
                        // 'user_pass' => apply_filters('pre_user_user_pass', $pwd1),
                        // 'user_login' => apply_filters('pre_user_user_login', $username),
                        // 'user_pass' => wp_generate_password(),
                        'user_pass'   =>  NULL,
                        'user_login' => apply_filters('pre_user_user_email', $email),
                        'user_email' => apply_filters('pre_user_user_email', $email),
                        'role' => 'subscriber'
                        )
                    );
                if(is_wp_error($user_id)) {
                    $err = 'Error on user creation.';
                } else {
                    do_action('user_register', $user_id);
                    $success = 'You\'re successfully registered!';
                }
            }

            // Return json data instead.
            // https://stackoverflow.com/questions/31205447/why-wordpress-ajax-request-returns-the-entire-html-page-instead-of-json-result
            if(!empty($err)) {
                $output = array(
                    'status' => 'error',
                    'message'=> $err
                );
            }
            if(!empty($success)) {
                $output = array(
                    'status' => 'ok',
                    'message'=> $success
                );
            }
            wp_send_json($output);
            die();
        }
    }
}
