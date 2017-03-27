<?php
if ( !function_exists('volcanno_add_tabs_style_type') ) {
   function volcanno_add_tabs_style_type() {

      /**
       * Vertical tabs 
       */
      $param = WPBMap::getParam( 'vc_tta_tour', 'style' );
      $param['value'][__( 'Consulting Press - Transparent', 'consultingpress' )] = 'consulting-press-theme-color-transparent';
      $param['value'][__( 'Consulting Press - Theme Color', 'consultingpress' )] = 'consulting-press-theme-color';
      $param['value'][__( 'Consulting Press - Dark Color', 'consultingpress' )] = 'consulting-press-dark-color';
      vc_update_shortcode_param( 'vc_tta_tour', $param );

      // SHAPE
      $param = WPBMap::getParam( 'vc_tta_tour', 'shape' );
      $param['dependency']['element'] = 'style';
      $param['dependency']['value'] = array('classic', 'modern', 'flat', 'outline');
      vc_update_shortcode_param( 'vc_tta_tour', $param );

      // Color
      $param = WPBMap::getParam( 'vc_tta_tour', 'color' );
      $param['dependency']['element'] = 'style';
      $param['dependency']['value'] = array('classic', 'modern', 'flat', 'outline');
      vc_update_shortcode_param( 'vc_tta_tour', $param );

      // pagination_style
      $param = WPBMap::getParam( 'vc_tta_tour', 'pagination_style' );
      $param['dependency']['element'] = 'style';
      $param['dependency']['value'] = array('classic', 'modern', 'flat', 'outline');
      vc_update_shortcode_param( 'vc_tta_tour', $param );

      // Spacing
      $param = WPBMap::getParam( 'vc_tta_tour', 'spacing' );
      $param['dependency']['element'] = 'style';
      $param['dependency']['value'] = array('classic', 'modern', 'flat', 'outline');
      vc_update_shortcode_param( 'vc_tta_tour', $param );

      // Gap
      $param = WPBMap::getParam( 'vc_tta_tour', 'gap' );
      $param['dependency']['element'] = 'style';
      $param['dependency']['value'] = array('classic', 'modern', 'flat', 'outline');
      vc_update_shortcode_param( 'vc_tta_tour', $param );

      // Position
      $param = WPBMap::getParam( 'vc_tta_tour', 'tab_position' );
      $param['dependency']['element'] = 'style';
      $param['dependency']['value'] = array('classic', 'modern', 'flat', 'outline');
      vc_update_shortcode_param( 'vc_tta_tour', $param );

      // Alignment
      $param = WPBMap::getParam( 'vc_tta_tour', 'alignment' );
      $param['dependency']['element'] = 'style';
      $param['dependency']['value'] = array('classic', 'modern', 'flat', 'outline');
      vc_update_shortcode_param( 'vc_tta_tour', $param );


      /**
       * Horizontal tabs
       */
      $param = WPBMap::getParam( 'vc_tta_tabs', 'style' );
      $param['value'][__( 'Consulting Press - Transparent', 'consultingpress' )] = 'consulting-press-theme-color-transparent';
      $param['value'][__( 'Consulting Press - Theme Color', 'consultingpress' )] = 'consulting-press-theme-color';
      $param['value'][__( 'Consulting Press - Dark Color', 'consultingpress' )] = 'consulting-press-dark-color';
      vc_update_shortcode_param( 'vc_tta_tabs', $param );

      // SHAPE
      $param = WPBMap::getParam( 'vc_tta_tabs', 'shape' );
      $param['dependency']['element'] = 'style';
      $param['dependency']['value'] = array('classic', 'modern', 'flat', 'outline');
      vc_update_shortcode_param( 'vc_tta_tabs', $param );

      // Color
      $param = WPBMap::getParam( 'vc_tta_tabs', 'color' );
      $param['dependency']['element'] = 'style';
      $param['dependency']['value'] = array('classic', 'modern', 'flat', 'outline');
      vc_update_shortcode_param( 'vc_tta_tabs', $param );

      // pagination_style
      $param = WPBMap::getParam( 'vc_tta_tabs', 'pagination_style' );
      $param['dependency']['element'] = 'style';
      $param['dependency']['value'] = array('classic', 'modern', 'flat', 'outline');
      vc_update_shortcode_param( 'vc_tta_tabs', $param );

      // Spacing
      $param = WPBMap::getParam( 'vc_tta_tabs', 'spacing' );
      $param['dependency']['element'] = 'style';
      $param['dependency']['value'] = array('classic', 'modern', 'flat', 'outline');
      vc_update_shortcode_param( 'vc_tta_tabs', $param );

      // Gap
      $param = WPBMap::getParam( 'vc_tta_tabs', 'gap' );
      $param['dependency']['element'] = 'style';
      $param['dependency']['value'] = array('classic', 'modern', 'flat', 'outline');
      vc_update_shortcode_param( 'vc_tta_tabs', $param );

      // Position
      $param = WPBMap::getParam( 'vc_tta_tabs', 'tab_position' );
      $param['dependency']['element'] = 'style';
      $param['dependency']['value'] = array('classic', 'modern', 'flat', 'outline');
      vc_update_shortcode_param( 'vc_tta_tabs', $param );

      // Alignment
      $param = WPBMap::getParam( 'vc_tta_tabs', 'alignment' );
      $param['dependency']['element'] = 'style';
      $param['dependency']['value'] = array('classic', 'modern', 'flat', 'outline');
      vc_update_shortcode_param( 'vc_tta_tabs', $param );

   }
}
add_action( 'vc_after_init', 'volcanno_add_tabs_style_type' );