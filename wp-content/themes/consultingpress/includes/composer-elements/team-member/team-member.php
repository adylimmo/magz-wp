<?php
/**
 * Visual composer mapping
 */
add_action( 'vc_before_init', 'volcanno_team_member_integrateWithVC' );
if ( !function_exists('volcanno_team_member_integrateWithVC') ) {
   function volcanno_team_member_integrateWithVC() {
      vc_map( array(
         "name" => esc_html__( "Team member", 'consultingpress' ),
         "base" => "volcanno_team_member",
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
               "heading" => esc_html__( "Title", 'consultingpress' ),
               "param_name" => "title",
               "description" => esc_html__( "Team member title.", 'consultingpress' )
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
               "type" => "textarea_html",
               "holder" => "",
               "class" => "",
               "heading" => esc_html__( "Short bio", 'consultingpress' ),
               "param_name" => "content",
               "description" => esc_html__( "Team member short biography.", 'consultingpress' ),
            ),
            array(
               "type" => "vc_link",
               "holder" => "",
               "class" => "",
               "heading" => esc_html__( "Read more", 'consultingpress' ),
               "param_name" => "href",
               "value" => "",
            ),
            array(
               "type" => "textfield",
               "class" => "",
               "heading" => esc_html__( "Read more text", 'consultingpress' ),
               "param_name" => "read_more",
               "value" => esc_html__( "View profile", 'consultingpress' ),
               "description" => esc_html__( "Here you can enter text that will apear on read more link.", 'consultingpress' ),
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
if ( !function_exists('volcanno_team_member_func') ) {
   function volcanno_team_member_func( $atts, $content = '' ) {
      extract(shortcode_atts( array(
         'image' => '',
         'title' => '',
         'name' => '',
         'read_more' => esc_html__( "View profile", 'consultingpress' ),
         'href' => '',
         'design_param' => '',
         'animation_param' => '',
         'extra_class_param' => '',
      ), $atts ));

      // Animation, Design & Extra Class params
      $css_class = Volcanno_Visual_Composer::return_css_class( $design_param, $animation_param, $extra_class_param, '', false);
      $data_animate = !empty( $animation_param ) ? ' data-animate="' . $animation_param . '"' : '';

      // Image
      $image = Volcanno_Visual_Composer::get_image( $image, array(263), true, 'retina');

      $title = !empty($title) ? '<span>' . $title . '</span>' : '';
      $name = !empty($name) ? '<h2>' . $name . '</h2>' : '';
      $read_more = !empty($href) ? Volcanno_Visual_Composer::build_link( $href, $read_more, $params = array('class' => 'read-more') ) : '';

      $output =   '<div class="team-horizontal' . $css_class . '"' . $data_animate . '>' . $image . '
                      <div class="team-details-container">
                          <div class="custom-heading-01">' . $title . $name . '</div>
                          ' . balancetags( '<p>' . $content, true ) . '
                          ' . $read_more . '
                      </div>
                  </div>';

      return $output;
   }
}