<?php

declare(strict_types=1);

get_header(); ?>

<main class="wrap">
    <h1><?php echo esc_html(post_type_archive_title('', false)); ?></h1>
    <?php
    $current_brand = isset($_GET['brand']) ? sanitize_title((string) $_GET['brand']) : '';
    $current_status = isset($_GET['status']) ? sanitize_title((string) $_GET['status']) : '';

    $brands = get_terms([
        'taxonomy'   => 'brand',
        'hide_empty' => false,
    ]);

    $statuses = get_terms([
        'taxonomy'   => 'status',
        'hide_empty' => false,
    ]);
    ?>

    <form method="get" action="<?php echo esc_url(get_post_type_archive_link('sneaker')); ?>">
        <label for="brand"><strong><?php echo esc_html__('Filter by brand:', 'sneaker-theme'); ?></strong></label>
        <select id="brand" name="brand" onchange="this.form.submit()">
            <option value=""><?php echo esc_html__('All brands', 'sneaker-theme'); ?></option>
            <?php foreach ($brands as $term) : ?>
                <option value="<?php echo esc_attr($term->slug); ?>" <?php selected($current_brand, $term->slug); ?>>
                    <?php echo esc_html($term->name); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <noscript><button type="submit"><?php echo esc_html__('Apply', 'sneaker-theme'); ?></button></noscript>

        <label for="status"><strong><?php echo esc_html__('Status:', 'sneaker-theme'); ?></strong></label>
        <select id="status" name="status" onchange="this.form.submit()">
            <option value=""><?php echo esc_html__('All statuses', 'sneaker-theme'); ?></option>
            <?php foreach ($statuses as $term) : ?>
                <option value="<?php echo esc_attr($term->slug); ?>" <?php selected($current_status, $term->slug); ?>>
                    <?php echo esc_html($term->name); ?>
                </option>
            <?php endforeach; ?>
        </select>

    </form>


    <?php if (have_posts()) : ?>
        <div class="sneaker-grid">
            <?php while (have_posts()) : the_post(); ?>
                <?php get_template_part('parts/card', 'sneaker'); ?>
            <?php endwhile; ?>
        </div>

        <?php the_posts_pagination(); ?>
    <?php else : ?>
        <p><?php echo esc_html__('No sneakers found.', 'sneaker-theme'); ?></p>
    <?php endif; ?>

</main>

<?php get_footer();
