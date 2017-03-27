<?php
/**
 * Visual composer mapping
 */
add_action( 'vc_before_init', 'volcanno_nivo_slider_integrateWithVC' );
if ( !function_exists('volcanno_nivo_slider_integrateWithVC') ) {
   function volcanno_nivo_slider_integrateWithVC() {
      vc_map( array(
         "name" => esc_html__( "Nivo slider", 'consultingpress' ),
         "base" => "volcanno_nivo_slider",
         "class" => "",
         "icon" => get_template_directory_uri() . "/includes/composer-elements/assets/icon-volcanno.png",
         "category" => esc_html__( "Consulting Press", 'consultingpress'),
         "params" => array(
            array(
               "type" => "dropdown",
               "class" => "",
               "heading" => esc_html__( "Navigation style", 'consultingpress' ),
               "param_name" => "nivo_style",
               "description" => esc_html__( "Here you can choose main title size", 'consultingpress' ),
               "value" => array(
                  'Dots' => 'nivo-slider-02',
                  'Arrows' => 'nivo-slider-01',
               ),
               "std" => 'nivo-slider-02',
            ),
            // Large icons - simple
            array(
               'type' => 'param_group',
               'value' => '',
               'param_name' => 'slide_group',
               'params' => array(
                  array(
                     "type" => "attach_image",
                     "holder" => "",
                     "class" => "",
                     "heading" => esc_html__( "Image", 'consultingpress' ),
                     "param_name" => "image",
                     "value" => "",
                     "description" => esc_html__( "Here you can upload slide image.", 'consultingpress' ),
                     "admin_label" => true,
                  ),
                  array(
                     "type" => "textfield",
                     "holder" => "",
                     "class" => "",
                     "heading" => esc_html__( "Title", 'consultingpress' ),
                     "param_name" => "title",
                     "value" => "",
                     "description" => esc_html__( "Here you can enter slide title.", 'consultingpress' ),
                     "admin_label" => true,
                  ),
                  array(
                     "type" => "colorpicker",
                     "heading" => esc_html__( "Title color", 'consultingpress' ),
                     "param_name" => "title_color",
                     "value" => '', 
                     "description" => esc_html__( "Custom title color", 'consultingpress' ),
                  ),
                  array(
                     "type" => "textarea",
                     "holder" => "",
                     "class" => "",
                     "heading" => esc_html__( "Caption", 'consultingpress' ),
                     "param_name" => "caption",
                     "value" => "",
                     "description" => esc_html__( "Here you can enter slide caption.", 'consultingpress' ),
                  ),
                  array(
                     "type" => "colorpicker",
                     "heading" => esc_html__( "Caption color", 'consultingpress' ),
                     "param_name" => "caption_color",
                     "value" => '', 
                     "description" => esc_html__( "Custom caption color", 'consultingpress' ),
                  ),
                  array(
                     "type" => "textfield",
                     "holder" => "",
                     "class" => "",
                     "heading" => esc_html__( "Subtext", 'consultingpress' ),
                     "param_name" => "subtext",
                     "value" => "",
                     "description" => esc_html__( "Here you can enter slide subtext.", 'consultingpress' ),
                  ),
                  array(
                     "type" => "colorpicker",
                     "heading" => esc_html__( "Subtext color", 'consultingpress' ),
                     "param_name" => "subtext_color",
                     "value" => '', 
                     "description" => esc_html__( "Custom subtext color", 'consultingpress' ),
                  ),
                  array(
                     "type" => "vc_link",
                     "holder" => "",
                     "class" => "",
                     "heading" => esc_html__( "Subtext link", 'consultingpress' ),
                     "param_name" => "href",
                     "value" => "",
                     "description" => esc_html__( "Here you can add link to subtext.", 'consultingpress' )
                  ),
               )
            ),
            array(
               "type" => "textfield",
               "edit_field_class" => "vc_col-xs-6",
               "heading" => esc_html__( "Width", 'consultingpress' ),
               "param_name" => "width",
               "value" => "1140",
               "description" => esc_html__( "Here you can define slide images width.", 'consultingpress' ),
            ),
            array(
               "type" => "textfield",
               "edit_field_class" => "vc_col-xs-6",
               "heading" => esc_html__( "Height", 'consultingpress' ),
               "param_name" => "height",
               "value" => "530",
               "description" => esc_html__( "Here you can define slide images height.", 'consultingpress' ),
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
if ( !function_exists('volcanno_nivo_slider_func') ) {
   function volcanno_nivo_slider_func( $atts ) {
      extract(shortcode_atts( array(
         'nivo_style' => 'nivo-slider-02',
         'width' => '1140',
         'height' => '530',
         'slide_group' => '',
         'design_param' => '',
         'animation_param' => '',
         'extra_class_param' => '',
      ), $atts ));

      // Enqueue styles
      wp_enqueue_style( 'nivo-slider' );
      wp_enqueue_style( 'nivo-slider-consulting-press' );

      // Enqueue scripts
      wp_enqueue_script( 'nivo-slider' );
      
      // Initialize nivo slider
      wp_add_inline_script( 'nivo-slider', '/* <![CDATA[ */ jQuery(document).ready(function ($) { "use strict"; VolcannoInclude.nivoSliderInit("' . $nivo_style . '"); }); /* ]]> */');

      // Animation, Design & Extra Class params
      $css_class = Volcanno_Visual_Composer::return_css_class( $design_param, $animation_param, $extra_class_param);
      $data_animate = !empty( $animation_param ) ? ' data-animate="' . $animation_param . '"' : '';

      // Parse param group
      $slide_group = vc_param_group_parse_atts( $slide_group );

      // Return if elements are empty
      if ( empty( $slide_group ) ) return;

      $images = $captions = '';

      foreach ($slide_group as $index => $slide) {

         // Get params
         $title_color = isset( $slide['title_color'] ) ? ' style="color:' . $slide['title_color'] . '"' : '';
         $title = isset( $slide['title'] ) ? '<h3' . $title_color . '>' . $slide['title'] . '</h3>' : '';

         $caption_color = isset( $slide['caption_color'] ) ? ' style="color:' . $slide['caption_color'] . '"' : '';
         $caption = isset( $slide['caption'] ) ? '<p' . $caption_color . '>' . $slide['caption'] . '</p>' : '';

         $subtext_color = isset( $slide['subtext_color'] ) ? ' style="color:' . $slide['subtext_color'] . '"' : '';
         $subtext = isset( $slide['subtext'] ) ? '<p' . $subtext_color . '>' . $slide['subtext'] . '</p>' : '';
         
         $image = isset( $slide['image'] ) ? $slide['image'] : '';
         $href = isset( $slide['href'] ) ? $slide['href'] : '';

         // Build link
         $link = ( !empty($subtext) && !empty($href) ) ? Volcanno_Visual_Composer::build_link( $href, $slide['subtext'], array('class' => 'read-more'), $subtext ) : $subtext;
         // Get image
         $img_title = (!empty($title) || !empty($caption) ) ? array('title' => '#slider-caption-' . $index) : array();
         $images .= Volcanno_Visual_Composer::get_image( $image, array($width, $height), false, 'responsive', false, $img_title );

         $captions .= (!empty($title) || !empty($caption) ) ? '<div id="slider-caption-' . $index . '">' . $title . $caption . $link . '</div>' : '';

      }

      $output =   '<div class="slider-wrapper' . $css_class . '"' . $data_animate . '>
                        <div class="nivo-slider nivoSlider">' . $images . '</div>
                        ' . $captions . '
                    </div>';

      return $output;

   }
}