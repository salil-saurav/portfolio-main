<?php

if (!defined('ABSPATH')) exit;

// Array of post types with their slugs


// Function to create custom post types
function register_custom_post_types()
{
    $custom_post_types = [
        'portfolio',
        'testimonial',
    ];

    function create_post_types($post_type)
    {
        $labels = array(
            'name'               => ucfirst($post_type) . 's',
            'singular_name'      => ucfirst($post_type),
            'menu_name'          => ucfirst($post_type) . 's',
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New ' . ucfirst($post_type),
            'edit_item'          => 'Edit ' . ucfirst($post_type),
            'view_item'          => 'View ' . ucfirst($post_type),
            'all_items'          => 'All ' . ucfirst($post_type) . 's',
            'search_items'       => 'Search ' . ucfirst($post_type) . 's',
            'not_found'          => 'No ' . $post_type . 's found.',
            'not_found_in_trash' => 'No ' . $post_type . 's found in Trash.',
        );

        register_post_type($post_type, array(
            'labels'             => $labels,
            'public'             => true,
            'has_archive'        => true,
            'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'comments'),
            'rewrite'            => array('slug' => $post_type . 's'),
            'show_in_rest'       => true,
            'menu_icon'          => 'dashicons-store',
        ));
    }

    // Register all custom post types
    foreach ($custom_post_types as $post_type) {
        create_post_types($post_type);
    }
}

function register_custom_taxonomies()
{
    $taxonomies = [
        ['name' => 'portfolio-category', 'post_type' => 'portfolio'],
        ['name' => 'testimonial-category', 'post_type' => 'testimonial']
    ];

    function create_taxonomy($tax)
    {
        $labels = array(
            'name'              => ucfirst(str_replace('-', ' ', $tax['name'])) . 's',
            'singular_name'     => ucfirst(str_replace('-', ' ', $tax['name'])),
            'search_items'      => 'Search ' . ucfirst(str_replace('-', ' ', $tax['name'])) . 's',
            'all_items'         => 'All ' . ucfirst(str_replace('-', ' ', $tax['name'])) . 's',
            'parent_item'       => 'Parent ' . ucfirst(str_replace('-', ' ', $tax['name'])),
            'parent_item_colon' => 'Parent ' . ucfirst(str_replace('-', ' ', $tax['name'])) . ':',
            'edit_item'         => 'Edit ' . ucfirst(str_replace('-', ' ', $tax['name'])),
            'update_item'       => 'Update ' . ucfirst(str_replace('-', ' ', $tax['name'])),
            'add_new_item'      => 'Add New ' . ucfirst(str_replace('-', ' ', $tax['name'])),
            'new_item_name'     => 'New ' . ucfirst(str_replace('-', ' ', $tax['name'])) . ' Name',
            'menu_name'         => ucfirst(str_replace('-', ' ', $tax['name'])) . 's'
        );

        register_taxonomy($tax['name'], $tax['post_type'], array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => $tax['name']),
            'show_in_rest'      => true
        ));
    }

    foreach ($taxonomies as $taxonomy) {
        create_taxonomy($taxonomy);
    }
}

add_action('init', 'register_custom_post_types');
add_action('init', 'register_custom_taxonomies');
