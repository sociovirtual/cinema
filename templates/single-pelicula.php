<?php
get_header();
?>

<div class="pelicula-detalles">
    <h1><?php the_title(); ?></h1>
    <div><?php the_content(); ?></div>
    <p>Clasificación: <?php echo get_post_meta(get_the_ID(), '_cinema_clasificacion', true); ?></p>
    <p>Duración: <?php echo get_post_meta(get_the_ID(), '_cinema_duracion', true); ?> min</p>
    <p>Tráiler: <a href="<?php echo esc_url(get_post_meta(get_the_ID(), '_cinema_trailer', true)); ?>" target="_blank">Ver tráiler</a></p>
    <?php
    $poster_id = get_post_meta(get_the_ID(), '_cinema_poster_id', true);
    if ($poster_id) {
        echo '<p>Póster:</p>';
        echo '<div><img src="' . esc_url(wp_get_attachment_image_url($poster_id, 'thumbnail')) . '" style="max-width: 200px; height: auto;" /></div>';
    }
    ?>
    <p>En Cartelera: <?php echo (get_post_meta(get_the_ID(), '_cinema_cartelera', true) == '1') ? 'Sí' : 'No'; ?></p>
    <p>Próximo Estreno: <?php echo (get_post_meta(get_the_ID(), '_cinema_proximo_estreno', true) == '1') ? 'Sí' : 'No'; ?></p>
    <p>Pre-Venta: <?php echo (get_post_meta(get_the_ID(), '_cinema_pre_venta', true) == '1') ? 'Sí' : 'No'; ?></p>

    <div class="horarios">
        <h3>Horarios:</h3>
        <?php
        $horarios = get_post_meta(get_the_ID(), '_cinema_horarios', true);
        if ($horarios) {
            echo '<ul>';
            foreach ($horarios as $horario) {
                echo '<li>';
                echo 'Hora: ' . esc_html($horario['hora']) . ' | ';
                echo 'Formato: ' . esc_html($horario['formato']) . ' | ';
                echo 'Doblaje: ' . esc_html($horario['doblaje']);
                echo '</li>';
            }
            echo '</ul>';
        }
        ?>
    </div>
</div>

<?php
get_footer();
?>
