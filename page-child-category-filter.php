<?php
/* Template Name: Child Category Filter Page */

get_header(); 

// Get GET filters
$recept_child    = isset($_GET['recept_child']) ? sanitize_text_field($_GET['recept_child']) : '';
$produkter_child = isset($_GET['produkter_child']) ? sanitize_text_field($_GET['produkter_child']) : '';

// Fetch child categories
$recept_children = get_terms([
    'taxonomy' => 'product_cat',
    'hide_empty' => true,
    'parent' => get_term_by('slug', 'recept', 'product_cat')->term_id,
]);

$produkter_children = get_terms([
    'taxonomy' => 'product_cat',
    'hide_empty' => true,
    'parent' => get_term_by('slug', 'produkter', 'product_cat')->term_id,
]);
?>

<div class="child-category-page">

    <!-- ================= Products Grid ================= -->
    <section class="child-category-section products-section">
        <h2>Products</h2>

        <?php if (!empty($produkter_children)) : ?>
        <form method="get" class="child-category-filter">
            <!-- Preserve recipe filter value -->
            <input type="hidden" name="recept_child" value="<?= esc_attr($recept_child); ?>">
            
            <select name="produkter_child" onchange="this.form.submit()">
                <option value="">Filter Products by Type</option>
                <?php foreach ($produkter_children as $cat) : ?>
                    <option value="<?= esc_attr($cat->slug) ?>" <?= selected($produkter_child, $cat->slug, false) ?>>
                        <?= esc_html($cat->name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
        <?php endif; ?>

        <?php
        // Products Query (only parent category "produkter")
        $products_tax_query = [
            [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => ['produkter'],
                'include_children' => true
            ]
        ];

        if ($produkter_child) {
            $products_tax_query[] = [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => [$produkter_child],
            ];
        }

        $products = new WP_Query([
            'post_type' => 'product',
            'posts_per_page' => 12,
            'tax_query' => $products_tax_query,
            'paged' => get_query_var('paged') ?: 1,
        ]);
        ?>

        <?php if ($products->have_posts()) : ?>
            <ul class="products-grid">
                <?php while ($products->have_posts()) : $products->the_post(); ?>
                    <?php wc_get_template_part('content', 'product'); ?>
                <?php endwhile; ?>
            </ul>

            <div class="pagination">
                <?= paginate_links(['total' => $products->max_num_pages]); ?>
            </div>
        <?php else: ?>
            <p>No products found.</p>
        <?php endif; wp_reset_postdata(); ?>
    </section>

    <!-- ================= Recipes Grid ================= -->
    <section class="child-category-section recipes-section">
        <h2>Recipes</h2>

        <?php if (!empty($recept_children)) : ?>
        <form method="get" class="child-category-filter">
            <!-- Preserve product filter value -->
            <input type="hidden" name="produkter_child" value="<?= esc_attr($produkter_child); ?>">
            
            <select name="recept_child" onchange="this.form.submit()">
                <option value="">Filter Recipes by Type</option>
                <?php foreach ($recept_children as $cat) : ?>
                    <option value="<?= esc_attr($cat->slug) ?>" <?= selected($recept_child, $cat->slug, false) ?>>
                        <?= esc_html($cat->name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
        <?php endif; ?>

        <?php
        // Recipes Query (only parent category "recept")
        $recipes_tax_query = [
            [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => ['recept'],
                'include_children' => true
            ]
        ];

        if ($recept_child) {
            $recipes_tax_query[] = [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => [$recept_child],
            ];
        }

        $recipes = new WP_Query([
            'post_type' => 'product',
            'posts_per_page' => 12,
            'tax_query' => $recipes_tax_query,
            'paged' => get_query_var('paged') ?: 1,
        ]);
        ?>

        <?php if ($recipes->have_posts()) : ?>
            <ul class="products-grid">
                <?php while ($recipes->have_posts()) : $recipes->the_post(); ?>
                    <?php wc_get_template_part('content', 'product'); ?>
                <?php endwhile; ?>
            </ul>

            <div class="pagination">
                <?= paginate_links(['total' => $recipes->max_num_pages]); ?>
            </div>

            <div class="create-recipe-cta">
                <button class="createButton">
                    Do you want to create a recipe and get 5% off your next purchase? Click here!
                </button>
            </div>

        <?php else: ?>
            <p>No recipes found.</p>
        <?php endif; wp_reset_postdata(); ?>
    </section>

</div>

<?php get_footer(); ?>
