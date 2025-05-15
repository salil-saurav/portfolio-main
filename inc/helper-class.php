<?php
if (!defined('ABSPATH')) exit;

/**
 * WP_Performance_Suite
 * 
 * Comprehensive WordPress performance optimization, security, and image handling
 * 
 * @version 1.0.0
 */
class WP_Performance_Suite
{
    /**
     * Configuration options
     * 
     * @var array
     */
    private $config = [
        // Basic optimizations
        'remove_emoji' => true,
        'remove_embeds' => true,
        'remove_wp_block_library' => true,
        'disable_xmlrpc' => true,
        'limit_revisions' => true,
        'max_revisions' => 3,
        'disable_heartbeat' => false,
        'heartbeat_frequency' => 60,
        'disable_self_pingbacks' => true,
        'disable_rss_feeds' => true,
        'remove_query_strings' => true,
        'disable_comments' => true,

        // Advanced optimizations
        'optimize_images' => true,
        'convert_to_webp' => true,
        'image_quality' => 82,
        'defer_js' => true,
        'lazy_load' => true,
        'preload_resources' => true,
        'disable_gutenberg' => true,
        'dns_prefetch' => true,
        'remove_jquery_migrate' => true,

        // Security options
        'hide_wp_version' => true,
        'disable_file_edit' => true,
        'restrict_admin_access' => false,
        'optimize_database' => true,
        'database_cleanup_interval' => 'weekly',

        // Development tools
        'show_template_path' => true,
        'debug_mode' => true
    ];

    /**
     * Resources for preloading
     * 
     * @var array
     */
    private $preload_resources = [
        'fonts' => [
            'https://fonts.googleapis.com',
            'https://fonts.gstatic.com'
        ],
        'scripts' => [
            'https://ajax.googleapis.com',
            'https://cdnjs.cloudflare.com'
        ],
        'analytics' => [
            'https://www.google-analytics.com',
            'https://www.googletagmanager.com'
        ]
    ];

    /**
     * Scripts to defer
     * 
     * @var array
     */
    private $defer_scripts = [
        'skip' => ['jquery', 'jquery-core', 'admin-bar', 'woocommerce'],
        'async' => ['google-analytics', 'gtag', 'gtm']
    ];

    /**
     * Constructor
     * 
     * @param array $config Optional configuration to override defaults
     */
    public function __construct(array $config = [])
    {
        // Merge user config with defaults
        $this->config = array_merge($this->config, $config);

        // Initialize hooks
        $this->init_hooks();

        // Register activation and deactivation hooks
        register_activation_hook(__FILE__, [$this, 'activate']);
        register_deactivation_hook(__FILE__, [$this, 'deactivate']);

        // Schedule database optimization if enabled
        if ($this->config['optimize_database'] && !wp_next_scheduled('wp_performance_suite_db_cleanup')) {
            wp_schedule_event(time(), $this->config['database_cleanup_interval'], 'wp_performance_suite_db_cleanup');
        }
    }

    /**
     * Plugin activation
     */
    public function activate()
    {
        // Set up database optimization schedule
        if ($this->config['optimize_database'] && !wp_next_scheduled('wp_performance_suite_db_cleanup')) {
            wp_schedule_event(time(), $this->config['database_cleanup_interval'], 'wp_performance_suite_db_cleanup');
        }

        // Flush rewrite rules
        flush_rewrite_rules();
    }

    /**
     * Plugin deactivation
     */
    public function deactivate()
    {
        // Clear scheduled events
        wp_clear_scheduled_hook('wp_performance_suite_db_cleanup');

        // Flush rewrite rules
        flush_rewrite_rules();
    }

