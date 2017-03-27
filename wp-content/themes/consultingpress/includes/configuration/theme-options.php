<?php

/**
 * Copyright (c) 2016.
 * Theme Name: ArcShapeR
 * Author: Pixel Industry
 * Website: www.pixel-industry.com
 */
/*
 * ---------------------------------------------------------
 * Redux
 *
 * ReduxFramework Config File
 * ----------------------------------------------------------
 */
if ( !class_exists( 'Redux' ) ) {
    return;
}

// This is your option name where all the Redux data is stored.
$opt_name = "volcanno_options";

$theme = wp_get_theme( get_template() );

$args = array(
    // TYPICAL -> Change these values as you need/desire
    'opt_name' => 'volcanno_options',
    // This is where your data is stored in the database and also becomes your global variable name.
    'display_name' => $theme->get( 'Name' ),
    // Name that appears at the top of your panel
    'display_version' => $theme->get( 'Version' ),
    // Version that appears at the top of your panel
    'menu_type' => '',
    // Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
    'allow_sub_menu' => true,
    // Show the sections below the admin menu item or not
    'menu_title' => $theme->get( 'Name' ),
    'page_title' => $theme->get( 'Name' ),
    // You will need to generate a Google API key to use this feature.
    // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
    'google_api_key' => 'AIzaSyBsN1cG-NVXTUyefbmSlbv5NxMWyDzD8Nw',
    // Set it you want google fonts to update weekly. A google_api_key value is required.
    'google_update_weekly' => false,
    // Must be defined to add google fonts to the typography module
    'async_typography' => true,
    // Use a asynchronous font on the front end or font string
    // 'disable_google_fonts_link' => true, // Disable this in case you want to create your own google fonts loader
    'admin_bar' => true,
    // Show the panel pages on the admin bar
    'admin_bar_icon' => 'dashicons-portfolio',
    // Choose an icon for the admin bar menu
    'admin_bar_priority' => 50,
    // Choose an priority for the admin bar menu
    'global_variable' => '',
    // Set a different name for your global variable other than the opt_name
    'dev_mode' => false,
    // Show the time the page took to load, etc
    'update_notice' => true,
    // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
    'customizer' => true,
    // Enable basic customizer support
    // 'open_expanded' => true, // Allow you to start the panel in an expanded way initially.
    // 'disable_save_warn' => true, // Disable the save warning when a user changes a field
    // OPTIONAL -> Give you extra features
    'page_priority' => null,
    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
    'page_parent' => "volcanno_options",
    // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
    'page_permissions' => 'manage_options',
    // Permissions needed to access the options panel.
    'menu_icon' => '',
    // Specify a custom URL to an icon
    'last_tab' => '',
    // Force your panel to always open to a specific tab (by id)
    'page_icon' => 'icon-themes',
    // Icon displayed in the admin panel next to your menu_title
    'page_slug' => '_vlc_options',
    // Page slug used to denote the panel
    'save_defaults' => true,
    // On load save the defaults to DB before user clicks save or not
    'default_show' => false,
    // If true, shows the default value next to each field that is not the default value.
    'default_mark' => '',
    // What to print by the field's title if the value shown is default. Suggested: *
    'show_import_export' => true,
    // Shows the Import/Export panel when not used as a field.
    // CAREFUL -> These options are for advanced use only
    'transient_time' => 60 * MINUTE_IN_SECONDS,
    'output' => true,
    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
    'output_tag' => true,
    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
    // 'footer_credit' => '', // Disable the footer credit of Redux. Please leave if you can help it.
    // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
    'database' => '',
    // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
    'system_info' => false
);

// SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
$args ['share_icons'] [] = array(
    'url' => 'https://github.com/pixel-industry/',
    'title' => 'Visit us on GitHub',
    'icon' => 'el-icon-github'
);
// 'img' => '', // You can use icon OR img. IMG needs to be a full URL.

