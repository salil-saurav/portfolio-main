<?php

if (!defined('ABSPATH')) exit;

/**
 * Template Name: Homepage
 */

get_header();
// Header
get_template_part('template-parts/header');
get_template_part('template-parts/hero');
get_template_part('template-parts/about');
get_template_part('template-parts/projects');

get_footer();
