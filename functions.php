<?php

/**
 * Main functions file
 * Auto-loads all PHP files from inc and components/utility directories
 */

if (!defined('ABSPATH')) exit;

/**
 * Require inc files
 */

foreach (glob(__DIR__ . '/inc/*.php') as $file) {

    require_once $file;
}

/**
 * Require components utility files
 */

foreach (glob(__DIR__ . '/components/utility/*.php') as $utility_files) {
    require_once $utility_files;
}


/**
 * Require Meta fields
 */

foreach (glob(__DIR__ . '/meta-fields/*.php') as $meta_fields) {
    require_once $meta_fields;
}


function cta()
{

    $html =
        '<a class="cta" href="tel:7701990393" style="margin-top: 15px;">
            <span>Contact</span>
        </a>';

    echo $html;
}
