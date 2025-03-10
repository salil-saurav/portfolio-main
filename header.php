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
    <title><?php the_title() ?></title>

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <header class="site-header">
        <div class="container">
            <?php get_template_part('components/header/header-logo'); ?>
            <?php get_template_part('components/header/nav-primary'); ?>
        </div>
    </header>