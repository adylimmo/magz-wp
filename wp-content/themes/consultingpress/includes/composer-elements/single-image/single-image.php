<?php

/**
 * Visual composer mapping
 */
add_action( 'vc_before_init', 'volcanno_single_image_integrateWithVC' );
if ( !function_exists('volcanno_single_image_integrateWithVC') ) {
   function volcanno_single_image_integrateWithVC() {
      vc_map( array(
         "name" => esc_html__( "Single Image", 'consultingpress' ),
         "base" => "volcanno_single_image",
         "class" => "",
         "icon" => get_template_directory_uri() . "/includes/composer-elements/assets/icon-volcanno.png",
         "category" => esc_html__( "Consulting Press", 'consultingpress'),
         "params" => array(
            // Image
            array(
               "type" => "attach_image",
               "heading" => esc_html__( "Image", 'consultingpress' ),
               "param_name" => "image",
               "description" => esc_html__( "Select image.", 'consultingpress' ),
               "admin_label" => true,
            ),
            array(
               "type" => "textfield",
               "heading" => esc_html__( "Image size", 'consultingpress' ),
               "param_name" => "size",
               "value" => "750",
               "description" => esc_html__( "Here you can define image size (width, height). You can enter only width to resize image or width and height to crop image ( Eg. 250, 250 ).", 'consultingpress' ) 
            ),
            // href
            array(
               "type" => "vc_link",
               "holder" => "",
               "class" => "",
               "heading" => esc_html__( "Image link", 'consultingpress' ),
               "param_name" => "href",
               "value" => "",
               "description" => esc_html__( "Here you can add link to image", 'consultingpress' )
            ),
            array(
               "type" => "checkbox",
               "holder" => "",
               "class" => "",
               "heading" => esc_html__( "Image reveal", 'consultingpress' ),
               "param_name" => "reveal",
               "description" => esc_html__( "Select this if you want reveal image when user scrools to it.", 'consultingpress' ),
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
if ( !function_exists('volcanno_single_image_func') ) {
   function volcanno_single_image_func( $atts ) {
      extract(shortcode_atts( array(
         'image' => '',
         'size' => '750',
         'crop' => '',
         'href' => '',
         'reveal' => '',
         'design_param' => '',
         'animation_param' => '',
         'extra_class_param' => '',
      ), $atts ));

      // Animation, Design & Extra Class params
      $css_class = Volcanno_Visual_Composer::return_css_class( $design_param, $animation_param, $extra_class_param );
      $data_animate = !empty( $animation_param ) ? ' data-animate="' . $animation_param . '"' : '';

      // Define image params
      $size = explode(',', $size);
      $crop = !empty($crop) ? true : false;
      $reveal = !empty($reveal) ? 'image-reveal' : '';
      $image = Volcanno_Visual_Composer::get_image( $image, $size, $crop );
      $image = !empty($reveal) ? $image . '<div class="image-reveal-mask triggerAnimation" data-animate="imageReveal"></div>' : $image;

      if ( !empty($href) ) {
         $output = Volcanno_Visual_Composer::build_link( $href, $image, $params = array(
               'class' => $reveal . $css_class,
            )
         );
      } else {
         $output = '<div class="' . $reveal . $css_class . '"' . $data_animate . '>' . $image . '</div>';
      }

      return $output;
   }
}