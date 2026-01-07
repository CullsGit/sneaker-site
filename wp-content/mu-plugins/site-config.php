<?php

/**
 * Plugin Name: Site Config (Sneaker Site)
 * Description: Global site configuration (CPTs, taxonomies, and defaults).
 */

declare(strict_types=1);


add_action('init', function (): void {
    // 1) Custom Post Type: Sneaker
    // Creates:
    // - Admin menu item "Sneakers"
    // - URLs like /sneakers/ and /sneakers/air-jordan-1/
    register_post_type('sneaker', [
        'labels' => [
            'name'          => __('Sneakers', 'sneaker-theme'),
            'singular_name' => __('Sneaker', 'sneaker-theme'),
        ],
        'public'       => true,
        'show_in_rest' => true, // enables block editor + REST API support
        'has_archive'  => true,
        'menu_icon'    => 'dashicons-tag',
        'supports'     => ['title', 'editor', 'thumbnail', 'excerpt'],
        'rewrite'      => ['slug' => 'sneakers'],
    ]);

    // 2) Taxonomy: Brand
    // Creates:
    // - Brand selector on Sneaker edit screen
    // - URLs like /brand/nike/
    register_taxonomy('brand', ['sneaker'], [
        'labels' => [
            'name'          => __('Brands', 'sneaker-theme'),
            'singular_name' => __('Brand', 'sneaker-theme'),
        ],
        'public'       => true,
        'hierarchical' => true,  // behaves like Categories (parent/child)
        'show_in_rest' => true,
        'rewrite'      => ['slug' => 'brand'],
    ]);
});
