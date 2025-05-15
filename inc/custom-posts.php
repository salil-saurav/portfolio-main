<?php

if (!defined('ABSPATH')) exit;

/**
 * Custom Post Types Manager
 * 
 * Handles creation of custom post types and taxonomies with proper naming conventions
 * and robust configuration options.
 */
class Custom_Post_Types_Manager
{
    /**
     * Custom post types configuration
     *
     * @var array
     */
    private $post_types = [
        'build' => [
            'menu_icon' => 'dashicons-layout',
            'taxonomies' => ['build_type'],
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt']
        ],
        'testimonial' => [
            'menu_icon' => 'dashicons-testimonial',
            'supports' => ['title', 'editor', 'thumbnail']
        ]
    ];

    /**
     * Initialize the class
     *
     * @return void
     */
    public static function init(): void
    {
        $instance = new self();
        $instance->register_post_types_and_taxonomies();
    }

    /**
     * Register all custom post types and their taxonomies
     *
     * @return void
     */
    public function register_post_types_and_taxonomies(): void
    {
        foreach ($this->post_types as $post_type => $config) {
            $this->register_post_type($post_type, $config);

            // Register associated taxonomies
            if (!empty($config['taxonomies'])) {
                foreach ($config['taxonomies'] as $taxonomy) {
                    $this->create_taxonomy($taxonomy, $post_type);
                }
            }
        }
    }

    /**
     * Generate proper labels for a post type
     *
     * @param string $post_type_name Post type name (singular)
     * @return array Post type labels
     */
    private function generate_post_type_labels(string $post_type_name): array
    {
        // Convert from slug format to readable format
        $singular = $this->format_name($post_type_name);
        $singular_ucfirst = ucfirst($singular);

        // Get plural form
        $plural = $this->pluralize($singular);
        $plural_ucfirst = ucfirst($plural);

        return [
            'name'               => $plural_ucfirst,
            'singular_name'      => $singular_ucfirst,
            'menu_name'          => $plural_ucfirst,
            'name_admin_bar'     => $singular_ucfirst,
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New ' . $singular_ucfirst,
            'edit_item'          => 'Edit ' . $singular_ucfirst,
            'new_item'           => 'New ' . $singular_ucfirst,
            'view_item'          => 'View ' . $singular_ucfirst,
            'view_items'         => 'View ' . $plural_ucfirst,
            'search_items'       => 'Search ' . $plural_ucfirst,
            'not_found'          => 'No ' . strtolower($plural) . ' found',
            'not_found_in_trash' => 'No ' . strtolower($plural) . ' found in trash',
            'parent_item_colon'  => 'Parent ' . $singular_ucfirst . ':',
            'all_items'          => 'All ' . $plural_ucfirst,
            'archives'           => $singular_ucfirst . ' Archives',
            'attributes'         => $singular_ucfirst . ' Attributes',
            'insert_into_item'   => 'Insert into ' . strtolower($singular),
            'uploaded_to_this_item' => 'Uploaded to this ' . strtolower($singular),
            'featured_image'     => 'Featured Image',
            'set_featured_image' => 'Set featured image',
            'remove_featured_image' => 'Remove featured image',
            'use_featured_image' => 'Use as featured image',
            'filter_items_list'  => 'Filter ' . strtolower($plural) . ' list',
            'items_list_navigation' => $plural_ucfirst . ' list navigation',
            'items_list'         => $plural_ucfirst . ' list',
        ];
    }

    /**
     * Generate proper labels for a taxonomy
     *
     * @param string $taxonomy_name Taxonomy name (slug format)
     * @return array Taxonomy labels
     */
    private function generate_taxonomy_labels(string $taxonomy_name): array
    {
        // Convert from slug format to readable format
        $singular = $this->format_name($taxonomy_name);
        $singular_ucfirst = ucfirst($singular);

        // Get plural form
        $plural = $this->pluralize($singular);
        $plural_ucfirst = ucfirst($plural);

        return [
            'name'              => $plural_ucfirst,
            'singular_name'     => $singular_ucfirst,
            'search_items'      => 'Search ' . $plural_ucfirst,
            'all_items'         => 'All ' . $plural_ucfirst,
            'parent_item'       => 'Parent ' . $singular_ucfirst,
            'parent_item_colon' => 'Parent ' . $singular_ucfirst . ':',
            'edit_item'         => 'Edit ' . $singular_ucfirst,
            'update_item'       => 'Update ' . $singular_ucfirst,
            'add_new_item'      => 'Add New ' . $singular_ucfirst,
            'new_item_name'     => 'New ' . $singular_ucfirst . ' Name',
            'menu_name'         => $plural_ucfirst,
            'back_to_items'     => '← Back to ' . $plural_ucfirst,
            'view_item'         => 'View ' . $singular_ucfirst,
            'popular_items'     => 'Popular ' . $plural_ucfirst,
            'separate_items_with_commas' => 'Separate ' . strtolower($plural) . ' with commas',
            'add_or_remove_items' => 'Add or remove ' . strtolower($plural),
            'choose_from_most_used' => 'Choose from the most used ' . strtolower($plural),
            'not_found'         => 'No ' . strtolower($plural) . ' found',
        ];
    }

