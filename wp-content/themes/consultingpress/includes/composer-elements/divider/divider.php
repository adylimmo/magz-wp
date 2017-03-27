<?php
/**
 * Visual composer mapping
 */
add_action( 'vc_before_init', 'volcanno_divider_integrateWithVC' );
if ( !function_exists('volcanno_divider_integrateWithVC') ) {
   function volcanno_divider_integrateWithVC() {
      vc_map( array(
         "name" => esc_html__( "Dividers", 'consultingpress' ),
         "base" => "volcanno_divider",
         "class" => "",
         "icon" => get_template_directory_uri() . "/includes/composer-elements/assets/icon-volcanno.png",
         "category" => esc_html__( "Consulting Press", 'consultingpress'),
         "params" => array(
            array(
               "type" => "dropdown",
               "class" => "",
               "heading" => esc_html__( "Divider style", 'consultingpress' ),
               "param_name" => "divider_style",
               "description" => esc_html__( "Here you can choose heading style.", 'consultingpress' ),
               "value" => array(
                  'Dots' => 'divider',
                  'Simple Line' => 'divider-line',
                  'Dotted' => 'divider-dotted',
                  'Custom icon' => 'divider-icon', // Get icon if
                  'To top' => 'divider-scroll-up',
               ),
               "std" => 'custom-heading-01',
            ),
            Volcanno_Visual_Composer::icons_lib('icon_type', array(
                  'element' => 'divider_style',
                  'value' => 'divider-icon',
               )
            ),
            Volcanno_Visual_Composer::icons_param('fontawesome'),
            Volcanno_Visual_Composer::icons_param('lynny'),
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
if ( !function_exists('volcanno_divider_func') ) {
   function volcanno_divider_func( $atts, $content = '' ) {
      extract(shortcode_atts( array(
         'divider_style' => 'divider',
         'icon_type' => 'icon_fontawesome',
         'icon_fontawesome' => '',
         'icon_lynny' => '',
         'design_param' => '',
         'animation_param' => '',
         'extra_class_param' => '',
      ), $atts ));

      // Animation, Design & Extra Class params
      $css_class = Volcanno_Visual_Composer::return_css_class( $design_param, $animation_param, $extra_class_param);
      $data_animate = !empty( $animation_param ) ? ' data-animate="' . $animation_param . '"' : '';

      if ( $divider_style == 'divider-scroll-up' ) {
         $divider_content = '<a href="#" class="scroll-up" style="display: table;"><i class="fa fa-chevron-up"></i></a>';
      } else if ( $divider_style == 'divider-icon' ) {
         $icon = $$icon_type;
         $divider_content = '<i class="' . $icon . '"></i>';
      } else {
         $divider_content = '';
      }

      $output = '<div class="' . $divider_style . $css_class . '"' . $data_animate . '>' . $divider_content . '</div>';

      return $output;

   }
}