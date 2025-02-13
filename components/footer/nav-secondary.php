<?php
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Renders the secondary navigation
 */
function render_secondary_navigation(): void
{
    $nav_args = [
        'theme_location' => 'secondary',  // Changed to secondary
        'menu_id'       => 'secondary-menu', // Changed to secondary-menu
        'menu_class'    => 'nav-menu',
        'container'     => false,
        'fallback_cb'   => false,
    ];

    $nav_labels = [
        'nav_aria_label' => __('Secondary Navigation', 'your-theme-text-domain'), // Changed label
        'toggle_text'    => __('Secondary Menu', 'your-theme-text-domain'), // Changed text
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
<?php
}

// Render the secondary navigation
render_secondary_navigation();
