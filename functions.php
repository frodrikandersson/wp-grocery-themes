<?php

// FUNCTIONS

function mygrocery_enqueue_styles() {
    wp_enqueue_style('mygrocery-style', get_stylesheet_uri());
}

function mygrocery_register_menus() {
    register_nav_menu('primary', __('Primary Menu', 'my-custom-theme'));
}


function mygrocery_enqueue_scripts() {
    wp_enqueue_script('mygrocery-menu', get_template_directory_uri() . '/assets/js/menu.js', array('jquery'), '1.0', true);
}

function mygrocery_quantity_input_loop() {
    global $product;
    if ( $product->is_type('simple') ) {
        woocommerce_quantity_input( array(
            'min_value'   => 1,
            'max_value'   => $product->get_stock_quantity() ?: 99,
            'input_value' => 1,
        ), $product );
    }
}

function mygrocery_wrap_product_form_start() {
    global $product;
    if ( $product->is_type('simple') ) {
        echo '<form class="cart" action="' . esc_url( $product->add_to_cart_url() ) . '" method="post" enctype="multipart/form-data">';
    }
}

function mygrocery_wrap_product_form_end() {
    global $product;
    if ( $product->is_type('simple') ) {
        echo '</form>';
    }
}

function add_thumbnail_to_grouped_products( $child_product ) {
    if ( ! $child_product ) return;

    $thumbnail = get_the_post_thumbnail(
        $child_product->get_id(), 
        'thumbnail', 
        array(
            'style' => 'border-radius:8px; width:60px; height:auto; margin-right:15px;'
        )
    );

    echo '<td class="woocommerce-grouped-product-list-item__thumb">' . $thumbnail . '</td>';
}

function mygrocery_grouped_total_price( $price_html, $product ) {
    if ( $product->is_type( 'grouped' ) ) {
        $total = 0;
        $children = $product->get_children();

        foreach ( $children as $child_id ) {
            $child_product = wc_get_product( $child_id );
            if ( $child_product ) {
                $total += floatval( $child_product->get_price() );
            }
        }

        $price_html = wc_price( $total );
    }

    return $price_html;
}

// ACTIONS


add_action('wp_enqueue_scripts', 'mygrocery_enqueue_styles');

add_action('after_setup_theme', 'mygrocery_register_menus');

add_action('wp_enqueue_scripts', 'mygrocery_enqueue_scripts');

add_action( 'woocommerce_before_main_content', function() {
    if ( is_product_category( 'recept' ) ) {
        echo '<div class="create-recipe-cta">
                <button class="createButton">
                    Do you want to create a recipe and get 5% off your next purchase? Click here!
                </button>
              </div>';
    }
}, 20 );


add_action( 'woocommerce_after_shop_loop_item', 'mygrocery_quantity_input_loop', 9 );

add_action( 'woocommerce_before_shop_loop_item', 'mygrocery_wrap_product_form_start', 5 );

add_action( 'woocommerce_after_shop_loop_item', 'mygrocery_wrap_product_form_end', 20 );

// FILTERS

add_filter( 'woocommerce_get_price_html', 'mygrocery_grouped_total_price', 10, 2 );

add_filter('woocommerce_grouped_product_list_before_quantity', 'add_thumbnail_to_grouped_products', 10, 1);

add_filter( 'woocommerce_related_products', function( $related_posts, $product_id, $args ) {

    $product = wc_get_product( $product_id );

    if ( $product && $product->is_type('grouped') ) {

        $products_in_category = get_posts( array(
            'post_type'      => 'product',
            'posts_per_page' => 4,
            'post__not_in'   => array( $product_id ),
            'tax_query'      => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'slug',
                    'terms'    => array( 'produkter' ),
                    'operator' => 'IN',
                ),
            ),
            'fields'         => 'ids',
        ) );

        return $products_in_category;
    }

    return $related_posts;

}, 10, 3 );