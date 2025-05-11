<?php

/**
 * Asset Manager Class
 * 
 * Handles registration and enqueuing of scripts and styles in WordPress
 */
class Theme_Asset_Manager
{
    /**
     * CSS assets to be enqueued
     * 
     * @var array
     */
    private $css_assets = [];

    /**
     * JavaScript assets to be enqueued
     * 
     * @var array
     */
    private $js_assets = [];

    /**
     * Version string for cache busting
     * 
     * @var string
     */
    private $version;

    /**
     * Constructor
     * 
     * @param string $version Version string for cache busting
     */
    public function __construct($version = null)
    {
        $this->version = $version ?? wp_get_theme()->get('Version');
        $this->register_hooks();
        $this->setup_assets();
    }

    /**
     * Register WordPress hooks
     */
    private function register_hooks()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    /**
     * Set up default assets
     */
    private function setup_assets()
    {
        // CSS Assets
        $this->add_css('main_style', get_stylesheet_directory_uri() . '/assets/css/main.css');

        // JavaScript Assets
        $this->add_js('dom_js', get_stylesheet_directory_uri() . '/assets/js/DOM.js');
    }

    /**
     * Add a CSS file to be enqueued
     * 
     * @param string $handle Unique identifier for the CSS file
     * @param string $url URL to the CSS file
     * @param array $dependencies Array of dependencies
     * @param string $media Media for which this stylesheet has been defined
     * @return Theme_Asset_Manager
     */
    public function add_css($handle, $url, $dependencies = [], $media = 'all')
    {
        $this->css_assets[$handle] = [
            'url' => $url,
            'dependencies' => $dependencies,
            'media' => $media
        ];

        return $this;
    }

    /**
     * Add a JavaScript file to be enqueued
     * 
     * @param string $handle Unique identifier for the JS file
     * @param string $url URL to the JS file
     * @param array $dependencies Array of dependencies
     * @param bool $in_footer Whether to enqueue the script in the footer
     * @return Theme_Asset_Manager
     */
    public function add_js($handle, $url, $dependencies = [], $in_footer = true)
    {
        $this->js_assets[$handle] = [
            'url' => $url,
            'dependencies' => $dependencies,
            'in_footer' => $in_footer
        ];

        return $this;
    }

    /**
     * Enqueue all registered assets
     */
    public function enqueue_assets()
    {
        $this->enqueue_styles();
        $this->enqueue_scripts();
    }

    /**
     * Enqueue CSS files
     */
    private function enqueue_styles()
    {
        foreach ($this->css_assets as $handle => $asset) {
            wp_register_style(
                $handle,
                $asset['url'],
                $asset['dependencies'],
                $this->version,
                $asset['media']
            );
            wp_enqueue_style($handle);
        }
    }

    /**
     * Enqueue JavaScript files
     */
    private function enqueue_scripts()
    {
        foreach ($this->js_assets as $handle => $asset) {
            wp_register_script(
                $handle,
                $asset['url'],
                $asset['dependencies'],
                $this->version,
                $asset['in_footer']
            );
            wp_enqueue_script($handle);
        }
    }
}

// Initialize the Asset Manager
new Theme_Asset_Manager();
