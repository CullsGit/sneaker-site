<?php

declare(strict_types=1);

get_header(); ?>

<main class="wrap">
    <?php while (have_posts()) : the_post(); ?>
        <article <?php post_class('sneaker'); ?>>
            <h1><?php the_title(); ?></h1>

            <?php if (has_post_thumbnail()) : ?>
                <div class="sneaker__image">
                    <?php the_post_thumbnail('large'); ?>
                </div>
            <?php endif; ?>

            <?php
            // Prints assigned brands (taxonomy terms)
            $brands = get_the_terms(get_the_ID(), 'brand');
            if (!empty($brands) && !is_wp_error($brands)) :
                $brand_names = wp_list_pluck($brands, 'name');
            ?>
                <p><strong><?php echo esc_html__('Brand:', 'sneaker-theme'); ?></strong>
                    <?php echo esc_html(implode(', ', $brand_names)); ?>
                </p>
            <?php endif; ?>

            <div class="sneaker__content">
                <?php the_content(); ?>
            </div>
        </article>
    <?php endwhile; ?>
</main>

<?php get_footer();
