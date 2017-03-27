<?php
/**
 * Visual composer mapping
 */
add_action( 'vc_before_init', 'volcanno_counter_integrateWithVC' );
if ( !function_exists('volcanno_counter_integrateWithVC') ) {
   function volcanno_counter_integrateWithVC() {
      vc_map( array(
         "name" => esc_html__( "Counter", 'consultingpress' ),
         "base" => "volcanno_counter",
         "class" => "",
         "icon" => get_template_directory_uri() . "/includes/composer-elements/assets/icon-volcanno.png",
         "category" => esc_html__( "Consulting Press", 'consultingpress'),
         "params" => array(
            Volcanno_Visual_Composer::icons_lib(),
            Volcanno_Visual_Composer::icons_param('fontawesome'),
            Volcanno_Visual_Composer::icons_param('lynny'),
            array(
               "type" => "colorpicker",
               "class" => "",
               "heading" => esc_html__( "Icon color", 'consultingpress' ),
               "param_name" => "icon_color",
               "value" => "",
               "description" => esc_html__( "By default is theme color.", 'consultingpress' ),
            ),
            array(
               "type" => "textfield",
               "edit_field_class" => "vc_col-xs-6",
               "heading" => esc_html__( "Number", 'consultingpress' ),
               "param_name" => "number",
               "value" => esc_html__( "27045", 'consultingpress' ),
               "description" => esc_html__( "You can only enter counter number.", 'consultingpress' )
            ),
            array(
               "type" => "textfield",
               "edit_field_class" => "vc_col-xs-6",
               "heading" => esc_html__( "Number mark", 'consultingpress' ),
               "param_name" => "number_mark",
               "value" => "",
               "description" => ""
            ),
            array(
               "type" => "colorpicker",
               "edit_field_class" => "vc_col-xs-6",
               "heading" => esc_html__( "Number color", 'consultingpress' ),
               "param_name" => "number_color",
               "value" => "",
               "description" => esc_html__( "By default is theme color.", 'consultingpress' ),
            ),
            array(
               "type" => "colorpicker",
               "edit_field_class" => "vc_col-xs-6",
               "heading" => esc_html__( "Number mark color", 'consultingpress' ),
               "param_name" => "number_mark_color",
               "value" => "",
               "description" => esc_html__( "By default is theme color.", 'consultingpress' ),
            ),
            array(
               "type" => "textfield",
               "admin_label" => true,
               "class" => "",
               "heading" => esc_html__( "Title", 'consultingpress' ),
               "param_name" => "title",
               "value" => "",
               "description" => esc_html__( "Here you can enter counter title", 'consultingpress' )
            ),
            array(
               "type" => "colorpicker",
               "class" => "",
               "heading" => esc_html__( "Title color", 'consultingpress' ),
               "param_name" => "title_color",
               "value" => "#071740",
               "description" => "",
            ),
            array(
               "type" => "textfield",
               "class" => "",
               "heading" => esc_html__( "Speed", 'consultingpress' ),
               "param_name" => "speed",
               "value" => esc_html__( "1500", 'consultingpress' ),
               "description" => esc_html__( "Here you can enter counter speed", 'consultingpress' )
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
if ( !function_exists('volcanno_counter_func') ) {
   function volcanno_counter_func( $atts ) {
      extract(shortcode_atts( array(
         'icon_type' => 'icon_fontawesome',
         'icon_fontawesome' => '',
         'icon_lynny' => '',
         'icon_color' => '',
         'number' => '27045',
         'number_mark' => '',
         'number_color' => '',
         'number_mark_color' => '',
         'title' => '',
         'title_color' => '',
         'speed' => '1500',
         'design_param' => '',
         'animation_param' => '',
         'extra_class_param' => '',
      ), $atts ));

      // Animation, Design & Extra Class params
      $css_class = Volcanno_Visual_Composer::return_css_class( $design_param, $animation_param, $extra_class_param);
      $data_animate = !empty( $animation_param ) ? ' data-animate="' . $animation_param . '"' : '';

      // Load script and styles
      wp_enqueue_style( 'odometer' );
      wp_enqueue_script( 'odometer' );
      wp_enqueue_script( 'odometer-initialize', VOLCANNO_TEMPLATEURL . '/includes/composer-elements/counter/assets/initialize.js', '', '', true);

      // Icons
      $icon = $$icon_type;
      $icon_color = !empty( $icon_color ) ? ' style="color:' . $icon_color . '"' : '';
      $icon = !empty( $icon ) ? '<i class="' . $icon . '"' . $icon_color . '></i>' : '';

      // Title
      $title_color = !empty( $title_color ) ? ' style="color:' . $title_color . '"' : '';
      $title = $title ? '<p' . $title_color . '>' . $title . '</p>' : '';

      // Number color
      $number_color = !empty( $number_color ) ? ' style="color:' . $number_color . '"' : '';

      // Number mark color
      $number_mark_color = !empty( $number_mark_color ) ? ' style="color:' . $number_mark_color . '"' : '';

      // Number mark
      $number_mark = !empty( $number_mark ) ? '<span class="digit-mark"' . $number_mark_color . '>' . $number_mark . '</span>' : '';

      //$number = preg_replace('[\D]', '', $number);

      $output =   '<div class="odometer-container' . $css_class . '"' . $data_animate . '>
                      <div class="odometer-inner">
                           ' . $icon . '
                           <div class="odometer" data-to="' . $number . '" data-speed="' . $speed . '"' . $number_color . '></div>
                           ' . $number_mark . '
                      </div>
                      ' . $title . '
                  </div>';

      return $output;
   }
}