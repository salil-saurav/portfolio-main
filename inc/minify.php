<?php

/**
 * HTML Minification functionality
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

        $buffer = preg_replace($search, $replace, $buffer);
        return trim($buffer);
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
     *
     * @return boolean
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

        return true;
    }
}

// Initialize the minifier
HTML_Minifier::init();
