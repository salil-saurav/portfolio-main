<?php

if (!defined('ABSPATH')) exit; ?>

<header id="header">
    <div class="container">
        <div class="head_wrap">
            <div class="header_lt">
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
            <div class="header_rt">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'container' => 'nav',
                    'container_class' => 'primary-menu-container',
                    'menu_class' => 'primary-menu',
                    'fallback_cb' => false // optional: disables fallback menu
                ));
                ?>
            </div>
        </div>
    </div>
</header>


<!-- From Uiverse.io by wilsondesouza -->
<ul class="icon_container">

    <?php

    if (have_rows('social_icons')) {
        while (have_rows('social_icons')) {
            the_row();

            $platform_link = get_sub_field('platform_link');
            $platform_text =   get_sub_field('platform_text')
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