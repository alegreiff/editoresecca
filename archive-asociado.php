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
        'posts_per_page' => 100,
        'post_status'    => 'publish',
        'paged'          => get_query_var('paged'),
        'meta_key'            => 'asociado_apellidos',
        'orderby'            => 'meta_value',
        'order'                => 'ASC'

    );

    /* 
	Overwrite $wp_query with our new query.
	The only reason we're doing this is so the pagination functions work,
	since they use $wp_query. If pagination wasn't an issue, 
	use: https://gist.github.com/3218106
	*/
    global $wp_query;
    $wp_query = new WP_Query($args);

    if (have_posts()) : while (have_posts()) : the_post();
            do_action('genesis_before_entry');

            printf('<article %s>', genesis_attr('entry'));

            do_action('genesis_entry_header');

            do_action('genesis_before_entry_content');
            printf('<div %s>', genesis_attr('entry-content'));
            do_action('genesis_entry_content');
            echo '</div>'; //** end .entry-content
            do_action('genesis_after_entry_content');

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

    wp_reset_query();
}
add_action('genesis_loop', 'be_custom_loop');
remove_action('genesis_loop', 'genesis_do_loop');


genesis();
