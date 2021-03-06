<?php

$campos = get_fields();

function tiene_entrecortes($id)
{
    $entrecortes = get_posts(array(
        'post_type' => 'post',
        'meta_query' => array(
            array(
                'key' => 'cortes_asociados', // name of custom field
                'value' => '"' . $id . '"', // matches exactly "123", not just 123. This prevents a match for "1234"
                'compare' => 'LIKE'
            )
        )
    ));
    if ($entrecortes) : ?>
<ul>
    <?php foreach ($entrecortes as $doctor) : ?>
    <li>
        <a href="<?php echo get_permalink($doctor->ID); ?>">

            <?php echo get_the_title($doctor->ID); ?>
        </a>
    </li>
    <?php endforeach; ?>
</ul>
<?php endif;
}

function imagensocio()
{
    $uploads = wp_upload_dir();
    $rm_image_id = attachment_url_to_postid($uploads['baseurl'] . '/2019/04/nofoto.png');
   
    $image = get_field('asociado_foto');
    if (get_field('asociado_foto')) {
        $image = get_field('asociado_foto');
    } else
        $image = $rm_image_id;
    $size = 'ecca_asociadomedium'; // (thumbnail, medium, large, full or custom size)


    if ($image) {

        echo wp_get_attachment_image($image, $size, array( "class" => "img-responsive" ));
    }
}

remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );

function asociado_single_view()
{
    global $campos;
    $post = get_post();
    echo '<div class="asociado_single">';
    echo do_shortcode('[social-icons]');
    echo '<h2>'
        .genesis_get_custom_field('asociado_nombres'). 
        ' <span class="apellido">'
        . genesis_get_custom_field('asociado_apellidos').
        '</span>
        </h2>';

    echo '<h5>';
        if (genesis_get_custom_field('asociado_cargoecca')) {
        echo genesis_get_custom_field('asociado_cargoecca') .' ECCA';
    }
    echo '</h5>';
    echo '<h5 class="tipo"><i class="far fa-address-book"></i> Socio <span class="socio">' . genesis_get_custom_field('asociado_tipo') . '</span> ECCA</h5>';

    imagensocio();
    echo '<div class="asociado_bio">'.$post->post_content.'</div>';
    do_action('genesis_entry_footer');

    tiene_entrecortes($post->ID);
    echo '</div>';

    do_action('genesis_after_entry');
}

remove_action('genesis_loop', 'genesis_do_loop');
add_action('genesis_loop', 'asociado_single_view');

if ( function_exists( 'genesis_share_icon_output' ) ) {
    global $Genesis_Simple_Share;
    genesis_share_icon_output( 'aside' , $Genesis_Simple_Share->icons );//outputs social shares as defined in plugin
} 
genesis();
 