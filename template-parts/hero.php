<?php
$hero_bg = get_field('hero_bg_image');
$phrases = [get_field('hero_title')];
$jsonPhrases = json_encode($phrases);
?>


<section class="hero_section" style="background: linear-gradient(to right, rgba(245, 245, 245, 0.8), rgba(245, 245, 245, 0.8)) center center, url(<?= $hero_bg['url'] ?>);">
    <div class="container">
        <main>
            <h1>
                Hi, I'm <span class="typewrite" data-period="2000" data-type='<?= htmlspecialchars($jsonPhrases) ?>'></span> <span class="wrap"></span>
            </h1>

            <div class="hero_desc">
                <p> <?= get_field('hero_subtitle') ?> </p>
            </div>

            <div class="to_projects">

                <button class="cta">
                    <span> <?= get_field('hero_cta'); ?></span>
                    <svg width="15px" height="10px" viewBox="0 0 13 10">
                        <path d="M1,5 L11,5"></path>
                        <polyline points="8 1 12 5 8 9"></polyline>
                    </svg>
                </button>
            </div>
        </main>
    </div>
</section>

<script>
    const element = document.querySelector(".typewrite");

    const data = {
        toRotate: element.getAttribute("data-type"),
        period: element.getAttribute("data-period")
    };

    new TxtType(element, JSON.parse(data.toRotate), data.period);
</script>