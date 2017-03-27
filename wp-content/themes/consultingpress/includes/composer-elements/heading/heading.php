<?php
/**
 * Visual composer mapping
 */
add_action( 'vc_before_init', 'volcanno_heading_integrateWithVC' );
if ( !function_exists('volcanno_heading_integrateWithVC') ) {
   function volcanno_heading_integrateWithVC() {
      vc_map( array(
         "name" => esc_html__( "Heading", 'consultingpress' ),
         "base" => "volcanno_heading",
         "class" => "",
         "icon" => get_template_directory_uri() . "/includes/composer-elements/assets/icon-volcanno.png",
         "category" => esc_html__( "Consulting Press", 'consultingpress'),
         "params" => array(
            array(
               "type" => "dropdown",
               "class" => "",
               "heading" => esc_html__( "Heading style", 'consultingpress' ),
               "param_name" => "heading_style",
               "description" => esc_html__( "Here you can choose heading style.", 'consultingpress' ),
               "value" => array(
                  'Top subtitle' => 'custom-heading-01',
                  'Centered' => 'custom-heading-02',
                  'Top line' => 'custom-heading-03'
               ),
               "std" => 'custom-heading-01',
            ),
            array(
               "type" => "textarea",
               "class" => "",
               "heading" => esc_html__( "Title", 'consultingpress' ),
               "param_name" => "title",
               "value" => "",
               "description" => esc_html__( "Here you can enter main title.", 'consultingpress' ),
               "admin_label" => true,
            ),
            array(
               "type" => "dropdown",
               "class" => "",
               "heading" => esc_html__( "Title size", 'consultingpress' ),
               "param_name" => "title_size",
               "description" => esc_html__( "Here you can choose main title size", 'consultingpress' ),
               "value" => array(
                  'H1' => 'h1',
                  'H2' => 'h2',
                  'H3' => 'h3',
                  'H4' => 'h4',
                  'H5' => 'h5',
                  'H6' => 'h6'
               ),
               "std" => 'h2',
            ),
            array(
               "type" => "textfield",
               "class" => "",
               "heading" => esc_html__( "Subtitle", 'consultingpress' ),
               "param_name" => "subtitle",
               "value" => "",
               "description" => esc_html__( "Here you can enter subtitle.", 'consultingpress' ),
               "dependency" => array(
                  'element' => 'heading_style',
                  'value' => array('custom-heading-01', 'custom-heading-02'),
               ),
            ),
            array(
               "type" => "dropdown",
               "class" => "",
               "heading" => esc_html__( "Color Style", 'consultingpress' ),
               "param_name" => "style",
               "description" => esc_html__( "Here you can edit heading color style", 'consultingpress' ),
               "value" => array(
                  'Default' => 'default',
                  'Custom' => 'custom' 
               ),
            ),
            array(
               "type" => "colorpicker",
               "class" => "",
               "heading" => esc_html__( "Main title color", 'consultingpress' ),
               "param_name" => "title_color",
               "value" => '#232020', //Default color
               "description" => esc_html__( "Choose main title color", 'consultingpress' ),
               "dependency" => array(
                  'element' => 'style',
                  'value' => 'custom'
               ),
            ),
            array(
               "type" => "colorpicker",
               "class" => "",
               "heading" => esc_html__( "Subtitle color", 'consultingpress' ),
               "param_name" => "subtitle_color",
               "value" => '#aaaaaa', //Default color
               "description" => esc_html__( "Choose subtitle color", 'consultingpress' ),
               "dependency" => array(
                  'element' => 'style',
                  'value' => 'custom'
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
if ( !function_exists('volcanno_heading_func') ) {
   function volcanno_heading_func( $atts ) {
      extract(shortcode_atts( array(
         'title' => '',
         'title_size' => 'h2',
         'subtitle' => '',
         'style' => '',
         'title_color' => '',
         'subtitle_color' => '',
         'heading_style' => 'custom-heading-01',
         'design_param' => '',
         'animation_param' => '',
         'extra_class_param' => '',
      ), $atts ));

      // Animation, Design & Extra Class params
      $css_class = Volcanno_Visual_Composer::return_css_class( $design_param, $animation_param, $extra_class_param);
      $data_animate = !empty( $animation_param ) ? ' data-animate="' . $animation_param . '"' : '';

      // Color style
      if ( $style == 'custom') {
         $title_color = $title_color ? ' style="color:' . $title_color . ';"' : '';
         $subtitle_color = $subtitle_color ? ' style="color:' . $subtitle_color . ';"' : '';
      } else {
         $title_color = '';
         $subtitle_color = '';
      }

      $title = $title ? '<' . $title_size . $title_color . '>' . $title . '</' . $title_size . '>' : '';
      $subtitle = $subtitle ? '<span' . $subtitle_color . '>' . $subtitle . '</span>' : '';

      if ( $heading_style == 'custom-heading-02' ) {
         $content = $title . $subtitle;
      } else {
         $content = $subtitle . $title;
      }

      $output = '<div class="custom-heading ' . $heading_style . $css_class . '"' . $data_animate . '>' . $content . '</div>';

      return $output;
   }
}