    /**
     * Initialize all hooks and optimizations
     */
    private function init_hooks()
    {
        // Set up debugging if enabled
        if ($this->config['debug_mode']) {
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
        }

        // Core optimization hooks
        $this->remove_header_bloat();

        // API endpoints
        add_action('after_setup_theme', [$this, 'remove_api_endpoints']);

        // Emoji support
        if ($this->config['remove_emoji']) {
            $this->remove_emoji_support();
        }

        // Embeds
        if ($this->config['remove_embeds']) {
            $this->disable_embeds();
        }

        // Styles and scripts
        add_action('wp_enqueue_scripts', [$this, 'optimize_assets'], 100);

        // Post type supports
        add_action('init', [$this, 'remove_post_type_supports'], 100);

        // Self pingbacks
        if ($this->config['disable_self_pingbacks']) {
            add_action('pre_ping', [$this, 'stop_self_ping']);
        }

        // Disable XML-RPC
        if ($this->config['disable_xmlrpc']) {
            $this->disable_xmlrpc();
        }

        // Limit post revisions
        if ($this->config['limit_revisions']) {
            $this->limit_post_revisions();
        }

        // Control WordPress Heartbeat API
        if ($this->config['disable_heartbeat']) {
            $this->control_heartbeat();
        }

        // Disable RSS feeds
        if ($this->config['disable_rss_feeds']) {
            $this->disable_rss_feeds();
        }

        // Remove query strings from static resources
        if ($this->config['remove_query_strings']) {
            add_filter('script_loader_src', [$this, 'remove_query_strings'], 15);
            add_filter('style_loader_src', [$this, 'remove_query_strings'], 15);
        }

        // Disable comments completely
        if ($this->config['disable_comments']) {
            $this->disable_comments();
        }

        // Optimize images
        if ($this->config['optimize_images']) {
            add_filter('jpeg_quality', [$this, 'set_image_quality']);
            add_filter('wp_editor_set_quality', [$this, 'set_image_quality']);

            // WebP conversion
            if ($this->config['convert_to_webp']) {
                add_filter('wp_handle_upload', [$this, 'convert_to_webp'], 10, 2);
                add_filter('mime_types', [$this, 'enable_custom_mime_support']);
            }
        }

        // Script optimization
        if ($this->config['defer_js']) {
            add_filter('script_loader_tag', [$this, 'optimize_script_loading'], 10, 3);
        }

        // Resource preloading
        if ($this->config['preload_resources']) {
            add_filter('style_loader_tag', [$this, 'optimize_style_loading'], 10, 4);
        }

        // Lazy loading for images and iframes
        if ($this->config['lazy_load']) {
            add_filter('the_content', [$this, 'add_lazy_loading']);
            add_filter('post_thumbnail_html', [$this, 'add_lazy_loading']);
            add_filter('widget_text', [$this, 'add_lazy_loading']);
        }

        // Disable Gutenberg if needed
        if ($this->config['disable_gutenberg']) {
            $this->disable_gutenberg();
        }

        // Add DNS prefetch for common domains
        if ($this->config['dns_prefetch']) {
            add_action('wp_head', [$this, 'add_resource_hints'], 1);
        }

        // Disable jQuery Migrate
        if ($this->config['remove_jquery_migrate']) {
            add_action('wp_default_scripts', [$this, 'remove_jquery_migrate']);
        }

        // Remove WP version
        if ($this->config['hide_wp_version']) {
            add_filter('the_generator', '__return_empty_string');
            add_filter('style_loader_src', [$this, 'remove_wp_version_strings']);
            add_filter('script_loader_src', [$this, 'remove_wp_version_strings']);
        }

        // Security: Disable file editing in admin
        if ($this->config['disable_file_edit'] && !defined('DISALLOW_FILE_EDIT')) {
            define('DISALLOW_FILE_EDIT', true);
        }

        // Show template path in admin bar
        if ($this->config['show_template_path'] && current_user_can('manage_options')) {
            add_action('admin_bar_menu', [$this, 'show_template_path'], 100);
        }

        // Restrict comments post access
        add_action('init', [$this, 'restrict_comments_post_access']);

        // TinyMCE customization
        add_filter('tiny_mce_before_init', [$this, 'customize_tinymce']);

        // Database optimization
        add_action('wp_performance_suite_db_cleanup', [$this, 'optimize_database']);
    }

    /**
     * Remove header bloat
     */
    private function remove_header_bloat()
    {
        $actions_to_remove = [
            ['wp_head', 'wp_shortlink_wp_head', 10],
            ['template_redirect', 'wp_shortlink_header', 11],
            ['wp_head', 'rsd_link'],
            ['wp_head', 'wlwmanifest_link'],
            ['wp_head', 'wp_generator'],
            ['wp_head', 'feed_links', 2],
            ['wp_head', 'feed_links_extra', 3],
            ['wp_head', 'wp_oembed_add_discovery_links', 10],
            ['wp_head', 'wp_oembed_add_host_js'],
            ['rest_api_init', 'wp_oembed_register_route'],
            ['wp_head', 'wp_resource_hints', 2],
            ['wp_head', 'adjacent_posts_rel_link_wp_head', 10],
            ['wp_head', 'rel_canonical']
        ];

        foreach ($actions_to_remove as $action) {
            remove_action(...$action);
        }

        remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
    }

