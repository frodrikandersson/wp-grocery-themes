<?php
get_header();

$paged = get_query_var('paged') ? get_query_var('paged') : 1;
$tax = 'collection_category';
$cat = get_query_var('collection_category') ?? ( $_GET['cat'] ?? 0 );
$search = sanitize_text_field( $_GET['s'] ?? '' );
$orderby = $_GET['orderby'] ?? 'date'; // 'date' or 'title'

$args = [
    'post_type' => 'collection',
    'posts_per_page' => 10,
    'paged' => $paged,
];

if ( ! empty( $search ) ) {
    $args['s'] = $search;
}

if ( ! empty( $cat ) ) {
    $args['tax_query'] = [
        [
            'taxonomy' => $tax,
            'field' => 'term_id',
            'terms' => intval($cat),
        ]
    ];
}

if ( $orderby === 'title' ) {
    $args['orderby'] = 'title';
    $args['order'] = 'ASC';
} else {
    $args['orderby'] = 'date';
    $args['order'] = 'DESC';
}

$query = new WP_Query( $args );
?>

<div class="collections-archive">
    <h1>Collections</h1>

    <form id="collections-filter" method="get">
        <input type="text" name="s" value="<?php echo esc_attr($search); ?>" placeholder="Search collections...">
        <?php
        $terms = get_terms(['taxonomy'=>'collection_category','hide_empty'=>false]);
        ?>
        <select name="cat">
            <option value="">All categories</option>
            <?php foreach ( $terms as $t ) printf('<option value="%d" %s>%s</option>', $t->term_id, selected($cat,$t->term_id,false), esc_html($t->name)); ?>
        </select>
        <select name="orderby">
            <option value="date" <?php selected($orderby,'date'); ?>>Newest</option>
            <option value="title" <?php selected($orderby,'title'); ?>>Alphabetical</option>
        </select>
        <button type="submit">Filter</button>
    </form>

    <?php if ( $query->have_posts() ): while ( $query->have_posts() ): $query->the_post(); ?>
        <article>
            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <div><?php the_excerpt(); ?></div>
        </article>
    <?php endwhile; endif; wp_reset_postdata(); ?>

    <?php
    // pagination
    echo paginate_links( [
        'total' => $query->max_num_pages
    ] );
    ?>
</div>

<?php get_footer(); ?>
