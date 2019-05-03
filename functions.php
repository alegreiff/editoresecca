<?php
/**
 * Genesis Sample.
 *
 * This file adds functions to the Genesis Sample Theme.
 *
 * @package Genesis Sample
 * @author  StudioPress
 * @license GPL-2.0-or-later
 * @link    https://www.studiopress.com/
 */

// Starts the engine.
require_once get_template_directory() . '/lib/init.php';

// Defines the child theme (do not remove).
define('CHILD_THEME_NAME', 'Genesis Sample');
define('CHILD_THEME_URL', 'https://www.studiopress.com/');
define('CHILD_THEME_VERSION', '2.8.0');

// Sets up the Theme.
require_once get_stylesheet_directory() . '/lib/theme-defaults.php';

add_action('after_setup_theme', 'genesis_sample_localization_setup');
/**
 * Sets localization (do not remove).
 *
 * @since 1.0.0
 */
function genesis_sample_localization_setup()
{

	load_child_theme_textdomain('genesis-sample', get_stylesheet_directory() . '/languages');
}

// Adds helper functions.
require_once get_stylesheet_directory() . '/lib/helper-functions.php';

// Adds image upload and color select to Customizer.
require_once get_stylesheet_directory() . '/lib/customize.php';

// Includes Customizer CSS.
require_once get_stylesheet_directory() . '/lib/output.php';

// Adds WooCommerce support.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-setup.php';

// Adds the required WooCommerce styles and Customizer CSS.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-output.php';

// Adds the Genesis Connect WooCommerce notice.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-notice.php';

add_action('after_setup_theme', 'genesis_child_gutenberg_support');
/**
 * Adds Gutenberg opt-in features and styling.
 *
 * @since 2.7.0
 */
function genesis_child_gutenberg_support()
{ // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- using same in all child themes to allow action to be unhooked.
	require_once get_stylesheet_directory() . '/lib/gutenberg/init.php';
}

add_action('wp_enqueue_scripts', 'genesis_sample_enqueue_scripts_styles');
/**
 * Enqueues scripts and styles.
 *
 * @since 1.0.0
 */
function genesis_sample_enqueue_scripts_styles()
{

	wp_enqueue_style(
		'genesis-sample-fonts',
		'//fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i,600,700',
		array(),
		CHILD_THEME_VERSION
	);

	wp_enqueue_style('dashicons');

	$suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';
	wp_enqueue_script(
		'genesis-sample-responsive-menu',
		get_stylesheet_directory_uri() . "/js/responsive-menus{$suffix}.js",
		array('jquery'),
		CHILD_THEME_VERSION,
		true
	);

	wp_localize_script(
		'genesis-sample-responsive-menu',
		'genesis_responsive_menu',
		genesis_sample_responsive_menu_settings()
	);

	wp_enqueue_script(
		'genesis-sample',
		get_stylesheet_directory_uri() . '/js/genesis-sample.js',
		array('jquery'),
		CHILD_THEME_VERSION,
		true
	);
}

/**
 * Defines responsive menu settings.
 *
 * @since 2.3.0
 */
function genesis_sample_responsive_menu_settings()
{

	$settings = array(
		'mainMenu'         => __('Menu', 'genesis-sample'),
		'menuIconClass'    => 'dashicons-before dashicons-menu',
		'subMenu'          => __('Submenu', 'genesis-sample'),
		'subMenuIconClass' => 'dashicons-before dashicons-arrow-down-alt2',
		'menuClasses'      => array(
			'combine' => array(
				'.nav-primary',
			),
			'others'  => array(),
		),
	);

	return $settings;
}

// Adds support for HTML5 markup structure.
add_theme_support('html5', genesis_get_config('html5'));

// Adds support for accessibility.
add_theme_support('genesis-accessibility', genesis_get_config('accessibility'));

// Adds viewport meta tag for mobile browsers.
add_theme_support('genesis-responsive-viewport');

// Adds custom logo in Customizer > Site Identity.
add_theme_support('custom-logo', genesis_get_config('custom-logo'));

