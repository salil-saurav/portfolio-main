<?php
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Renders the secondary navigation
 */

$nav_args = [
    'theme_location' => 'secondary',
    'menu_id'       => 'secondary-menu',
    'menu_class'    => 'nav-menu',
    'container'     => false,
    'fallback_cb'   => false,
];

$nav_labels = [
    'nav_aria_label' => __('Secondary Navigation', 'your-theme-text-domain'),
    'toggle_text'    => __('Secondary Menu', 'your-theme-text-domain'),
];
?>

<nav id="secondary-navigation"
    class="secondary-navigation"
    role="navigation"
    aria-label="<?php echo esc_attr($nav_labels['nav_aria_label']); ?>">

    <button class="menu-toggle"
        aria-controls="secondary-menu" <!-- Changed to match menu_id -->
        aria-expanded="false">
        <span class="screen-reader-text"><?php echo esc_html($nav_labels['toggle_text']); ?></span>
        <span class="menu-toggle-icon"></span>
    </button>

    <?php wp_nav_menu($nav_args); ?>
</nav>