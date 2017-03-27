<?php

/**
 *
 * @package	   TGM-Plugin-Activation
 * @subpackage Example
 * @version	   2.3.6
 * @author	   Thomas Griffin <thomas@thomasgriffinmedia.com>
 * @author	   Gary Jones <gamajo@gamajo.com>
 * @copyright  Copyright (c) 2012, Thomas Griffin
 * @license	   http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/thomasgriffin/TGM-Plugin-Activation
 */
/**
 * Include the TGM_Plugin_Activation class.
 */

require_once get_template_directory() . '/core/libs/external/class-tgm-plugin-activation.php';

add_action('tgmpa_register', 'volcanno_register_required_plugins');

/**
 * Register the required plugins for this theme.
 *
 * In this example, we register two plugins - one included with the TGMPA library
 * and one from the .org repo.
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function volcanno_register_required_plugins() {

    /**
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(
        // Required and optional plugins
        array(
            'name' => esc_html__('WordPress Importer', 'consultingpress'),
            'slug' => 'wordpress-importer',
            'required' => false,
            'version' => ''
        ),
        array(
            'name' => esc_html__('Redux Framework', 'consultingpress'),
            'slug' => 'redux-framework',
            'required' => true,
            'force_activation' => true,
        ),
        array(
            'name' => esc_html__('Volcanno One Click Installer', 'consultingpress'),
            'slug' => 'volcanno-one-click-installer',
            'source' => 'volcanno-one-click-installer.zip',
            'required' => false,
            'force_activation' => false,
            'version' => '1.1'
        ),
        array(
            'name' => esc_html__('Volcanno Icon Font Parser (Optional)', 'consultingpress'),
            'slug' => 'volcanno-icon-font-parser',
            'source' => 'volcanno-icon-font-parser.zip',
            'required' => false,
            'force_activation' => false,
            'version' => '1.0'
        ),
        array(
            'name' => esc_html__('Custom Post Types', 'consultingpress'),
            'slug' => 'volcanno-custom-post-types',
            'required' => true,
            'version' => '1.1.5',
            'source' => 'volcanno-custom-post-types.zip'
        ),
        array(
            'name' => esc_html__('Meta Box', 'consultingpress'),
            'slug' => 'meta-box',
            'required' => true,
            'version' => ''
        ),
        array(
            'name' => esc_html__('Meta Box Tabs', 'consultingpress'),
            'slug' => 'meta-box-tabs',
            'required' => true,
            'version' => '0.1.5',
            'source' => 'meta-box-tabs.zip'
        ),
        array(
            'name' => esc_html__('Meta Box Conditional Logic', 'consultingpress'),
            'slug' => 'meta-box-conditional-logic',
            'required' => true,
            'version' => '1.1',
            'source' => 'meta-box-conditional-logic.zip'
        ),
        array(
            'name' => esc_html__('Visual Composer', 'consultingpress'),
            'slug' => 'js_composer',
            'required' => true,
            'version' => '5.0.1',
            'source' => 'js_composer.zip'
        ),
        array(
            'name' => esc_html__('Volcanno Shortcodes', 'consultingpress'),
            'slug' => 'volcanno-shortcodes',
            'required' => true,
            'version' => '1.2',
            'source' => 'volcanno-shortcodes.zip'
        ),
        array(
            'name' => esc_html__('TinyMCE Advanced', 'consultingpress'),
            'slug' => 'tinymce-advanced',
            'required' => false,
            'version' => ''
        ),
        array(
            'name' => esc_html__('Volcanno Breadcrumbs (Optional)', 'consultingpress'),
            'slug' => 'volcanno-breadcrumbs',
            'source' => 'volcanno-breadcrumbs.zip',
            'required' => false,
            'force_activation' => false,
            'version' => '1.0',
        ),
        array(
            'name' => esc_html__('Contact Form 7 (Optional)', 'consultingpress'),
            'slug' => 'contact-form-7',
            'required' => false,
            'version' => ''
        ),
        array(
            'name' => esc_html__('Max Mega Menu (Optional)', 'consultingpress'),
            'slug' => 'megamenu',
            'required' => false,
            'version' => ''
        ),
        array(
            'name' => esc_html__('Events Manager (Optional)', 'consultingpress'),
            'slug' => 'events-manager',
            'required' => false,
            'version' => '',
        ),
        array(
            'name' => esc_html__('Newsletter (Optional)', 'consultingpress'),
            'slug' => 'newsletter',
            'required' => false,
            'version' => ''
        ),
        array(
            'name' => esc_html__('Master Slider (Optional)', 'consultingpress'), // The plugin name
            'slug' => 'masterslider', // The plugin slug (typically the folder name)
            'source' => 'masterslider.zip', // The plugin source
            'required' => false, // If false, the plugin is only 'recommended' instead of required
            'force_activation' => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url' => '', // If set, overrides default API URL and points to an external URL
            'version' => '3.1.2'
        ),
        array(
            'name' => esc_html__('SVG Support (Optional)', 'consultingpress'), // The plugin name
            'slug' => 'svg-support', // The plugin slug (typically the folder name)            
            'required' => false, // If false, the plugin is only 'recommended' instead of required
            'version' => '' // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented            
        ),
        array(
            'name' => esc_html__('Recent Posts Widget Extended', 'consultingpress'), // The plugin name
            'slug' => 'recent-posts-widget-extended', // The plugin slug (typically the folder name)            
            'required' => false, // If false, the plugin is only 'recommended' instead of required
            'version' => '' // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented            
        ),
        array(
            'name' => esc_html__('Envato Market (Optional)', 'consultingpress'),
            'slug' => 'envato-market',
            'required' => false,
            'source' => 'envato-market.zip',
            'version' => '1.0.0-RC2'
        ),
    );
    
    // Change this to your theme text domain, used for internationalising strings
    $theme_text_domain = 'consultingpress';

    /**
     * Array of configuration settings. Amend each line as needed.
     * If you want the default strings to be available under your own theme domain,
     * leave the strings uncommented.
     * Some of the strings are added into a sprintf, so see the comments at the
     * end of each line for what each argument will be.
     */
    
    $config = array(
        'domain' => $theme_text_domain, // Text domain - likely want to be the same as your theme.
        'default_path' => get_template_directory() . '/includes/plugins/', // Default absolute path to pre-packaged plugins
        'parent_slug' => 'themes.php', // Default parent menu slug
        'menu' => 'install-required-plugins', // Menu slug
        'has_notices' => true, // Show admin notices or not
        'is_automatic' => true, // Automatically activate plugins after installation or not
        'message' => '', // Message to output right before the plugins table
        'strings' => array(
            'page_title' => esc_html__('Install Required Plugins', 'consultingpress'),
            'menu_title' => esc_html__('Install Plugins', 'consultingpress'),
            'installing' => esc_html__('Installing Plugin: %s', 'consultingpress'), // %1$s = plugin name
            'oops' => esc_html__('Something went wrong with the plugin API.', 'consultingpress'),
            'notice_can_install_required' => _n_noop('This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'consultingpress'), // %1$s = plugin name(s)
            'notice_can_install_recommended' => _n_noop('This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'consultingpress'), // %1$s = plugin name(s)
            'notice_cannot_install' => _n_noop('Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'consultingpress'), // %1$s = plugin name(s)
            'notice_can_activate_required' => _n_noop('The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'consultingpress'), // %1$s = plugin name(s)
            'notice_can_activate_recommended' => _n_noop('The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'consultingpress'), // %1$s = plugin name(s)
            'notice_cannot_activate' => _n_noop('Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'consultingpress'), // %1$s = plugin name(s)
            'notice_ask_to_update' => _n_noop('The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'consultingpress'), // %1$s = plugin name(s)
            'notice_cannot_update' => _n_noop('Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'consultingpress'), // %1$s = plugin name(s)
            'install_link' => _n_noop('Begin installing plugin', 'Begin installing plugins', 'consultingpress'),
            'activate_link' => _n_noop('Activate installed plugin', 'Activate installed plugins', 'consultingpress'),
            'return' => esc_html__('Return to Required Plugins Installer', 'consultingpress'),
            'plugin_activated' => esc_html__('Plugin activated successfully.', 'consultingpress'),
            'complete' => esc_html__('All plugins installed and activated successfully. %s', 'consultingpress'), // %1$s = dashboard link
            'nag_type' => 'updated' // Determines admin notice type - can only be 'updated' or 'error'
        )
    );

    tgmpa($plugins, $config);
}
