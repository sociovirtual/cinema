<?php
/*
Plugin Name: Cinema
Description: Plugin para gestionar una cartelera de cine.
Version: 1.0
Author: Tu Nombre
*/

// Asegurarse de que el archivo no se accede directamente
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Registrar el custom post type para las películas
function cinema_register_post_type() {
    $labels = array(
        'name'                  => _x('Películas', 'Post type general name', 'cinema'),
        'singular_name'         => _x('Película', 'Post type singular name', 'cinema'),
        'menu_name'             => _x('Cartelera de Cine', 'Admin Menu text', 'cinema'),
        'name_admin_bar'        => _x('Película', 'Add New on Toolbar', 'cinema'),
        'add_new'               => __('Añadir Nueva', 'cinema'),
        'add_new_item'          => __('Añadir Nueva Película', 'cinema'),
        'new_item'              => __('Nueva Película', 'cinema'),
        'edit_item'             => __('Editar Película', 'cinema'),
        'view_item'             => __('Ver Película', 'cinema'),
        'all_items'             => __('Todas las Películas', 'cinema'),
        'search_items'          => __('Buscar Películas', 'cinema'),
        'parent_item_colon'     => __('Películas Parentes', 'cinema'),
        'not_found'             => __('No se encontraron películas.', 'cinema'),
        'not_found_in_trash'    => __('No se encontraron películas en la papelera.', 'cinema'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'pelicula'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
    );

    register_post_type('pelicula', $args);
}
add_action('init', 'cinema_register_post_type');

// Añadir campos personalizados
function cinema_add_custom_metaboxes() {
    add_meta_box(
        'cinema_movie_details',
        'Detalles de la Película',
        'cinema_movie_details_callback',
        'pelicula',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'cinema_add_custom_metaboxes');

function cinema_movie_details_callback($post) {
    wp_nonce_field('cinema_save_movie_details', 'cinema_movie_details_nonce');

    $duracion = get_post_meta($post->ID, '_cinema_duracion', true);
    $director = get_post_meta($post->ID, '_cinema_director', true);

    echo '<label for="cinema_duracion">Duración (min):</label>';
    echo '<input type="number" id="cinema_duracion" name="cinema_duracion" value="' . esc_attr($duracion) . '" size="25" />';
    echo '<br/><br/>';
    echo '<label for="cinema_director">Director:</label>';
    echo '<input type="text" id="cinema_director" name="cinema_director" value="' . esc_attr($director) . '" size="25" />';
}

function cinema_save_movie_details($post_id) {
    if (!isset($_POST['cinema_movie_details_nonce']) || !wp_verify_nonce($_POST['cinema_movie_details_nonce'], 'cinema_save_movie_details')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (isset($_POST['cinema_duracion'])) {
        update_post_meta($post_id, '_cinema_duracion', sanitize_text_field($_POST['cinema_duracion']));
    }

    if (isset($_POST['cinema_director'])) {
        update_post_meta($post_id, '_cinema_director', sanitize_text_field($_POST['cinema_director']));
    }
}
add_action('save_post', 'cinema_save_movie_details');

// Shortcode para mostrar las películas
function cinema_display_movies($atts) {
    $atts = shortcode_atts(array(
        'posts_per_page' => -1,
    ), $atts, 'cinema_movies');

    $query = new WP_Query(array(
        'post_type' => 'pelicula',
        'posts_per_page' => $atts['posts_per_page'],
    ));

    if ($query->have_posts()) {
        $output = '<div class="cinema-movies">';
        while ($query->have_posts()) {
            $query->the_post();
            $output .= '<div class="movie">';
            $output .= '<h2>' . get_the_title() . '</h2>';
            $output .= '<div>' . get_the_content() . '</div>';
            $output .= '<p>Duración: ' . get_post_meta(get_the_ID(), '_cinema_duracion', true) . ' min</p>';
            $output .= '<p>Director: ' . get_post_meta(get_the_ID(), '_cinema_director', true) . '</p>';
            $output .= '</div>';
        }
        $output .= '</div>';
    } else {
        $output = '<p>No se encontraron películas.</p>';
    }
    wp_reset_postdata();
    return $output;
}
add_shortcode('cinema_movies', 'cinema_display_movies');

// Cargar estilos y scripts
function cinema_enqueue_scripts() {
    wp_enqueue_style('cinema-style', plugins_url('assets/css/style.css', __FILE__));
    wp_enqueue_script('cinema-script', plugins_url('assets/js/script.js', __FILE__), array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'cinema_enqueue_scripts');
