<?php

if (!defined('ABSPATH')) exit;


// Helper function to pluralize a word.
// If the word ends with a consonant followed by "y", replace "y" with "ies".
function pluralize($word)
{
    if (preg_match('/[bcdfghjklmnpqrstvwxz]y$/i', $word)) {
        return substr($word, 0, -1) . 'ies';
    }
    return $word . 's';
}

// Create a custom post type with additional options (such as a custom menu_icon)
function create_custom_post_type($post_type, $args = array())
{
    // Set default options if not provided
    $menu_icon = $args['menu_icon'] ?? 'dashicons-archive';

    // Use the helper function to get the proper plural version
    $plural_label = pluralize($post_type);

    $labels = array(
        'name'               => ucfirst($plural_label),
        'singular_name'      => ucfirst($post_type),
        'menu_name'          => ucfirst($plural_label),
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New ' . ucfirst($post_type),
        'edit_item'          => 'Edit ' . ucfirst($post_type),
        'view_item'          => 'View ' . ucfirst($post_type),
        'all_items'          => 'All ' . ucfirst($plural_label),
        'search_items'       => 'Search ' . ucfirst($plural_label),
        'not_found'          => 'No ' . strtolower($plural_label) . ' found.',
        'not_found_in_trash' => 'No ' . strtolower($plural_label) . ' found in Trash.',
    );

    register_post_type($post_type, array(
        'labels'       => $labels,
        'public'       => true,
        'has_archive'  => true,
        'supports'     => array('title', 'editor', 'thumbnail', 'excerpt', 'comments'),
        'rewrite'      => array('slug' => $plural_label),
        'show_in_rest' => true,
        'menu_icon'    => $menu_icon,
    ));
}

// Register custom post types.
function register_custom_post_types()
{

    $custom_post_types = [
        'portfolio'   => ['menu_icon' => 'dashicons-portfolio'],
        'testimonial' => ['menu_icon' => 'dashicons-testimonial'],
        'property'    => ['menu_icon' => 'dashicons-admin-home'] // "property" pluralizes to "properties"
    ];

    foreach ($custom_post_types as $post_type => $options) {
        create_custom_post_type($post_type, $options);
    }
}
add_action('init', 'register_custom_post_types');


function create_custom_taxonomy($taxonomy, $post_type)
{
    // Replace dashes with spaces for labels
    $taxonomy_label = str_replace('-', ' ', $taxonomy);
    $plural_label = pluralize($taxonomy_label);

    $labels = array(
        'name'              => ucfirst($plural_label),
        'singular_name'     => ucfirst($taxonomy_label),
        'search_items'      => 'Search ' . ucfirst($plural_label),
        'all_items'         => 'All ' . ucfirst($plural_label),
        'parent_item'       => 'Parent ' . ucfirst($taxonomy_label),
        'parent_item_colon' => 'Parent ' . ucfirst($taxonomy_label) . ':',
        'edit_item'         => 'Edit ' . ucfirst($taxonomy_label),
        'update_item'       => 'Update ' . ucfirst($taxonomy_label),
        'add_new_item'      => 'Add New ' . ucfirst($taxonomy_label),
        'new_item_name'     => 'New ' . ucfirst($taxonomy_label) . ' Name',
        'menu_name'         => ucfirst($plural_label)
    );

    register_taxonomy($taxonomy, $post_type, array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => $taxonomy),
        'show_in_rest'      => true
    ));
}

function register_custom_taxonomies()
{
    $taxonomies = [
        ['taxonomy' => 'portfolio-category', 'post_type' => 'portfolio'],
        ['taxonomy' => 'testimonial-category', 'post_type' => 'testimonial']
    ];

    foreach ($taxonomies as $tax) {
        create_custom_taxonomy($tax['taxonomy'], $tax['post_type']);
    }
}
add_action('init', 'register_custom_taxonomies');