$args ['share_icons'] [] = array(
    'url' => 'https://www.facebook.com/pixel.industry.themes',
    'title' => 'Like us on Facebook',
    'icon' => 'el-icon-facebook'
);
$args ['share_icons'] [] = array(
    'url' => 'https://twitter.com/pixel_industry',
    'title' => 'Follow us on Twitter',
    'icon' => 'el-icon-twitter'
);
$args ['share_icons'] [] = array(
    'url' => 'http://www.linkedin.com/company/redux-framework',
    'title' => 'Find us on LinkedIn',
    'icon' => 'el-icon-linkedin'
);
$args ['share_icons'] [] = array(
    'url' => 'http://dribbble.com/pixel_industry',
    'title' => 'Our Work on Dribbble',
    'icon' => 'el-icon-dribbble'
);
$args ['share_icons'] [] = array(
    'url' => 'https://www.behance.net/pixel-industry',
    'title' => 'Our Portfolio on Behance',
    'icon' => 'el-icon-behance'
);

Redux::setArgs( $opt_name, $args );

/**
 * Consulting Press section
 */
Redux::setSection( $opt_name, array(
    'icon' => 'el-icon-home',
    'title' => esc_html__( 'ConsultingPress', 'consultingpress' ),
    'fields' => array(
        array(
            'id'        => 'support-menu-callback',
            'type'      => 'callback',
            'class'     => 'redux-support-menu',
            'callback' => 'Volcanno_Support::render_submenu_page'
        ),
    )
) );

/**
 * General Section
 */
Redux::setSection( $opt_name, array(
    'icon' => 'el-icon-wrench',
    'title' => esc_html__( 'General', 'consultingpress' ),
    'fields' => array(
        array(
            'id' => 'theme_loader_status',
            'type' => 'switch',
            'title' => esc_html__( 'Theme loader', 'consultingpress' ),
            'subtitle' => esc_html__( 'Enable or disable theme loading animation', 'consultingpress' ),
            'on' => esc_html__( 'Enable', 'consultingpress' ),
            'off' => esc_html__( 'Disable', 'consultingpress' ),
            'default' => true,
        ),
        array(
            'id' => 'scrool_to_top',
            'type' => 'switch',
            'title' => esc_html__( 'Scroll to top', 'consultingpress' ),
            'subtitle' => esc_html__( 'Enable or disable scrool to top.', 'consultingpress' ),
            'on' => esc_html__( 'Enable', 'consultingpress' ),
            'off' => esc_html__( 'Disable', 'consultingpress' ),
            'default' => true,
        ),
        array(
            'id' => 'smooth_scroll',
            'type' => 'switch',
            'title' => esc_html__( 'Smooth scroll', 'consultingpress' ),
            'subtitle' => esc_html__( 'Enable or disable smooth scroll.', 'consultingpress' ),
            'on' => esc_html__( 'Enable', 'consultingpress' ),
            'off' => esc_html__( 'Disable', 'consultingpress' ),
            'default' => false,
        ),
        array(
            'id' => 'color_style',
            'type' => 'select',
            'multi' => false,
            'title' => esc_html__( 'Theme color type', 'consultingpress' ),
            'subtitle' => esc_html__( 'Select predefined or custom color style type.', 'consultingpress' ),
            'options' => array(
                'predefined' => 'Predefined',
                'custom' => 'Custom' ),
            'default' => 'predefined',
        ),
        array(
            'id' => 'predefined_color_style',
            'type' => 'select',
            'multi' => false,
            'title' => esc_html__( 'Theme color style', 'consultingpress' ),
            'subtitle' => esc_html__( 'Select one of predefined theme color styles.', 'consultingpress' ),
            'options' => array(
                'color-default' => 'Default',
                'color-red' => 'Red',
                'color-blue' => 'Blue' ),
            'default' => 'color-default',
            'required' => array( 'color_style', '=', 'predefined' ),
        ),
        array(
            'id'       => 'custom_color_style',
            'type'     => 'color',
            'title'    => esc_html__('Main Color', 'consultingpress'), 
            'subtitle' => esc_html__('Pick a main color for the theme.', 'consultingpress'),
            'default'  => '#6ec25b',
            'validate' => 'color',
            'compiler' => 'true',
            'required' => array( 'color_style', '=', 'custom' ),
        ),
        array(
            'id'       => 'custom_color_style_2',
            'type'     => 'color',
            'title'    => esc_html__('Second hover Color', 'consultingpress'), 
            'subtitle' => esc_html__('Pick a second hover color for the theme.', 'consultingpress'),
            'default'  => '#59b744',
            'validate' => 'color',
            'compiler' => 'true',
            'required' => array( 'color_style', '=', 'custom' ),
        ),
    )
) );

