<div class="site-logo">
    <?php
    if (has_custom_logo()) {
        the_custom_logo();
    } else {
    ?>
        <a href="<?php echo esc_url(home_url('/')); ?>" class="site-title">
            <?php bloginfo('name'); ?>
        </a>
    <?php
    }
    ?>
</div>