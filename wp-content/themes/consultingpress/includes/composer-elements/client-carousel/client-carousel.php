<?php
/**
 * Visual composer mapping
 */
add_action( 'vc_before_init', 'volcanno_client_carousel_integrateWithVC' );
if ( !function_exists('volcanno_client_carousel_integrateWithVC') ) {
   function volcanno_client_carousel_integrateWithVC() {
      vc_map( array(
         "name" => esc_html__( "Client Carousel", 'consultingpress' ),
         "base" => "volcanno_client_carousel",
         "class" => "",
         "icon" => get_template_directory_uri() . "/includes/composer-elements/assets/icon-volcanno.png",
         "category" => esc_html__( "Consulting Press", 'consultingpress'),
         "params" => array(
            array(
               "type" => "attach_images",
               "class" => "",
               "heading" => esc_html__( "Images", 'consultingpress' ),
               "param_name" => "clients",
               "value" => "",
               "description" => esc_html__( "Here you can select images that will show in carousel.", 'consultingpress' ),
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
if ( !function_exists('volcanno_client_carousel_func') ) {
   function volcanno_client_carousel_func( $atts ) {
      extract(shortcode_atts( array(
         'clients' => '',
         'animation_param' => '',
         'extra_class_param' => '',
         'design_param' => '',
      ), $atts ));

      // Design & Extra Class params
      $css_class = Volcanno_Visual_Composer::return_css_class( $design_param, $animation_param, $extra_class_param, array('carousel-container'), true );
      $data_animate = !empty( $animation_param ) ? ' data-animate="' . $animation_param . '"' : '';

      // Load styles and scripts
      wp_enqueue_style('owl-carousel');
      wp_enqueue_script('owl-carousel');
      // Initialize owl carousel
      wp_enqueue_script( 'owl-carousel-init' );

      $clients = explode(',', $clients);

      $output = '<div'. $css_class . $data_animate . '><div class="owl-carousel client-carousel" data-type="client-carousel">';

      foreach ($clients as $index => $id) {
         $output .= '<div class="owl-item">' . Volcanno_Visual_Composer::get_image( $id, array(), false, 'retina' ) . '</div>';
      }

      $output .= '</div></div>';

      return $output;

   }
}