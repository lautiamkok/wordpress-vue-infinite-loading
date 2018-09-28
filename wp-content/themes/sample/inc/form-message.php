<?php
// Contact form.
if(!function_exists('send_message')) {
    add_action('init', 'send_message');
    function send_message() {
        if(isset($_POST['task']) && $_POST['task'] == 'message') {
            // Google recaptcha integration.
            // https://developers.google.com/recaptcha/intro
            // https://github.com/google/recaptcha
            // https://github.com/google/recaptcha/blob/master/examples/example-captcha.php
            // https://codeforgeek.com/2014/12/google-recaptcha-tutorial/
            // https://developers.google.com/recaptcha/
            // http://www.codedodle.com/2014/12/google-new-recaptcha-using-javascript.html
            // https://y-designs.com/blog/google-recaptcha-v2-on-a-dynamic-page/

            // Construct the Google verification API request link.
            $params = array();
            $params['secret'] = '6Lcwa0cUAAAAABpqjQVAo3Qw-V5dnGxAJn5zHT6d'; // Secret key
            if (!empty($_POST) && isset($_POST['g-recaptcha-response'])) {
                $params['response'] = urlencode($_POST['g-recaptcha-response']);
            }
            $params['remoteip'] = $_SERVER['REMOTE_ADDR'];

            $params_string = http_build_query($params);
            $requestURL = 'https://www.google.com/recaptcha/api/siteverify?' . $params_string;

            // Get cURL resource
            $curl = curl_init();

            // Set some options
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $requestURL,
            ));

            // Send the request
            $response = curl_exec($curl);
            // Close request to clear up some resources
            curl_close($curl);

            // If it is false, stop here.
            $response = @json_decode($response, true);

            $status = 'failed';
            if ($response["success"] === false) {
                $result = array(
                    'status' => $status,
                    'message' => 'Your captcha verification failed.'
                );
                 wp_send_json($result);
            }

            // Integrate with Contact Form 7.
            $contact_form = wpcf7_contact_form((int) $_POST['_wpcf7']);
            if ($contact_form) {
                $result = $contact_form->submit();
                wp_send_json($result);
            }
            die();
        }
    }
}

/**
 * Disable Contact Form 7 JS and CSS.
 * https://contactform7.com/loading-javascript-and-stylesheet-only-when-it-is-necessary/
 */
add_filter('wpcf7_load_js', '__return_false');
add_filter('wpcf7_load_css', '__return_false');
