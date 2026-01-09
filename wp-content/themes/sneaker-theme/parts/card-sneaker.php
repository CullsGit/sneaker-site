<?php

declare(strict_types=1);

$sku          = (string) get_post_meta(get_the_ID(), '_sneaker_sku', true);
$retail_price = (string) get_post_meta(get_the_ID(), '_sneaker_retail_price', true);
$release_date = (string) get_post_meta(get_the_ID(), '_sneaker_release_date', true);

$brands = get_the_terms(get_the_ID(), 'brand');
$brand  = (!empty($brands) && !is_wp_error($brands)) ? $brands[0]->name : '';
$statuses = get_the_terms(get_the_ID(), 'status');
$status   = (!empty($statuses) && !is_wp_error($statuses)) ? $statuses[0]->name : '';

?>

<article <?php post_class('sneaker-card'); ?>>
    <a href="<?php the_permalink(); ?>" class="sneaker-card__image">
        <?php
        if (has_post_thumbnail()) {
            the_post_thumbnail('sneaker-cover');
        }
        ?>
    </a>

    <div class="sneaker-card__body">
        <h2 class="sneaker-card__title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h2>
        <?php if ($status !== '') : ?>
            <p class="sneaker-card__status"><?php echo esc_html($status); ?></p>
        <?php endif; ?>

        <?php if ($brand !== '') : ?>
            <p class="sneaker-card__brand"><?php echo esc_html($brand); ?></p>
        <?php endif; ?>

        <?php if ($retail_price !== '') : ?>
            <p class="sneaker-card__price">
                <?php echo esc_html('$' . number_format((float) $retail_price, 2)); ?>
            </p>
        <?php endif; ?>

        <?php if ($release_date !== '') : ?>
            <p class="sneaker-card__date">
                <?php echo esc_html(wp_date('j M Y', strtotime($release_date))); ?>
            </p>
        <?php endif; ?>
    </div>
</article>