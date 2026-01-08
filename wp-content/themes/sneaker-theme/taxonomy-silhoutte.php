<?php

declare(strict_types=1);

get_header(); ?>

<main class="wrap">
    <h1><?php echo esc_html(single_term_title('', false)); ?></h1>

    <?php if (have_posts()) : ?>
        <div class="sneaker-grid">
            <?php while (have_posts()) : the_post(); ?>
                <?php get_template_part('parts/card', 'sneaker'); ?>
            <?php endwhile; ?>
        </div>

        <?php the_posts_pagination(); ?>
    <?php else : ?>
        <p><?php echo esc_html__('No sneakers found for this silhouette.', 'sneaker-theme'); ?></p>
    <?php endif; ?>
</main>

<?php get_footer();
