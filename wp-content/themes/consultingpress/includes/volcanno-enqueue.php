<?php
/* ---------------------------------------------------------
 * Enqueue
 *
 * Class for including Javascript and CSS files
  ---------------------------------------------------------- */

class Volcanno_Enqueue {

    public static $css;
    public static $js;
    public static $admin_css;

    /**
     * Configuration array for stylesheet that will be loaded
     */
    static function load_css() {

        // array with CSS file to load
        static::$css = array(
            // CSS default files
            'bootstrap' => 'includes/assets/bootstrap/css/bootstrap.min.css',
            'font-awesome' => 'includes/assets/fonts/font-awesome/css/font-awesome.min.css',
            'volcanno-font-lynny' => 'includes/assets/fonts/lynny/style.min.css',
            'volcanno-animate' => 'css/animate.css',
            'volcanno-consulting-press' => 'style.css',
            'volcanno-consulting-press-responsive' => 'css/responsive.css',
        );

        // enqueue files
        Volcanno_Enqueue::enqueue_css();
    }

    /**
     * Configuration array for Javascript files that will be loaded
     */
    static function load_js() {

        // hookname => array(filename, dependency_hook, load_in_footer, version)
        // url to file should be relative to the theme root directory
        // default: load_in_footer = TRUE
        static::$js = array(
            'jQuery-bootstrap' => array('includes/assets/bootstrap/js/bootstrap.min.js', 'jquery', true), // Bootstrap script
            'jQuery-scripts' => array('js/jquery-scripts.js', 'jquery', true), // Additional scripts
            'jQuery-volcanno' => array('js/volcanno.include.js', 'jquery', true), // Custom jQuery functions
        );

        if ( is_singular() && get_option('thread_comments') ) {
            static::$js['comment-reply'] = '';
        }

        // enqueue files
        Volcanno_Enqueue::enqueue_js();
    }

    /**
     * Enqueue Javascript and CSS file to admin
     */
    static function load_admin_css_js() {

        // array with admin css files
        static::$admin_css = array(
            'font-awesome' => VOLCANNO_TEMPLATEURL . '/includes/assets/fonts/font-awesome/css/font-awesome.min.css',
            'volcanno-font-lynny' => VOLCANNO_TEMPLATEURL . '/includes/assets/fonts/lynny/style.min.css',
        );

        // enqueue files
        Volcanno_Enqueue::enqueue_admin_css_js();
    }
    
     /**
     * Enqueue Google Fonts
     */
    static function google_fonts() {
        wp_enqueue_style( 'volcanno-google-fonts', Volcanno_Enqueue::get_fonts_url(), array(), '1.0.0' );
    }
    
    /**
     * Returns Google Fonts URL to load
     * 
     * @return string
     */
    static function get_fonts_url() {
        $font_url = '';

        /*
          Translators: If there are characters in your language that are not supported
          by chosen font(s), translate this to 'off'. Do not translate into your own language.
         */
        if ( 'off' !== _x( 'on', 'Google font: on or off', 'consultingpress' ) ) {
            $font_url = add_query_arg( 'family', urlencode( 'Poppins:400,300,500,600|PT Serif:400,400i&subset=latin,latin-ext' ), "//fonts.googleapis.com/css" );
        }
        return $font_url;
    }

    /**
     * Enqueue CSS files
     */
    static function enqueue_css() {

        // concate full url to file by add url prefix to css dir
        static::$css = array_map('Volcanno_Helper::enqueue_css_prefix', static::$css);

        // allow modifiying array of css files that will be loaded
        static::$css = apply_filters('volcanno_css_files', static::$css);

        // loop through files and enqueue
        foreach (static::$css as $key => $value) {

            // if value is array it means dependency and $media might be set
            if (is_array($value)) {
                $file = isset($value[0]) ? $value[0] : '';
                $dependency = isset($value[1]) ? $value[1] : '';
                $media = isset($value[2]) ? $value[2] : 'all';

                wp_enqueue_style($key, $file, $dependency, '', $media);
            } else {
                wp_enqueue_style($key, $value, '', '');
            }
        }
    }

