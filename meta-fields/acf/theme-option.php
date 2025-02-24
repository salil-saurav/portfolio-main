<?php

if (function_exists('acf_add_local_field_group')) {

    add_action('acf/init', 'my_acf_add_global_fields');

    function my_acf_add_global_fields()
    {
        acf_add_local_field_group(array(
            'key' => 'group_global_options',
            'title' => 'Global Options',
            'fields' => array(

                // Global Tab
                array(
                    'key' => 'tab_global',
                    'label' => 'Global',
                    'type' => 'tab',
                ),

                array(
                    'key' => 'field_site_logo',
                    'label' => 'Site Logo',
                    'name' => 'site_logo',
                    'type' => 'image', // Image upload field
                    'required' => 0,
                    'return_format' => 'array', // Options: 'array', 'id', 'url'
                    'preview_size' => 'thumbnail', // Preview size for the image
                ),
                array(
                    'key' => 'field_address',
                    'label' => 'Address',
                    'name' => 'address',
                    'type' => 'text', // Text input field
                    'required' => 0,
                ),
                array(
                    'key' => 'field_email',
                    'label' => 'Email',
                    'name' => 'email',
                    'type' => 'text', // Text input field
                    'required' => 0,
                    'wrapper' => [
                        'width' => '50'
                    ]
                ),
                array(
                    'key' => 'field_phone_number',
                    'label' => 'Phone Number',
                    'name' => 'phone_number',
                    'type' => 'text', // Text input field
                    'required' => 0,
                    'wrapper' => [
                        'width' => '50'
                    ]
                ),
                array(
                    'key' => 'field_social_media_group',
                    'label' => 'Social Media',
                    'name' => 'social_media',
                    'type' => 'group',
                    'layout' => 'block', // Choose 'block' for a vertical layout or 'table' for a table-like layout
                    'sub_fields' => array(
                        array(
                            'key' => 'field_social_media_facebook',
                            'label' => 'Facebook',
                            'name' => 'social_media_facebook',
                            'type' => 'text',
                            'wrapper' => [
                                'width' => '50'
                            ]
                        ),
                        array(
                            'key' => 'field_social_media_instagram',
                            'label' => 'Instagram',
                            'name' => 'social_media_instagram',
                            'type' => 'text',
                            'wrapper' => [
                                'width' => '50'
                            ]
                        ),
                        array(
                            'key' => 'field_social_media_tiktok',
                            'label' => 'Tiktok',
                            'name' => 'social_media_tiktok',
                            'type' => 'text',
                            'wrapper' => [
                                'width' => '50'
                            ]
                        ),
                        array(
                            'key' => 'field_social_media_x',
                            'label' => 'X',
                            'name' => 'social_media_x',
                            'type' => 'text',
                            'wrapper' => [
                                'width' => '50'
                            ]
                        ),
                    ),
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
                        // array(
                        //     'key' => 'field_header_cta_btn_link',
                        //     'label' => 'CTA Button Link',
                        //     'name' => 'header_cta_btn_link',
                        //     'type' => 'post_object', // Use post_object field type
                        //     'post_type' => array('page'), // Only show pages
                        //     'return_format' => 'id', // Return the page ID
                        //     'multiple' => false, // Single selection
                        // ),
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


                // array(
                //     'key' => 'field_footer_column_4_images',
                //     'label' => 'Column 4 Images',
                //     'name' => 'footer_column_4_images',
                //     'type' => 'repeater',
                //     'layout' => 'table', // Choose 'block' for a vertical layout or 'table' for a table-like layout
                //     'sub_fields' => array(
                //         array(
                //             'key' => 'field_footer_column_4_image',
                //             'label' => 'Image',
                //             'name' => 'footer_column_4_image',
                //             'type' => 'image', // Image upload field
                //             'required' => 0,
                //             'return_format' => 'array', // Options: 'array', 'id', 'url'
                //             'preview_size' => 'thumbnail', // Preview size for the image
                //         ),
                //     ),
                // ),

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

            ),
            'location' => array(
                array(
                    array(
                        'param' => 'options_page',
                        'operator' => '==',
                        'value' => 'theme-general-options', // Match the slug from the options page
                    ),
                ),
            ),
            'active' => 1,
            'description' => '',
        ));
    }
}
