<?php

declare(strict_types=1);

add_action('after_setup_theme', function (): void {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
    register_nav_menus([
        'primary' => __('Primary Menu', 'sneaker-theme'),
    ]);
});

add_action('wp_enqueue_scripts', function (): void {
    $uri = get_template_directory_uri();
    wp_enqueue_style('sneaker-theme', $uri . '/assets/css/main.css', [], '0.1.0');
    wp_enqueue_script('sneaker-theme', $uri . '/assets/js/main.js', [], '0.1.0', true);
});
