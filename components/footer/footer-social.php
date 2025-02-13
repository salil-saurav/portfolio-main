<?php

/**
 * Footer Social Links Component
 */

$social_networks = [
    'facebook'   => ['icon' => 'fab fa-facebook'],
    'twitter'    => ['icon' => 'fab fa-twitter'],
    'instagram'  => ['icon' => 'fab fa-instagram'],
    'linkedin'   => ['icon' => 'fab fa-linkedin']
];
?>

<div class="footer-social">
    <ul class="social-links">
        <?php foreach ($social_networks as $network => $data) {
            $url = get_theme_mod("social_{$network}");
            if ($url) { ?>
                <li>
                    <a href="<?= esc_url($url); ?>"
                        target="_blank"
                        rel="noopener noreferrer">
                        <i class="<?= esc_attr($data['icon']); ?>"></i>
                    </a>
                </li>
        <?php };
        }; ?>
    </ul>
</div>