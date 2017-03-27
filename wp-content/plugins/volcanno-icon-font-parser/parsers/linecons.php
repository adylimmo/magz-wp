<?php

/**
 * Linecons file parser
 */
class VIFP_Linecons {

    /**
     * Parse Linecons string and extract classes.
     * 
     * @param string $css String with all classes
     * @param string $auto_load Is request autoloading or user requested
     */
    static function parse_css( $css ) {

        // parse the content and store in array
        if ( !empty( $css ) ) {
            $pattern = '/\.(linecons-(?:\w+(?:-)?)+):before\s+{\s*content:\s*"(.+)";\s+}/';

            preg_match_all( $pattern, $css, $matches, PREG_SET_ORDER );

            $icons = array();
            foreach ( $matches as $match ) {
                $icons[$match[1]] = $match[1];
            }

            $page_url = esc_url( admin_url( 'admin.php?page=vifp_icon_font_parser' ) );

            echo "<div class='updated'>" . esc_html__( 'Parsing Linecons successful.', 'volcanno-icon-font-parser' ) . "</div>";
        } else {
            $icons = array( 'Icons not parsed' );

            echo "<div class='error'>" . esc_html__( 'Error while parsing Linecons, please try again!', 'volcanno-icon-font-parser' ) . "</div>";
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
        $icons = get_option( 'volcanno_linecons_list' );
        $icons = apply_filters( 'volcanno_linecons_list', $icons );

        // reset array - for testing
        //$icons = null;

        return $icons;
    }

}
