<?php

if (function_exists('acf_add_local_field_group')) {
    add_action('acf/init', 'my_acf_add_global_fields');

    function my_acf_add_global_fields()
    {
        acf_add_local_field_group(array(
            'key' => 'group_global_options',
            'title' => 'Global Options',
            'fields' => array(
                // Branding Tab
                array(
                    'key' => 'tab_branding',
                    'label' => 'Branding',
                    'type' => 'tab',
                ),
                array(
                    'key' => 'field_site_logo',
                    'label' => 'Site Logo',
                    'name' => 'site_logo',
                    'type' => 'image',
                    'return_format' => 'array',
                    'preview_size' => 'medium',
                ),
                array(
                    'key' => 'field_site_favicon',
                    'label' => 'Favicon',
                    'name' => 'site_favicon',
                    'type' => 'image',
                    'return_format' => 'array',
                    'preview_size' => 'thumbnail',
                ),
                array(
                    'key' => 'field_company_info',
                    'label' => 'Company Information',
                    'name' => 'company_info',
                    'type' => 'group',
                    'layout' => 'block',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_company_name',
                            'label' => 'Company Name',
                            'name' => 'company_name',
                            'type' => 'text',
                        ),
                        array(
                            'key' => 'field_address',
                            'label' => 'Address',
                            'name' => 'address',
                            'type' => 'textarea',
                        ),
                        array(
                            'key' => 'field_email',
                            'label' => 'Email',
                            'name' => 'email',
                            'type' => 'email',
                            'wrapper' => ['width' => '50']
                        ),
                        array(
                            'key' => 'field_phone_number',
                            'label' => 'Phone Number',
                            'name' => 'phone_number',
                            'type' => 'text',
                            'wrapper' => ['width' => '50']
                        ),
                    )
                ),

                // Social Media Tab
                array(
                    'key' => 'tab_social_media',
                    'label' => 'Social Media',
                    'type' => 'tab',
                ),
                array(
                    'key' => 'field_social_media_accounts',
                    'label' => 'Social Media Accounts',
                    'name' => 'social_media_accounts',
                    'type' => 'repeater',
                    'layout' => 'table',
                    'button_label' => 'Add Social Media',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_social_platform',
                            'label' => 'Platform',
                            'name' => 'platform',
                            'type' => 'select',
                            'choices' => array(
                                'facebook' => 'Facebook',
                                'instagram' => 'Instagram',
                                'twitter' => 'Twitter',
                                'linkedin' => 'LinkedIn',
                                'youtube' => 'YouTube',
                                'tiktok' => 'TikTok',
                                'pinterest' => 'Pinterest'
                            ),
                            'wrapper' => ['width' => '30']
                        ),
                        array(
                            'key' => 'field_social_url',
                            'label' => 'URL',
                            'name' => 'url',
                            'type' => 'url',
                            'wrapper' => ['width' => '70']
                        )
                    )
                ),

                // Analytics & SEO Tab
                array(
                    'key' => 'tab_analytics_seo',
                    'label' => 'Analytics & SEO',
                    'type' => 'tab',
                ),
                array(
                    'key' => 'field_google_analytics',
                    'label' => 'Google Analytics ID',
                    'name' => 'google_analytics_id',
                    'type' => 'text',
                    'placeholder' => 'UA-XXXXXXXXX-X or G-XXXXXXXXXX'
                ),
                array(
                    'key' => 'field_meta_tags',
                    'label' => 'Default Meta Tags',
                    'name' => 'default_meta_tags',
                    'type' => 'group',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_meta_title',
                            'label' => 'Meta Title',
                            'name' => 'meta_title',
                            'type' => 'text',
                        ),
                        array(
                            'key' => 'field_meta_description',
                            'label' => 'Meta Description',
                            'name' => 'meta_description',
                            'type' => 'textarea',
                        ),
                    )
                ),

                array(
                    'key' => 'smtp_configuration',
                    'label' => 'SMTP Configuration',
                    'type' => 'tab',
                ),

                array(
                    'key' => 'field_group_smtp_configuration',
                    'label' => 'SMTP Configuration',
                    'name' => 'group_smtp_configuration',
                    'type' => 'group',
                    'layout' => 'block', // Choose 'block' for a vertical layout or 'table' for a table-like layout
                    'sub_fields' => array(
                        array(
                            'key' => 'field_smtp_host',
                            'label' => 'SMTP Host',
                            'name' => 'smtp_host',
                            'type' => 'text',
                            'instructions' => 'Enter your SMTP server host.',
                            'wrapper' => [
                                'width' => '33'
                            ]
                        ),
                        array(
                            'key' => 'field_smtp_sender_name',
                            'label' => 'Sender Name',
                            'name' => 'smtp_sender_name',
                            'type' => 'text',
                            'instructions' => 'Enter the name to be displayed as the sender.',
                            'wrapper' => [
                                'width' => '33'
                            ]
                        ),
                        array(
                            'key' => 'field_smtp_port',
                            'label' => 'SMTP Port',
                            'name' => 'smtp_port',
                            'type' => 'text',
                            'instructions' => 'Enter the SMTP port number.',
                            'wrapper' => [
                                'width' => '33'
                            ]
                        ),
                        array(
                            'key' => 'field_smtp_email',
                            'label' => 'SMTP Email',
                            'name' => 'smtp_email',
                            'type' => 'text',
                            'instructions' => 'Enter the email address for SMTP.',
                            'wrapper' => [
                                'width' => '50'
                            ]
                        ),
                        array(
                            'key' => 'field_smtp_password',
                            'label' => 'SMTP Password',
                            'name' => 'smtp_password',
                            'type' => 'password',
                            'instructions' => 'Enter your SMTP password.',
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '50',
                                'class' => '',
                                'id' => '',
                            ),
                        ),
                    ),
                ),

                // Header Tab
                array(
                    'key' => 'tab_header',
                    'label' => 'Header',
                    'type' => 'tab',
                ),

                array(
                    'key' => 'field_header_cta_btn_group',
                    'label' => 'CTA Button',
                    'name' => 'header_cta_btn',
                    'type' => 'group',
                    'layout' => 'block', // Choose 'block' for a vertical layout or 'table' for a table-like layout
                    'sub_fields' => array(
                        array(
                            'key' => 'field_show_header_cta_btn',
                            'label' => 'Show CTA Button',
                            'name' => 'show_header_cta_btn',
                            'type' => 'true_false',
                            'ui' => 1, // Use toggle switch
                            'default_value' => 0, // Default to false
                        ),
                        array(
                            'key' => 'field_header_cta',
                            'label' => 'CTA Button',
                            'name' => 'header_cta',
                            'type' => 'link',
                            'return_format' => 'array', // or 'url', 'id', etc.
                        ),

                    ),
                ),


                // Footer Tab
                array(
                    'key' => 'tab_footer',
                    'label' => 'Footer',
                    'type' => 'tab',
                ),
                array(
                    'key' => 'field_footer_logo',
                    'label' => 'Logo',
                    'name' => 'footer_logo',
                    'type' => 'image', // Image upload field
                    'required' => 0,
                    'return_format' => 'array', // Options: 'array', 'id', 'url'
                    'preview_size' => 'thumbnail', // Preview size for the image
                ),

                array(
                    'key' => 'field_footer_content',
                    'label' => 'Content',
                    'name' => 'footer_content',
                    'type' => 'wysiwyg',
                    'toolbar' => 'full',
                ),

                // Scripts Tab
                array(
                    'key' => 'tab_global_codes',
                    'label' => 'Scripts',
                    'type' => 'tab',
                ),
                array(
                    'key' => 'field_global_header_code_group',
                    'label' => 'Header',
                    'name' => 'global_header_code',
                    'type' => 'group',
                    'layout' => 'block', // Choose 'block' for a vertical layout or 'table' for a table-like layout
                    'sub_fields' => array(
                        array(
                            'key' => 'field_global_header_html_code',
                            'label' => 'HTML',
                            'name' => 'global_header_html_code',
                            'type' => 'textarea',
                        ),
                        array(
                            'key' => 'field_global_header_css_code',
                            'label' => 'CSS',
                            'name' => 'global_header_css_code',
                            'type' => 'textarea',
                        ),
                        array(
                            'key' => 'field_global_header_js_code',
                            'label' => 'JavaScript',
                            'name' => 'global_header_js_code',
                            'type' => 'textarea',
                        ),
                    ),
                ),
                array(
                    'key' => 'field_global_footer_code_group',
                    'label' => 'Footer',
                    'name' => 'global_footer_code',
                    'type' => 'group',
                    'layout' => 'block', // Choose 'block' for a vertical layout or 'table' for a table-like layout
                    'sub_fields' => array(
                        array(
                            'key' => 'field_global_footer_html_code',
                            'label' => 'HTML',
                            'name' => 'global_header_html_code',
                            'type' => 'textarea',
                            'data-mode' => 'text/html'
                        ),
                        array(
                            'key' => 'field_global_footer_css_code',
                            'label' => 'CSS',
                            'name' => 'global_header_css_code',
                            'type' => 'textarea',
                        ),
                        array(
                            'key' => 'field_global_footer_js_code',
                            'label' => 'JavaScript',
                            'name' => 'global_header_js_code',
                            'type' => 'textarea',
                        ),
                    ),
                ),
                // Thank You Tab
                array(
                    'key' => 'tab_thank_you_page',
                    'label' => 'Thank You Page',
                    'type' => 'tab',
                ),
                array(
                    'key' => 'field_contact_us_thank_you_page_content',
                    'label' => 'Contact Us Thank You Content',
                    'name' => 'contact_us_thank_you_page_content',
                    'type' => 'text',
                ),

                array(
                    'key' => 'field_thank_you_page_cta_btn_label',
                    'label' => 'CTA Button Label',
                    'name' => 'thank_you_page_cta_btn_label',
                    'type' => 'text', // Text input field
                ),

                // breadcrumb
                array(
                    'key' => 'tab_breadcrumb',
                    'label' => 'Breadcrumb',
                    'type' => 'tab',
                ),
                array(
                    'key' => 'field_breadcrumb_shortcode',
                    'label' => 'Breadcrumb Shortcode',
                    'name' => 'breadcrumb_shortcode',
                    'type' => 'text',
                    'instructions' => 'Enter your breadcrumb shortcode here (e.g., [breadcrumb])',
                    'placeholder' => '[your_breadcrumb_shortcode]',
                    'default_value' => '[breadcrumb]',
                ),

            ),
            'location' => array(
                array(
                    array(
                        'param' => 'options_page',
                        'operator' => '==',
                        'value' => 'theme-general-options',
                    ),
                ),
            ),
            'style' => 'seamless',
            'active' => true,
        ));
    }
}
