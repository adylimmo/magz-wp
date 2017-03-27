<?php
/*
 * ---------------------------------------------------------
 * Color style
 *
 * Class for custom color style rendering
 * ----------------------------------------------------------
 */
if ( !class_exists('Volcanno_Color_Style') ) {
    class Volcanno_Color_Style {

        /**
         * Compiles custom CSS and returns new CSS as string
         * 
         * @param array $color_styles
         * @return string
         */
        static function get_compiled_css( $color_styles ) {

            // array keys are redux field ID's
            $main_color = $color_styles['custom_color_style'];
            $hover_color = $color_styles['custom_color_style_2'];

            ob_start();
            ?>

            a:focus,
            a:hover,
            .read-more,
            #quick-links > li > a,
            #social-links > li a:hover,
            .service-box > .icon-container > i,
            a:hover h1,
            a:hover h2,
            a:hover h3,
            a:hover h4,
            a:hover h5,
            a:hover h6,
            .odometer.odometer-auto-theme,
            .odometer.odometer-theme-default,
            .odometer-inner > i,
            .cta-social > li > a:hover,
            #footer-wrapper a:hover,
            .widget > ul > li::before,
            .contact-info-list > li > i,
            .header-style-02 .header-info-widgets > ul > li .text-container a:hover,
            .header-style-02 .navbar-default .navbar-nav > li.current-menu-item > a,
            .header-style-02 .navbar-default .navbar-nav > li > a:hover,
            .header-style-02 #mega-menu-wrap-primary #mega-menu-primary > li.mega-menu-item > a.mega-menu-link:hover,
            .header-style-02 #mega-menu-wrap-primary #mega-menu-primary > li.mega-menu-item.mega-toggle-on > a.mega-menu-link,
            .header-style-02 #mega-menu-wrap-primary #mega-menu-primary > li.mega-menu-item > a.mega-menu-link:hover,
            .header-style-02 #mega-menu-wrap-primary #mega-menu-primary > li.mega-menu-item > a.mega-menu-link:focus,
            .header-style-02 #mega-menu-wrap-primary #mega-menu-primary > li.mega-menu-item.mega-current-menu-parent > a.mega-menu-link,
            .fa-ul.large-icons > li .icon-container,
            #footer-wrapper .pi-latest-posts-02 .post-body h4:hover,
            .pi-latest-posts-02 .post-body h4:hover,
            .featured-page-box > .body h2::before,
            .featured-page-box > .body h3::before,
            .featured-page-box > .body h4::before,
            .featured-page-box > .body h5::before,
            .featured-page-box > .body h6::before,
            .page-content.dark .odometer-value,
            .service-box.service-box-listed .icon-container > i,
            .banner-box > i,
            .page-content.dark .odometer-container .digit-mark,
            .breadcrumb > li > a:hover,
            a.download-link::before,
            .feature-box .icon-container i,
            .company-timeline .icon-date-container i,
            .fa-ul.default i,
            .text-colored,
            p.text-colored,
            .pricing-table .price span,
            .cases-grid .portfolio-item .body h2::before,
            .cases-grid .portfolio-item .body h3::before,
            .cases-grid .portfolio-item .body h4::before,
            .cases-grid .portfolio-item .body h5::before,
            .cases-grid .portfolio-item .body h6::before,
            .pi-latest-posts-03 > li > a:hover,
            .widget > .pi-latest-posts-03 > li > a:hover,
            .post-comments > ul.comments-li > li .comment .comment-meta > li a:hover,
            .portfolio-blog-nav-simple p > a::after,
            .portfolio-blog-nav-simple p > a:hover,
            .vc_tta.vc_general.vc_tta-style-consulting-press-theme-color-transparent .vc_tta-tab.vc_active>a,
            .vc_tta.vc_general.vc_tta-style-consulting-press-theme-color-transparent .vc_tta-tab>a:hover,
            .vc_tta.vc_general.vc_tta-style-consulting-press-theme-color-transparent .vc_tta-tab>a:focus,
            .vc_tta.vc_tta-tabs.vc_tta-style-consulting-press-theme-color .vc_tta-tab.vc_active > a,
            .vc_tta.vc_tta-tabs.vc_tta-style-consulting-press-theme-color .vc_tta-tab > a:hover,
            .vc_tta.vc_tta-tabs.vc_tta-style-consulting-press-theme-color .vc_tta-tab > a:focus,
            .vc_tta.vc_general.vc_tta-style-consulting-press-theme-color-transparent .vc_tta-panel .vc_tta-panel-heading .vc_tta-panel-title > a:hover,
            .vc_tta.vc_general.vc_tta-style-consulting-press-theme-color-transparent .vc_tta-panel .vc_tta-panel-heading .vc_tta-panel-title > a:focus,
            .vc_tta.vc_general.vc_tta-style-consulting-press-theme-color-transparent .vc_tta-panel.vc_active .vc_tta-panel-heading .vc_tta-panel-title > a,
            .vc_tta.vc_general.vc_tta-style-consulting-press-theme-color .vc_tta-panel.vc_active .vc_tta-panel-heading .vc_tta-panel-title > a,
            .vc_tta.vc_general.vc_tta-style-consulting-press-theme-color .vc_tta-panel .vc_tta-panel-heading .vc_tta-panel-title > a:hover,
            .vc_tta.vc_general.vc_tta-style-consulting-press-theme-color .vc_tta-panel .vc_tta-panel-heading .vc_tta-panel-title > a:focus,
            .header-cart .cart-container a:hover,
            .page-title-style-03 .breadcrumb > li > span.active,
            .woocommerce .woocommerce-pagination .page-numbers li > a.next.page-numbers:after,
            .woocommerce .widget_product_categories .product-categories .cat-item.current-cat:before,
            .woocommerce .widget_product_categories .product-categories .cat-item:hover:before,
            .widget_nav_menu .menu-item > a:before,
            .rpwe_widget div.rpwe-block .rpwe-ul li .rpwe-title a:hover,
            .widget_categories ul li.cat-item:hover::before,
            .widget_categories ul li.cat-item.current-cat::before,
            .aside-widgets .widget.widget_archive ul li:hover::before{
                color: <?php echo wp_kses( $main_color, array() ); ?>
            }

            #quick-links > li > a::before,
            .btn,
            .read-more::after,
            .accordion > .title::before,
            .testimonial-style-02::before,
            .wpcf7 .wpcf7-submit,
            .header-style-02.header-negative-bottom #search,
            .featured-page-box > .body::before,
            .fa-ul.ul-circled .icon-container,
            .custom-heading-03::before,
            .company-timeline .timeline-item-details h1::before,
            .company-timeline .timeline-item-details h2::before,
            .company-timeline .timeline-item-details h3::before,
            .company-timeline .timeline-item-details h4::before,
            .company-timeline .timeline-item-details h5::before,
            .company-timeline .timeline-item-details h6::before,
            .cases-grid .portfolio-item .body::before,
            .pagination li.active a,
            .pagination > ul > li:hover > a,
            #filters > li::before,
            #filters > li.filter-button.is-checked::before,
            .tagcloud > a:hover,
            .comment-form-container .form-submit #comment-reply,
            .comment-form .form-submit #comment-reply,
            .text-highlighted,
            .divider-scroll-up .scroll-up:hover,
            .scroll-up:hover,
            .tabs-container.tabs-dark > .tabs > li.active a,
            .tabs-container.tabs-dark > .tabs > li:hover a,
            .header-cart .cart-container a .cart-count,
            .vc_tta.vc_tta-accordion .vc_tta-controls-icon-position-left .vc_tta-controls-icon,
            .vc_tta.vc_general.vc_tta-tabs.vc_tta-style-consulting-press-dark-color .vc_tta-tab.vc_active,
            .vc_tta.vc_general.vc_tta-tabs.vc_tta-style-consulting-press-dark-color .vc_tta-tab:hover,
            .vc_tta.vc_general.vc_tta-style-consulting-press-dark-color .vc_tta-panel.vc_active .vc_tta-panel-heading .vc_tta-panel-title,
            .vc_tta.vc_general.vc_tta-style-consulting-press-dark-color .vc_tta-panel .vc_tta-panel-heading .vc_tta-panel-title:hover,
            .woocommerce a.button,
            .woocommerce.woocommerce-page button.button.alt,
            .woocommerce .woocommerce-pagination .page-numbers li > a:hover,
            .woocommerce nav.woocommerce-pagination ul li span.current,
            .woocommerce .woocommerce-pagination .page-numbers li > a.next.page-numbers:hover,
            .woocommerce .woocommerce-pagination .page-numbers li > a.next.page-numbers:focus,
            .woocommerce nav.woocommerce-pagination ul li span.current,
            .woocommerce nav.woocommerce-pagination ul li a:hover,
            .woocommerce nav.woocommerce-pagination ul li a:focus,
            .woocommerce #review_form #respond .form-submit input,
            .widget_calendar #wp-calendar #today,
            #em-wrapper div.css-search.has-advanced div.em-search-main .em-search-submit,
            .woocommerce .widget_price_filter .price_slider_amount .button,
            .woocommerce a.button.alt,
            .woocommerce input.button.alt,
            .em-booking-buttons .em-booking-submit,
            .newsletter-widget .submit{
                background-color: <?php echo wp_kses( $main_color, array() ); ?>
            }
            .widget_newsletterwidget .tnp-widget input.tnp-submit{
                background-color: <?php echo wp_kses( $main_color, array() ); ?> !important;
            }
            .circle-icon-animate {
                stroke: <?php echo wp_kses( $main_color, array() ); ?>
            }

            blockquote {
                border-color: <?php echo wp_kses( $main_color, array() ); ?>
            }

            .wpcf7 .wpcf7-submit:hover,
            .comment-form-container .form-submit #comment-reply:hover,
            .comment-form .form-submit #comment-reply:hover,
            .woocommerce a.button:hover,
            .woocommerce.woocommerce-page button.button.alt:hover,
            .woocommerce #review_form #respond .form-submit input:hover,
            #em-wrapper div.css-search.has-advanced div.em-search-main .em-search-submit:hover,
            .woocommerce .widget_price_filter .price_slider_amount .button:hover,
            .woocommerce a.button.alt:hover,
            .woocommerce input.button.alt:hover,
            .em-booking-buttons .em-booking-submit:hover,
            .newsletter-widget .submit:hover{
                background-color: <?php echo wp_kses( $hover_color, array() ); ?>
            }
            .widget_newsletterwidget .tnp-widget input.tnp-submit:hover{
                background-color: <?php echo wp_kses( $hover_color, array() ); ?> !important;
            }
            ::-moz-selection {
                color: #fff;
                background: <?php echo wp_kses( $main_color, array() ); ?>
            }

            ::selection {
                color: #fff;
                background: <?php echo wp_kses( $main_color, array() ); ?>
            }

            <?php
            $css = ob_get_clean();

            return $css;
        }

    }
}