<?php

if (!defined('ABSPATH')) exit;

function add_homepage_fields()
{

    acf_add_local_field_group([
        'key'     => 'group_homepage_fields',
        'title'   => 'Homepage ',
        'fields'  => [

            // Header Tab 

            [
                'key'           => 'tab_header',
                'label'         => 'Header',
                'type'          => 'tab',
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


            [
                'key'           => 'field_cta_icon_mail',
                'label'         => 'CTA Icon Mail',
                'name'          => 'cta_icon_mail',
                'type'          => 'image',
                'return_format' => 'array',
                'preview_size'  => 'thumbnail',
                'wrapper'       => ['width' => '50'],
            ],
            [
                'key'           => 'field_cta_icon_mail_text',
                'label'         => 'Mail Text',
                'name'          => 'cta_icon_mail_text',
                'type'          => 'text',
                'wrapper'       => ['width' => '50'],
            ],


            [
                'key'           => 'field_cta_icon_phone',
                'label'         => 'CTA Icon Phone',
                'name'          => 'cta_icon_phone',
                'type'          => 'image',
                'return_format' => 'array',
                'preview_size'  => 'thumbnail',
                'wrapper'       => ['width' => '50'],
            ],
            [
                'key'           => 'field_cta_icon_call_text',
                'label'         => 'Call Text',
                'name'          => 'cta_icon_call_text',
                'type'          => 'text',
                'wrapper'       => ['width' => '50'],
            ],



            // Hero Section ----------------------------------

            [
                'key'         => 'tab_hero',
                'label'       => 'Hero Section',
                'type'        => 'tab',
            ],
            [
                'key'           => 'field_hero_bg_image',
                'name'          => 'hero_bg_image',
                'label'         => 'BG Image',
                'type'          => 'image',
                'return_format' => 'array',
                'preview_size'  => 'thumbnail',
            ],
            [
                'key'           => 'field_hero_title',
                'name'          => 'hero_title',
                'label'         => 'Title',
                'type'          => 'text',
                'wrapper'       => ['width' => '33'],
            ],
            [
                'key'           => 'field_hero_subtitle',
                'name'          => 'hero_subtitle',
                'label'         => 'Sub Title',
                'type'          => 'textarea',
                'wrapper'       => ['width' => '33'],
            ],
            [
                'key'           => 'field_hero_cta',
                'name'          => 'hero_cta',
                'label'         => 'CTA Label',
                'type'          => 'text',
                'wrapper'       => ['width' => '33'],
            ],

            // Bio Section -------------------------------------------

            [
                'key'            => 'tab_bio',
                'label'          => 'Bio Section',
                'type'           => 'tab',
            ],
            [
                'key'            => 'field_bio_title',
                'name'           => 'bio_title',
                'label'          => 'Title',
                'type'           => 'text',
            ],
            [
                'key'            => 'field_bio_sub_title',
                'name'           => 'bio_sub_title',
                'label'          => 'Sub Title',
                'type'           => 'textarea',
                'rows'           => '3',
            ],
            [
                'key'            => 'field_skills',
                'label'          => 'Frontend Technologies',
                'name'           => 'skills',
                'type'           => 'repeater',
                'layout'         => 'table',
                'button_label'   => 'Add Skill',
                'sub_fields'     => [
                    [
                        'key'           => 'field_skill_icon',
                        'label'         => 'Icon',
                        'name'          => 'skill_icon',
                        'type'          => 'image',
                        'return_format' => 'array',
                        'preview_size'  => 'thumbnail',
                        'wrapper'       => ['width' => '20'],
                    ],
                    [
                        'key'     => 'field_skill',
                        'label'   => 'Skill',
                        'name'    => 'skill',
                        'type'    => 'text',
                        'wrapper' => ['width' => '30'],
                    ],
                    [
                        'key'     => 'field_skill_level',
                        'label'   => 'Skill Level',
                        'name'    => 'skill_level',
                        'type'    => 'number',
                        'wrapper' => ['width' => '20'],
                    ],
                    [
                        'key'     => 'field_skill_color',
                        'label'   => 'Skill Color',
                        'name'    => 'skill_color',
                        'type'    => 'color_picker',
                        'wrapper' => ['width' => '30'],
                    ],
                ],
            ],

            [
                'key'            => 'field_backend_skills',
                'label'          => 'Backend Technologies',
                'name'           => 'backend_skills',
                'type'           => 'repeater',
                'layout'         => 'table',
                'button_label'   => 'Add Skill',
                'sub_fields'     => [
                    [
                        'key'           => 'field_skill_icon',
                        'label'         => 'Icon',
                        'name'          => 'skill_icon',
                        'type'          => 'image',
                        'return_format' => 'array',
                        'preview_size'  => 'thumbnail',
                        'wrapper'       => ['width' => '20'],
                    ],
                    [
                        'key'     => 'field_skill',
                        'label'   => 'Skill',
                        'name'    => 'skill',
                        'type'    => 'text',
                        'wrapper' => ['width' => '30'],
                    ],
                    [
                        'key'     => 'field_skill_level',
                        'label'   => 'Skill Level',
                        'name'    => 'skill_level',
                        'type'    => 'number',
                        'wrapper' => ['width' => '20'],
                    ],
                    [
                        'key'     => 'field_skill_color',
                        'label'   => 'Skill Color',
                        'name'    => 'skill_color',
                        'type'    => 'color_picker',
                        'wrapper' => ['width' => '30'],
                    ],
                ],
            ],

            [
                'key'            => 'field_database_skills',
                'label'          => 'Database Technologies',
                'name'           => 'database_skills',
                'type'           => 'repeater',
                'layout'         => 'table',
                'button_label'   => 'Add Skill',
                'sub_fields'     => [
                    [
                        'key'           => 'field_skill_icon',
                        'label'         => 'Icon',
                        'name'          => 'skill_icon',
                        'type'          => 'image',
                        'return_format' => 'array',
                        'preview_size'  => 'thumbnail',
                        'wrapper'       => ['width' => '20'],
                    ],
                    [
                        'key'     => 'field_skill',
                        'label'   => 'Skill',
                        'name'    => 'skill',
                        'type'    => 'text',
                        'wrapper' => ['width' => '30'],
                    ],
                    [
                        'key'     => 'field_skill_level',
                        'label'   => 'Skill Level',
                        'name'    => 'skill_level',
                        'type'    => 'number',
                        'wrapper' => ['width' => '20'],
                    ],
                    [
                        'key'     => 'field_skill_color',
                        'label'   => 'Skill Color',
                        'name'    => 'skill_color',
                        'type'    => 'color_picker',
                        'wrapper' => ['width' => '30'],
                    ],
                ],
            ],

            [
                'key'            => 'field_cms_skills',
                'label'          => 'CMS',
                'name'           => 'cms_skills',
                'type'           => 'repeater',
                'layout'         => 'table',
                'button_label'   => 'Add Skill',
                'sub_fields'     => [
                    [
                        'key'           => 'field_skill_icon',
                        'label'         => 'Icon',
                        'name'          => 'skill_icon',
                        'type'          => 'image',
                        'return_format' => 'array',
                        'preview_size'  => 'thumbnail',
                        'wrapper'       => ['width' => '20'],
                    ],
                    [
                        'key'     => 'field_skill',
                        'label'   => 'Skill',
                        'name'    => 'skill',
                        'type'    => 'text',
                        'wrapper' => ['width' => '30'],
                    ],
                    [
                        'key'     => 'field_skill_level',
                        'label'   => 'Skill Level',
                        'name'    => 'skill_level',
                        'type'    => 'number',
                        'wrapper' => ['width' => '20'],
                    ],
                    [
                        'key'     => 'field_skill_color',
                        'label'   => 'Skill Color',
                        'name'    => 'skill_color',
                        'type'    => 'color_picker',
                        'wrapper' => ['width' => '30'],
                    ],
                ],
            ],

            [
                'key'            => 'field_javascript_lib_skills',
                'label'          => 'JavaScript Library',
                'name'           => 'javascript_lib_skills',
                'type'           => 'repeater',
                'layout'         => 'table',
                'button_label'   => 'Add Skill',
                'sub_fields'     => [
                    [
                        'key'           => 'field_skill_icon',
                        'label'         => 'Icon',
                        'name'          => 'skill_icon',
                        'type'          => 'image',
                        'return_format' => 'array',
                        'preview_size'  => 'thumbnail',
                        'wrapper'       => ['width' => '20'],
                    ],
                    [
                        'key'     => 'field_skill',
                        'label'   => 'Skill',
                        'name'    => 'skill',
                        'type'    => 'text',
                        'wrapper' => ['width' => '30'],
                    ],
                    [
                        'key'     => 'field_skill_level',
                        'label'   => 'Skill Level',
                        'name'    => 'skill_level',
                        'type'    => 'number',
                        'wrapper' => ['width' => '20'],
                    ],
                    [
                        'key'     => 'field_skill_color',
                        'label'   => 'Skill Color',
                        'name'    => 'skill_color',
                        'type'    => 'color_picker',
                        'wrapper' => ['width' => '30'],
                    ],
                ],
            ],
            [
                'key'            => 'field_devops_skills',
                'label'          => 'DevOps & Tools',
                'name'           => 'devops_skills',
                'type'           => 'repeater',
                'layout'         => 'table',
                'button_label'   => 'Add Skill',
                'sub_fields'     => [
                    [
                        'key'           => 'field_skill_icon',
                        'label'         => 'Icon',
                        'name'          => 'skill_icon',
                        'type'          => 'image',
                        'return_format' => 'array',
                        'preview_size'  => 'thumbnail',
                        'wrapper'       => ['width' => '20'],
                    ],
                    [
                        'key'     => 'field_skill',
                        'label'   => 'Skill',
                        'name'    => 'skill',
                        'type'    => 'text',
                        'wrapper' => ['width' => '30'],
                    ],
                    [
                        'key'     => 'field_skill_level',
                        'label'   => 'Skill Level',
                        'name'    => 'skill_level',
                        'type'    => 'number',
                        'wrapper' => ['width' => '20'],
                    ],
                    [
                        'key'     => 'field_skill_color',
                        'label'   => 'Skill Color',
                        'name'    => 'skill_color',
                        'type'    => 'color_picker',
                        'wrapper' => ['width' => '30'],
                    ],
                ],
            ],

            [
                'key'      => 'field_bio_description',
                'name'     => 'bio_description',
                'label'    => 'Bio Description',
                'type'     => 'wysiwyg',
                'toolbar'  => 'full',
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'page_type',
                    'operator' => '==',
                    'value'    => 'front_page',
                ],
            ],
        ],
        'active' => true,
    ]);
}

if (function_exists('acf_add_local_field_group')) {
    add_action('acf/init', 'add_homepage_fields');
}
