<?php
/*
Plugin Name: Project Manager Advanced
Description: Sistem menaxhimi projektesh për WordPress
Version: 1.0
Author: Student
*/

// Security
if (!defined('ABSPATH')) exit;

// Custom Post Type: Projekte
function pm_create_projekte_cpt() {

    register_post_type('projekte', array(
        'labels' => array(
            'name' => 'Projekte',
            'singular_name' => 'Projekt'
        ),
        'public' => true,
        'menu_icon' => 'dashicons-portfolio',
        'supports' => array('title', 'editor'),
        'has_archive' => true
    ));
}
add_action('init', 'pm_create_projekte_cpt');
