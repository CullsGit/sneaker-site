<?php

declare(strict_types=1);

get_header(); ?>

<main class="wrap">
    <h1><?php echo esc_html(post_type_archive_title('', false)); ?></h1>

    <?php if (have_posts()) : ?>
        <ul>
            <?php while (have_posts()) : the_post(); ?>
                <li>
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    <?php the_excerpt(); ?>
                </li>
            <?php endwhile; ?>
        </ul>

        <?php the_posts_pagination(); ?>
    <?php else : ?>
        <p><?php echo esc_html__('No sneakers found.', 'sneaker-theme'); ?></p>
    <?php endif; ?>
</main>

<?php get_footer();
