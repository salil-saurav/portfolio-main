<?php

/**
 * Footer Copyright Section
 */
?>
<div class="footer-copyright">
    <div class="container">
        <div class="copyright-text">
            &copy; <?= date('Y'); ?>
            <?php
            $site_name = get_bloginfo('name');
            echo esc_html($site_name);
            ?>
            - All Rights Reserved.
        </div>
    </div>
</div>