    /**
     * Remove WordPress API endpoints
     */
    public function remove_api_endpoints()
    {
        remove_action('wp_head', 'rest_output_link_wp_head', 10);
        remove_action('template_redirect', 'rest_output_link_header', 11);

        // Disable REST API for non-authenticated users if configured
        if ($this->config['restrict_admin_access']) {
            add_filter('rest_authentication_errors', function ($result) {
                if (!empty($result)) {
                    return $result;
                }
                if (!is_user_logged_in()) {
                    return new WP_Error('rest_not_logged_in', 'You are not authorized to use the REST API', ['status' => 401]);
                }
                return $result;
            });
        }
    }

    /**
     * Remove emoji support
     */
    private function remove_emoji_support()
    {
        $emoji_actions = [
            ['wp_head', 'print_emoji_detection_script', 7],
            ['admin_print_scripts', 'print_emoji_detection_script'],
            ['wp_print_styles', 'print_emoji_styles'],
            ['admin_print_styles', 'print_emoji_styles'],
            ['wp_mail', 'wp_staticize_emoji_for_email']
        ];

        foreach ($emoji_actions as $action) {
            remove_action(...$action);
        }

        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

        // Remove from TinyMCE
        add_filter('tiny_mce_plugins', [$this, 'disable_emojis_tinymce']);

        // Remove emoji DNS prefetch
        add_filter('emoji_svg_url', '__return_false');
    }

    /**
     * Disable emojis in TinyMCE
     */
    public function disable_emojis_tinymce($plugins)
    {
        if (is_array($plugins)) {
            return array_diff($plugins, ['wpemoji']);
        }
        return $plugins;
    }

    /**
     * Disable WordPress embeds
     */
    private function disable_embeds()
    {
        // Deregister scripts
        add_action('wp_print_scripts', [$this, 'deregister_embed_scripts']);

        // Remove TinyMCE plugin
        add_filter('tiny_mce_plugins', [$this, 'disable_embeds_tiny_mce_plugin']);

        // Remove REST API endpoint
        remove_action('rest_api_init', 'wp_oembed_register_route');

        // Disable oEmbed discovery
        add_filter('embed_oembed_discover', '__return_false');

        // Remove oEmbed-specific JavaScript from the front-end and back-end
        remove_action('wp_head', 'wp_oembed_add_host_js');
    }

    /**
     * Deregister embed scripts
     */
    public function deregister_embed_scripts()
    {
        $scripts = [
            'wp-embed',
            'twenty-twenty-one-responsive-embeds-script',
            'twenty-twenty-two-responsive-embeds-script',
            'twenty-twenty-three-responsive-embeds-script',
            'twenty-twenty-four-responsive-embeds-script',
            'jquery-embed'
        ];

        foreach ($scripts as $script) {
            wp_dequeue_script($script);
            wp_deregister_script($script);
        }
    }

    /**
     * Disable embeds TinyMCE plugin
     */
    public function disable_embeds_tiny_mce_plugin($plugins)
    {
        if (is_array($plugins)) {
            return array_diff($plugins, ['wpembed']);
        }
        return $plugins;
    }

    /**
     * Optimize assets (styles and scripts)
     */
    public function optimize_assets()
    {
        // Styles to remove
        $styles = [
            'twenty-twenty-one-print-style',
            'twenty-twenty-one-custom-color-overrides',
            'twenty-twenty-two-print-style',
            'twenty-twenty-two-custom-color-overrides',
            'twenty-twenty-three-print-style',
            'twenty-twenty-three-custom-color-overrides',
            'twenty-twenty-four-print-style',
            'twenty-twenty-four-custom-color-overrides',
        ];

        if ($this->config['remove_wp_block_library']) {
            $styles[] = 'wp-block-library';
            $styles[] = 'wp-block-library-theme';
            $styles[] = 'wc-blocks-style'; // WooCommerce blocks
            $styles[] = 'global-styles'; // Global styles
        }

        foreach ($styles as $style) {
            wp_dequeue_style($style);
            wp_deregister_style($style);
        }

        // Dequeue default theme styles if needed
        if (!is_admin() && !is_customize_preview()) {
            $themes = [
                'twenty-twenty-one-style',
                'twenty-twenty-two-style',
                'twenty-twenty-three-style',
                'twenty-twenty-four-style'
            ];

            // Only remove if not using one of these themes
            $current_theme = wp_get_theme()->get_stylesheet();
            if (strpos($current_theme, 'twenty-twenty') === false) {
                foreach ($themes as $theme) {
                    wp_dequeue_style($theme);
                    wp_deregister_style($theme);
                }
            }
        }
    }

