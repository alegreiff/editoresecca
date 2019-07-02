<?php
/**
 * Template Name: ECCA - página de inicio
 * Plantilla INICIO E C C A

 * @package ecca
 */
$entrecortes_describe = get_field('eccahome_describe_entrecortes');
$banner = get_field('eccahome_banner');
if($banner == true){
    add_action( 'genesis_before_content', 'bannerHome' );
}

function bannerHome() {
    $banneres = get_field('eccahome_slider');
    if(count($banneres) === 1){
        $elemento = $banneres[0];
        if($elemento['eccahome_tipourl']==='Interna'){
                    $elenlace = esc_url( get_permalink($elemento['eccahome_interno']) );
                    $target='_self';
                    $title = get_the_title( $elemento['eccahome_interno'] );

                }else{
                    $elenlace = $elemento['eccahome_externa'];
                    $target='_blank';
                    $title = $elemento['eccahome_externa'];
                }
        

        echo '<div class="ecca_carrusel_home">
        <a href="'.$elenlace.'" target="'.$target.'"><img src="'.get_field("eccahome_slider")[0]["eccahome_imagen"].'" title="'.$title.'"></a>
        </div>';
    }else{
        echo '<div class="ecca_carrusel_home ciclo">
                <div class="slider">';

            foreach($banneres as $elemento){
                if($elemento['eccahome_tipourl']==='Interna'){
                    $elenlace = esc_url( get_permalink($elemento['eccahome_interno']) );
                    $target='_self';
                    $title = get_the_title( $elemento['eccahome_interno'] );

                }else{
                    $elenlace = $elemento['eccahome_externa'];
                    $target='_blank';
                    $title = $elemento['eccahome_externa'];
                }
                //esc_url( get_permalink(10) );

                    echo '<div>
                            <a href="'.$elenlace.'" target="'.$target.'">
                                <img src="'.$elemento['eccahome_imagen'].'" title="'.$title.'">
                            </a>


                    </div>';
                }
        echo '</div></div>';
    }

    
    /* print_r("<pre>");
    print_r(get_fields());
    print_r("</pre>");  */ 
}



/* d(get_fields());
 
 d($banner); */
//echo $banner;
remove_action('genesis_loop', 'genesis_do_loop');

add_action('genesis_loop', 'ecca_noticias_home');
add_action('genesis_loop', 'child_grid_loop_helper');

function child_grid_loop_helper()
{
    /* global $banner;
    echo '<hr />INICIO';
    
    echo 'FIN<hr />'; */
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
 global $entrecortes_describe;
    global $wp_query;
    $wp_query = new WP_Query($args);
    if ($wp_query->have_posts()) :
        echo '<div class="' . $claseprincipal . '">';
        if($claseprincipal === 'ecca-home-entrecortes'){
            echo '<h4>Entre Cortes</h4>
            <p class="describe_ec">
            '.$entrecortes_describe.'
            </p>';
        }

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
    //$categoria_principal = 'KITKAT';
    $fields = get_field('cortes_asociados');
    if (is_array($fields)) {

        //d($fields);
    }
    


    //$clase = get_category($categoria_principal[2]);
    //$clase = $clase->slug;
    $fecha = ucfirst(get_the_date('F j \d\e\ Y'));

    ?>
<article>
    <span class="fecha"><?php echo $fecha; ?></span>
    <div class="imagen"><?php echo the_post_thumbnail('ecca_entrecortes', array('class' => 'ecca_entrecortes_img', 'title' => get_the_title(), 'alt' => 'Imagen miniatura: ' . get_the_title())); ?></div>
    <h2 class="<?php echo 'titulo'; ?>"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>

    <div class="entrada"><?php the_excerpt(); ?></div>
    
    <div class="homeentrecortes_socios"><?php 
echo '<span class="categorias">';
    //the_category(' - ');
    echo '</span>';
            if (is_array($fields)) {
                $i = 0;
                foreach ($fields as $v) {
                    //echo "Valor actual de \$fields: $v.\n" . $i;
                    echo '<span class="eccasociohome"><a href="' . get_the_permalink($v) . '">' . get_the_title($v) . '</a></span> ';
                    $i++;
                }
            }
            ?></div>
    
</article>
<?php

}
genesis();