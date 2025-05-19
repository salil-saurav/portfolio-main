<?php

/**
 * The header for our theme
 *
 * @package WordPress
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Salil Saurav | Full-stack Developer</title>

    <?php wp_head(); ?>

    <script>
        class TxtType {
            constructor(el, toRotate, period) {
                this.toRotate = toRotate;
                this.el = el;
                this.loopNum = 0;
                this.period = parseInt(period, 10) || 2000;
                this.txt = "";
                this.isDeleting = false;
                this.tick();
            }

            tick() {
                const i = this.loopNum % this.toRotate.length;
                const fullTxt = this.toRotate[i];

                this.txt = this.isDeleting ?
                    fullTxt.substring(0, this.txt.length - 1) :
                    fullTxt.substring(0, this.txt.length + 1);

                this.el.innerHTML = `<span class="wrap">${this.txt}</span> <span class="cursor">|</span>`;

                let delta = 200 - Math.random() * 100;
                if (this.isDeleting) delta /= 2;

                if (!this.isDeleting && this.txt === fullTxt) {
                    delta = this.period;
                    this.isDeleting = true;
                } else if (this.isDeleting && this.txt === "") {
                    this.isDeleting = false;
                    this.loopNum++;
                    delta = 500;
                }
                setTimeout(() => this.tick(), delta);
            }
        }
    </script>

</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <!-- From Uiverse.io by wilsondesouza -->
    <ul class="icon_container">

        <?php

        if (have_rows('social_icons', 'option')) {
            while (have_rows('social_icons', 'option')) {
                the_row();

                $platform_link = get_sub_field('platform_link', 'option');
                $platform_text =   get_sub_field('platform_text', 'option')
        ?>

                <li class="icon-content">
                    <a
                        href="<?= $platform_link ?>"
                        aria-label="<?= ucfirst($platform_text)  ?>"
                        data-social="<?= strtolower($platform_text) ?>"
                        target="_blank">
                        <div class="filled"></div>
                        <?= get_image(get_sub_field('platform_icon')['id'], [
                            'width' => [35, 35],
                            'alt' => $platform_text,
                        ]) ?>
                    </a>
                    <div class="tooltip"><?= $platform_text ?></div>
                </li>
        <?php
            }
        }
        ?>
    </ul>