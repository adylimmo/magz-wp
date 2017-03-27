<?php


/**
* Main core file
*/
class Volcanno {
    
    function __construct() {
        self::init();
    }
 
    static function init() {
        if ( self::child_override(__FUNCTION__, func_get_args() ) ) return self::child_override(__FUNCTION__, func_get_args() ); // Child overide

        // Setup Jawiku Framework
        add_action( 'after_setup_theme', 'Volcanno::setup_framework', 12 );
        // Verify audio file type
        add_filter( 'volcanno_verify_post_audio', 'Volcanno::verify_audio_post_format_files' );
        // Hooks on CPT pages before items are rendered
        add_action( 'volcanno_pre_cpt_items_render', 'Volcanno::portofolio_gallery_required_plugins_loaded' );

    }
    
    /**
     * Breadcrumbs helper method
     * @return string
     */
    static function breadcrumbs() {
        // Enable or disable breadcrumbs
        if ( Volcanno::return_theme_option('breadcrumbs') ) {
            if ( class_exists('Volcanno_Breadcrumbs') ) { return Volcanno_Breadcrumbs::breadcrumbs(); }
        }
    }

    /**
     * Overide method if same function in child theme exist
     * 
     * @param  string
     * @return  function
     */
    static function child_override( $function, $params = '' ) {
        
        $class = __CLASS__ . '_Child';

        if ( class_exists( $class ) && is_callable( array($class, $function) ) ) {
            return call_user_func_array(array($class, $function), $params);
        }

    }

    /**
     * Setup framework
     * 
     * @return void
     */
    static function setup_framework() {
        if ( self::child_override(__FUNCTION__, func_get_args() ) ) return self::child_override(__FUNCTION__, func_get_args() ); // Child overide

        load_textdomain( 'volcanno', '' );
    }

    /**
     * Returns theme text domain
     * 
     * @global type $volcanno_theme_config
     * @return type string
     */
    static function get_theme_textdomain() {
        if ( self::child_override(__FUNCTION__, func_get_args() ) ) return self::child_override(__FUNCTION__, func_get_args() ); // Child overide

        global $volcanno_theme_config;

        // If the global textdomain isn't set, define it. Plugin/theme authors may also define a custom textdomain.
        if ( !isset( $volcanno_theme_config['text_domain'] ) || empty( $volcanno_theme_config['text_domain'] ) ) {
            $theme = wp_get_theme( get_template() );
            $textdomain = $theme->get( 'TextDomain' ) ? $theme->get( 'TextDomain' ) : get_template();
            $volcanno_theme_config['text_domain'] = sanitize_key( apply_filters( 'volcanno_theme_textdomain', $textdomain ) );
        }
        // Return the expected textdomain of the parent theme.
        return $volcanno_theme_config['text_domain'];
    }

    /**
     * Verify audio file type
     * 
     * @param type $audio_string
     * @return type array
     */
    static function verify_audio_post_format_files( $audio_string ) {
        if ( self::child_override(__FUNCTION__, func_get_args() ) ) return self::child_override(__FUNCTION__, func_get_args() ); // Child overide

        $audio_urls = preg_split( '/\r\n|[\r\n]/', $audio_string[0] );
        $allowed_formats = array( 'm4a', 'webma', 'oga', 'fla', 'wav', 'ogg' );
        $valid_urls = array();
        foreach ( $audio_urls as $url ) {
            $audio_format = substr( $url, strrpos( $url, '.' ) + 1 );
            if ( in_array( $audio_format, $allowed_formats ) ) {
                if ( $audio_format == 'ogg' )
                    $audio_format = 'oga';
                $valid_urls[$audio_format] = esc_url_raw( $url );
            }
        }
        return $valid_urls;
    }


