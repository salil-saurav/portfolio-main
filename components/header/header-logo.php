<?php


function get_site_logo()
{
    if (has_custom_logo()) {
        return get_custom_logo();
    }

    return sprintf(
        '<a href="%s" class="site-title">%s</a>',
        esc_url(home_url('/')),
        esc_html(get_bloginfo('name'))
    );
}
?>

<div class="site-logo">
    <?= get_site_logo(); ?>
</div>