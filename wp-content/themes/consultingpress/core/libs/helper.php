<?php

/**
* Theme helper functions
*/
class Volcanno_Helper {
    
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
     * Substring text
     * 
     * @param string $str Text to cut
     * @param int $length Number of characters to cut
     * @param string $minword How many letters word must have to skip it from cutting
     * @return string
     */
    static function substr( $str, $length, $minword = 3 ) {
        if ( self::child_override(__FUNCTION__, func_get_args() ) ) return self::child_override(__FUNCTION__, func_get_args() ); // Child overide

        $sub = '';
        $len = 0;

        foreach ( explode( ' ', $str ) as $word ) {
            $part = (($sub != '') ? ' ' : '') . $word;
            $sub .= $part;
            $len += strlen( $part );

            if ( strlen( $word ) > $minword && strlen( $sub ) >= $length ) {
                break;
            }
        }

        return $sub . (($len < strlen( $str )) ? '...' : '');
    }

    /**
     * Verify if visitor is using Internet Explorer
     * 
     * @return array
     */
    static function detect_ie() {
        if ( self::child_override(__FUNCTION__, func_get_args() ) ) return self::child_override(__FUNCTION__, func_get_args() ); // Child overide

        $browsers = array();
        $ie6 = strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE 6.0' );
        $ie7 = strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE 7.0' );
        $ie8 = strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE 8.0' );
        if ( $ie6 !== false ) {
            $browsers['ie6'] = 'true';
        } else if ( $ie7 !== false ) {
            $browsers['ie7'] = 'true';
        } else if ( $ie8 !== false ) {
            $browsers['ie8'] = 'true';
        }

        return $browsers;
    }

    /**
     * Check the current post for the existence of a short code
     * 
     * @param string $shortcode
     * @return boolean
     */
    static function has_shortcode( $shortcode = '' ) {
        if ( self::child_override(__FUNCTION__, func_get_args() ) ) return self::child_override(__FUNCTION__, func_get_args() ); // Child overide

        $post_to_check = get_post( get_the_ID() );

        // false because we have to search through the post content first
        $found = false;

        // if no short code was provided, return false
        if ( !$shortcode ) {
            return $found;
        }
        // check the post content for the short code
        if ( stripos( $post_to_check->post_content, '[' . $shortcode ) !== false ) {
            // we have found the short code
            $found = true;
        }

        // return our final results
        return $found;
    }

    /**
     * Adjust brightness by changing Hex value
     * 
     * @param string $hex
     * @param string $steps
     * @return string
     */
    static function adjust_color_brightness( $hex, $steps ) {
        if ( self::child_override(__FUNCTION__, func_get_args() ) ) return self::child_override(__FUNCTION__, func_get_args() ); // Child overide

        // Steps should be between -255 and 255. Negative = darker, positive = lighter
        $steps = max( -255, min( 255, $steps ) );

        // Format the hex color string
        $hex = str_replace( '#', '', $hex );
        if ( strlen( $hex ) == 3 ) {
            $hex = str_repeat( substr( $hex, 0, 1 ), 2 ) . str_repeat( substr( $hex, 1, 1 ), 2 ) . str_repeat( substr( $hex, 2, 1 ), 2 );
        }

        // Get decimal values
        $r = hexdec( substr( $hex, 0, 2 ) );
        $g = hexdec( substr( $hex, 2, 2 ) );
        $b = hexdec( substr( $hex, 4, 2 ) );

        // Adjust number of steps and keep it inside 0 to 255
        $r = max( 0, min( 255, $r + $steps ) );
        $g = max( 0, min( 255, $g + $steps ) );
        $b = max( 0, min( 255, $b + $steps ) );

        $r_hex = str_pad( dechex( $r ), 2, '0', STR_PAD_LEFT );
        $g_hex = str_pad( dechex( $g ), 2, '0', STR_PAD_LEFT );
        $b_hex = str_pad( dechex( $b ), 2, '0', STR_PAD_LEFT );

        return '#' . $r_hex . $g_hex . $b_hex;
    }

