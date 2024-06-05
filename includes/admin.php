<?php

// Estilos y Scripts para el Admin
function cinema_admin_enqueue_scripts() {
    wp_enqueue_style('cinema-admin-style', plugin_dir_url(__FILE__) . '../css/admin-style.css');
    wp_enqueue_script('cinema-admin-script', plugin_dir_url(__FILE__) . '../js/admin-script.js', array('jquery'), null, true);
}
add_action('admin_enqueue_scripts', 'cinema_admin_enqueue_scripts');