/**
 * Header Section
 */
Redux::setSection( $opt_name, array(
    'id' => 'header-section',
    'icon' => 'el-icon-lines',
    'title' => esc_html__( 'Header', 'consultingpress' ),
    'fields' => array(
        array(
            'id' => 'header_type',
            'type' => 'select_image',
            'title' => esc_html__( 'Main Layout', 'consultingpress' ),
            'subtitle' => esc_html__( 'Select header type. ( Finance & Tourism, IT Security, Management )', 'consultingpress' ),
            'options' => array(
                'finance' => array(
                    'alt' => 'Finance',
                    'img' => VOLCANNO_TEMPLATEURL . '/includes/assets/redux/header-finance.jpg', 
                ),
                'tourism' => array(
                    'alt' => 'Tourism',
                    'img' => VOLCANNO_TEMPLATEURL . '/includes/assets/redux/header-tourism.jpg', 
                ),
                'it_security' => array(
                    'alt' => 'IT Security',
                    'img' => VOLCANNO_TEMPLATEURL . '/includes/assets/redux/header-it-security.jpg',
                ),
                'management' => array(
                    'alt' => 'Management',
                    'img' => VOLCANNO_TEMPLATEURL . '/includes/assets/redux/header-management.jpg',
                ),
            ),
            'default' => 'finance'
        ),
        array(
            'id' => 'logo',
            'type' => 'media',
            'title' => esc_html__( 'Logo', 'consultingpress' ),
            'compiler' => 'true',
            'subtitle' => esc_html__( 'Upload logo for your website.', 'consultingpress' ),
            'default' => array('url' => VOLCANNO_TEMPLATEURL . '/img/svg/consultingpress-logo-red.svg')
        ),
        // Header type: FINANCE, TOURISM
        array(
            'id' => 'quick_links',
            'type' => 'select_text',
            'title' => esc_html__( 'Contact info', 'consultingpress' ),
            'subtitle' => esc_html__( 'Contact info', 'consultingpress' ),
            'icons' => Volcanno_Icon_Fonts::lynny_classes(),
            'key_prefix' => '',
            'required' => array(
                array( 'header_type', '!=', 'it_security' ),
                array( 'header_type', '!=', 'management' )
            ),
            'default' => array(
                'icon' => array( 'lynny-phone-1', 'lynny-mail-duplicate' ),
                'text' => array( '+00 41 258 9854', '<a href="' . esc_url( home_url('/') ) . '">Contact us</a>' )
            )
        ),
        // Header type: IT SECURITY, MANAGEMENT
        array(
            'id' => 'info_widgets',
            'type' => 'select_text',
            'title' => esc_html__( 'Contact info', 'consultingpress' ),
            'subtitle' => esc_html__( 'Please insert text in format: title|text|url (url is optional).', 'consultingpress' ),
            'icons' => Volcanno_Icon_Fonts::lynny_classes(),
            'key_prefix' => '',
            'required' => array(
                array( 'header_type', '!=', 'finance' ),
                array( 'header_type', '!=', 'tourism' )
            ),
            'default' => array(
                'icon' => array( 'lynny-phone-1', 'lynny-mail-duplicate', 'globe-2_1' ),
                'text' => array( 
                    esc_html__('Call us|+00 41 258 9854', 'consultingpress'), 
                    esc_html__('Get in touch|info@consultingpress.com|mailto:info@consultingpress.com', 'consultingpress'),
                    esc_html__( 'Our locations|Global coverage|http://www.consultingpress.com/contact/', 'consultingpress' )
                ),
            )
        ),
        array(
            'id' => 'social_links',
            'type' => 'select_text',
            'title' => esc_html__( 'Social icons', 'consultingpress' ),
            'subtitle' => esc_html__( 'Here you can add your social networks.', 'consultingpress' ),
            'icons' => Volcanno_Icon_Fonts::font_awesome_classes(),
            'key_prefix' => 'fa ',
            'default' => array(
                'icon' => array( 'fa fa-linkedin', 'fa fa-facebook', 'fa fa-twitter' ),
                'text' => array( 'http://linkedin.com', 'http://facebook.com', 'http://twitter.com' )
            )
        ),
        array(
            'id' => 'display_language_switcher',
            'type' => 'switch',
            'title' => esc_html__( 'Languages switcher', 'consultingpress' ),
            'subtitle' => esc_html__( 'Show or hide language switcher. ( Available only when WPML or Polylang plugins are active ).', 'consultingpress' ),
            'on' => esc_html__( 'Show', 'consultingpress' ),
            'off' => esc_html__( 'Hide', 'consultingpress' ),
            'default' => true,
        ),
        array(
            'id' => 'search_placeholder',
            'type' => 'text',
            'title' => esc_html__( 'Search input placeholder text', 'consultingpress' ),
            'subtitle' => esc_html__( 'Text that will apear as a placeholder in search field.', 'consultingpress' ),
            'default' => esc_html__('Type and hit enter...', 'consultingpress'),
        ),
    )
) );

