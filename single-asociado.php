<?php

$campos = get_fields();


function imagensocio()
{
    $uploads = wp_upload_dir();
    $rm_image_id = attachment_url_to_postid($uploads['baseurl'] . '/2019/04/nofoto.png');
   
    $image = get_field('asociado_foto');
    if (get_field('asociado_foto')) {
        $image = get_field('asociado_foto');
    } else
        $image = $rm_image_id;
    $size = 'medium'; // (thumbnail, medium, large, full or custom size)


    if ($image) {

        echo wp_get_attachment_image($image, $size);
    }
}

remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );

function asociado_single_view()
{
    global $campos;
    $post = get_post();
    echo '<div class="asociado_single">';
    echo '<h2>'
        .genesis_get_custom_field('asociado_nombres'). 
        ' <span class="apellido">'
        . genesis_get_custom_field('asociado_apellidos').
        '</span>
    <h2>';

    if (genesis_get_custom_field('asociado_cargoecca')) {
        echo '<h5>' . genesis_get_custom_field('asociado_cargoecca') . '</h5>';
    }else{
        echo '<p>nolas</p>';
    }
    echo '<span class="tipo">' . genesis_get_custom_field('asociado_tipo') . '</span>';
    imagensocio();
    echo $post->post_content;
    do_action('genesis_entry_footer');

    
    echo '</div>';

    do_action('genesis_after_entry');
}

remove_action('genesis_loop', 'genesis_do_loop');
add_action('genesis_loop', 'asociado_single_view');
genesis();
 