    /**
     * Remove post type supports
     */
    public function remove_post_type_supports()
    {
        // Always remove trackbacks 
        remove_post_type_support('page', 'trackbacks');
        remove_post_type_support('post', 'trackbacks');

        // Remove comments based on configuration
        if ($this->config['disable_comments']) {
            remove_post_type_support('page', 'comments');
            remove_post_type_support('post', 'comments');

            // Get all post types and remove comment support
            $post_types = get_post_types();
            foreach ($post_types as $post_type) {
                if (post_type_supports($post_type, 'comments')) {
                    remove_post_type_support($post_type, 'comments');
                }
            }
        }

        // Remove unnecessary page features (excerpts aren't typically needed on pages)
        remove_post_type_support('page', 'excerpt');
    }

    /**
     * Stop self pingbacks
     */
    public function stop_self_ping(&$links)
    {
        $home = get_option('home');
        foreach ($links as $l => $link) {
            if (0 === strpos($link, $home)) {
                unset($links[$l]);
            }
        }
    }

    /**
     * Disable XML-RPC functionality
     */
    private function disable_xmlrpc()
    {
        // Disable XML-RPC methods that require authentication
        add_filter('xmlrpc_enabled', '__return_false');

        // Remove XML-RPC headers
        add_filter('wp_headers', function ($headers) {
            unset($headers['X-Pingback']);
            return $headers;
        });

        // Close pingback functionality
        add_filter('xmlrpc_methods', function ($methods) {
            unset($methods['pingback.ping']);
            unset($methods['pingback.extensions.getPingbacks']);
            return $methods;
        });

        // Block access to xmlrpc.php
        if (!defined('DOING_AJAX') && !defined('DOING_CRON')) {
            if (strpos($_SERVER['REQUEST_URI'], 'xmlrpc.php') !== false) {
                header('HTTP/1.0 403 Forbidden');
                exit('XML-RPC is disabled on this site.');
            }
        }
    }

    /**
     * Limit post revisions
     */
    private function limit_post_revisions()
    {
        if (!defined('WP_POST_REVISIONS')) {
            define('WP_POST_REVISIONS', $this->config['max_revisions']);
        }

        // Clean up existing revisions periodically
        add_action('admin_init', function () {
            if (!current_user_can('manage_options')) {
                return;
            }

            global $wpdb;
            $max_revisions = $this->config['max_revisions'];

            // Get all post ids
            $post_ids = $wpdb->get_col("SELECT ID FROM {$wpdb->posts} WHERE post_type = 'post' OR post_type = 'page'");

            if ($post_ids) {
                foreach ($post_ids as $post_id) {
                    // Get revisions for this post/page
                    $revisions = $wpdb->get_results(
                        $wpdb->prepare(
                            "SELECT ID FROM {$wpdb->posts} WHERE post_parent = %d AND post_type = 'revision' ORDER BY post_date DESC",
                            $post_id
                        )
                    );

                    // Remove excess revisions
                    if (count($revisions) > $max_revisions) {
                        $revisions_to_delete = array_slice($revisions, $max_revisions);
                        foreach ($revisions_to_delete as $revision) {
                            wp_delete_post_revision($revision->ID);
                        }
                    }
                }
            }
        });
    }

    /**
     * Control WordPress Heartbeat API
     */
    private function control_heartbeat()
    {
        // Disable heartbeat completely
        add_action('init', function () {
            // Only run on admin pages
            if (is_admin()) {
                wp_deregister_script('heartbeat');
            }
        }, 1);

        // Or alternatively, modify heartbeat frequency
        add_filter('heartbeat_settings', function ($settings) {
            $settings['interval'] = $this->config['heartbeat_frequency']; // Value in seconds
            return $settings;
        });
    }

    /**
     * Disable RSS feeds
     */
    private function disable_rss_feeds()
    {
        add_action('do_feed', [$this, 'disable_feed'], 1);
        add_action('do_feed_rdf', [$this, 'disable_feed'], 1);
        add_action('do_feed_rss', [$this, 'disable_feed'], 1);
        add_action('do_feed_rss2', [$this, 'disable_feed'], 1);
        add_action('do_feed_atom', [$this, 'disable_feed'], 1);
        add_action('do_feed_rss2_comments', [$this, 'disable_feed'], 1);
        add_action('do_feed_atom_comments', [$this, 'disable_feed'], 1);
    }

