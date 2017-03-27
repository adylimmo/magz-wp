<?php

/* ---------------------------------------------------------
 * Plugins
 *
 * Class with functions related to plugins 
 * necesarry for proper theme work
  ---------------------------------------------------------- */

class Volcanno_Core_Plugins {

    /**
     * Check if required plugins are loaded
     *
     * @return void
     */
    static function plugins_loaded() {

        // check if Visual Composer plugin is loaded
        if ( class_exists('Vc_Manager') ) {
            define( 'VOLCANNO_VISUAL_COMPOSER', true );
        } else {
            define( 'VOLCANNO_VISUAL_COMPOSER', false );
        }

        // check if Volcanno Shortcodes plugin is loaded
        if ( class_exists('Volcanno_Visual_Composer') ) {
            define( 'VOLCANNO_PI_SHORTCODES', true );
        } else {
            define( 'VOLCANNO_PI_SHORTCODES', false );
        }

        // check if MetaBox plugin is loaded
        if ( class_exists('RW_Meta_Box') ) {
            define( 'VOLCANNO_META_BOX', true );
        } else {
            define( 'VOLCANNO_META_BOX', false );
        }

        // check if Volcanno Custom Post Types plugin is loaded
        if ( function_exists( 'vcpt_cpt_config' ) ) {
            define( 'VOLCANNO_CPTS', true );
        } else {
            define( 'VOLCANNO_CPTS', false );
        }
        
        // check if Redux Framework plugin is loaded
        if ( class_exists('ReduxFrameworkPlugin') ) {
            define( 'VOLCANNO_REDUX_FRAMEWORK', true );
        } else {
            define( 'VOLCANNO_REDUX_FRAMEWORK', false );
        }

        // check if Revolution Slider plugin is loaded
        if ( class_exists('RevSliderFront') ) {
            define( 'VOLCANNO_REVSLIDER', true );
        } else {
            define( 'VOLCANNO_REVSLIDER', false );
        }

        // check if Master Slider plugin is loaded
        if ( function_exists( 'masterslider' ) ) {
            define( 'VOLCANNO_MASTER_SLIDER', true );
        } else {
            define( 'VOLCANNO_MASTER_SLIDER', false );
        }

        // check if Contact Form 7 plugin is loaded
        if ( class_exists('WPCF7') ) {
            define( 'VOLCANNO_CF7', true );
        } else {
            define( 'VOLCANNO_CF7', false );
        }

        // check if Mega Menu plugin is loaded
        if ( class_exists('Mega_Menu') ) {
            define( 'VOLCANNO_MEGA_MENU', true );
        } else {
            define( 'VOLCANNO_MEGA_MENU', false );
        }
        
        // check if Share Button plugin is loaded
        if ( defined('SSBA_ROOT') ) {
        	define( 'VOLCANNO_SHARE_BUTTON', true );
        } else {
        	define( 'VOLCANNO_SHARE_BUTTON', false );
        }

        // check if WooCommerce plugin is loaded
        if ( class_exists('WooCommerce') ) {
            define( 'VOLCANNO_WOOCOMMERCE', true );
        } else {
            define( 'VOLCANNO_WOOCOMMERCE', false );
        }

        // check if WPML Multilingual CMS is loaded
        if ( class_exists('SitePress') ) {
            define( 'VOLCANNO_WPML_SITE_PRESS', true );
        } else {
            define( 'VOLCANNO_WPML_SITE_PRESS', false );
        }

        // check if Polylang is loaded
        if ( class_exists('Polylang') ) {
            define( 'VOLCANNO_POLYLANG', true );
        } else {
            define( 'VOLCANNO_POLYLANG', false );
        }

        // check if Events Manager is loaded
        if ( class_exists('EM_Object') ) {
            define( 'VOLCANNO_EVENTS_MANAGER', true );
        } else {
            define( 'VOLCANNO_EVENTS_MANAGER', false );
        }

    }

}

Volcanno_Core_Plugins::plugins_loaded();

