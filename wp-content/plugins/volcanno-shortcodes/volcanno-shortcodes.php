<?php

/* -----------------------------------------------------------------------------------

  Plugin Name: Volcanno Shortcodes
  Plugin URI: http://www.pixel-industry.com
  Description: A plugin for shortcodes functionality by Pixel Industry themes.
  Version: 1.2
  Author: Pixel Industry
  Author URI: http://www.pixel-industry.com

  ----------------------------------------------------------------------------------- */

if ( !defined( 'VOLCANNO_VC_SHORTCODES_URL' ) )
    define( 'VOLCANNO_VC_SHORTCODES_URL', plugin_dir_url( __FILE__ ) );

if ( !defined( 'VOLCANNO_VC_SHORTCODES_DIR' ) ) {
    define( 'VOLCANNO_VC_SHORTCODES_DIR', get_template_directory() );
}

if ( !function_exists( 'is_plugin_active' ) ) {
    require_once ABSPATH . '/wp-admin/includes/plugin.php';
}

class Volcanno_Visual_Composer {

    /**
     * Init function
     */
    static function init() {
        
        Volcanno_Visual_Composer::load_composer_element_files();
        
        if ( is_plugin_active( 'js_composer/js_composer.php' ) ) {
            // Actions
            add_action( 'vc_before_init', 'Volcanno_Visual_Composer::vc_set_as_theme' );
            // Filters
            add_filter( 'vc_iconpicker-type-lynny', array( 'Volcanno_Visual_Composer', 'lynny_icons' ) );
            add_filter( 'vc_base_build_shortcodes_custom_css', array( 'Volcanno_Visual_Composer', 'custom_build_shortcodes_custom_css' ), 10, 1 );
            add_filter( 'vc_shortcode_output', array( 'Volcanno_Visual_Composer', 'custom_vc_output' ), 10, 3 );

            // Add additional visual composer params to volcanno elements
            add_filter( 'volcanno_vc_map_params', array('Volcanno_Visual_Composer', 'vc_map_params' ) );

            // Filter shortcode output
            add_filter( 'volcanno_vc_shortcode_output_wrapper', array('Volcanno_Visual_Composer', 'add_shortcode_wrapper' ), 10, 2 );

        }
        if ( !is_plugin_active( 'js_composer/js_composer.php' ) ) {

            function vc_param_group_parse_atts() {
                return array();
            }

        }

        // Add support for Shortcodes in Widgets
        add_filter( 'widget_text', 'do_shortcode' );
    }

    /**
     * Add additional params to all volcanno visual composer elements
     * 
     * @param  array $params
     * @return array
     */
    static function vc_map_params( $params ) {

        // Add animation param
        array_push( $params, self::animation_param() );
        // Add extra class param
        array_push( $params, self::extra_class_param() );
        // Add desing param
        array_push( $params, self::design_param() );

        return $params;

    }

    /**
     * Add additional atts to all volcanno shortcodes
     * 
     * @param array $out
     * @param array $pairs
     * @param array $atts
     * @return array
     */
    static function add_shortcode_atts( $out, $pairs, $atts ) {

        $out['design_param'] = '';
        $out['animation_param'] = '';
        $out['extra_class_param'] = '';

        return $out;
    }

    static function add_shortcode_wrapper( $output, $atts ) {

        // Additional params
        $design_param = !empty( $atts['design_param'] ) ? $atts['design_param'] : '';
        $animation_param = !empty( $atts['animation_param'] ) ? $atts['animation_param'] : '';
        $extra_class_param = !empty( $atts['extra_class_param'] ) ? $atts['extra_class_param'] : '';

        // If any of additional params is not empty wrap output
        if ( !empty( $design_param ) || !empty( $animation_param ) || !empty( $extra_class_param ) ) {
            $classes = Volcanno_Visual_Composer::return_css_class( $design_param, $animation_param, $extra_class_param );
            $data_animate = !empty( $animation_param ) ? ' data-animate="' . $animation_param . '"' : '';
            $output = '<div class="' . $classes . '"' . $data_animate . '>' . $output . '</div>';
        }

        return balancetags( $output );
    }

    /**
     * Enable specific features for visual composer
     * 
     * @param  array $atts
     * @return void
     */
    static function features_enable( $atts ) {

        if ( in_array( 'shadow', $atts ) ) {
            add_action( 'vc_after_init', 'Volcanno_Visual_Composer::add_row_shadow_params' );
        }

        if ( in_array( 'overlay', $atts ) ) {
            add_action( 'vc_after_init', 'Volcanno_Visual_Composer::add_row_overlay_params' );
        }

        if ( !empty( $atts ) ) {
            add_filter( 'vc_shortcodes_css_class', array( 'Volcanno_Visual_Composer', 'add_row_additional_class' ), 10, 3 );
        }
    }

