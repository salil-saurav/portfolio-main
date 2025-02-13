# WordPress Child Theme Starter Guide

## Steps to Create a Child Theme

1. **Create Theme Directory**

    - Navigate to `wp-content/themes/`
    - Create a new folder: `your-theme-name-child`

2. **Essential Files**
   Create these two required files:

    - `style.css`
    - `functions.php`

3. **style.css Template**

```css
/*
Theme Name: Your Theme Name Child
Theme URI: https://example.com
Description: Child theme for Your Theme Name
Author: Your Name
Author URI: https://example.com
Template: parent-theme-folder-name
Version: 1.0
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: your-theme-name-child
*/
```

4. **functions.php Template**

```php
<?php
function my_child_theme_enqueue_styles() {
     wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
     wp_enqueue_style( 'child-style',
          get_stylesheet_directory_uri() . '/style.css',
          array( 'parent-style' ),
          wp_get_theme()->get('Version')
     );
}
add_action( 'wp_enqueue_scripts', 'my_child_theme_enqueue_styles' );
```

5. **Activation**
    - Go to WordPress Admin Panel
    - Navigate to Appearance > Themes
    - Activate your child theme

## Notes

-   Replace `parent-theme-folder-name` with actual parent theme's folder name
-   Always backup before making changes
-   Test thoroughly before deployment

## Customization

Override parent theme files by copying them to your child theme with identical path structure.
