<?php

/**
 * The template for displaying 404 pages (Not Found)
 *
 */

get_header();

?>

<style>
    body {
        background-color: #000;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        overflow: hidden;
        font-family: 'Courier New', monospace;
    }

    .error-container {
        text-align: center;
        position: relative;
    }

    .error-404 {
        font-size: 5rem;
        font-weight: bold;
        color: #fff;
        text-shadow: 0 0 10px #0ff;
        animation: glitch 1s infinite alternate;
    }

    .error-text {
        font-size: 2rem;
        color: #fff;
        text-shadow: 0 0 5px #f0f;
        animation: glitch 1.2s infinite alternate-reverse;
    }

    @keyframes glitch {
        0% {
            text-shadow: 0 0 10px #0ff, 2px 0 0 #f0f, -2px 0 0 #0f0;
        }

        25% {
            transform: translateX(-2px);
            opacity: 0.8;
        }

        50% {
            text-shadow: 0 0 5px #f0f, -3px 0 0 #0ff, 3px 0 0 #0f0;
        }

        75% {
            transform: translateX(2px);
        }

        100% {
            text-shadow: 0 0 15px #0f0, 4px 0 0 #f0f, -4px 0 0 #0ff;
        }
    }

    /* Optional CRT scan lines */
    .scanlines {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: repeating-linear-gradient(to bottom,
                transparent 0%,
                rgba(255, 255, 255, 0.05) 1px,
                transparent 2px);
        pointer-events: none;
        z-index: 100;
    }

    footer.footer {
        position: fixed;
        bottom: 0;
        width: 100%;
    }

    /* ... (previous styles remain the same) ... */

    .back-link {
        display: inline-block;
        margin-top: 2rem;
        color: #fff;
        text-decoration: none;
        font-size: 1.2rem;
        padding: 0.5rem 1rem;
        border: 1px solid #0ff;
        border-radius: 0;
        background: rgba(0, 0, 0, 0.3);
        text-shadow: 0 0 5px #0ff;
        animation: glitch-link 2s infinite;
        transition: all 0.3s;
    }

    .back-link:hover {
        background: rgba(0, 255, 255, 0.2);
        box-shadow: 0 0 10px #0ff;
    }

    @keyframes glitch-link {

        0%,
        100% {
            transform: translateX(0);
        }

        20% {
            transform: translateX(-2px);
        }

        40% {
            transform: translateX(2px);
        }

        60% {
            transform: translateX(-1px);
        }

        80% {
            transform: translateX(1px);
        }
    }
</style>

<div class="error-container">
    <div class="error-404">404</div>
    <div class="error-text">Oops! The page you're looking for doesn't exist.</div>

    <a href="<?= site_url() ?>" class="back-link">Back to Profile</a>
</div>
<div class="scanlines"></div>

<!-- Optional: Add random glitch effect with JS -->
<script>
    const error404 = document.querySelector('.error-404');
    const errorText = document.querySelector('.error-text');

    function randomGlitch() {
        const glitchIntensity = Math.random() * 5;
        error404.style.transform = `translateX(${glitchIntensity * (Math.random() > 0.5 ? 1 : -1)}px)`;
        errorText.style.opacity = `${0.7 + Math.random() * 0.3}`;

        setTimeout(randomGlitch, 100 + Math.random() * 500);
    }

    randomGlitch();
</script>