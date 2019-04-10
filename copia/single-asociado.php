<?php

$campos = get_fields();


function imagensocio()
{
    $uploads = wp_upload_dir();
    $rm_image_id = attachment_url_to_postid($uploads['baseurl'] . '/2019/04/nofoto.png');
    //$rm_image_id = attachment_url_to_postid('http://ecca.co/wp-content/uploads/2019/04/nofoto.png');
    //echo $rm_image_id;
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

function be_custom_loop()
{
    $post = get_post();
    $contenido = $post->post_content;
    do_action('genesis_before_entry');
    echo '<div class="asociado_single">';
    printf('<article %s>', genesis_attr('entry'));

    do_action('genesis_entry_header');
    if (genesis_get_custom_field('asociado_cargoecca')) {
        echo '<h5>' . genesis_get_custom_field('asociado_cargoecca') . '</h5>';
    }
    echo '<span class="tipo">' . genesis_get_custom_field('asociado_tipo') . '</span>';
    
    echo '<div class="asociado_interno_contenido">';
imagensocio();
    echo $contenido;

    echo '</div>';

    /* print_r("<pre>");
    print_r($post);
    print_r("</pre>"); */

  


    /* echo '<div class="asociado_interno_contenido">' 
    . $post->post_content . 
       
    '</div>'; */
    //tiene_entrecortes(get_the_ID());


    //nombresapellidos();
    //$foto = genesis_get_custom_field('asociado_foto');
    /* $foto = get_field('asociado_foto');
            print_r("<pre>");
            print_r($foto);
            print_r("</pre>"); */
    //imagensocio();

    /*     do_action('genesis_before_entry_content');
    printf('<div %s>', genesis_attr('entry-content'));
    do_action('genesis_entry_content');
    echo '</div>'; //** end .entry-content
    do_action('genesis_after_entry_content'); */

    do_action('genesis_entry_footer');

    echo '</article>';
    echo '</div>';

    do_action('genesis_after_entry');
}

remove_action('genesis_loop', 'genesis_do_loop');
add_action('genesis_loop', 'be_custom_loop');
genesis();
 