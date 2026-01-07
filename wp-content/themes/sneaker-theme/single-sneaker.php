<?php

declare(strict_types=1);

get_header(); ?>

<main class="wrap">
    <?php while (have_posts()) : the_post(); ?>
        <article <?php post_class('sneaker'); ?>>
            <h1><?php the_title(); ?></h1>
            <?php
            $sku          = (string) get_post_meta(get_the_ID(), '_sneaker_sku', true);
            $retail_price = (string) get_post_meta(get_the_ID(), '_sneaker_retail_price', true);
            $release_date = (string) get_post_meta(get_the_ID(), '_sneaker_release_date', true);

            $details = [];

            if ($sku !== '') {
                $details[] = ['SKU', $sku];
            }
            if ($retail_price !== '') {
                $details[] = ['Retail', '$' . number_format((float) $retail_price, 2)];
            }
            if ($release_date !== '') {
                $details[] = ['Release', $release_date];
            }
            ?>

            <?php if (!empty($details)) : ?>
                <dl class="sneaker__details">
                    <?php foreach ($details as [$label, $value]) : ?>
                        <dt><?php echo esc_html($label); ?></dt>
                        <dd><?php echo esc_html($value); ?></dd>
                    <?php endforeach; ?>
                </dl>
            <?php endif; ?>

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
