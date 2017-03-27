<?php
/**
 * Visual composer mapping
 */
add_action( 'vc_before_init', 'volcanno_blockquote_integrateWithVC' );
if ( !function_exists('volcanno_blockquote_integrateWithVC') ) {
   function volcanno_blockquote_integrateWithVC() {
      vc_map( array(
         "name" => esc_html__( "Blockquote", 'consultingpress' ),
         "base" => "volcanno_blockquote",
         "class" => "",
         "icon" => get_template_directory_uri() . "/includes/composer-elements/assets/icon-volcanno.png",
         "category" => esc_html__( "Consulting Press", 'consultingpress'),
         "params" => array(
            array(
               "type" => "textarea_html",
               "class" => "",
               "heading" => esc_html__( "Blockquote", 'consultingpress' ),
               "param_name" => "content",
               "value" => "",
               "description" => esc_html__( "Here you can blockquote text.", 'consultingpress' )
            ),
            array(
               "type" => "textfield",
               "holder" => "",
               "class" => "",
               "heading" => esc_html__( "Author", 'consultingpress' ),
               "param_name" => "author",
               "value" => "",
               "description" => esc_html__( "Here you can enter blockquote author.", 'consultingpress' ),
               "admin_label" => true,
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
if ( !function_exists('volcanno_blockquote_func') ) {
   function volcanno_blockquote_func( $atts, $content = '' ) {
      extract(shortcode_atts( array(
         'author' => '',
         'design_param' => '',
         'animation_param' => '',
         'extra_class_param' => '',
      ), $atts ));

      global $volcanno_theme_config;

      // Animation, Design & Extra Class params
      $css_class = Volcanno_Visual_Composer::return_css_class( $design_param, $animation_param, $extra_class_param, '', true);

      $output = '<blockquote' . $css_class . '>' . balancetags( '<p>' . $content, true ) . '<cite>' . wp_kses( $author, $volcanno_theme_config['allowed_html_tags'] ) . '</cite></blockquote>';

      return $output;
   }
}