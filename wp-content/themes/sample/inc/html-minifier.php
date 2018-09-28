<?php
/**
 * MINIFY JAVASCRIPT AND HTML FOR WORDPRESS WITHOUT A PLUGIN.
 * http://setuix.com/minify-javascript-html-wordpress-without-plugin/
 */
class WP_HTML_Compression
{
    // Settings
    protected $info_comment = true;

    // Variables
    protected $html;

    public function __construct($html)
    {
        if (!empty($html)) {
            $this->parseHTML($html);
        }
    }

    public function __toString()
    {
        return $this->html;
    }

    protected function bottomComment($raw, $compressed)
    {
        $raw = strlen($raw);
        $compressed = strlen($compressed);

        $savings = ($raw-$compressed) / $raw * 100;
        $savings = round($savings, 2);

        return '<!--HTML compressed, size saved '.$savings.'%. From '.$raw.' bytes, now '.$compressed.' bytes-->';
    }

    /**
     * Use the following snippet to remove white-spaces from the HTML with the help ob_start's buffer:
     * https://gist.github.com/Rodrigo54/93169db48194d470188f
     */
    protected function minifyHTML($input)
    {
        if(trim($input) === "") return $input;

        // Remove extra white-space(s) between HTML attribute(s)
        $input = preg_replace_callback('#<([^\/\s<>!]+)(?:\s+([^<>]*?)\s*|\s*)(\/?)>#s', function($matches) {
            return '<' . $matches[1] . preg_replace('#([^\s=]+)(\=([\'"]?)(.*?)\3)?(\s+|$)#s', ' $1$2', $matches[2]) . $matches[3] . '>';
        }, str_replace("\r", "", $input));

        // Skip minifying inline CSS declaration(s)
        if(strpos($input, ' style=') !== false) {
            $input = preg_replace_callback('#<([^<]+?)\s+style=([\'"])(.*?)\2(?=[\/\s>])#s', function($matches) {
                return '<' . $matches[1] . ' style=' . $matches[2] . $matches[3] . $matches[2];
            }, $input);
        }
        if(strpos($input, '</style>') !== false) {
          $input = preg_replace_callback('#<style(.*?)>(.*?)</style>#is', function($matches) {
            return '<style' . $matches[1] .'>'. $matches[2] . '</style>';
          }, $input);
        }

        // Skip minifying inline JS declaration(s)
        if(strpos($input, '</script>') !== false) {
          $input = preg_replace_callback('#<script(.*?)>(.*?)</script>#is', function($matches) {
            return '<script' . $matches[1] .'>'. $matches[2] . '</script>';
          }, $input);
        }

        // Return minified HTML.
        return preg_replace(
            array(
                // t = text
                // o = tag open
                // c = tag close
                // Keep important white-space(s) after self-closing HTML tag(s)
                '#<(img|input)(>| .*?>)#s',
                // Remove a line break and two or more white-space(s) between tag(s)
                '#(<!--.*?-->)|(>)(?:\n*|\s{2,})(<)|^\s*|\s*$#s',
                '#(<!--.*?-->)|(?<!\>)\s+(<\/.*?>)|(<[^\/]*?>)\s+(?!\<)#s', // t+c || o+t
                '#(<!--.*?-->)|(<[^\/]*?>)\s+(<[^\/]*?>)|(<\/.*?>)\s+(<\/.*?>)#s', // o+o || c+c
                '#(<!--.*?-->)|(<\/.*?>)\s+(\s)(?!\<)|(?<!\>)\s+(\s)(<[^\/]*?\/?>)|(<[^\/]*?\/?>)\s+(\s)(?!\<)#s', // c+t || t+o || o+t -- separated by long white-space(s)
                '#(<!--.*?-->)|(<[^\/]*?>)\s+(<\/.*?>)#s', // empty tag
                '#<(img|input)(>| .*?>)<\/\1>#s', // reset previous fix
                '#(&nbsp;)&nbsp;(?![<\s])#', // clean up ...
                '#(?<=\>)(&nbsp;)(?=\<)#', // --ibid
                // Remove HTML comment(s) except IE comment(s)
                '#\s*<!--(?!\[if\s).*?-->\s*|(?<!\>)\n+(?=\<[^!])#s'
            ),
            array(
                '<$1$2</$1>',
                '$1$2$3',
                '$1$2$3',
                '$1$2$3$4$5',
                '$1$2$3$4$5$6$7',
                '$1$2$3',
                '<$1$2',
                '$1 ',
                '$1',
                ""
            ),
        $input);
    }

    public function parseHTML($html)
    {
        $this->html = $this->minifyHTML($html);

        if ($this->info_comment) {
            $this->html .= "\n" . $this->bottomComment($html, $this->html);
        }
    }
}

function wp_html_compression_finish($html)
{
    return new WP_HTML_Compression($html);
}

function wp_html_compression_start()
{
    ob_start('wp_html_compression_finish');
}

// Minifiy html only if the user is not logged in.
// https://developer.wordpress.org/reference/functions/is_user_logged_in/
if (!is_user_logged_in()) {
    add_action('get_header', 'wp_html_compression_start');
}
