<?php get_header(); ?>

<section class="hero">
    <div class="hero-inner">
        <h1>Snap Basket</h1>
        <p>Get your groceries in a flash through easy to access recipes made by other users!</p>
        <p>Make your own recipes and get a coupon for 5% off!</p>
        <a href="<?php echo site_url('/product-category/recept/'); ?>" class="btn">Browse Recipes</a>
    </div>
</section>

<section class="products content-section">
    <h2>Featured Products</h2>
    <div class="grid">
        <?php
        echo do_shortcode('[products limit="4" columns="4" category="produkter"]');
        ?>
    </div>
    <div style="text-align:center; margin-top:20px;">
        <a href="<?php echo site_url('/product-category/produkter/'); ?>" class="btn">View All Products</a>
    </div>
</section>

<section class="collections content-section">
    <h2>Featured Recipes</h2>
    <div class="grid">
        <?php
        echo do_shortcode('[products limit="3" columns="3" category="recept"]');
        ?>
    </div>
    <div style="text-align:center; margin-top:20px;">
        <a href="<?php echo site_url('/product-category/recept/'); ?>" class="btn">View All Recipes</a>
    </div>
</section>

<?php get_footer(); ?>
