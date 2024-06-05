<?php
/*
Plugin Name: Cinema
Description: Plugin para gestionar una cartelera de cine.
Version: 1.0.2
Author: Sociovirtual.com
*/


// Incluir archivos necesarios
require_once plugin_dir_path(__FILE__) . 'includes/meta-boxes.php';
require_once plugin_dir_path(__FILE__) . 'includes/display.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin.php';

// Registrar Custom Post Type
function cinema_register_post_type() {
    $labels = array(
        'name' => 'Películas',
        'singular_name' => 'Película',
        'menu_name' => 'Cartelera de Cine',
        'name_admin_bar' => 'Película',
        'add_new' => 'Añadir Nueva',
        'add_new_item' => 'Añadir Nueva Película',
        'new_item' => 'Nueva Película',
        'edit_item' => 'Editar Película',
        'view_item' => 'Ver Película',
        'all_items' => 'Todas las Películas',
        'search_items' => 'Buscar Películas',
        'not_found' => 'No se encontraron películas',
        'not_found_in_trash' => 'No se encontraron películas en la papelera'
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'show_in_rest' => true,
    );

    register_post_type('pelicula', $args);
}
add_action('init', 'cinema_register_post_type');
