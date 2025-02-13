<?php

if (!defined('ABSPATH')) exit;

/**
 * Deletes ACF field data when a field is deleted.
 *
 * @param array $field The ACF field being deleted.
 */
function my_acf_delete_field_data($field)
{
    $field_key = $field['key'];

    // Delete postmeta data
    delete_post_meta_by_key($field_key);

    // Delete options data (if applicable)
    $option_key = 'option_' . $field_key;
    delete_option($option_key);
}
add_action('acf/delete_field', 'my_acf_delete_field_data', 10, 1);