    /**
     * Disable feed and redirect
     */
    public function disable_feed()
    {
        wp_die(
            '<p>' . __('RSS feeds are disabled for better performance and security.') . '</p>',
            '',
            ['response' => 410]
        );
    }

    /**
     * Remove query strings from static resources
     */
    public function remove_query_strings($src)
    {
        if (strpos($src, '?ver=')) {
            $src = remove_query_arg('ver', $src);
        }
        return $src;
    }

    /**
     * Completely disable comments
     */
    private function disable_comments()
    {
        // Close comments and pings on all post types
        add_action('admin_init', function () {
            $post_types = get_post_types();
            foreach ($post_types as $post_type) {
                if (post_type_supports($post_type, 'comments')) {
                    remove_post_type_support($post_type, 'comments');
                    remove_post_type_support($post_type, 'trackbacks');
                }
            }
        });

        // Hide comments menu item
        add_action('admin_menu', function () {
            remove_menu_page('edit-comments.php');
        });

        // Redirect comments page
        add_action('admin_init', function () {
            global $pagenow;
            if ($pagenow === 'edit-comments.php') {
                wp_redirect(admin_url());
                exit;
            }
        });

        // Remove comments from admin bar
        add_action('wp_before_admin_bar_render', function () {
            global $wp_admin_bar;
            $wp_admin_bar->remove_menu('comments');
        });

        // Close comments on the front-end
        add_filter('comments_open', '__return_false', 20, 2);
        add_filter('pings_open', '__return_false', 20, 2);

        // Hide existing comments
        add_filter('comments_array', '__return_empty_array', 10, 2);

        // Remove comment-related fields from post screens
        add_filter('comment_form_default_fields', '__return_empty_array');

        // Disable comment-reply JS
        add_action('wp_print_scripts', function () {
            wp_dequeue_script('comment-reply');
        });
    }

    /**
     * Set image quality
     */
    public function set_image_quality()
    {
        return $this->config['image_quality']; // Good balance between quality and file size
    }

    /**
     * Convert uploaded images to WebP format
     */
    public function convert_to_webp($upload)
    {
        if (strpos($upload['type'], 'image/') !== 0 || $upload['type'] === 'image/webp' || $upload['type'] === 'image/svg+xml') {
            return $upload;
        }

        $file_path = $upload['file'];
        $file_info = wp_check_filetype($file_path);
        $allowed_types = ['image/jpeg', 'image/png'];

        // Process only JPEG/PNG images
        if (in_array($file_info['type'], $allowed_types)) {
            // Generate new file path with .webp extension
            $webp_path = preg_replace('/\.(jpe?g|png)$/i', '.webp', $file_path);
            $success = false;

            // Use GD Library if available
            if (function_exists('imagewebp')) {
                switch ($file_info['type']) {
                    case 'image/jpeg':
                        $image = imagecreatefromjpeg($file_path);
                        break;
                    case 'image/png':
                        $image = imagecreatefrompng($file_path);
                        // Preserve PNG transparency
                        imagealphablending($image, false);
                        imagesavealpha($image, true);
                        break;
                    default:
                        return $upload;
                }

                // Convert to WebP with configured quality
                $success = imagewebp($image, $webp_path, $this->config['image_quality']);
                imagedestroy($image);
            }
            // Fallback to Imagick if available
            elseif (class_exists('Imagick')) {
                try {
                    $imagick = new Imagick($file_path);
                    $imagick->setImageFormat('webp');
                    $imagick->setImageCompressionQuality($this->config['image_quality']);
                    $imagick->setOption('webp:lossless', 'false');
                    $imagick->setImageAlphaChannel(Imagick::ALPHANCHANNEL_ACTIVATE);
                    $success = $imagick->writeImage($webp_path);
                    $imagick->clear();
                } catch (Exception $e) {
                    error_log('WebP Conversion Error: ' . $e->getMessage());
                }
            }

            // If conversion succeeded, update metadata to point to the new .webp file
            if ($success && file_exists($webp_path)) {
                // Optionally keep originals by commenting out the unlink line
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
                $upload['type'] = 'image/webp';
                $upload['file'] = $webp_path;
                $upload['url'] = str_replace(basename($upload['url']), basename($webp_path), $upload['url']);
            }
        }

        return $upload;
    }

