<?php

/**
 * HTML, CSS, and JS Minification functionality
 *
 * @package WordPress-Starter
 */

if (!defined('ABSPATH')) {
    exit;
}

class HTML_Minifier
{
    /**
     * Initialize the minifier
     */
    public static function init()
    {
        add_action('template_redirect', [self::class, 'start_buffer']);
        add_action('wp_footer', [self::class, 'end_buffer']);
        add_filter('style_loader_src', [self::class, 'minify_css_js_urls'], 10, 2);
        add_filter('script_loader_src', [self::class, 'minify_css_js_urls'], 10, 2);
    }

    /**
     * Minify HTML content
     *
     * @param string $buffer The HTML content to minify
     * @return string The minified HTML content
     */
    public static function minify($buffer)
    {
        if (!self::should_minify()) {
            return $buffer;
        }

        // Minify HTML
        $buffer = self::minify_html($buffer);

        // Minify inline CSS
        $buffer = preg_replace_callback('/<style\b[^>]*>(.*?)<\/style>/is', function ($matches) {
            return '<style>' . self::minify_css($matches[1]) . '</style>';
        }, $buffer);

        // Minify inline JavaScript
        $buffer = preg_replace_callback('/<script\b([^>]*)>(.*?)<\/script>/is', function ($matches) {
            $attributes = $matches[1];
            $content = self::minify_js($matches[2]);
            return '<script ' . $attributes . '>' . $content . '</script>';
        }, $buffer);

        return trim($buffer);
    }

    /**
     * Minify HTML
     */
    private static function minify_html($buffer)
    {
        $search = [
            '/\>[^\S ]+/s',     // Remove whitespace after tags
            '/[^\S ]+\</s',     // Remove whitespace before tags
            '/(\s)+/s',         // Remove multiple whitespace sequences
            '/<!--(.|\s)*?-->/' // Remove HTML comments
        ];

        $replace = [
            '>',
            '<',
            '\\1',
            ''
        ];

        return preg_replace($search, $replace, $buffer);
    }

    /**
     * Minify CSS
     */
    private static function minify_css($css)
    {
        // Remove comments
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        // Remove whitespace
        $css = str_replace(["\r\n", "\r", "\n", "\t", '  ', '    ', '    '], '', $css);
        // Remove unnecessary semicolons
        $css = str_replace(';}', '}', $css);

        return trim($css);
    }

    /**
     * Minify JavaScript
     */
    private static function minify_js($js)
    {
        // Remove comments (both single-line and multi-line)
        $js = preg_replace('/((?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:\/\/.*))/', '', $js);
        // Remove whitespace
        $js = preg_replace('/\s+/', ' ', $js);

        return trim($js);
    }

    /**
     * Minify CSS and JS URLs
     */
    public static function minify_css_js_urls($src, $handle)
    {
        if (!self::should_minify()) {
            return $src;
        }

        // Add version to prevent caching issues
        return add_query_arg('ver', wp_get_theme()->get('Version'), $src);
    }

    /**
     * Start output buffering
     */
    public static function start_buffer()
    {
        ob_start([self::class, 'minify']);
    }

    /**
     * End output buffering
     */
    public static function end_buffer()
    {
        if (ob_get_length()) {
            ob_end_flush();
        }
    }

    /**
     * Check if minification should be performed
     */
    private static function should_minify()
    {
        // Don't minify for admin pages
        if (is_admin()) {
            return false;
        }

        // Don't minify for AJAX requests
        if (wp_doing_ajax()) {
            return false;
        }

        // Don't minify if SCRIPT_DEBUG is enabled
        if (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) {
            return false;
        }

        return true;
    }
}

// Initialize the minifier
HTML_Minifier::init();


class AssetOptimizer
{

    /**
     * Minify JavaScript code.
     */
    private static function minify_js($js)
    {
        // Remove comments and excess whitespace
        $js = preg_replace('/((?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:\/\/.*))/', '', $js);
        $js = preg_replace('/\s+/', ' ', $js);
        return trim($js);
    }

    /**
     * Minify CSS code.
     */
    private static function minify_css($css)
    {
        // Remove CSS comments and excess whitespace
        $css = preg_replace('!/\*.*?\*/!s', '', $css);
        $css = preg_replace('/\s*([{};:>,])\s*/', '$1', $css);
        $css = preg_replace('/\s\s+/', ' ', $css);
        return trim($css);
    }

    /**
     * Process JS and CSS assets in their respective directories.
     */
    public static function process_assets($assets_dir)
    {

        $js_dir = glob($assets_dir . '/js/*.js');

        if (!empty($js_dir)) {
            // Process all JavaScript files in assets/js/
            foreach ($js_dir as $js_file) {
                // Skip already minified files
                if (strpos($js_file, '.min.js') !== false) {
                    continue;
                }

                $js_code = file_get_contents($js_file);

                $minified_js = self::minify_js($js_code);

                $minified_js_file = str_replace('.js', '.min.js', $js_file);
                file_put_contents($minified_js_file, $minified_js);
            }
        }

        $css_dir = glob($assets_dir . '/css/*.css');

        if (!empty($css_dir)) {

            // Process all CSS files in assets/css/
            foreach ($css_dir as $css_file) {
                // Skip already minified files
                if (strpos($css_file, '.min.css') !== false) {
                    continue;
                }

                $css_code = file_get_contents($css_file);
                $minified_css = self::minify_css($css_code);

                $minified_css_file = str_replace('.css', '.min.css', $css_file);
                if (!file_exists($minified_css_file)) {
                    file_put_contents($minified_css_file, $minified_css);
                }
            }
        }
    }
}

// Example usage
AssetOptimizer::process_assets(__DIR__ . '/../assets');
