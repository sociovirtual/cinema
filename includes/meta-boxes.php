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

  // Obtener valores actuales si ya están guardados
    $clasificacion = get_post_meta($post->ID, '_cinema_clasificacion', true);
    $duracion = get_post_meta($post->ID, '_cinema_duracion', true);
    $trailer = get_post_meta($post->ID, '_cinema_trailer', true);
    $formato = get_post_meta($post->ID, '_cinema_formato', true);
    $horarios = get_post_meta($post->ID, '_cinema_horarios', true);
    $doblar = get_post_meta($post->ID, '_cinema_doblaje', true);
    $poster = get_post_meta($post->ID, '_cinema_poster', true);
    $cartelera = get_post_meta($post->ID, '_cinema_cartelera', true);
    $proximo_estreno = get_post_meta($post->ID, '_cinema_proximo_estreno', true);
    $pre_venta = get_post_meta($post->ID, '_cinema_pre_venta', true);



    echo '<label for="cinema_clasificacion">Clasificación:</label>';
    echo '<input type="text" id="cinema_clasificacion" name="cinema_clasificacion" value="' . esc_attr($clasificacion) . '" size="25" />';
    echo '<br/><br/>';

    echo '<label for="cinema_duracion">Duración (min):</label>';
    echo '<input type="number" id="cinema_duracion" name="cinema_duracion" value="' . esc_attr($duracion) . '" size="25" />';
    echo '<br/><br/>';

    echo '<label for="cinema_trailer">URL del Tráiler:</label>';
    echo '<input type="text" id="cinema_trailer" name="cinema_trailer" value="' . esc_attr($trailer) . '" size="25" />';
    echo '<br/><br/>';

    echo '<label for="cinema_poster">Póster:</label>';
    if ($poster) {
        echo '<div><img src="' . esc_url(wp_get_attachment_image_url($poster, 'thumbnail')) . '" style="max-width: 200px; height: auto;" /><br/><a href="#" id="remove_cinema_poster">Eliminar</a></div>';
    }
    echo '<input type="hidden" id="cinema_poster" name="cinema_poster" value="' . esc_attr($poster) . '" />';
    echo '<button class="button" id="upload_cinema_poster">Subir/Seleccionar Póster</button>';
    echo '<br/><br/>';

    echo '<label for="cinema_cartelera">¿En Cartelera?</label>';
    echo '<input type="checkbox" id="cinema_cartelera" name="cinema_cartelera" value="1" ' . checked($cartelera, '1', false) . ' />';
    echo '<br/><br/>';

    echo '<label for="cinema_proximo_estreno">¿Próximo Estreno?</label>';
    echo '<input type="checkbox" id="cinema_proximo_estreno" name="cinema_proximo_estreno" value="1" ' . checked($proximo_estreno, '1', false) . ' />';
    echo '<br/><br/>';

    echo '<label for="cinema_pre_venta">¿Pre-Venta?</label>';
    echo '<input type="checkbox" id="cinema_pre_venta" name="cinema_pre_venta" value="1" ' . checked($pre_venta, '1', false) . ' />';
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
    echo '<br/><br/>';

    echo '<button class="button add-horario">Añadir Horario</button>';

    ?>
    <script>
    jQuery(document).ready(function($) {
        // Subir/Seleccionar Póster
        $('#upload_cinema_poster').click(function(e) {
            e.preventDefault();
            var custom_uploader = wp.media({
                title: 'Seleccionar Póster',
                button: {
                    text: 'Seleccionar'
                },
                multiple: false
            });
            custom_uploader.on('select', function() {
                var attachment = custom_uploader.state().get('selection').first().toJSON();
                $('#cinema_poster').val(attachment.id);
                $('#cinema_poster').html('<img src="' + attachment.url + '" style="max-width:200px;height:auto;"/><br/><a href="#" id="remove_cinema_poster">Eliminar</a>');
            });
            custom_uploader.open();
        });

        // Eliminar Póster
        $(document).on('click', '#remove_cinema_poster', function(e) {
            e.preventDefault();
            $('#cinema_poster').val('');
            $('#cinema_poster').html('<button class="button" id="upload_cinema_poster">Subir/Seleccionar Póster</button>');
        });

        // Añadir/Quitar Horario
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
        update_post_meta($post_id, '_cinema_poster', sanitize_text_field($_POST['cinema_poster']));
    }

    // Guardar opciones "sí o no"
    update_post_meta($post_id, '_cinema_cartelera', isset($_POST['cinema_cartelera']) ? '1' : '0');
    update_post_meta($post_id, '_cinema_proximo_estreno', isset($_POST['cinema_proximo_estreno']) ? '1' : '0');
    update_post_meta($post_id, '_cinema_pre_venta', isset($_POST['cinema_pre_venta']) ? '1' : '0');

    // Guardar horarios
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
