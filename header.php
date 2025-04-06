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

    <link rel="icon" type="image/png" href="<?= get_stylesheet_directory_uri() ?>/assets/images/favicon/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="<?= get_stylesheet_directory_uri() ?>/assets/images/favicon/favicon.svg" />
    <link rel="shortcut icon" href="<?= get_stylesheet_directory_uri() ?>/assets/images/favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="<?= get_stylesheet_directory_uri() ?>/assets/images/favicon/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="Salil Saurav" />
    <link rel="manifest" href="<?= get_stylesheet_directory_uri() ?>/assets/images/favicon/site.webmanifest" />
    <title><?php the_title() ?></title>

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
