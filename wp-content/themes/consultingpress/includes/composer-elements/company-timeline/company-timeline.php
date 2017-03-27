<?php
/**
 * Visual composer mapping
 */
add_action( 'vc_before_init', 'volcanno_company_timeline_integrateWithVC' );
if ( !function_exists('volcanno_company_timeline_integrateWithVC') ) {
   function volcanno_company_timeline_integrateWithVC() {
      vc_map( array(
         "name" => esc_html__( "Company timeline", 'consultingpress' ),
         "base" => "volcanno_company_timeline",
         "class" => "",
         "icon" => get_template_directory_uri() . "/includes/composer-elements/assets/icon-volcanno.png",
         "category" => esc_html__( "Consulting Press", 'consultingpress'),
         "params" => array(
            array(
               'type' => 'param_group',
               'value' => '',
               'param_name' => 'param_group',
               'params' => array(
                  Volcanno_Visual_Composer::icons_lib('icon_type', array(), 'icon_fontawesome'),
                  Volcanno_Visual_Composer::icons_param('fontawesome', 'icon_type', 'fa fa-calendar'),
                  Volcanno_Visual_Composer::icons_param('lynny'),
                  array(
                     "type" => "textfield",
                     "holder" => "",
                     "class" => "",
                     "heading" => esc_html__( "Title", 'consultingpress' ),
                     "param_name" => "title",
                     "value" => "",
                     "description" => esc_html__( "Here you can enter list element title.", 'consultingpress' ),
                     "admin_label" => true,
                  ),
                  array(
                     "type" => "textarea",
                     "holder" => "",
                     "class" => "",
                     "heading" => esc_html__( "Text", 'consultingpress' ),
                     "param_name" => "text",
                     "value" => "",
                     "description" => esc_html__( "Here you can enter list element text.", 'consultingpress' ),
                  ),
               ),
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
if ( !function_exists('volcanno_company_timeline_func') ) {
   function volcanno_company_timeline_func( $atts ) {
      extract(shortcode_atts( array(
         'param_group' => '',
         'design_param' => '',
         'animation_param' => '',
         'extra_class_param' => '',
      ), $atts ));

      // Animation, Design & Extra Class params
      $css_class = Volcanno_Visual_Composer::return_css_class( $design_param, $animation_param, $extra_class_param);
      $data_animate = !empty( $animation_param ) ? ' data-animate="' . $animation_param . '"' : '';

      $param_group = vc_param_group_parse_atts( $param_group );

      // Return if elements are empty
      if ( empty( $param_group ) ) return;

      $output = '<ul class="company-timeline clearfix' . $css_class . '"' . $data_animate . '>';

      foreach ($param_group as $index => $list) {

         // Icon
         $icon_lib = isset( $list['icon_type'] ) ? $list['icon_type'] : 'icon_fontawesome';
         $icon = isset( $list[ $icon_lib ] ) ? $list[ $icon_lib ] : '';
         $icon = !empty( $icon ) ? '<div class="icon-date-container"><i class="' . $icon . '"></i></div>' : '';

         $text = isset( $list['text'] ) ? '<p>' . $list['text'] . '</p>' : '';
         $title = isset( $list['title'] ) ? '<h3>' . $list['title'] . '</h3>' : '';

         $output .=  '<li>' . $icon . '
                         <div class="timeline-item-details">' . $title . $text . '</div>
                     </li>';

      }

      $output .= '</ul>';

      return $output;

   }
}