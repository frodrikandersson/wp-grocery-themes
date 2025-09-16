<?php
get_header();

if (have_posts()) : while (have_posts()) : the_post();

    $product_ids = get_post_meta(get_the_ID(), '_collection_product_ids', true);
    if (!is_array($product_ids)) {
        $product_ids = [];
    }
    ?>

    <div class="single-collection">
        <h1><?php the_title(); ?></h1>

        <div class="collection-content">
            <?php the_content(); ?>
        </div>

        <?php if (!empty($product_ids)): ?>
            <h2>Products in this collection</h2>
            <ul class="collection-products">
                <?php foreach ($product_ids as $pid):
                    $product = wc_get_product($pid);
                    if (!$product) continue; ?>
                    <li <?php wc_product_class('', $product); ?>>
                        <a href="<?php echo get_permalink($pid); ?>">
                            <?php echo $product->get_image('thumbnail'); ?>
                            <span class="product-title"><?php echo esc_html($product->get_name()); ?></span>
                        </a>
                        <span class="price"><?php echo $product->get_price_html(); ?></span>
                        <form action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
                            <input type="hidden" name="add-to-cart" value="<?php echo esc_attr($pid); ?>">
                            <button type="submit" class="button">Add to cart</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>

            <form class="purchase-all" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                <?php wp_nonce_field('wc_collections_purchase'); ?>
                <input type="hidden" name="action" value="wc_collections_purchase_all">
                <input type="hidden" name="collection_id" value="<?php echo esc_attr(get_the_ID()); ?>">
                <button type="submit" class="button alt">Purchase All</button>
            </form>

        <?php else: ?>
            <p>No products attached to this collection yet.</p>
        <?php endif; ?>
    </div>

<?php
endwhile; endif;

get_footer();
