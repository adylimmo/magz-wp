<?php

/**
 * Visual composer mapping
 */
add_action( 'vc_before_init', 'volcanno_call_to_action_integrateWithVC' );
if ( !function_exists('volcanno_call_to_action_integrateWithVC') ) {
   function volcanno_call_to_action_integrateWithVC() {
      vc_map( array(
         "name" => esc_html__( "Call to action", 'consultingpress' ),
         "base" => "volcanno_call_to_action",
         "class" => "",
         "icon" => get_template_directory_uri() . "/includes/composer-elements/assets/icon-volcanno.png",
         "category" => esc_html__( "Consulting Press", 'consultingpress'),
         "admin_label" => esc_html__( "Consulting Press button", 'consultingpress'),
         "params" => array(
            // size
            array(
               "type" => "dropdown",
               "class" => "",
               "heading" => esc_html__( "Type", 'consultingpress' ),
               "param_name" => "type",
               "description" => esc_html__( "Here you can choose call to action type.", 'consultingpress' ),
               "value" => array(
                  'Default' => 'default',
                  'Centered' => 'centered',
               ),
            ),
            // cta text
            array(
               "type" => "textarea",
               "class" => "",
               "heading" => esc_html__( "Call to action text", 'consultingpress' ),
               "param_name" => "cta_text",
               "value" => "",
               "description" => esc_html__( "Here you can enter call to action description text.", 'consultingpress' ),
               "admin_label" => true,
            ),
            // cta text color
            array(
               "type" => "colorpicker",
               "class" => "",
               "heading" => esc_html__( "Call to action text color", 'consultingpress' ),
               "param_name" => "cta_text_color",
               "value" => '#071740',
               "description" => esc_html__( "Choose call to action text color.", 'consultingpress' ),
            ),
            // text
            array(
               "type" => "textfield",
               "class" => "",
               "heading" => esc_html__( "Button text", 'consultingpress' ),
               "param_name" => "text",
               "value" => "",
               "description" => esc_html__( "Here you can enter text that will apear on button.", 'consultingpress' ) 
            ),
            // href
            array(
               "type" => "vc_link",
               "class" => "",
               "heading" => esc_html__( "Url (Link)", 'consultingpress' ),
               "param_name" => "href",
               "value" => "",
               "description" => esc_html__( "Here you can add link to button", 'consultingpress' )
            ),
            // size
            array(
               "type" => "dropdown",
               "class" => "",
               "heading" => esc_html__( "Size", 'consultingpress' ),
               "param_name" => "size",
               "description" => esc_html__( "Here you can choose button size", 'consultingpress' ),
               "value" => array(
                  'Standard' => '',
                  'Large' => 'btn-large',
                  'Small' => 'btn-small' 
               ),
            ),
            // style
            array(
               "type" => "dropdown",
               "class" => "",
               "heading" => esc_html__( "Color Style", 'consultingpress' ),
               "param_name" => "style",
               "description" => esc_html__( "Here you can edit button color style", 'consultingpress' ),
               "value" => array(
                  'Theme color' => 'default',
                  'Standard dark' => 'btn-blue',
                  'Custom' => 'custom' 
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
               "heading" => esc_html__( "Add button icon", 'consultingpress' ),
               "param_name" => "icon",
               "value" => "",
               "description" => ""
            ),
            // icon_type
            Volcanno_Visual_Composer::icons_lib('icon_type', array(
                  'element' => 'icon',
                  'value' => 'true',
               )),
            Volcanno_Visual_Composer::icons_param('fontawesome'),
            Volcanno_Visual_Composer::icons_param('lynny'),
            // If type centered
            // Social links text
            array(
               "type" => "textfield",
               "class" => "",
               "heading" => esc_html__( "Social icons text", 'consultingpress' ),
               "param_name" => "cta_social_text",
               "value" => esc_html__( 'Or connect with us:', 'consultingpress' ),
               "description" => esc_html__( "Here you can enter text that will apear before social icons.", 'consultingpress' ),
               "dependency" => array(
                  'element' => 'type',
                  'value' => 'centered'
               ),
            ),
            // Param group
            array(
               'type' => 'param_group',
               'value' => '',
               'param_name' => 'param_group',
               "dependency" => array(
                  'element' => 'type',
                  'value' => 'centered'
               ),
               'params' => array(
                  Volcanno_Visual_Composer::icons_lib('icon_type', array(), 'icon_fontawesome'),
                  Volcanno_Visual_Composer::icons_param('fontawesome', 'icon_type', 'fa fa-calendar'),
                  Volcanno_Visual_Composer::icons_param('lynny'),
                  array(
                     "type" => "vc_link",
                     "heading" => esc_html__( "Url", 'consultingpress' ),
                     "param_name" => "title",
                     "value" => "",
                     "description" => esc_html__( "Here you can enter list element title.", 'consultingpress' ),
                     "admin_label" => true,
                  ),
               ),
            ),
            // Standard fields
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
if ( !function_exists('volcanno_call_to_action_func') ) {
   function volcanno_call_to_action_func( $atts ) {
      extract(shortcode_atts( array(
         'type' => 'default',
         'cta_text' => '',
         'cta_text_color' => '#071740',
         'text' => '',
         'href' => '',
         'alignment' => '',
         'size' => '', 
         'style' => '',
         'text_color' => '',
         'background_color' => '',
         'icon' => '',
         'icon_type' => 'icon_fontawesome',
         'icon_fontawesome' => '',
         'icon_lynny' => '',
         'cta_social_text' => esc_html__( 'Or connect with us:', 'consultingpress' ),
         'param_group' => '',
         'design_param' => '',
         'animation_param' => '',
         'extra_class_param' => '',
      ), $atts ));

      // Animation, Design & Extra Class params
      $css_class = Volcanno_Visual_Composer::return_css_class( $design_param, $animation_param, $extra_class_param );
      $data_animate = !empty( $animation_param ) ? ' data-animate="' . $animation_param . '"' : '';

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
      $button_content = '<span' . $style_color . '>' . $icon .  $text . '</span>';

      // Build button
      $button = Volcanno_Visual_Composer::build_link( $href, $button_content, $params = array(
            'class' => 'btn ' . $button_style, 
            'style' => $background,
         )
      );

      // Call to action text
      $cta_text_color = !empty( $cta_text_color ) ? ' style="color:' . $cta_text_color . '"' : '';
      if ( $type == 'default' ) {
         $cta_text = !empty( $cta_text ) ? '<h4' . $cta_text_color . '>' . $cta_text . '</h4>' : '';
      } else {
         $cta_text = !empty( $cta_text ) ? '<h2' . $cta_text_color . '>' . $cta_text . '</h2>' : '';
      }


      // Param group for social icons
      $param_group = vc_param_group_parse_atts( $param_group );
      // Return if elements are empty
      if ( !empty( $param_group ) && $type == 'centered' ) {

         $cta_social = '<ul class="cta-social"><li>' . $cta_social_text . '</li>';

         foreach ($param_group as $index => $list) {

            // Icon
            $icon_lib = isset( $list['icon_type'] ) ? $list['icon_type'] : 'icon_fontawesome';
            $social_icon = isset( $list[ $icon_lib ] ) ? $list[ $icon_lib ] : '';

            $icon_link = Volcanno_Visual_Composer::build_link( $href, '', $params = array(
                  'class' => $social_icon,
               )
            );

            $cta_social .=  '<li>' . $icon_link . '</li>';

         }

         $cta_social .= '</ul>';

      } else {
         $cta_social = '';
      }

      $output =   '<div class="call-to-action ' . $type . ' clearfix' . $css_class . '"' . $data_animate . '>
                     <div class="text">' . $cta_text . '</div>
                     ' . $button . '
                     ' . $cta_social . '
                  </div>';

      return $output;
   }
}