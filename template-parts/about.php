<?php

$bio_title         = get_field('bio_title');
$bio_sub_title     = get_field('bio_sub_title');
$bio_description   = get_field('bio_description');

$skill_repeater    = [
    'skills' => '💻',
    'backend_skills' => '🧠',
    'cms_skills' => '⚙️',
    'javascript_lib_skills' => '⚡',
    'devops_skills' => '☁️',
];

?>

<section class="bio_section">
    <div class="container">
        <h2> <?= $bio_title ?> </h2>

        <div class="bio_sub">
            <p> <?= $bio_sub_title ?></p>
        </div>

        <div class="skill_wrapper">

            <?php
            foreach ($skill_repeater as $skills => $skill_icon) {

                $field_object = get_field_object($skills);
            ?>
                <div class="skills">
                    <h3> <?= $skill_icon .  $field_object['label'] ?> </h3>

                    <ul>
                        <?php

                        $skill_group = get_field($skills);

                        if (!empty($skill_group)) {
                            foreach ($skill_group as $group) {

                                setup_postdata($group); ?>

                                <li>
                                    <?= get_image($group['skill_icon']['id'], [
                                        'size' => [30, 30],
                                        'alt' => $group['skill'],
                                    ]) ?>
                                    <span tooltip="<?= $group['slill_tooltip'] ?>"> <?= $group['skill'] ?> </span>
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
    </div>
</section>