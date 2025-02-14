<?php

/**
 * Enqueue scripts and styles
 */
function theme_scripts_styles()
{
    // Enqueue CSS files
    wp_enqueue_style(
        'main-style',
        get_template_directory_uri() . '/assets/css/main.css',
        array(),
        '1.0.0'
    );

    // Enqueue JavaScript files
    wp_enqueue_script(
        'DOM-script',
        get_template_directory_uri() . '/assets/js/DOM.js',
        [],
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'theme_scripts_styles');
