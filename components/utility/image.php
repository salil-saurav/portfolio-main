<?php

/**
 * Gets an image with specified parameters.
 *
 * @param int|string $attachment_id The attachment ID for the image.
 * @param array $args {
 *     Optional. An array of arguments.
 *
 *     @type string $class        Additional CSS classes for the image.
 *     @type string $alt          Alt text for the image.
 *     @type bool   $lazyload     Whether to lazy load the image.
 *     @type bool   $remove_style Whether to remove inline style attributes.
 *     @type string $size         Image size to use.
 *     @type string $placeholder  URL to a custom placeholder image.
 * }
 * @return string HTML markup for the image or false if invalid.
 */
function get_image($attachment_id, array $args = []): string
{
    // Early return if no valid attachment ID
    if (empty($attachment_id) || !wp_attachment_is_image($attachment_id)) {
        return false;
    }

    // Parse arguments using WordPress function
    $config = wp_parse_args($args, [
        'class'        => '',
        'alt'          => '',
        'lazyload'     => true,
        'remove_style' => false,
        'size'         => 'large',
    ]);

    // Get image source
    $image_src_array = wp_get_attachment_image_src($attachment_id, $config['size']);
    if (!$image_src_array || !is_array($image_src_array)) {
        return '';
    }
    $image_src = $image_src_array[0];

    // Get placeholder image

    $placeholder = get_field('site_image_placeholder', 'option');

    // Get alt text if not provided
    if (empty($config['alt'])) {
        $config['alt'] = get_post_meta($attachment_id, '_wp_attachment_image_alt', true) ?: get_the_title($attachment_id);
    }

    // Build class attribute - ensure it's a string, not an array
    $class_string = $config['lazyload'] ?  'lazyload img-fluid' : 'img-fluid' ;

    if (!empty($config['class'])) {
        // Handle if class is passed as string or array
        if (is_array($config['class'])) {
            $class_string .= ' ' . implode(' ', array_filter($config['class']));
        } else {
            $class_string .= ' ' . trim($config['class']);
        }
    }

    if ($config['lazyload']) {
        $class_string .= ' lazyload';
    }

    // Define image attributes
    $image_attributes = [
        'class'    =>  trim($class_string),
        'alt'      => esc_attr($config['alt']),
        'src'      => $config['lazyload'] ? esc_url($placeholder['url']) : esc_url($image_src),
        'loading'  => $config['lazyload'] ? 'lazy' : 'eager',
    ];

    // Add data-src for lazy loading
    if ($config['lazyload']) {
        $image_attributes['data-src'] = esc_url($image_src);
    }

    // Generate image HTML
    $image = wp_get_attachment_image($attachment_id, $config['size'], false, $image_attributes);

    // Remove style attribute if requested
    if ($config['remove_style'] && !empty($image)) {
        $image = preg_replace('/\sstyle=["\'](.*?)["\']/', '', $image);
    }

    return $image;
}
