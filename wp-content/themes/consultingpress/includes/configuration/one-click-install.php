<?php

/* ---------------------------------------------------------
 * One click installer
 *
 * Function for registering one click installer files
  ---------------------------------------------------------- */

function volcanno_setup_one_click_installer( $options ) {
    global $volcanno_theme_config;

    /**
     * Demo files path in theme
     */
    $options['demo_files_path'] = VOLCANNO_THEME_DIR . 'includes/demo-files/';

    /**
     * Holds demo content file names for each content type
     * 
     * CUSTOM POST TYPES
     * Key 'cpts' holds all custom post types registered in theme
     * 
     * SLIDERS
     * Revolution slider key: slider
     * Master slider key: master_slider
     * 
     * LAYOUTS
     * Key => Value (array)
     * Key - layout ID
     * Value - array with layout name and screenshot
     * 
     * @var type array
     */
    $options['demo_files'] = array(
        'layouts' => array(
            'finance' => array(
                'name' => 'Finance',
                'image' => 'IMAGE_URL'
            ),
            'it_security' => array(
                'name' => 'IT Security',
                'image' => 'IMAGE_URL'
            ),
            'management' => array(
                'name' => 'Management',
                'image' => 'IMAGE_URL'
            ),
            'tourism' => array(
                'name' => 'Tourism',
                'image' => 'IMAGE_URL'
            ),
        ),
        'pages_menus' => array(            
            'layouts' => array(
                'finance' => '/finance/pages_menus.xml', 
                'it_security' => '/it-security/pages_menus.xml', 
                'management' => '/management/pages_menus.xml', 
                'tourism' => '/tourism/pages_menus.xml', 
            )
        ),
        'posts' => array(            
            'layouts' => array(
                'finance' => '/finance/posts.xml', 
                'it_security' => '/it-security/posts.xml', 
                'management' => '/management/posts.xml', 
                'tourism' => '/tourism/posts.xml', 
            )
        ),
        'contact' => 'contact.xml',
        'master_slider' => 'masterslider.json',
        'attachments' => array(            
            'layouts' => array(
                'finance' => '/finance/attachments.xml', 
                'it_security' => '/it-security/attachments.xml', 
                'management' => '/management/attachments.xml', 
                'tourism' => '/tourism/attachments.xml', 
            )
        ),
        'cpts' => array(
            'case_studies' => array(            
                'layouts' => array(
                    'finance' => '/finance/case_studies.xml', 
                    'it_security' => '/it-security/case_studies.xml', 
                    'management' => '/management/case_studies.xml', 
                    'tourism' => '/tourism/case_studies.xml', 
                )
            ),
            'events' => 'events.xml',
        ),
        'newsletter_forms' => '1',        
    );

    /**
     * Name of the Redux options key
     */
    $options['theme_options_name'] = 'volcanno_options';

    /**
     * Importer settings
     * 
     * Usage:
     * key (must not be changed) => value (name of the page to assign)
     * 
     * @since 0.0.3
     * 
     * @var type array
     */
    $options['settings'] = array(
        'home' => array(
            'finance' => 452,
            'management' => 2430,
            'tourism' => 1946,
            'it_security' => 2267,
        ),
        'blog' => array(
            'finance' => 718,
            'management' => 2939,
            'tourism' => 2254,
            'it_security' => 2737,
        ),
    );

    /**
     * Menu locations
     * location => Menu name
     * 
     * @var type array
     */
    $options['menu_locations'] = array(
        'primary' => array( 
            'finance' => 'Main Menu - Finance',
            'management' => 'Main Menu - Management',
            'tourism' => 'Main Menu - Tourism',
            'it_security' => 'Main Menu - IT Security',
        ),
    );

    return $options;
}

add_filter( 'voci_one_click_installer_options', 'volcanno_setup_one_click_installer' );