/**
 * Typography
 */
Redux::setSection( $opt_name, array(
    'icon' => 'el-icon-font',
    'title' => esc_html__( 'Typography', 'consultingpress' ),
    'fields' => array(
        array(
            'id' => 'body_font2',
            'type' => 'typography',
            'title' => esc_html__( 'Body Font', 'consultingpress' ),
            'subtitle' => esc_html__( 'Specify the body font properties.', 'consultingpress' ),
            'google' => true,
            'default' => array(
                'color' => '#748182',
                'font-size' => '15px',
                'font-family' => 'Poppins',
                'font-weight' => '400',
                'line-height' => '25px'
            ),
            'output' => array(
                'body'
            )
        ),
        array(
            'id' => 'paragraphs',
            'type' => 'typography',
            'title' => esc_html__( 'Paragraph Font', 'consultingpress' ),
            'subtitle' => esc_html__( 'Specify the paragraph font properties.', 'consultingpress' ),
            'google' => true,
            'default' => array(
                'color' => '#748182',
                'font-size' => '15px',
                'font-family' => 'Poppins',
                'font-weight' => '400',
                'line-height' => '25px'
            ),
            'output' => array(
                'p'
            )
        ),
        array(
            'id' => 'heading_h1',
            'type' => 'typography',
            'title' => esc_html__( 'H1 Heading', 'consultingpress' ),
            'subtitle' => esc_html__( 'Specify the body font properties.', 'consultingpress' ),
            'google' => true,
            'output' => array(
                'h1'
            ),
            'default' => array(
                'color' => '#071740',
                'font-size' => '36px',
                'font-family' => 'Poppins',
                'line-height' => '54px',
                'font-weight' => '600'
            )
        ),
        array(
            'id' => 'heading_h2',
            'type' => 'typography',
            'title' => esc_html__( 'H2 Heading', 'consultingpress' ),
            'subtitle' => esc_html__( 'Specify the properties for H2.', 'consultingpress' ),
            'google' => true,
            'output' => array(
                'h2'
            ),
            'default' => array(
                'color' => '#071740',
                'font-size' => '30px',
                'font-family' => 'Poppins',
                'line-height' => '40px',
                'font-weight' => '600'
            )
        ),
        array(
            'id' => 'heading_h3',
            'type' => 'typography',
            'title' => esc_html__( 'H3 Heading', 'consultingpress' ),
            'subtitle' => esc_html__( 'Specify the properties for H3.', 'consultingpress' ),
            'google' => true,
            'output' => array(
                'h3'
            ),
            'default' => array(
                'color' => '#071740',
                'font-size' => '24px',
                'font-family' => 'Poppins',
                'line-height' => '32px',
                'font-weight' => '600'
            )
        ),
        array(
            'id' => 'heading_h4',
            'type' => 'typography',
            'title' => esc_html__( 'H4 Heading', 'consultingpress' ),
            'subtitle' => esc_html__( 'Specify the properties for H4.', 'consultingpress' ),
            'google' => true,
            'output' => array(
                'h4'
            ),
            'default' => array(
                'color' => '#071740',
                'font-size' => '21px',
                'font-family' => 'Poppins',
                'line-height' => '30px',
                'font-weight' => '600'
            )
        ),
        array(
            'id' => 'heading_h5',
            'type' => 'typography',
            'title' => esc_html__( 'H5 Heading', 'consultingpress' ),
            'subtitle' => esc_html__( 'Specify the properties for H5.', 'consultingpress' ),
            'google' => true,
            'output' => array(
                'h5'
            ),
            'default' => array(
                'color' => '#071740',
                'font-size' => '18px',
                'font-family' => 'Poppins',
                'line-height' => '28px',
                'font-weight' => '600'
            )
        ),
        array(
            'id' => 'heading_h6',
            'type' => 'typography',
            'title' => esc_html__( 'H6 Heading', 'consultingpress' ),
            'subtitle' => esc_html__( 'Specify the properties for H6.', 'consultingpress' ),
            'google' => true,
            'output' => array(
                'h6'
            ),
            'default' => array(
                'color' => '#071740',
                'font-size' => '15px',
                'font-family' => 'Poppins',
                'line-height' => '23px',
                'font-weight' => '600'
            )
        )
    )
) );

