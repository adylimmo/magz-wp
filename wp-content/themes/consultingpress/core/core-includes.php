<?php

/* ---------------------------------------------------------
 * VOLCANNO FRAMEWORK
 * WordPress framework for creating themes.
 * 
 * Author: Pixel Industry
 * Website: www.pixel-industry.com
 * 
 * MAIN FILE FOR INCLUDING CORE FEATURES
 * 
 * Version: 3.0
 * ---------------------------------------------------------- */


global $volcanno_theme_config;

/* -------------------------------------------------------------------- 
 * Include script for Plugins management
 * -------------------------------------------------------------------- */

// Load from Child theme is file is available
if ( file_exists( VOLCANNO_STYLESHEET_DIR . '/core/libs/plugins.php' ) ) {

    require_once VOLCANNO_STYLESHEET_DIR . '/core/libs/plugins.php';
}
// Load from Parent theme is file is available
else if ( file_exists( VOLCANNO_THEME_DIR . '/core/libs/plugins.php' ) ) {

    require_once VOLCANNO_THEME_DIR . '/core/libs/plugins.php';
}

/* -------------------------------------------------------------------- 
 * Include Helper functions
 * -------------------------------------------------------------------- */

// Load from Child theme is file is available
if ( file_exists( VOLCANNO_STYLESHEET_DIR . '/core/libs/helper.php' ) ) {

    require_once VOLCANNO_STYLESHEET_DIR . '/core/libs/helper.php';
}
// Load from Parent theme is file is available
else if ( file_exists( VOLCANNO_THEME_DIR . '/core/libs/helper.php' ) ) {

    require_once VOLCANNO_THEME_DIR . '/core/libs/helper.php';
}

/* -------------------------------------------------------------------- 
 * Include Custom Styles class
 * -------------------------------------------------------------------- */

// Load from Child theme is file is available
if ( file_exists( VOLCANNO_STYLESHEET_DIR . '/core/libs/custom-styles.php' ) ) {

    require_once VOLCANNO_STYLESHEET_DIR . '/core/libs/custom-styles.php';
}
// Load from Parent theme is file is available
else if ( file_exists( VOLCANNO_THEME_DIR . '/core/libs/custom-styles.php' ) ) {

    require_once VOLCANNO_THEME_DIR . '/core/libs/custom-styles.php';
}

/* -------------------------------------------------------------------- 
 * Include Core functions
 * -------------------------------------------------------------------- */
// Load from Child theme is file is available
if ( file_exists( VOLCANNO_STYLESHEET_DIR . '/core/libs/core.php' ) ) {

    require_once VOLCANNO_STYLESHEET_DIR . '/core/libs/core.php';
}
// Load from Parent theme is file is available
else if ( file_exists( VOLCANNO_THEME_DIR . '/core/libs/core.php' ) ) {

    require_once VOLCANNO_THEME_DIR . '/core/libs/core.php';
}

/* -------------------------------------------------------------------- 
 * Load file with Icon Font functions
 * -------------------------------------------------------------------- */
/*
// Load from Child theme is file is available
if ( file_exists( VOLCANNO_STYLESHEET_DIR . '/core/libs/icon-fonts.php' ) ) {

    require_once VOLCANNO_STYLESHEET_DIR . '/core/libs/icon-fonts.php';
}
// Load from Parent theme is file is available
else if ( file_exists( VOLCANNO_THEME_DIR . '/core/libs/icon-fonts.php' ) ) {

    require_once VOLCANNO_THEME_DIR . '/core/libs/icon-fonts.php';
}*/


/* -------------------------------------------------------------------- 
 * Load file with Icon Font functions
 * -------------------------------------------------------------------- */

// Load from Child theme is file is available
if ( file_exists( VOLCANNO_STYLESHEET_DIR . '/includes/configuration/icon-fonts.php' ) ) {

    require_once VOLCANNO_STYLESHEET_DIR . '/includes/configuration/icon-fonts.php';
}
// Load from Parent theme is file is available
else if ( file_exists( VOLCANNO_THEME_DIR . '/includes/configuration/icon-fonts.php' ) ) {

    require_once VOLCANNO_THEME_DIR . '/includes/configuration/icon-fonts.php';
}

if ( is_admin() ) {

    /* -------------------------------------------------------------------- 
     * Create Support menu
     * -------------------------------------------------------------------- */

    // Load One click installer from Child theme is file is available
    if ( file_exists( VOLCANNO_STYLESHEET_DIR . '/core/libs/support-menu.php' ) ) {

        require_once VOLCANNO_STYLESHEET_DIR . '/core/libs/support-menu.php';
    }
    // Load One click installer from Parent theme is file is available
    else if ( file_exists( VOLCANNO_THEME_DIR . '/core/libs/support-menu.php' ) ) {

        require VOLCANNO_THEME_DIR . '/core/libs/support-menu.php';
    }

    /* -------------------------------------------------------------------- 
     * One click installer
     * -------------------------------------------------------------------- */

    if ( !isset( $volcanno_theme_config['include']['one_click_installer'] ) || $volcanno_theme_config['include']['one_click_installer'] == '1' ) {

        // filter config files directory
        $volcanno_config_files_dir = apply_filters( 'volcanno_config_files_dir', '' );

        // check if value is filtered
        if ( !empty( $volcanno_config_files_dir ) ) {
            $file_url = trailingslashit( $volcanno_config_files_dir ) . 'one-click-install.php';

            if ( file_exists( $file_url ) ) {
                require_once $file_url;
            }
        }
        // Load One click installer from Child theme is file is available
        else if ( file_exists( VOLCANNO_STYLESHEET_DIR . '/includes/configuration/one-click-install.php' ) ) {

            require_once VOLCANNO_STYLESHEET_DIR . '/includes/configuration/one-click-install.php';
        }
        // Load One click installer from Parent theme is file is available
        else if ( file_exists( VOLCANNO_THEME_DIR . '/includes/configuration/one-click-install.php' ) ) {

            require_once VOLCANNO_THEME_DIR . '/includes/configuration/one-click-install.php';
        }
    }
}

