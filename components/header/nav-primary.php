<?php
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Renders the primary navigation
 */

$nav_args = [
    'theme_location' => 'primary',
    'menu_id'       => 'primary-menu',
    'menu_class'    => 'nav-menu',
    'container'     => false,
    'fallback_cb'   => false,
];

$nav_labels = [
    'nav_aria_label' => __('Primary Navigation', 'your-theme-text-domain'),
    'toggle_text'    => __('Menu', 'your-theme-text-domain'),
];
?>

<nav id="site-navigation"
    class="main-navigation"
    role="navigation"
    aria-label="<?php echo esc_attr($nav_labels['nav_aria_label']); ?>">

    <button class="menu-toggle"
        aria-controls="primary-menu"
        aria-expanded="false">
        <span class="screen-reader-text"><?php echo esc_html($nav_labels['toggle_text']); ?></span>
        <span class="menu-toggle-icon"></span>
    </button>

    <?php wp_nav_menu($nav_args); ?>
</nav>