<?php
// Theme setup
function ppwp_setup(){
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', array('search-form','comment-form','comment-list','gallery','caption'));
    add_theme_support('post-formats', array('aside','gallery','link','image','quote','status','video','audio'));

    register_nav_menus(array(
        'primary' => __('Primary Menu', 'personal-project-wp'),
    ));
}
add_action('after_setup_theme','ppwp_setup');

// Enqueue styles and scripts
function ppwp_enqueue(){
    wp_enqueue_style('ppwp-main', get_stylesheet_directory_uri() . '/assets/css/main.css', array(), '1.0');
    wp_enqueue_script('ppwp-main', get_stylesheet_directory_uri() . '/assets/js/main.js', array('jquery'), '1.0', true);
}
add_action('wp_enqueue_scripts','ppwp_enqueue');

// Register sidebar/widget area
function ppwp_widgets(){
    register_sidebar(array(
        'name' => __('Main Sidebar','personal-project-wp'),
        'id' => 'sidebar-1',
        'description' => 'Widgets in this area will appear in the sidebar.',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}
add_action('widgets_init','ppwp_widgets');

// Register a Custom Post Type: Project
function ppwp_register_cpt(){
    $labels = array('name'=>'Projects','singular_name'=>'Project');
    $args = array(
        'labels'=>$labels,
        'public'=>true,
        'has_archive'=>true,
        'supports'=>array('title','editor','thumbnail','excerpt','custom-fields','comments','author','revisions','page-attributes'),
        'show_in_rest'=>true,
    );
    register_post_type('project',$args);
}
add_action('init','ppwp_register_cpt');

// Simple helper: custom WP_Query example used in a template
function ppwp_sample_query(){
    $args = array('post_type'=>'project','posts_per_page'=>3);
    $q = new WP_Query($args);
    if($q->have_posts()){
        echo '<div class="project-list">';
        while($q->have_posts()) { $q->the_post();
            echo '<article class="project">';
            the_title('<h2 class="entry-title">','</h2>');
            the_excerpt();
            echo '</article>';
        }
        echo '</div>';
        wp_reset_postdata();
    }
}

?>