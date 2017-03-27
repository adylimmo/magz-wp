<?php
/**
 * Visual composer mapping
 */
add_action( 'vc_before_init', 'volcanno_featured_pages_carousel_integrateWithVC' );
if ( !function_exists('volcanno_featured_pages_carousel_integrateWithVC') ) {
   function volcanno_featured_pages_carousel_integrateWithVC() {
      vc_map( array(
         "name" => esc_html__( "Featured pages carousel", 'consultingpress' ),
         "base" => "volcanno_featured_pages_carousel",
         "class" => "",
         "icon" => get_template_directory_uri() . "/includes/composer-elements/assets/icon-volcanno.png",
         "category" => esc_html__( "Consulting Press", 'consultingpress'),
         "params" => array(
            array(
               "type" => "param_group",
               "heading" => esc_html__( "Featured pages", 'consultingpress' ),
               "param_name" => "featured_pages_group",
               "value" => "",
               "description" => esc_html__( "Here you can enter featured pages.", 'consultingpress' ),
               "params" => array(
                  array(
                     "type" => "attach_image",
                     "class" => "",
                     "heading" => esc_html__( "Image", 'consultingpress' ),
                     "param_name" => "image",
                     "value" => "",
                     "description" => esc_html__( "Here you can select service image.", 'consultingpress' ),
                  ),
                  array(
                     "type" => "textfield",
                     "class" => "",
                     "heading" => esc_html__( "Title", 'consultingpress' ),
                     "param_name" => "title",
                     "value" => "",
                     "description" => esc_html__( "Here you can enter service title", 'consultingpress' ),
                     "admin_label" => true
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
if ( !function_exists('volcanno_featured_pages_carousel_func') ) {
   function volcanno_featured_pages_carousel_func( $atts ) {
      extract(shortcode_atts( array(
         'featured_pages_group' => '',
         'design_param' => '',
         'animation_param' => '',
         'extra_class_param' => '',
      ), $atts ));

      // Get carousel style
      $carousel_style = 'featured-pages-carousel';

      // Enqueue styles
      wp_enqueue_style( 'owl-carousel' );
      // Enqueue scripts
      wp_enqueue_script( 'owl-carousel' );
      // Initialize owl carousel
      wp_enqueue_script( 'owl-carousel-init' );

      // Animation, Design & Extra Class params
      $css_class = Volcanno_Visual_Composer::return_css_class( $design_param, $animation_param, $extra_class_param);
      $data_animate = !empty( $animation_param ) ? ' data-animate="' . $animation_param . '"' : '';

      $output = '<div class="carousel-container' . $css_class . '"' . $data_animate . '><div class="owl-carousel ' . $carousel_style . '" data-type="' . $carousel_style . '">';

      $param_group = vc_param_group_parse_atts( $featured_pages_group );

      if ( $param_group != '' ) {

         foreach ( $param_group as $page => $param ) {

            // Get params
            $title = isset( $param['title'] ) ? $param['title'] : '';
            $image = isset( $param['image'] ) ? Volcanno_Visual_Composer::get_image( $param['image'], array(360, 245), true, 'retina' ) : '';

            // Build link
            $href = isset( $param['href'] ) ? $param['href'] : '';
            $link_content = '<h2>' . esc_html( $title ) . '</h2>';
            $link = Volcanno_Visual_Composer::build_link( $href, $link_content );

            $output .=  '<div class="owl-item">
                           <div class="featured-page-box">
                              <div class="media">' . $image . '</div>
                              <div class="body">' . $link . '</div>
                           </div>
                        </div>';

         }
      }

      $output .= '</div></div>';

      return $output;
   }
}