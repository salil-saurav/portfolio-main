<?php

if (!defined('ABSPATH')) exit;

/**
 * Custom Login Page Customization
 * 
 * @package WordPress
 */

class Custom_Login_Manager
{
    /**
     * Initialize the login customizations
     */
    public function __construct()
    {
        $this->init_hooks();
    }

    /**
     * Initialize WordPress hooks
     */
    private function init_hooks()
    {
        add_action('login_enqueue_scripts', [$this, 'customize_login_styles']);
        add_filter('login_headerurl', [$this, 'customize_logo_url']);
        add_filter('login_headertext', [$this, 'customize_logo_title']);
        add_filter('login_errors', [$this, 'customize_error_messages']);
        add_filter('login_redirect', [$this, 'handle_login_redirect'], 10, 3);
        add_filter('login_url', [$this, 'customize_admin_url_slug']);
    }

    /**
     * Custom login page styles
     */
    public function customize_login_styles()
    {
?>
        <style type="text/css">
            body.login {
                background-color: #f1f1f1;
            }

            .login h1 a {
                background-image: url(<?php echo esc_url(get_theme_file_uri('assets/images/logo.png')); ?>);
                background-size: contain;
                width: 320px;
                height: 120px;
            }

            .login form {
                background-color: #ffffff;
                border-radius: 4px;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            }

            .wp-core-ui .button-primary {
                background-color: #0073aa;
                border-color: #0073aa;
            }
        </style>
<?php
    }

    /**
     * Customize login logo URL
     */
    public function customize_logo_url(): string
    {
        return home_url();
    }

    /**
     * Customize login logo title
     */
    public function customize_logo_title(): string
    {
        return get_bloginfo('name');
    }

    /**
     * Customize login error messages
     */
    public function customize_error_messages(): string
    {
        return 'Invalid credentials. Please try again.';
    }

    /**
     * Handle login redirect based on user role
     */
    public function handle_login_redirect($redirect_to, $request, $user)
    {
        if (!is_wp_error($user) && isset($user->roles) && is_array($user->roles)) {
            return in_array('administrator', $user->roles) ? admin_url() : home_url();
        }
        return $redirect_to;
    }

    /**
     * Customize admin URL slug
     */
    public function customize_admin_url_slug($url): string
    {
        return str_replace('wp-admin', 'login', $url);
    }
}

// Initialize the login customization
new Custom_Login_Manager();
