<?php

/**
 * Visual composer mapping
 */
add_action( 'vc_before_init', 'volcanno_button_integrateWithVC' );
if ( !function_exists('volcanno_button_integrateWithVC') ) {
   function volcanno_button_integrateWithVC() {
      vc_map( array(
         "name" => esc_html__( "Button", 'consultingpress' ),
         "base" => "volcanno_button",
         "class" => "",
         "icon" => get_template_directory_uri() . "/includes/composer-elements/assets/icon-volcanno.png",
         "category" => esc_html__( "Consulting Press", 'consultingpress'),
         "admin_label" => esc_html__( "Empire button", 'consultingpress'),
         "params" => array(
            // text
            array(
               "type" => "textfield",
               "holder" => "div",
               "class" => "",
               "heading" => esc_html__( "Text", 'consultingpress' ),
               "param_name" => "text",
               "value" => "",
               "description" => esc_html__( "Here you can enter text that will apear on button.", 'consultingpress' ) 
            ),
            // href
            array(
               "type" => "vc_link",
               "holder" => "",
               "class" => "",
               "heading" => esc_html__( "Url (Link)", 'consultingpress' ),
               "param_name" => "href",
               "value" => "",
               "description" => esc_html__( "Here you can add link to button", 'consultingpress' )
            ),
            // button style
            array(
               "type" => "dropdown",
               "holder" => "",
               "class" => "",
               "heading" => esc_html__( "Style", 'consultingpress' ),
               "param_name" => "button_format_style",
               "description" => esc_html__( "Here you can set button style.", 'consultingpress' ),
               "value" => array(
                  'Default' => 'default',
                  'Link' => 'link',
               ),
            ),
            // alignment
            array(
               "type" => "dropdown",
               "holder" => "",
               "class" => "",
               "heading" => esc_html__( "Alignment", 'consultingpress' ),
               "param_name" => "alignment",
               "description" => esc_html__( "Here you can set button alignment", 'consultingpress' ),
               "value" => array(
                  'Center' => 'btn-center',
                  'Left' => 'btn-left',
                  'Right' => 'btn-right'
               ),
               "dependency" => array(
                  'element' => 'button_format_style',
                  'value' => 'default'
               ),
            ),
            // size
            array(
               "type" => "dropdown",
               "holder" => "",
               "class" => "",
               "heading" => esc_html__( "Size", 'consultingpress' ),
               "param_name" => "size",
               "description" => esc_html__( "Here you can choose button size", 'consultingpress' ),
               "value" => array(
                  'Standard' => '',
                  'Large' => 'btn-large',
                  'Small' => 'btn-small' 
               ),
               "dependency" => array(
                  'element' => 'button_format_style',
                  'value' => 'default'
               ),
            ),
            // color style
            array(
               "type" => "dropdown",
               "holder" => "",
               "class" => "",
               "heading" => esc_html__( "Color Style", 'consultingpress' ),
               "param_name" => "style",
               "description" => esc_html__( "Here you can edit button color style", 'consultingpress' ),
               "value" => array(
                  'Theme color' => 'default',
                  'Standard dark' => 'btn-blue',
                  'Custom' => 'custom' 
               ),
               "dependency" => array(
                  'element' => 'button_format_style',
                  'value' => 'default'
               ),
            ),
            // text_color
            array(
               "type" => "colorpicker",
               "class" => "",
               "heading" => esc_html__( "Button text color", 'consultingpress' ),
               "param_name" => "text_color",
               "value" => '#fff', 
               "description" => esc_html__( "Choose text color", 'consultingpress' ),
               "dependency" => array(
                  'element' => 'style',
                  'value' => 'custom'
               ),
            ),
            // background_color
            array(
               "type" => "colorpicker",
               "class" => "",
               "heading" => esc_html__( "Button background", 'consultingpress' ),
               "param_name" => "background_color",
               "value" => '#071740',
               "description" => esc_html__( "Choose button background color", 'consultingpress' ),
               "dependency" => array(
                  'element' => 'style',
                  'value' => 'custom'
               ),
            ),
            // icon
            array(
               "type" => "checkbox",
               "holder" => "",
               "class" => "",
               "heading" => esc_html__( "Add icon", 'consultingpress' ),
               "param_name" => "icon",
               "value" => "",
               "description" => "",
               "dependency" => array(
                  'element' => 'button_format_style',
                  'value' => 'default'
               ),
            ),
            // icon_type
            Volcanno_Visual_Composer::icons_lib('icon_type', array(
                  'element' => 'icon',
                  'value' => 'true',
               )),
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
if ( !function_exists('volcanno_button_func') ) {
   function volcanno_button_func( $atts ) {
      extract(shortcode_atts( array(
         'text' => '',
         'href' => '',
         'button_format_style' => 'default',
         'alignment' => 'btn-center',
         'size' => '', 
         'style' => '',
         'text_color' => '',
         'background_color' => '',
         'icon' => '',
         'icon_type' => 'icon_fontawesome',
         'icon_fontawesome' => '',
         'icon_lynny' => '',
         'design_param' => '',
         'animation_param' => '',
         'extra_class_param' => '',
      ), $atts ));

      // Animation, Design & Extra Class params
      $css_class = Volcanno_Visual_Composer::return_css_class( $design_param, $animation_param, $extra_class_param );

      // Icon
      $icon_class = $$icon_type;

      $button_style = $alignment;
      $button_style .= $size ? ' ' . $size . '' : '';
      $button_style .= $style == 'btn-blue' ? ' ' . $style : '';
      $button_style .= $icon ? ' icon-animated' : '';

      // If custom color style
      if ( $style == 'custom' ) {
         $color = $text_color ? 'color:' . $text_color . ';' : '';
         $background = $background_color ? 'background-color:' . $background_color . ';' : '';
         $style_color = ' style="' . $color . '"';
      } else {
         $background = $style_color = '';
      }

      // Add icon if exist
      $icon = $icon ? '<i class="' . $icon_class . '"></i>' : ''; 
      
      // Button content
      $link_content = '<span' . $style_color . '>' . $icon .  $text . '</span>';

      // Build button
      if ( $button_format_style == 'default' ) {

         $output = Volcanno_Visual_Composer::build_link( $href, $link_content, $params = array(
               'class' => 'btn ' . $button_style . $css_class, 
               'style' => $background,
            )
         );

      } else {
         $output = Volcanno_Visual_Composer::build_link( $href, $link_content, $params = array(
               'class' => 'read-more' . $css_class, 
               'style' => $background,
            )
         );
      }

      return $output;
   }
}