<?php
get_header();
?>
<div class="container">
    <main id="primary" class="site-main">
        <?php
        while (have_posts()) :
            the_post();
        ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <?php the_title('<h1 class="entry-title">', '</h1>'); ?>

                    <div class="entry-meta">
                        <?php
                        // Post meta information
                        echo sprintf(
                            '<span class="posted-on">Posted on %s</span>',
                            get_the_date()
                        );
                        ?>
                    </div>
                </header>

                <div class="entry-content">
                    <?php
                    the_content();

                    wp_link_pages(array(
                        'before' => '<div class="page-links">Pages:',
                        'after'  => '</div>',
                    ));
                    ?>
                </div>

                <footer class="entry-footer">
                    <?php
                    $categories = get_the_category();
                    if ($categories) {
                        echo '<span class="cat-links">Categories: ';
                        the_category(', ');
                        echo '</span>';
                    }

                    $tags = get_the_tags();
                    if ($tags) {
                        echo '<span class="tags-links">Tags: ';
                        the_tags('', ', ');
                        echo '</span>';
                    }
                    ?>
                </footer>
            </article>

        <?php
            // If comments are open or we have at least one comment
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;
        endwhile;
        ?>
    </main>
</div>
<?php
get_sidebar();
get_footer();