    /**
     * Enable WebP mime type support
     */
    public function enable_custom_mime_support($mimes)
    {
        $mimes['webp'] = 'image/webp';
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }

    /**
     * Optimize script loading with defer/async attributes
     */
    public function optimize_script_loading($tag, $handle, $src)
    {
        // Skip specific scripts
        if (in_array($handle, $this->defer_scripts['skip'])) {
            return $tag;
        }

        // Don't defer in admin
        if (is_admin()) {
            return $tag;
        }

        // Async loading for specific scripts
        if (in_array($handle, $this->defer_scripts['async']) || strpos($handle, 'analytics') !== false) {
            return str_replace(' src', ' async src', $tag);
        }

        // Add defer attribute to all other scripts
        if (strpos($tag, 'async') === false) {
            return str_replace(' src', ' defer src', $tag);
        }

        return $tag;
    }

    /**
     * Optimize style loading with preload attributes
     */
    public function optimize_style_loading($tag, $handle, $href, $media)
    {
        // Add preload for critical CSS
        if (strpos($handle, 'critical') !== false || strpos($handle, 'above-fold') !== false) {
            return str_replace('rel=\'stylesheet\'', 'rel=\'preload\' as=\'style\' onload="this.onload=null;this.rel=\'stylesheet\'"', $tag);
        }

        // Add preload for preconnect resources
        if (strpos($handle, 'preload') !== false || strpos($handle, 'preconnect') !== false) {
            return str_replace('rel=\'stylesheet\'', 'rel=\'preload stylesheet\' as=\'style\'', $tag);
        }

        return $tag;
    }

    /**
     * Add lazy loading to images and iframes
     */
    public function add_lazy_loading($content)
    {
        if (is_admin() || empty($content)) {
            return $content;
        }

        // Don't process content if it's already been processed
        if (strpos($content, 'loading="lazy"') !== false) {
            return $content;
        }

        // Skip if it's a feed or AMP page
        if (is_feed() || (function_exists('is_amp_endpoint') && is_amp_endpoint())) {
            return $content;
        }

        // Add loading="lazy" to images that don't already have it
        $content = preg_replace('/<img(?![^>]*loading=)(.*?)>/i', '<img$1 loading="lazy">', $content);

        // Add loading="lazy" to iframes that don't already have it
        $content = preg_replace('/<iframe(?![^>]*loading=)(.*?)>/i', '<iframe$1 loading="lazy">', $content);

        return $content;
    }

    /**
     * Disable Gutenberg editor
     */
    private function disable_gutenberg()
    {
        // Disable Gutenberg for posts
        add_filter('use_block_editor_for_post', '__return_false', 10);

        // Disable Gutenberg for post types
        add_filter('use_block_editor_for_post_type', '__return_false', 10);

        // Disable block widgets
        add_filter('gutenberg_use_widgets_block_editor', '__return_false');
        add_filter('use_widgets_block_editor', '__return_false');

        // Remove Gutenberg styles
        add_action('wp_enqueue_scripts', function () {
            wp_dequeue_style('wp-block-library');
            wp_dequeue_style('wp-block-library-theme');
            wp_dequeue_style('wc-block-style');
            wp_dequeue_style('wc-block-style');
            wp_dequeue_style('global-styles');
            wp_dequeue_style('classic-theme-styles');
        });
    }

    /**
     * Add resource hints for DNS prefetching
     */
    public function add_resource_hints()
    {
        $domains = [];

        // Add resource hints from config
        foreach ($this->preload_resources as $category => $urls) {
            $domains = array_merge($domains, $urls);
        }

        // Add DNS prefetch for each domain
        foreach (array_unique($domains) as $domain) {
            echo '<link rel="dns-prefetch" href="' . esc_url($domain) . '" />' . "\n";
            echo '<link rel="preconnect" href="' . esc_url($domain) . '" crossorigin />' . "\n";
        }
    }

    /**
     * Remove jQuery Migrate
     */
    public function remove_jquery_migrate($scripts)
    {
        if (!is_admin() && isset($scripts->registered['jquery'])) {
            $script = $scripts->registered['jquery'];

            if ($script->deps) {
                $script->deps = array_diff($script->deps, ['jquery-migrate']);
            }
        }
    }

