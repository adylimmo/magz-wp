<?php
/**
 * Visual composer mapping
 */
add_action( 'vc_before_init', 'volcanno_pricing_table_integrateWithVC' );
if ( !function_exists('volcanno_pricing_table_integrateWithVC') ) {
   function volcanno_pricing_table_integrateWithVC() {
      vc_map( array(
         "name" => esc_html__( "Pricing table", 'consultingpress' ),
         "base" => "volcanno_pricing_table",
         "class" => "",
         "icon" => get_template_directory_uri() . "/includes/composer-elements/assets/icon-volcanno.png",
         "category" => esc_html__( "Consulting Press", 'consultingpress'),
         "params" => array(
            array(
               "type" => "dropdown",
               "class" => "",
               "heading" => esc_html__( "List style", 'consultingpress' ),
               "param_name" => "table_style",
               "description" => esc_html__( "Here you can choose how to display pricing table.", 'consultingpress' ),
               "value" => array(
                  'Standard style' => 'standard',
                  'Active style' => 'active',
               ),
               "std" => 'standard',
            ),
            array(
               "type" => "textfield",
               "holder" => "",
               "class" => "",
               "heading" => esc_html__( "Title", 'consultingpress' ),
               "param_name" => "title",
               "value" => "",
               "description" => esc_html__( "Here you can enter pricing table title.", 'consultingpress' ),
               "admin_label" => true,
            ),
            array(
               "type" => "textfield",
               "holder" => "",
               "class" => "",
               "heading" => esc_html__( "Price", 'consultingpress' ),
               "param_name" => "price",
               "value" => esc_html__( "{\$2500} / project", 'consultingpress' ),
               "description" => esc_html__( "Wrap price into { } . eg. {\$8000} / project.", 'consultingpress' ),
            ),
            // default
            array(
               'type' => 'param_group',
               'value' => '',
               'param_name' => 'features_group',
               'params' => array(
                  array(
                     "type" => "textarea",
                     "holder" => "",
                     "class" => "",
                     "heading" => esc_html__( "Feature text", 'consultingpress' ),
                     "param_name" => "text",
                     "value" => "",
                     "description" => esc_html__( "Here you can enter list element text.", 'consultingpress' ),
                     "admin_label" => true,
                  ),
               )
            ),
            array(
               "type" => "textfield",
               "holder" => "",
               "class" => "",
               "heading" => esc_html__( "Button text", 'consultingpress' ),
               "param_name" => "button_text",
               "value" => esc_html__( "Contact us", 'consultingpress' ),
               "description" => "",
            ),
            array(
               "type" => "vc_link",
               "holder" => "",
               "class" => "",
               "heading" => esc_html__( "Button url", 'consultingpress' ),
               "param_name" => "button_href",
               "value" => "",
               "description" => "",
            ),
            array(
               "type" => "colorpicker",
               "holder" => "",
               "class" => "",
               "heading" => esc_html__( "Button color", 'consultingpress' ),
               "param_name" => "button_color",
               "value" => "",
               "description" => "",
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
if ( !function_exists('volcanno_pricing_table_func') ) {
   function volcanno_pricing_table_func( $atts ) {
      extract(shortcode_atts( array(
         'table_style' => 'default',
         'title' => '',
         'price' => esc_html__( "{\$2500} / project", 'consultingpress' ),
         'button_text' => 'Contact us',
         'button_href' => '',
         'button_color' => '',
         'features_group' => '',
         'design_param' => '',
         'animation_param' => '',
         'extra_class_param' => '',
      ), $atts ));

      // Animation, Design & Extra Class params
      $css_class = Volcanno_Visual_Composer::return_css_class( $design_param, $animation_param, $extra_class_param);
      $data_animate = !empty( $animation_param ) ? ' data-animate="' . $animation_param . '"' : '';

      $price = preg_replace( '/\{(.*?)\}(.*?$)/', '<span>$1</span>$2', $price );

      $active = $table_style == 'active' ? ' active' : '';

      $btn_color = $table_style == 'default' ? ' btn-blue' : '';

      $output =   '<div class="pricing-table' . $active . $css_class . '"' . $data_animate . '>
                     <ul>
                        <li class="head">
                           <h2>' . $title . '</h2>
                           <p class="price">' . $price . '</p>
                        </li>';

      $param_group = vc_param_group_parse_atts( $features_group );

      // Return if elements are empty
      if ( !empty( $param_group ) ) :

         foreach ($param_group as $index => $list) {

            $output .=     '<li>' . $list['text'] . '</li>';

         }

      endif;

      // Build link
      $button_content = '<span><i class="lynny-arrow-circle-right"></i>' . $button_text . '</span>';
      $button_color = $button_color != '' ? 'background-color:' . $button_color : '';
      $button = Volcanno_Visual_Composer::build_link( $button_href, $button_content, $params = array(
            'class' => 'btn icon-animated' . $btn_color . ' btn-center', 
            'style' => $button_color,
         ) 
      );

      $output .=        '<li class="pricing-footer">' . $button . '</li>
                     </ul>
                  </div>';

      return $output;

   }
}