    /**
     * Check if all required plugins are loaded for Portfolio and Gallery templates
     * 
     * @global string $volcanno_page_title
     * @global type $volcanno_theme_config
     */
    static function portofolio_gallery_required_plugins_loaded() {
        if ( self::child_override(__FUNCTION__, func_get_args() ) ) return self::child_override(__FUNCTION__, func_get_args() ); // Child overide

        global $volcanno_page_title, $volcanno_theme_config;

        if ( !VOLCANNO_CPTS || !VOLCANNO_META_BOX ) {

            $volcanno_page_title = 'Plugin activation required!';

            // include page title if portfolio/gallery single is loaded
            if ( is_singular( 'pi_portfolio' ) || is_singular( 'pi_gallery' ) ) {
                if ( file_exist( VOLCANNO_THEME_DIR . "/page-title.php" ) ) {
                    get_template_part( 'page', 'title' );
                } else {
                    get_template_part( 'section', 'title' );
                }
            }
            ?>
            <section class="page-content">
                <!-- container start -->
                <div class="container">
                    <!-- .row start -->
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            echo wp_kses( __( 'Please activate <strong>Custom post types</strong> and <strong>MetaBox</strong> plugins.', 'consultingpress' ), $volcanno_theme_config['allowed_html_tags'] );
                            ?>
                        </div>
                    </div><!-- .row end -->
                </div><!-- .container end -->
            </section>

            <?php
            // footer
            get_footer();

            exit;
        }
    }

    /**
     * Helper function for registering hooks
     * 
     * @param array $hooks_callbacks
     * @param string $hook_name
     */
    static function register_hooks( $hooks, $type ) {
        if ( self::child_override(__FUNCTION__, func_get_args() ) ) return self::child_override(__FUNCTION__, func_get_args() ); // Child overide

        // allow filtering the array with registered filters / actions
        if ( $type == 'filter' ) {
            $hooks = apply_filters( 'volcanno_theme_filters', $hooks );
        } else if ( $type == 'action' ) {
            $hooks = apply_filters( 'volcanno_theme_actions', $hooks );
        }

        foreach ( $hooks as $hook_name => $params ) {

            foreach ( $params as $callback => $val ) {

                if ( is_array( $val ) ) {

                    if ( count( $val ) == 2 ) {

                        $priority = $val[0];
                        $args = $val[1];
                    } else if ( count( $val ) == 1 ) {
                        $priority = $val[0];
                        $args = 1;
                    }

                    if ( $type == 'action' ) {
                        add_action( $hook_name, $callback, $priority, $args );
                    } else if ( $type == 'filter' ) {
                        add_filter( $hook_name, $callback, $priority, $args );
                    }
                } else {
                    if ( $type == 'action' ) {
                        add_action( $hook_name, $val );
                    } else if ( $type == 'filter' ) {
                        add_filter( $hook_name, $val );
                    }
                }
            }
        }

        // additional hook to allow changes after filters / actions are registered
        if ( $type == 'filter' ) {
            do_action( 'volcanno_after_filters_setup' );
        } else if ( $type == 'action' ) {
            do_action( 'volcanno_after_actions_setup' );
        }
    }

    /**
     * Method that returns option from theme options
     * 
     * @global array $volcanno_options
     * @param string $string
     * @param string $str
     * @return string
     */
    static function return_theme_option( $string, $str = null, $default = null ) {
        if ( self::child_override(__FUNCTION__, func_get_args() ) ) return self::child_override(__FUNCTION__, func_get_args() ); // Child overide

        global $volcanno_options;

        // check if redux option object is empty and default value is set
        if ( empty( $volcanno_options ) && !empty( $default ) ) {
            return $default;
            // check that Redux framework is activated and options object exists
        } else {
            if ( $str != null ) {
                return isset( $volcanno_options ['' . $string . ''] ['' . $str . ''] ) ? $volcanno_options ['' . $string . ''] ['' . $str . ''] : null;
            } else {
                return isset( $volcanno_options ['' . $string . ''] ) ? $volcanno_options ['' . $string . ''] : null;
            }
        }

        return null;
    }

    /**
     * Generate excerpt
     * @param  integer $length
     * @return string
     */
    static function generate_excerpt( $length = 30, $wrap = '' ) {

        add_filter( 'excerpt_more', function( $more ) { return '...'; } );
        add_filter( 'excerpt_length', function( $length ) { return $length; }, 999 );
        $excerpt = get_the_excerpt();

        if ( !empty($wrap) ) {
            $excerpt = '<' . $wrap . '>' . $excerpt . '</' . $wrap . '>';
        }
        return balancetags( $excerpt, true );
    }