    /**
     * Remove WordPress version strings from scripts and styles
     */
    public function remove_wp_version_strings($src)
    {
        if (strpos($src, 'ver=' . get_bloginfo('version'))) {
            $src = remove_query_arg('ver', $src);
        }
        return $src;
    }

    /**
     * Show template path in admin bar for developers
     */
    public function show_template_path($admin_bar)
    {
        if (!is_admin_bar_showing() || !current_user_can('manage_options') || is_admin()) {
            return;
        }

        global $template;
        global $wp_admin_bar;


        $theme_directory = basename(get_stylesheet_directory());

        $template_path = str_replace(ABSPATH . "wp-content/themes/$theme_directory", '', $template);


        $wp_admin_bar->add_node([
            'id'    => 'template-path',
            'title' => 'Template: ' . $template_path,
            'top'   => true
        ]);
        /**
         * Completely disable WordPress comments functionality
         * @return void
         */
    }

    /**
     * Restrict access to wp-comments-post.php to prevent comment spam
     */
    public function restrict_comments_post_access()
    {
        if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], 'wp-comments-post.php') !== false) {
            if (!empty($_SERVER['HTTP_REFERER'])) {
                $referrer = $_SERVER['HTTP_REFERER'];
                $site_url = get_site_url();

                if (strpos($referrer, $site_url) !== 0) {
                    wp_die(
                        'Comments are closed.',
                        '',
                        [
                            'response' => 403,
                            'back_link' => true
                        ]
                    );
                }
            } else {
                wp_die(
                    'Comments are closed.',
                    '',
                    [
                        'response' => 403,
                        'back_link' => true
                    ]
                );
            }
        }
    }

    /**
     * Customize TinyMCE editor settings
     */
    public function customize_tinymce($settings)
    {
        // Disable wpautop to prevent automatic paragraph generation
        $settings['wpautop'] = false;

        // Remove some default buttons
        if (isset($settings['toolbar1'])) {
            $settings['toolbar1'] = str_replace(',wp_more', '', $settings['toolbar1']);
        }

        // Add custom styles
        $settings['content_css'] = get_template_directory_uri() . '/assets/css/editor-style.css';

        return $settings;
    }

    /**
     * Optimize database
     */
    public function optimize_database()
    {
        global $wpdb;

        // Clean up post revisions (beyond our limit)
        $wpdb->query("DELETE FROM $wpdb->posts WHERE post_type = 'revision' AND post_parent NOT IN (SELECT ID FROM $wpdb->posts)");

        // Clean up auto drafts
        $wpdb->query("DELETE FROM $wpdb->posts WHERE post_status = 'auto-draft'");

        // Clean up orphaned postmeta
        $wpdb->query("DELETE FROM $wpdb->postmeta WHERE post_id NOT IN (SELECT ID FROM $wpdb->posts)");

        // Clean up orphaned commentmeta
        $wpdb->query("DELETE FROM $wpdb->commentmeta WHERE comment_id NOT IN (SELECT comment_ID FROM $wpdb->comments)");

        // Clean up orphaned term relationships
        $wpdb->query("DELETE FROM $wpdb->term_relationships WHERE object_id NOT IN (SELECT ID FROM $wpdb->posts)");

        // Clean up orphaned term meta
        $wpdb->query("DELETE FROM $wpdb->termmeta WHERE term_id NOT IN (SELECT term_id FROM $wpdb->terms)");

        // Optimize tables
        $tables = $wpdb->get_results("SHOW TABLES LIKE '{$wpdb->prefix}%'");
        foreach ($tables as $table) {
            $table_name = array_values(get_object_vars($table))[0];
            $wpdb->query("OPTIMIZE TABLE $table_name");
        }

        // Clean up expired transients
        $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '\_transient\_%' AND option_name NOT LIKE '\_transient\_timeout\_%'");
        $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '\_site\_transient\_%' AND option_name NOT LIKE '\_site\_transient\_timeout\_%'");
    }

    /**
     * Get singleton instance
     */
    public static function get_instance($config = [])
    {
        static $instance = null;

        if ($instance === null) {
            $instance = new self($config);
        }

        return $instance;
    }
}

// Initialize if not loaded by another plugin
if (!function_exists('wp_performance_suite_init')) {
    function wp_performance_suite_init($config = [])
    {
        return WP_Performance_Suite::get_instance($config);
    }

    // Auto-initialize with default settings
    wp_performance_suite_init();
}
