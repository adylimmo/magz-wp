<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <?php wp_head(); ?>
    </head>

    <body <?php body_class(); ?>>

        <?php echo Volcanno_Partials::generate_loader(); 

        global $volcanno_header_type;
        global $volcanno_parent_id;
        global $post;

        $volcanno_parent_id = get_the_ID();

        // Check if single has parent 
        if ( is_singular( 'pi_case_studies' ) ) {

            $main_category = get_the_terms( get_the_id(), 'case-studies-category');
            $main_category_id = !empty( $main_category[0]->term_id ) ? $main_category[0]->term_id : '';
            $volcanno_parent_id = get_term_meta( $main_category_id, 'parent_case_studies_page', true ) ? : get_the_ID();

        } else if ( is_single() ) {

            // Parent page/category
            $main_category = get_the_category();
            $main_category_id = !empty( $main_category[0]->cat_ID ) ? $main_category[0]->cat_ID : '';
            $volcanno_parent_id = get_term_meta( $main_category_id, 'parent_blog_page', true ) ? : get_the_ID();

            if ( isset($post->post_type) && $post->post_type == 'product' ) {
                $shop_id = get_option( 'woocommerce_shop_page_id' );
                $volcanno_parent_id = apply_filters( 'woocommerce_get_shop_page_id', $shop_id );
            }

        } else if ( VOLCANNO_WOOCOMMERCE && is_shop() ) {
            $shop_id = get_option( 'woocommerce_shop_page_id' );
            $volcanno_parent_id = apply_filters( 'woocommerce_get_shop_page_id', $shop_id );
        }

        // Set header type
        $volcanno_header_type_def = get_post_meta( $volcanno_parent_id, 'pg_page_custom_header_type', true ) ? : Volcanno::return_theme_option( 'header_type' );
        $volcanno_header_type = apply_filters( 'volcanno_header_type', $volcanno_header_type_def );

        if ( !VOLCANNO_REDUX_FRAMEWORK ) {
            $volcanno_header_type = 'finance';
        }

        $header_type_class = array( 'header-wrapper', 'clearfix' );

        switch ( $volcanno_header_type ) {
            case 'finance':
                $header_type_class[] = 'header-transparent';
                $header_type_class[] = 'header-finance';
                break;
            case 'it_security':
                $header_type_class[] = 'header-style-02';
                $header_type_class[] = 'header-it-security';
                break;
            case 'management':
                $header_type_class[] = 'header-style-02';
                $header_type_class[] = 'header-negative-bottom';
                $header_type_class[] = 'header-management';
                break;
            case 'tourism':
                $header_type_class[] = 'header-transparent';
                $header_type_class[] = 'header-tourism';
                break;
            default:
        }

        $header_classes = apply_filters( 'volcanno_header_type_classes', $header_type_class );
        ?>

        <!-- Header wrapper start -->
        <div class="<?php echo join( ' ', $header_classes ) ?>">

            <!-- #header start -->
            <header id="header">

                <!-- .container start -->
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- .top-bar-wrapper start -->
                            <div class="top-bar-wrapper clearfix">

                                <div style="display : none" class="row">
                                    <?php if ( $volcanno_header_type == 'it_security' || $volcanno_header_type == 'management' ) : ?>

                                        <!-- .col-md-3 start -->
                                        <div class="col-md-3">
                                            <?php echo Volcanno_Partials::generate_logo(); ?>
                                        </div><!-- .col-md-3 end -->

                                        <!-- .col-md-9 start -->
                                        <div class="col-md-9">
                                            <?php echo Volcanno_Partials::header_element( 'info_widgets' ); ?>
                                        </div><!-- .col-md-9 end -->

                                    <?php else : ?>

                                        <!-- .col-md-6 start -->
                                        <div class="col-md-6">
                                            <!-- #quick-links start -->
                                            <?php echo Volcanno_Partials::header_element( 'quick_links' ); ?>
                                        </div><!-- .col-md-6 end -->

                                        <!-- .col-md-6 start -->
                                        <div class="col-md-6 clearfix">
                                            <?php echo Volcanno_Partials::header_element( 'cart' ); ?>

                                            <?php echo Volcanno_Partials::header_element( 'language_switcher' ); ?>

                                            <?php echo Volcanno_Partials::header_element( 'social_links' ); ?>
                                        </div><!-- .col-md-6 end -->

                                    <?php endif; ?>
                                </div><!-- nested row end -->
                            </div><!-- .top-bar-wrapper end -->
                        </div><!-- .col-md-12 end -->
                    </div><!-- .row end -->
                </div><!-- .container end -->

                <!-- .header-inner start -->
                <div class="header-inner">

                    <!-- .container start -->
                    <div class="container">

                        <div class="row">

                            <!-- .col-md-12 start -->
                            <div class="col-md-12">
                                <?php if ( $volcanno_header_type == 'it_security' ) : ?>
                                    <div class="main-navigation">
                                        <!-- .navbar.navbar-default start -->
                                        <nav class="navbar navbar-default nav-left pi-mega">
                                            <!-- .navbar-header start -->
                                            <div class="navbar-header">
                                                <?php echo Volcanno_Partials::header_element( 'navbar_toggle' ); ?>
                                            </div><!-- .navbar-header end -->
                                            <!-- MAIN NAVIGATION -->
                                            <div id="main-nav" class="collapse navbar-collapse">
                                                <?php echo Volcanno_Partials::generate_menu_html(); ?>
                                            </div><!-- navbar end -->
                                            <div class="nav-additional-links">
                                                <?php echo Volcanno_Partials::header_element( 'search' ); ?>
                                                <div class="nav-plugins clearfix">
                                                    <?php echo Volcanno_Partials::header_element( 'wpml_languages' ); ?>
                                                    <?php echo Volcanno_Partials::header_element( 'cart' ); ?>
                                                </div><!-- .nav-plugins end -->
                                            </div><!-- .nav-additional-links -->
                                        </nav><!-- .navbar.navbar-default end -->
                                    </div> <!-- .main-nav end -->
                                <?php elseif ( $volcanno_header_type == 'management' ): ?>
                                    <!-- .main-nav start -->
                                    <div class="main-navigation">
                                        <!-- .navbar.navbar-default start -->
                                        <nav class="navbar navbar-default nav-left pi-mega">
                                            <!-- .navbar-header start -->
                                            <div class="navbar-header">
                                                <?php echo Volcanno_Partials::header_element( 'navbar_toggle' ); ?>
                                            </div><!-- .navbar-header end -->
                                            <!-- MAIN NAVIGATION -->
                                            <div id="main-nav" class="collapse navbar-collapse">
                                                <?php echo Volcanno_Partials::generate_menu_html(); // Need check for max mega menu ( included in mixed theme) ?>
                                            </div><!-- navbar end -->
                                            <div class="nav-additional-links">
                                                <?php echo Volcanno_Partials::header_element( 'search' ); ?>
                                                <div class="nav-plugins clearfix">
                                                    <?php echo Volcanno_Partials::header_element( 'wpml_languages' ); ?>
                                                    <?php echo Volcanno_Partials::header_element( 'cart' ); ?>
                                                </div><!-- .nav-plugins end -->
                                            </div><!-- .nav-additional-links -->
                                        </nav><!-- .navbar.navbar-default end -->
                                    </div> <!-- .main-nav end -->
                                <?php else : ?>
                                    <!-- .main-nav start -->
                                    <div class="main-nav">
                                        <!-- .navbar.navbar-default start -->
                                        <nav class="navbar navbar-default nav-left pi-mega">
                                            <!-- .navbar-header start -->
                                            <div class="navbar-header">
                                                <?php echo Volcanno_Partials::generate_logo(); ?>
                                                <?php echo Volcanno_Partials::header_element( 'navbar_toggle' ); ?>
                                            </div><!-- .navbar-header end -->
                                            <?php echo Volcanno_Partials::header_element( 'search' ); ?>
                                            <!-- MAIN NAVIGATION -->
                                            <div id="main-nav" class="collapse navbar-collapse">
                                                <?php echo Volcanno_Partials::generate_menu_html(); // Need check for max mega menu ( included in mixed theme) ?>
                                            </div><!-- navbar end -->
                                        </nav><!-- .navbar.navbar-default end -->
                                    </div> <!-- .main-nav end -->
                                <?php endif; ?>
                            </div><!-- .col-md-12 end -->

                        </div><!-- .row end -->

                    </div><!-- .container end -->

                </div><!-- .header-inner end -->
            </header><!-- #header end -->
        </div><!-- .header-wrapper.header-transparent end -->