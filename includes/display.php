<?php

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
            $output .= '<p>Clasificación: ' . get_post_meta(get_the_ID(), '_cinema_clasificacion', true) . '</p>';
            $output .= '<p>Duración: ' . get_post_meta(get_the_ID(), '_cinema_duracion', true) . ' min</p>';
            $output .= '<p>Tráiler: <a href="' . esc_url(get_post_meta(get_the_ID(), '_cinema_trailer', true)) . '" target="_blank">Ver tráiler</a></p>';
            $output .= '<p><img src="' . esc_url(get_post_meta(get_the_ID(), '_cinema_poster', true)) . '" alt="Póster de ' . get_the_title() . '"></p>';
            $output .= '<div class="horarios">';
            $horarios = get_post_meta(get_the_ID(), '_cinema_horarios', true);
            if ($horarios) {
                foreach ($horarios as $horario) {
                    $output .= '<p>Hora: ' . esc_html($horario['hora']) . ', Formato: ' . esc_html($horario['formato']) . ', Doblaje: ' . esc_html($horario['doblaje']) . '</p>';
                }
            }
            $output .= '</div>';
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
