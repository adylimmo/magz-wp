<?php
/* -----------------------------------------------------------------------------------

  Plugin Name: Volcanno One Click Installer
  Plugin URI: http://www.pixel-industry.com
  Description: A plugin that enables importing demo content to themes developed by Pixel Industry.
  Version: 1.1
  Author: Pixel Industry
  Author URI: http://www.pixel-industry.com

  ----------------------------------------------------------------------------------- */

/**
 * Class Volcanno_One_Click_Installer
 *
 * This class provides the capability to import demo content as well as import widgets and WordPress menus
 *
 * @category VolcannoFramework
 * @author   Pixel Industry
 * @link     http://pixel-industry.com
 *
 */
class Volcanno_One_Click_Installer {

    /**
     * Holds URL of plugin directory
     * 
     * @var string
     */
    public $plugin_dir_url;

    /**
     * Holds path to plugin directory
     * 
     * @var string
     */
    public $plugin_dir_path;

    /**
     * Holds path to directory with demo files
     * 
     * @var string
     */
    public $demo_files_path;

    /**
     * Holds a copy of the object for easy reference.
     *
     * @since 2.2.0
     *
     * @var object
     */
    public $demo_files;

    /**
     * List of allowed HTML tags that is used in wp_kses function 
     * to clean up the inputs
     * 
     * @var array
     */
    public $allowed_html_tags = array(
        'a' => array(
            'href' => array(),
            'title' => array(),
            'class' => array(),
            'target' => array()
        ),
        'br' => array(),
        'em' => array(),
        'strong' => array(),
        'span' => array( 'class' => array() )
    );

    /**
     * Holds configuration array
     * 
     * @var array
     */
    public $options;

    /**
     * Holds widget import results
     * 
     * @var array 
     */
    public $widget_import_results;

    /**
     * Redux (theme options) option name
     * 
     * @var string
     */
    public $theme_option_name;

    /**
     * ID of content that's being uploaded
     * 
     * @var string
     */
    public $content_id;

    /**
     * Current layout
     * 
     * @var string
     */
    public $layout;

    /**
     * Array with posts that will be uploaded
     * 
     * @var array
     */
    public $posts_to_upload;

    /**
     * Array with all parsed posts
     * 
     * @var array
     */
    public $all_posts;

    /**
     * Holds a copy of the object for easy reference.
     *
     * @since 2.2.0
     *
     * @var object
     */
    private static $instance;

    /**
     * Constructor. Hooks all interactions to initialize the class.
     *
     * @since 2.2.0
     */
    public function __construct() {

        self::$instance = $this;

        // setup plugin parameters
        add_action( 'after_setup_theme', array( $this, 'init' ) );

        // add submenu page
        add_action( 'admin_menu', array( $this, 'add_admin' ) );

        // enqueue stylesheets
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_stylesheet' ) );

        // enqueue script
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

        // ajax call
        add_action( 'wp_ajax_vlc_import_demo', array( $this, 'ajax_import_content' ) );
        add_action( 'wp_ajax_vlc_count_files', array( $this, 'ajax_count_files' ) );

        // set master slider path to demo sliders file
        add_filter( 'masterslider_exported_sliders_file_path', array( $this, 'set_master_slider_demo_path' ) );
    }

    /**
     * Initialize properties
     * 
     */
    public function init() {

        $this->options = apply_filters( 'voci_one_click_installer_options', array() );

        $this->demo_files_path = isset( $this->options['demo_files_path'] ) ? $this->options['demo_files_path'] : false;

        $this->demo_files = isset( $this->options['demo_files'] ) ? $this->options['demo_files'] : false;

        $this->theme_option_name = isset( $this->options['theme_options_name'] ) ? $this->options['theme_options_name'] : false;

        $this->plugin_dir_url = trailingslashit( plugin_dir_url( __FILE__ ) );

        $this->plugin_dir_path = trailingslashit( plugin_dir_path( __FILE__ ) );
    }

    /**
     * Add One Click Install submenu item
     *
     */
    public function add_admin() {

        add_menu_page( "One Click Installer", "One Click Installer", 'manage_options', 'volcanno_demo_importer', array( $this, 'output_installer_HTML' ) );
    }

    /**
     * Add stylesheet to admin page
     * 
     */
    public function enqueue_scripts( $hook ) {
        if ( $this->ends_with( $hook, '_page_volcanno_demo_importer' ) ) {
            wp_enqueue_script( 'volcanno_demo_importer', $this->plugin_dir_url . '/js/installer.js', array(), '1.0', 'screen' );
        }
    }

    /**
     * Add stylesheet to admin page
     * 
     */
    public function enqueue_stylesheet( $hook ) {
        if ( $this->ends_with( $hook, '_page_volcanno_demo_importer' ) ) {
            wp_enqueue_style( 'volcanno_demo_importer', $this->plugin_dir_url . 'css/installer.css', array(), '1.0', 'screen' );
        }
    }

