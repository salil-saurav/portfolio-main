<?php

if (!defined('ABSPATH')) exit; ?>

<header id="header">
    <div class="container">
        <div class="head_wrap">
            <div class="header_lt" data-aos="fade-right">
                <div class="site_img">
                    <?= get_image(get_field('header_logo')['id'], [
                        'size' => [50, 50],
                        'alt' => 'Salil Saurav'
                    ])
                    ?>
                </div>
                <div class="site_name">
                    <?= get_field('header_name') ?>
                </div>
            </div>
            <div class="header_rt" data-aos="fade-left">
                <?php
                /*
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'container' => 'nav',
                    'container_class' => 'primary-menu-container',
                    'menu_class' => 'primary-menu',
                    'fallback_cb' => false // optional: disables fallback menu
                ));

                */
                ?>

                <div class="cta_container">
                    <div class="cta_inner">
                        <div class="header_mail cta_content"> <a href="mailto:<?= get_field('cta_icon_mail_text') ?>"> <span class="cta_icon"> <?= get_image(get_field('cta_icon_mail')['id'], ['size' => [45, 45]]) ?> </span> <span class="cta_text"> <?= get_field('cta_icon_mail_text') ?></span> </a> </div>
                    </div>
                    <div class="cta_inner">
                        <div class="header_call cta_content"> <a href="tel: <?= get_field('cta_icon_call_text')  ?>"> <span class="cta_icon"> <?= get_image(get_field('cta_icon_phone')['id'], ['size' => [45, 45]]) ?> </span> <span class="cta_text"> +91 <?= get_field('cta_icon_call_text')  ?></span> </a> </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>