<?php

/**
 * FontAwesome file parser
 */
class VIFP_Font_Awesome {

    /**
     * Parse Font Awesome string and extract classes.
     * 
     * @param string $css String with all classes
     * @param string $auto_load Is request autoloading or user requested
     */
    static function parse_css( $css ) {

        // parse the content and store in array
        if ( !empty( $css ) ) {
            $pattern = '/\.(fa-(?:\w+(?:-)?)+):before\s+{\s*content:\s*"(.+)";\s+}/';

            preg_match_all( $pattern, $css, $matches, PREG_SET_ORDER );

            $icons = array();
            foreach ( $matches as $match ) {
                $icons[$match[1]] = ucfirst( str_ireplace( array( 'fa-', '-' ), array( '', ' ' ), $match[1] ) );
            }

            echo "<div class='updated'>" . esc_html__( 'Parsing Font Awesome successful.', 'volcanno-icon-font-parser' ) . "</div>";
        } else {
            $icons = array( 'Icons not parsed' );

            echo "<div class='error'>" . esc_html__( 'Error while parsing Font Awesome, please try again!', 'volcanno-icon-font-parser' ) . "</div>";
        }

        return $icons;        
    }

    /**
     * Helper function that returns font classes from database
     *
     * @return array
     */
    static function get_classes() {

        // get icon list from database
        $icons = get_option( 'volcanno_font_awesome_list' );
        $icons = apply_filters( 'volcanno_font_awesome_list', $icons );

        // reset array - for testing
        //$icons = null;

        return $icons;
    }

}
