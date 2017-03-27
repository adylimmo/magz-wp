<?php
/**
 * Visual composer mapping
 */
add_action( 'vc_before_init', 'volcanno_message_box_integrateWithVC' );
if ( !function_exists('volcanno_message_box_integrateWithVC') ) {
	function volcanno_message_box_integrateWithVC() {
		vc_map( array(
			"name" => esc_html__( "Message box", 'consultingpress' ),
			"base" => "volcanno_message_box",
			"class" => "",
			"icon" => get_template_directory_uri() . "/includes/composer-elements/assets/icon-volcanno.png",
			"category" => esc_html__( "Consulting Press", 'consultingpress'),
			"params" => array(
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => esc_html__( "Style", 'consultingpress' ),
					"param_name" => "style",
					"description" => esc_html__( "Here you can choose message box style.", 'consultingpress' ),
					"value" => array(
					   'Information' => 'infobox-default',
					   'Error' => 'errorbox-default',
					   'Success' => 'successbox-default',
					   'Warning' => 'warningbox-default',
					   'Custom' => 'infobox-default custom',
					),
					"std" => 'infobox-default',
				),
				Volcanno_Visual_Composer::icons_lib(),
				Volcanno_Visual_Composer::icons_param('fontawesome'),
				Volcanno_Visual_Composer::icons_param('lynny'),
				array(
					"type" => "textarea",
					"class" => "",
					"heading" => esc_html__( "Text", 'consultingpress' ),
					"param_name" => "text",
					"value" => "",
					"description" => esc_html__( "Here you can enter message box text description.", 'consultingpress' ),
					'admin_label' => true,
				),
				array(
					"type" => "checkbox",
					"class" => "",
					"heading" => esc_html__( "Close button", 'consultingpress' ),
					"param_name" => "close_button",
					"value" => "",
					"description" => esc_html__( "Enable close button on hover", 'consultingpress' ),
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => esc_html__( "Icon color", 'consultingpress' ),
					"param_name" => "icon_color",
					"value" => "",
					"description" => "",
					'dependency' => array(
		               'element' => 'style',
		               'value' => 'infobox-default custom',
		            ),
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => esc_html__( "Text color", 'consultingpress' ),
					"param_name" => "text_color",
					"value" => "",
					"description" => "",
					'dependency' => array(
		               'element' => 'style',
		               'value' => 'infobox-default custom',
		            ),
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => esc_html__( "Background color", 'consultingpress' ),
					"param_name" => "main_color",
					"value" => "",
					"description" => "",
					'dependency' => array(
		               'element' => 'style',
		               'value' => 'infobox-default custom',
		            ),
				),
				Volcanno_Visual_Composer::animation_param(),
				Volcanno_Visual_Composer::extra_class_param(),
				Volcanno_Visual_Composer::design_param(),
			)
		));
	}
}

/**
 * Wordpress shortcode
 * 
 * @param  $atts
 * @return string
 */
if ( !function_exists('volcanno_message_box_func') ) {
	function volcanno_message_box_func( $atts ) {
		extract(shortcode_atts( array(
			'style' => 'infobox-default',
			'text' => '',
			'icon_type' => 'icon_fontawesome',
			'icon_fontawesome' => '',
			'icon_lynny' => '',
			'close_button' => '',
			'icon_color' => '',
			'text_color' => '',
			'main_color' => '',
			'design_param' => '',
			'animation_param' => '',
			'extra_class_param' => '',
		), $atts ));

		// Animation, Design & Extra Class params
		$css_class = Volcanno_Visual_Composer::return_css_class( $design_param, $animation_param, $extra_class_param );
		$data_animate = !empty( $animation_param ) ? ' data-animate="' . $animation_param . '"' : '';

	   	// Icons
	   	$icon = $$icon_type;
	   	// Icon color
	   	$icon_color = $icon_color != '' ? ' style="color:' . $icon_color . '"' : '';
	   	// Icon background color 
	   	$icon_bg = $main_color != '' ? ' style="background-color:' . $main_color . '"' : '';

	   	$icon = $icon != '' ? '<div class="icon-container"' . $icon_bg . '><i class="' . $icon . '"' . $icon_color . '></i></div>' : '';

	   	// Main background color
	   	$main_color_alpha = Volcanno_Helper::hex2rgb( $main_color, '0.55', 'string' );
	   	$main_bg = $main_color != '' ? ' style="background-color:' . $main_color_alpha . '"' : '';

	   	// Text color
	   	$text_color = $text_color != '' ? ' style="color:' . $text_color . '"' : '';

	   	// Close button
	   	if ( $close_button ) {
	   		wp_add_inline_script( 'jQuery-volcanno', '/* <![CDATA[ */ jQuery(document).ready(function ($) { "use strict"; VolcannoInclude.messageClose(); if (VolcannoInclude.isTouchDevice() || VolcannoInclude.isIOSDevice()) { VolcannoInclude.messageShowClose(); }}); /* ]]> */');
	   		$close_button = '<span class="message-close"><i class="fa fa-close"></i></span>';
	   	}

	   	$output =	'<div class="message-boxes ' . $style . $css_class . '"' . $data_animate . '>
	   					' . $close_button . '
	                    ' . $icon . '
	                    <div class="notification-container"' . $main_bg . '>
	                        <p' . $text_color . '>' . $text . '</p>
	                    </div>
	                </div>';

		if ( isset( $output ) ) return $output;
		
	}
}