/* -------------------------------------------------------------------- 
 * Load Redux loader file
 * -------------------------------------------------------------------- */

if ( !isset( $volcanno_theme_config['include']['theme_options'] ) || $volcanno_theme_config['include']['theme_options'] == '1' ) {

    // Load from Child theme is file is available
    if ( file_exists( VOLCANNO_STYLESHEET_DIR . '/core/components/theme-options/loader.php' ) ) {

        require_once VOLCANNO_STYLESHEET_DIR . '/core/components/theme-options/loader.php';
    }
    // Load from Parent theme is file is available
    else if ( file_exists( VOLCANNO_THEME_DIR . '/core/components/theme-options/loader.php' ) ) {

        require_once VOLCANNO_THEME_DIR . '/core/components/theme-options/loader.php';
    }
}

/* -------------------------------------------------------------------- 
 * Load MetaBox loader file
 * -------------------------------------------------------------------- */

if ( !isset( $volcanno_theme_config['include']['metabox'] ) || $volcanno_theme_config['include']['metabox'] == '1' ) {

   if ( file_exists( VOLCANNO_STYLESHEET_DIR . '/core/components/meta-box/loader.php' ) ) {

        require_once VOLCANNO_STYLESHEET_DIR . '/core/components/meta-box/loader.php';
    }
    // Load from Parent theme is file is available
    else if ( file_exists( VOLCANNO_THEME_DIR . '/core/components/meta-box/loader.php' ) ) {

        require_once VOLCANNO_THEME_DIR . '/core/components/meta-box/loader.php';
    }
}

/* -------------------------------------------------------------------- 
 * TGMPA plugins list
 * -------------------------------------------------------------------- */

if ( !isset( $volcanno_theme_config['include']['tgmpa'] ) || $volcanno_theme_config['include']['tgmpa'] == '1' ) {

    // filter config files directory
    $volcanno_config_files_dir = apply_filters( 'volcanno_config_files_dir', '' );

    // check if value is filtered
    if ( !empty( $volcanno_config_files_dir ) ) {
        $file_url = trailingslashit( $volcanno_config_files_dir ) . 'plugin-list.php';

        if ( file_exists( $file_url ) ) {
            require_once $file_url;
        }
    }
    // Load from Child theme is file is available
    else if ( file_exists( VOLCANNO_STYLESHEET_DIR . '/includes/configuration/plugin-list.php' ) ) {

        require_once VOLCANNO_STYLESHEET_DIR . '/includes/configuration/plugin-list.php';
    }
    // Load from Parent theme is file is available
    else if ( file_exists( VOLCANNO_THEME_DIR . '/includes/configuration/plugin-list.php' ) ) {

        include_once VOLCANNO_THEME_DIR . '/includes/configuration/plugin-list.php';
    }
}

/* -------------------------------------------------------------------- 
 * Include image resizer script
 * -------------------------------------------------------------------- */

if ( !isset( $volcanno_theme_config['include']['image_resizer'] ) || $volcanno_theme_config['include']['image_resizer'] == '1' ) {

    // Load from Child theme is file is available
    if ( file_exists( VOLCANNO_STYLESHEET_DIR . '/core/libs/image_resizer.php' ) ) {

        require_once VOLCANNO_STYLESHEET_DIR . '/core/libs/image_resizer.php';
    }
    // Load from Parent theme is file is available
    else if ( file_exists( VOLCANNO_THEME_DIR . '/core/libs/image_resizer.php' ) ) {

        include_once VOLCANNO_THEME_DIR . '/core/libs/image_resizer.php';
    }
}

/* -------------------------------------------------------------------- 
 * Include Visual Composer setup file
  -------------------------------------------------------------------- */

// Load from Child theme is file is available
if ( file_exists( VOLCANNO_STYLESHEET_DIR . '/core/components/visual-composer/setup.php' ) ) {

    require_once VOLCANNO_STYLESHEET_DIR . '/core/components/visual-composer/setup.php';
}
// Load from Parent theme is file is available
else if ( file_exists( VOLCANNO_THEME_DIR . '/core/components/visual-composer/setup.php' ) ) {

    require VOLCANNO_THEME_DIR . '/core/components/visual-composer/setup.php';
}

/* -------------------------------------------------------------------- 
 * Include Widgets setup file
  -------------------------------------------------------------------- */

// Load from Child theme is file is available
if ( file_exists( VOLCANNO_STYLESHEET_DIR . '/core/components/widgets/setup.php' ) ) {

    require_once VOLCANNO_STYLESHEET_DIR . '/core/components/widgets/setup.php';
}
// Load from Parent theme is file is available
else if ( file_exists( VOLCANNO_THEME_DIR . '/core/components/widgets/setup.php' ) ) {

    require VOLCANNO_THEME_DIR . '/core/components/widgets/setup.php';
}


/* -------------------------------------------------------------------- 
 * Include Menus registration script
  -------------------------------------------------------------------- */

// Load from Child theme is file is available
if ( file_exists( VOLCANNO_STYLESHEET_DIR . '/core/libs/menus.php' ) ) {

    require_once VOLCANNO_STYLESHEET_DIR . '/core/libs/menus.php';
}
// Load from Parent theme is file is available
else if ( file_exists( VOLCANNO_THEME_DIR . '/core/libs/menus.php' ) ) {

    require VOLCANNO_THEME_DIR . '/core/libs/menus.php';
}