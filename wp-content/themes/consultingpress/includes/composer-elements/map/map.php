<?php

/**
 * Visual composer mapping
 */
add_action( 'vc_before_init', 'volcanno_map_integrateWithVC' );
if ( !function_exists( 'volcanno_map_integrateWithVC' ) ) {

    function volcanno_map_integrateWithVC() {
        vc_map( array(
            "name" => esc_html__( "Map", 'consultingpress' ),
            "base" => "volcanno_map",
            "class" => "",
            "icon" => get_template_directory_uri() . "/includes/composer-elements/assets/icon-volcanno.png",
            "category" => esc_html__( "Consulting Press", 'consultingpress' ),
            "params" => array(
                array(
                    "type" => "textfield",
                    "class" => "",
                    "heading" => esc_html__( "Api KEY", 'consultingpress' ),
                    "param_name" => "api_key",
                    "value" => "",
                    "description" => sprintf( esc_html__( 'You can get key on: %s', 'consultingpress' ), '<a href="https://developers.google.com/maps/documentation/javascript/" target="_blank">Maps JavaScript API</a>' )
                ),
                array(
                    "type" => "textfield",
                    "class" => "",
                    "edit_field_class" => "vc_col-xs-6",
                    "heading" => esc_html__( "Latitude", 'consultingpress' ),
                    "param_name" => "lat",
                    "value" => esc_html__( "36.1699412", 'consultingpress' ),
                    "description" => ""
                ),
                array(
                    "type" => "textfield",
                    "class" => "",
                    "edit_field_class" => "vc_col-xs-6",
                    "heading" => esc_html__( "Longitude", 'consultingpress' ),
                    "param_name" => "lng",
                    "value" => esc_html__( "-115.13982959999998", 'consultingpress' ),
                    "description" => ""
                ),
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "heading" => esc_html__( "Map style", 'consultingpress' ),
                    "param_name" => "contrast",
                    "description" => esc_html__( "Enable or disable scrool on map.", 'consultingpress' ),
                    "value" => array(
                        'Default' => 'default',
                        'Consulting Press' => 'volcanno',
                        'Grayscale' => 'grayscale',
                        'Saturated' => 'saturated',
                    ),
                    'std' => 'volcanno',
                ),
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "heading" => esc_html__( "Map type", 'consultingpress' ),
                    "param_name" => "map_type",
                    "description" => "",
                    "value" => array(
                        'Satellite' => 'satellite',
                        'Roadmap' => 'roadmap',
                        'Hybrid' => 'hybrid',
                        'Terrain' => 'roadmap',
                    ),
                    'std' => 'satellite',
                ),
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "heading" => esc_html__( "Markers", 'consultingpress' ),
                    "param_name" => "markers_type",
                    "description" => "",
                    "value" => array(
                        'Single marker' => 'single',
                        'Multiple markers' => 'multiple',
                    ),
                ),
                array(
                    'type' => 'param_group',
                    'value' => '',
                    'heading' => esc_html__( "Multiple markers", 'consultingpress' ),
                    'param_name' => 'markers_group',
                    'dependency' => array(
                        'element' => 'markers_type',
                        'value' => 'multiple',
                    ),
                    'params' => array(
                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__( "Latitude", 'consultingpress' ),
                            "param_name" => "lat",
                            "value" => esc_html__( "36.1699412", 'consultingpress' ),
                            "description" => "",
                            "admin_label" => true,
                        ),
                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__( "Longitude", 'consultingpress' ),
                            "param_name" => "lng",
                            "value" => esc_html__( "-115.13982959999998", 'consultingpress' ),
                            "description" => "",
                            "admin_label" => true,
                        ),
                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__( "Info text", 'consultingpress' ),
                            "param_name" => "info_text",
                            "value" => "",
                            "description" => esc_html__( "Here you can write info text that will apear on marker click.", 'consultingpress' ),
                            "admin_label" => true
                        ),
                    ),
                ),
                array(
                    "type" => "textfield",
                    "class" => "",
                    "heading" => esc_html__( "Zoom", 'consultingpress' ),
                    "param_name" => "zoom",
                    "value" => esc_html__( "8", 'consultingpress' ),
                    "description" => esc_html__( "Here you can set zoom level.", 'consultingpress' )
                ),
                array(
                    "type" => "textfield",
                    "class" => "",
                    "heading" => esc_html__( "Height", 'consultingpress' ),
                    "param_name" => "height",
                    "value" => esc_html__( "450", 'consultingpress' ),
                    "description" => esc_html__( "Here you can set map height in px.", 'consultingpress' )
                ),
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "heading" => esc_html__( "Scrool", 'consultingpress' ),
                    "param_name" => "scrool",
                    "description" => esc_html__( "Enable or disable scrool on map.", 'consultingpress' ),
                    "value" => array(
                        'Enable' => '1',
                        'Disable' => '0',
                    ),
                    'std' => '1',
                ),
            )
        ) );
    }

}

/**
 * Wordpress shortcode
 * 
 * @param  $atts
 * @return string
 */
if ( !function_exists( 'volcanno_map_func' ) ) {

    function volcanno_map_func( $atts ) {
        extract( shortcode_atts( array(
            'api_key' => '',
            'lat' => '36.1699412',
            'lng' => '-115.13982959999998',
            'markers_type' => 'single',
            'markers_group' => '', // lat, lng, info_text
            'zoom' => '8',
            'map_type' => 'roadmap',
            'height' => '450',
            'scrool' => '1',
            'contrast' => 'volcanno'
        ), $atts ) );

        if ( !empty( $api_key ) ) {

            // Enqueue scripts
            wp_enqueue_script( 'google-maps-js-api', 'https://maps.googleapis.com/maps/api/js?key=' . $api_key, '', 'null', true );
            wp_enqueue_script( 'google-maps-js-api-initialize', get_template_directory_uri() . '/includes/composer-elements/map/assets/initialize.js', '', '', true );

            if ( $markers_type == 'multiple' ) {
                $locations = '';
                $markers_group = vc_param_group_parse_atts( $markers_group );
                // Return if elements are empty
                if ( !empty( $markers_group ) ) :
                    foreach ( $markers_group as $index => $value ) {
                        $info_text = !empty( $value['info_text'] ) ? $value['info_text'] : '';
                        $lat_g = !empty( $value['lat'] ) ? $value['lat'] : '';
                        $lng_g = !empty( $value['lng'] ) ? $value['lng'] : '';
                        $locations .= '[' . $info_text . '|' . $lat_g . '|' . $lng_g . ']%&';
                    }
                endif;
            }

            $data_attr = $map_type ? 'data-type="' . $map_type . '"' : '';
            $data_attr .= $lng ? ' data-long="' . $lng . '"' : '';
            $data_attr .= $lat ? ' data-lat="' . $lat . '"' : '';
            $data_attr .= $zoom ? ' data-zoom="' . $zoom . '"' : '';
            $data_attr .= $scrool ? ' data-scroll-wheel="' . $scrool . '"' : '';
            $data_attr .= $contrast ? ' data-contrast="' . $contrast . '"' : '';
            $data_attr .= isset( $locations ) ? ' data-locations="' . $locations . '"' : '';

            $output = '<div class="map-canvas" style="height:' . $height . 'px" ' . $data_attr . '></div>';
        } else {
            $output = sprintf( esc_html__( 'Missing Google Maps API key. You can get key on:  %s', 'consultingpress' ), '<a href="https://developers.google.com/maps/documentation/javascript/" target="_blank">Maps JavaScript API</a>' );
        }

        return $output;
    }

} 

