<?php
// Salir si se accede directamente.
if (!defined('ABSPATH')) {
    exit;
}

add_action('graphql_register_types', function () {
    register_graphql_field('Pelicula', 'clasificacionPublico', [
        'type' => 'String',
        'description' => __('Clasificación de Público', 'cinema'),
        'resolve' => function($post) {
            $clasificacion_publico = get_post_meta($post->ID, '_cinema_clasificacion', true);
            return !empty($clasificacion_publico) ? sanitize_text_field($clasificacion_publico) : null;
        }
    ]);

    register_graphql_field('Pelicula', 'duracion', [
        'type' => 'Int',
        'description' => __('Duración en Minutos', 'cinema'),
        'resolve' => function($post) {
            $duracion = get_post_meta($post->ID, '_cinema_duracion', true);
            return !empty($duracion) ? intval($duracion) : null;
        }
    ]);

    register_graphql_field('Pelicula', 'urlTrailer', [
        'type' => 'String',
        'description' => __('URL del Tráiler', 'cinema'),
        'resolve' => function($post) {
            $url_trailer = get_post_meta($post->ID, '_cinema_trailer', true);
            return !empty($url_trailer) ? esc_url($url_trailer) : null;
        }
    ]);

   register_graphql_field('Pelicula', 'cinemaHorarios', [
        'type' => [
            'list_of' => 'CinemaHorario',
        ],
        'resolve' => function ($post) {
            return get_post_meta($post->ID, '_cinema_horarios', true);
        },
    ]);

    register_graphql_object_type('CinemaHorario', [
        'description' => 'Horarios de proyección para Cinema plugin',
        'fields' => [
            'hora' => [
                'type' => 'String',
                'description' => 'Hora de la proyección',
                'resolve' => function ($horario) {
                    return $horario['hora'];
                },
            ],
            'formato' => [
                'type' => 'String',
                'description' => 'Formato de la proyección (3D, 2D, etc.)',
                'resolve' => function ($horario) {
                    return $horario['formato'];
                },
            ],
            'doblaje' => [
                'type' => 'String',
                'description' => 'Tipo de doblaje (si aplica)',
                'resolve' => function ($horario) {
                    return $horario['doblaje'];
                },
            ],
        ],
    ]);


 register_graphql_field('Pelicula', 'cartelera', [
        'type' => 'Boolean',
        'description' => '¿Está en cartelera?',
        'resolve' => function ($post) {
            $cartelera = get_post_meta($post->ID, '_cinema_cartelera', true);
            return !empty($cartelera) ? true : false;
        },
    ]);

    register_graphql_field('Pelicula', 'proximoEstreno', [
        'type' => 'Boolean',
        'description' => '¿Es próximo estreno?',
        'resolve' => function ($post) {
            $proximo_estreno = get_post_meta($post->ID, '_cinema_proximo_estreno', true);
            return !empty($proximo_estreno) ? true : false;
        },
    ]);

    register_graphql_field('Pelicula', 'preVenta', [
        'type' => 'Boolean',
        'description' => '¿Está en pre-venta?',
        'resolve' => function ($post) {
            $pre_venta = get_post_meta($post->ID, '_cinema_pre_venta', true);
            return !empty($pre_venta) ? true : false;
        },
    ]);


register_graphql_field('Pelicula', 'imagenPoster', [
    'type' => 'String',
    'description' => __('Imagen del Póster', 'cinema'),
    'args' => [
        'size' => [
            'type' => 'String',
            'description' => 'Tamaño de la imagen (por ejemplo, "thumbnail", "medium", "large", "full").',
            'default' => 'medium', // Tamaño por defecto si no se especifica
        ],
    ],
    'resolve' => function($post, $args) {
        $imagen_poster_id = get_post_meta($post->ID, '_cinema_poster', true);

        if (!empty($imagen_poster_id)) {
            $imagen_poster_url = wp_get_attachment_image_url($imagen_poster_id, $args['size']);
            return esc_url($imagen_poster_url);
        }

        return null;
    },
]);

    register_graphql_field('Pelicula', 'imagenFondo', [
        'type' => 'String',
        'description' => 'URL de la fuente de la imagen destacada',
        'args' => [
            'size' => [
                'type' => 'String',
                'description' => 'Tamaño de la imagen (ej. thumbnail, medium, full)',
                'default' => 'full',
            ],
        ],
        'resolve' => function ($post, $args) {
            $featured_image_id = get_post_thumbnail_id($post->ID);
            if ($featured_image_id) {
                $image_url = wp_get_attachment_image_src($featured_image_id, $args['size']);
                if ($image_url) {
                    return $image_url[0];
                }
            }
            return null;
        },
    ]);


});
