<?php

/**
 * The template for displaying 404 pages (Not Found)
 *
 */

get_header();

// Custom function to get recent posts for suggestions
function get_recent_posts_for_404()
{
    return get_posts([
        'posts_per_page' => 3,
        'post_status'    => 'publish'
    ]);
}
?>

<main class="error-404 not-found">
    <div class="container">
        <header class="error-header">
            <h1 class="error-title">
                <?php esc_html_e('404', 'your-theme-text-domain'); ?>
            </h1>
            <h2 class="error-subtitle">
                <?php esc_html_e('Page Not Found', 'your-theme-text-domain'); ?>
            </h2>
        </header>

        <div class="error-content">
            <p class="error-description">
                <?php esc_html_e('The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'your-theme-text-domain'); ?>
            </p>

            <div class="error-actions">
                <a href="<?= esc_url(home_url('/')); ?>" class="btn btn-primary">
                    <span class="icon-home"></span>
                    <?php esc_html_e('Return to Homepage', 'your-theme-text-domain'); ?>
                </a>
            </div>

            <div class="error-search">
                <h3><?php esc_html_e('Search our site', 'your-theme-text-domain'); ?></h3>
                <?php get_search_form(); ?>
            </div>

            <?php
            $recent_posts = get_recent_posts_for_404();
            if ($recent_posts) { ?>
                <div class="recent-posts">
                    <h3><?php esc_html_e('Recent Posts', 'your-theme-text-domain'); ?></h3>
                    <ul>
                        <?php foreach ($recent_posts as $post) { ?>
                            <li>
                                <a href="<?= esc_url(get_permalink($post)); ?>">
                                    <?= esc_html($post->post_title); ?>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>
        </div>
    </div>
</main>

<?php
get_footer();
