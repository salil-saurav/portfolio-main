<?php

/**
 * The template for displaying the footer
 *
 * @package WordPress
 */
?>

<footer class="footer">
    <div class="footer-container">

        <!-- Left: Name and Bio -->
        <div class="footer-section footer-about" data-aos="fade-right">
            <h3 class="footer-title"> <?= get_field('header_name', 'option') ?></h3>
            <p class="footer-description">
                <?= get_field('footer_description', 'option') ?>
            </p>
        </div>

        <!-- Right: Social Links -->
        <div class="footer-section footer-social" data-aos="fade-left">
            <h3 class="footer-social-title"><?= get_field('footer_connect_label', 'option') ?></h3>
            <div class="footer-icons">

                <?php
                if (have_rows('social_icons')) {
                    while (have_rows('social_icons')) {
                        the_row();

                        $platform_link = get_sub_field('platform_link');
                        $platform_text =   get_sub_field('platform_text')
                ?>

                        <a
                            href="<?= $platform_link ?>"
                            aria-label="<?= ucfirst($platform_text)  ?>"
                            data-social="<?= strtolower($platform_text) ?>"
                            target="_blank"
                            class="footer-icon">
                            <?= get_image(get_sub_field('platform_icon')['id'], [
                                'width' => [35, 35],
                                'alt' => $platform_text,
                            ]) ?>
                        </a>
                <?php
                    }
                }
                ?>
            </div>

        </div>


    </div>
    <div class="copy_section">
        <!-- Middle: Copyright -->
        <div class="footer-section footer-copy">
            <span>
                &copy; <?= date('Y') . get_field('footer_copy_content', 'option') ?>
            </span>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>

</body>

</html>