<?php

/**
 * Manage custom css files
 */
class Volcanno_Core_Custom_Styles {

    private static $custom_uploads_dir_path;
    private static $file_url;
    private static $chmod_file;
    private static $chmod_dir;
    private static $custom_css_file_name = 'custom.css';
    private static $custom_color_file_name = 'color.css';
    private static $custom_color_file_path;
    private static $custom_css_file_path;
    private static $file_system_error = array();
    private static $processing;
    private static $custom_color_fields_processed;
    private static $all_custom_color_values = array();

    static function init() {

        // Hooks
        add_filter( 'redux/options/volcanno_options/compiler', 'Volcanno_Core_Custom_Styles::redux_css_compiler', 2, 3 );
        // Load custom color css file
        add_filter( 'volcanno_css_files', 'Volcanno_Core_Custom_Styles::load_custom_color_css' );
        // Load custom css file
        add_filter( 'volcanno_css_files', 'Volcanno_Core_Custom_Styles::load_custom_css' );

        static::file_location();
    }

    /**
     * Set file path and url in local variables
     * 
     * @return void 
     */
    static function file_location() {

        // sanitize theme name for name of the directory
        $dir_name = sanitize_file_name( sanitize_title( VOLCANNO_THEME_NAME ) );

        // get uploads dir
        $upload = wp_upload_dir();

        // uploads directory
        $uploads_dir = $upload['basedir'];
        $uploads_dir = trailingslashit( $uploads_dir ) . trailingslashit( $dir_name );
        static::$custom_uploads_dir_path = $uploads_dir;

        // uploads url
        $uploads_url = $upload['baseurl'];
        $uploads_url = trailingslashit( $uploads_url ) . trailingslashit( $dir_name );
        static::$file_url = $uploads_url;
    }

    /**
     * Callback for Redux compiler hook that stores custom.css file to 
     * uploads directory. Script will also create sub-directory with name 
     * of the theme.
     * 
     * @global type $wp_filesystem
     * @param array $options
     * @param string $css
     * @param array $changed_values
     * @return void
     */
    static function redux_css_compiler( $options, $css, $changed_values ) {

        // loop through all values that has changed
        foreach ( $changed_values as $key => $value ) {
            if ( $key == 'custom_css' || Volcanno_Helper::starts_with( $key, 'custom_color_style' ) ) {
                self::$processing = true;
            }
        }

        // process further only when "custom_css" value or "custom_color_style" values are not changed
        if ( !self::$processing ) {
            return;
        }

        // create directory structure before compiling CSS files
        Volcanno_Core_Custom_Styles::create_directory_structure();


        // loop through all values that has changed
        foreach ( $changed_values as $field => $value ) {

            // check if field we watch has changed
            if ( $field == 'custom_css' ) {
                self::$custom_css_file_path = self::$custom_uploads_dir_path . self::$custom_css_file_name;

                // get new value of custom_css field
                $css = Volcanno::return_theme_option( 'custom_css' );

                // save file to custom dir in uploads directory
                Volcanno_Core_Custom_Styles::save_css_file( self::$custom_css_file_path, $css );

                // check if custom color style is changed where five colors 
                // are allowed to be defined in theme options  
            } else if ( Volcanno_Helper::starts_with( $field, 'custom_color_style' ) ) {

                if ( $custom_color_fields_processed ) {
                    continue;
                }

                self::$custom_color_file_path = self::$custom_uploads_dir_path . self::$custom_color_file_name;

                // get color style from theme options
                $color_style = Volcanno::return_theme_option( 'color_style' );

                // if saved field is one of custom color fields, save the file

                if ( Volcanno_Helper::starts_with( $field, 'custom_color_style' ) ) {
                    Volcanno_Core_Custom_Styles::get_all_custom_color_values();

                    self::$custom_color_fields_processed = true;
                }

                /**
                 * Check if custom color is set and store it to database
                 * in case custom color is not set, remove database record and file from disk
                 */
                if ( $color_style == 'custom' && !empty( self::$all_custom_color_values ) ) {

                    $css = self::get_color_style_css( self::$all_custom_color_values );

                    update_option( 'volcanno_color_style_compiled_css', $css );
                } else {
                    Volcanno_Core_Custom_Styles::clean_custom_color_style_records();

                    continue;
                }

                // save file to custom dir in uploads directory
                Volcanno_Core_Custom_Styles::save_css_file( self::$custom_color_file_path, $css );
            } else {
                continue;
            }

            // if errors occured, store that in database
            Volcanno_Core_Custom_Styles::update_filesystem_errors();
        }
    }

