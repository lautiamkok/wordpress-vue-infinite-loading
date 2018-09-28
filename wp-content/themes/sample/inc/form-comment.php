<?php
// Message/ comment form.
// https://codex.wordpress.org/Function_Reference/wp_insert_comment
// https://codex.wordpress.org/Function_Reference/wp_new_comment
if(!function_exists('insert_comment')) {
    add_action('init', 'insert_comment');
    function insert_comment() {
        $err = '';
        $success = '';
        global $wpdb, $post, $current_user;

        if(isset($_POST['task']) && $_POST['task'] == 'comment') {
            $author = $wpdb->escape(trim($_POST['author']));
            $email = $wpdb->escape(trim($_POST['email']));
            // $phone = $wpdb->escape(trim($_POST['phone']));
            $comment = $wpdb->escape(trim($_POST['comment']));
            $post_id = $wpdb->escape(trim($_POST['post_id']));

            if(
                $author == "" &&
                $email == "" &&
                $comment = ""
            ) {
                $err = 'Please don\'t leave the required fields.';
            } else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $err = 'Invalid email address.';
            } else {
                $time = current_time('mysql');
                $data = array(
                    'comment_post_ID' => $post_id,
                    'comment_author' => $author,
                    'comment_author_email' => $email,
                    'comment_author_url' => '',
                    'comment_content' => $comment,
                    'comment_type' => '',
                    'comment_parent' => 0,
                    'user_id' => '',
                    'comment_author_IP' => get_client_ip(),
                    'comment_agent' => '',
                    'comment_date' => $time,
                    'comment_approved' => 0,
                );

                $comment_id = wp_insert_comment($data);
                if(is_wp_error($comment_id)) {
                    $err = 'Error on message creation.';
                } else {
                    // Add additonal comment meta data.
                    // add_comment_meta($comment_id, 'phone', $phone);
                    $success = 'It is sent successfully!';
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

// Function to get the client IP address
// https://stackoverflow.com/questions/15699101/get-the-client-ip-address-using-php
function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
add_action('init', 'get_client_ip');
