<?php
/**
 * Visual composer mapping
 */
add_action( 'vc_before_init', 'volcanno_icon_list_integrateWithVC' );
if ( !function_exists('volcanno_icon_list_integrateWithVC') ) {
   function volcanno_icon_list_integrateWithVC() {
      vc_map( array(
         "name" => esc_html__( "Icon Lists", 'consultingpress' ),
         "base" => "volcanno_icon_list",
         "class" => "",
         "icon" => get_template_directory_uri() . "/includes/composer-elements/assets/icon-volcanno.png",
         "category" => esc_html__( "Consulting Press", 'consultingpress'),
         "params" => array(
            array(
               "type" => "dropdown",
               "class" => "",
               "heading" => esc_html__( "List style", 'consultingpress' ),
               "param_name" => "list_style",
               "description" => esc_html__( "Here you can choose heading style.", 'consultingpress' ),
               "value" => array(
                  'Default' => 'default',
                  'Circled icons' => 'ul-circled',
                  'Large icons - detailed' => 'detailed',
                  'Large icons - simple' => 'large-simple',
               ),
               "std" => 'default',
               "admin_label" => true,
            ),
            // Large icons - simple
            array(
               'type' => 'param_group',
               'value' => '',
               'param_name' => 'param_group_simple',
               'dependency' => array(
                  'element' => 'list_style',
                  'value' => 'large-simple',
               ),
               'params' => array(
                  Volcanno_Visual_Composer::icons_lib(),
                  Volcanno_Visual_Composer::icons_param('fontawesome'),
                  Volcanno_Visual_Composer::icons_param('lynny'),
                  array(
                     "type" => "textarea",
                     "holder" => "",
                     "class" => "",
                     "heading" => esc_html__( "Text", 'consultingpress' ),
                     "param_name" => "text",
                     "value" => "",
                     "description" => esc_html__( "Here you can enter list element text.", 'consultingpress' ),
                     "admin_label" => true,
                  ),
               )
            ),
            // Default
            array(
               'type' => 'param_group',
               'value' => '',
               'param_name' => 'param_group_default',
               'dependency' => array(
                  'element' => 'list_style',
                  'value' => 'default',
               ),
               'params' => array(
                  Volcanno_Visual_Composer::icons_lib(),
                  Volcanno_Visual_Composer::icons_param('fontawesome'),
                  Volcanno_Visual_Composer::icons_param('lynny'),
                  array(
                     "type" => "textarea",
                     "holder" => "",
                     "class" => "",
                     "heading" => esc_html__( "Text", 'consultingpress' ),
                     "param_name" => "text",
                     "value" => "",
                     "description" => esc_html__( "Here you can enter list element text.", 'consultingpress' ),
                     "admin_label" => true,
                  ),
                  array(
                     "type" => "vc_link",
                     "holder" => "",
                     "class" => "",
                     "heading" => esc_html__( "Url (link)", 'consultingpress' ),
                     "param_name" => "href",
                     "value" => "",
                     "description" => esc_html__( "Here you can add url to list item.", 'consultingpress' ),
                  ),
               )
            ),
            // Circled
            array(
               'type' => 'param_group',
               'value' => '',
               'param_name' => 'param_group_circled',
               'dependency' => array(
                  'element' => 'list_style',
                  'value' => 'ul-circled',
               ),
               'params' => array(
                  Volcanno_Visual_Composer::icons_lib(),
                  Volcanno_Visual_Composer::icons_param('fontawesome'),
                  Volcanno_Visual_Composer::icons_param('lynny'),
                  array(
                     "type" => "attach_image",
                     "holder" => "",
                     "class" => "",
                     "heading" => esc_html__( "Image icon", 'consultingpress' ),
                     "param_name" => "image_icon",
                     "value" => "",
                     "description" => esc_html__( "Here you can upload image to use as icon.", 'consultingpress' ),
                  ),
                  array(
                     "type" => "textarea",
                     "holder" => "",
                     "class" => "",
                     "heading" => esc_html__( "Text", 'consultingpress' ),
                     "param_name" => "text",
                     "value" => "",
                     "description" => esc_html__( "Here you can enter list element text.", 'consultingpress' ),
                     "admin_label" => true,
                  ),
               ),
            ),
            // Detailed
            array(
               'type' => 'param_group',
               'value' => '',
               'param_name' => 'param_group_detailed',
               'dependency' => array(
                  'element' => 'list_style',
                  'value' => 'detailed',
               ),
               'params' => array(
                  Volcanno_Visual_Composer::icons_lib(),
                  Volcanno_Visual_Composer::icons_param('fontawesome'),
                  Volcanno_Visual_Composer::icons_param('lynny'),
                  array(
                     "type" => "attach_image",
                     "holder" => "",
                     "class" => "",
                     "heading" => esc_html__( "Image icon", 'consultingpress' ),
                     "param_name" => "image_icon",
                     "value" => "",
                     "description" => esc_html__( "Here you can upload image to use as icon.", 'consultingpress' ),
                  ),
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
                  array(
                     "type" => "textfield",
                     "holder" => "",
                     "class" => "",
                     "heading" => esc_html__( "Read more text", 'consultingpress' ),
                     "param_name" => "read_more_text",
                     "value" => esc_html__( "Read more", 'consultingpress' ),
                     "description" => esc_html__( "Here you can enter list element read more text.", 'consultingpress' ),
                  ),
                  array(
                     "type" => "vc_link",
                     "holder" => "",
                     "class" => "",
                     "heading" => esc_html__( "Read more url", 'consultingpress' ),
                     "param_name" => "read_more_url",
                     "value" => "",
                     "description" => esc_html__( "Here you can enter list element read more url.", 'consultingpress' ),
                  ),
               ),
            ),
            array(
               "type" => "textfield",
               "holder" => "",
               "class" => "",
               "heading" => esc_html__( "Read more text", 'consultingpress' ),
               "param_name" => "read_more_text_simple",
               "value" => esc_html__( "Explore our services", 'consultingpress' ),
               "description" => esc_html__( "Here you can enter list read more text.", 'consultingpress' ),
               'dependency' => array(
                  'element' => 'list_style',
                  'value' => 'large-simple',
               ),
            ),
            array(
               "type" => "vc_link",
               "holder" => "",
               "class" => "",
               "heading" => esc_html__( "Read more url", 'consultingpress' ),
               "param_name" => "read_more_url_simple",
               "value" => "",
               "description" => esc_html__( "Here you can enter list read more url.", 'consultingpress' ),
               'dependency' => array(
                  'element' => 'list_style',
                  'value' => 'large-simple',
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
if ( !function_exists('volcanno_icon_list_func') ) {
   function volcanno_icon_list_func( $atts ) {
      extract(shortcode_atts( array(
         'list_style' => 'default',
         'param_group_default' => '',
         'param_group_circled' => '',
         'param_group_detailed' => '',
         'param_group_simple' => '',
         'read_more_text_simple' => esc_html__( "Explore our services", 'consultingpress' ),
         'read_more_url_simple' => '',
         'design_param' => '',
         'animation_param' => '',
         'extra_class_param' => '',
      ), $atts ));

      // Animation, Design & Extra Class params
      $css_class = Volcanno_Visual_Composer::return_css_class( $design_param, $animation_param, $extra_class_param);
      $data_animate = !empty( $animation_param ) ? ' data-animate="' . $animation_param . '"' : '';

      $param_group = $param_group_default != '' ? $param_group_default : '';
      $param_group = $param_group_circled != '' ? $param_group_circled : $param_group;
      $param_group = $param_group_detailed != '' ? $param_group_detailed : $param_group;
      $param_group = $param_group_simple != '' ? $param_group_simple : $param_group;

      $param_group = vc_param_group_parse_atts( $param_group );

      // Return if elements are empty
      if ( empty( $param_group ) ) return;

      $list_style_class = $list_style == 'detailed' ? 'large-icons detailed clearfix' : $list_style;

      $list_style_class = $list_style == 'large-simple' ? 'large-icons clearfix' : $list_style_class;

      $output = '<ul class="fa-ul ' . $list_style_class . $css_class . '"' . $data_animate . '>';

      foreach ($param_group as $index => $list) {

         // Icon
         $icon_lib = isset( $list['icon_type'] ) ? $list['icon_type'] : 'icon_fontawesome';
         $icon = isset( $list[ $icon_lib ] ) ? $list[ $icon_lib ] : '';
         $image_icon = isset( $list['image_icon'] ) ? wp_get_attachment_url( $list['image_icon'] ) : '';
         $icon = $image_icon ? '<img src="' . $image_icon . '" alt="Aerospace &amp; Defense">' : '<i class="icon-container ' . $icon . '"></i>';

         $text = isset( $list['text'] ) ? $list['text'] : '';
         $title = isset( $list['title'] ) ? $list['title'] : '';

         // Build link
         $href = isset( $list['read_more_url'] ) ? $list['read_more_url'] : '';
         $read_more_text = isset( $list['read_more_text'] ) ? $list['read_more_text'] : '';
         $read_more = Volcanno_Visual_Composer::build_link( $href, $read_more_text, $params = array('class' => 'read-more') );

         if ( $list_style == 'default' ) {
            // Default
            $href = isset( $list['href'] ) ? $list['href'] : '';
            $text = Volcanno_Visual_Composer::build_link( $href, $text, array(), $text);
            $output .= '<li>' . $icon . '<p>' . $text . '</p></li>';
         } else if ( $list_style == 'ul-circled' ) {
            // Circled icons
            $output .= '<li><div class="icon-container' . $css_class . '"' . $data_animate . '>' . $icon . '</div><div class="li-content"><p>' . $text . '</p></div></li>';
         } else if ( $list_style == 'detailed') {
            // Large icons detailed
            $output .=  '<li>' . $icon . '<div class="li-content">
                           <h4>' . $title . '</h4>
                           <p>' . $text . '</p>
                           ' . $read_more . '
                           </div>
                        </li>';
         } else {
            // Large icons simple
            $output .=  '<li>' . $icon . '<div class="li-content"><p>' . $text . '</p></div></li>';
         }

      }

      if ( !empty($read_more_url_simple) ) {
         $output .= '<li>' . Volcanno_Visual_Composer::build_link( $read_more_url_simple, $read_more_text_simple, $params = array('class' => 'read-more') ) . '</li>';
      }

      $output .= '</ul>';

      return $output;

   }
}