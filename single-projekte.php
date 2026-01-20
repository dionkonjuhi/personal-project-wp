<?php get_header(); ?>

<div class="project-single">
    <h1><?php the_title(); ?></h1>

    <?php if (get_field('klienti')->ID === get_current_user_id() || current_user_can('administrator')) : ?>

        <p><strong>Statusi:</strong> <?php the_field('statusi'); ?></p>
        <p><strong>Buxheti:</strong> €<?php the_field('buxheti'); ?></p>
        <p><strong>Data Fillimit:</strong> <?php the_field('data_fillimit'); ?></p>

        <div><?php the_content(); ?></div>

    <?php else : ?>
        <p>🚫 Nuk keni akses në këtë projekt.</p>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
