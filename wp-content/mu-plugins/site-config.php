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

add_action('add_meta_boxes', function (): void {
    add_meta_box(
        'sneaker_details',
        __('Sneaker Details', 'sneaker-theme'),
        function (WP_Post $post): void {
            $sku          = (string) get_post_meta($post->ID, '_sneaker_sku', true);
            $retail_price = (string) get_post_meta($post->ID, '_sneaker_retail_price', true);
            $release_date = (string) get_post_meta($post->ID, '_sneaker_release_date', true);

            wp_nonce_field('sneaker_details_save', 'sneaker_details_nonce');
?>
        <p>
            <label for="sneaker_sku"><strong><?php echo esc_html__('SKU', 'sneaker-theme'); ?></strong></label><br>
            <input type="text" id="sneaker_sku" name="sneaker_sku" value="<?php echo esc_attr($sku); ?>" class="regular-text" />
        </p>

        <p>
            <label for="sneaker_retail_price"><strong><?php echo esc_html__('Retail price (number)', 'sneaker-theme'); ?></strong></label><br>
            <input type="number" step="0.01" min="0" id="sneaker_retail_price" name="sneaker_retail_price" value="<?php echo esc_attr($retail_price); ?>" class="regular-text" />
        </p>

        <p>
            <label for="sneaker_release_date"><strong><?php echo esc_html__('Release date (YYYY-MM-DD)', 'sneaker-theme'); ?></strong></label><br>
            <input type="date" id="sneaker_release_date" name="sneaker_release_date" value="<?php echo esc_attr($release_date); ?>" />
        </p>
<?php
        },
        'sneaker',
        'side',
        'default'
    );
});

add_action('save_post_sneaker', function (int $post_id): void {
    // 1) Nonce check
    if (
        empty($_POST['sneaker_details_nonce']) ||
        ! wp_verify_nonce((string) $_POST['sneaker_details_nonce'], 'sneaker_details_save')
    ) {
        return;
    }

    // 2) Autosave / revision guard
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // 3) Capability check
    if (! current_user_can('edit_post', $post_id)) {
        return;
    }

    // 4) Sanitise + save
    $sku = isset($_POST['sneaker_sku']) ? sanitize_text_field((string) $_POST['sneaker_sku']) : '';
    update_post_meta($post_id, '_sneaker_sku', $sku);

    $price_raw = isset($_POST['sneaker_retail_price']) ? (string) $_POST['sneaker_retail_price'] : '';
    $price     = ($price_raw === '') ? '' : (string) (float) $price_raw;
    update_post_meta($post_id, '_sneaker_retail_price', $price);

    $date_raw = isset($_POST['sneaker_release_date']) ? (string) $_POST['sneaker_release_date'] : '';
    $date     = preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_raw) ? $date_raw : '';
    update_post_meta($post_id, '_sneaker_release_date', $date);
});

add_action('pre_get_posts', function (WP_Query $query): void {
    if (is_admin() || ! $query->is_main_query()) {
        return;
    }

    // Only target the Sneaker archive: /sneakers/
    if (! $query->is_post_type_archive('sneaker')) {
        return;
    }

    $query->set('meta_key', '_sneaker_release_date');
    $query->set('orderby', 'meta_value');
    $query->set('meta_type', 'DATE');
    $query->set('order', 'DESC');

    // Optional: only show sneakers that actually have a release date set
    $query->set('meta_query', [
        [
            'key'     => '_sneaker_release_date',
            'compare' => 'EXISTS',
        ],
    ]);
    $brand = isset($_GET['brand']) ? sanitize_title((string) $_GET['brand']) : '';

    if ($brand !== '') {
        $query->set('tax_query', [
            [
                'taxonomy' => 'brand',
                'field'    => 'slug',
                'terms'    => [$brand],
            ],
        ]);
    }
});
