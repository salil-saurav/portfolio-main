<?php

/**
 * The template for displaying the footer
 *
 * @package WordPress
 */
?>

<footer class="site-footer">
    <?php
    // Get all PHP files from the components/footer directory
    $footer_components = glob(get_template_directory() . '/components/footer/*.php');

    // Loop through and include each component
    foreach ($footer_components as $component) {
        include($component);
    }
    ?>

    <div class="site-info">
        <div class="container">
            <div class="copyright">
                &copy; <?php echo date('Y'); ?> <?php echo get_bloginfo('name'); ?>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>

</body>

</html>