// Renames primary and secondary navigation menus.
add_theme_support('genesis-menus', genesis_get_config('menus'));

// Adds image sizes.
add_image_size('sidebar-featured', 75, 75, true);

// Adds support for after entry widget.
add_theme_support('genesis-after-entry-widget-area');

// Adds support for 3-column footer widgets.
add_theme_support('genesis-footer-widgets', 3);

// Removes header right widget area.
unregister_sidebar('header-right');

// Removes secondary sidebar.
unregister_sidebar('sidebar-alt');

// Removes site layouts.
genesis_unregister_layout('content-sidebar-sidebar');
genesis_unregister_layout('sidebar-content-sidebar');
genesis_unregister_layout('sidebar-sidebar-content');

// Removes output of primary navigation right extras.
remove_filter('genesis_nav_items', 'genesis_nav_right', 10, 2);
remove_filter('wp_nav_menu_items', 'genesis_nav_right', 10, 2);

add_action('genesis_theme_settings_metaboxes', 'genesis_sample_remove_metaboxes');
/**
 * Removes output of unused admin settings metaboxes.
 *
 * @since 2.6.0
 *
 * @param string $_genesis_admin_settings The admin screen to remove meta boxes from.
 */
function genesis_sample_remove_metaboxes($_genesis_admin_settings)
{

	remove_meta_box('genesis-theme-settings-header', $_genesis_admin_settings, 'main');
	remove_meta_box('genesis-theme-settings-nav', $_genesis_admin_settings, 'main');
}

add_filter('genesis_customizer_theme_settings_config', 'genesis_sample_remove_customizer_settings');
/**
 * Removes output of header and front page breadcrumb settings in the Customizer.
 *
 * @since 2.6.0
 *
 * @param array $config Original Customizer items.
 * @return array Filtered Customizer items.
 */
function genesis_sample_remove_customizer_settings($config)
{

	unset($config['genesis']['sections']['genesis_header']);
	unset($config['genesis']['sections']['genesis_breadcrumbs']['controls']['breadcrumb_front_page']);
	return $config;
}

// Displays custom logo.
add_action('genesis_site_title', 'the_custom_logo', 0);

// Repositions primary navigation menu.
remove_action('genesis_after_header', 'genesis_do_nav');
add_action('genesis_header', 'genesis_do_nav', 12);

// Repositions the secondary navigation menu.
remove_action('genesis_after_header', 'genesis_do_subnav');
add_action('genesis_footer', 'genesis_do_subnav', 10);

add_filter('wp_nav_menu_args', 'genesis_sample_secondary_menu_args');
/**
 * Reduces secondary navigation menu to one level depth.
 *
 * @since 2.2.3
 *
 * @param array $args Original menu options.
 * @return array Menu options with depth set to 1.
 */
function genesis_sample_secondary_menu_args($args)
{

	if ('secondary' !== $args['theme_location']) {
		return $args;
	}

	$args['depth'] = 1;
	return $args;
}

add_filter('genesis_author_box_gravatar_size', 'genesis_sample_author_box_gravatar');
/**
 * Modifies size of the Gravatar in the author box.
 *
 * @since 2.2.3
 *
 * @param int $size Original icon size.
 * @return int Modified icon size.
 */
function genesis_sample_author_box_gravatar($size)
{

	return 90;
}

add_filter('genesis_comment_list_args', 'genesis_sample_comments_gravatar');
/**
 * Modifies size of the Gravatar in the entry comments.
 *
 * @since 2.2.3
 *
 * @param array $args Gravatar settings.
 * @return array Gravatar settings with modified size.
 */
function genesis_sample_comments_gravatar($args)
{

	$args['avatar_size'] = 60;
	return $args;
}

//JAIME DE GREIFF
add_action('init', 'crear_un_cpt_asociados');
function crear_un_cpt_asociados()
{
	$args = array(
		'public' => true,
		'labels' => array(
			'name' => __('Asociados'),
			'singular_name' => __('Asociado')
		),
		'show_in_rest' => true,
		'has_archive' => 'asociados',
		'supports' => array('title', 'editor', 'custom-fields', ' thumbnail'),

	);
	register_post_type('asociado', $args);
}