    /**
     * Register a custom post type
     *
     * @param string $post_type Post type name (slug format)
     * @param array $config Configuration options
     * @return void
     */
    private function register_post_type(string $post_type, array $config): void
    {
        // Normalize post type name to follow WordPress conventions
        $post_type = sanitize_key($post_type);

        // Format for URL
        $slug = str_replace('_', '-', $post_type);

        // Generate labels
        $labels = $this->generate_post_type_labels($post_type);

        // Set up arguments with defaults
        $args = [
            'labels'       => $labels,
            'public'       => true,
            'has_archive'  => true,
            'menu_icon'    => $config['menu_icon'] ?? 'dashicons-admin-post',
            'hierarchical' => $config['hierarchical'] ?? false,
            'supports'     => $config['supports'] ?? ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
            'rewrite'      => [
                'slug' => $slug,
                'with_front' => true,
            ],
            'show_in_rest' => true,
            'menu_position' => $config['menu_position'] ?? null,
            'show_in_nav_menus' => $config['show_in_nav_menus'] ?? true,
            'show_in_admin_bar' => $config['show_in_admin_bar'] ?? true,
            'capability_type' => $config['capability_type'] ?? 'post',
        ];

        // Register the post type
        register_post_type($post_type, $args);
    }

    /**
     * Register a custom taxonomy
     *
     * @param string $taxonomy Taxonomy name (slug format)
     * @param string $post_type Post type to attach taxonomy to
     * @return void
     */
    private function create_taxonomy(string $taxonomy, string $post_type): void
    {
        // Normalize taxonomy name to follow WordPress conventions
        $taxonomy = sanitize_key($taxonomy);

        // Format for URL
        $slug = str_replace('_', '-', $taxonomy);

        // Generate labels
        $labels = $this->generate_taxonomy_labels($taxonomy);

        // Set up arguments
        $args = [
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => [
                'slug' => $slug,
                'with_front' => true,
                'hierarchical' => true,
            ],
            'show_in_rest'      => true,
        ];

        // Register the taxonomy
        register_taxonomy($taxonomy, $post_type, $args);
    }

    /**
     * Format a slug or machine name to a readable format
     *
     * @param string $name Name to format
     * @return string Formatted name
     */
    private function format_name(string $name): string
    {
        // Replace underscores and hyphens with spaces
        $formatted = str_replace(['-', '_'], ' ', $name);

        // Trim extra spaces
        return trim($formatted);
    }

    /**
     * Pluralize a word based on English rules
     *
     * @param string $word Word to pluralize
     * @return string Pluralized word
     */
    private function pluralize(string $word): string
    {
        // Common irregular plurals
        $irregular_plurals = [
            'child' => 'children',
            'foot' => 'feet',
            'goose' => 'geese',
            'man' => 'men',
            'mouse' => 'mice',
            'person' => 'people',
            'tooth' => 'teeth',
            'woman' => 'women',
        ];

        // Check for irregular plural
        if (isset($irregular_plurals[$word])) {
            return $irregular_plurals[$word];
        }

        // Handle words ending in 'y'
        if (preg_match('/[bcdfghjklmnpqrstvwxz]y$/i', $word)) {
            return substr($word, 0, -1) . 'ies';
        }

        // Handle words ending in 's', 'x', 'z', 'ch', 'sh'
        if (preg_match('/(s|x|z|ch|sh)$/i', $word)) {
            return $word . 'es';
        }

        // Handle words ending in 'f' or 'fe'
        if (preg_match('/f$/i', $word)) {
            return substr($word, 0, -1) . 'ves';
        }
        if (preg_match('/fe$/i', $word)) {
            return substr($word, 0, -2) . 'ves';
        }

        // Regular plural: add 's'
        return $word . 's';
    }
}

// Initialize the class
add_action('init', ['Custom_Post_Types_Manager', 'init']);