    /**
     * Convert HEX color to RGB
     * based on http://bavotasan.com/2011/convert-hex-color-to-rgb-using-php/
     * 
     * @param string $hex Hex value of the color
     * @param decimal $transparency Decimal value of transparency
     * @param array|string $return Return color as array or CSS string
     * @return mixed Return value depends on parameter $return
     */
    static function hex2rgb( $hex, $transparency, $return ) {
        if ( self::child_override(__FUNCTION__, func_get_args() ) ) return self::child_override(__FUNCTION__, func_get_args() ); // Child overide

        $hex = str_replace( "#", "", $hex );
        $transparency = empty( $transparency ) ? 1 : $transparency;

        if ( strlen( $hex ) == 3 ) {
            $r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
            $g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
            $b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
        } else {
            $r = hexdec( substr( $hex, 0, 2 ) );
            $g = hexdec( substr( $hex, 2, 2 ) );
            $b = hexdec( substr( $hex, 4, 2 ) );
        }

        $rgb = array( $r, $g, $b, $transparency );

        if ( empty( $return ) || $return == 'array' ) {
            return $rgb; // returns an array with the rgb values
        } else {
            $rgb_string = "rgba(" . implode( ",", $rgb ) . ");";
            return $rgb_string; // returns the rgb values separated by commas
        }
    }

    /**
     * Convert RGB color to HEX
     * http://bavotasan.com/2011/convert-hex-color-to-rgb-using-php/* 
     * 
     * @param string $rgb
     * @return string
     */
    static function rgb2hex( $rgb ) {
        if ( self::child_override(__FUNCTION__, func_get_args() ) ) return self::child_override(__FUNCTION__, func_get_args() ); // Child overide

        $hex = "#";
        $hex .= str_pad( dechex( $rgb[0] ), 2, "0", STR_PAD_LEFT );
        $hex .= str_pad( dechex( $rgb[1] ), 2, "0", STR_PAD_LEFT );
        $hex .= str_pad( dechex( $rgb[2] ), 2, "0", STR_PAD_LEFT );

        return $hex; // returns the hex value including the number sign (#)
    }

    /**
     * Get top level menu items
     * 
     * @param string $location_id
     * @return boolean
     */
    static function get_top_level_menu_items( $location_id = 'primary' ) {
        if ( self::child_override(__FUNCTION__, func_get_args() ) ) return self::child_override(__FUNCTION__, func_get_args() ); // Child overide

        $top_level_nav = array();
        $locations = get_registered_nav_menus();
        $menus = wp_get_nav_menus();
        $menu_locations = get_nav_menu_locations();

        if ( isset( $menu_locations[$location_id] ) ) {
            foreach ( $menus as $menu ) {
                // If the ID of this menu is the ID associated with the location we're searching for
                if ( $menu->term_id == $menu_locations[$location_id] ) {
                    // This is the correct menu
                    // Get the items for this menu 
                    $menu_items = wp_get_nav_menu_items( $menu );
                    break;
                }
            }

            if ( isset( $menu_items ) ) {
                foreach ( $menu_items as $menu_item ) {
                    if ( !$menu_item->menu_item_parent ) {
                        $top_level_nav[$menu_item->ID] = $menu_item->title;
                    }
                }
            }
            return $top_level_nav;
        } else {
            return false;
        }
    }

