<?php
/* -----------------------------------------------------------------------------------

  Plugin Name: Volcanno Icon Font Parser
  Plugin URI: http://www.pixel-industry.com
  Description: A plugin that enables parsing icons fonts.
  Version: 1.0
  Author: Pixel Industry
  Author URI: http://www.pixel-industry.com

  ----------------------------------------------------------------------------------- */

/**
 * Class that enables parsing icons fonts
 */
class Volcanno_Icon_Font_Parser {

    /**
     * URL to plugin directory
     * 
     * @var string
     */
    static $plugin_dir_url;

    /**
     * Holds path to plugin directory
     * 
     * @var string
     */
    static $plugin_dir_path;

    /**
     * Array with all available icon fonts
     * 
     * @var array
     */
    static $icon_fonts;

    /**
     * URL to parser admin page
     * 
     * @var string
     */
    static $admin_page_url;

    /**
     * Error messages for PHP upload field
     * 
     * @var array 
     */
    static $php_file_upload_errors = array(
        0 => 'There is no error, the file uploaded with success.',
        1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
        2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.',
        3 => 'The uploaded file was only partially uploaded.',
        4 => 'No file was uploaded.',
        6 => 'Missing a temporary folder.',
        7 => 'Failed to write file to disk.',
        8 => 'A PHP extension stopped the file upload.',
    );

    /**
     * Initialize plugin
     */
    static function init() {

        // Register menu for fetching Icon Fonts
        add_action( 'admin_menu', 'Volcanno_Icon_Font_Parser::add_admin_menu' );

        // enqueue stylesheets
        add_action( 'admin_enqueue_scripts', 'Volcanno_Icon_Font_Parser::enqueue_stylesheet' );

        // setup default values
        add_action( 'after_setup_theme', 'Volcanno_Icon_Font_Parser::setup_fonts_list' );

        // setup plugin dir url
        static::$plugin_dir_url = trailingslashit( plugin_dir_url( __FILE__ ) );

        // setup plugin dir path
        static::$plugin_dir_path = trailingslashit( plugin_dir_path( __FILE__ ) );

        // admin page url
        static::$admin_page_url = esc_url( admin_url( 'admin.php?page=vifp_icon_font_parser' ) );
    }

    /**
     * Setup array with all available icons fonts
     */
    static function setup_fonts_list() {

        static::$icon_fonts = array(
            'font_awesome' => false,
            'linecons' => false
        );

        static::$icon_fonts = apply_filters( 'vifp_enabled_icon_fonts', static::$icon_fonts );
    }

    /**
     * Register admin menu
     */
    static function add_admin_menu() {

        // add menu for Icons fonts
        add_menu_page( 'Icon Font Parser', 'Icon Font Parser', 'manage_options', 'vifp_icon_font_parser', 'Volcanno_Icon_Font_Parser::render_icon_fonts_page' );
    }

    /**
     * Add stylesheet to rendered page
     * 
     */
    static function enqueue_stylesheet( $hook ) {
        if ( self::ends_with( $hook, '_page_vifp_icon_font_parser' ) ) {
            wp_enqueue_style( 'vifp-css', static::$plugin_dir_url . 'css/parser.css', array(), '1.0', 'screen' );
        }
    }

