<?php

if (!defined('ABSPATH')) exit;

// Enqueue CodeMirror
function enqueue_custom_admin_assets()
{

    // Enqueue FontAwesome
    wp_enqueue_style('fontawesome-css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');

    // Enqueue CodeMirror 5.65.17
    wp_enqueue_style('codemirror-css', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.17/codemirror.min.css');
    wp_enqueue_script('codemirror-js', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.17/codemirror.min.js', array(), null, true);

    // Enqueue modes and addons
    wp_enqueue_script('codemirror-html', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.17/mode/xml/xml.min.js', array('codemirror-js'), null, true);
    wp_enqueue_script('codemirror-css', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.17/mode/css/css.min.js', array('codemirror-js'), null, true);
    wp_enqueue_script('codemirror-javascript', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.17/mode/javascript/javascript.min.js', array('codemirror-js'), null, true);

    // Enqueue addons for autoclosing
    wp_enqueue_script('codemirror-autoclosetags', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.17/addon/edit/closebrackets.min.js', array('codemirror-js'), null, true);
    wp_enqueue_script('codemirror-autoclosetag', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.17/addon/edit/closetag.min.js', array('codemirror-js'), null, true);

    wp_enqueue_script('jquery-ui-accordion');
    wp_enqueue_style('jquery-ui-css', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');

    add_action('admin_footer', function () {
?>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Initialize CodeMirror for each textarea
                document.querySelectorAll('textarea.codemirror').forEach(textarea => {
                    const mode = textarea.dataset.mode;

                    const cm = CodeMirror.fromTextArea(textarea, {
                        lineNumbers: true,
                        mode: mode,
                        theme: 'default',
                        extraKeys: {
                            "Ctrl-Space": "autocomplete"
                        },
                        autoCloseBrackets: true,
                        autoCloseTags: true
                    });
                });

                // Accordion functionality
                const accordionHeaders = document.querySelectorAll('.accordion-header');
                accordionHeaders.forEach(header => {
                    header.addEventListener('click', () => {
                        const content = header.nextElementSibling;
                        const icon = header.querySelector('.accordion-icon');

                        content.style.display = content.style.display === 'none' ? 'block' : 'none';
                        icon.classList.toggle('open');
                    });
                });

                // Collapsing by default
                document.querySelectorAll('.accordion-content').forEach(content => {
                    content.style.display = 'none';
                });
            });
        </script>
        <style>
            .accordion-header {
                cursor: pointer;
                padding: 10px;
                background-color: #f1f1f1;
                border: 1px solid #ccc;
                margin-bottom: 5px;
                position: relative;
            }

            .accordion-icon {
                position: absolute;
                right: 10px;
                top: 10px;
                transition: transform 0.2s;
            }

            .accordion-icon.open {
                transform: rotate(180deg);
            }

            .accordion-content .CodeMirror {
                border: 1px solid #ccc;
                margin-bottom: 5px;
            }
        </style>
    <?php
    });
}
add_action('admin_enqueue_scripts', 'enqueue_custom_admin_assets');


// Add Header/Footer Code to each Page
function add_custom_meta_boxes()
{
    $post_types = ['post', 'page'];
    $meta_boxes = [
        [
            'id' => 'header_code_meta_box',
            'title' => 'Header Code',
            'callback' => 'render_header_code_meta_box'
        ],
        [
            'id' => 'footer_code_meta_box',
            'title' => 'Footer Code',
            'callback' => 'render_footer_code_meta_box'
        ]
    ];

    foreach ($post_types as $post_type) {
        foreach ($meta_boxes as $box) {
            add_meta_box(
                $box['id'],
                $box['title'],
                $box['callback'],
                $post_type,
                'normal',
                'high'
            );
        }
    }
}
add_action('add_meta_boxes', 'add_custom_meta_boxes');

// Add Metabox
function render_code_section($type, $language, $value, $mode)
{
    ?>
    <div class="accordion-header"><?php echo esc_html($language); ?> <i class="fa fa-chevron-down accordion-icon"></i></div>
    <div class="accordion-content">
        <textarea name="<?php echo esc_attr($type); ?>" class="codemirror" data-mode="<?php echo esc_attr($mode); ?>" style="width: 100%; height: 100px;"><?php echo esc_textarea($value); ?></textarea>
    </div>
<?php
}

function render_header_code_meta_box($post)
{
    $sections = [
        ['html', 'HTML', 'text/html', '_header_html'],
        ['css', 'CSS', 'text/css', '_header_css'],
        ['js', 'JavaScript', 'text/javascript', '_header_js']
    ];

    foreach ($sections as [$type, $language, $mode, $meta_key]) {
        $value = get_post_meta($post->ID, $meta_key, true);
        render_code_section("header_{$type}", $language, $value, $mode);
    }
}

function render_footer_code_meta_box($post)
{
    $sections = [
        ['html', 'HTML', 'text/html', '_footer_html'],
        ['css', 'CSS', 'text/css', '_footer_css'],
        ['js', 'JavaScript', 'text/javascript', '_footer_js']
    ];

    foreach ($sections as [$type, $language, $mode, $meta_key]) {
        $value = get_post_meta($post->ID, $meta_key, true);
        render_code_section("footer_{$type}", $language, $value, $mode);
    }
}

// Save Meta Box Data

function save_custom_meta_boxes($post_id)
{
    // Verify nonce and autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    // Fields to save
    $fields = [
        'header' => ['html', 'css', 'js'],
        'footer' => ['html', 'css', 'js']
    ];

    // Save each field
    foreach ($fields as $location => $types) {
        foreach ($types as $type) {
            $field_name = "{$location}_{$type}";
            if (isset($_POST[$field_name])) {
                $value = sanitize_textarea_field($_POST[$field_name]);
                update_post_meta($post_id, "_{$field_name}", $value);
            }
        }
    }
}

add_action('save_post', 'save_custom_meta_boxes');


// Output Script in Frontend

function output_custom_header_scripts()
{
    if (!is_single() && !is_page()) return;

    global $post;

    // Output header HTML
    if ($header_html = get_post_meta($post->ID, '_header_html', true)) {
        echo $header_html;
    }

    // Output header CSS
    if ($header_css = get_post_meta($post->ID, '_header_css', true)) {
        printf('<style>%s</style>', $header_css);
    }

    // Output header JS
    if ($header_js = get_post_meta($post->ID, '_header_js', true)) {
        printf('<script>%s</script>', wp_kses($header_js, array()));
    }
}

function output_custom_footer_scripts()
{
    if (!is_single() && !is_page()) return;

    global $post;

    // Output footer HTML
    if ($footer_html = get_post_meta($post->ID, '_footer_html', true)) {
        echo $footer_html;
    }

    // Output footer CSS
    if ($footer_css = get_post_meta($post->ID, '_footer_css', true)) {
        printf('<style>%s</style>', $footer_css);
    }

    // Output footer JS
    if ($footer_js = get_post_meta($post->ID, '_footer_js', true)) {
        printf('<script>%s</script>', wp_kses($footer_js, array()));
    }
}

add_action('wp_head', 'output_custom_header_scripts');
add_action('wp_footer', 'output_custom_footer_scripts');