// Adds image sizes.
add_image_size('ecca_entrecortes', 400, 167, true);
add_image_size('ecca_asociadomini', 150, 150, true);
add_image_size('ecca_asociadomedium', 400, 400, true);
add_image_size('ecca_banner', 1600, 500, true);
add_image_size('ecca_bannermedio', 800, 250, true);
add_image_size('ecca_bannermini', 400, 125, true);


/*
Administrador
E-378_H$1-0n7*2
*/

//add_theme_support('post-thumbnails');
//add_post_type_support('asociado', 'thumbnail'); 

//WEB ORIGIN
//https://github.com/srikat/genesis-sample-task-runner


// Agrega fuentes de Google Fonts
// Enqueue the fonts
	 function wpb_load_fa() {
 
wp_enqueue_style( 'wpb-fa', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.1/css/all.min.css' );
 
}
 
add_action( 'wp_enqueue_scripts', 'wpb_load_fa' );


function add_fonts_to_theme(){
        wp_enqueue_style("adding-google-fonts", all_google_fonts());
     }
     add_action("wp_enqueue_scripts","add_fonts_to_theme");

    // Choose the fonts 
    function all_google_fonts() {
        $fonts = array(
               "Pacifico",
               "Oswald",
               "Inconsolata:400,700"
            );
        $fonts_collection = add_query_arg(array(
            "family"=>urlencode(implode("|",$fonts)),
            "subset"=>"latin"
            ),'https://fonts.googleapis.com/css');
        return $fonts_collection;
     }




function custom_single_template($the_template) {
    foreach ( (array) get_the_category() as $cat ) {
        if ( locate_template("single-{$cat->slug}.php") ) {
            return locate_template("single-{$cat->slug}.php");
        }
    }
    return $the_template;
}
add_filter( 'single_template', 'custom_single_template');


//FOOTER CUSTOM

remove_action( 'genesis_footer', 'genesis_footer_markup_open', 5 );
remove_action( 'genesis_footer', 'genesis_do_footer' );
remove_action( 'genesis_footer', 'genesis_footer_markup_close', 15 );
// Customize site footer
add_action( 'genesis_footer', 'sp_custom_footer' );
function sp_custom_footer() { ?>

	<div class="site-footer"><div class="wrap">
	<p>
	<span>ECCA</span> - Editores Cinematográficos Colombianos Asociados
	<i class="fas fa-play-circle"></i>
	</p></div></div>

<?php
}

// Adds a [social-icons] shortcode to output Genesis Simple Share icons in posts
// https://wordpress.org/plugins/genesis-simple-share/
// Add the code below to your active theme's functions.php file,
// or use in a site-specific plugin.
// The shortcode takes no attributes; change your icon settings via Genesis → Simple Share.
add_shortcode( 'social-icons', 'gss_shortcode' );
function gss_shortcode() {
	global $Genesis_Simple_Share;
	$icons = '';
	
	if ( function_exists( 'genesis_share_get_icon_output' ) ) {
		$location = uniqid( 'gss-shortcode-' );
		$icons = genesis_share_get_icon_output( $location, $Genesis_Simple_Share->icons );
	}
	
	return $icons;
}

//SLIDER EN HOME
/* if($_SERVER["REQUEST_URI"] == '/'){
	add_action('wp_enqueue_scripts', 'carrusel_home');
	

} */
add_action('wp_enqueue_scripts', 'carrusel_home');
function carrusel_home() {
    wp_register_script('carrusel', "//cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js", array('jquery'), '1.0.0', true );
    wp_enqueue_script( 'carrusel2', get_stylesheet_directory_uri() . '/js/carrusel_home.js', array('jquery'), '1.0.0', true );
    //wp_enqueue_style( 'carrusel', '//cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.css' );
	wp_enqueue_script('carrusel');
	wp_enqueue_script('carrusel2');
	


}