    /**
     * Gets all custom color value from theme options and stores to private variable.
     * 
     * Default value has name: "custom_color_style"
     * 
     * Every next value must has suffix with incremental value e.g.
     * 
     * custom_color_style_2
     * custom_color_style_3
     * 
     */
    static function get_all_custom_color_values() {

        $color = Volcanno::return_theme_option( 'custom_color_style' );

        self::$all_custom_color_values['custom_color_style'] = $color;

        // 10 custom color styles are allowed
        for ( $i = 2; $i < 10; $i++ ) {

            $next_style = 'custom_color_style_' . $i;
            $color = Volcanno::return_theme_option( $next_style );

            if ( !empty( $color ) ) {
                self::$all_custom_color_values[$next_style] = $color;
            }
        }
    }

    /**
     * Save CSS file to custom directory in uploads dir
     * 
     * @global array $wp_filesystem
     * @param string $filename
     * @param string $css
     */
    static function save_css_file( $filename, $css ) {

        global $wp_filesystem;

        // try to save CSS file to uploads directory
        if ( $wp_filesystem && !isset( self::$file_system_error['dir'] ) ) {

            // create file
            $res = $wp_filesystem->put_contents(
                    $filename, $css, self::$chmod_file // predefined mode settings for WP files
            );

            // verify that file is successfully created
            if ( !$res ) {
                self::$file_system_error[$field] = '1';
            } else {
                self::$file_system_error[$field] = '0';
            }
        } else {
            self::$file_system_error['filesystem'] = '1';
        }
    }

    /**
     * Create directory in uploads dir with the name of theme where css files
     * will be stored.
     * 
     * @global array $wp_filesystem
     */
    static function create_directory_structure() {

        global $wp_filesystem;

        // prepare filesystem
        Volcanno_Core_Custom_Styles::prepare_filesystem();

        // try to create new directory with the name of theme in uploads dir
        if ( $wp_filesystem ) {

            if ( !is_dir( self::$custom_uploads_dir_path ) ) {

                // try to create dir using Filesystem API
                $res = $wp_filesystem->mkdir( self::$custom_uploads_dir_path );

                // if Filesystem API fails, try using WP function
                if ( !$res ) {
                    $res = wp_mkdir_p( self::$custom_uploads_dir_path );

                    // if WP function fails, try with PHP function
                    if ( !$res ) {

                        // set error flags
                        self::$file_system_error['dir'] = '1';
                    } else {
                        self::$file_system_error['dir'] = '0';
                    }
                }
            }
        }
    }

    /**
     * Filter array with CSS files that will be loaded and insert custom.css file
     * 
     * @param array $css
     * @return array
     */
    static function load_custom_css( $css ) {

        global $volcanno_theme_config;

        // load custom CSS file
        $file_exists = static::css_file_exists( 'css' );

        /*
         * Load CSS in <head>
         * 
         * CONDITIONS:
         * File doesn't exists AND value is set in theme options - saving file failed probably due to bad permission configuration
         * OR
         * File exists AND filesystem error is set - file was already saved before but additional saving failed
         */

        $custom_css = Volcanno::return_theme_option( 'custom_css' );

        if ( ( empty( $file_exists ) && !empty( $custom_css )) || (!empty( $file_exists ) && Volcanno_Core_Custom_Styles::is_filesystem_error( 'custom_css' )) ) {

            add_action( 'wp_enqueue_scripts', 'Volcanno_Core_Custom_Styles::add_custom_css_inline_css', 16 );

            // if file exists, inject file url to array
        } else if ( !empty( $file_exists ) && !empty( $custom_css ) ) {
            $filename = self::$file_url . self::$custom_css_file_name;

            $css['volcanno-custom-css'] = array( $filename, 'volcanno-style' );
        }

        return $css;
    }

