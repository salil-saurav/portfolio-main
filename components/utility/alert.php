<?php

/**
 * Alert and Modal Component
 * 
 * @param string $type - success, danger, warning, info
 * @param string $message - Alert message
 * @param string $title - Modal title (optional)
 */

function show_alert($type, $message)
{
?>
    <div class="alert alert-<?php echo esc_attr($type); ?> alert-dismissible fade show" role="alert">
        <?php echo esc_html($message); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php
}

function show_modal($id, $title, $content)
{
?>
    <div class="modal fade" id="<?php echo esc_attr($id); ?>" tabindex="-1" aria-labelledby="<?php echo esc_attr($id); ?>Label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="<?php echo esc_attr($id); ?>Label"><?php echo esc_html($title); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php echo wp_kses_post($content); ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<?php
}

// Usage examples:
// show_alert('success', 'Operation completed successfully!');
// show_modal('exampleModal', 'Important Notice', 'This is the modal content.');
// To trigger modal: <button data-bs-toggle="modal" data-bs-target="#exampleModal">Open Modal</button>
?>