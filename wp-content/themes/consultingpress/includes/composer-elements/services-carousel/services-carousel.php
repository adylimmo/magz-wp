<?php
/**
 * Visual composer mapping
 */
add_action( 'vc_before_init', 'volcanno_services_carousel_integrateWithVC' );
if ( !function_exists('volcanno_services_carousel_integrateWithVC') ) {
   function volcanno_services_carousel_integrateWithVC() {
      vc_map( array(
         "name" => esc_html__( "Services carousel", 'consultingpress' ),
         "base" => "volcanno_services_carousel",
         "class" => "",
         "icon" => get_template_directory_uri() . "/includes/composer-elements/assets/icon-volcanno.png",
         "category" => esc_html__( "Consulting Press", 'consultingpress'),
         "params" => array(
            array(
               "type" => "dropdown",
               "holder" => "",
               "class" => "",
               "heading" => esc_html__( "Style", 'consultingpress' ),
               "param_name" => "style",
               "description" => esc_html__( "Here you can choose services style", 'consultingpress' ),
               "value" => array(
                  'Icon Services' => 'icon',
                  'Image Services' => 'image' 
               ),
               "admin_label" => true,
            ),
            array(
               "type" => "param_group",
               "heading" => esc_html__( "Services", 'consultingpress' ),
               "param_name" => "services_group_icon",
               "value" => "",
               "description" => esc_html__( "Here you can enter services.", 'consultingpress' ),
               'dependency' => array(
                  'element' => 'style',
                  'value' => 'icon',
               ),
               "params" => array(
                  Volcanno_Visual_Composer::icons_lib(),
                  Volcanno_Visual_Composer::icons_param('fontawesome'),
                  Volcanno_Visual_Composer::icons_param('lynny'),
                  array(
                     "type" => "colorpicker",
                     "class" => "",
                     "heading" => esc_html__( "Custom icon color", 'consultingpress' ),
                     "param_name" => "icon_color",
                     "value" => '',
                     "description" => esc_html__( "Here you can change default icon color.", 'consultingpress' ),
                  ),
                  array(
                     "type" => "textfield",
                     "holder" => "div",
                     "class" => "",
                     "heading" => esc_html__( "Title", 'consultingpress' ),
                     "param_name" => "title",
                     "value" => "",
                     "description" => esc_html__( "Here you can enter service title", 'consultingpress' ),
                     "admin_label" => true
                  ),
                  array(
                     "type" => "textarea",
                     "class" => "",
                     "heading" => esc_html__( "Description", 'consultingpress' ),
                     "param_name" => "description",
                     "value" => "",
                     "description" => esc_html__( "Here you can enter service description", 'consultingpress' ),
                  ),
                  array(
                     "type" => "vc_link",
                     "holder" => "",
                     "class" => "",
                     "heading" => esc_html__( "Url (Link)", 'consultingpress' ),
                     "param_name" => "href",
                     "value" => "",
                     "description" => esc_html__( "Here you can add link to service", 'consultingpress' )
                  ),
               ),
            ),
            array(
               "type" => "param_group",
               "heading" => esc_html__( "Services", 'consultingpress' ),
               "param_name" => "services_group_image",
               "value" => "",
               "description" => esc_html__( "Here you can enter services.", 'consultingpress' ),
               'dependency' => array(
                  'element' => 'style',
                  'value' => 'image',
               ),
               "params" => array(
                  array(
                     "type" => "attach_image",
                     "class" => "",
                     "heading" => esc_html__( "Image", 'consultingpress' ),
                     "param_name" => "image",
                     "value" => "",
                     "description" => esc_html__( "Here you can select service image.", 'consultingpress' ),
                  ),
                  Volcanno_Visual_Composer::icons_lib('icon_type'),
                  Volcanno_Visual_Composer::icons_param('fontawesome'),
                  Volcanno_Visual_Composer::icons_param('lynny'),
                  array(
                     "type" => "colorpicker",
                     "class" => "",
                     "heading" => esc_html__( "Custom icon color", 'consultingpress' ),
                     "param_name" => "icon_color",
                     "value" => '',
                     "description" => esc_html__( "Here you can change default icon color.", 'consultingpress' ),
                  ),
                  array(
                     "type" => "textfield",
                     "holder" => "div",
                     "class" => "",
                     "heading" => esc_html__( "Title", 'consultingpress' ),
                     "param_name" => "title",
                     "value" => "",
                     "description" => esc_html__( "Here you can enter service title", 'consultingpress' ),
                     "admin_label" => true
                  ),
                  array(
                     "type" => "textarea",
                     "class" => "",
                     "heading" => esc_html__( "Description", 'consultingpress' ),
                     "param_name" => "description",
                     "value" => "",
                     "description" => esc_html__( "Here you can enter service description", 'consultingpress' ),
                  ),
                  array(
                     "type" => "vc_link",
                     "holder" => "",
                     "class" => "",
                     "heading" => esc_html__( "Url (Link)", 'consultingpress' ),
                     "param_name" => "href",
                     "value" => "",
                     "description" => esc_html__( "Here you can add link to service", 'consultingpress' )
                  ),
                  array(
                     "type" => "textarea",
                     "class" => "",
                     "heading" => esc_html__( "Button text", 'consultingpress' ),
                     "param_name" => "button_text",
                     "value" => esc_html__( "Read more", 'consultingpress' ),
                     "description" => esc_html__( "Here you can enter button text", 'consultingpress' ),
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
if ( !function_exists('volcanno_services_carousel_func') ) {
   function volcanno_services_carousel_func( $atts ) {
      extract(shortcode_atts( array(
         'style' => 'icon',
         'services_group_icon' => '',
         'services_group_image' => '',
         'design_param' => '',
         'animation_param' => '',
         'extra_class_param' => '',
      ), $atts ));

      // Get carousel style
      $carousel_style = $style == 'icon' ? 'services-carousel' : 'services-carousel-02';

      // Enqueue styles
      wp_enqueue_style( 'owl-carousel' );
      // Enqueue scripts
      wp_enqueue_script( 'owl-carousel' );
      // Initialize owl carousel
      wp_enqueue_script( 'owl-carousel-init' );

      // Extra class & design param
      $design_param = preg_replace('/(.)(.*?)({.*?})/', '$2', $design_param);
      $css_class = $design_param ? ' ' . $design_param : '';
      $css_class .= $extra_class_param ? ' ' . $extra_class_param : '';

      // Animation, Design & Extra Class params
      $css_class = Volcanno_Visual_Composer::return_css_class( $design_param, $animation_param, $extra_class_param, array('carousel-container'), true );
      $data_animate = !empty( $animation_param ) ? ' data-animate="' . $animation_param . '"' : '';

      // Get services param group
      $param_group = $style == 'icon' ? $services_group_icon : $services_group_image;
      $param_group = vc_param_group_parse_atts( $param_group );

      // Return if elements are empty
      if ( empty( $param_group ) ) return;

      $output = '<div' . $css_class . $data_animate . '><div class="owl-carousel ' . $carousel_style . '" data-type="' . $carousel_style . '">';
    
      foreach ($param_group as $service => $param) {

         // Get global fileds
         $title = isset( $param['title'] ) ? $param['title'] : '';
         $description = isset( $param['description'] ) ? $param['description'] : '';
         $href = isset( $param['href'] ) ? $param['href'] : '';
         $icon_lib = isset( $param['icon_type'] ) ? $param['icon_type'] : 'icon_fontawesome';
         $icon = isset( $param[$icon_lib] ) ? $param[$icon_lib] : '';
         $icon_color = isset( $param['icon_color'] ) ? ' style="color:' . $param['icon_color'] . '"' : '';

         // Build link
         $title_link_content = '<h3>' . esc_html( $title ) . '</h3>';
         $title_link = Volcanno_Visual_Composer::build_link( $href, $title_link_content );

         // Icon services
         if ( $style == 'icon' ) :

            $output .=  '<div class="owl-item">
                           <div class="service-box service-box-01">
                              <div class="icon-container">
                                 <i class="' . $icon . '"' . $icon_color . '></i>
                              </div>
                              ' . $title_link . '
                              <p>' . esc_html( $description ) . '</p>
                           </div>
                        </div>';

         // Image services
         elseif ( $style == 'image' ) :

            // Get fields for image style
            $image_id = isset( $param['image'] ) ? $param['image'] : '';
            $button_text = isset( $param['button_text'] ) ? $param['button_text'] : '';
            // Build link
            $read_more_link = Volcanno_Visual_Composer::build_link( $href, $button_text, array('class' => 'read-more') );            
            // Get image
            $image = Volcanno_Visual_Composer::get_image( $image_id, array(360, 245), true, 'retina' );
            // Get image with link
            $image_with_link = Volcanno_Visual_Composer::build_link( $href, $image );

            $output .=  '<div class="owl-item">
                           <div class="service-box service-box-04">
                              <div class="media">
                                 ' . $image_with_link .'
                              </div>
                              <div class="icon-container">
                                 <i class="' . $icon . '"' . $icon_color . '></i>
                              </div>
                              <div class="text-container">
                                 ' . $title_link . '
                                 <p>' . esc_html( $description ) . '</p>
                                 ' . $read_more_link . '
                              </div>
                           </div>
                        </div>';

         endif;
      }

      $output .= '</div></div>';

      return $output;
   }
}
