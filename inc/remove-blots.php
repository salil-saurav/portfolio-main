<?php
if (!defined('ABSPATH')) exit;

class WP_Cleanup
{
    public function __construct()
    {
        $this->init_hooks();
    }

    private function init_hooks()
    {
        // Remove header bloat
        $this->remove_header_bloat();

        // Remove API endpoints
        add_action('after_setup_theme', [$this, 'remove_api_endpoints']);

        // Remove emojis
        $this->remove_emoji_support();

        // Remove embeds
        add_action('wp_print_scripts', [$this, 'deregister_embed_scripts']);

        // Remove various styles
        add_action('wp_print_styles', [$this, 'deregister_unnecessary_styles'], 100);

        // Remove post type supports
        add_action('init', [$this, 'remove_post_type_supports']);

        // Remove self pingbacks
        add_action('pre_ping', [$this, 'stop_self_ping']);

        // Conditional features
        if (is_front_page()) {
            add_action('init', [$this, 'remove_frontpage_features']);
        }
    }

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
            ['wp_head', 'wp_resource_hints', 2]
        ];

        foreach ($actions_to_remove as $action) {
            remove_action(...$action);
        }

        remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
    }

    public function remove_api_endpoints()
    {
        remove_action('wp_head', 'rest_output_link_wp_head', 10);
        remove_action('template_redirect', 'rest_output_link_header', 11);
    }

    private function remove_emoji_support()
    {
        $emoji_actions = [
            ['wp_head', 'print_emoji_detection_script', 7],
            ['admin_print_scripts', 'print_emoji_detection_script'],
            ['wp_print_styles', 'print_emoji_styles'],
            ['admin_print_styles', 'print_emoji_styles']
        ];

        foreach ($emoji_actions as $action) {
            remove_action(...$action);
        }

        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    }

    public function deregister_embed_scripts()
    {
        $scripts = ['wp-embed', 'twenty-twenty-one-responsive-embeds-script'];
        foreach ($scripts as $script) {
            wp_dequeue_script($script);
            wp_deregister_script($script);
        }
    }

    public function deregister_unnecessary_styles()
    {
        $styles = [
            'twenty-twenty-one-print-style',
            'twenty-twenty-one-custom-color-overrides',
            'wp-block-library',
            'twenty-twenty-one-style'
        ];

        foreach ($styles as $style) {
            wp_dequeue_style($style);
            wp_deregister_style($style);
        }
    }

    public function remove_post_type_supports()
    {
        remove_post_type_support('page', 'comments');
        remove_post_type_support('page', 'trackbacks');
        remove_post_type_support('page', 'excerpt');
    }

    public function remove_frontpage_features()
    {
        remove_post_type_support('page', 'thumbnail');
    }

    public function stop_self_ping(&$links)
    {
        $home = get_option('home');
        foreach ($links as $l => $link) {
            if (0 === strpos($link, $home)) {
                unset($links[$l]);
            }
        }
    }
}

// Initialize the cleanup
new WP_Cleanup();
