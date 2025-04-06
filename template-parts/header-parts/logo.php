<?php

if (!defined('ABSPATH')) exit;

if (function_exists('get_field')) {
    $site_logo = get_field("site_logo", "option");
?>

    <?php if ($site_logo) { ?>
        <div class="site-logo">
            <?= get_image($site_logo['id'], [
                'size' => [75,75]
            ]) ?>
        </div>

<?php }
} ?>