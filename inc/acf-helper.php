<?php

if (!defined('ABSPATH')) exit;

/**
 * Deletes ACF field data when a field is deleted.
 *
 * @param array $field The ACF field being deleted.
 */
function my_acf_delete_field_data($field)
{
    $field_key = $field['key'];

    // Delete postmeta data
    delete_post_meta_by_key($field_key);

    // Delete options data (if applicable)
    $option_key = 'option_' . $field_key;
    delete_option($option_key);
}
add_action('acf/delete_field', 'my_acf_delete_field_data', 10, 1);

if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'page_title' => 'Theme Options',
        'menu_title' => 'Theme Options',
        'menu_slug' => 'theme-general-options',
        'capability' => 'manage_options',
        'redirect' => false,
        'position' => '85', // Position below the ACF menu
        'icon_url' => 'dashicons-admin-site-alt2', // Dashicon for the menu
    ));
}
