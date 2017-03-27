<?php

/**
 * Class related to Icon Fonts parsing and loading
 */
class Volcanno_Support {

    static function init() {
        if ( self::child_override( __FUNCTION__, func_get_args() ) ) {
            return self::child_override( __FUNCTION__, func_get_args() ); // Child overide
        }

        // Enqueue stylesheets
        add_action( 'admin_enqueue_scripts', 'Volcanno_Support::admin_menu_enqueue_stylesheet' );
    }

    /**
     * Overide method if same function in child theme exist
     * 
     * @param  string
     * @return  function
     */
    static function child_override( $function, $params = '' ) {

        $class = __CLASS__ . '_Child';

        if ( class_exists( $class ) && is_callable( array( $class, $function ) ) ) {
            return call_user_func_array( array( $class, $function ), $params );
        }
    }

    /**
     * Enqueue stylesheets
     * 
     * @param  $hook
     * @return void    
     */
    static function admin_menu_enqueue_stylesheet( $hook ) {
        if ( self::child_override( __FUNCTION__, func_get_args() ) )
            return self::child_override( __FUNCTION__, func_get_args() ); // Child overide

        wp_enqueue_style( 'volcanno_admin_menu', VOLCANNO_TEMPLATEURL . '/core/assets/css/admin-menu.css', array(), '1.0', 'screen' );
        wp_enqueue_script( 'volcanno-admin-menu', VOLCANNO_TEMPLATEURL . '/core/assets/js/admin-menu.js', array( 'jquery' ), '1.0', true );
    }

    /**
     * Render page for parsing icons fonts
     *
     * @return string
     */
    static function render_submenu_page() {
        if ( self::child_override( __FUNCTION__, func_get_args() ) )
            return self::child_override( __FUNCTION__, func_get_args() ); // Child overide

        global $volcanno_theme_config;

        $theme = sanitize_title( VOLCANNO_THEME_NAME );

        $support_url = 'http://pixel-industry.com/web/wp-login.php?action=';
        $support_login_url = $support_url . 'login?theme=' . $theme;
        $support_register_url = $support_url . 'register';

        $support_envato_url = "";

        $envato_login = false;

        $support_class = $envato_login ? '' : ' standard';

        // get all installed themes
        $themes = wp_get_themes();

        // get current theme object
        $current_theme = wp_get_theme( get_template() );

        ob_start();
        ?>
        <div class="volcanno-theme-page">
            <div class="page-content">
                <div class="theme-info">
                    <h1><?php echo esc_html( $current_theme->get( 'Name' ) ) ?></h1>
                    <p><?php echo esc_html( $current_theme->get( 'Description' ) ) ?></p>
                    <label><?php esc_html_e( "Version:", 'consultingpress' ); ?></label>
                    <p><?php echo esc_html( $current_theme->get( 'Version' ) ) ?></p>
                </div><!-- .theme-info end -->

                <div class="theme-details clearfix">

                    <div class="theme-presentation-box">
                        <div class="content">
                            <h2><?php esc_html_e( "Documentation", 'consultingpress' ); ?></h2>
                            <p><?php esc_html_e( "Theme comes with Documentation where you can find how to Setup and use the theme. Documentation can be found in archive downloaded from ThemeForest.", 'consultingpress' ); ?></p>
                        </div>
                    </div>

                    <div class="theme-presentation-box">
                        <div class="content">
                            <h2><?php esc_html_e( "Updates", 'consultingpress' ); ?></h2>
                            <?php if ( is_plugin_inactive( 'envato-market/envato-market.php' ) ) { ?>
                                <p><?php esc_html_e( "To receive notification for theme updates please install official ThemeForest plugin for updates: ", 'consultingpress' ); ?>
                                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=install-required-plugins' ) ); ?>"><?php esc_html_e( "Envato Market - Updates.", 'consultingpress' ); ?></a></p>
                            <?php } else { ?>
                                <p><?php esc_html_e( "To receive notification for theme updates please use official ThemeForest plugin for updates: ", 'consultingpress' ); ?>
                                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=envato-market' ) ); ?>"><?php esc_html_e( "Envato Market - Updates.", 'consultingpress' ); ?></a></p>
                            <?php } ?>
                        </div>
                    </div>
                </div><!-- .theme-details end -->
            </div><!-- .page-content end -->
        </div>
        <div class="volcanno-support-page">
            <div class="page-content">
                <div class="support-info">
                    <h1><?php esc_html_e( "Support", 'consultingpress' ); ?></h1>
                    <p>
                        <?php esc_html_e( "If you can't find answer to your problem in Documentation please use one of the following options.", 'consultingpress' ); ?>
                        <br /> 
                        <?php esc_html_e( "We have dedicated support team that will help you with your problem.", 'consultingpress' ); ?>
                    </p>
                </div><!-- .theme-info end -->

                <div class="support-details clearfix <?php echo sanitize_html_class( $support_class ); ?>"> 
                    <?php if ( $envato_login ) : ?>

                        <div class="theme-support-box envato-login-box">
                            <div class="content">
                                <a href="<?php echo esc_url( $support_envato_url ); ?>">
                                    <?php esc_html_e( "Click here to Login with Envato ", 'consultingpress' ); ?>
                                    <br /> 
                                    <?php esc_html_e( "account with just one click", 'consultingpress' ); ?>
                                </a>
                            </div>
                        </div><!-- .theme-image end -->

                        <div class="theme-support-box divider-box">
                            <div class="content">
                                <p><?php esc_html_e( "OR", 'consultingpress' ); ?></p>
                            </div>
                        </div>

                    <?php endif; ?>

                    <div class="theme-support-box standard-login-box">
                        <div class="content">
                            <h2><?php esc_html_e( "Existing user", 'consultingpress' ); ?></h2>
                            <a href="<?php echo esc_url( $support_login_url ); ?>" target="_blank"><?php esc_html_e( "Click here to login.", 'consultingpress' ); ?></a>
                        </div>
                    </div>

                    <div class="theme-support-box support-register-box">
                        <div class="content">
                            <h2><?php esc_html_e( "Not registered?", 'consultingpress' ); ?></h2>
                            <a href="<?php echo esc_url( $support_register_url ); ?>" target="_blank"><?php esc_html_e( "Click here to create your account.", 'consultingpress' ); ?></a>
                        </div>
                    </div>
                </div><!-- .theme-details end -->
            </div><!-- .page-content end -->
        </div><!-- .volcanno-theme-page end -->
        <?php
        echo ob_get_clean();
    }

}

Volcanno_Support::init();
