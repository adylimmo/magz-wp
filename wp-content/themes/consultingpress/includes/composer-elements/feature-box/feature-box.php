<?php

/**
 * Visual composer mapping
 */
add_action( 'vc_before_init', 'volcanno_feature_box_integrateWithVC' );
if ( !function_exists( 'volcanno_feature_box_integrateWithVC' ) ) {

    function volcanno_feature_box_integrateWithVC() {
        vc_map( array(
            "name" => esc_html__( "Feature box", 'consultingpress' ),
            "base" => "volcanno_feature_box",
            "class" => "",
            "icon" => get_template_directory_uri() . "/includes/composer-elements/assets/icon-volcanno.png",
            "category" => esc_html__( "Consulting Press", 'consultingpress' ),
            "params" => array(
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "heading" => esc_html__( "Style", 'consultingpress' ),
                    "param_name" => "style",
                    "description" => esc_html__( "Here you can choose box style.", 'consultingpress' ),
                    "value" => array(
                        'Boxed - simple' => 'feature-box',
                        'Boxed - icon' => 'boxed',
                        'Boxed - background' => 'boxed-background',
                        'Boxed & Shadow' => 'service-box-03',
                        'Image - Read more' => 'image',
                        'Image - Circled icon' => 'image-circled',
                        'List' => 'list',
                        'Small Icons' => 'icons-sm',
                        'Circled Icons' => 'service-box-02',
                        'Custom Pages Links' => 'cp-links'
                    ),
                    "std" => 'boxed',
                ),
                array(
                    "type" => "attach_image",
                    "class" => "",
                    "heading" => esc_html__( "Image", 'consultingpress' ),
                    "param_name" => "image",
                    "value" => "",
                    "description" => "",
                    'dependency' => array(
                        'element' => 'style',
                        'value' => array( 'image', 'image-circled', 'cp-links' ),
                    ),
                ),
                Volcanno_Visual_Composer::icons_lib( 'icon_type', array(
                    'element' => 'style',
                    'value' => array( 'boxed', 'service-box-03', 'image', 'image-circled', 'list', 'icons-sm', 'service-box-02', 'cp-links', 'boxed-background' ),
                        )
                ),
                Volcanno_Visual_Composer::icons_param( 'fontawesome' ),
                Volcanno_Visual_Composer::icons_param( 'lynny' ),
                array(
                    "type" => "colorpicker",
                    "class" => "",
                    "heading" => esc_html__( "Icon color", 'consultingpress' ),
                    "param_name" => "icon_color",
                    "value" => "",
                    "description" => esc_html__( "By default is theme color.", 'consultingpress' ),
                    'dependency' => array(
                        'element' => 'icon_type',
                        'not_empty' => true,
                    ),
                ),
                array(
                    "type" => "colorpicker",
                    "class" => "",
                    "heading" => esc_html__( "Icon background color", 'consultingpress' ),
                    "param_name" => "icon_background_color",
                    "value" => "",
                    "description" => esc_html__( "By default is theme color.", 'consultingpress' ),
                    'dependency' => array(
                        'element' => 'style',
                        'value' => array( 'image-circled' ),
                    ),
                ),
                array(
                    "type" => "attach_image",
                    "class" => "",
                    "heading" => esc_html__( "Image icon", 'consultingpress' ),
                    "param_name" => "image_icon",
                    "value" => "",
                    "description" => esc_html__( "Use image as icon. This overide icon from iconpicker.", 'consultingpress' ),
                    'dependency' => array(
                        'element' => 'style',
                        'value' => array( 'image-circled' ),
                    ),
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
                    "dependency" => array(
                        'element' => 'style',
                        'value_not_equal_to' => 'image-circled',
                    ),
                ),
                // List
                array(
                    'type' => 'param_group',
                    'value' => '',
                    'param_name' => 'list',
                    "heading" => esc_html__( "List with icons", 'consultingpress' ),
                    "description" => esc_html__( "Here you can create list with icons.", 'consultingpress' ),
                    'dependency' => array(
                        'element' => 'style',
                        'value' => 'list',
                    ),
                    'params' => array(
                        Volcanno_Visual_Composer::icons_lib(),
                        Volcanno_Visual_Composer::icons_param( 'fontawesome' ),
                        Volcanno_Visual_Composer::icons_param( 'lynny' ),
                        array(
                            "type" => "textarea",
                            "holder" => "",
                            "class" => "",
                            "heading" => esc_html__( "Text", 'consultingpress' ),
                            "param_name" => "text",
                            "value" => "",
                            "description" => esc_html__( "Here you can enter list element text.", 'consultingpress' ),
                            "admin_label" => true,
                        ),
                    )
                ),
                array(
                    "type" => "vc_link",
                    "class" => "",
                    "heading" => esc_html__( "Url (Link)", 'consultingpress' ),
                    "param_name" => "href",
                    "value" => "",
                    "description" => "",
                    "dependency" => array(
                        'element' => 'style',
                        'value' => array( 'boxed', 'service-box-03', 'image', 'image-circled', 'list', 'icons-sm', 'service-box-02', 'cp-links', 'boxed-background' ),
                    )
                ),
                array(
                    "type" => "textfield",
                    "class" => "",
                    "heading" => esc_html__( "Read more text", 'consultingpress' ),
                    "param_name" => "read_more",
                    "value" => esc_html__( "Read more", 'consultingpress' ),
                    "description" => "",
                    'dependency' => array(
                        'element' => 'style',
                        'value' => array( 'image', 'boxed-background' ),
                    ),
                ),
                array(
                   "type" => "checkbox",
                   "heading" => esc_html__( "White text", 'consultingpress' ),
                   "param_name" => "text_color",
                   "value" => "",
                   "description" => esc_html__( "Use for dark backgrounds", 'consultingpress' ),
                   'dependency' => array(
                        'element' => 'style',
                        'value' => array( 'boxed-background' ),
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
if ( !function_exists( 'volcanno_feature_box_func' ) ) {

    function volcanno_feature_box_func( $atts ) {
        extract( shortcode_atts( array(
            'style' => 'boxed',
            'image' => '',
            'title' => '',
            'text' => '',
            'list' => '',
            'icon_type' => 'icon_fontawesome',
            'icon_fontawesome' => '',
            'icon_lynny' => '',
            'icon_color' => '',
            'image_icon' => '',
            'icon_background_color' => '',
            'href' => '',
            'read_more' => esc_html__( "Read more", 'consultingpress' ),
            'text_color' => '',
            'design_param' => '',
            'animation_param' => '',
            'extra_class_param' => '',
        ), $atts ) );

        // Animation, Design & Extra Class params
        $css_class = Volcanno_Visual_Composer::return_css_class( $design_param, $animation_param, $extra_class_param );
        $data_animate = !empty( $animation_param ) ? ' data-animate="' . $animation_param . '"' : '';

        // Background for icon
        $icon_background_color = !empty( $icon_background_color ) ? ' style="background-color:' . $icon_background_color . '"' : '';

        // Icons
        $icon = $$icon_type;
        $icon_color = $icon_color != '' ? ' style="color:' . $icon_color . '"' : '';
        $icon = $icon != '' ? '<div class="icon-container"' . $icon_background_color . '><i class="' . $icon . '"' . $icon_color . '></i></div>' : '';

        // Title 
        $title_html = $title != '' ? '<h3>' . $title . '</h3>' : '';
        $title_html = $style == 'cp-links' ? '<h2>' . $title . '</h2>' : $title_html;

        // Link
        $title = Volcanno_Visual_Composer::build_link( $href, $title_html );

        // Read more
        $read_more = $read_more != '' ? $read_more : '';
        $read_more = Volcanno_Visual_Composer::build_link( $href, $read_more, array( 'class' => 'read-more' ) );

        // Image
        $image = $image != '' ? Volcanno_Visual_Composer::get_image( $image, array( 360, 245 ), true, 'retina' ) : '';
        $image = $href != '' ? Volcanno_Visual_Composer::build_link( $href, $image ) : $image;

        // Text
        $text = $text = !'' ? '<p>' . $text . '</p>' : '';

        // Text color
        $white_text = !empty( $text_color ) ? ' dark' : '';

        if ( $style == 'boxed' ) {

            $output =   '<div class="service-box service-box-01' . $css_class . '"' . $data_animate . '>
	                        ' . $icon . '
	                        ' . $title . '
	                        ' . $text . '
	                    </div>';

        } else if ( $style == 'image' ) {

            $output =   '<div class="service-box service-box-04' . $css_class . '"' . $data_animate . '>
	                        <div class="media">' . $image . '</div>
	                        ' . $icon . '
	                        <div class="text-container">
	                            ' . $title . '
	                            ' . $text . '
	                            ' . $read_more . '
	                        </div>
	                    </div>';

        } else if ( $style == 'image-circled' ) {

            // Use image for icon if is selected else use icon from iconpicker
            $image_icon = !empty( $image_icon ) ? '<div class="icon-container"' . $icon_background_color . '>' . Volcanno_Visual_Composer::get_image( $image_icon, array(20, 40), true, 'retina' ) . '</div>' : $icon;
            
            $output =   '<ul class="industry-sectors-grid">
                            <li>
                                <div class="feature-box service-box-05' . $css_class . '"' . $data_animate . '">
                                    <div class="media">' . $image . '</div>
                                    ' . $image_icon . '
                                    <div class="text-container">
                                        ' . $title . '
                                    </div>
                                </div>
                            </li>
                        </ul>';

        } else if ( $style == 'list' ) {

            // List
            $list_group = vc_param_group_parse_atts( $list );
            $list = '';
            // Return if elements are empty
            if ( !empty( $list_group ) ) {
                foreach ( $list_group as $li => $param ) {
                    if ( isset( $param['text'] ) ) {
                        // Get icon class
                        $list_icon = $param[$param['icon_type']];
                        $list .= '<li><i class="' . $list_icon . '"></i><p>' . $param['text'] . '</p>';
                    }
                }
            }

            $output =   '<div class="service-box service-box-02' . $css_class . '"' . $data_animate . '>
	                        ' . $icon . '
	                        <div class="text-container">
	                            ' . $title . '
								' . $text . '
	                            <ul class="fa-ul default">' . $list . '</ul>
	                        </div>
	                    </div>';
        } else if ( $style == 'icons-sm' ) {

            $output =   '<ul class="service-box service-box-listed clearfix' . $css_class . '"' . $data_animate . '>
		                    <li>
		                        ' . $icon . '
		                        <div class="text-container">
		                            ' . $title . '
		                            ' . $text . '
		                        </div>
		                    </li>
		                </ul>';

        } else if ( $style == 'service-box-02' || $style == 'service-box-03' ) {

            $output =   '<div class="service-box ' . $style . $css_class . '">
	                        ' . $icon . '
	                        <div class="text-container">
	                            ' . $title . '
	                            ' . $text . '
	                        </div>
	                    </div>';

        } else if ( $style == 'cp-links' ) {

            $output =   '<div class="featured-page-box' . $css_class . '"' . $data_animate . '>
	                        <div class="media">' . $image . '</div>
	                        <div class="body">
	                            ' . $title . '
	                        </div>
	                    </div>';

        } else if ( $style == 'feature-box' ) {

            $output =   '<div class="feature-box custom-background bkg-color-dark dark centered' . $css_class . '"' . $data_animate . '>
                            ' . $title_html . '
                            ' . $text . '
                        </div>';

        } else if ( $style == 'boxed-background' ) {

            $output =   '<div class="feature-box custom-background bkg-color-light-grey' . $css_class . $white_text . '"' . $data_animate . '>
	                        ' . $icon . '
	                        <div class="text-container">
	                            ' . $title_html . '
	                            ' . $text . '
	                            ' . $read_more . '
	                        </div>
	                    </div>';

        };

        if ( isset( $output ) ) {
            return $output;
        }
    }

}