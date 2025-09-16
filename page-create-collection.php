<?php
/**
 * Template Name: Create Collection
 *
 * This page template outputs the front-end form for users to create a collection (recipe).
 */

get_header();
?>

<div class="create-collection-page">
    <h1>Create a New Collection</h1>

    <p>Fill out the form below to create your recipe/collection. You can select existing products, add a description, and optionally request a coupon code.</p>

    <?php
    echo do_shortcode('[collection_create_form]');
    ?>
</div>

<?php get_footer(); ?>