    /**
     * Return cropped image
     * @param  int      $img_id    
     * @param  array    $size   width, height
     * @param  bool     $crop
     * @param  string   $type   default empty ( responsive, retina, )
     * @param  bool     $post_thumbnail 
     * @param  string   $attr   additional image params
     * @return string 
     *
     * @version 1.0
     */
    static function get_image( $img_id, $size = array(), $crop = false, $type = 'responsive', $post_thumbnail = false, $attr = array() ) {

        // Check if $img_id exist
        if ( empty($img_id) ) 
            return;
        
        // Post thumbnail
        if ( $post_thumbnail ) {
            $image_url = get_the_post_thumbnail_url( $img_id );
            $image_id = get_post_thumbnail_id();
        } else {
            $image_url = wp_get_attachment_url( $img_id ); 
            $image_id = $img_id;
        }

        // Get original image width & height
        $orig_meta = wp_get_attachment_metadata($image_id);
        $orig_width = isset( $orig_meta['width'] ) ? $orig_meta['width'] : 1;
        $orig_height = isset( $orig_meta['height'] ) ? $orig_meta['height'] : 1;

        // Get image alt text
        $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
        if ( !array_key_exists( 'alt', $attr ) ) {
            $image_alt_param = $image_alt ? ' alt="' . $image_alt . '"' : '';
        } else {
            $image_alt_param = '';
        }

        // Pass aditional attributes into string
        $attributes = '';
        foreach ($attr as $key => $value) {
            $attributes .= ' ' . $key . '="' . $value . '"';
        }

        // Define crop width & height
        if ( empty( $size ) ) {
            $width = $orig_width;
            $height = $orig_height;
        } else {
            $width = isset($size[0]) ? $size[0] : '';
            $height = isset($size[1]) ? $size[1] : '';
        }

        if ( isset($size[0]) && isset($size[1]) ) {
            // Define crop width & height ratio
            $crop_ratio = $width / $height;
        } else {
            $crop_ratio = $orig_width / $orig_height;
        }

        // Define src param
        $src = empty( $size ) ? $image_url : volcanno_crop_image( $image_id, $width, $height);

        // Define srcset param
        if ( $type == 'retina' ) {
            if ( $orig_width > ($width * 2) ) {
                $retina_width = isset($size[0]) ? $width * 2 : '';
                $retina_height = isset($size[1]) ? $height * 2 : '';
                $srcset = volcanno_crop_image( $image_id, $retina_width, $retina_height) . ' 2x';
            } else if ( $orig_width > $width ) {
                $srcset = $image_url . ' 2x';
            } else {
                $srcset = '';
            }
        } else {
            $srcset = ( $orig_width >= 480 && $orig_height >= (480 / $crop_ratio) ) ? volcanno_crop_image( $image_id, '480', (480 / $crop_ratio) ) . ' 480w' : ''; // mobile
            $srcset .= ( $orig_width >= 768 && $orig_height >= (768 / $crop_ratio) ) ? ', ' . volcanno_crop_image( $image_id, '768', (768 / $crop_ratio) ) . ' 768w' : ''; // tablet
            $srcset .= ( $orig_width >= 1024 && $orig_height >= (1024 / $crop_ratio) ) ? ', ' . volcanno_crop_image( $image_id, '1024', (1024 / $crop_ratio) ) . ' 1024w' : ''; // laptop
            $srcset .= ( $orig_width >= 1140 && $orig_height >= (1140 / $crop_ratio) ) ? ', ' . volcanno_crop_image( $image_id, '1440', (1440 / $crop_ratio) ) . ' 1140w' : ''; // laptop large
            $srcset .= ( $orig_width >= 1920 && $orig_height >= (1920 / $crop_ratio) ) ? ', ' . volcanno_crop_image( $image_id, '1920', (1920 / $crop_ratio) ) . ' 1920w' : ''; // desktop

        }

        if ( !empty( $srcset ) ) {
            $srcset_attr = ' srcset="' . $srcset . '"';
            
            // Define sizes param
            if ( $type == 'retina' ) {
                $sizes = '';
            } else {
                $sizes = ' sizes="(max-width: ' . $width . 'px) 100vw, ' . $width . 'px"';
            }
        
        } else {
            $srcset_attr = '';
            $sizes = '';
        }
        
        // Check for width & height
        if ( isset($size[0]) || isset($size[1]) ) {
            $width = isset($size[0]) ? $width : $height * $crop_ratio;
            $height = isset($size[1]) ? $height : $width / $crop_ratio;
        }
        
        // Image output
        if ( !empty($src) ) {
            $output = '<img width="' . floor( $width ) . '" height="' . floor( $height ) .'" src="' . $src . '"' . $image_alt_param . $attributes . $srcset_attr . $sizes . '>';
        } else {
            $output = '';
        }
        
        $output = apply_filters('volcanno_after_image_fetch', $output, $image_id);

        return $output;
    }
}