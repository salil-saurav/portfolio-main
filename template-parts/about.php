<?php

$bio_title         = get_field('bio_title');
$bio_sub_title     = get_field('bio_sub_title');
$bio_description   = get_field('bio_description');

$skill_repeater = [
    'skills' => '🧑‍💻',              // General skills - developer emoji
    'backend_skills' => '🔧',         // Backend - wrench/tool for server-side logic
    'database_skills' => '🗄️',        // Database - file cabinet or you can use 🛢️ (oil drum = database)
    'cms_skills' => '🧩',             // CMS - puzzle piece for modular systems
    'javascript_lib_skills' => '📦',  // JS Libs - package for libraries/modules
    'devops_skills' => '🚀',          // DevOps - rocket for deployment/automation
];

function lighten_color($hex, $percent)
{
    $hex = ltrim($hex, '#');
    if (strlen($hex) == 3) {
        $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
    }

    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));

    $r = min(255, round($r + (255 - $r) * $percent));
    $g = min(255, round($g + (255 - $g) * $percent));
    $b = min(255, round($b + (255 - $b) * $percent));

    return sprintf("#%02x%02x%02x", $r, $g, $b);
}

?>

<section class="bio_section">
    <div class="container">
        <h2 data-aos="zoom-in"> <?= $bio_title ?> </h2>

        <div class="bio_sub" data-aos="zoom-in">
            <p> <?= $bio_sub_title ?></p>
        </div>

        <div class="skill_wrapper">

            <?php
            foreach ($skill_repeater as $skills => $skill_icon) {

                $field_object = get_field_object($skills);
            ?>
                <div class="skills" data-aos="fade-up">
                    <h3> <?= $skill_icon .  $field_object['label'] ?> </h3>

                    <ul>
                        <?php

                        $skill_group = get_field($skills);

                        if (!empty($skill_group)) {
                            foreach ($skill_group as $group) {
                                setup_postdata($group); ?>

                                <li class="skill-bar-item">
                                    <div class="skill-label">
                                        <?= get_image($group['skill_icon']['id'], ['size' => [30, 30], 'alt' => $group['skill']]) ?>
                                        <span class="skill-name"><?= $group['skill'] ?></span>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="--progress: <?= intval($group['skill_level']) ?>%; background: linear-gradient(90deg, <?= lighten_color($group['skill_color'], 0.5) ?>, <?= $group['skill_color'] ?>);"></div>
                                    </div>
                                </li>
                        <?php
                            }
                        }
                        ?>
                    </ul>
                </div>
            <?php }
            wp_reset_postdata(); ?>
        </div>
        <div class="contact_cta">
            <?= cta(); ?>
        </div>
    </div>
</section>