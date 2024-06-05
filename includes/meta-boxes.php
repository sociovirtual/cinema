<?php

// Agregar Meta Box
function cinema_add_meta_box() {
    add_meta_box(
        'cinema_movie_details',
        'Detalles de la Película',
        'cinema_movie_details_callback',
        'pelicula',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'cinema_add_meta_box');

// Mostrar Meta Box
function cinema_movie_details_callback($post) {
    wp_nonce_field('cinema_save_movie_details', 'cinema_movie_details_nonce');

    $duracion = get_post_meta($post->ID, '_cinema_duracion', true);
    $clasificacion = get_post_meta($post->ID, '_cinema_clasificacion', true);
    $trailer = get_post_meta($post->ID, '_cinema_trailer', true);
    $horarios = get_post_meta($post->ID, '_cinema_horarios', true);
    $poster = get_post_meta($post->ID, '_cinema_poster', true);

    echo '<label for="cinema_clasificacion">Clasificación:</label>';
    echo '<input type="text" id="cinema_clasificacion" name="cinema_clasificacion" value="' . esc_attr($clasificacion) . '" size="25" />';
    echo '<br/><br/>';

    echo '<label for="cinema_duracion">Duración (min):</label>';
    echo '<input type="number" id="cinema_duracion" name="cinema_duracion" value="' . esc_attr($duracion) . '" size="25" />';
    echo '<br/><br/>';

    echo '<label for="cinema_trailer">URL del Tráiler:</label>';
    echo '<input type="text" id="cinema_trailer" name="cinema_trailer" value="' . esc_attr($trailer) . '" size="25" />';
    echo '<br/><br/>';

    echo '<label for="cinema_poster">URL del Póster:</label>';
    echo '<input type="text" id="cinema_poster" name="cinema_poster" value="' . esc_attr($poster) . '" size="25" />';
    echo '<br/><br/>';

    echo '<label>Horarios, Formato y Doblaje:</label>';
    echo '<div id="cinema-horarios-wrapper">';
    if ($horarios) {
        foreach ($horarios as $index => $horario) {
            echo '<div class="cinema-horario-group">';
            echo '<input type="text" name="cinema_horarios[' . $index . '][hora]" value="' . esc_attr($horario['hora']) . '" placeholder="Hora" />';
            echo '<input type="text" name="cinema_horarios[' . $index . '][formato]" value="' . esc_attr($horario['formato']) . '" placeholder="Formato" />';
            echo '<input type="text" name="cinema_horarios[' . $index . '][doblaje]" value="' . esc_attr($horario['doblaje']) . '" placeholder="Doblaje" />';
            echo '<button class="button remove-horario">Eliminar</button>';
            echo '</div>';
        }
    }
    echo '</div>';
    echo '<button class="button add-horario">Añadir Horario</button>';

    ?>
    <script>
    jQuery(document).ready(function($) {
        $('.add-horario').on('click', function(e) {
            e.preventDefault();
            var index = $('#cinema-horarios-wrapper .cinema-horario-group').length;
            $('#cinema-horarios-wrapper').append(
                '<div class="cinema-horario-group">' +
                '<input type="text" name="cinema_horarios[' + index + '][hora]" placeholder="Hora" />' +
                '<input type="text" name="cinema_horarios[' + index + '][formato]" placeholder="Formato" />' +
                '<input type="text" name="cinema_horarios[' + index + '][doblaje]" placeholder="Doblaje" />' +
                '<button class="button remove-horario">Eliminar</button>' +
                '</div>'
            );
        });
        $(document).on('click', '.remove-horario', function(e) {
            e.preventDefault();
            $(this).parent('.cinema-horario-group').remove();
        });
    });
    </script>
    <?php
}

// Guardar Meta Box
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

    if (isset($_POST['cinema_clasificacion'])) {
        update_post_meta($post_id, '_cinema_clasificacion', sanitize_text_field($_POST['cinema_clasificacion']));
    }

    if (isset($_POST['cinema_trailer'])) {
        update_post_meta($post_id, '_cinema_trailer', sanitize_text_field($_POST['cinema_trailer']));
    }

    if (isset($_POST['cinema_poster'])) {
        update_post_meta($post_id, '_cinema_poster', esc_url_raw($_POST['cinema_poster']));
    }

    if (isset($_POST['cinema_horarios'])) {
        $horarios = array();
        foreach ($_POST['cinema_horarios'] as $horario) {
            $horarios[] = array(
                'hora' => sanitize_text_field($horario['hora']),
                'formato' => sanitize_text_field($horario['formato']),
                'doblaje' => sanitize_text_field($horario['doblaje']),
            );
        }
        update_post_meta($post_id, '_cinema_horarios', $horarios);
    }
}
add_action('save_post', 'cinema_save_movie_details');
