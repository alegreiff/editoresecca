<?php
remove_action('genesis_loop', 'genesis_do_loop');
add_action('genesis_loop', 'child_grid_loop_helper');
function child_grid_loop_helper()
{
    wp_reset_query();

    $args = array(

        'posts_per_page' => '9',

    );
    //Llama al loop general de MaguaRED
    maguared_loop_general($args, 'maguared-home-multipost');
}



function maguared_loop_general($args, $claseprincipal)
{
    global $wp_query;
    $wp_query = new WP_Query($args);
    if ($wp_query->have_posts()) :
        echo '<div class="' . $claseprincipal . '">';

        while ($wp_query->have_posts()) : $wp_query->the_post();
            global $post;

            maguared_loop_estructura($args);

        endwhile;
        if (isset($args['paged'])) {
            genesis_posts_nav();
        }
        echo '</div>';
    endif;
    wp_reset_query();
}

function maguared_loop_estructura($args)
{
    //Se define la categoría para mostrar en HOME y la clase que generará el color
    //$categoria_principal = principaal();
    //$clase = get_category($categoria_principal[2]);
    //$clase = $clase->slug;
    $clase = '';
    $fecha = ucfirst(get_the_date('F j \d\e Y'));

    ?>
<article>
    <span class="fecha"><?php echo $fecha; ?></span>
    <div class="imagen"><?php echo the_post_thumbnail('maguared-mini-image', array('class' => 'maguared-miniatura', 'title' => get_the_title(), 'alt' => 'Imagen miniatura: ' . get_the_title())); ?></div>
    <h2 class="<?php echo $clase; ?>"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>

    <div class="entrada"><?php the_excerpt(); ?></div>

    <?php 
    if ($args['category_name'] == 'portada') {
        //echo $categoria_principal[0];
        echo '<div class="categorias unica">';
        echo '<i class="icon ion-md-star-outline"></i> <a href="' . $categoria_principal[1] . '">' . htmlspecialchars($categoria_principal[0]) . '</a>';
        echo '</div>';
    } else {
        echo '<div class="categorias">';
        the_category(' - ');
        echo '</div>';
    }

    ?>

    <!--<a href="#" class="leermas">Leer más</a>-->

</article>
<?php

}

genesis();