    /**
     * Filter array with CSS files that will be loaded and insert color.css file
     * 
     * @param array $css
     * @return array
     */
    static function load_custom_color_css( $css ) {

        global $volcanno_theme_config;

        // load custom CSS file
        $file_exists = static::css_file_exists( 'color' );

        // get value of color style field
        $color_style = Volcanno::return_theme_option( 'color_style' );

        // get values of custom colorand compiled CSS
        $custom_color_style = Volcanno::return_theme_option( 'custom_color_style' );
        $compiled_css = get_option( 'volcanno_color_style_compiled_css' );

        if ( ($color_style == 'custom' && empty( $file_exists ) && !empty( $custom_color_style ) && !empty( $compiled_css )) ||
                ($color_style == 'custom' && !empty( $file_exists ) && Volcanno_Core_Custom_Styles::is_filesystem_error( 'custom_color_style' )) ) {
            /*
             * Load CSS in <head>
             * 
             * CONDITIONS:
             * 
             * color style is set to "custom" or not set at all 
             * AND 
             * file with compiled CSS doesn't exists ( saving file failed probably due to bad permission configuration )
             * AND 
             * value is set in theme options 
             * AND
             * compiled CSS is stored in database options
             * 
             * OR
             * 
             * color style is set to "custom" or not set at all 
             * AND
             * file with compiled CSS exists
             * AND 
             * filesystem error is set - file was already saved before but additional saving failed
             */

            add_action( 'wp_enqueue_scripts', 'Volcanno_Core_Custom_Styles::add_custom_color_inline_css', 15 );
        } else if ( $color_style == 'custom' && !empty( $file_exists ) ) {

            /**
             * Loads CSS file from disk when conditions are met
             * 
             * CONDITIONS:
             * 
             * Color style is set to "custom" and compiled CSS exists on disk
             */
            $filename = self::$file_url . self::$custom_color_file_name;

            // inject url to file
            $css['volcanno-color'] = array( $filename, 'volcanno-style' );
        } else if ( $color_style == 'predefined' ) {

            /**
             * Loads predefined CSS style when user selected to load predefined color
             * 
             * CONDITIONS:
             * 
             * Color style is set to "predefined"
             */
            $predefined_color_style = Volcanno::return_theme_option( 'predefined_color_style' );

            // if predefine value is empty, load the file
            if ( !empty( $predefined_color_style ) ) {
                $filename = trailingslashit( VOLCANNO_TEMPLATEURL ) . 'css/' . $predefined_color_style . '.css';

                // inject url to file
                $css['volcanno-color'] = array( $filename, 'volcanno-style' );

                // otherwise try getting default value from redux or load default file
            } else {

                // check that Redux framework is installed and try to get predefined color value
                if ( class_exists( "ReduxFrameworkInstances" ) ) {

                    // get Redux instance and default color
                    $redux = ReduxFrameworkInstances::get_instance( 'volcanno_options' );
                    $predefined_color = $redux->get_default_value( 'predefined_color_style' );
                }

                // if predefined color is not set, load default css file
                if ( empty( $predefined_color ) ) {
                    $css = static::load_default_color_file( $css );
                }
            }
        } else {
            $css = static::load_default_color_file( $css );
        }

        return $css;
    }

    /**
     * Adds default color CSS file to array of CSS files that will be loaded
     * 
     * @global array $volcanno_theme_config
     * @param array $css
     * @return array
     */
    static function load_default_color_file( $css ) {

        global $volcanno_theme_config;

        // get default color value from theme configuration array
        $default_color = isset( $volcanno_theme_config['default_color'] ) ? $volcanno_theme_config['default_color'] : false;

        // load default color css file if value is set
        if ( !empty( $default_color ) ) {

            $filename = trailingslashit( VOLCANNO_TEMPLATEURL ) . 'css/' . $default_color . '.css';

            // inject url to file
            $css['volcanno-color'] = array( $filename, 'volcanno-style' );
        }

        return $css;
    }

