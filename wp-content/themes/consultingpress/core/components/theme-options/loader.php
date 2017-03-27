<?php

/**
* Theme options 
*/
class Volcanno_Theme_Options {
    
    static function init() {
        //Disable News Blast
        $GLOBALS['redux_notice_check'] = false;
        //Disable Update Notice
        $GLOBALS['redux_update_check'] = false;

        // Remove Demo mode in Redux Framework
        add_action( 'init', 'Volcanno_Theme_Options::redux_remove_demo_mode' );
        // Remove redux menu under the tools
        add_action( 'admin_menu', 'Volcanno_Theme_Options::remove_redux_menu', 12 );
        // Remove Redux Framework Dashboard widget
        add_action( 'wp_dashboard_setup', 'Volcanno_Theme_Options::remove_dashboard_widget', 12 );
        // Add customizer support for custom redux fields
        add_action( 'redux/extension/customizer/control/includes', 'Volcanno_Theme_Options::customizer_support' );

        //Include Custom field Select Text
        add_filter( "redux/volcanno_options/field/class/select_text", "Volcanno_Theme_Options::redux_select_text_field" );
        // Include custom field Select Label
        add_filter( "redux/volcanno_options/field/class/select_label", "Volcanno_Theme_Options::redux_select_label_field" );
        // Include modified field Select Image
        add_filter( "redux/volcanno_options/field/class/select_image", "Volcanno_Theme_Options::redux_select_image_field" );

        self::theme_option_configuration();

    }

    /**
     * Add customizer support for custom redux fields
     * @return void
     */
    static function customizer_support() {
        require_once( VOLCANNO_THEME_DIR . '/core/components/theme-options/custom-fields/customizer_fields.php' );
    }

    /**
     * Remove Demo mode in Redux Framework
     * 
     * @return void
     */
    static function redux_remove_demo_mode() { // Be sure to rename this function to something more unique
        // remove notices
        if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
            remove_filter( 'plugin_row_meta', array( ReduxFrameworkPlugin::get_instance(), 'plugin_meta_demo_mode_link' ), null, 2 );
            remove_filter( 'plugin_row_meta', array( ReduxFrameworkPlugin::get_instance(), 'plugin_metalinks'), null, 2 );
            remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
        }

        // remove text on ThemeCheck plugin
        if ( class_exists( 'ReduxFramework' ) ) {
            remove_action( 'admin_enqueue_scripts', array( Redux_ThemeCheck::get_instance(), 'enqueue_admin_scripts' ) );
        }

        // Remove tracking
        if ( class_exists( 'Redux_Tracking' ) ) {
            // Remove the tracking request notice.
            remove_action( 'admin_enqueue_scripts', array( Redux_Tracking::get_instance(), '_enqueue_tracking' ) );
            // Remove the newsletter tracking nag.
            remove_action( 'admin_enqueue_scripts', array( Redux_Tracking::get_instance(), '_enqueue_newsletter' ) );
        }
    }

    /**
     * Remove redux menu under the tools
     *
     * @return void
     */
    static function remove_redux_menu() {
        remove_submenu_page( 'tools.php', 'redux-about' );
    }

    /**
     * Remove Redux Framework Dashboard widget
     *
     * @return void
     */
    static function remove_dashboard_widget() {
        remove_meta_box( 'redux_dashboard_widget', 'dashboard', 'side' );
    }


    /**
     * Include Custom field Select Text
     * 
     * @param  $field 
     * @return string       
     */
    static function redux_select_text_field( $field ) {
        return VOLCANNO_THEME_DIR . '/core/components/theme-options/custom-fields/select_text/field_select_text.php';
    }

    /**
     * Include custom field Select Label
     * 
     * @param  $field
     * @return string        
     */
    static function redux_select_label_field( $field ) {
        return VOLCANNO_THEME_DIR . '/core/components/theme-options/custom-fields/select_label/field_select_label.php';
    }

    /**
     * Include modified field Select Image
     * @param  $field 
     * @return string
     */
    static function redux_select_image_field( $field ) {
        return VOLCANNO_THEME_DIR . '/core/components/theme-options/custom-fields/select_image/field_select_image.php';
    }

    /**
     * Theme option configuration
     * @return void 
     */
    static function theme_option_configuration() {
        if ( !isset( $redux_demo ) ) {

            // filter config files directory
            $volcanno_config_files_dir = apply_filters( 'volcanno_config_files_dir', '' );

            // check if value is filtered
            if ( !empty( $volcanno_config_files_dir ) ) {
                $file_url = trailingslashit( $volcanno_config_files_dir ) . 'theme-options.php';

                if ( file_exists( $file_url ) ) {
                    require_once $file_url;
                }
            }
            // Load from Child theme is file is available
            else if ( file_exists( VOLCANNO_STYLESHEET_DIR . '/includes/configuration/theme-options.php' ) ) {
                require_once VOLCANNO_STYLESHEET_DIR . '/includes/configuration/theme-options.php';
            }
            // Load from Parent theme is file is available
            else if ( file_exists( VOLCANNO_THEME_DIR . '/includes/configuration/theme-options.php' ) ) {
                require_once VOLCANNO_THEME_DIR . '/includes/configuration/theme-options.php';
            }
        }
    }
}

Volcanno_Theme_Options::init();