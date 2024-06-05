<?php
// Si no se llama directamente al archivo de WordPress, salir.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Eliminar opciones de la base de datos al desinstalar el plugin
global $wpdb;

// Obtener IDs de todas las películas para eliminar metadatos asociados
$movie_ids = get_posts(array(
    'post_type' => 'peliculas',
    'numberposts' => -1,
    'fields' => 'ids',
));

// Eliminar metadatos de cada película
foreach ($movie_ids as $movie_id) {
    delete_post_meta($movie_id, '_cinema_clasificacion');
    delete_post_meta($movie_id, '_cinema_duracion');
    delete_post_meta($movie_id, '_cinema_trailer');
    delete_post_meta($movie_id, '_cinema_poster_id');
    delete_post_meta($movie_id, '_cinema_cartelera');
    delete_post_meta($movie_id, '_cinema_proximo_estreno');
    delete_post_meta($movie_id, '_cinema_pre_venta');
    delete_post_meta($movie_id, '_cinema_horarios');
}

// Eliminar todas las opciones del plugin de la base de datos
$wpdb->query("DELETE FROM {$wpdb->prefix}options WHERE option_name LIKE 'cinema_%'");

// Eliminar las traducciones del plugin si existen
$mo_files = glob(WP_LANG_DIR . '/plugins/cinema-plugin-*.mo');
foreach ($mo_files as $mo_file) {
    unlink($mo_file);
}
