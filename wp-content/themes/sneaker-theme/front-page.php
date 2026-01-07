<?php

declare(strict_types=1);

get_header(); ?>

<main class="wrap">
    <h1><?php echo esc_html(get_bloginfo('name')); ?></h1>

    <section>
        <h2><?php echo esc_html__('Latest Releases', 'sneaker-theme'); ?></h2>

        <?php
        $latest = new WP_Query([
            'post_type'      => 'sneaker',
            'posts_per_page' => 6,
            'meta_key'       => '_sneaker_release_date',
            'orderby'        => 'meta_value',
            'meta_type'      => 'DATE',
            'order'          => 'DESC',
            'meta_query'     => [
                [
                    'key'     => '_sneaker_release_date',
                    'compare' => 'EXISTS',
                ],
            ],
        ]);

        if ($latest->have_posts()) :
            echo '<ul>';
            while ($latest->have_posts()) : $latest->the_post();
                echo '<li>';
                if (has_post_thumbnail()) {
                    the_post_thumbnail('sneaker-cover');
                }
                echo '<a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a>';
                echo '</li>';
            endwhile;
            echo '</ul>';
            wp_reset_postdata();
        else :
            echo '<p>' . esc_html__('No releases yet.', 'sneaker-theme') . '</p>';
        endif;
        ?>
    </section>
</main>

<?php get_footer();