    /**
     * Render page for parsing icons fonts
     *
     * @return string
     */
    static function render_icon_fonts_page() {

        $fonts_html_list = '';

        echo '<div class="volcanno-icon-font-parser">
                <div class="vifp-title-container"><h2>' . esc_html__( 'Available Icon Fonts', 'volcanno-icon-font-parser' ) . '</h2>';

        echo "<p>" . esc_html__( "This plugin allows updating icon fonts that are stored in database by parsing font file.", 'volcanno-icon-font-parser' ) . "</p></div>";

        // check if user clicked on button otherwise show form
        if ( !isset( $_POST['save'] ) ) {

            foreach ( static::$icon_fonts as $name => $enabled ) {

                // font is enabled
                if ( $enabled ) {

                    // cleanup font name
                    $name_clean = str_replace( "_", " ", $name );
                    $name_clean = ucwords( $name_clean );

                    // build list of enabled fonts
                    $fonts_html_list .= "<li>";
                    $fonts_html_list .= "<img src='" . static::$plugin_dir_url . "img/font-awesome-logo.svg' class='vifp-icon' alt='Font Awesome Icon'/>";
                    $fonts_html_list .= "<h3>" . $name_clean . "</h3>";

                    // display what file should be uploaded
                    if ( $name == 'font_awesome' ) {
                        $fonts_html_list .= "<p>File to upload: <strong>fontawesome.css</strong></p>";
                    } else if ( $name == 'linecons' ) {
                        $fonts_html_list .= "<p>File to upload: <strong>linecons.css</strong></p>";
                    }

                    $fonts_html_list .= "</li>";
                }
            }

            echo "<ul class='vifp-enabled-fonts'>{$fonts_html_list}</ul>";
            ?>
            <div class="vifp-upload-section">
                <form action="" method="post" enctype="multipart/form-data">
                    <?php wp_nonce_field( 'vifp-icon-fonts' ); ?>    

                    <h3><?php esc_html_e( 'Upload font file', 'volcanno-icon-font-parser' ) ?></h3>

                    <select name="font_name">

                        <option value=""><?php esc_html_e( 'Select font to upload', 'volcanno-icon-font-parser' ) ?></option>

                        <?php
                        foreach ( static::$icon_fonts as $name => $enabled ) {

                            // cleanup font name
                            $name_clean = str_replace( "_", " ", $name );
                            $name_clean = ucwords( $name_clean );

                            // echo font names that are enabled
                            if ( $enabled ) {
                                ?>                    
                                <option value="<?php echo $name ?>"><?php echo $name_clean ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>

                    <input type="file" name="font"/><br/><br/>

                    <input type="checkbox" id="vifp-output" value="0" name="output_data"/><label for="vifp-output"><?php esc_html_e( 'Output result instead of saving', 'volcanno-icon-font-parser' ) ?></label><br/><br/>

                    <input name="save" type="submit" class="button button-primary" value="Upload" />
                </form>
            </div>
            <?php
        } else {
            // verify referer
            check_admin_referer( 'vifp-icon-fonts' );

            Volcanno_Icon_Font_Parser::prepare_parser();

            echo "<a class='vifp-return' href='" . static::$admin_page_url . "'>" . esc_html__( 'Return', 'volcanno-icon-font-parser' ) . "</a>";
        }

        echo '</div>';
    }

    /**
     * Prepare
     * 
     * @return void
     */
    static function prepare_parser() {

        // verify that file is uploaded
        if ( (isset( $_FILES['font'] ) && $_FILES['font']['error'] == 0 ) ) {

            // verify that desired font is selected
            if ( isset( $_POST['font_name'] ) && !empty( $_POST['font_name'] ) ) {

                // setup variables
                $name = $_FILES['font']['name'];
                $ext = explode( '.', $name );
                $ext_clean = strtolower( array_pop( $ext ) );
                $tmp_file_name = $_FILES['font']['tmp_name'];
                $font_name = isset( $_POST['font_name'] ) ? $_POST['font_name'] : false;

                // verify that user uploaded css file
                if ( $ext_clean == 'css' ) {

                    // check if file exists
                    if ( !empty( $tmp_file_name ) ) {

                        // check if file path is set
                        if ( file_exists( $tmp_file_name ) ) {

                            // fetch font file content
                            $css = file_get_contents( $tmp_file_name );

                            if ( !empty( $css ) ) {
                                // get correct class name based on font key
                                $font_class_name = str_replace( "_", " ", $font_name );
                                $font_class_name = ucwords( $font_class_name );
                                $font_class_name = str_replace( " ", "_", $font_class_name );

                                // get class and method name
                                $font_class_name = 'VIFP_' . $font_class_name;
                                $method_name = "parse_css";

                                // get path of icon font parser file
                                $file_path = static::$plugin_dir_path . 'parsers/' . $font_name . '.php';

                                // check that file exists
                                if ( file_exists( $file_path ) ) {
                                    include_once $file_path;

                                    // call parse method if it exists
                                    if ( method_exists( $font_class_name, $method_name ) ) {
                                        $css_parsed = call_user_func_array( array( $font_class_name, $method_name ), array( $css ) );

                                        if ( isset( $_POST['output_data'] ) ) {
                                            Volcanno_Icon_Font_Parser::output_data( $css_parsed );
                                        } else {
                                            $option_name = 'volcanno_' . $font_name . '_list';

                                            update_option( $option_name, $css_parsed );
                                        }
                                    }
                                }
                            } else {
                                echo "<strong>" . esc_html__( 'Reading file unsuccessful!', 'volcanno-icon-font-parser' ) . "</strong>";
                            }
                        }
                    }
                } else {
                    echo "<strong>" . esc_html__( 'Only CSS files are allowed!', 'volcanno-icon-font-parser' ) . "</strong>";
                }
            } else {
                echo "<strong>" . esc_html__( 'Please select font you want to update.', 'volcanno-icon-font-parser' ) . "</strong>";
            }
        } else {

            // get PHP error code
            $error_code = $_FILES['font']['error'];
            echo "<strong>" . static::$php_file_upload_errors[$error_code] . "</strong>";
        }
    }

    /**
     * Output array to screen
     * 
     * @param string $css_parsed
     */
    static function output_data( $css_parsed ) {
        ?>
        <textarea class="vifp-output-field"><?php var_export( $css_parsed ) ?></textarea>
        <?php
    }

    /**
     * Helper function that verifies if string ends with keyword
     * 
     * @param string $haystack
     * @param string $needle
     * @return string
     */
    static function ends_with( $haystack, $needle ) {

        // search forward starting from end minus needle length characters
        return $needle === "" || (($temp = strlen( $haystack ) - strlen( $needle )) >= 0 && strpos( $haystack, $needle, $temp ) !== FALSE);
    }

}

if ( is_admin() ) {
    Volcanno_Icon_Font_Parser::init();
}
