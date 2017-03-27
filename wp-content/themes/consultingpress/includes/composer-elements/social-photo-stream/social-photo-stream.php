<?php
/**
 * Visual composer mapping
 */
add_action( 'vc_before_init', 'volcanno_social_photo_stream_integrateWithVC' );
if ( !function_exists('volcanno_social_photo_stream_integrateWithVC') ) {
   function volcanno_social_photo_stream_integrateWithVC() {
      vc_map( array(
         "name" => esc_html__( "Social Photo Stream", 'consultingpress' ),
         "base" => "volcanno_social_photo_stream",
         "class" => "",
         "icon" => get_template_directory_uri() . "/includes/composer-elements/assets/icon-volcanno.png",
         "category" => esc_html__( "Consulting Press", 'consultingpress'),
         "params" => array(
            array(
               "type" => "dropdown",
               "class" => "",
               "heading" => esc_html__( "Network", 'consultingpress' ),
               "param_name" => "network",
               "description" => esc_html__( "Select social network.", 'consultingpress' ),
               "value" => array(
                    //'DeviantArt' => 'deviantart',
                    'Dribbble' => 'dribbble',
                    'Flickr' => 'flickr',
                    'Instagram' => 'instagram',
                    //'Newsfeed (RSS)' => 'newsfeed',
                    //'Picasa' => 'picasa',
                    //'Pinterest' => 'pinterest',
                    'Youtube' => 'youtube',
                ),
               "std" => 'deviantart',
               "admin_label" => true,
            ),
            array(
               "type" => "textarea",
               "class" => "",
               "heading" => esc_html__( "Username / Channel", 'consultingpress' ),
               "param_name" => "username",
               "value" => "",
               "description" => esc_html__( "Username / Channel", 'consultingpress' ),
            ),
            array(
               "type" => "textarea",
               "class" => "",
               "heading" => esc_html__( "Limit", 'consultingpress' ),
               "param_name" => "limit",
               "value" => "",
               "description" => esc_html__( "Number of images to fetch.", 'consultingpress' ),
               "std" => "9",
            ),
            array(
               "type" => "textarea",
               "class" => "",
               "heading" => esc_html__( "Access token Key", 'consultingpress' ),
               "param_name" => "api_key",
               "value" => "",
               "description" => esc_html__( "Enter Access token key if required.", 'consultingpress' ),
               "std" => "",
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
if ( !function_exists('volcanno_social_photo_stream_func') ) {
    function volcanno_social_photo_stream_func( $atts ) {
        extract(shortcode_atts( array(
            'network' => 'deviantart',
            'username' => '',
            'limit' => '9',
            'api_key' => '',
            'design_param' => '',
            'animation_param' => '',
            'extra_class_param' => '',
        ), $atts ));

        // Animation, Design & Extra Class params
        $css_class = Volcanno_Visual_Composer::return_css_class( $design_param, $animation_param, $extra_class_param);
        $data_animate = !empty( $animation_param ) ? ' data-animate="' . $animation_param . '"' : '';

        /* script */
        wp_enqueue_script('cma-scripts-cma-social-photo-stream-script', get_template_directory_uri() . '/includes/composer-elements/social-photo-stream/assets/socialstream.jquery.js', array('jquery'), '1.0', TRUE);
        /* init */
        wp_add_inline_script( "cma-scripts-cma-social-photo-stream-script", "
            (jQuery)(function ($) {
              /* ================ SOCIAL NETWORK FEED ================ */
              $('.social-feed-" . $network . "').each(function () {
                  var network = $(this).data('network');
                  var username = $(this).data('username');
                  var limit = $(this).data('limit');
                  var apiKey = $(this).data('api');

                  $(this).socialstream({
                      socialnetwork: network,
                      username: username,
                      limit: limit,
                      apikey: apiKey,
                      accessToken: apiKey
                  });
              });
            });"
        );

        $network = sanitize_text_field($network);
        $username = sanitize_text_field($username);
        $limit = intval($limit);

        $output = '<div class="social-feed ' . $network . '-feed' . $css_class . ' social-feed-' . $network . '"' . $data_animate . ' data-network="' . esc_attr( $network ) . '" data-username="' . esc_attr( $username ) . '" data-limit="' . esc_attr( $limit ) . '" data-api="' . esc_attr( $api_key ) . '"></div>';

        return $output;
      

      return $output;
   }
}