/**
 * Page
 */
Redux::setSection( $opt_name, array(
    'icon' => 'el-icon-website',
    'title' => esc_html__( 'Page', 'consultingpress' ),
    'fields' => array(
        array(
            'id' => 'section-page_title',
            'type' => 'section',
            'title' => esc_html__( 'Page title', 'consultingpress' ),
            'indent' => false
        ),
        array(
            'id' => 'breadcrumbs',
            'type' => 'switch',
            'title' => esc_html__( 'Breadcrumbs', 'consultingpress' ),
            'subtitle' => esc_html__( 'Enable or disable page title breadcrumbs.', 'consultingpress' ),
            'on' => esc_html__( 'Enable', 'consultingpress' ),
            'off' => esc_html__( 'Disable', 'consultingpress' ),
            'default' => true,
        ),
        array(
            'id' => 'breadcrumbs_prefix',
            'type' => 'text',
            'title' => esc_html__( 'Breadcrumbs prefix text', 'consultingpress' ),
            'subtitle' => esc_html__( 'Text that will appear before breadcrumbs.', 'consultingpress' ),
            'default' => 'You are here:',
            'required' => array( 'breadcrumbs', '=', 1 ),
        ),
        array(
            'id' => 'breadcrumbs_navigation_label',
            'type' => 'checkbox',
            'title' => esc_html__( 'Breadcrumbs Text', 'consultingpress' ),
            'subtitle' => esc_html__( 'Show navigation label instead page/post title.', 'consultingpress' ),
            'default' => '0',
            'required' => array( 'breadcrumbs', '=', 1 )
        ),
        array(
            'id' => 'breadcrumbs_length',
            'type' => 'slider',
            'title' => esc_html__( 'Breadcrumbs Length', 'consultingpress' ),
            'subtitle' => esc_html__( 'Breadcrumbs will be cut to number of characters entered here.', 'consultingpress' ),
            "default" => 25,
            "min" => 0,
            "step" => 1,
            "max" => 100,
            'display_value' => 'text',
            'required' => array( 'breadcrumbs', '=', 1 )
        )
    )
) );

/**
 * Blog
 */
Redux::setSection( $opt_name, array(
    'icon' => 'el-icon-pencil',
    'title' => esc_html__( 'Blog', 'consultingpress' ),
    'fields' => array(
        array(
            'id' => 'default_blog_style',
            'type' => 'select',
            'multi' => false,
            'title' => esc_html__( 'Default Blog style', 'consultingpress' ),
            'subtitle' => esc_html__( 'Here you can choose default blog style.', 'consultingpress' ),
            'options' => array(
                'blog-list' => 'List',
                'blog-grid' => 'Grid',
            ),
            'default' => 'blog-list',
        ),
        array(
            'id' => 'default_sidebar_position',
            'type' => 'select',
            'multi' => false,
            'title' => esc_html__( 'Default sidebar position', 'consultingpress' ),
            'subtitle' => esc_html__( 'Here you can select default sidebar position.', 'consultingpress' ),
            'options' => array(
                'right' => 'Right',
                'left' => 'Left',
                'fullwidth' => 'No sidebar' ),
            'default' => 'right',
        ),
        array(
            'id' => 'default_blog_pagination',
            'type' => 'slider',
            'title' => esc_html__( 'How many posts to display by default', 'consultingpress' ),
            'subtitle' => esc_html__( 'You can overide this for every blog template.', 'consultingpress' ),
            "default" => 10,
            "min" => 0,
            "step" => 1,
            "max" => 50,
            'display_value' => 'text'
        ),
        array(
            'id' => 'section-page_title',
            'type' => 'section',
            'title' => esc_html__( 'Single page', 'consultingpress' ),
            'indent' => false
        ),
        array(
            'id' => 'blog_single_author_description',
            'type' => 'switch',
            'title' => esc_html__( 'Author description', 'consultingpress' ),
            'subtitle' => esc_html__( 'Show or hide author description at the end of the post.', 'consultingpress' ) . '<br>' . esc_html__( 'Visible only if user have biographical info in user profile page.', 'consultingpress' ),
            'on' => esc_html__( 'Show', 'consultingpress' ),
            'off' => esc_html__( 'Hide', 'consultingpress' ),
            'default' => true,
        ),
        array(
            'id' => 'blog_single_pagination',
            'type' => 'switch',
            'title' => esc_html__( 'Pagination', 'consultingpress' ),
            'subtitle' => esc_html__( 'Show or hide pagination at the end of the post.', 'consultingpress' ),
            'on' => esc_html__( 'Show', 'consultingpress' ),
            'off' => esc_html__( 'Hide', 'consultingpress' ),
            'default' => true,
        ),
    )
) );