    /**
     * Add additional overlay params to row 
     */
    static function add_row_overlay_params() {
        $overlayParamData = array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'Row overlay', 'js_composer' ),
            "edit_field_class" => "vc_col-xs-6",
            'param_name' => 'row_overlay',
            'value' => '',
            'description' => esc_html__( 'Enable row overlay if you have background image or color.', 'js_composer' ),
            'weight' => 0,
        );
        $overlayColorParamData = array(
            'type' => 'colorpicker',
            'heading' => esc_html__( 'Row overlay color', 'js_composer' ),
            "edit_field_class" => "vc_col-xs-6",
            'param_name' => 'row_overlay_color',
            'value' => '',
            'description' => esc_html__( 'These need to have some transparency.', 'js_composer' ),
            'weight' => 0,
            'dependency' => array(
                'element' => 'row_overlay',
                'value' => 'true',
            ),
        );
        vc_update_shortcode_param( 'vc_row', $overlayParamData );
        vc_update_shortcode_param( 'vc_row', $overlayColorParamData );
    }

    /**
     * Add additional shadow params to row
     */
    static function add_row_shadow_params() {
        $shadowParamData = array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'Enable row shadow', 'js_composer' ),
            'param_name' => 'row_shadow',
            'value' => '',
            'description' => esc_html__( 'Enable row shadow.', 'js_composer' ),
            'weight' => 0,
        );
        vc_update_shortcode_param( 'vc_row', $shadowParamData );
    }

    /**
     * Add class to row depending on row overlay param
     * 
     * @param string $css_classes 
     * @param string $base        
     * @param array $atts        
     */
    static function add_row_additional_class( $css_classes, $base, $atts ) {
        if ( $base == 'vc_row' ) {
            // Add row overlay class
            $row_overlay = isset( $atts['row_overlay'] ) ? $atts['row_overlay'] : false;
            if ( $row_overlay ) {
                $css_classes .= ' vc_row-has-overlay';
            }
            // Add row shadow class
            $row_shadow = isset( $atts['row_shadow'] ) ? $atts['row_shadow'] : false;
            if ( $row_shadow ) {
                $css_classes .= ' bkg-color-white-shadow';
            }
        }
        return $css_classes;
    }

    /**
     * Add overlay div in row
     * 
     * @param  string $output 
     * @param  string $this  
     * @param  array $atts   
     * @return string
     */
    static function custom_vc_output( $output, $this, $atts ) {
        if ( isset( $atts['row_overlay'] ) ) {
            $overlay_color = isset( $atts['row_overlay_color'] ) ? ' style="background-color:' . $atts['row_overlay_color'] . ';"' : '';
            $output = preg_replace( '/^<div (.*?)>/', '<div $1><div class="page-content-mask mask-solid-color"' . $overlay_color . '></div>', $output );
        }
        return $output;
    }

    /**
     * Function that ouput image tag
     * 
     * @param  id  $img_id         
     * @param  array   $size           
     * @param  boolean $crop           
     * @param  string  $type           
     * @param  boolean $post_thumbnail
     * @param  array   $attr           
     * @return string
     */
    static function get_image( $img_id, $size = array(), $crop = false, $type = 'responsive', $post_thumbnail = false, $attr = array() ) {

        if ( class_exists( 'Volcanno' ) ) {
            $image = Volcanno::get_image( $img_id, $size, $crop, $type, $post_thumbnail, $attr );
        } else {
            // Post thumbnail
            if ( $post_thumbnail ) {
                $image_url = get_the_post_thumbnail_url( $img_id );
                $image_id = get_post_thumbnail_id();
            } else {
                $image_url = wp_get_attachment_url( $img_id );
                $image_id = $img_id;
            }

            $image = wp_get_attachment_image( $image_id, $size, false, $attr );
        }

        return $image;
    }

    /**
     * Filter visual composer custom css images, and adds media queries with cropped images for each resolution.
     * 
     * @param  $css 
     * @return string
     */
    static function custom_build_shortcodes_custom_css( $css ) {

        if ( function_exists( 'volcanno_crop_image' ) ) :

            preg_match_all( "/\.vc_custom_(\d*){(.*?)}/", $css, $output_array );

            // Class name id's
            $classes = $output_array[1];
            // Class css
            $properties = $output_array[2];

            // Initial variables
            $media_query = $images_480 = $images_768 = $images_1024 = $images_1440 = $images_1920 = '';

            foreach ( $classes as $index => $class ) {

                // Check for class with background image
                $property = preg_match( '/background-image:.*?\((.*)\?id=(\d*).*?\)/', $properties[$index] );
                // If custom class has image background
                if ( $property ) {

                    // Get background image url and id
                    preg_match_all( '/background-image:.*?\((.*)\?id=(\d*).*?\)/', $properties[$index], $image_url );
                    $image_id = $image_url[2][0];
                    $image_url = $image_url[1][0];

                    // Get original image width & height
                    $orig_meta = wp_get_attachment_metadata( $image_id );
                    $orig_width = isset( $orig_meta['width'] ) ? $orig_meta['width'] : 1;
                    $orig_height = isset( $orig_meta['height'] ) ? $orig_meta['height'] : 1;

                    // Get image ratio
                    $ratio = $orig_width / $orig_height;

                    // Width 480px
                    if ( $orig_width > 480 ) {
                        $image = volcanno_crop_image( $image_id, '480', (480 / $ratio ) );
                        $images_480 .= '.vc_custom_' . $class . '{ background-image: url(' . $image . '?id=' . $image_id . ') !important; }';
                    }

                    // Width 768px
                    if ( $orig_width > 768 ) {
                        $image = volcanno_crop_image( $image_id, '768', (768 / $ratio ) );
                        $images_768 .= '.vc_custom_' . $class . '{ background-image: url(' . $image . '?id=' . $image_id . ') !important; }';
                    }

                    // Width 1024px
                    if ( $orig_width > 1024 ) {
                        $image = volcanno_crop_image( $image_id, '1024', (1024 / $ratio ) );
                        $images_1024 .= '.vc_custom_' . $class . '{ background-image: url(' . $image . '?id=' . $image_id . ') !important; }';
                    }

                    // Width 1440px
                    if ( $orig_width > 1440 ) {
                        $image = volcanno_crop_image( $image_id, '1440', (1440 / $ratio ) );
                        $images_1440 .= '.vc_custom_' . $class . '{ background-image: url(' . $image . '?id=' . $image_id . ') !important; }';
                    }

                    // Width 1920px
                    if ( $orig_width > 1920 ) {
                        $image = volcanno_crop_image( $image_id, '1920', (1920 / $ratio ) );
                        $images_1920 .= '.vc_custom_' . $class . '{ background-image: url(' . $image . '?id=' . $image_id . ') !important; }';
                    }
                }
            }

            // Media query for 480px
            $media_query .= $images_480 != '' ? '@media only screen and (min-width: 0) and (max-width: 480px) {' . $images_480 . '} ' : '';
            // Media query for 768px
            $media_query .= $images_768 != '' ? '@media only screen and (min-width: 481px) and (max-width: 768px) {' . $images_768 . '} ' : '';
            // Media query for 1024px
            $media_query .= $images_1024 != '' ? '@media only screen and (min-width: 769px) and (max-width: 1024px) {' . $images_1024 . '} ' : '';
            // Media query for 1440px
            $media_query .= $images_1440 != '' ? '@media only screen and (min-width: 1025px) and (max-width: 1440px) {' . $images_1440 . '} ' : '';
            // Media query for 1920px
            $media_query .= $images_1920 != '' ? '@media only screen and (min-width: 1441px) and (max-width: 1920px) {' . $images_1920 . '} ' : '';

            $css = $css . ' ' . $media_query;

        endif;

        return $css;
    }

    /**
     * Load shortcode files from directory inside plugin. Additional directory 
     * can be added using 'cma_shortcodes_directory' hook
     * 
     * @return void 
     */
    static function load_composer_element_files() {

        $shortcodes_dir = apply_filters( 'composer_elements_directory', array( get_template_directory() . "/includes/composer-elements/" ) );

        if ( is_dir( get_stylesheet_directory() . "/volcanno/composer-elements/" ) ) {

            $shortcodes_dir[] = get_stylesheet_directory() . "/volcanno/composer-elements/";
        }

        // reverse values in array so that user can override values 
        // by using the filter
        $shortcodes_dir = array_reverse( $shortcodes_dir );

        foreach ( $shortcodes_dir as $dir ) {
            $dir_files = glob( $dir . '*.php' );
            $dir_in_directory = glob( $dir . '*/*.php' );

            if ( is_array( $dir_files ) && count( $dir_files ) > 0 ) {
                foreach ( $dir_files as $file ) {
                    require_once( $file );
                }
            }

            if ( is_array( $dir_in_directory ) && count( $dir_in_directory ) > 0 ) {
                foreach ( $dir_in_directory as $file ) {
                    require_once( $file );
                    preg_match_all( '/\/composer-elements\/(.*?)\//', $file, $shortcode_tag );
                    $shortcode_tag = preg_replace( '/(-)/', '_', $shortcode_tag[1][0] );
                    $shortcode_tag = 'volcanno_' . $shortcode_tag;
                    $shortcode_func = preg_replace( '/(-)/', '_', $shortcode_tag ) . '_func';
                    if ( $shortcode_tag != 'assets' ) {
                        add_shortcode( $shortcode_tag, $shortcode_func );
                        // Filter shortcode atts
                        add_filter( 'shortcode_atts_' . $shortcode_tag, array('Volcanno_Visual_Composer', 'add_shortcode_atts' ), 10, 3 );
                    }
                }
            }
        }
    }

    /**
     * Hide certain Visual Composer settings tabs
     * 
     * @return void
     */
    static function vc_set_as_theme() {
        vc_set_as_theme();
    }

    /**
     * Get array of used categories for visual composer dropdown param
     * 
     * @return array array("Name" => "slug")
     */
    static function vc_get_categories( $taxonomy = ''  ) {
        
        $output = array( 'All categories' => '' );

        if ( !empty( $taxonomy ) ) {

            if ( taxonomy_exists( $taxonomy ) ) {
                // Get all terms from taxonomy
                $terms = get_terms( array( 'taxonomy' => $taxonomy, 'hierarchical' => true ) );
                $output = array();

                foreach ( $terms as $term => $data ) {
                  $name = $data->name;
                  $slug = $data->slug;
                  $output[$name] = $slug;
                }

                // Sort array by value
                if ( is_array( $output ) ) {
                    asort( $output );
                }

                return $output;
            }
        } else {
            $categories = get_categories();
            foreach ( $categories as $object => $param ) {
                $name = $param->name;
                $slug = $param->slug;
                $output[$name] = $slug;
            }
        }
    }

    /**
     * Design param
     * @return array 
     */
    static function design_param() {
        return array(
            'type' => 'css_editor',
            'heading' => esc_html__( 'Css', 'volcanno' ),
            'param_name' => 'design_param',
            'group' => esc_html__( 'Design options', 'volcanno' ),
        );
    }

    /**
     * Animation param
     * @return array
     */
    static function animation_param() {
        return array(
            "type" => "dropdown",
            "holder" => "",
            "class" => "",
            "heading" => esc_html__( "CSS Animation", "empire" ),
            "param_name" => "animation_param",
            "description" => esc_html__( 'Select type of animation for element to be animated when it "enters" the browsers viewport (Note: works only in modern browsers).', 'empire' ),
            "value" => array(
                'No animation' => '',
                'bounce' => 'bounce',
                'flash' => 'flash',
                'pulse' => 'pulse',
                'rubber Band' => 'rubberBand',
                'shake' => 'shake',
                'head Shake' => 'headShake',
                'swing' => 'swing',
                'tada' => 'tada',
                'wobble' => 'wobble',
                'jello' => 'jello',
                'bounce In' => 'bounceIn',
                'bounce In Down' => 'bounceInDown',
                'bounce In Left' => 'bounceInLeft',
                'bounce In Right' => 'bounceInRight',
                'bounce In Up' => 'bounceInUp',
                'bounce Out' => 'bounceOut',
                'bounce Out Down' => 'bounceOutDown',
                'bounce Out Left' => 'bounceOutLeft',
                'bounce Out Right' => 'bounceOutRight',
                'bounce Out Up' => 'bounceOutUp',
                'fade In' => 'fadeIn',
                'fade In Down' => 'fadeInDown',
                'fade In Down Big' => 'fadeInDownBig',
                'fade In Left' => 'fadeInLeft',
                'fade In Left Big' => 'fadeInLeftBig',
                'fade In Right' => 'fadeInRight',
                'fade In Right Big' => 'fadeInRightBig',
                'fade In Up' => 'fadeInUp',
                'fade In Up Big' => 'fadeInUpBig',
                'fade Out' => 'fadeOut',
                'fade Out Down' => 'fadeOutDown',
                'fade Out Down Big' => 'fadeOutDownBig',
                'fade Out Left' => 'fadeOutLeft',
                'fade Out Left Big' => 'fadeOutLeftBig',
                'fade Out Right' => 'fadeOutRight',
                'fade Out Right Big' => 'fadeOutRightBig',
                'fade Out Up' => 'fadeOutUp',
                'fade Out Up Big' => 'fadeOutUpBig',
                'flip In X' => 'flipInX',
                'flip In Y' => 'flipInY',
                'flip Out X' => 'flipOutX',
                'flip Out Y' => 'flipOutY',
                'light Speed In' => 'lightSpeedIn',
                'light Speed Out' => 'lightSpeedOut',
                'rotate In' => 'rotateIn',
                'rotate In Down Left' => 'rotateInDownLeft',
                'rotate In Down Right' => 'rotateInDownRight',
                'rotate In Up Left' => 'rotateInUpLeft',
                'rotate In Up Right' => 'rotateInUpRight',
                'rotate Out' => 'rotateOut',
                'rotate Out Down Left' => 'rotateOutDownLeft',
                'rotate Out Down Right' => 'rotateOutDownRight',
                'rotate Out Up Left' => 'rotateOutUpLeft',
                'rotate Out Up Right' => 'rotateOutUpRight',
                'hinge' => 'hinge',
                'roll In' => 'rollIn',
                'roll Out' => 'rollOut',
                'zoom In' => 'zoomIn',
                'zoom In Down' => 'zoomInDown',
                'zoom In Left' => 'zoomInLeft',
                'zoom In Right' => 'zoomInRight',
                'zoom In Up' => 'zoomInUp',
                'zoom Out' => 'zoomOut',
                'zoom Out Down' => 'zoomOutDown',
                'zoom Out Left' => 'zoomOutLeft',
                'zoom Out Right' => 'zoomOutRight',
                'zoom Out Up' => 'zoomOutUp',
                'slide In Down' => 'slideInDown',
                'slide In Left' => 'slideInLeft',
                'slide In Right' => 'slideInRight',
                'slide In Up' => 'slideInUp',
                'slide Out Down' => 'slideOutDown',
                'slide Out Left' => 'slideOutLeft',
                'slide Out Right' => 'slideOutRight',
                'slide Out Up' => 'slideOutUp'
            ),
        );
    }

    /**
     * Extra class param
     * @return array 
     */
    static function extra_class_param() {
        return array(
            "type" => "textfield",
            "class" => "",
            "heading" => esc_html__( "Extra class name", "empire" ),
            "param_name" => "extra_class_param",
            "value" => esc_html__( "", "empire" ),
            "description" => esc_html__( "Style particular content element differently - add a class name and refer to it in custom CSS.", "empire" ),
        );
    }

    /**
     * Icons library dropdown param
     * @param  $param_name
     * @param  $dependency 
     * @param  $field_class visual composer default is 'vc_col-xs-12'
     * @return array             
     */
    static function icons_lib( $param_name = 'icon_type', $dependency = array(), $default = '', $field_class = 'vc_col-xs-12' ) {
        return array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Icon library', 'volcanno' ),
            'edit_field_class' => $field_class,
            'value' => array(
                esc_html__( 'Font Awesome', 'volcanno' ) => 'icon_fontawesome',
                esc_html__( 'Lynny', 'volcanno' ) => 'icon_lynny',
            ),
            'param_name' => $param_name,
            'std' => $default,
            'description' => esc_html__( 'Select icon library.', 'volcanno' ),
            'dependency' => $dependency,
        );
    }

    /**
     * Iconpicker param
     * @param  $library
     * @param  $dependency
     * @param  $field_class visual composer default is 'vc_col-xs-12'
     * @return array        
     */
    static function icons_param( $library, $dependency = 'icon_type', $default = '', $field_class = 'vc_col-xs-12' ) {
        return array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon', 'volcanno' ),
            'edit_field_class' => $field_class,
            'param_name' => 'icon_' . $library,
            'value' => $default,
            'settings' => array(
                'emptyIcon' => true,
                'iconsPerPage' => 75,
                'type' => $library,
            ),
            'dependency' => array(
                'element' => $dependency,
                'value' => 'icon_' . $library,
            ),
            'description' => esc_html__( 'Select icon from library.', 'volcanno' ),
        );
    }

    /**
     * Build link from href attribute
     * @param  $href    
     * @param  $content 
     * @param   $params  
     * @return string
     *
     * @version  1.0
     */
    static function build_link( $href, $content = '', $params = array(), $default = '' ) {

        if ( !function_exists( 'vc_build_link' ) )
            return;

        $href = vc_build_link( $href );

        if ( $href['url'] ) {

            // Build link params
            $href_title = $href['title'] ? ' title="' . $href['title'] . '"' : '';
            $href_target = $href['target'] ? ' target="' . $href['target'] . '"' : '';
            $href_rel = $href['rel'] ? ' rel="' . $href['rel'] . '"' : '';
            $href_atts = $href_title . $href_rel . $href_target;

            // Additional params
            $attributes = '';
            foreach ( $params as $key => $value ) {
                if ( !empty( $value ) ) {
                    $attributes .= ' ' . $key . '="' . $value . '"';
                }
            }

            $content = '<a href="' . esc_url( $href['url'] ) . '"' . $href_atts . $attributes . '>' . $content . '</a>';
        } else if ( !empty( $default ) ) {
            $content = $default;
        }

        return balancetags( $content );
    }

    /**
     * Return additional classes
     * @param  string $design_param      
     * @param  string $animation_param   
     * @param  string $extra_class_param
     * @param  array $additional array('example', 'example2')
     * @param array $type 'Set to true if you want to output class attribute or false if output only classes'
     * @return string
     *
     * @version 1.0
     */
    static function return_css_class( $design_param = '', $animation_param = '', $extra_class_param = '', $additional = array(), $type = false ) {

        // Default class for volcanno custom elements
        $css_class = ' vc-volcanno';

        // Animation, Design & Extra Class params
        $css_class .= $design_param != '' ? ' ' . preg_replace( '/(.)(.*?)({.*?})/', '$2', $design_param ) : '';
        $css_class .= $animation_param ? ' triggerAnimation animated' : '';
        $css_class .=!empty( $extra_class_param ) ? ' ' . $extra_class_param : '';

        // Add attribute if additional classes exist
        if ( !empty( $additional ) ) {
            // Loop additional classes
            foreach ( $additional as $index => $class ) {
                // Add space to begining of class name if needed
                $space = $index == 1 || !$type ? '' : ' ';
                // Add class
                $css_class .= $space . $class;
            }
        }

        if ( $type && !empty( $css_class ) ) {
            // Add class attribute with classes
            $css_class = ' class="' . $css_class . '"';
        }

        return $css_class;
    }

    /**
     * Decode encoded html string into raw html
     * @param  string $code
     * @return string
     */
    static function decode_raw_html( $code = '' ) {
       if ( !empty( $code ) ) {
          return htmlentities( rawurldecode( base64_decode( strip_tags( $code ) ) ), ENT_COMPAT, 'UTF-8' );
       }
    }

    /**
     * Lynny icons classes
     * @param  $icons
     * @return array
     */
    static function lynny_icons( $icons ) {

        $prefix = 'lynny-';

        $lynny_icons = array(
            array( $prefix . "boat" => "boat" ),
            array( $prefix . "add" => "add" ),
            array( $prefix . "address-book" => "address-book" ),
            array( $prefix . "admission-ticket" => "admission-ticket" ),
            array( $prefix . "admission-tickets" => "admission-tickets" ),
            array( $prefix . "airplay-logo" => "airplay-logo" ),
            array( $prefix . "align-center" => "align-center" ),
            array( $prefix . "align-justify" => "align-justify" ),
            array( $prefix . "align-left" => "align-left" ),
            array( $prefix . "align-right" => "align-right" ),
            array( $prefix . "android" => "android" ),
            array( $prefix . "arrow-circle-down" => "arrow-circle-down" ),
            array( $prefix . "arrow-circle-left" => "arrow-circle-left" ),
            array( $prefix . "arrow-circle-right" => "arrow-circle-right" ),
            array( $prefix . "arrow-circle-up" => "arrow-circle-up" ),
            array( $prefix . "arrow-curved-down" => "arrow-curved-down" ),
            array( $prefix . "arrow-curved-left" => "arrow-curved-left" ),
            array( $prefix . "arrow-curved-right" => "arrow-curved-right" ),
            array( $prefix . "arrow-curved-up" => "arrow-curved-up" ),
            array( $prefix . "arrow-down-1" => "arrow-down-1" ),
            array( $prefix . "arrow-down-2" => "arrow-down-2" ),
            array( $prefix . "arrow-down-3" => "arrow-down-3" ),
            array( $prefix . "arrow-left-1" => "arrow-left-1" ),
            array( $prefix . "arrow-left-2" => "arrow-left-2" ),
            array( $prefix . "arrow-left-3" => "arrow-left-3" ),
            array( $prefix . "arrow-move" => "arrow-move" ),
            array( $prefix . "arrow-righ-angle-1" => "arrow-righ-angle-1" ),
            array( $prefix . "arrow-righ-angle-2" => "arrow-righ-angle-2" ),
            array( $prefix . "arrow-right-1" => "arrow-right-1" ),
            array( $prefix . "arrow-right-2" => "arrow-right-2" ),
            array( $prefix . "arrow-right-3" => "arrow-right-3" ),
            array( $prefix . "arrow-square-down" => "arrow-square-down" ),
            array( $prefix . "arrow-square-left" => "arrow-square-left" ),
            array( $prefix . "arrow-square-right" => "arrow-square-right" ),
            array( $prefix . "arrow-square-up" => "arrow-square-up" ),
            array( $prefix . "arrow-triangle-down" => "arrow-triangle-down" ),
            array( $prefix . "arrow-triangle-left" => "arrow-triangle-left" ),
            array( $prefix . "arrow-triangle-right" => "arrow-triangle-right" ),
            array( $prefix . "arrow-triangle-up" => "arrow-triangle-up" ),
            array( $prefix . "arrow-up-1" => "arrow-up-1" ),
            array( $prefix . "arrow-up-2" => "arrow-up-2" ),
            array( $prefix . "arrow-up-3" => "arrow-up-3" ),
            array( $prefix . "at" => "at" ),
            array( $prefix . "atlas" => "atlas" ),
            array( $prefix . "atom" => "atom" ),
            array( $prefix . "bandaid" => "bandaid" ),
            array( $prefix . "bar-chart" => "bar-chart" ),
            array( $prefix . "barcode" => "barcode" ),
            array( $prefix . "barcode-scan" => "barcode-scan" ),
            array( $prefix . "barcode-search" => "barcode-search" ),
            array( $prefix . "battery-1" => "battery-1" ),
            array( $prefix . "battery-2" => "battery-2" ),
            array( $prefix . "battery-3" => "battery-3" ),
            array( $prefix . "battery-4" => "battery-4" ),
            array( $prefix . "battery-5" => "battery-5" ),
            array( $prefix . "battery-charging" => "battery-charging" ),
            array( $prefix . "beaker-1" => "beaker-1" ),
            array( $prefix . "beaker-2" => "beaker-2" ),
            array( $prefix . "beaker-3" => "beaker-3" ),
            array( $prefix . "bell" => "bell" ),
            array( $prefix . "billboard-1" => "billboard-1" ),
            array( $prefix . "billboard-2" => "billboard-2" ),
            array( $prefix . "binoculars" => "binoculars" ),
            array( $prefix . "bitcoin-coin" => "bitcoin-coin" ),
            array( $prefix . "bitcoin-coins" => "bitcoin-coins" ),
            array( $prefix . "bluetooth" => "bluetooth" ),
            array( $prefix . "bone" => "bone" ),
            array( $prefix . "bones" => "bones" ),
            array( $prefix . "book" => "book" ),
            array( $prefix . "bookmark-1" => "bookmark-1" ),
            array( $prefix . "bookmark-2" => "bookmark-2" ),
            array( $prefix . "bookshelf" => "bookshelf" ),
            array( $prefix . "boom-box" => "boom-box" ),
            array( $prefix . "bottle" => "bottle" ),
            array( $prefix . "bow-tie" => "bow-tie" ),
            array( $prefix . "box-1" => "box-1" ),
            array( $prefix . "box-2" => "box-2" ),
            array( $prefix . "box-download" => "box-download" ),
            array( $prefix . "box-papers" => "box-papers" ),
            array( $prefix . "box-upload" => "box-upload" ),
            array( $prefix . "box-video" => "box-video" ),
            array( $prefix . "briefcase" => "briefcase" ),
            array( $prefix . "browser" => "browser" ),
            array( $prefix . "browser-compass" => "browser-compass" ),
            array( $prefix . "browsers" => "browsers" ),
            array( $prefix . "building" => "building" ),
            array( $prefix . "building-2" => "building-2" ),
            array( $prefix . "calculator" => "calculator" ),
            array( $prefix . "calendar-1" => "calendar-1" ),
            array( $prefix . "calendar-2" => "calendar-2" ),
            array( $prefix . "camera" => "camera" ),
            array( $prefix . "camera-film" => "camera-film" ),
            array( $prefix . "camera-polaroid" => "camera-polaroid" ),
            array( $prefix . "cameras" => "cameras" ),
            array( $prefix . "camera-wide-angle" => "camera-wide-angle" ),
            array( $prefix . "cash-register" => "cash-register" ),
            array( $prefix . "celsius" => "celsius" ),
            array( $prefix . "chat-1" => "chat-1" ),
            array( $prefix . "chat-2" => "chat-2" ),
            array( $prefix . "chat-3" => "chat-3" ),
            array( $prefix . "checkmark-1" => "checkmark-1" ),
            array( $prefix . "checkmark-2" => "checkmark-2" ),
            array( $prefix . "chromecast-logo" => "chromecast-logo" ),
            array( $prefix . "circle-outline" => "circle-outline" ),
            array( $prefix . "circle-outline-circle" => "circle-outline-circle" ),
            array( $prefix . "circle-outline-down" => "circle-outline-down" ),
            array( $prefix . "circle-outline-up" => "circle-outline-up" ),
            array( $prefix . "clipboard" => "clipboard" ),
            array( $prefix . "clock-1" => "clock-1" ),
            array( $prefix . "clock-2" => "clock-2" ),
            array( $prefix . "close" => "close" ),
            array( $prefix . "cloud-1" => "cloud-1" ),
            array( $prefix . "cloud-2" => "cloud-2" ),
            array( $prefix . "cloud-download" => "cloud-download" ),
            array( $prefix . "cloud-lightning-1" => "cloud-lightning-1" ),
            array( $prefix . "cloud-lightning-2" => "cloud-lightning-2" ),
            array( $prefix . "cloud-rain--1" => "cloud-rain--1" ),
            array( $prefix . "cloud-rain-2" => "cloud-rain-2" ),
            array( $prefix . "cloud-rain-3" => "cloud-rain-3" ),
            array( $prefix . "cloud-snow" => "cloud-snow" ),
            array( $prefix . "cloud-sun" => "cloud-sun" ),
            array( $prefix . "cloud-sync" => "cloud-sync" ),
            array( $prefix . "cloud-upload" => "cloud-upload" ),
            array( $prefix . "command-line" => "command-line" ),
            array( $prefix . "compass" => "compass" ),
            array( $prefix . "contact-card-1" => "contact-card-1" ),
            array( $prefix . "contact-card-2" => "contact-card-2" ),
            array( $prefix . "contact-card-3" => "contact-card-3" ),
            array( $prefix . "contact-cards" => "contact-cards" ),
            array( $prefix . "credit-card" => "credit-card" ),
            array( $prefix . "credit-cards" => "credit-cards" ),
            array( $prefix . "currency-euro" => "currency-euro" ),
            array( $prefix . "desk" => "desk" ),
            array( $prefix . "desktop" => "desktop" ),
            array( $prefix . "desktop-download" => "desktop-download" ),
            array( $prefix . "desktop-left" => "desktop-left" ),
            array( $prefix . "desktop-right" => "desktop-right" ),
            array( $prefix . "desktop-sync" => "desktop-sync" ),
            array( $prefix . "desktop-upload" => "desktop-upload" ),
            array( $prefix . "diamond" => "diamond" ),
            array( $prefix . "dlna-logo" => "dlna-logo" ),
            array( $prefix . "dollar-coin" => "dollar-coin" ),
            array( $prefix . "dollar-coins" => "dollar-coins" ),
            array( $prefix . "download" => "download" ),
            array( $prefix . "download-circle" => "download-circle" ),
            array( $prefix . "drawers-1" => "drawers-1" ),
            array( $prefix . "drawers-2" => "drawers-2" ),
            array( $prefix . "drawers-3" => "drawers-3" ),
            array( $prefix . "dribbble-cricle" => "dribbble-cricle" ),
            array( $prefix . "dribbble-square" => "dribbble-square" ),
            array( $prefix . "dropbox-cricle" => "dropbox-cricle" ),
            array( $prefix . "dropbox-square" => "dropbox-square" ),
            array( $prefix . "droplet" => "droplet" ),
            array( $prefix . "eject" => "eject" ),
            array( $prefix . "envelopes-vertical" => "envelopes-vertical" ),
            array( $prefix . "envelope-vertical" => "envelope-vertical" ),
            array( $prefix . "error" => "error" ),
            array( $prefix . "euro-coin" => "euro-coin" ),
            array( $prefix . "euro-coins" => "euro-coins" ),
            array( $prefix . "exit-1" => "exit-1" ),
            array( $prefix . "exit-2" => "exit-2" ),
            array( $prefix . "eye" => "eye" ),
            array( $prefix . "facebook-circle" => "facebook-circle" ),
            array( $prefix . "facebook-square" => "facebook-square" ),
            array( $prefix . "fahrenheit" => "fahrenheit" ),
            array( $prefix . "fast-forward" => "fast-forward" ),
            array( $prefix . "file-ai" => "file-ai" ),
            array( $prefix . "file-gif" => "file-gif" ),
            array( $prefix . "file-jpg" => "file-jpg" ),
            array( $prefix . "file-pdf" => "file-pdf" ),
            array( $prefix . "file-png" => "file-png" ),
            array( $prefix . "file-psd" => "file-psd" ),
            array( $prefix . "filter" => "filter" ),
            array( $prefix . "filter-add" => "filter-add" ),
            array( $prefix . "filter-remove" => "filter-remove" ),
            array( $prefix . "finder" => "finder" ),
            array( $prefix . "flag-1" => "flag-1" ),
            array( $prefix . "flag-2" => "flag-2" ),
            array( $prefix . "floppy-disk-1" => "floppy-disk-1" ),
            array( $prefix . "floppy-disk-2" => "floppy-disk-2" ),
            array( $prefix . "folder" => "folder" ),
            array( $prefix . "folder-more" => "folder-more" ),
            array( $prefix . "folders" => "folders" ),
            array( $prefix . "folder-search" => "folder-search" ),
            array( $prefix . "fork" => "fork" ),
            array( $prefix . "fullscreen" => "fullscreen" ),
            array( $prefix . "fullscreen-reverse" => "fullscreen-reverse" ),
            array( $prefix . "game-controller" => "game-controller" ),
            array( $prefix . "gift" => "gift" ),
            array( $prefix . "global-connection" => "global-connection" ),
            array( $prefix . "globe-1" => "globe-1" ),
            array( $prefix . "globe-2_1" => "globe-2_1" ),
            array( $prefix . "globe-arrows" => "globe-arrows" ),
            array( $prefix . "globes" => "globes" ),
            array( $prefix . "gold" => "gold" ),
            array( $prefix . "google-plus-cricle" => "google-plus-cricle" ),
            array( $prefix . "google-plus-square" => "google-plus-square" ),
            array( $prefix . "graduation" => "graduation" ),
            array( $prefix . "grid-1" => "grid-1" ),
            array( $prefix . "grid-2" => "grid-2" ),
            array( $prefix . "grid-3" => "grid-3" ),
            array( $prefix . "grid-4" => "grid-4" ),
            array( $prefix . "grid-5" => "grid-5" ),
            array( $prefix . "grid-6" => "grid-6" ),
            array( $prefix . "grid-7" => "grid-7" ),
            array( $prefix . "grid-8" => "grid-8" ),
            array( $prefix . "grid-9" => "grid-9" ),
            array( $prefix . "grid-10" => "grid-10" ),
            array( $prefix . "grid-11" => "grid-11" ),
            array( $prefix . "grid-12" => "grid-12" ),
            array( $prefix . "handbag" => "handbag" ),
            array( $prefix . "hard-drive" => "hard-drive" ),
            array( $prefix . "hat-cane" => "hat-cane" ),
            array( $prefix . "headphones" => "headphones" ),
            array( $prefix . "headphones-2" => "headphones-2" ),
            array( $prefix . "headphones-3" => "headphones-3" ),
            array( $prefix . "health-circle" => "health-circle" ),
            array( $prefix . "health-square" => "health-square" ),
            array( $prefix . "heart" => "heart" ),
            array( $prefix . "heart-broken-1" => "heart-broken-1" ),
            array( $prefix . "heart-broken-2" => "heart-broken-2" ),
            array( $prefix . "help" => "help" ),
            array( $prefix . "home" => "home" ),
            array( $prefix . "inbox" => "inbox" ),
            array( $prefix . "inbox-download" => "inbox-download" ),
            array( $prefix . "inboxes" => "inboxes" ),
            array( $prefix . "inbox-full" => "inbox-full" ),
            array( $prefix . "inbox-upload" => "inbox-upload" ),
            array( $prefix . "infinite" => "infinite" ),
            array( $prefix . "info" => "info" ),
            array( $prefix . "instagram-cricle" => "instagram-cricle" ),
            array( $prefix . "instagram-square" => "instagram-square" ),
            array( $prefix . "key-1" => "key-1" ),
            array( $prefix . "key-2" => "key-2" ),
            array( $prefix . "keyboard" => "keyboard" ),
            array( $prefix . "keyboard-down" => "keyboard-down" ),
            array( $prefix . "keyboard-up" => "keyboard-up" ),
            array( $prefix . "laptop" => "laptop" ),
            array( $prefix . "life-preserver" => "life-preserver" ),
            array( $prefix . "lightning" => "lightning" ),
            array( $prefix . "line-chart" => "line-chart" ),
            array( $prefix . "linkedin-cricle" => "linkedin-cricle" ),
            array( $prefix . "linkedin-square" => "linkedin-square" ),
            array( $prefix . "list-1" => "list-1" ),
            array( $prefix . "list-2" => "list-2" ),
            array( $prefix . "list-3" => "list-3" ),
            array( $prefix . "location-target" => "location-target" ),
            array( $prefix . "lock" => "lock" ),
            array( $prefix . "magnifier" => "magnifier" ),
            array( $prefix . "mail" => "mail" ),
            array( $prefix . "mail-duplicate" => "mail-duplicate" ),
            array( $prefix . "map-1" => "map-1" ),
            array( $prefix . "map-2" => "map-2" ),
            array( $prefix . "map-pin-1" => "map-pin-1" ),
            array( $prefix . "map-pin-2" => "map-pin-2" ),
            array( $prefix . "map-pin-3" => "map-pin-3" ),
            array( $prefix . "medicine-bottle" => "medicine-bottle" ),
            array( $prefix . "megaphone-1" => "megaphone-1" ),
            array( $prefix . "megaphone-2" => "megaphone-2" ),
            array( $prefix . "megaphone-3" => "megaphone-3" ),
            array( $prefix . "microphone-1" => "microphone-1" ),
            array( $prefix . "microphone-2" => "microphone-2" ),
            array( $prefix . "mobile" => "mobile" ),
            array( $prefix . "moon-1" => "moon-1" ),
            array( $prefix . "moon-2" => "moon-2" ),
            array( $prefix . "moon-cycle-1" => "moon-cycle-1" ),
            array( $prefix . "moon-cycle-2" => "moon-cycle-2" ),
            array( $prefix . "moon-cycle-3" => "moon-cycle-3" ),
            array( $prefix . "moon-cycle-4" => "moon-cycle-4" ),
            array( $prefix . "moon-cycle-5" => "moon-cycle-5" ),
            array( $prefix . "moon-cycle-6" => "moon-cycle-6" ),
            array( $prefix . "moon-cycle-7" => "moon-cycle-7" ),
            array( $prefix . "moon-cycle-8" => "moon-cycle-8" ),
            array( $prefix . "more-1" => "more-1" ),
            array( $prefix . "more-2" => "more-2" ),
            array( $prefix . "mouse" => "mouse" ),
            array( $prefix . "music-note-1" => "music-note-1" ),
            array( $prefix . "music-note-2" => "music-note-2" ),
            array( $prefix . "music-note-circle" => "music-note-circle" ),
            array( $prefix . "music-note-square-1" => "music-note-square-1" ),
            array( $prefix . "music-note-square-2" => "music-note-square-2" ),
            array( $prefix . "music-note-square-3" => "music-note-square-3" ),
            array( $prefix . "neck-tie" => "neck-tie" ),
            array( $prefix . "newspaper" => "newspaper" ),
            array( $prefix . "newspaper-stack" => "newspaper-stack" ),
            array( $prefix . "next" => "next" ),
            array( $prefix . "no-entry" => "no-entry" ),
            array( $prefix . "note" => "note" ),
            array( $prefix . "notepad" => "notepad" ),
            array( $prefix . "orbit" => "orbit" ),
            array( $prefix . "oswindows" => "oswindows" ),
            array( $prefix . "osx" => "osx" ),
            array( $prefix . "page-1" => "page-1" ),
            array( $prefix . "page-2" => "page-2" ),
            array( $prefix . "pages-1" => "pages-1" ),
            array( $prefix . "pages-2" => "pages-2" ),
            array( $prefix . "paper-bitcoin" => "paper-bitcoin" ),
            array( $prefix . "paper-bitcoins-1" => "paper-bitcoins-1" ),
            array( $prefix . "paper-bitcoins-2" => "paper-bitcoins-2" ),
            array( $prefix . "paper-clip" => "paper-clip" ),
            array( $prefix . "paper-dollar" => "paper-dollar" ),
            array( $prefix . "paper-dollars-1" => "paper-dollars-1" ),
            array( $prefix . "paper-dollars-2" => "paper-dollars-2" ),
            array( $prefix . "paper-euro" => "paper-euro" ),
            array( $prefix . "paper-euros-1" => "paper-euros-1" ),
            array( $prefix . "paper-euros-2" => "paper-euros-2" ),
            array( $prefix . "paper-plane" => "paper-plane" ),
            array( $prefix . "paper-shredder" => "paper-shredder" ),
            array( $prefix . "pause" => "pause" ),
            array( $prefix . "pencil" => "pencil" ),
            array( $prefix . "phone-1" => "phone-1" ),
            array( $prefix . "phone-2" => "phone-2" ),
            array( $prefix . "photo-1" => "photo-1" ),
            array( $prefix . "photo-list" => "photo-list" ),
            array( $prefix . "photos-1" => "photos-1" ),
            array( $prefix . "photos-2" => "photos-2" ),
            array( $prefix . "photos-3" => "photos-3" ),
            array( $prefix . "pie-chart" => "pie-chart" ),
            array( $prefix . "pill" => "pill" ),
            array( $prefix . "pinterest-cricle" => "pinterest-cricle" ),
            array( $prefix . "pinterest-square" => "pinterest-square" ),
            array( $prefix . "play" => "play" ),
            array( $prefix . "podcast-1" => "podcast-1" ),
            array( $prefix . "podcast-2" => "podcast-2" ),
            array( $prefix . "popout" => "popout" ),
            array( $prefix . "posicle" => "posicle" ),
            array( $prefix . "power" => "power" ),
            array( $prefix . "presentation-1" => "presentation-1" ),
            array( $prefix . "presentation-2" => "presentation-2" ),
            array( $prefix . "presentation-3" => "presentation-3" ),
            array( $prefix . "presentation-4" => "presentation-4" ),
            array( $prefix . "presentation-5" => "presentation-5" ),
            array( $prefix . "printer" => "printer" ),
            array( $prefix . "radar" => "radar" ),
            array( $prefix . "radio" => "radio" ),
            array( $prefix . "rainbow" => "rainbow" ),
            array( $prefix . "receipt" => "receipt" ),
            array( $prefix . "receipts" => "receipts" ),
            array( $prefix . "record" => "record" ),
            array( $prefix . "record-player" => "record-player" ),
            array( $prefix . "refresh-1" => "refresh-1" ),
            array( $prefix . "refresh-2" => "refresh-2" ),
            array( $prefix . "refresh-3" => "refresh-3" ),
            array( $prefix . "refresh-4" => "refresh-4" ),
            array( $prefix . "remove" => "remove" ),
            array( $prefix . "road-signs" => "road-signs" ),
            array( $prefix . "rocket" => "rocket" ),
            array( $prefix . "router" => "router" ),
            array( $prefix . "rss-1" => "rss-1" ),
            array( $prefix . "rss-2" => "rss-2" ),
            array( $prefix . "safe" => "safe" ),
            array( $prefix . "scissors" => "scissors" ),
            array( $prefix . "server-connections" => "server-connections" ),
            array( $prefix . "servers_1" => "servers_1" ),
            array( $prefix . "settings-1" => "settings-1" ),
            array( $prefix . "settings-2" => "settings-2" ),
            array( $prefix . "settings-3" => "settings-3" ),
            array( $prefix . "share-1" => "share-1" ),
            array( $prefix . "share-2" => "share-2" ),
            array( $prefix . "shield" => "shield" ),
            array( $prefix . "shopping-bag" => "shopping-bag" ),
            array( $prefix . "shopping-bags" => "shopping-bags" ),
            array( $prefix . "shopping-basket" => "shopping-basket" ),
            array( $prefix . "shopping-basket-add" => "shopping-basket-add" ),
            array( $prefix . "shopping-basket-in" => "shopping-basket-in" ),
            array( $prefix . "shopping-basket-left" => "shopping-basket-left" ),
            array( $prefix . "shopping-basket-out" => "shopping-basket-out" ),
            array( $prefix . "shopping-basket-remove" => "shopping-basket-remove" ),
            array( $prefix . "shopping-basket-right" => "shopping-basket-right" ),
            array( $prefix . "shopping-cart" => "shopping-cart" ),
            array( $prefix . "shopping-cart-add" => "shopping-cart-add" ),
            array( $prefix . "shopping-cart-in" => "shopping-cart-in" ),
            array( $prefix . "shopping-cart-left" => "shopping-cart-left" ),
            array( $prefix . "shopping-cart-out" => "shopping-cart-out" ),
            array( $prefix . "shopping-cart-remove" => "shopping-cart-remove" ),
            array( $prefix . "shopping-cart-right" => "shopping-cart-right" ),
            array( $prefix . "shuffle" => "shuffle" ),
            array( $prefix . "sign-post" => "sign-post" ),
            array( $prefix . "square-outline" => "square-outline" ),
            array( $prefix . "square-outline-cube" => "square-outline-cube" ),
            array( $prefix . "square-outline-down" => "square-outline-down" ),
            array( $prefix . "square-outline-up" => "square-outline-up" ),
            array( $prefix . "stack-1" => "stack-1" ),
            array( $prefix . "stack-2" => "stack-2" ),
            array( $prefix . "star" => "star" ),
            array( $prefix . "star-circle" => "star-circle" ),
            array( $prefix . "stars" => "stars" ),
            array( $prefix . "stop" => "stop" ),
            array( $prefix . "storefront" => "storefront" ),
            array( $prefix . "suitcase" => "suitcase" ),
            array( $prefix . "suitcases" => "suitcases" ),
            array( $prefix . "sun" => "sun" ),
            array( $prefix . "sunrise" => "sunrise" ),
            array( $prefix . "sunset" => "sunset" ),
            array( $prefix . "tablet" => "tablet" ),
            array( $prefix . "tag" => "tag" ),
            array( $prefix . "tags" => "tags" ),
            array( $prefix . "target" => "target" ),
            array( $prefix . "tea-cup" => "tea-cup" ),
            array( $prefix . "test-tube" => "test-tube" ),
            array( $prefix . "thermometer" => "thermometer" ),
            array( $prefix . "toggles-1" => "toggles-1" ),
            array( $prefix . "toggles-2" => "toggles-2" ),
            array( $prefix . "toggles-3" => "toggles-3" ),
            array( $prefix . "top-charts" => "top-charts" ),
            array( $prefix . "trash" => "trash" ),
            array( $prefix . "trash-full" => "trash-full" ),
            array( $prefix . "tree" => "tree" ),
            array( $prefix . "tumblr-cricle" => "tumblr-cricle" ),
            array( $prefix . "tumblr-square" => "tumblr-square" ),
            array( $prefix . "turn-ahead" => "turn-ahead" ),
            array( $prefix . "tv" => "tv" ),
            array( $prefix . "tv-fuzz" => "tv-fuzz" ),
            array( $prefix . "tv-old" => "tv-old" ),
            array( $prefix . "twitter-circle" => "twitter-circle" ),
            array( $prefix . "twitter-square" => "twitter-square" ),
            array( $prefix . "umbrella" => "umbrella" ),
            array( $prefix . "unlock" => "unlock" ),
            array( $prefix . "upload" => "upload" ),
            array( $prefix . "user-female" => "user-female" ),
            array( $prefix . "user-group-1" => "user-group-1" ),
            array( $prefix . "user-group-2" => "user-group-2" ),
            array( $prefix . "user-male" => "user-male" ),
            array( $prefix . "video-1" => "video-1" ),
            array( $prefix . "video-2" => "video-2" ),
            array( $prefix . "video-3" => "video-3" ),
            array( $prefix . "video-camera-1" => "video-camera-1" ),
            array( $prefix . "video-camera-2" => "video-camera-2" ),
            array( $prefix . "video-camera-3" => "video-camera-3" ),
            array( $prefix . "video-camera-4" => "video-camera-4" ),
            array( $prefix . "vimeo-cricle" => "vimeo-cricle" ),
            array( $prefix . "vimeo-square" => "vimeo-square" ),
            array( $prefix . "volume-1" => "volume-1" ),
            array( $prefix . "volume-2" => "volume-2" ),
            array( $prefix . "volume-3" => "volume-3" ),
            array( $prefix . "volume-4" => "volume-4" ),
            array( $prefix . "volume-off" => "volume-off" ),
            array( $prefix . "wallet-1" => "wallet-1" ),
            array( $prefix . "wallet-2" => "wallet-2" ),
            array( $prefix . "wallet-3" => "wallet-3" ),
            array( $prefix . "warning" => "warning" ),
            array( $prefix . "wi-fi-logo" => "wi-fi-logo" ),
            array( $prefix . "wind" => "wind" ),
            array( $prefix . "wine-glass" => "wine-glass" ),
            array( $prefix . "wordpress-cricle" => "wordpress-cricle" ),
            array( $prefix . "wordpress-square" => "wordpress-square" ),
            array( $prefix . "wrench" => "wrench" ),
            array( $prefix . "write" => "write" ),
            array( $prefix . "yahoo-cricle" => "yahoo-cricle" ),
            array( $prefix . "yahoo-square" => "yahoo-square" ),
            array( $prefix . "youtube-circle" => "youtube-circle" ),
            array( $prefix . "youtube-square" => "youtube-square" ),
            array( $prefix . "zoom-in" => "zoom-in" ),
            array( $prefix . "zoom-out" => "zoom-out" ),
        );

        return array_merge( $icons, $lynny_icons );
    }

}

Volcanno_Visual_Composer::init();
