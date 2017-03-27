<?php
if ( !function_exists('volcanno_add_accordion_style_type') ) {
   
   function volcanno_add_accordion_style_type() {

      $param = WPBMap::getParam( 'vc_tta_accordion', 'style' );
      $param['value'][__( 'Consulting Press - Default', 'consultingpress' )] = 'consulting-press-default';
      $param['value'][__( 'Consulting Press - Boxed', 'consultingpress' )] = 'consulting-press-boxed';
      vc_update_shortcode_param( 'vc_tta_accordion', $param );

      // SHAPE
      $param = WPBMap::getParam( 'vc_tta_accordion', 'shape' );
      $param['dependency']['element'] = 'style';
      $param['dependency']['value'] = array('classic', 'modern', 'flat', 'outline');
      vc_update_shortcode_param( 'vc_tta_accordion', $param );

      // Color
      $param = WPBMap::getParam( 'vc_tta_accordion', 'color' );
      $param['type'] = 'colorpicker';
      //$param['value'][__( 'Consulting Press - Default', 'consultingpress' )] = 'consulting-press-default';
      //$param['dependency']['element'] = 'style';
      //$param['dependency']['value'] = array('classic', 'modern', 'flat', 'outline');
      vc_update_shortcode_param( 'vc_tta_accordion', $param );

      // Spacing
      $param = WPBMap::getParam( 'vc_tta_accordion', 'spacing' );
      $param['dependency']['element'] = 'style';
      $param['dependency']['value'] = array('classic', 'modern', 'flat', 'outline');
      vc_update_shortcode_param( 'vc_tta_accordion', $param );

      // Gap
      $param = WPBMap::getParam( 'vc_tta_accordion', 'gap' );
      $param['dependency']['element'] = 'style';
      $param['dependency']['value'] = array('classic', 'modern', 'flat', 'outline');
      vc_update_shortcode_param( 'vc_tta_accordion', $param );

      // Alignment
      $param = WPBMap::getParam( 'vc_tta_accordion', 'c_align' );
      $param['dependency']['element'] = 'style';
      $param['dependency']['value'] = array('classic', 'modern', 'flat', 'outline');
      vc_update_shortcode_param( 'vc_tta_accordion', $param );

      // Icon position
      $param = WPBMap::getParam( 'vc_tta_accordion', 'c_position' );
      $param['dependency']['element'] = 'style';
      $param['dependency']['value'] = array('classic', 'modern', 'flat', 'outline');
      vc_update_shortcode_param( 'vc_tta_accordion', $param );

      // Add dark background
      $attributes = array(
            "type" => "checkbox",
            "heading" => esc_html__( "White text", 'consultingpress' ),
            "param_name" => "white_text",
            "description" => esc_html__( 'Enable this if you have dark background.', 'consultingpress' ),
            "value" => "",
            "dependency" => array(
                  'element' => 'style',
                  'value' => array( 'consulting-press-default', 'consulting-press-boxed')
            ),
      );
      vc_add_param( 'vc_tta_accordion', $attributes );

   }

}

add_action( 'vc_after_init', 'volcanno_add_accordion_style_type' );

/**
 * Add custom class to Visual Composer accordion element
 * 
 * @param  array $classes
 * @param  array $atts
 * @return array
 */
function volcanno_custom_vc_tta_accordion_general_classes( $classes, $atts ) {

      if ( !empty( $atts['white_text'] ) ) {
            $classes[] = 'dark';
      }

      return $classes;
}

add_filter( 'vc_tta_accordion_general_classes', 'volcanno_custom_vc_tta_accordion_general_classes', 10, 2 );