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

        // Actions

        add_action('login_enqueue_scripts', [$this, 'customize_login_styles'], 10, 1);
        // add_action('init', [$this, 'custom_login_rewrite_rule']);
        // add_action('init', [$this, 'redirect_wp_admin_to_404']);
        // add_action('wp_logout', [$this, 'custom_logout_redirect']);



        // Filters
        add_filter('login_headerurl', [$this, 'customize_logo_url']);
        add_filter('login_headertext', [$this, 'customize_logo_title']);
        add_filter('login_errors', [$this, 'customize_error_messages']);
        // add_filter('login_url', [$this, 'custom_login_url'], 10, 3);
    }

    /**
     * Custom login page styles
     */
    public function customize_login_styles()
    {
?>
        <style>
            @import url(https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=account_circle,lock);
            @import url(https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap);

            body.login {
                background-color: #f1f1f1;
                font-family: 'Poppins', sans-serif;
            }

            body.login #login {
                width: auto;
                padding: 5% 0 0;
                margin: auto;
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .login h1 a {
                background-image: url(<?php echo esc_url(get_theme_file_uri('assets/images/logo.jpg')); ?>) !important;
                background-size: contain !important;
                width: 320px !important;
                height: 120px !important;
            }

            .login form {
                background-color: #ffffff;
                border-radius: 4px !important;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1) !important;
            }

            .wp-core-ui .button-primary {
                background-color: #0073aa;
                border-color: #0073aa;
                transition: all 0.3s ease-in-out;
            }

            .wp-core-ui .button-primary:hover {
                background-color: #142f43 !important;
                border-color: #142f43 !important;
            }

            div#login-message {
                position: absolute;
                top: 12px;
                min-width: 250px;
                border: none;
                text-align: center;
                border-radius: 5px;
                right: 30px;
                padding: 15px;
                background: #137a4a;
                color: #fff;
                padding-right: 10px;
                opacity: 1;
                transition: all 0.3s ease-in-out;
            }

            div#login-message.closed {
                transform: translateY(-12px);
                opacity: 0;
            }


            div#login_error {
                position: absolute;
                top: 12px;
                min-width: 250px;
                border: none;
                text-align: center;
                border-radius: 5px;
                background: #da515e;
                color: #fff;
                right: 30px;
                padding: 15px;
                padding-right: 36px;
                opacity: 1;
                transition: all 0.3s ease-in-out;
            }

            div#login_error.closed {
                transform: translateY(-12px);
                opacity: 0;
            }

            form#loginform {
                display: flex;
                flex-direction: column;
                gap: 25px;
                min-width: 320px;
                margin: 0 auto;
                padding: 72px 55px;
                border-radius: 10px !important;
            }

            form#loginform p.submit {
                width: 100%;
                margin: 20px 0;
            }

            form#loginform p.submit input {
                width: 100%;
                border-radius: 25px;
                padding: 3px;
                font-size: 15px;
            }

            form#loginform input:not([type='submit']):not([type='checkbox']) {
                border: none;
                border-bottom: 1px solid #D3D3D3;
                font-size: 13px;
                margin: 12px 0;
                transition: all 0.3s ease-in-out;
                letter-spacing: -0.5px;
            }

            form#loginform input:not([type='submit']):not([type='checkbox']):focus {
                border-bottom: 1px solid;
                box-shadow: none;
            }

            form#loginform input.password-input {
                font-family: 'Poppins', sans-serif;
            }

            label:not([for='rememberme']) {
                display: none !important;
            }

            /* error box  */

            .close {
                position: absolute;
                right: 20px;
                top: 17px;
                width: 20px;
                height: 20px;
                opacity: 0.6;
            }

            .close:hover {
                opacity: 1;
            }

            .close:before,
            .close:after {
                position: absolute;
                left: 15px;
                content: ' ';
                height: 16px;
                width: 2px;
                background-color: #fff;
            }

            .close:before {
                transform: rotate(45deg);
            }

            .close:after {
                transform: rotate(-45deg);
            }
        </style>

        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const selectors = {
                    username: "input#user_login",
                    password: "input#user_pass",
                    errorBox: "login_error",
                    messageBox: "login-message"
                };

                const placeholders = {
                    username: "Type your username or Email address",
                    password: "Type your password"
                };

                function setInputPlaceholders() {
                    const userInput = document.querySelector(selectors.username);
                    const passInput = document.querySelector(selectors.password);

                    if (!userInput || !passInput) return;

                    userInput.placeholder = placeholders.username;
                    passInput.placeholder = placeholders.password;
                }

                function createCloseButton() {
                    const closeButton = document.createElement("a");
                    closeButton.classList.add("close");
                    closeButton.href = "javascript:void(0)";
                    return closeButton;
                }

                function handleClose(container) {
                    container.classList.add("closed");
                    setTimeout(() => container.style.display = 'none', 300);
                }

                function addCloseButtonTo(containerId) {
                    const container = document.getElementById(containerId);
                    if (!container) return;

                    const closeButton = createCloseButton();
                    container.appendChild(closeButton);
                    closeButton.addEventListener("click", () => handleClose(container));
                }

                // Initialize
                setInputPlaceholders();
                [selectors.errorBox, selectors.messageBox].forEach(addCloseButtonTo);

            })
        </script>
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


    public function custom_login_rewrite_rule()
    {
        add_rewrite_rule('^login$', 'wp-login.php', 'top');
    }

    // public function custom_login_url($login_url)
    // {
    //     return home_url('/login/');
    // }

    public function redirect_wp_login()
    {
        if (strpos($_SERVER['REQUEST_URI'], 'wp-login.php') !== false || strpos($_SERVER['REQUEST_URI'], 'login')) {
            wp_redirect(home_url('/wp-login.php'));
            exit;
        }
    }

    public function redirect_wp_admin_to_404()
    {
        if (is_admin() && !defined('DOING_AJAX') && !current_user_can('manage_options')) {
            global $wp_query;
            $wp_query->set_404();
            status_header(404);
            get_template_part('404');
            exit;
        }
    }

    public function custom_logout_redirect()
    {
        wp_redirect(home_url('/login'));
        exit;
    }
}

// Initialize the login customization
new Custom_Login_Manager();
