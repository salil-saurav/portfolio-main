<?php

if (!defined('ABSPATH')) exit;

/**
 * Class WP_Starter_Admin
 * Handles all admin-related functionality
 */
class WP_Starter_Admin
{
    /**
     * Initialize the admin features
     */
    public function __construct()
    {
        $this->init_hooks();
    }

    /**
     * Register all hooks
     */
    private function init_hooks()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        add_filter('admin_footer_text', [$this, 'customize_footer_text']);
        add_action('wp_dashboard_setup', [$this, 'setup_dashboard_widget']);
        remove_action('welcome_panel', 'wp_welcome_panel');

        add_action('after_setup_theme', [$this, 'register_menu']);
        add_action('widgets_init', [$this, 'register_widgets']);
    }

    /**
     * Enqueue admin styles and fonts
     */
    public function enqueue_admin_assets()
    {
        $assets = [
            'wp-starter-fonts' => [
                'url' => 'https://fonts.googleapis.com/css2?family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap',
                'deps' => [],
            ],
            'wp-starter-admin-style' => [
                'url' => get_stylesheet_directory_uri() . '/assets/css/admin.css',
                'deps' => [],
            ],
        ];

        foreach ($assets as $handle => $asset) {
            wp_register_style($handle, $asset['url'], $asset['deps'], null);
            wp_enqueue_style($handle);
        }
    }

    /**
     * Customize admin footer text
     */
    public function customize_footer_text()
    {
        return sprintf(
            'Thank you for creating with <a href="%s">%s</a>',
            home_url(),
            get_bloginfo('name')
        );
    }

    /**
     * Setup dashboard widget
     */
    public function setup_dashboard_widget()
    {
        wp_add_dashboard_widget(
            'custom_dashboard_widget',
            'Site Overview',
            [$this, 'render_dashboard_widget']
        );
    }

    /**
     * Render dashboard widget content
     */
    public function render_dashboard_widget()
    {
        $stats = [
            'Posts' => wp_count_posts()->publish,
            'Pages' => wp_count_posts('page')->publish,
            'Comments' => wp_count_comments()->total_comments,
        ];

        echo '<p>Welcome to your dashboard! Quick stats:</p>';
        echo '<ul>';
        foreach ($stats as $label => $count) {
            printf('<li>%s: %d</li>', $label, $count);
        }
        echo '</ul>';
    }

    public function register_menu()
    {
        register_nav_menu('primary', __('Primary Menu'));
    }

    public  function register_widgets()
    {
        register_sidebar(array(
            'name' => __('Sidebar'),
            'id' => 'sidebar-1',
            'description' => __('Add widgets to this sidebar'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h2 class="widget-title">',
            'after_title' => '</h2>',
        ));
    }
}

// Initialize the admin features
new WP_Starter_Admin();
