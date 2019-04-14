<?php

$asociados = get_field('cortes_asociados');

function imagensocio($id)
{
    $uploads = wp_upload_dir();
    $rm_image_id = attachment_url_to_postid($uploads['baseurl'] . '/2019/04/nofoto.png');
   
    $image = get_field('asociado_foto', $id);
    if (get_field('asociado_foto', $id)) {
        $image = get_field('asociado_foto', $id);
    } else
        $image = $rm_image_id;
    $size = 'ecca_asociadomini'; // (thumbnail, medium, large, full or custom size)


    if ($image) {

        echo wp_get_attachment_image($image, $size, array( "class" => "img-responsive" ));
    }
}


function asociados_entrecortes($asociados){
    echo '<div class="asociados">';
    foreach ( $asociados as $socio ) {
	
        $post = get_post($socio);
        //d($post);
        //d(get_permalink($socio));
        echo '<div>';
        
        imagensocio($socio);
        echo '<a href="'.get_permalink($socio).'">'.$post->post_title.'</a>';
        
        echo '</div>';
        
	
    }
    echo '</div>';
    //echo count($asociados);

}


function entrecortes_single_view()
{
    global $asociados;
    //d($asociados);
    /* d($campos);
    $post = get_post();
    d($post);
    $lafecha = (get_the_time('F j \d\e Y'));
    d($lafecha);
    $content=get_post_field('post_content', $post->ID);
    d($content);
    $blocks = parse_blocks( $post->post_content);
    d($blocks); */
    $lafecha = (get_the_time('F j \d\e Y'));
    $post = get_post();
    //Cargo los bloques de Gutenberg
    $blocks = parse_blocks( $post->post_content);
    echo '<div class="entrada_entrecortes">';
    echo '<h1>'. $post->post_title.'</h1>';
    echo '<span class="fecha">'.$lafecha.'</span>';

    $content_markup = '';
    foreach ( $blocks as $block ) {
	
		$content_markup .= render_block( $block );
	
    }
    
    echo apply_filters( 'the_content', $content_markup );
    asociados_entrecortes($asociados);

    echo '</div>';
}

remove_action('genesis_loop', 'genesis_do_loop');
add_action('genesis_loop', 'entrecortes_single_view');
genesis();
 