/**
 * Case studies
 */
Redux::setSection( $opt_name, array(
    'icon' => 'el el-graph',
    'title' => esc_html__( 'Case Studies', 'consultingpress' ),
    'fields' => array(
        array(
            'id' => 'section-case-studies-category',
            'type' => 'section',
            'title' => esc_html__( 'Categories', 'consultingpress' ),
            'indent' => false
        ),
        array(
            'id' => 'default_case_studies_style',
            'type' => 'select',
            'multi' => false,
            'title' => esc_html__( 'Default Case studies style', 'consultingpress' ),
            'subtitle' => esc_html__( 'Here you can choose default Case studies style.', 'consultingpress' ),
            'options' => array(
                'cases-list' => 'List',
                'cases-grid' => 'Grid',
            ),
            'default' => 'cases-list',
        ),
        array(
            'id' => 'default_case_studies_sidebar_position',
            'type' => 'select',
            'multi' => false,
            'title' => esc_html__( 'Default sidebar position', 'consultingpress' ),
            'subtitle' => esc_html__( 'Here you can select default sidebar position for case studies.', 'consultingpress' ),
            'options' => array(
                'right' => 'Right',
                'left' => 'Left',
                'fullwidth' => 'No sidebar' ),
            'default' => 'right',
        ),
        array(
            'id' => 'default_case_studies_pagination',
            'type' => 'slider',
            'title' => esc_html__( 'How many case studies to display by default', 'consultingpress' ),
            'subtitle' => esc_html__( 'You can overide this for every Case studies template.', 'consultingpress' ),
            "default" => 10,
            "min" => 0,
            "step" => 1,
            "max" => 50,
            'display_value' => 'text'
        ),
    )
) );

if ( VOLCANNO_WOOCOMMERCE ) {
    Redux::setSection( $opt_name, array(
        'icon' => 'el el-shopping-cart',
        'title' => esc_html__( 'WooCommerce', 'consultingpress' ),
        'fields' => array(
            array(
                'id' => 'shop_sidebar_position',
                'type' => 'select',
                'multi' => false,
                'title' => esc_html__( 'Sidebar position', 'consultingpress' ),
                'subtitle' => esc_html__( 'Here you can select shop sidebar position.', 'consultingpress' ),
                'options' => array(
                    'right' => 'Right',
                    'left' => 'Left',
                    'fullwidth' => 'No sidebar' ),
                'default' => 'right',
            ),
            array(
                'id' => 'display_cart',
                'type' => 'switch',
                'title' => esc_html__( 'Shopping Cart', 'consultingpress' ),
                'subtitle' => esc_html__( 'Show or hide shopping cart.', 'consultingpress' ),
                'on' => esc_html__( 'Show', 'consultingpress' ),
                'off' => esc_html__( 'Hide', 'consultingpress' ),
                'default' => true,
            ),
            array(
                'id' => 'display_cart_amount',
                'type' => 'switch',
                'title' => esc_html__( 'Shopping Cart Amount', 'consultingpress' ),
                'subtitle' => esc_html__( 'Show or hide shopping cart amount.', 'consultingpress' ),
                'on' => esc_html__( 'Show', 'consultingpress' ),
                'off' => esc_html__( 'Hide', 'consultingpress' ),
                'default' => false,
            ),
        )
    ) );
}

