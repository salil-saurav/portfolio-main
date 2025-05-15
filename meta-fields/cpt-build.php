<?php

if (!defined('ABSPATH')) exit;


function add_builds_fields()
{

    acf_add_local_field_group([
        'key' => 'group_builds_fields',
        'title' => 'Builds',
        'fields' => [

            [
                'key' => 'field_project_screenshot',
                'name' => 'project_screenshot',
                'label' => 'Project Screenshot',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'thumbnail',
                'wrapper' => ['width' => '50'],
            ],
            [
                'key' => 'field_project_link',
                'name' => 'project_link',
                'label' => 'Project Link',
                'type' => 'text',
                'wrapper' => ['width' => '50'],
            ],
            [
                'key' => 'field_tool_used',
                'label' => 'Tool Used',
                'name' => 'tool_used',
                'type' => 'repeater',
                'layout' => 'table',
                'button_label' => 'Add Tool',
                'sub_fields' => [
                    [
                        'key' => 'field_tool',
                        'label' => 'Tool',
                        'name' => 'tool',
                        'type' => 'text',
                    ],

                ],
            ],

        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'build',
                ],
            ],
        ],
        'style' => 'seamless',
        'active' => true,
    ]);
}

if (function_exists('acf_add_local_field_group')) {
    add_action('acf/init', 'add_builds_fields');
}
