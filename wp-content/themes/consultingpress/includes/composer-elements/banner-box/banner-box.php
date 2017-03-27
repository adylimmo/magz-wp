<?php
/**
 * Visual composer mapping
 */
add_action( 'vc_before_init', 'volcanno_banner_box_integrateWithVC' );
if ( !function_exists('volcanno_banner_box_integrateWithVC') ) {
	function volcanno_banner_box_integrateWithVC() {
		vc_map( array(
			"name" => esc_html__( "Banner box", 'consultingpress' ),
			"base" => "volcanno_banner_box",
			"class" => "",
			"icon" => get_template_directory_uri() . "/includes/composer-elements/assets/icon-volcanno.png",
			"category" => esc_html__( "Consulting Press", 'consultingpress'),
			"params" => array(
				Volcanno_Visual_Composer::icons_lib(),
				Volcanno_Visual_Composer::icons_param('fontawesome'),
				Volcanno_Visual_Composer::icons_param('lynny'),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => esc_html__( "Icon color", 'consultingpress' ),
					"param_name" => "icon_color",
					"value" => "",
					"description" => esc_html__( "By default is theme color.", 'consultingpress' ),
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => esc_html__( "Title", 'consultingpress' ),
					"param_name" => "title",
					"value" => "",
					"description" => esc_html__( "Here you can enter main title.", 'consultingpress' ),
					"admin_label" => true,
				),
				array(
					"type" => "textarea",
					"class" => "",
					"heading" => esc_html__( "Text", 'consultingpress' ),
					"param_name" => "text",
					"value" => "",
					"description" => esc_html__( "Here you can enter feature box text description.", 'consultingpress' ),
				),
				array(
					"type" => "vc_link",
					"class" => "",
					"heading" => esc_html__( "Url (Link)", 'consultingpress' ),
					"param_name" => "href",
					"value" => "",
					"description" => "",
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => esc_html__( "Button text", 'consultingpress' ),
					"param_name" => "button_text",
					"value" => esc_html__( "Contact us", 'consultingpress' ),
					"description" => "",
					'dependency' => array(
		               'element' => 'style',
		               'value' => 'image',
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
if ( !function_exists('volcanno_banner_box_func') ) {
	function volcanno_banner_box_func( $atts ) {
		extract(shortcode_atts( array(
			'title' => '',
			'text' => '',
			'icon_type' => 'icon_fontawesome',
			'icon_fontawesome' => '',
			'icon_lynny' => '',
			'icon_color' => '',
			'href' => '',
			'button_text' => esc_html__( "Contact us", 'consultingpress' ),
			'design_param' => '',
			'animation_param' => '',
			'extra_class_param' => '',
		), $atts ));

		global $volcanno_theme_config;

		// Animation, Design & Extra Class params
		$css_class = Volcanno_Visual_Composer::return_css_class( $design_param, $animation_param, $extra_class_param );
		$data_animate = !empty( $animation_param ) ? ' data-animate="' . $animation_param . '"' : '';

	   	// Icons
	   	$icon = $$icon_type;
	   	$icon_color = !empty( $icon_color ) ? ' style="color:' . $icon_color . '"' : '';
	   	$icon = !empty( $icon ) ? '<i class="' . $icon . '"' . $icon_color . '></i>' : '';

	   	// Title 
	   	$title_html = !empty( $title ) ? '<h3>' . $title . '</h3>' : '';

	   	// Link
	   	$button = Volcanno_Visual_Composer::build_link( $href, '<span><i class="lynny-arrow-circle-right"></i>' . esc_html( $button_text ) . '</span>', array('class' => 'btn icon-animated btn-blue btn-center') );

	   	// Text
	   	$text = $text =! '' ? '<p>' . $text . '</p>' : '';
	   	
	   	$output = balancetags( '<div class="banner-box' . $css_class . '"' . $data_animate . '>' . $icon . $title_html . wp_kses( $text, $volcanno_theme_config['allowed_html_tags'] ) . $button . '</div>', true );

		if ( isset( $output ) ) return $output;

	}
}