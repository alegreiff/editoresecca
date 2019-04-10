<?php
/**
 * Template Name: ECCA - página de inicio
 * Plantilla INICIO E C C A

 * @package ecca
 */

remove_action('genesis_loop', 'genesis_do_loop');
add_action('genesis_loop', 'child_grid_loop_helper');
add_action('genesis_loop', 'ecca_noticias_home');
function child_grid_loop_helper()
{
    wp_reset_query();
    $args = array(
        'category_name' => 'entre-cortes',
        'posts_per_page' => '4',
    );
    loop_entrecortes($args, 'ecca-home-entrecortes');
}

function ecca_noticias_home()
{
    wp_reset_query();
    $args = array(
        'category_name' => 'noticias',
        'posts_per_page' => '2',
    );
    loop_entrecortes($args, 'ecca-home-noticias');
}

function loop_entrecortes($args, $claseprincipal)
{
    global $wp_query;
    $wp_query = new WP_Query($args);
    if ($wp_query->have_posts()) :
        echo '<div class="' . $claseprincipal . '">';

        while ($wp_query->have_posts()) : $wp_query->the_post();
            global $post;

            ecca_loop($args);

        endwhile;
        if (isset($args['paged'])) {
            genesis_posts_nav();
        }
        echo '</div>';
    endif;
    wp_reset_query();
}

function ecca_loop($args)
{
    //Se define la categoría para mostrar en HOME y la clase que generará el color
    $categoria_principal = 'KITKAT';
    $fields = get_field('cortes_asociados');
    if (is_array($fields)) {

        //d($fields);
    }


    //$clase = get_category($categoria_principal[2]);
    //$clase = $clase->slug;
    $fecha = ucfirst(get_the_date('F j \d\e\ Y'));

    ?>
<article>
    <span class="fecha">dd/mm<?php echo $fecha; ?></span>
    <div class="imagen"><?php echo the_post_thumbnail('ecca_entrecortes', array('class' => 'ecca_entrecortes_img', 'title' => get_the_title(), 'alt' => 'Imagen miniatura: ' . get_the_title())); ?></div>
    <h2 class="<?php echo 'MIKLASELOKA'; ?>"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>

    <div class="entrada"><?php the_excerpt(); ?></div>
    <div><?php 
            if (is_array($fields)) {
                foreach ($fields as $v) {
                    //echo "Valor actual de \$fields: $v.\n";
                    echo '<span><a href="' . get_the_permalink($v) . '">' . get_the_title($v) . '</a></span> ';
                }
            }
            ?></div>
    <?php 
    echo '<hr /> <div class="categorias">';
    the_category(' - ');
    echo '</div>';
    ?>
</article>
<?php

}
genesis();
