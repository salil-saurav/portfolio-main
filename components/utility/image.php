<?php

/**
 * Get an HTML img element with specified attributes
 * 
 * @param int    $attachment_id WordPress attachment ID
 * @param string $size         Image size (default: 'large')
 * @param array  $args         Optional arguments for image configuration
 * @return string HTML img element or empty string if no attachment
 */


function get_image($attachment_id, array $args = []): string
{
    if (!$attachment_id) {
        return '';
    }

    $config = array_merge([
        'class'        => '',
        'alt'          => '',
        'lazyload'     => true,
        'remove_style' => false,
        'size'         => 'large',
    ], $args);

    // Fetch the full image URL for use in the data-src attribute
    $image_src = wp_get_attachment_image_src($attachment_id, $config['size'])[0];
    // Use a placeholder image as the src attribute
    $placeholder = 'path/to/your/placeholder.jpg';

    $image_attributes = [
        'class'   => trim('img-fluid ' . $config['class'] . ($config['lazyload'] ? ' lazyload' : '')),
        'alt'     => $config['alt'],
        'src'     => $config['lazyload'] ? $placeholder : $image_src,
        'data-src' => $config['lazyload'] ? $image_src : '',
        'loading' => $config['lazyload'] ? 'lazy' : 'eager'
    ];

    $image = wp_get_attachment_image($attachment_id, $config['size'], false, $image_attributes);

    return $config['remove_style']
        ? preg_replace('/\sstyle=["\'](.*?)["\']/', '', $image)
        : $image;
}
