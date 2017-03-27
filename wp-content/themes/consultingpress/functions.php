<?php

/* ---------------------------------------------------
 * Theme: ConsultingPress
 * Author: Pixel Industry Ltd.
 * URL: www.pixel-industry.com
  -------------------------------------------------- */

/* -------------------------------------------------------------------- 
 * Define constanst for template directory uri and directory path
  -------------------------------------------------------------------- */

if ( !defined( 'VOLCANNO_THEME_NAME' ) ) {
    define( 'VOLCANNO_THEME_NAME', "Consulting Press" );
}

if ( !defined( 'VOLCANNO_THEME_VERSION' ) ) {
    define( 'VOLCANNO_THEME_VERSION', "1.0" );
}

if ( !defined( 'VOLCANNO_TEMPLATEURL' ) ) {
    define( 'VOLCANNO_TEMPLATEURL', get_template_directory_uri() );
}

if ( !defined( 'VOLCANNO_THEME_DIR' ) ) {
    define( 'VOLCANNO_THEME_DIR', get_template_directory() . '/' );
}
if ( !defined( 'VOLCANNO_STYLESHEET_DIR' ) ) {
    define( 'VOLCANNO_STYLESHEET_DIR', get_stylesheet_directory() );
}

/**
 * Main configuration array for theme.
 * 
 * @var array $volcanno_theme_config
 */
global $volcanno_theme_config;

/* -------------------------------------------------------------------- 
 * Array with configurations for current theme
 * -------------------------------------------------------------------- */

$volcanno_theme_config = array(
    'icon_fonts' => array(
        'font_awesome' => array(
            'local' => VOLCANNO_THEME_DIR . "/includes/assets/fonts/font-awesome/css/font-awesome.css",
            'remote' => "http://pixel-industry.com/resources/icon-fonts/font-awesome-4.3.0/css/font-awesome.css"
        ),
        'pixons' => array(
            'local' => VOLCANNO_THEME_DIR . "/includes/assets/pixons/style.css",
            'remote' => "http://pixel-industry.com/resources/icon-fonts/pixons/style.css"
        ),
        'linecons' => array(
            'local' => VOLCANNO_THEME_DIR . "/includes/assets/linecons/linecons.css",
            'remote' => "http://pixel-industry.com/resources/icon-fonts/linecons/linecons.css"
        )
    ),
    'allowed_html_tags' => array(
        'a' => array(
            'href' => array(),
            'title' => array(),
            'class' => array()
        ),
        'p' => array(),
        'br' => array(),
        'em' => array(),
        'strong' => array(),
        'span' => array( 'class' => array() ),
    ),
    'menu_caret' => '0',
    'text_domain' => 'consultingpress',
    'default_color' => "color-default"
);

$volcanno_theme_config = apply_filters( 'volcanno_theme_config', $volcanno_theme_config );

/**
 * Return path to directory where configuration files reside
 * 
 * @return string
 */
function volcanno_config_files_dir() {
    return trailingslashit( VOLCANNO_THEME_DIR ) . 'includes/configuration';
}

add_filter( 'volcanno_config_files_dir', 'volcanno_config_files_dir' );

/**
 * Prevent Meta Box Plugin undefined functions 
 */
if ( !function_exists( 'rwmb_meta' ) ) {

    function rwmb_meta( $key, $args = '', $post_id = null ) {
        // This function doesn't return nothing
    }

}

/**
 * Prevent WooCommerce undefined function
 */
if ( !function_exists( 'is_shop' ) ) {

    function is_shop() {
        return false;
    }

}

/**
 * Load actions, filters and partials
 */
require_once VOLCANNO_THEME_DIR . 'core/core-includes.php';
include_once VOLCANNO_THEME_DIR . 'includes/volcanno-partials.php';
include_once VOLCANNO_THEME_DIR . 'includes/volcanno-actions.php';
include_once VOLCANNO_THEME_DIR . 'includes/volcanno-filters.php';

/**
 * Enable composer custom features
 */
if ( VOLCANNO_PI_SHORTCODES ) {
    Volcanno_Visual_Composer::features_enable( array( 'shadow', 'overlay' ) );
}

/**
 * Disable visual composer frontend editor
 */
if ( function_exists('vc_disable_frontend') ) {
    vc_disable_frontend();
}

/**
 * Set default post types for Visual Composer
 */
if ( function_exists('vc_set_default_editor_post_types') ) {
    vc_set_default_editor_post_types( array(
        'page',
        'post',
        'pi_case_studies'
    ) );
}

function custom_loginlogo() {
echo '<style type="text/css">
h1 a {background-image: url('.get_bloginfo('template_directory').'/images/login_logo.png) !important; height:500px;
		width:320px; }
</style>';
}
add_action('login_head', 'custom_loginlogo');

if ( function_exists('register_sidebar') )
register_sidebar(array(
 'name' => 'sidebar2',
 'before_widget' => '<div class="widget">',
 'after_widget' => '</div>',
 'before_title' => '<h3>',
 'after_title' => '</h3>',
));