<?php
get_header();
?>

<div class="pelicula-detalles">
    <h1><?php the_title(); ?></h1>
    <div><?php the_content(); ?></div>
    <p>Clasificación: <?php echo get_post_meta(get_the_ID(), '_cinema_clasificacion', true); ?></p>
    <p>Duración: <?php echo get_post_meta(get_the_ID(), '_cinema_duracion', true); ?> min</p>
    <p>Tráiler: <a href="<?php echo esc_url(get_post_meta(get_the_ID(), '_cinema_trailer', true)); ?>" target="_blank">Ver tráiler</a></p>
    <p>Formato: <?php echo get_post_meta(get_the_ID(), '_cinema_formato', true); ?></p>
    <p>Horarios: <?php echo nl2br(get_post_meta(get_the_ID(), '_cinema_horarios', true)); ?></p>
    <p>Doblaje: <?php echo get_post_meta(get_the_ID(), '_cinema_doblaje', true); ?></p>
    <p><img src="<?php echo esc_url(get_post_meta(get_the_ID(), '_cinema_poster', true)); ?>" alt="Póster de <?php the_title(); ?>"></p>
</div>

<?php
get_footer();
?>
