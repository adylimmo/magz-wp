<?php
/**
 * Visual composer mapping
 */
add_action( 'vc_before_init', 'volcanno_testimonial_integrateWithVC' );
if ( !function_exists('volcanno_testimonial_integrateWithVC') ) {
   function volcanno_testimonial_integrateWithVC() {
      vc_map( array(
         "name" => esc_html__( "Single Testimonial", 'consultingpress' ),
         "base" => "volcanno_testimonial",
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
                  'Simple' => 'testimonial-style-01',
                  'Boxed' => 'testimonial-style-02' 
               ),
            ),
            array(
               "type" => "textarea",                  
               "class" => "",
               "heading" => esc_html__( "Testimonial", 'consultingpress' ),
               "param_name" => "text",
               "value" => "",
               "description" => esc_html__( "Here you can enter service title", 'consultingpress' )
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
if ( !function_exists('volcanno_testimonial_func') ) {
   function volcanno_testimonial_func( $atts ) {
      extract(shortcode_atts( array(
         'style' => 'testimonial-style-01',
         'text' => '',
         'author' => '',
         'href' => '',
         'href_text' => '',
         'animation_param' => '',
         'extra_class_param' => '',
         'design_param' => '',
      ), $atts ));

      // Animation, Design & Extra Class params
      $css_class = Volcanno_Visual_Composer::return_css_class( $design_param, $animation_param, $extra_class_param, array($style), true );
      $data_animate = !empty( $animation_param ) ? ' data-animate="' . $animation_param . '"' : '';

      // Author link
      $author_link = ( $href != '' && $href_text != '' ) ? ', ' . Volcanno_Visual_Composer::build_link( $href, $href_text ) : '';

      $output =   '<div' . $css_class . $data_animate . '>
                      <p>"' . $text . '"</p>
                      <span class="author">' . $author . $author_link . '</span>
                  </div>';

      return $output;

   }
}
