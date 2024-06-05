<?php
get_header();
?>

<div class="pelicula-detalles">
    <h1><?php the_title(); ?></h1>
    <div><?php the_content(); ?></div>
    <p>Clasificación: <?php echo get_post_meta(get_the_ID(), '_cinema_clasificacion', true); ?></p>
    <p>Duración: <?php echo get_post_meta(get_the_ID(), '_cinema_duracion', true); ?> min</p>
    <p>Tráiler: <a href="<?php echo esc_url(get_post_meta(get_the_ID(), '_cinema_trailer', true)); ?>" target="_blank">Ver tráiler</a></p>
    <p><img src="<?php echo esc_url(get_post_meta(get_the_ID(), '_cinema_poster', true)); ?>" alt="Póster de <?php the_title(); ?>"></p>
    <div class="horarios">
        <?php
        $horarios = get_post_meta(get_the_ID(), '_cinema_horarios', true);
        if ($horarios) {
            foreach ($horarios as $horario) {
                echo '<p>Hora: ' . esc_html($horario['hora']) . ', Formato: ' . esc_html($horario['formato']) . ', Doblaje: ' . esc_html($horario['doblaje']) . '</p>';
            }
        }
        ?>
    </div>
</div>

<?php
get_footer();
?>
