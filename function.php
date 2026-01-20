<?php
// Custom Post Type: Projekte
function create_projekte_cpt() {

    $labels = array(
        'name' => 'Projekte',
        'singular_name' => 'Projekt',
        'add_new' => 'Shto Projekt',
        'add_new_item' => 'Shto Projekt të Ri',
        'edit_item' => 'Ndrysho Projekt',
        'new_item' => 'Projekt i Ri',
        'view_item' => 'Shiko Projektin',
        'search_items' => 'Kërko Projekte',
        'not_found' => 'Nuk u gjet asnjë projekt'
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-portfolio',
        'supports' => array('title', 'editor', 'thumbnail'),
        'rewrite' => array('slug' => 'projekte'),
    );

    register_post_type('projekte', $args);
}

add_action('init', 'create_projekte_cpt');
