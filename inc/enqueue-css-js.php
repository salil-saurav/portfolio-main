<?php

/**
 * Enqueue scripts and styles
 */
function theme_scripts_styles()
{

    $css_assets = [
        'bootstrap_css' => [
            'url' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
            'depth' => [],
        ],
        'main_style' => [
            'url' => get_stylesheet_directory_uri() . '/assets/css/main.css',
            'depth' => [],
        ],
    ];

    $js_assets = [
        'jquery_js' => [
            'url' => 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js',
            'depth' => [],

        ],
        'bootstrap_js' => [
            'url' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
            'depth' => [],
        ],
        'dom_js' => [
            'url' => get_stylesheet_directory_uri() . '/assets/js/DOM.js',
            'depth' => [],
        ],
    ];

    // enqueue CSS files 

    foreach ($css_assets as $handle => $asset) {
        wp_register_style($handle, $asset['url'], $asset['depth'], null);
        wp_enqueue_style($handle);
    }

    // Enqueue JS files 

    foreach ($js_assets as $handle => $asset) {
        wp_register_script($handle, $asset['url'], $asset['url'], null);
        wp_enqueue_script($handle);
    }
}
add_action('wp_enqueue_scripts', 'theme_scripts_styles');
