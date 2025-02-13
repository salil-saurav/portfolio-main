<?php
if (!defined('ABSPATH')) exit;

class WP_Helper_Functions
{
    /**
     * Initialize hooks and filters
     */
    public static function init()
    {
        // Editor related
        add_filter('use_block_editor_for_post', '__return_false', 10);
        add_filter('wpcf7_autop_or_not', '__return_false');
        add_filter('tiny_mce_before_init', [self::class, 'remove_tinymce_background_color']);


        // Resource optimization
        add_filter('style_loader_tag', [self::class, 'handle_resource_loading'], 10, 2);
        add_filter('script_loader_tag', [self::class, 'handle_script_loading'], 10, 2);

        // Image handling
        add_filter('wp_handle_upload', [self::class, 'compress_and_convert_to_webp'], 10, 2);
        add_filter('mime_types', [self::class, 'enable_webp_support']);

        // Security
        add_action('init', [self::class, 'restrict_comments_post_access']);
    }

    /**
     * Handle resource loading attributes
     */
    public static function handle_resource_loading($tag, $handle)
    {
        $attributes = [
            'preload' => "rel='preload stylesheet' as='style'",
            'preconnect' => "rel='preconnect stylesheet' as='style'"
        ];

        foreach ($attributes as $key => $value) {
            if (str_contains($handle, $key)) {
                return str_replace("rel='stylesheet'", $value, $tag);
            }
        }
        return $tag;
    }

    /**
     * Handle script loading attributes
     */
    public static function handle_script_loading($tag, $handle)
    {
        $attributes = [
            'preload' => "rel='preload' as='script'",
            'async' => 'async',
            'defer' => 'defer'
        ];

        foreach ($attributes as $key => $value) {
            if (str_contains($handle, $key)) {
                return str_replace('<script', "<script $value", $tag);
            }
        }
        return $tag;
    }

    // ... Additional methods for other functionalities ...

    /**
     * Restrict direct access to wp-comments-post.php
     * @return void
     */
    public static function restrict_comments_post_access()
    {
        if (basename($_SERVER['SCRIPT_FILENAME']) !== 'wp-comments-post.php') {
            return;
        }

        $allowed_host = parse_url(get_site_url(), PHP_URL_HOST);
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        $referer_host = parse_url($referer, PHP_URL_HOST);

        if (empty($referer) || $referer_host !== $allowed_host) {
            wp_die(
                __('Direct access to this page is not allowed.'),
                __('Access Denied'),
                ['response' => 403]
            );
        }
    }

    /**
     * Customize TinyMCE editor styles
     * @param array $mceInit TinyMCE settings
     * @return array Modified settings
     */
    public static function remove_tinymce_background_color($mceInit)
    {
        $custom_styles = [
            'body { background-color: transparent !important; }',
            'body, p, .wp-block-quote { font-family: Inter, sans-serif !important; }'
        ];

        $style_string = implode(' ', $custom_styles);

        $mceInit['content_style'] = isset($mceInit['content_style'])
            ? $mceInit['content_style'] . ' ' . $style_string
            : $style_string;

        return $mceInit;
    }

    /**
     * Convert uploaded image to Webp
     */

    public static function compress_and_convert_to_webp($upload)
    {
        // Check if the uploaded file is an image
        if (strpos($upload['type'], 'image/') === 0) {
            $file_path = $upload['file'];
            $file_type = wp_check_filetype($file_path);

            // Process only JPEG/PNG images
            if (in_array($file_type['type'], ['image/jpeg', 'image/png'])) {
                // Create WebP version
                $webp_path = $file_path . '.webp';
                $success = false;

                // Check for GD or Imagick support
                if (function_exists('imagewebp')) {
                    // GD Library
                    switch ($file_type['type']) {
                        case 'image/jpeg':
                            $image = imagecreatefromjpeg($file_path);
                            break;
                        case 'image/png':
                            $image = imagecreatefrompng($file_path);
                            imagepalettetotruecolor($image); // Preserve transparency
                            break;
                    }

                    // Compress and save as WebP (quality: 80%)
                    $success = imagewebp($image, $webp_path, 80);
                    imagedestroy($image);
                } elseif (class_exists('Imagick')) {
                    // Imagick Library
                    $imagick = new Imagick($file_path);
                    $imagick->setImageFormat('webp');
                    $imagick->setImageCompressionQuality(80);
                    $success = $imagick->writeImage($webp_path);
                    $imagick->clear();
                }

                // Replace original with WebP if conversion succeeded
                if ($success) {
                    unlink($file_path); // Delete original JPG/PNG
                    rename($webp_path, $file_path); // Rename WebP to original filename

                    // Update metadata to reflect WebP format
                    $upload['type'] = 'image/webp';
                    $upload['file'] = str_replace(
                        ['.jpg', '.jpeg', '.png'],
                        '.webp',
                        $upload['file']
                    );
                }
            }
        }

        return $upload;
    }

    public static function enable_webp_support($mimes)
    {
        $mimes['webp'] = 'image/webp';
        return $mimes;
    }
}

// Initialize the helper functions
WP_Helper_Functions::init();




// Add WebP support to WordPress