    /**
     * List of available animations in animate.css
     * 
     * @return array
     */
    static function animate_css_animations() {
        if ( self::child_override(__FUNCTION__, func_get_args() ) ) return self::child_override(__FUNCTION__, func_get_args() ); // Child overide

        $animations = array(
            'disabled' => 'Animation disabled',
            'bounce' => "Bounce",
            'flash' => "Flash",
            'pulse' => "Pulse",
            'rubberBand' => "Rubber Band",
            'shake' => "Shake",
            'swing' => "Swing",
            'tada' => "Tada",
            'wobble' => "Wobble",
            'bounceIn' => "Bounce In",
            'bounceInDown' => "Bounce In Down",
            'bounceInLeft' => "Bounce In Left",
            'bounceInRight' => "Bounce In Right",
            'bounceInUp' => "Bounce In Up",
            'bounceOut' => "Bounce Out",
            'bounceOutDown' => "Bounce Out Down",
            'bounceOutLeft' => "Bounce Out Left",
            'bounceOutRight' => "Bounce Out Right",
            'bounceOutUp' => "Bounce Out Up",
            'fadeIn' => "Fade In",
            'fadeInDown' => "Fade In Down",
            'fadeInDownBig' => "Fade In Down Big",
            'fadeInLeft' => "Fade In Left",
            'fadeInLeftBig' => "Fade In Left Big",
            'fadeInRight' => "Fade In Right",
            'fadeInRightBig' => "Fade In Right Big",
            'fadeInUp' => "Fade In Up",
            'fadeInUpBig' => "Fade In Up Big",
            'fadeOut' => "Fade Out",
            'fadeOutDown' => "Fade Out Down",
            'fadeOutDownBig' => "Fade Out Down Big",
            'fadeOutLeft' => "Fade Out Left",
            'fadeOutLeftBig' => "Fade Out Left Big",
            'fadeOutRight' => "Fade Out Right",
            'fadeOutRightBig' => "Fade Out Right Big",
            'fadeOutUp' => "Fade Out Up",
            'fadeOutUpBig' => "Fade Out Up Big",
            'flip' => "Flip",
            'flipInX' => "Flip In-X",
            'flipInY' => "Flip In-Y",
            'flipOutX' => "Flip Out-X",
            'flipOutY' => "Flip Out-Y",
            'lightSpeedIn' => "Light Speed In",
            'lightSpeedOut' => "Light Speed Out",
            'rotateIn' => "Rotate In",
            'rotateInDownLeft' => "Rotate In Down Left",
            'rotateInDownRight' => "Rotate In Down Right",
            'rotateInUpLeft' => "Rotate In Up Left",
            'rotateInUpRight' => "Rotate In Up Right",
            'rotateOut' => "Rotate Out",
            'rotateOutDownLeft' => "Rotate Out Down Left",
            'rotateOutDownRight' => "Rotate Out Down Right",
            'rotateOutUpLeft' => "Rotate Out Up Left",
            'rotateOutUpRight' => "Rotate Out Up Right",
            'slideInDown' => "Slide In Down",
            'slideInLeft' => "Slide In Left",
            'slideInRight' => "Slide In Right",
            'slideOutLeft' => "Slide Out Left",
            'slideOutRight' => "Slide Out Right",
            'slideOutUp' => "Slide Out Up",
            'hinge' => "Hinge",
            'rollIn' => "Roll In",
            'rollOut' => "Roll Out"
        );

        return $animations;
    }

    /**
     * Connect fs
     * @param  $url     
     * @param  $method  
     * @param  $context 
     * @param  $fields  
     * @return bool
     */
    static function connect_fs( $url, $method, $context, $fields = null ) {
        if ( self::child_override(__FUNCTION__, func_get_args() ) ) return self::child_override(__FUNCTION__, func_get_args() ); // Child overide

        global $wp_filesystem;

        if ( false === ($credentials = request_filesystem_credentials( $url, $method, false, $context, $fields )) ) {
            return false;
        }

        //check if credentials are correct or not.
        if ( !WP_Filesystem( $credentials ) ) {
            request_filesystem_credentials( $url, $method, true, $context );
            return false;
        }

        return true;
    }

