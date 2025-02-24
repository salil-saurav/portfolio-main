<?php

/**
 * Enqueue scripts and styles
 */
function theme_scripts_styles()
{
    // Enqueue minified CSS files
    // wp_enqueue_style(
    //     'main-style',
    //     get_stylesheet_directory_uri() . '/assets/css/main.min.css',
    //     array(),
    //     '1.0.0'
    // );

    wp_enqueue_style(
        'bootstrap-css',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
        [],
        '1.0.0'
    );

    // Google Icons 

    wp_enqueue_style(
        'google-icon-css',
        'https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=call,new_releases,star',
        [],
        '1.0.0'
    );

    wp_enqueue_style(
        'main-style',
        get_stylesheet_directory_uri() . '/assets/css/main.css',
        array(),
        '1.0.0'
    );

    // Enqueue minified JavaScript files
    // wp_enqueue_script(
    //     'DOM-script',
    //     get_stylesheet_directory_uri() . '/assets/js/DOM.min.js',
    //     [],
    //     false,
    //     true
    // );

    wp_enqueue_script(
        'jquery-js',
        'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js',
        [],
        false,
        true
    );

    wp_enqueue_script(
        'bootstrap-js',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
        [],
        false,
        true
    );

    wp_enqueue_script(
        'DOM-script',
        get_stylesheet_directory_uri() . '/assets/js/DOM.js',
        [],
        false,
        true
    );
}
add_action('wp_enqueue_scripts', 'theme_scripts_styles');