/**
 * Footer
 */
Redux::setSection( $opt_name, array(
    'icon' => 'el el-icon-photo',
    'title' => esc_html__( 'Footer', 'consultingpress' ),
    'fields' => array(
        array(
            'id' => 'footer_top_widget',
            'type' => 'switch',
            'title' => esc_html__( 'Top widget section', 'consultingpress' ),
            'subtitle' => esc_html__( 'Enable or disable footer top widget section', 'consultingpress' ),
            'on' => esc_html__( 'Enable', 'consultingpress' ),
            'off' => esc_html__( 'Disable', 'consultingpress' ),
            'default' => false,
        ),
        array(
            'id' => 'footer_main_widget',
            'type' => 'switch',
            'title' => esc_html__( 'Main widget sections', 'consultingpress' ),
            'subtitle' => esc_html__( 'Enable or disable footer main widget section', 'consultingpress' ),
            'on' => esc_html__( 'Enable', 'consultingpress' ),
            'off' => esc_html__( 'Disable', 'consultingpress' ),
            'default' => true,
        ),
        array(
            'id' => 'footer_widget_areas',
            'type' => 'slider',
            'title' => esc_html__( 'Number of main footer sections', 'consultingpress' ),
            'subtitle' => esc_html__( 'For each section is created new widget sidebar.', 'consultingpress' ),
            "default" => 4,
            "min" => 1,
            "step" => 1,
            "max" => 4,
            'display_value' => 'text',
            'required' => array( 'footer_main_widget', '=', 1 ),
        ),
        array(
            'id' => 'footer_copyright',
            'type' => 'switch',
            'title' => esc_html__( 'Copyright section', 'consultingpress' ),
            'subtitle' => esc_html__( 'Enable or disable footer copyright section.', 'consultingpress' ),
            'on' => esc_html__( 'Enable', 'consultingpress' ),
            'off' => esc_html__( 'Disable', 'consultingpress' ),
            'default' => true,
        ),
        array(
            'id' => 'footer_copyright_left',
            'type' => 'text',
            'title' => esc_html__( 'Left Copyright text', 'consultingpress' ),
            'subtitle' => esc_html__( 'Text that will apear in copyright on left side.', 'consultingpress' ),
            'default' => esc_html__( 'Copyright © 2017 Consulting Press', 'consultingpress' ),
            'required' => array( 'footer_copyright', '=', 1 ),
        ),
        array(
            'id' => 'footer_copyright_right',
            'type' => 'text',
            'title' => esc_html__( 'Right Copyright text', 'consultingpress' ),
            'subtitle' => esc_html__( 'Text that will apear in copyright on right side.', 'consultingpress' ),
            'default' => 'Design and development by <a href="http://www.pixel-industry.com">Pixel Industry</a>',
            'required' => array( 'footer_copyright', '=', 1 ),
        ),
    )
) );

/**
 * 404 Page
 */
Redux::setSection( $opt_name, array(
    'icon' => 'el el-warning-sign',
    'title' => esc_html__( '404 Page', 'consultingpress' ),
    'fields' => array(
        array(
            'id' => '404_heading_image',
            'type' => 'media',
            'title' => esc_html__( 'Page title image', 'consultingpress' ),
            'subtitle' => esc_html__( 'Here you can upload page title background image.', 'consultingpress' )
        ),
        array(
            'id' => '404_top_heading',
            'type' => 'text',
            'title' => esc_html__( 'Top Heading', 'consultingpress' ),
            'subtitle' => esc_html__( 'Top Heading for 404 page.', 'consultingpress' ),
            'default' => esc_html__( '404 - Page not found', 'consultingpress' ),
        ),
        array(
            'id' => '404_message',
            'type' => 'textarea',
            'title' => esc_html__( 'Message', 'consultingpress' ),
            'subtitle' => esc_html__( 'Message for 404 page.', 'consultingpress' ),
            'default' => esc_html__( "We're sorry, we couldn't find the page you're looking for. The reason might be that it doesn't exist anymore, it has moved elsewhere or the address is not correct.", 'consultingpress' ),
        ),
    )
) );