    /**
     * Outputs installer page HTML
     *
     * @return void
     */
    public function output_installer_HTML() {

        $demo_files = $this->demo_files;
        ?>

        <div class="one-click-installer">
            <div class="demo-header">    
                <h2><?php esc_html_e( 'One Click Installer', 'volcanno-one-click-installer' ) ?></h2>
                <small><?php esc_html_e( 'Control Panel for installing Demo content.', 'volcanno-one-click-installer' ) ?></small>
            </div>

            <div class="demo-content">

                <?php
                if ( empty( $demo_files ) || empty( $this->demo_files_path ) ) {
                    ?>
                    <div class="warning"><p><?php
                            sprintf( '<strong>%1$s </strong>', esc_html( 'Error:', 'volcanno-one-click-installer' ) );
                            esc_html_e( 'Demo files or demo files path missing.', 'volcanno-one-click-installer' );
                            ?></p></div>
                    <?php
                    exit;
                }
                ?>

                <div class="info">
                    <p class="note"><?php esc_html_e( 'Importing demo content (post, pages, images, theme settings, ...) is the easiest way to setup your theme. It will allow you to quickly edit everything instead of creating content from scratch.', 'volcanno-one-click-installer' ) ?></p>

                    <p><?php esc_html_e( 'When you import the data following things will happen:', 'volcanno-one-click-installer' ) ?></p>

                    <ul>
                        <li><?php esc_html_e( 'No existing posts, pages, categories, images, custom post types or any other data will be deleted or modified.', 'volcanno-one-click-installer' ) ?></li>
                        <li><?php esc_html_e( 'If Pages / Menus are imported, Front page and Blog will be set in Settings.', 'volcanno-one-click-installer' ) ?></li>                            
                        <li><?php esc_html_e( 'Images will be downloaded from our server.', 'volcanno-one-click-installer' ) ?></li>
                        <li><?php esc_html_e( 'Importing can can take a couple of minutes.', 'volcanno-one-click-installer' ) ?></li>
                    </ul>
                </div>

                <div class="warning"><p><?php esc_html_e( 'Before you begin, make sure all the required plugins are activated.', 'volcanno-one-click-installer' ) ?> <a href="<?php echo esc_url( admin_url( 'admin.php?page=install-required-plugins' ) ); ?>"><?php esc_html_e( 'Check required plugins', 'volcanno-one-click-installer' ) ?></a></p></div>

                <div class="fields-container">

                    <table class="table-fields">

                        <?php if ( isset( $demo_files['layouts'] ) ) { ?>
                            <tr>
                                <td><h4><?php esc_html_e( 'Layout', 'volcanno-one-click-installer' ) ?></h4></td>
                                <td>
                                    <select id="layout">
                                        <?php
                                        foreach ( $demo_files['layouts'] as $key => $layout ) {
                                            $layout_name = isset( $layout['name'] ) ? $layout['name'] : 'Layout';
                                            ?>
                                            <option value="<?php echo esc_attr( $key ) ?>"><?php echo esc_html( $layout_name ) ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                        <?php } ?>
                        
                        <tr>
                            <td><h4><?php esc_html_e( 'Pages / Menus', 'volcanno-one-click-installer' ) ?></h4></td>
                            <td>
                                <input type="button" class="button-primary import-button" id="pages-menus" value="<?php esc_html_e( 'Import', 'volcanno-one-click-installer' ) ?>"/>
                                <div class="importing-finished">
                                    <i class="fa fa-check-circle"></i>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td><h4><?php esc_html_e( 'Posts', 'volcanno-one-click-installer' ) ?></h4></td>
                            <td>
                                <input type="button" class="button-primary import-button" id="posts" value="<?php esc_html_e( 'Import', 'volcanno-one-click-installer' ) ?>"/>
                                <div class="importing-finished">
                                    <i class="fa fa-check-circle"></i>
                                </div>
                            </td>
                        </tr>

                        <?php if ( isset( $demo_files['portfolio_gallery'] ) ): ?>
                            <tr>
                                <td><h4><?php esc_html_e( 'Portfolio / Gallery', 'volcanno-one-click-installer' ) ?></h4></td>
                                <td>
                                    <input type="button" class="button-primary import-button" id="portfolio-gallery" value="<?php esc_html_e( 'Import', 'volcanno-one-click-installer' ) ?>"/>
                                    <div class="importing-finished">
                                        <i class="fa fa-check-circle"></i>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php if ( isset( $demo_files['vehicles'] ) ): ?>
                            <tr>
                                <td><h4><?php esc_html_e( 'Vehicles', 'volcanno-one-click-installer' ) ?></h4></td>
                                <td>
                                    <input type="button" class="button-primary import-button" id="vehicles" value="<?php esc_html_e( 'Import', 'volcanno-one-click-installer' ) ?>"/>
                                    <div class="importing-finished">
                                        <i class="fa fa-check-circle"></i>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php
                        if ( isset( $demo_files['cpts'] ) ):
                            foreach ( $demo_files['cpts'] as $name => $cpt ) {
                                ?>
                                <tr>
                                    <td><h4><?php echo ucfirst( str_replace( '_', ' ', strip_tags( $name ) ) ) ?></h4></td>
                                    <td>
                                        <input type="button" class="button-primary import-button" id="cpt-<?php echo sanitize_html_class( $name ) ?>" value="<?php esc_html_e( 'Import', 'volcanno-one-click-installer' ) ?>"/>
                                        <div class="importing-finished">
                                            <i class="fa fa-check-circle"></i>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                        endif;
                        ?>

                        <tr>
                            <td><h4><?php esc_html_e( 'Contact', 'volcanno-one-click-installer' ) ?></h4></td>
                            <td>
                                <input type="button" class="button-primary import-button" id="contact" value="<?php esc_html_e( 'Import', 'volcanno-one-click-installer' ) ?>"/>
                                <div class="importing-finished">
                                    <i class="fa fa-check-circle"></i>
                                </div>
                            </td>
                        </tr>

                        <?php if ( isset( $demo_files['slider'] ) ): ?>
                            <tr>
                                <td><h4><?php esc_html_e( 'Slider', 'volcanno-one-click-installer' ) ?></h4></td>
                                <td>
                                    <input type="button" class="button-primary import-button" id="slider" value="<?php esc_html_e( 'Import', 'volcanno-one-click-installer' ) ?>"/>
                                    <div class="importing-finished">
                                        <i class="fa fa-check-circle"></i>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php if ( isset( $demo_files['newsletter_forms'] ) ): ?>
                            <tr>
                                <td><h4><?php esc_html_e( 'Newsletter Forms', 'volcanno-one-click-installer' ) ?></h4></td>
                                <td>
                                    <input type="button" class="button-primary import-button" id="newsletter-forms" value="<?php esc_html_e( 'Import', 'volcanno-one-click-installer' ) ?>"/>
                                    <div class="importing-finished">
                                        <i class="fa fa-check-circle"></i>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <tr>
                            <td><h4><?php esc_html_e( 'Theme options', 'volcanno-one-click-installer' ) ?></h4></td>
                            <td>
                                <input type="button" class="button-primary import-button" id="theme-options" value="<?php esc_html_e( 'Import', 'volcanno-one-click-installer' ) ?>"/>
                                <div class="importing-finished">
                                    <i class="fa fa-check-circle"></i>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td><h4><?php esc_html_e( 'Widgets', 'volcanno-one-click-installer' ) ?></h4></td>
                            <td>
                                <input type="button" class="button-primary import-button" id="widgets" value="<?php esc_html_e( 'Import', 'volcanno-one-click-installer' ) ?>"/>
                                <div class="importing-finished">
                                    <i class="fa fa-check-circle"></i>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td><h4><?php esc_html_e( 'Images (attachments)', 'volcanno-one-click-installer' ) ?></h4></td>
                            <td>
                                <input type="button" class="button-primary import-button" id="attachments" value="<?php esc_html_e( 'Import', 'volcanno-one-click-installer' ) ?>"/>
                                <div class="importing-finished">
                                    <i class="fa fa-check-circle"></i>
                                </div>
                            </td>
                        </tr>

                    </table>

                    <div id="circularG">
                        <div id="circularG_1" class="circularG"></div>
                        <div id="circularG_2" class="circularG"></div>
                        <div id="circularG_3" class="circularG"></div>
                        <div id="circularG_4" class="circularG"></div>
                        <div id="circularG_5" class="circularG"></div>
                        <div id="circularG_6" class="circularG"></div>
                        <div id="circularG_7" class="circularG"></div>
                        <div id="circularG_8" class="circularG"></div>
                    </div>                    

                    <div class="results-container">
                        <div class="voci-progress-container">
                            <div id="voci-progress-bar" class="voci-progressbar voci-green">
                                <div id="voci-progress-bar-text" class="voci-text"></div>
                            </div>
                        </div>
                        <div class="results-field" placeholder="<?php esc_html_e( 'Import log...', 'volcanno-one-click-installer' ) ?>"><div class="holder"></span></div>
                    </div>

                    <input type="hidden" name="demononce" id="demo-nonce" value="<?php echo wp_create_nonce( 'volcanno-demo-code' ); ?>" />

                </div>
            </div>


        </div>
        <?php
    }

    /**
     * Return path to demo file
     * 
     * @param string $content_id
     * @return string
     */
    public function return_demo_file_path( $content_id, $file_number = '' ) {

        // If is custom post type
        if ( $this->starts_with( $content_id, 'cpt_' ) ) {

            $demo_files = $this->demo_files;
            $content_id_clean = substr( $content_id, 4 );
            $file_object = !empty( $demo_files['cpts'][$content_id_clean] ) ? $demo_files['cpts'][$content_id_clean] : '' ;
            // Check if file is in root or in layout folder
            if ( !is_array( $file_object ) ) {
                $file_url = $demo_files['cpts'][$content_id_clean];
            } else {
                $file_url = $file_object['layouts'][$this->layout];
            }

        } else {
        
            if ( is_array( $this->demo_files[$content_id] ) ) {
                $file_url = isset( $this->demo_files[$content_id]['layouts'][$this->layout] ) ? $this->demo_files[$content_id]['layouts'][$this->layout] : false;
            } else {
                $file_url = $this->demo_files[$content_id];
            }

        }

        if ( !empty( $file_number ) && $file_number != 1 ) {
            $file_number = '_' . str_pad( $file_number, 2, "0", STR_PAD_LEFT ); 
            $file_url = preg_replace('/(\.xml)/', $file_number . '$1', $file_url );
        }

        return $file_url;
    }

    /**
     * Count how many files have for import
     * @return void
     */
    public function ajax_count_files() {

        $action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : '';
        $this->content_id = isset( $_REQUEST['content_id'] ) ? $_REQUEST['content_id'] : false;

        $this->layout = isset( $_REQUEST['layout'] ) ? $_REQUEST['layout'] : '';

        $import_type = preg_replace('/(-)/', '_', $this->content_id);

        $file_number = 1;

        if ( $action == 'vlc_count_files' && check_ajax_referer( 'volcanno-demo-code', 'security' ) && !in_array( $import_type, array( 'theme_options', 'widgets', 'contact', 'slider' ) ) ) {

            $file_url = $this->demo_files_path . $this->return_demo_file_path( $import_type, $file_number );

            if ( file_exists( $file_url ) ) {

                while ( file_exists( $file_url ) ) {
                    $file_number++;
                    $file_url = $this->demo_files_path . $this->return_demo_file_path( $import_type, $file_number );
                }

            }

            echo $file_number - 1;
            exit();
        } else {
            echo 1;
            exit();
        }

    }

    /**
     * Uploads content based on parameter from AJAX request.
     */
    public function ajax_import_content() {

        $action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : '';
        $this->content_id = isset( $_REQUEST['content_id'] ) ? $_REQUEST['content_id'] : false;

        $this->layout = isset( $_REQUEST['layout'] ) ? $_REQUEST['layout'] : '';

        $file_number = isset( $_REQUEST['file_number'] ) ? $_REQUEST['file_number'] : '';

        if ( $action == 'vlc_import_demo' && check_ajax_referer( 'volcanno-demo-code', 'security' ) ) {

            $demo_files = $this->demo_files;

            // UPLOAD PAGES AND MENUS
            if ( $this->content_id == 'pages-menus' ) {

                $file_url = $this->demo_files_path . $this->return_demo_file_path( 'pages_menus', $file_number );

                if ( file_exists( $file_url ) ) {
                    $this->set_demo_data( $file_url );
                    // set menu locations
                    $this->set_demo_menus();
                    $this->setup_settings();
                } else {
                    echo 'done';
                    exit();
                }

                // UPLOAD POSTS
            } else if ( $this->content_id == 'posts' ) {

                $file_url = $this->demo_files_path . $this->return_demo_file_path( 'posts', $file_number );

                if ( file_exists( $file_url ) ) {
                    $this->set_demo_data( $file_url );
                } else {
                    echo 'done';
                    exit();
                }

                // UPLOAD CONTACT FORMS
            } else if ( $this->content_id == 'contact' ) {

                if ( !VOLCANNO_CF7 ) {
                    echo wp_kses( __( '<strong>Please activate/install Contact Form 7 plugin before uploading demo content.</strong>', 'volcanno-one-click-installer' ), $this->allowed_html_tags );
                    exit();
                }

                $file_url = $this->demo_files_path . $this->return_demo_file_path( 'contact' );
                $this->set_demo_data( $file_url );
            }  // REVOLUTION SLIDER
            else if ( $this->content_id == 'slider' ) {

                if ( !defined( 'VOLCANNO_REVSLIDER' ) || (defined( 'VOLCANNO_REVSLIDER' ) && !VOLCANNO_REVSLIDER) ) {
                    echo wp_kses( __( '<strong>Please activate/install Revolution slider before uploading demo content.</strong>', 'volcanno-one-click-installer' ), $this->allowed_html_tags );
                    exit();
                }

                if ( is_array( $demo_files['slider'] ) ) {
                    foreach ( $demo_files['slider'] as $key => $value ) {
                        $file_url = $this->demo_files_path . $value;
                        $this->set_revslider_slides( $file_url );
                    }
                } else {
                    $file_url = $this->demo_files_path . $demo_files['slider'];
                    $this->set_revslider_slides( $file_url );
                }
            } // UPLOAD NEWSLETTER FORMS
            else if ( $this->content_id == 'newsletter-forms' ) {
                $this->set_newsletter_forms(); //import newsletter forms
                // UPLOAD THEME OPTIONS
            }// UPLOAD MASTER SLIDER
            else if ( $this->content_id == 'theme-options' ) {
                $this->set_demo_theme_options(); //import before widgets incase we need more sidebars
                // UPLOAD WIDGETS
            } else if ( $this->content_id == 'widgets' ) {
                $this->process_widget_import_file();
                $this->widget_import_log();

                // UPLOAD ATTACHMENTS
            } else if ( $this->content_id == 'attachments' ) {

                //$file_url = $this->demo_files_path . $demo_files['attachments'];
                $file_url = $this->demo_files_path . $this->return_demo_file_path( 'attachments', $file_number );

                if ( file_exists( $file_url ) ) {
                    $this->set_demo_data( $file_url );
                } else {
                    echo 'done';
                    exit();
                }
                // UPLOAD CUSTOM POST TYPE
            } else if ( $this->starts_with( $this->content_id, 'cpt-' ) ) {
                $content_id_clean = substr( $this->content_id, 4 );
                $file_object = !empty( $demo_files['cpts'][$content_id_clean] ) ? $demo_files['cpts'][$content_id_clean] : '' ;
                // Check if file is in root or in layout folder
                if ( !is_array( $file_object ) ) {
                    $file_url = $this->demo_files_path . $demo_files['cpts'][$content_id_clean];
                } else {
                    $file_url = $this->demo_files_path . $file_object['layouts'][$this->layout];
                }
                // Add file number if needed
                if ( !empty( $file_number ) && $file_number != 1 ) {
                    $file_number = '_' . str_pad( $file_number, 2, "0", STR_PAD_LEFT ); 
                    $file_url = preg_replace('/(\.xml)/', $file_number . '$1', $file_url );
                }

                if ( file_exists( $file_url ) ) {
                    $this->set_demo_data( $file_url );
                } else {
                    echo 'done';
                    exit();
                }
            }
        }
        exit();
    }

    /**
     * Return results of importing widgets
     */
    public function widget_import_log() {

        if ( !empty( $this->widget_import_results ) ) {
            ?>
            <table id="wie-import-results">

                <?php
                // Loop sidebars
                $results = $this->widget_import_results;

                foreach ( $results as $sidebar ) :
                    ?>
                    <tr class="wie-import-results-sidebar">
                        <td colspan="2" class="wie-import-results-sidebar-name">
                            <?php echo esc_html( $sidebar['name'] ); // sidebar name if theme supports it; otherwise ID               ?>
                        </td>
                        <td class="wie-import-results-sidebar-message wie-import-results-message wie-import-results-message-<?php echo sanitize_html_class( $sidebar['message_type'] ); ?>">
                            <?php echo esc_html( $sidebar['message'] ); // sidebar may not exist in theme               ?>
                        </td>
                    </tr>

                    <?php
                    // Loop widgets
                    foreach ( $sidebar['widgets'] as $widget ) :
                        ?>
                        <tr class="wie-import-results-widget">
                            <td class="wie-import-results-widget-name">
                                <?php echo esc_html( $widget['name'] ); // widget name or ID if name not available (not supported by site)                     ?>
                            </td>
                            <td class="wie-import-results-widget-title">
                                <?php echo esc_html( $widget['title'] ); // shows "No Title" if widget instance is untitled                       ?>
                            </td>
                            <td class="wie-import-results-widget-message wie-import-results-message wie-import-results-message-<?php echo sanitize_html_class( $widget['message_type'] ); ?>">
                                <?php echo esc_html( $widget['message'] ); // sidebar may not exist in theme                       ?>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                    <tr class="wie-import-results-space">
                        <td colspan="100%"></td>
                    </tr>

                <?php endforeach; ?>

            </table>
            <?php
        }
    }

    /**
     * Imports sidebars
     * 
     * @param  string $sidebar_slug    Sidebar slug to add widget
     * @param  string $widget_slug     Widget slug
     * @param  string $count_mod       position in sidebar
     * @param  array  $widget_settings widget settings
     *
     * @return null
     */
    public function add_widget_to_sidebar( $sidebar_slug, $widget_slug, $count_mod, $widget_settings = array() ) {

        $sidebars_widgets = get_option( 'sidebars_widgets' );

        if ( !isset( $sidebars_widgets[$sidebar_slug] ) )
            $sidebars_widgets[$sidebar_slug] = array( '_multiwidget' => 1 );

        $newWidget = get_option( 'widget_' . $widget_slug );

        if ( !is_array( $newWidget ) )
            $newWidget = array();

        $count = count( $newWidget ) + 1 + $count_mod;
        $sidebars_widgets[$sidebar_slug][] = $widget_slug . '-' . $count;

        $newWidget[$count] = $widget_settings;

        update_option( 'sidebars_widgets', $sidebars_widgets );
        update_option( 'widget_' . $widget_slug, $newWidget );
    }

    /**
     * Import content using WordPress importer
     * 
     * @param type $file
     */
    public function set_demo_data( $file ) {
        if ( !defined( 'WP_LOAD_IMPORTERS' ) )
            define( 'WP_LOAD_IMPORTERS', true );

        require_once ABSPATH . 'wp-admin/includes/import.php';

        $importer_error = false;

        if ( !class_exists( 'WP_Importer' ) ) {

            $class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';

            if ( file_exists( $class_wp_importer ) ) {

                require_once $class_wp_importer;
            } else {
                $importer_error = true;
            }
        }

        if ( !class_exists( 'WP_Import' ) ) {
            $class_wp_import = $this->plugin_dir_path . 'libs/wordpress-importer.php';

            if ( file_exists( $class_wp_import ) ) {
                require_once $class_wp_import;
            } else {
                $importer_error = true;
            }
        }

        if ( $importer_error ) {

            die( "Error on import" );
        } else {

            if ( !is_file( $file ) ) {
                esc_html_e( "The XML file containing the dummy content is not available or could not be read .. You might want to try to set the file permission to chmod 755.<br/>If this doesn't work please use the WordPress importer and import the XML file (should be located in your download .zip: Sample Content folder) manually.", 'volcanno-one-click-installer' );
            } else {

                $wp_import = new WP_Import();
                $wp_import->fetch_attachments = true;
                $wp_import->import( $file );
            }
        }
    }

    /**
     * Add menus to theme locations set in $menu_locations array
     *
     */
    public function set_demo_menus() {

        $menus = isset( $this->options['menu_locations'] ) ? $this->options['menu_locations'] : false;

        if ( !empty( $menus ) ) {

            // get locations
            $locations = get_theme_mod( 'nav_menu_locations' );

            foreach ( $menus as $location => $menu_name ) {

                if ( is_array( $menu_name ) ) {
                    $menu_name = !empty( $menu_name[$this->layout] ) ? $menu_name[$this->layout] : '';
                }

                // assign main menu
                $main_menu = get_term_by( 'name', $menu_name, 'nav_menu' );

                if ( !empty( $main_menu ) ) {

                    $locations[$location] = $main_menu->term_id;
                }
            }

            // update options
            set_theme_mod( 'nav_menu_locations', $locations );
        }
    }

    /**
     * Upload custom forms for Newsletter plugin
     */
    public function set_newsletter_forms() {
        $configuration_file = $this->demo_files_path . 'index.php';

        if ( file_exists( $configuration_file ) ) {
            require_once $configuration_file;

            // Check that file exists
            if ( !function_exists( 'volcanno_get_newsletter_forms' ) ) {
                wp_die(
                        esc_html__( 'Newsletter forms could not be imported. Please try again.', 'volcanno-one-click-installer' ), '', array( 'back_link' => true )
                );
            }

            $data = volcanno_get_newsletter_forms();
            update_option( 'newsletter_forms', $data );

            echo "<h4>" . esc_html__( 'Newsletter forms successfully uploaded.', 'volcanno-one-click-installer' ) . "</h4>";
        } else {
            echo "<h4>" . esc_html__( 'Configuration file does not exists.', 'volcanno-one-click-installer' ) . "</h4>";
        }
    }

    /**
     * Upload and set Theme options
     */
    public function set_demo_theme_options() {
        $configuration_file = $this->demo_files_path . 'index.php';

        if ( file_exists( $configuration_file ) ) {
            require_once $configuration_file;

            // File exists?
            if ( !function_exists( 'volcanno_get_redux_demo_options' ) ) {
                wp_die(
                        esc_html__( 'Theme options could not be imported. Please try again.', 'volcanno-one-click-installer' ), '', array( 'back_link' => true )
                );
            }

            // get JSON and decode it
            $layout = !empty($this->layout) ? $this->layout : '';
            $data = volcanno_get_redux_demo_options( $layout );
            $data = json_decode( $data, true );

            // Hook before import
            $data = apply_filters( 'voci_theme_import_theme_options', $data );

            update_option( $this->theme_option_name, $data );

            echo "<h4>" . esc_html__( 'Theme options successfully uploaded.', 'volcanno-one-click-installer' ) . "</h4>";
        } else {
            echo "<h4>" . esc_html__( 'Configuration file does not exists.', 'volcanno-one-click-installer' ) . "</h4>";
        }
    }

    /**
     * Upload Revolution slider demo slides
     * 
     * @param  string $file       url to slider file
     * 
     * @return void
     */
    public function set_revslider_slides( $file ) {

        if ( class_exists( 'RevSlider' ) ) {

            $slider = new RevSlider();
            $response = $slider->importSliderFromPost( true, true, $file );
            $sliderID = $response["sliderID"];

            //handle error
            if ( $response["success"] == false ) {
                $message = $response["error"];
                esc_html_e( 'Error:', 'volcanno-one-click-installer' );
                echo strip_tags( $message );
            } else {
                esc_html_e( 'Slider Import Success.', 'volcanno-one-click-installer' );
            }
        }
    }

    /**
     * Set Master slider demo file path.
     * 
     * @param string $path Default path
     * 
     * @return string New path
     */
    function set_master_slider_demo_path( $path ) {

        $demo_files = $this->demo_files;

        if ( isset( $demo_files['master_slider'] ) ) {

            $file = $this->demo_files_path . $demo_files['master_slider'];

            if ( file_exists( $file ) ) {
                $path = $file;
            }
        }

        return $path;
    }

    /**
     * Available widgets
     *
     * Gather site's widgets into array with ID base, name, etc.
     * Used by export and import functions.
     *
     * @global array $wp_registered_widget_updates
     * 
     * @return array Widget information
     */
    function available_widgets() {

        global $wp_registered_widget_controls;

        $widget_controls = $wp_registered_widget_controls;

        $available_widgets = array();

        foreach ( $widget_controls as $widget ) {

            if ( !empty( $widget['id_base'] ) && !isset( $available_widgets[$widget['id_base']] ) ) { // no dupes
                $available_widgets[$widget['id_base']]['id_base'] = $widget['id_base'];
                $available_widgets[$widget['id_base']]['name'] = $widget['name'];
            }
        }

        return apply_filters( 'voci_theme_import_widget_available_widgets', $available_widgets );
    }

    /**
     * Process import file
     *
     * This parses a file and triggers importation of its widgets.
     *
     * @param string $file Path to .wie file uploaded
     * @global string $widget_import_results
     */
    function process_widget_import_file( $file ) {

        $configuration_file = $this->demo_files_path . 'index.php';

        if ( file_exists( $configuration_file ) ) {
            require $configuration_file;

            // Check that file exists
            if ( !function_exists( 'volcanno_get_widget_demo_setup' ) ) {
                wp_die(
                        esc_html__( 'Widgets could not be imported. Please try again.', 'volcanno-one-click-installer' ), '', array( 'back_link' => true )
                );
            }

            // get JSON and decode it
            $layout = !empty($this->layout) ? $this->layout : '';
            $data = volcanno_get_widget_demo_setup( $layout );
            $data = json_decode( $data, true );

            $this->widget_import_results = $this->import_widgets( $data );
        }
    }

    /**
     * Import widget JSON data
     *
     * @global array $wp_registered_sidebars
     * @param object $data JSON widget data from .wie file
     * 
     * @return array Results array
     */
    public function import_widgets( $data ) {

        global $wp_registered_sidebars;

        // Have valid data?
        // If no data or could not decode
        if ( empty( $data ) || !is_array( $data ) ) {
            wp_die(
                    esc_html__( 'Widget import data could not be read. Please try a different file.', 'volcanno-one-click-installer' ), '', array( 'back_link' => true )
            );
        }

        // Hook before import
        $data = apply_filters( 'voci_theme_import_widget_data', $data );

        // Get all available widgets site supports
        $available_widgets = $this->available_widgets();

        // Get all existing widget instances
        $widget_instances = array();
        foreach ( $available_widgets as $widget_data ) {
            $widget_instances[$widget_data['id_base']] = get_option( 'widget_' . $widget_data['id_base'] );
        }

        // Begin results
        $results = array();

        // Loop import data's sidebars
        foreach ( $data as $sidebar_id => $widgets ) {

            // Skip inactive widgets
            // (should not be in export file)
            if ( 'wp_inactive_widgets' == $sidebar_id ) {
                continue;
            }

            // Check if sidebar is available on this site
            // Otherwise add widgets to inactive, and say so
            if ( isset( $wp_registered_sidebars[$sidebar_id] ) ) {
                $sidebar_available = true;
                $use_sidebar_id = $sidebar_id;
                $sidebar_message_type = 'success';
                $sidebar_message = '';
            } else {
                $sidebar_available = false;
                $use_sidebar_id = 'wp_inactive_widgets'; // add to inactive if sidebar does not exist in theme
                $sidebar_message_type = 'error';
                $sidebar_message = esc_html__( 'Sidebar does not exist in theme (using Inactive)', 'volcanno-one-click-installer' );
            }

            // Result for sidebar
            $results[$sidebar_id]['name'] = !empty( $wp_registered_sidebars[$sidebar_id]['name'] ) ? $wp_registered_sidebars[$sidebar_id]['name'] : $sidebar_id; // sidebar name if theme supports it; otherwise ID
            $results[$sidebar_id]['message_type'] = $sidebar_message_type;
            $results[$sidebar_id]['message'] = $sidebar_message;
            $results[$sidebar_id]['widgets'] = array();

            // Loop widgets
            foreach ( $widgets as $widget_instance_id => $widget ) {

                $fail = false;

                // Get id_base (remove -# from end) and instance ID number
                $id_base = preg_replace( '/-[0-9]+$/', '', $widget_instance_id );
                $instance_id_number = str_replace( $id_base . '-', '', $widget_instance_id );

                // Does site support this widget?
                if ( !$fail && !isset( $available_widgets[$id_base] ) ) {
                    $fail = true;
                    $widget_message_type = 'error';
                    $widget_message = esc_html__( 'Site does not support widget', 'volcanno-one-click-installer' ); // explain why widget not imported
                }

                // Filter to modify settings before import
                // Do before identical check because changes may make it identical to end result (such as URL replacements)
                $widget = apply_filters( 'voci_theme_import_widget_settings', $widget );

                // Does widget with identical settings already exist in same sidebar?
                if ( !$fail && isset( $widget_instances[$id_base] ) ) {

                    // Get existing widgets in this sidebar
                    $sidebars_widgets = get_option( 'sidebars_widgets' );
                    $sidebar_widgets = isset( $sidebars_widgets[$use_sidebar_id] ) ? $sidebars_widgets[$use_sidebar_id] : array(); // check Inactive if that's where will go
                    // Loop widgets with ID base
                    $single_widget_instances = !empty( $widget_instances[$id_base] ) ? $widget_instances[$id_base] : array();
                    foreach ( $single_widget_instances as $check_id => $check_widget ) {

                        // Is widget in same sidebar and has identical settings?
                        if ( in_array( "$id_base-$check_id", $sidebar_widgets ) && ( array ) $widget == $check_widget ) {

                            $fail = true;
                            $widget_message_type = 'warning';
                            $widget_message = esc_html__( 'Widget already exists', 'volcanno-one-click-installer' ); // explain why widget not imported

                            break;
                        }
                    }
                }

                // No failure
                if ( !$fail ) {

                    // Add widget instance
                    $single_widget_instances = get_option( 'widget_' . $id_base ); // all instances for that widget ID base, get fresh every time
                    $single_widget_instances = !empty( $single_widget_instances ) ? $single_widget_instances : array( '_multiwidget' => 1 ); // start fresh if have to
                    $single_widget_instances[] = ( array ) $widget; // add it
                    // Get the key it was given
                    end( $single_widget_instances );
                    $new_instance_id_number = key( $single_widget_instances );

                    // If key is 0, make it 1
                    // When 0, an issue can occur where adding a widget causes data from other widget to load, and the widget doesn't stick (reload wipes it)
                    if ( '0' === strval( $new_instance_id_number ) ) {
                        $new_instance_id_number = 1;
                        $single_widget_instances[$new_instance_id_number] = $single_widget_instances[0];
                        unset( $single_widget_instances[0] );
                    }

                    // Move _multiwidget to end of array for uniformity
                    if ( isset( $single_widget_instances['_multiwidget'] ) ) {
                        $multiwidget = $single_widget_instances['_multiwidget'];
                        unset( $single_widget_instances['_multiwidget'] );
                        $single_widget_instances['_multiwidget'] = $multiwidget;
                    }

                    // Update option with new widget
                    update_option( 'widget_' . $id_base, $single_widget_instances );

                    // Assign widget instance to sidebar
                    $sidebars_widgets = get_option( 'sidebars_widgets' ); // which sidebars have which widgets, get fresh every time
                    $new_instance_id = $id_base . '-' . $new_instance_id_number; // use ID number from new widget instance
                    $sidebars_widgets[$use_sidebar_id][] = $new_instance_id; // add new instance to sidebar
                    update_option( 'sidebars_widgets', $sidebars_widgets ); // save the amended data
                    // Success message
                    if ( $sidebar_available ) {
                        $widget_message_type = 'success';
                        $widget_message = esc_html__( 'Imported', 'volcanno-one-click-installer' );
                    } else {
                        $widget_message_type = 'warning';
                        $widget_message = esc_html__( 'Imported to Inactive', 'volcanno-one-click-installer' );
                    }
                }

                // Result for widget instance
                $results[$sidebar_id]['widgets'][$widget_instance_id]['name'] = isset( $available_widgets[$id_base]['name'] ) ? $available_widgets[$id_base]['name'] : $id_base; // widget name or ID if name not available (not supported by site)
                $results[$sidebar_id]['widgets'][$widget_instance_id]['title'] = isset( $widget->title ) ? $widget->title : esc_html__( 'No Title', 'volcanno-one-click-installer' ); // show "No Title" if widget instance is untitled
                $results[$sidebar_id]['widgets'][$widget_instance_id]['message_type'] = $widget_message_type;
                $results[$sidebar_id]['widgets'][$widget_instance_id]['message'] = $widget_message;
            }
        }

        // Hook after import
        do_action( 'volcanno_theme_import_widget_after_import' );

        // Return results       
        return apply_filters( 'voci_theme_import_widget_results', $results );
    }

    /**
     * Setup settings - set Home and Blog page
     *
     * @return void
     */
    public function setup_settings() {

        $settings = isset( $this->options['settings'] ) ? $this->options['settings'] : false;

        if ( empty( $settings ) ) {
            $home_page = 'Home';
            $blog_page = 'Blog';
        } else {

            $home_page = isset( $settings['home'] ) ? $settings['home'] : 'Home';
            $blog_page = isset( $settings['blog'] ) ? $settings['blog'] : 'Blog';

            if ( is_array( $home_page ) ) {
                $home_page = !empty( $home_page[$this->layout] ) ? $home_page[$this->layout] : 'Home';
            }

            if ( is_array( $blog_page ) ) {
                $blog_page = !empty( $blog_page[$this->layout] ) ? $blog_page[$this->layout] : 'Blog';
            }

        }

        // set Home page
        if ( is_int( $home_page ) ) {
            update_option( 'page_on_front', $home_page );
            update_option( 'show_on_front', 'page' );

        } else {
            if ( get_page_by_title( $home_page ) ) {

                // Use a static front page
                $home = get_page_by_title( $home_page );
                update_option( 'page_on_front', $home->ID );
                update_option( 'show_on_front', 'page' );
            }

        }

        // set Blog page
        if ( is_int( $blog_page ) ) {
            update_option( 'page_for_posts', $blog_page );

        } else {
            if ( get_page_by_title( $blog_page ) ) {

                // Set the blog page
                $blog = get_page_by_title( $blog_page );
                update_option( 'page_for_posts', $blog->ID );
            }

        }
    }

    /**
     * Helpers function that searches for needle at the start of haystack 
     * 
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    function starts_with( $haystack, $needle ) {
        // search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos( $haystack, $needle, -strlen( $haystack ) ) !== FALSE;
    }

    /**
     * Helper function that verifies if string ends with keyword
     * 
     * @param string $haystack
     * @param string $needle
     * @return string
     */
    public function ends_with( $haystack, $needle ) {

        // search forward starting from end minus needle length characters
        return $needle === "" || (($temp = strlen( $haystack ) - strlen( $needle )) >= 0 && strpos( $haystack, $needle, $temp ) !== FALSE);
    }

    /**
     * Check that admin menu item is created
     * 
     * @global array $menu
     * @global array $submenu
     * @param string $handle
     * @param boolean $sub
     * @return boolean
     */
    public function admin_menu_item_exists( $handle, $sub = false ) {
        if ( !is_admin() || (defined( 'DOING_AJAX' ) && DOING_AJAX) ) {
            return false;
        }
        global $menu, $submenu;
        $check_menu = $sub ? $submenu : $menu;
        if ( empty( $check_menu ) ) {
            return false;
        }

        foreach ( $check_menu as $k => $item ) {
            if ( $sub ) {
                foreach ( $item as $sm ) {
                    if ( $handle == $sm[2] ) {
                        return true;
                    }
                }
            } else {
                if ( $handle == $item[2] ) {
                    return true;
                }
            }
        }
        return false;
    }

}

if ( is_admin() ) {
    new Volcanno_One_Click_Installer();
}
?>