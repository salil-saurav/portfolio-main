<?php
function custom_pagination($pages = '', $range = 2)
{
    // Early return if there's only one page
    if ($pages == 1) return;

    // Initialize variables
    $showitems = ($range * 2) + 1;
    $paged = get_current_page_number();
    $pages = get_total_pages($pages);

    // Don't show pagination if there's only one page
    if ($pages <= 1) return;

    echo '<div class="pagination">';
    render_previous_link($paged);
    render_page_numbers($paged, $pages, $range, $showitems);
    render_next_link($paged, $pages);
    echo '</div>';
}

function get_current_page_number()
{
    global $paged;
    return empty($paged) ? 1 : $paged;
}

function get_total_pages($pages)
{
    if ($pages == '') {
        global $wp_query;
        $pages = $wp_query->max_num_pages;
        return $pages ?: 1;
    }
    return $pages;
}

function render_previous_link($paged)
{
    if ($paged > 1) {
        echo '<a href="' . get_pagenum_link($paged - 1) . '" class="prev">&laquo; Previous</a>';
    }
}

function render_next_link($paged, $pages)
{
    if ($paged < $pages) {
        echo '<a href="' . get_pagenum_link($paged + 1) . '" class="next">Next &raquo;</a>';
    }
}

function render_page_numbers($paged, $pages, $range, $showitems)
{
    for ($i = 1; $i <= $pages; $i++) {
        $should_show = !($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems;

        if ($should_show) {
            if ($paged == $i) {
                echo '<span class="current">' . $i . '</span>';
            } else {
                echo '<a href="' . get_pagenum_link($i) . '" class="inactive">' . $i . '</a>';
            }
        }
    }
}

add_shortcode('theme-pagination', 'custom_pagination');