    /**
     * Simple Share Buttons Added 
     * list of social networks
     * 
     * @return array
     */
    static function simple_share_buttons_list() {
        if ( self::child_override(__FUNCTION__, func_get_args() ) ) return self::child_override(__FUNCTION__, func_get_args() ); // Child overide

        return array(
            'buffer' => 'Buffer',
            'diggit' => 'Diggit',
            'email' => 'Email',
            'facebook' => 'Facebook',
            'flattr' => 'Flattr',
            'google' => 'Google',
            'linkedin' => 'LinkedIn',
            'pinterest' => 'Pinterest',
            'print' => 'Print',
            'reddit' => 'Reddit',
            'stumbleupon' => 'Stumbleupon',
            'tumblr' => 'Tumblr',
            'twitter' => 'Twitter'
        );
    }

    /**
     * Get attachment details by attachment id
     * 
     * @param string $attachment_id
     * @return array
     */
    static function get_attachment( $attachment_id ) {
        if ( self::child_override(__FUNCTION__, func_get_args() ) ) return self::child_override(__FUNCTION__, func_get_args() ); // Child overide

        $attachment = get_post( $attachment_id );

        if ( !empty( $attachment ) ) {
            return array(
                'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
                'caption' => $attachment->post_excerpt,
                'description' => $attachment->post_content,
                'href' => get_permalink( $attachment->ID ),
                'src' => $attachment->guid,
                'title' => $attachment->post_title
            );
        } else {
            return false;
        }
    }

    /**
     * Helpers function that searches for needle at the start of haystack 
     * 
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    static function starts_with( $haystack, $needle ) {
        if ( self::child_override(__FUNCTION__, func_get_args() ) ) return self::child_override(__FUNCTION__, func_get_args() ); // Child overide

        // search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos( $haystack, $needle, -strlen( $haystack ) ) !== FALSE;
    }

    /**
     * Helpers function that searches for needle at the end of haystack 
     * 
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    static function ends_with( $haystack, $needle ) {
        if ( self::child_override(__FUNCTION__, func_get_args() ) ) return self::child_override(__FUNCTION__, func_get_args() ); // Child overide

        // search forward starting from end minus needle length characters
        return $needle === "" || (($temp = strlen( $haystack ) - strlen( $needle )) >= 0 && strpos( $haystack, $needle, $temp ) !== FALSE);
    }

    /**
     * Prefix each javascript file url with url to the theme root directory
     * 
     * @param string $url
     * @return string
     */
    static function enqueue_js_prefix( $url ) {
        if ( self::child_override(__FUNCTION__, func_get_args() ) ) return self::child_override(__FUNCTION__, func_get_args() ); // Child overide

        if ( !filter_var( $url, FILTER_VALIDATE_URL ) ) {
            $js_dir = VOLCANNO_TEMPLATEURL . '/';

            if ( is_array( $url ) ) {
                $url[0] = $js_dir . $url[0];
            } else {
                $url = $js_dir . $url;
            }
        }

        return $url;
    }

    /**
     * Prefix each css file url with url to the theme root directory
     * 
     * @param string $url
     * @return string
     */
    static function enqueue_css_prefix( $url ) {
        if ( self::child_override(__FUNCTION__, func_get_args() ) ) return self::child_override(__FUNCTION__, func_get_args() ); // Child overide

        if ( !filter_var( $url, FILTER_VALIDATE_URL ) ) {
            $css_dir = VOLCANNO_TEMPLATEURL . '/';

            if ( is_array( $url ) ) {
                $url[0] = $css_dir . $url[0];
            } else {
                $url = $css_dir . $url;
            }
        }

        return $url;
    }

    /**
     * Return all registered sidebars.
     * 
     * @return array
     */
    static function get_registered_sidebars() {
        if ( self::child_override(__FUNCTION__, func_get_args() ) ) return self::child_override(__FUNCTION__, func_get_args() ); // Child overide

        $sidebar_options = array();
        $sidebars = $GLOBALS['wp_registered_sidebars'];

        foreach ( $sidebars as $sidebar ) {
            $sidebar_options[$sidebar['id']] = $sidebar['name'];
        }

        return $sidebar_options;
    }

}
