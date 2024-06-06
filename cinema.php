<?php
/**
 * Plugin Name: Cinema
 * Plugin URI: https://sociovirtual.com/plugins/cinema
 * Description: Gestiona y muestra información detallada sobre películas en tu sitio web.
 * Version: 1.0.8
 * Author: Jose Vargas Molina
 * Author URI: https://sociovirtual.com
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: cinema
 * Domain Path: /languages
 */

// Exit si se accede directamente.
if (!defined('ABSPATH')) {
    exit;
}

// Incluir archivos necesarios
require_once plugin_dir_path(__FILE__) . 'includes/meta-boxes.php';
require_once plugin_dir_path(__FILE__) . 'includes/display.php';
require_once plugin_dir_path(__FILE__) . 'includes/wpgraphql.php';

// Registrar Custom Post Type
function cinema_register_post_type() {
    $labels = array(
        'name' => __('Películas', 'cinema'),
        'singular_name' => __('Película', 'cinema'),
        'menu_name' => __('Cartelera de Cine', 'cinema'),
        'name_admin_bar' => __('Película', 'cinema'),
        'add_new' => __('Añadir Nueva', 'cinema'),
        'add_new_item' => __('Añadir Nueva Película', 'cinema'),
        'new_item' => __('Nueva Película', 'cinema'),
        'edit_item' => __('Editar Película', 'cinema'),
        'view_item' => __('Ver Película', 'cinema'),
        'all_items' => __('Todas las Películas', 'cinema'),
        'search_items' => __('Buscar Películas', 'cinema'),
        'not_found' => __('No se encontraron películas', 'cinema'),
        'not_found_in_trash' => __('No se encontraron películas en la papelera', 'cinema')
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'show_in_rest' => true,
        'show_in_graphql' => true, // Asegurarse de agregar esta línea
        'graphql_single_name' => 'Pelicula', // Nombre singular para GraphQL
        'graphql_plural_name' => 'Peliculas' // Nombre plural para GraphQL
    );

    register_post_type('pelicula', $args);
}
add_action('init', 'cinema_register_post_type');

// Cargar scripts y estilos
function cinema_enqueue_scripts() {
    wp_enqueue_style('cinema-styles', plugin_dir_url(__FILE__) . 'assets/css/styles.css');
    wp_enqueue_script('cinema-scripts', plugin_dir_url(__FILE__) . 'assets/js/scripts.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'cinema_enqueue_scripts');

function cinema_enqueue_admin_scripts() {
    wp_enqueue_style('cinema-admin-styles', plugin_dir_url(__FILE__) . 'assets/css/admin-style.css');
    wp_enqueue_script('cinema-admin-scripts', plugin_dir_url(__FILE__) . 'assets/js/admin-script.js', array('jquery'), null, true);
}
add_action('admin_enqueue_scripts', 'cinema_enqueue_admin_scripts');
