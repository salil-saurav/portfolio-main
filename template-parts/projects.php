<?php

$project_args = [
    'post_type' => 'build',
    'posts_per_page' => -1,
    'order' => 'ASC',
];
$hero_bg = get_field('hero_bg_image');


$projects = new WP_Query($project_args);

?>

<section class="project_section" style="background: linear-gradient(to right, rgba(245, 245, 245, 0.8), rgba(245, 245, 245, 0.8)) center center, url(<?= $hero_bg['url'] ?>);">
    <div class="container">
        <h2 data-aos="fade-right">
            Projects
        </h2>
        <p data-aos="fade-right">
            Explore a selection of personal and client projects I've worked on, each accompanied by its own detailed case study.
        </p>

        <div class="project_container">
            <?php
            if ($projects->have_posts()) {
                while ($projects->have_posts()) {

                    $projects->the_post();
                    $project_img = get_field('project_screenshot')['id'];
                    $title = get_the_title();

            ?>

                    <div class="single_project">
                        <div class="project_lt" data-aos="fade-right">
                            <div class="project_img">
                                <a href="<?= get_field('project_link') ?>" target="_blank">
                                    <?= get_image($project_img, [
                                        'alt' => $title,
                                    ]) ?>
                                </a>
                            </div>
                        </div>
                        <div class="project_rt" data-aos="fade-left">

                            <div class="p_title">
                                <h3><?= $title ?></h3>
                            </div>
                            <div class="p_description">
                                <?= get_the_content(); ?>
                            </div>

                            <div class="tool_used">
                                <h4>Tool Used</h4>
                                <ul>
                                    <?php
                                    $initial = 400;
                                    if (have_rows('tool_used')) {
                                        while (have_rows('tool_used')) {
                                            the_row();

                                    ?>
                                            <li data-aos="fade-down" data-aos-duration="<?= $initial += 100 ?>">
                                                <?= get_sub_field('tool') ?>
                                            </li>
                                    <?php   }
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
            <?php
                    wp_reset_postdata();
                }
            }
            ?>
        </div>
    </div>
</section>