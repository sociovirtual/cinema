<?php
get_header();
?>

<div class="pelicula-detalles">
    <h1><?php the_title(); ?></h1>
    <div><?php the_content(); ?></div>
    <p>Duración: <?php echo get_post_meta(get_the_ID(), '_cinema_duracion', true); ?> min</p>
    <p>Director: <?php echo get_post_meta(get_the_ID(), '_cinema_director', true); ?></p>
</div>

<?php
get_footer();
?>
