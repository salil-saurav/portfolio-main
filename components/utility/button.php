<?php

/**
 * Button Component
 * 
 * @param array $args {
 *     @type string $text       Button text
 *     @type string $url        Button URL
 *     @type string $type       Button type (primary, secondary, etc.)
 *     @type string $size       Button size (small, medium, large)
 *     @type string $class      Additional CSS classes
 *     @type bool   $new_tab    Open in new tab
 * }
 */

function get_button($args = [])
{
    $defaults = [
        'text' => 'Click Here',
        'url' => '#',
        'type' => 'primary',
        'size' => 'medium',
        'class' => '',
        'new_tab' => false
    ];

    $args = wp_parse_args($args, $defaults);

    $classes = [
        'btn',
        'btn-' . esc_attr($args['type']),
        'btn-' . esc_attr($args['size']),
        $args['class']
    ];

    $target = $args['new_tab'] ? ' target="_blank" rel="noopener noreferrer"' : '';

    printf(
        '<a href="%s" class="%s"%s>%s</a>',
        esc_url($args['url']),
        esc_attr(trim(implode(' ', $classes))),
        $target,
        esc_html($args['text'])
    );
}
