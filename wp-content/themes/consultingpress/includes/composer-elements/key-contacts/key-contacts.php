<?php
/**
 * Visual composer mapping
 */
add_action( 'vc_before_init', 'volcanno_key_contacts_integrateWithVC' );
if ( !function_exists('volcanno_key_contacts_integrateWithVC') ) {
   function volcanno_key_contacts_integrateWithVC() {
      vc_map( array(
         "name" => esc_html__( "Key contacts", 'consultingpress' ),
         "base" => "volcanno_key_contacts",
         "class" => "",
         "icon" => get_template_directory_uri() . "/includes/composer-elements/assets/icon-volcanno.png",
         "category" => esc_html__( "Consulting Press", 'consultingpress'),
         "params" => array(
            array(
               "type" => "attach_image",
               "holder" => "",
               "class" => "",
               "heading" => esc_html__( "Short bio", 'consultingpress' ),
               "param_name" => "image",
               "description" => esc_html__( "Team member image.", 'consultingpress' ),
            ),
            array(
               "type" => "textfield",
               "class" => "",
               "heading" => esc_html__( "Name", 'consultingpress' ),
               "param_name" => "name",
               "description" => esc_html__( "Team member first and last name.", 'consultingpress' ),
               "admin_label" => true,
            ),
            array(
               "type" => "textfield",
               "class" => "",
               "heading" => esc_html__( "Position", 'consultingpress' ),
               "param_name" => "position",
               "description" => esc_html__( "Team member position.", 'consultingpress' )
            ),
            array(
               "type" => "param_group",
               "heading" => esc_html__( "Contact info", 'consultingpress' ),
               "param_name" => "info_group",
               "value" => "",
               "description" => esc_html__( "Here you can enter contact info.", 'consultingpress' ),
               "params" => array(
                  Volcanno_Visual_Composer::icons_lib('icon_type'),
                  Volcanno_Visual_Composer::icons_param('fontawesome'),
                  Volcanno_Visual_Composer::icons_param('lynny'),
                  array(
                     "type" => "textfield",
                     "class" => "",
                     "heading" => esc_html__( "Contact info text", 'consultingpress' ),
                     "param_name" => "info_text",
                     "description" => esc_html__( "Here you can enter contact info text.", 'consultingpress' ),
                     "admin_label" => true,
                  ),
                  array(
                     "type" => "vc_link",
                     "holder" => "",
                     "class" => "",
                     "heading" => esc_html__( "Url (Link)", 'consultingpress' ),
                     "param_name" => "href",
                     "value" => "",
                     "description" => esc_html__( "Here you can add link to contact info text.", 'consultingpress' )
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
if ( !function_exists('volcanno_key_contacts_func') ) {
   function volcanno_key_contacts_func( $atts ) {
      extract(shortcode_atts( array(
         'image' => '',
         'position' => '',
         'name' => '',
         'info_group' => '',
         'design_param' => '',
         'animation_param' => '',
         'extra_class_param' => '',
      ), $atts ));

      // Animation, Design & Extra Class params
      $css_class = Volcanno_Visual_Composer::return_css_class( $design_param, $animation_param, $extra_class_param, '', false);
      $data_animate = !empty( $animation_param ) ? ' data-animate="' . $animation_param . '"' : '';

      // Image
      $image = Volcanno_Visual_Composer::get_image( $image, array(263), true, 'retina');

      $position = !empty($position) ? '<span class="position">' . $position . '</span>' : '';
      $name = !empty($name) ? '<h4>' . $name . '</h4>' : '';

      $contact_info = '<ul class="fa-ul default clearfix">';

      $info_group = vc_param_group_parse_atts( $info_group );

      // Return if elements are empty
      if ( empty( $info_group ) ) return;

      foreach ($info_group as $index => $param) {

         // Icon
         $icon_lib = isset( $param['icon_type'] ) ? $param['icon_type'] : 'icon_fontawesome';
         $icon = isset( $param[ $icon_lib ] ) ? '<i class="' . $param[ $icon_lib ] . '"></i>' : '';

         // Build link
         $href = !empty( $param['href'] ) ? $param['href'] : '';
         $link = Volcanno_Visual_Composer::build_link( $href, $param['info_text'], array(), $param['info_text'] );

         // Info text
         $info_text = isset( $param['info_text'] ) ? '<p>' . $link . '</p>' : '';

         $contact_info .= '<li>' . $icon . $info_text . '</li>';

      }

      $contact_info .= '</ul>';

      $output =   '<div class="key-contacts' . $css_class . '"' . $data_animate . '>
                     <ul class="clearfix">
                          <li>' . $image . '
                              <div class="text-container">
                                 <div class="contacts-title">
                                    ' . $name . '
                                    ' . $position . '
                                 </div>
                                 ' . $contact_info . '
                              </div>
                          </li>
                      </ul>
                  </div>';

      return $output;
   }
}