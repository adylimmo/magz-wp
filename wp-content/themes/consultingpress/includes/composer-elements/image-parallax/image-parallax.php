<?php

/**
 * Visual composer mapping
 */
add_action( 'vc_before_init', 'volcanno_image_parallax_integrateWithVC' );
if ( !function_exists('volcanno_image_parallax_integrateWithVC') ) {
   function volcanno_image_parallax_integrateWithVC() {
      vc_map( array(
         "name" => esc_html__( "Image Parallax", 'consultingpress' ),
         "base" => "volcanno_image_parallax",
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
               "heading" => esc_html__( "Top", 'consultingpress' ),
               "param_name" => "top",
               "value" => "",
            ),
            array(
               "type" => "textfield",
               "heading" => esc_html__( "Left", 'consultingpress' ),
               "param_name" => "left",
               "value" => "",
            ),
            array(
               "type" => "textfield",
               "heading" => esc_html__( "Speed", 'consultingpress' ),
               "param_name" => "speed",
               "value" => "20",
            ),
            array(
               "type" => "textfield",
               "heading" => esc_html__( "Image size", 'consultingpress' ),
               "param_name" => "size",
               "value" => "290",
               "description" => esc_html__( "Here you can define image size (width, height). You can enter only width or width and height to crop image ( Eg. 250, 250 ), or leave empty for default image size.", 'consultingpress' ) 
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
if ( !function_exists('volcanno_image_parallax_func') ) {
   function volcanno_image_parallax_func( $atts ) {
      extract(shortcode_atts( array(
         'image' => '',
         'size' => '290',
         'top' => '',
         'left' => '',
         'speed' => '20',
         'design_param' => '',
         'animation_param' => '',
         'extra_class_param' => '',
      ), $atts ));

      // Animation, Design & Extra Class params
      $css_class = Volcanno_Visual_Composer::return_css_class( $design_param, $animation_param, $extra_class_param );
      $data_animate = !empty( $animation_param ) ? ' data-animate="' . $animation_param . '"' : '';

      wp_enqueue_script( 'jQuery-parallax-scrool' );
      wp_enqueue_script( 'jQuery-parallax-scrool-init' );

      // Define image params
      $size = explode(',', $size);
      $width = !empty( $size[0] ) ? $size[0] : 0;
      $height = !empty( $size[1] ) ? $size[1] : 0;
      $image = Volcanno_Visual_Composer::get_image( $image, $size );

      $data_top = !empty( $top ) ? ' data-top="' . $top . '"' : '';
      $data_left = !empty( $left ) ? ' data-left="' . $left . '"' : '';
      $data_width = !empty( $width ) ? ' data-width="' . $width . '"' : '';

      $output = '
         <div class="img-animate-container X' . $css_class . '"' . $data_animate . ' data-parallax="{&quot;y&quot; : ' . $speed . '}"' . $data_width . $data_top . $data_left . '>
            <div class="img-animate">
               <div class="image-reveal triggerAnimation animated" data-animate="fadeInUp">
                  ' . $image . '
                  <div class="image-reveal-mask triggerAnimation" data-animate="imageReveal"></div>
               </div>
            </div>
         </div>
      ';

      return $output;
   }
}