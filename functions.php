<?php
// Enqueue main stylesheet
function mygrocery_enqueue_styles() {
    wp_enqueue_style('mygrocery-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'mygrocery_enqueue_styles');

// Register a menu
function mygrocery_register_menus() {
    register_nav_menu('primary', __('Primary Menu', 'my-custom-theme'));
}
add_action('after_setup_theme', 'mygrocery_register_menus');
