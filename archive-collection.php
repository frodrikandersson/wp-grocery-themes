<?php
get_header();

$paged   = get_query_var('paged') ? get_query_var('paged') : 1;
$tax     = 'collection_category';
$cat     = get_query_var('collection_category') ?? ($_GET['cat'] ?? '');
$search  = sanitize_text_field($_GET['s'] ?? '');
$orderby = sanitize_text_field($_GET['orderby'] ?? 'date'); // 'date' or 'title'

$args = [
    'post_type'      => 'collection',
    'posts_per_page' => 10,
    'paged'          => $paged,
];

// Search
if (!empty($search)) {
    $args['s'] = $search;
}

// Category filter
if (!empty($cat)) {
    $args['tax_query'] = [
        [
            'taxonomy' => $tax,
            'field'    => 'term_id',
            'terms'    => intval($cat),
        ]
    ];
}

// Sorting
if ($orderby === 'title') {
    $args['orderby'] = 'title';
    $args['order']   = 'ASC';
} else {
    $args['orderby'] = 'date';
    $args['order']   = 'DESC';
}

$query = new WP_Query($args);
?>

<div class="collections-archive">
    <h1>Collections</h1>

    <form id="collections-filter" method="get">
        <label for="search">Search</label>
        <input id="search" type="text" name="s" value="<?php echo esc_attr($search); ?>" placeholder="Search collections...">

        <?php $terms = get_terms(['taxonomy' => 'collection_category', 'hide_empty' => false]); ?>
        <label for="category">Category</label>
        <select id="category" name="cat">
            <option value="">All categories</option>
            <?php foreach ($terms as $t): ?>
                <option value="<?php echo esc_attr($t->term_id); ?>" <?php selected($cat, $t->term_id); ?>>
                    <?php echo esc_html($t->name); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="orderby">Sort by</label>
        <select id="orderby" name="orderby">
            <option value="date" <?php selected($orderby, 'date'); ?>>Newest</option>
            <option value="title" <?php selected($orderby, 'title'); ?>>Alphabetical</option>
        </select>

        <button type="submit">Filter</button>
    </form>

    <?php if ($query->have_posts()): ?>
        <?php while ($query->have_posts()): $query->the_post(); ?>
            <article <?php post_class(); ?>>
                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <div><?php the_excerpt(); ?></div>
            </article>
        <?php endwhile; ?>

        <?php
        echo paginate_links([
            'total'   => $query->max_num_pages,
            'current' => $paged,
            'add_args'=> [
                's'       => $search,
                'cat'     => $cat,
                'orderby' => $orderby,
            ],
        ]);
        ?>
    <?php else: ?>
        <p>No collections found. Try adjusting your search or filters.</p>
    <?php endif; ?>

    <?php wp_reset_postdata(); ?>
</div>

<?php get_footer(); ?>
