<?php
/**
 * Visual composer mapping
 */
add_action( 'vc_before_init', 'volcanno_raw_code_integrateWithVC' );
if ( !function_exists('volcanno_raw_code_integrateWithVC') ) {
   function volcanno_raw_code_integrateWithVC() {
      vc_map( array(
         "name" => esc_html__( "Raw code", 'consultingpress' ),
         "base" => "volcanno_raw_code",
         "class" => "",
         "icon" => get_template_directory_uri() . "/includes/composer-elements/assets/icon-volcanno.png",
         "category" => esc_html__( "Consulting Press", 'consultingpress'),
         "params" => array(
            array(
               "type" => "textarea_raw_html",
               "class" => "",
               "heading" => esc_html__( "Code", 'consultingpress' ),
               "param_name" => "code",
               "value" => "",
               "description" => esc_html__( "Here you can enter raw code.", 'consultingpress' )
            ),
            Volcanno_Visual_Composer::animation_param(),
            Volcanno_Visual_Composer::extra_class_param(),
            Volcanno_Visual_Composer::design_param(),
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
if ( !function_exists('volcanno_raw_code_func') ) {
   function volcanno_raw_code_func( $atts ) {
      extract(shortcode_atts( array(
         'code' => '',
         'design_param' => '',
         'animation_param' => '',
         'extra_class_param' => '',
      ), $atts ));

      // Animation, Design & Extra Class params
      $css_class = Volcanno_Visual_Composer::return_css_class( $design_param, $animation_param, $extra_class_param, '', true);
      $data_animate = !empty( $animation_param ) ? ' data-animate="' . $animation_param . '"' : '';
      
      $code = Volcanno_Visual_Composer::decode_raw_html( $code );

      $code = !empty( $code ) ? $code : '';

      $output = '<pre' . $css_class . $data_animate . '>' . $code . '</pre>';

      return $output;
   }
}