    /**
     * Enqueue Javascript files
     */
    static function enqueue_js() {

        // concate full url to file by add url prefix to js dir
        static::$js = array_map('Volcanno_Helper::enqueue_js_prefix', static::$js);

        // allow modifiying array of javascript files that will be loaded
        static::$js = apply_filters('volcanno_js_files', static::$js);

        // loop through files and enqueue
        foreach (static::$js as $key => $value) {

            // if value is array it means dependency and $in_footer might be set
            if (is_array($value)) {
                $file = isset($value[0]) ? $value[0] : '';
                $dependency = isset($value[1]) ? array( $value[1] ) : '';
                $in_footer = isset($value[2]) ? $value[2] : true;
                $version = VOLCANNO_THEME_VERSION;

                wp_enqueue_script($key, $file, $dependency, $version, $in_footer);
            } else {
                wp_enqueue_script($key, $value, '', '', true);
            }
        }
    }

    /**
     * Enqueue Javascript and CSS file to admin
     */
    static function enqueue_admin_css_js() {

        // allow modifiying array of css files that will be loaded
        static::$admin_css = apply_filters('volcanno_admin_css_files', static::$admin_css);

        // loop through array of admin css files and load them
        foreach (static::$admin_css as $key => $value) {

            wp_enqueue_style($key, $value);
        }
    }

    /**
     * Make certain options available on front-end
     */
    static function localize_script() {

        // pass settings to include.js file
        $static_header = Volcanno::return_theme_option('static_header');
        $retina = Volcanno::return_theme_option('retina');        
        $scroll_top_top = Volcanno::return_theme_option('scrool_to_top');        
        $smooth_scroll = Volcanno::return_theme_option('smooth_scroll');

        // output config object
        wp_localize_script('jQuery-volcanno', 'VolcannoConfig', array(
            'themeName' => sanitize_title(VOLCANNO_THEME_NAME),
            'staticHeader' => $static_header,
            'volcannoTemplateUrl' => VOLCANNO_TEMPLATEURL,
            'volcannoScrollToTop' => !empty( $scroll_top_top ) ? true : false,
            'volcannoSmoothScroll' => !empty( $smooth_scroll ) ? true : false,
        ));

        wp_localize_script('jQuery-retina', 'VolcannoConfig', array(
            'retina' => $retina
        ));
        
    }

    /**
     * Register scripts and styles for using in shortcodes
     */
    static function register_css_js() {

        // Owl Carousel
        wp_register_style( 'owl-carousel', VOLCANNO_TEMPLATEURL . '/includes/assets/owl-carousel/owl.carousel.css' );
        wp_register_script( 'owl-carousel', VOLCANNO_TEMPLATEURL . '/includes/assets/owl-carousel/owl.carousel.min.js' );
        wp_register_script( 'owl-carousel-init', VOLCANNO_TEMPLATEURL . '/js/volcanno.owlcarousel.init.js' );

        // Odometer
        wp_register_style( 'odometer', VOLCANNO_TEMPLATEURL . '/includes/assets/odometer/odometer.min.css' );
        wp_register_script( 'odometer', VOLCANNO_TEMPLATEURL . '/includes/assets/odometer/odometer.min.js' );

        // Nivo slider
        wp_register_style( 'nivo-slider', VOLCANNO_TEMPLATEURL . '/includes/assets/nivo-slider/nivo-slider.css' );
        wp_register_style( 'nivo-slider-consulting-press', VOLCANNO_TEMPLATEURL . '/includes/assets/nivo-slider/consulting-press/nivo-slider-consulting-press.css' );
        wp_register_script( 'nivo-slider', VOLCANNO_TEMPLATEURL . '/includes/assets/nivo-slider/jquery.nivo.slider.pack.js' );

        // Parallax image
        wp_register_script( 'jQuery-parallax-scrool', VOLCANNO_TEMPLATEURL . '/js/jquery.parallax-scroll.js', array('jquery')); // Image Parallax Moving on Scroll Animation' );
        wp_register_script( 'jQuery-parallax-scrool-init', VOLCANNO_TEMPLATEURL . '/js/jquery.parallax-scroll-init.js', array('jquery')); // Image Parallax Moving on Scroll Animation' );
        
    }
}
