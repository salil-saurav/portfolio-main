<?php

/**
 * Main functions file
 * Auto-loads all PHP files from inc and components/utility directories
 */

if (!defined('ABSPATH')) exit;

/**
 * Require inc files
 */

require_once __DIR__ . '/inc/acf-helper.php';
require_once __DIR__ . '/inc/admin.php';
require_once __DIR__ . '/inc/code-meta.php';
require_once __DIR__ . '/inc/config-smtp.php';
require_once __DIR__ . '/inc/custom-login.php';
require_once __DIR__ . '/inc/custom-posts.php';
require_once __DIR__ . '/inc/helper-functions.php';
require_once __DIR__ . '/inc/minify.php';
require_once __DIR__ . '/inc/remove-blots.php';

/**
 * Require components files
 */

require_once __DIR__ . '/components/alert.php';
require_once __DIR__ . '/components/breadrumb.php';
require_once __DIR__ . '/components/button.php';
require_once __DIR__ . '/components/pagination.php';
require_once __DIR__ . '/components/image.php';
