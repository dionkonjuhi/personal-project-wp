<?php
/* Template Name: Dashboard */
get_header();

$current_user = wp_get_current_user();

$query = new WP_Query(array(
    'post_type' => 'projekte',
    'meta_query' => array(
        array(
            'key' => 'klienti',
            'value' => $current_user->ID,
            'compare' => '='
        )
    )
));
?>

<h2>Mirësevini, <?php echo $current_user->display_name; ?></h2>

<h3>Projektet e Mia</h3>

<ul>
<?php while ($query->have_posts()) : $query->the_post(); ?>
    <li>
        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        - <?php the_field('statusi'); ?>
    </li>
<?php endwhile; ?>
</ul>

<?php wp_reset_postdata(); get_footer(); ?>
