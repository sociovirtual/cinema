<?php
/**
 * Plugin Name: Cinema
 * Plugin URI: https://sociovirtual.com/plugins/cinema
 * Description: Gestiona y muestra información detallada sobre películas en tu sitio web.
 * Version: 1.0.4
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


// Registrar un endpoint de API REST para películas
function cinema_register_rest_endpoint() {
    register_rest_route('cinema/v1', '/movies/', array(
        'methods' => 'GET',
        'callback' => 'cinema_get_movies',
    ));
}
add_action('rest_api_init', 'cinema_register_rest_endpoint');

// Callback para obtener películas
function cinema_get_movies($data) {
    $args = array(
        'post_type' => 'peliculas', // Nombre del tipo de post
        'posts_per_page' => -1,
    );

    $query = new WP_Query($args);
    $movies = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            // Obtener metadatos de la película
            $movie_data = array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'clasificacion' => get_post_meta(get_the_ID(), '_cinema_clasificacion', true),
                'duracion' => get_post_meta(get_the_ID(), '_cinema_duracion', true),
                'url_trailer' => get_post_meta(get_the_ID(), '_cinema_url_trailer', true),
                'formato' => get_post_meta(get_the_ID(), '_cinema_formato', true),
                'horarios' => get_post_meta(get_the_ID(), '_cinema_horarios', true),
                'doblaje' => get_post_meta(get_the_ID(), '_cinema_doblaje', true),
                'poster' => get_post_meta(get_the_ID(), '_cinema_poster', true),
                'preventa' => get_post_meta(get_the_ID(), '_cinema_preventa', true),
            );

            $movies[] = $movie_data;
        }
    }

    wp_reset_postdata();

    return rest_ensure_response($movies);
}



// Registrar campos personalizados en WPGraphQL
function cinema_register_graphql_fields() {
    if (function_exists('register_graphql_field')) {
        register_graphql_field('Pelicula', 'clasificacion', array(
            'type' => 'String',
            'description' => 'Clasificación de la película.',
            'resolve' => function ($post) {
                return get_post_meta($post->ID, '_cinema_clasificacion', true);
            },
        ));

        // Registrar más campos según sea necesario
    }
}
add_action('graphql_register_types', 'cinema_register_graphql_fields');
