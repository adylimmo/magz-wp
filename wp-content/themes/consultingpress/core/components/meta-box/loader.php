<?php

/* ---------------------------------------------------------
 * MetaBox Loader
 *
 * Class for loading MetaBox configuration file 
 * and customs cripts
  ---------------------------------------------------------- */

class Volcanno_Core_Meta_Box_Loader {

    private static $tabs;

    /**
     * Init MetaBox Loader
     */
    static function init() {

        // verify that MetaBox plugin is installed
        if ( VOLCANNO_META_BOX ) {
            // register user defined fields
            Volcanno_Core_Meta_Box_Loader::register_fields();

            // get array of tabs
            if ( is_callable( array('Volcanno_Meta_Box', 'metabox_tabs_visibility') ) ) {
                static::$tabs = Volcanno_Meta_Box::metabox_tabs_visibility();
            }

            // include custom script for Tabs control
            if ( is_admin() ) {
                
                add_action( 'admin_enqueue_scripts', 'Volcanno_Core_Meta_Box_Loader::enqueue_scripts' );
            }
        }
    }

    /**
     * Load script that holds field registration array
     * 
     * @global type $volcanno_theme_config
     */
    static function register_fields() {
        global $volcanno_theme_config;

        if ( !isset( $volcanno_theme_config['include']['metabox'] ) || $volcanno_theme_config['include']['metabox'] == '1' ) {

            // filter config files directory
            $volcanno_config_files_dir = apply_filters( 'volcanno_config_files_dir', '' );

            // check if value is filtered
            if ( !empty( $volcanno_config_files_dir ) ) {
                $file_url = trailingslashit( $volcanno_config_files_dir ) . 'post-metabox.php';

                if ( file_exists( $file_url ) ) {
                    require_once $file_url;
                }
            }
            // Load from Child theme is file is available
            else if ( file_exists( VOLCANNO_STYLESHEET_DIR . '/includes/post-metabox.php' ) ) {

                require_once VOLCANNO_STYLESHEET_DIR . '/includes/post-metabox.php';
            }
            // Load from Parent theme is file is available
            else if ( file_exists( VOLCANNO_THEME_DIR . '/includes/post-metabox.php' ) ) {

                require_once VOLCANNO_THEME_DIR . '/includes/post-metabox.php';
            }
        }
    }

    /**
     * Enqueue script that controls Tabs visibility
     */
    static function enqueue_scripts() {

        $tabs = static::$tabs;

        $screen = get_current_screen();

        if ( $screen->base == 'post' ) {
            wp_enqueue_script( 'volcanno-rwmb-custom-scripts', VOLCANNO_TEMPLATEURL . '/core/components/meta-box/js/rwmb-custom.js', array( 'jquery' ), '1.0', true );
        }

        if ( !empty( $tabs ) && array_filter( $tabs ) ) {
            // output config object
            wp_localize_script( 'volcanno-rwmb-custom-scripts', 'VolcannoRwmbConfig', array(
                'tabs' => $tabs
            ) );
        }
    }

}

Volcanno_Core_Meta_Box_Loader::init();
