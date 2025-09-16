<?php
function mygrocery_enqueue_styles() {
    wp_enqueue_style('mygrocery-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'mygrocery_enqueue_styles');

function mygrocery_register_menus() {
    register_nav_menu('primary', __('Primary Menu', 'my-custom-theme'));
}
add_action('after_setup_theme', 'mygrocery_register_menus');


function mygrocery_enqueue_scripts() {
    wp_enqueue_script('mygrocery-menu', get_template_directory_uri() . '/assets/js/menu.js', array('jquery'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'mygrocery_enqueue_scripts');

add_filter('woocommerce_grouped_product_list_before_quantity', 'add_thumbnail_to_grouped_products', 10, 1);

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
