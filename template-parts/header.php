<?php

if (!defined('ABSPATH')) exit; ?>

<header id="header">
    <div class="container">
        <div class="head_wrap">
            <div class="header_lt" data-aos="fade-right">
                <div class="site_img">
                    <?= get_image(get_field('header_logo', 'option')['id'], [
                        'size' => [50, 50],
                        'alt' => get_field('header_name' . 'option'),
                    ])
                    ?>
                </div>
                <div class="site_name">
                    <?= get_field('header_name', 'option') ?>
                </div>
            </div>
            <div class="header_rt" data-aos="fade-left">

                <div class="cta_container">
                    <div class="cta_inner">
                        <div class="header_mail cta_content"> <a href="mailto:<?= get_field('cta_icon_mail_text', 'option') ?>"> <span class="cta_icon"> <?= get_image(get_field('cta_icon_mail', 'option')['id'], ['size' => [45, 45]]) ?> </span> <span class="cta_text"> <?= get_field('cta_icon_mail_text', 'option') ?></span> </a> </div>
                    </div>
                    <div class="cta_inner">
                        <div class="header_call cta_content"> <a href="tel: <?= get_field('cta_icon_call_text', 'option')  ?>"> <span class="cta_icon"> <?= get_image(get_field('cta_icon_phone', 'option')['id'], ['size' => [45, 45]]) ?> </span> <span class="cta_text"> +91 <?= get_field('cta_icon_call_text', 'option')  ?></span> </a> </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>