    /**
     * Load CSS for custom color to <head> instead of loading a file
     */
    static function add_custom_color_inline_css() {

        $custom_color = get_option( 'volcanno_color_style_compiled_css' );

        if ( !empty( $custom_color ) ) {
            wp_add_inline_style( 'volcanno-style', $custom_color );
        }
    }

    /**
     * Load CSS for custom CSS to <head> instead of loading a file
     * when unable to write to filesystem
     */
    static function add_custom_css_inline_css() {

        // get CSS from Theme Options
        $custom_css = Volcanno::return_theme_option( 'custom_css' );

        if ( !empty( $custom_css ) ) {
            wp_add_inline_style( 'volcanno-style', $custom_css );
        }
    }

    /**
     * Verify if custom.css file exists in uploads directory
     * 
     * @return bool
     */
    static function css_file_exists( $type ) {

        if ( $type == 'css' ) {
            $filename = self::$custom_uploads_dir_path . self::$custom_css_file_name;
        } else if ( $type == 'color' ) {
            $filename = self::$custom_uploads_dir_path . self::$custom_color_file_name;
        }

        return file_exists( $filename );
    }

    /**
     * Get compiled custom color CSS
     * 
     * @param string $custom_color
     * @return string
     */
    static function get_color_style_css( $color_styles ) {

        include VOLCANNO_THEME_DIR . '/includes/configuration/color-style.php';

        return Volcanno_Color_Style::get_compiled_css( $color_styles );
    }

    /**
     * Prepare Filesystem API
     * 
     * @global type $wp_filesystem
     */
    static function prepare_filesystem() {

        global $wp_filesystem;

        // check if we have access to filesystem
        if ( empty( $wp_filesystem ) ) {
            require_once( ABSPATH . '/wp-admin/includes/file.php' );
            WP_Filesystem();
        }

        // file permission
        if ( defined( 'FS_CHMOD_FILE' ) ) {
            self::$chmod_file = FS_CHMOD_FILE;
        } else {
            self::$chmod_file = 0644;
        }

        if ( defined( 'FS_CHMOD_DIR' ) ) {
            self::$chmod_dir = FS_CHMOD_DIR;
        } else {
            self::$chmod_dir = 0755;
        }
    }

    /**
     * In case of errors during the process of creating directories or saving files
     * save array with error logs in database for future use.
     * 
     * We will use this array to determin if local CSS file should be loaded or
     * CSS should be rendered in <head>.
     * 
     * In case when file couldn't be written to disk, CSS will be loaded to <head>
     */
    static function update_filesystem_errors() {

        // get errors array from database
        $file_system_errors = get_option( 'volcanno_color_style_filesystem_error' );

        // update errors status and store to database again
        if ( !empty( $file_system_errors ) ) {
            $merged = array_merge( $file_system_errors, self::$file_system_error );
            update_option( 'volcanno_color_style_filesystem_error', $merged );
        } else {
            update_option( 'volcanno_color_style_filesystem_error', self::$file_system_error );
        }
    }

    /**
     * Verify if errors for specific taks occured.
     * For example we can verify if error appeared while creating directory on disk.
     * Available values: dir, filesystem, custom_css, custom_color_style
     * 
     * @param type $type Type of error to verify
     * @return boolean Error appeared or not
     */
    static function is_filesystem_error( $type ) {
        $filesystem_error = get_option( 'volcanno_color_style_filesystem_error' );

        if ( !empty( $type ) ) {
            if ( isset( $filesystem_error[$type] ) && $filesystem_error[$type] == '1' ) {
                return true;
            } else {
                return false;
            }
        }

        return false;
    }

    /**
     * Remove custom color records from database and remove file 
     * from uploads directory
     * 
     * @global type $wp_filesystem
     * @param type $filename Path to the file to delete
     */
    static function clean_custom_color_style_records() {
        global $wp_filesystem;

        // delete color style from database
        delete_option( 'volcanno_color_style_compiled_css' );

        if ( !empty( self::$custom_color_file_path ) ) {
            // remove file from disk
            $res = $wp_filesystem->delete( self::$custom_color_file_path );

            // verify that file is successfully removed and raise an error if not
            if ( !$res ) {
                self::$file_system_error['custom_color_style'] = '1';
            }
        }
    }

}

Volcanno_Core_Custom_Styles::init();