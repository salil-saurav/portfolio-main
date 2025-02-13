<?php

if (!defined('ABSPATH')) exit;

function custom_breadcrumb_shortcode()
{
    // Initialize output
    $output = '<nav class="breadcrumb">';
    $output .= '<ol class="breadcrumb-list">';

    // Add home link
    $output .= '<li class="breadcrumb-item"><a href="' . home_url() . '">Home</a></li>';

    // If not on home page, add current page/post info
    if (!is_front_page()) {
        if (is_category() || is_single()) {
            $output .= '<li class="breadcrumb-item">' . get_the_category()[0]->name . '</li>';

            if (is_single()) {
                $output .= '<li class="breadcrumb-item">' . get_the_title() . '</li>';
            }
        } elseif (is_page()) {
            $output .= '<li class="breadcrumb-item">' . get_the_title() . '</li>';
        }
    }

    $output .= '</ol>';
    $output .= '</nav>';

    return $output;
}
add_shortcode('breadcrumb', 'custom_breadcrumb_shortcode');
