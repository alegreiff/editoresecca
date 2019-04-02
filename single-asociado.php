<?php

$campos = get_fields();
/* print_r("<pre>");
print_r($campos);
print_r("</pre>"); */



function be_custom_loop()
{

    do_action('genesis_before_entry');
    printf('<article %s>', genesis_attr('entry'));

    do_action('genesis_entry_header');
    if (genesis_get_custom_field('asociado_cargoecca')) {
        echo '<h5>' . genesis_get_custom_field('asociado_cargoecca') . '</h5>';
    }
    echo '<span class="tipo">' . genesis_get_custom_field('asociado_tipo') . '</span>';

    echo get_the_content();

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

    do_action('genesis_after_entry');
}

remove_action('genesis_loop', 'genesis_do_loop');
add_action('genesis_loop', 'be_custom_loop');
genesis();
