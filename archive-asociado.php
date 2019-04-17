<?php
/**
 * Genesis custom loop
 */
function be_custom_loop()
{
    global $post;

    // arguments, adjust as needed
    $args = array(
        'post_type'      => 'asociado',
        'post_status'    => 'publish',
        'paged'          => get_query_var('paged'),
        'meta_key'            => 'asociado_orden',
        'meta_compare' => '!=',
        'orderby'            => 'meta_value_num',
        'order'                => 'ASC',
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => 'asociado_cargoecca',
                'value' => '',
                'type' => 'CHAR',
                'compare' => '!='
            )
        )

    );

    /* 
	Overwrite $wp_query with our new query.
	The only reason we're doing this is so the pagination functions work,
	since they use $wp_query. If pagination wasn't an issue, 
	use: https://gist.github.com/3218106
    */



    global $wp_query;
    $wp_query = new WP_Query($args);
    echo '<div class="asociados">';
    if (have_posts()) : while (have_posts()) : the_post();
            do_action('genesis_before_entry');
            /* $r = get_fields();

            print_r("<pre>");
            print_r($r);
            print_r("</pre>"); */



            printf('<article %s>', genesis_attr('entry'));

            do_action('genesis_entry_header');
            if (genesis_get_custom_field('asociado_cargoecca')) {
                echo '<h5>' . genesis_get_custom_field('asociado_cargoecca') . '</h5>';
            }
            echo '<span class="tipo">' . genesis_get_custom_field('asociado_tipo') . '</span>';
            


            nombresapellidos();
            
            //$foto = genesis_get_custom_field('asociado_foto');
            /* $foto = get_field('asociado_foto');
            print_r("<pre>");
            print_r($foto);
            print_r("</pre>"); */
            imagensocio();
            tiene_entrecortes(get_the_ID());

            /* do_action('genesis_before_entry_content');
            printf('<div %s>', genesis_attr('entry-content'));
            do_action('genesis_entry_content');
            echo '</div>'; //** end .entry-content
            do_action('genesis_after_entry_content'); */

            do_action('genesis_entry_footer');

            echo '</article>';

            do_action('genesis_after_entry');
        endwhile;
        /** end of one post **/
        do_action('genesis_after_endwhile');
    else : /** if no posts exist **/
        do_action('genesis_loop_else');
    endif;
    /** end loop **/
    echo '</div>';

    wp_reset_query();
}


function lista_socios_activos()
{
    global $post;

    // arguments, adjust as needed
    $args = array(
        'posts_per_page' => -1,
        'post_type'      => 'asociado',
        'post_status'    => 'publish',
        'paged'          => get_query_var('paged'),
        'meta_key'            => 'asociado_apellidos',
        'orderby'            => 'meta_value',
        'order'                => 'ASC',
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => 'asociado_cargoecca',
                'value' => '',
                'type' => 'CHAR',
                'compare' => '='
            ), array(
                'key' => 'asociado_tipo',
                'value' => 'ACTIVO',
                'compare' => '='
            )
        )

    );
    global $wp_query;
    $wp_query = new WP_Query($args);
    echo '<h3>Socios activos</h3>';
    echo '<div class="asociados">';
    if (have_posts()) : while (have_posts()) : the_post();
            do_action('genesis_before_entry');

            printf('<article %s>', genesis_attr('entry'));

            do_action('genesis_entry_header');
            echo '<span class="tipo">' . genesis_get_custom_field('asociado_tipo') . '</span>';
            tiene_entrecortes(get_the_ID());
            nombresapellidos();
            imagensocio();

            do_action('genesis_entry_footer');

            echo '</article>';

            do_action('genesis_after_entry');
        endwhile;
        /** end of one post **/
        do_action('genesis_after_endwhile');
    else : /** if no posts exist **/
        do_action('genesis_loop_else');
    endif;
    /** end loop **/
    echo '</div>';

    wp_reset_query();
}

function lista_socios_adherentes()
{
    global $post;

    // arguments, adjust as needed
    $args = array(
        'posts_per_page' => -1,
        'post_type'      => 'asociado',
        'post_status'    => 'publish',
        'paged'          => get_query_var('paged'),
        'meta_key'            => 'asociado_apellidos',
        'orderby'            => 'meta_value',
        'order'                => 'ASC',
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => 'asociado_cargoecca',
                'value' => '',
                'type' => 'CHAR',
                'compare' => '='
            ), array(
                'key' => 'asociado_tipo',
                'value' => 'ADHERENTE',
                'compare' => '='
            )
        )

    );
    global $wp_query;
    $wp_query = new WP_Query($args);
    echo '<h3>Socios adherentes</h3>';
    echo '<div class="asociados">';
    if (have_posts()) : while (have_posts()) : the_post();
            do_action('genesis_before_entry');

            printf('<article %s>', genesis_attr('entry'));

            do_action('genesis_entry_header');
            echo '<span class="tipo">' . genesis_get_custom_field('asociado_tipo') . '</span>';
            nombresapellidos();
            imagensocio();


            do_action('genesis_entry_footer');

            echo '</article>';

            do_action('genesis_after_entry');
        endwhile;
        /** end of one post **/
        do_action('genesis_after_endwhile');
    else : /** if no posts exist **/
        do_action('genesis_loop_else');
    endif;
    /** end loop **/
    echo '</div>';

    wp_reset_query();
}

function nombresapellidos()
{
    echo genesis_get_custom_field('asociado_nombres') . " ";
    echo genesis_get_custom_field('asociado_apellidos');
}

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
    $size = 'ecca_asociadomini'; // (thumbnail, medium, large, full or custom size)


    if ($image) {

        echo wp_get_attachment_image($image, $size);
        //echo ruta_subidas() . '2019/04/nofoto.png';
        //echo $image;
        //echo $rm_image_id;


        //echo ruta_subidas();
    }
}

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

function ruta_subidas()
{
    $upload_dir = wp_upload_dir();
    return trailingslashit($upload_dir['basedir']);
}

add_action('genesis_loop', 'be_custom_loop');
add_action('genesis_loop', 'lista_socios_activos');
add_action('genesis_loop', 'lista_socios_adherentes');



remove_action('genesis_loop', 'genesis_do_loop');
remove_action('genesis_entry_header', 'genesis_do_post_title');
remove_action('genesis_entry_header', 'genesis_post_info', 12);



genesis();
 