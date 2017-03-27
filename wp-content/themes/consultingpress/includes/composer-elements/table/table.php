<?php
/**
 * Visual composer mapping
 */
add_action( 'vc_before_init', 'volcanno_table_integrateWithVC' );
if ( !function_exists('volcanno_table_integrateWithVC') ) {
   function volcanno_table_integrateWithVC() {
      vc_map( array(
         "name" => esc_html__( "Table", 'consultingpress' ),
         "base" => "volcanno_table",
         "class" => "",
         "icon" => get_template_directory_uri() . "/includes/composer-elements/assets/icon-volcanno.png",
         "category" => esc_html__( "Consulting Press", 'consultingpress'),
         "params" => array(
            array(
               "type" => "textarea_html",
               "class" => "",
               "heading" => esc_html__( "Table", 'consultingpress' ),
               "param_name" => "content",
               "value" => "",
               "description" => esc_html__( "Here you can create table. All other content except table will be removed. You can have only one table per element.", 'consultingpress' )
            ),
            array(
               "type" => "dropdown",
               "class" => "",
               "heading" => esc_html__( "Table type", 'consultingpress' ),
               "param_name" => "type",
               "value" => array(
                  'Simple' => '',
                  'Events' => 'events-table',
                  'Hover' => 'table-hover',
               ),
               "description" => ""
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
if ( !function_exists('volcanno_table_func') ) {
   function volcanno_table_func( $atts, $content = '' ) {
      extract(shortcode_atts( array(
         'type' => '',
         'design_param' => '',
         'animation_param' => '',
         'extra_class_param' => '',
      ), $atts ));

      // Animation, Design & Extra Class params
      $css_class = Volcanno_Visual_Composer::return_css_class( $design_param, $animation_param, $extra_class_param );
      $data_animate = !empty( $animation_param ) ? ' data-animate="' . $animation_param . '"' : '';

      // Remove all attributes from elements
      $table = preg_replace('/ (?!href).*?=".*?"/', '', $content);
      // Remove all tags except table
      preg_match_all('(<table>[\s\S]*?<\/table>)', $table, $table);
      $table = $table[0][0];
      // Adds class to table
      $table = preg_replace('/<table>/', '<table class="table ' . $type . '">', $table);
      // Adds thead to first tr
      $table = preg_replace('/<tbody>[\s\S](<tr>[\s\S]*?<\/tr>)/', '<thead>$1</thead>', $table);
      // Remove line breaks
      $table = preg_replace('/\n/', '', $table);
      // Extract table thead
      preg_match_all('/(.*?)(<thead>.*?<\/thead>)(.*)/', $table, $table);
      // Replace td to th in thead
      $thead = preg_replace('/<td>/', '<th>', $table[2][0]);
      // Replace /td to /th
      $thead = preg_replace('/<\/td>/', '</th>', $thead);
      // Glue table
      $table = $table[1][0] . $thead . $table[3][0];

      $output = '<div class="table-responsive' . $css_class . '"' . $data_animate . '>' . $table . '</div>';
      
      return balancetags( $output, true );
   }
}

/**
 * Enable table button in mce
 * @param  array $buttons
 * @return array          
 */
function volcanno_enable_more_buttons($buttons) {

    $buttons[] = 'table';
    return $buttons;

}
add_filter("mce_buttons", "volcanno_enable_more_buttons");

/**
 * Load mce_external_plugins
 * @param  array $mce_plugins 
 * @return array
 */
function volcanno_mce_external_plugins( $mce_plugins ) {

    $mce_plugins["table"] = VOLCANNO_TEMPLATEURL . '/includes/assets/tinymce/tinymce/plugins/table/plugin.min.js';

    return $mce_plugins;
}
add_filter( 'mce_external_plugins', 'volcanno_mce_external_plugins', 999 );