<?php

if (function_exists('acf_add_local_field_group')) {
    add_action('acf/init', 'my_acf_add_global_fields');

    function my_acf_add_global_fields()
    {
        acf_add_local_field_group([
            'key' => 'group_global_options',
            'title' => 'Global Options',
            'fields' => [

                // Sitcky Icons 
                [
                    'key' => 'tab_sticky_icons',
                    'label' => 'Sticky Icons',
                    'type' => 'tab',
                ],

                [
                    'key'           => 'field_social_icons',
                    'label'         => 'Sticky Icons',
                    'name'          => 'social_icons',
                    'type'          => 'repeater',
                    'layout'        => 'table',
                    'button_label'  => 'Add Social',
                    'sub_fields'    => [
                        [
                            'key'           => 'field_platform_icon',
                            'label'         => 'Platform Icon',
                            'name'          => 'platform_icon',
                            'type'          => 'image',
                            'return_format' => 'array',
                            'preview_size'  => 'thumbnail',
                            'wrapper'       => ['width' => '20'],
                        ],
                        [
                            'key'           => 'field_platform_link',
                            'label'         => 'Platform Link',
                            'name'          => 'platform_link',
                            'type'          => 'text',
                            'wrapper'       => ['width' => '53'],
                        ],
                        [
                            'key'           => 'field_platform_text',
                            'label'         => 'Platform Text',
                            'name'          => 'platform_text',
                            'type'          => 'text',
                            'wrapper'       => ['width' => '33'],
                        ],
                    ],
                ],



                // Header Tab
                [
                    'key' => 'tab_header',
                    'label' => 'Header',
                    'type' => 'tab',
                ],

                [
                    'key'           => 'field_header_logo',
                    'name'          => 'header_logo',
                    'label'         => 'Logo',
                    'type'          => 'image',
                    'return_format' => 'array',
                    'preview_size'  => 'thumbnail',
                    'wrapper'       => ['width' => '50'],
                ],
                [
                    'key'           => 'field_header_name',
                    'name'          => 'header_name',
                    'label'         => 'Name',
                    'type'          => 'text',
                    'wrapper'       => ['width' => '50'],
                ],

                [
                    'key'           => 'field_cta_icon_mail',
                    'label'         => 'CTA Icon Mail',
                    'name'          => 'cta_icon_mail',
                    'type'          => 'image',
                    'return_format' => 'array',
                    'preview_size'  => 'thumbnail',
                    'wrapper'       => ['width' => '25'],
                ],
                [
                    'key'           => 'field_cta_icon_mail_text',
                    'label'         => 'Mail Text',
                    'name'          => 'cta_icon_mail_text',
                    'type'          => 'text',
                    'wrapper'       => ['width' => '25'],
                ],

                [
                    'key'           => 'field_cta_icon_phone',
                    'label'         => 'CTA Icon Phone',
                    'name'          => 'cta_icon_phone',
                    'type'          => 'image',
                    'return_format' => 'array',
                    'preview_size'  => 'thumbnail',
                    'wrapper'       => ['width' => '25'],
                ],
                [
                    'key'           => 'field_cta_icon_call_text',
                    'label'         => 'Call Text',
                    'name'          => 'cta_icon_call_text',
                    'type'          => 'text',
                    'wrapper'       => ['width' => '25'],
                ],




                // Footer Tab
                [
                    'key' => 'tab_footer',
                    'label' => 'Footer',
                    'type' => 'tab',
                ],
                [
                    'key' => 'field_footer_description',
                    'label' => 'Footer Description',
                    'name' => 'footer_description',
                    'type' => 'textarea',
                    'rows' => '4',
                ],
                [
                    'key' => 'field_footer_connect_label',
                    'label' => 'Connect Label',
                    'name' => 'footer_connect_label',
                    'type' => 'text',
                ],
                [
                    'key' => 'field_footer_copy_content',
                    'label' => 'Copy Content',
                    'name' => 'footer_copy_content',
                    'type' => 'text',
                ],
            ],
            'location' => [
                [
                    [
                        'param' => 'options_page',
                        'operator' => '==',
                        'value' => 'theme-general-options',
                    ],
                ],
            ],
            'style' => 'seamless',
            'active' => true,
        ]);
    }
}
