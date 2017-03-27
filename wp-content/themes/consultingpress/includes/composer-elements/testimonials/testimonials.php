<?php
/**
 * Visual composer mapping
 */
add_action( 'vc_before_init', 'volcanno_testimonials_integrateWithVC' );
if ( !function_exists('volcanno_testimonials_integrateWithVC') ) {
   function volcanno_testimonials_integrateWithVC() {
      vc_map( array(
         "name" => esc_html__( "Testimonials Carousel", 'consultingpress' ),
         "base" => "volcanno_testimonials",
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
               "description" => esc_html__( "Here you can select testimonial style.", 'consultingpress' ),
               "value" => array(
                  'Style 1' => 'style-1',
                  'Style 2' => 'style-2' 
               ),
            ),
            array(
               "type" => "param_group",
               "heading" => esc_html__( "Testimonials", 'consultingpress' ),
               "param_name" => "testimonials_group",
               "value" => "",
               "description" => esc_html__( "Here you can enter testimonials.", 'consultingpress' ),
               "params" => array(
                  array(
                     "type" => "textarea",                  
                     "class" => "",
                     "heading" => esc_html__( "Testimonial", 'consultingpress' ),
                     "param_name" => "text",
                     "value" => "",
                     "description" => esc_html__( "Here you can enter testimonial title", 'consultingpress' )
                  ),
                  array(
                     "type" => "textfield",
                     "class" => "",
                     "heading" => esc_html__( "Author", 'consultingpress' ),
                     "param_name" => "author",
                     "value" => "",
                     "description" => esc_html__( "Here you can enter testimonial author", 'consultingpress' ),
                     'admin_label' => true,
                  ),
                  array(
                     "type" => "vc_link",                  
                     "class" => "",
                     "heading" => esc_html__( "Author link", 'consultingpress' ),
                     "param_name" => "href",
                     "value" => "",
                     "description" => esc_html__( "Here you can enter link to author page.", 'consultingpress' )
                  ),
                  array(
                     "type" => "textfield",                  
                     "class" => "",
                     "heading" => esc_html__( "Author link text", 'consultingpress' ),
                     "param_name" => "href_text",
                     "value" => "",
                     "description" => esc_html__( "Here you can enter text that will apear on link.", 'consultingpress' )
                  ),
               ),
            ),
            array(
               "type" => "colorpicker",
               "class" => "",
               "heading" => esc_html__( "Testimonial color", 'consultingpress' ),
               "param_name" => "text_color",
               "value" => '',
               "description" => esc_html__( "Optional.", 'consultingpress' ),
            ),
            array(
               "type" => "colorpicker",
               "class" => "",
               "heading" => esc_html__( "Author color", 'consultingpress' ),
               "param_name" => "author_color",
               "value" => '',
               "description" => esc_html__( "Optional.", 'consultingpress' ),
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
if ( !function_exists('volcanno_testimonials_func') ) {
   function volcanno_testimonials_func( $atts ) {
      extract(shortcode_atts( array(
         'style' => 'style-1',
         'testimonials_group' => '',
         'text_color' => '',
         'author_color' => '',
         'animation_param' => '',
         'extra_class_param' => '',
         'design_param' => '',
      ), $atts ));

      // Design & Extra Class params
      $css_class = Volcanno_Visual_Composer::return_css_class( $design_param, $animation_param, $extra_class_param, array('carousel-container'), true );
      $data_animate = !empty( $animation_param ) ? ' data-animate="' . $animation_param . '"' : '';

      $carousel_style = $style == 'style-1' ? 'testimonial-carousel' : 'testimonial-carousel-02';

      // Enqueue styles
      wp_enqueue_style( 'owl-carousel' );
      // Enqueue scripts
      wp_enqueue_script( 'owl-carousel' );
      // Initialize owl carousel
      wp_enqueue_script( 'owl-carousel-init' );

      $testimonials = vc_param_group_parse_atts( $testimonials_group );

      // Return if elements are empty
      if ( empty( $testimonials_group ) ) return;

      $output = '<div' . $css_class . $data_animate . '><div class="owl-carousel ' . $carousel_style . '" data-type="' . $carousel_style . '">';

      foreach ($testimonials as $testimonial => $param) {
         
         // Text
         $text = isset( $param['text'] ) ? $param['text'] : '';
         $text_color_style = !empty( $text_color ) ? ' style="color:' . $text_color . '"' : '';
         // Author
         $author = isset( $param['author'] ) ? $param['author'] : '';
         $author_color_style = !empty( $author_color ) ? ' style="color:' . $author_color . '"' : '';
         // Author link
         $author_link = ( isset( $param['href'] ) && isset( $param['href_text'] ) ) ? ', ' . Volcanno_Visual_Composer::build_link( $param['href'], $param['href_text'] ) : '';


         // Style type
         if ( $style == 'style-1' ) {
            $output .=  '<div class="owl-item">
                           <div class="testimonial-style-01">
                              <p' . $text_color_style . '>"' . $text . '"</p>
                              <span class="author"' . $author_color_style . '>' . $author . $author_link . '</span>
                           </div>
                       </div>';
         } else {
            $output .=  '<div class="owl-item">
                              <div class="testimonial-style-02">
                                  <p' . $text_color_style . '>"' . $text . '"</p>
                                  <span class="author"' . $author_color_style . '>' . $author . $author_link . '</span>
                              </div>
                          </div>';
         }
      }

      $output .= '</div></div>';

      return $output;

   }
}