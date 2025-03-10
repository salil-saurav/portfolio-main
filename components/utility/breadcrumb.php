<?php

if (!defined('ABSPATH')) exit;

function custom_breadcrumb_shortcode()
{
    if (is_front_page()) {
        return build_breadcrumb_html(['Home']);
    }

    $breadcrumb_items = [
        ['title' => 'Home', 'url' => home_url()]
    ];

    if (is_category() || is_single()) {
        $category = get_the_category();
        if (!empty($category)) {
            $breadcrumb_items[] = ['title' => $category[0]->name];
        }

        if (is_single()) {
            $breadcrumb_items[] = ['title' => get_the_title()];
        }
    } elseif (is_page()) {
        $breadcrumb_items[] = ['title' => get_the_title()];
    }

    return build_breadcrumb_html($breadcrumb_items);
}

function build_breadcrumb_html($items)
{
    $output = '<nav class="breadcrumb"><ol class="breadcrumb-list">';

    foreach ($items as $index => $item) {
        $output .= '<li class="breadcrumb-item">';
        if (isset($item['url'])) {
            $output .= '<a href="' . esc_url($item['url']) . '">' . esc_html($item['title']) . '</a>';
        } else {
            $output .= esc_html($item['title']);
        }
        $output .= '</li>';
    }

    $output .= '</ol></nav>';
    return $output;
}

add_shortcode('breadcrumb', 'custom_breadcrumb_shortcode');
