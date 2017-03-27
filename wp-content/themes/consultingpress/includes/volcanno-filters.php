<?php
/* ---------------------------------------------------------
 * Filters
 *
 * Class for registering filters
  ---------------------------------------------------------- */

class Volcanno_Theme_Filters {

    static $hooks = array();

    /**
     * Initialize filters
     */
    static function init() {

        /**
         * Array of filter hooks
         *
         * Usage:
         *
         * 'filter_name' => array(
         *      'callback' => array( priority, accepted_args )
         * )
         *
         * When 'callback' value is empty (non-array) or any of values ommited,
         * default priority and accepted args will be used
         *
         * e.g.
         * priority = 10
         * accepted_args = 1
         */
        static::$hooks = array(
            // WORDPRESS FILTERS

            'admin_body_class' => array(
                'Volcanno_Theme_Filters::admin_body_class'
            ),
            'body_class' => array(
                'Volcanno_Theme_Filters::add_body_classes'
            ),
            'excerpt_length' => array(
                'Volcanno_Theme_Filters::excerpt_length'
            ),
            'wp_page_menu_args' => array(
                'Volcanno_Theme_Filters::page_menu_args'
            ),
            'comment_form_defaults' => array(
                'Volcanno_Theme_Filters::get_comment_form'
            ),
            'comment_form_fields' => array(
                'Volcanno_Theme_Filters::move_comment_form_below'
            ),
            'tiny_mce_before_init' => array(
                'Volcanno_Theme_Filters::add_tinymce_tables'
            ),
            'override_load_textdomain' => array(
                'Volcanno_Theme_Filters::override_load_textdomain' => array(5, 3)
            ),
            // THEME AND FRAMEWORK FILTERS
            'vcpt_register_custom_post_types' => array(
                'Volcanno_Theme_Filters::register_custom_post_types'
            ),
            'volcanno_blog_style' => array(
                'Volcanno_Theme_Filters::search_page_blog_style'
            ),
            'cma_grid_options_fields' => array(
                'Volcanno_Theme_Filters::cma_grid_options_fields'
            ),
            'cma_section_options_fields' => array(
                'Volcanno_Theme_Filters::cma_section_options_fields'
            ),
            'cma_settings_fields' => array(
                'Volcanno_Theme_Filters::cma_settings_fields'
            ),
            'volcanno_blog_image_width' => array(
                'Volcanno_Theme_Filters::get_blog_image_size'
            ),
            'volcanno_main_menu_location' => array(
                'Volcanno_Theme_Filters::main_menu_name'
            ),
            'get_search_form' => array(
                'Volcanno_Theme_Filters::volcanno_get_search_form' => array(100)
            ),
            // WooCommerce add to cart AJAX
            'add_to_cart_fragments' => array(
                'Volcanno_Theme_Filters::woocommerce_header_add_to_cart_fragment'
            ),
            'woocommerce_show_page_title' => array(
                'Volcanno_Theme_Filters::disable_woocommerce_page_title'
            ),
            // Change how many WooCommerce related products to show
            'woocommerce_output_related_products_args' => array(
                'Volcanno_Theme_Filters::woo_related_products_args'
            ),
            'volcanno_header_type_classes' => array(
                'Volcanno_Theme_Filters::header_type_classes'
            ),
            'post_class' => array(
                'Volcanno_Theme_Filters::post_classes'
            ),
            'the_content' => array(
                'Volcanno_Theme_Filters::events_manager_fix_tags'
            ),
            'vifp_enabled_icon_fonts' => array(
                'Volcanno_Theme_Filters::vifp_enable_icon_fonts'
            ),
            'manage_posts_columns' => array(
                'Volcanno_Theme_Filters::admin_add_parent_column'
            ),
        );

        if (shortcode_exists('ssba')) {
            add_filter('ssba_html_output', 'Volcanno_Theme_Filters::remove_ssba_from_content', 10, 2);
        }
        if (class_exists('Mega_Menu')) {
            add_filter('megamenu_themes', 'Volcanno_Theme_Filters::add_megamenu_custom_theme');
        }

        Volcanno::register_hooks(static::$hooks, 'filter');
    }

    /**
     * Add custom classes to blog post
     * 
     * @param type $classes
     * @return array
     */
    static function post_classes($classes) {
        $classes[] = 'post-container';
        $classes[] = 'clearfix';

        return $classes;
    }

    /**
     * Add custom classes body
     * 
     * @param type $classes
     * @return array
     */
    static function add_body_classes($classes) {

        global $post;

        $post = get_post();

        $no_vc_content = ( $post && strpos($post->post_content, 'vc_row') ) ? false : true;

        if (!VOLCANNO_VISUAL_COMPOSER && $no_vc_content) {
            $classes[] = 'no-vc';
        } else if (VOLCANNO_VISUAL_COMPOSER && $no_vc_content) {
            $classes[] = 'no-vc';
        }

        return $classes;
    }

    /**
     * Enable icon fonts in Icon Font Parser plugin
     * 
     * @param array $icon_fonts
     * @return array
     */
    static function vifp_enable_icon_fonts($icon_fonts) {

        $icon_fonts['font_awesome'] = true;

        return $icon_fonts;
    }

    /**
     * Add column Parent admin post list
     * 
     * @param array $columns
     */
    static function admin_add_parent_column($columns) {
        return array_merge($columns, array('parent' => esc_html__('Parent', 'consultingpress')));
    }

    /**
     * Ensure cart contents update when products are added to the cart via AJAX
     * @param  $fragments
     * @return string
     */
    static function woocommerce_header_add_to_cart_fragment($fragments) {
        ob_start();
        global $woocommerce;
        global $volcanno_theme_config;
        $amount = !$woocommerce->cart->is_empty() && Volcanno::return_theme_option('display_cart_amount') ? '<span class="amount">' . $woocommerce->cart->get_total() . '</span>' : '';
        ?>
        <div id="header-cart-ajax" class="header-cart clearfix">
            <div class="cart-container icon-cart-3">
                <a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php echo esc_html__("View your shopping cart", 'consultingpress'); ?>">
                    <span class="cart-count"><?php echo esc_html( $woocommerce->cart->get_cart_contents_count() ); ?></span>
                    <?php echo wp_kses( $amount, $volcanno_theme_config['allowed_html_tags'] ); ?>
                </a>
            </div>
        </div>
        <?php
        $fragments['#header-cart-ajax'] = ob_get_clean();

        return $fragments;
    }

    /**
     * Fix html formating for location post type in events manager plugin
     * 
     * @param  string $content
     * @return string
     */
    static function events_manager_fix_tags($content) {

        // Filter content only for location post type
        if (get_post_type() === 'location') {
            $content = preg_replace('/<p>(\s?)*<li>(.*?)<\/li>(\s?)*<\/p>/', '<p>$2</p>', $content);
        }

        return $content;
    }

    /**
     * Filter header type classes
     * 
     * @return array
     */
    static function header_type_classes($header_classes) {

        return $header_classes;
    }

    /**
     * Filter related products arguments
     * 
     * @param array $args
     * @return int
     */
    static function woo_related_products_args($args) {
        $args['posts_per_page'] = 3; // 4 related products
        $args['columns'] = 3; // arranged in 2 columns
        return $args;
    }

    /**
     * Disable woocommerce title on page
     * @return boolean
     */
    static function disable_woocommerce_page_title() {
        return false;
    }

    /**
     * Add themes to Max Mega Menu plugin
     * 
     * @param type $themes
     * @return array
     */
    static function add_megamenu_custom_theme($themes) {
        $default = $themes['default'];
        $icon = '\f078';
        $consulting = array(
            'title' => 'ConsultingPress',
            'container_background_from' => 'rgba(0,0,0,0)',
            'container_background_to' => 'rgba(0,0,0,0)',
            'menu_item_background_hover_from' => 'rgba(0,0,0,0)',
            'menu_item_background_hover_to' => 'rgba(0,0,0,0)',
            'menu_item_link_font_size' => '13px',
            'menu_item_link_height' => '50px',
            'menu_item_link_color' => '#c5dadc',
            'menu_item_link_weight' => 'inherit',
            'menu_item_link_color_hover' => '#fff',
            'menu_item_link_weight_hover' => 'inherit',
            'menu_item_link_padding_left' => '15px',
            'menu_item_link_padding_right' => '15px',
            'menu_item_link_padding_top' => '10px',
            'menu_item_link_padding_bottom' => '10px',
            'panel_background_from' => 'rgba(7, 23, 64, 0.8)',
            'panel_background_to' => 'rgba(7, 23, 64, 0.8)',
            'panel_header_color' => '#c5dadc',
            'panel_header_text_transform' => 'none',
            'panel_header_font_size' => '10px',
            'panel_header_font_weight' => 'inherit',
            'panel_header_padding_top' => '10px',
            'panel_header_border_color' => '#c5dadc',
            'panel_padding_top' => '15px',
            'panel_padding_bottom' => '10px',
            'panel_font_size' => '12px',
            'panel_font_color' => '#fff',
            'panel_font_family' => 'inherit',
            'panel_second_level_font_color' => '#c5dadc',
            'panel_second_level_font_color_hover' => '#c5dadc',
            'panel_second_level_text_transform' => 'none',
            'panel_second_level_font' => 'inherit',
            'panel_second_level_font_size' => '10px',
            'panel_second_level_font_weight' => 'inherit',
            'panel_second_level_font_weight_hover' => 'inherit',
            'panel_second_level_text_decoration' => 'none',
            'panel_second_level_text_decoration_hover' => 'none',
            'panel_second_level_border_color' => '#c5dadc',
            'panel_third_level_font_color' => '#fff',
            'panel_third_level_font_color_hover' => '#fff',
            'panel_third_level_font' => 'inherit',
            'panel_third_level_font_size' => '12px',
            'panel_third_level_font_weight' => 'inherit',
            'panel_third_level_font_weight_hover' => 'inherit',
            'panel_third_level_padding_bottom' => '5px',
            'flyout_menu_background_from' => 'rgba(7, 23, 64, 0.8)',
            'flyout_menu_background_to' => 'rgba(7, 23, 64, 0.8)',
            'flyout_link_padding_left' => '25px',
            'flyout_link_padding_right' => '25px',
            'flyout_link_padding_top' => '5px',
            'flyout_link_padding_bottom' => '10px',
            'flyout_link_weight' => 'inherit',
            'flyout_link_weight_hover' => 'inherit',
            'flyout_link_height' => '1.42857143',
            'flyout_background_from' => 'rgba(0,0,0,0)',
            'flyout_background_to' => 'rgba(0,0,0,0)',
            'flyout_background_hover_from' => 'rgba(0,0,0,0)',
            'flyout_background_hover_to' => 'rgba(0,0,0,0)',
            'flyout_link_size' => '12px',
            'flyout_link_color' => '#c5dadc',
            'flyout_link_color_hover' => '#fff',
            'flyout_link_family' => 'inherit',
            'flyout_link_text_transform' => 'uppercase',
            'responsive_breakpoint' => '992px',
            'responsive_text' => '',
            'line_height' => '1.42857143',
            'resets' => 'on',
            'toggle_background_from' => 'rgba(0,0,0,0)',
            'toggle_background_to' => 'rgba(0,0,0,0)',
            'toggle_font_color' => '#c5dadc',
            'mobile_background_from' => 'rgba(0,0,0,0)',
            'mobile_background_to' => 'rgba(0,0,0,0)',
            'custom_css' => '#{$wrap} { 
                        clear: both;
                    }
                    #{$wrap} #{$menu} > li.mega-menu-megamenu > ul.mega-sub-menu > li.mega-menu-item li.mega-menu-item > a.mega-menu-link {
                        font-weight: 500 !important;
                        text-transform: uppercase;
                    }
                    #{$wrap} #{$menu} > li.mega-menu-item ul.mega-sub-menu > li.mega-menu-item > a.mega-menu-link {
                        white-space: nowrap;
                        letter-spacing: 1px;
                    }
                    #{$wrap} #{$menu} > li.mega-menu-item ul.mega-sub-menu {
                        width: auto;
                        padding: 15px 0 10px 0;
                    }
                    #{$wrap} #{$menu} > li.mega-menu-item.mega-menu-megamenu > ul.mega-sub-menu {
                        padding-top: 30px;
                        padding-bottom: 20px;
                    }
                        #{$wrap} #{$menu} > li.mega-menu-item.cloned {
                        display: none;
                        }
                    #{$wrap} #{$menu} > li.mega-menu-item.mega-menu-megamenu ul.mega-sub-menu > li.mega-menu-item-has-children {
                        padding-left: 40px;
                    }
                    #{$wrap} #{$menu} > li.mega-menu-item.mega-menu-megamenu ul.mega-sub-menu > li.mega-menu-item-has-children > a.mega-menu-link {
                        color: #fff;
                        font-size: 13px;
                        line-height: 20px;
                        margin-bottom: 15px;
                        font-weight: 500;
                    }
                    #{$wrap} #{$menu} li.mega-menu-item.mega-menu-item-has-children a.mega-menu-link {
                        padding: 10px 20px 10px 15px;
                    }
                    #{$wrap} #{$menu} > li.mega-menu-flyout ul.mega-sub-menu li.mega-menu-item a.mega-menu-link {
                        padding: 5px 45px 10px 25px;
                    }
                    #{$wrap} #{$menu} > li.mega-menu-flyout ul.mega-sub-menu li.mega-menu-item a.mega-menu-link:after {
                        top: 5px;
                        right: 22px;
                    }
                    #{$wrap} #{$menu} li.mega-menu-item.mega-menu-item-has-children a.mega-menu-link:after {
                        right: 5px;
                        top: 8px;
                        position: absolute;
                    }
                    .mega-menu-link-style,
                    #{$wrap} #{$menu} > li.mega-menu-item > a.mega-menu-link,
                    #{$wrap} #{$menu} li.mega-menu-flyout.mega-menu-item-has-children > a.mega-menu-link,
                    #{$wrap} #{$menu} li.mega-menu-flyout li.mega-menu-item-has-children > a.mega-menu-link,
                    #{$wrap} #{$menu} li > li.mega-menu-item-has-children > a.mega-menu-link {
                        color: #c5dadc;
                    }

                    #{$wrap} #{$menu} li.mega-menu-flyout.mega-menu-item-has-children .mega-sub-menu > li > a.mega-menu-link:after{
                     content: "\\\f054" !important;
                      font-family: "FontAwesome" !important;
                        font-size: 7px;
                    }
                    .mega-menu-link-style:after,
                    #{$wrap} #{$menu} li > li.mega-menu-item-has-children > a.mega-menu-link:after,
                    #{$wrap} #{$menu} > li.mega-menu-item > a.mega-menu-link:after,
                    #{$wrap} #{$menu} li.mega-menu-flyout.mega-menu-item-has-children > a.mega-menu-link:after,
                    #{$wrap} #{$menu} li.mega-menu-flyout li.mega-menu-item-has-children > a.mega-menu-link:after
                     {
                        content: "\\\f078" !important;
                        font-family: "FontAwesome" !important;
                        font-size: 7px;
                    }
                    .mega-menu-link-style:hover,
                    #{$wrap} #{$menu} > li.mega-menu-item > a.mega-menu-link:hover,
                    #{$wrap} #{$menu} li.mega-menu-flyout.mega-menu-item-has-children > a.mega-menu-link:hover,
                    #{$wrap} #{$menu} li.mega-menu-flyout li.mega-menu-item-has-children > a.mega-menu-link:hover,
                    #{$wrap} #{$menu} li > li.mega-menu-item-has-children > a.mega-menu-link:hover,
                    .mega-menu-link-style:focus,
                    #{$wrap} #{$menu} > li.mega-menu-item > a.mega-menu-link:focus,
                    #{$wrap} #{$menu} li.mega-menu-flyout.mega-menu-item-has-children > a.mega-menu-link:focus,
                    #{$wrap} #{$menu} li.mega-menu-flyout li.mega-menu-item-has-children > a.mega-menu-link:focus,
                    #{$wrap} #{$menu} li > li.mega-menu-item-has-children > a.mega-menu-link:focus {
                        color: #fff;
                        font-weight: 500
                    }
                    .mega-submenu-link-style,
                    #{$wrap} #{$menu} > li.mega-menu-item.mega-current-menu-parent ul.mega-sub-menu .mega-menu-item.mega-menu-item-has-children > a.mega-menu-link,
                    #{$wrap} #{$menu} > li.mega-menu-item.mega-current-menu-ancestor ul.mega-sub-menu .mega-menu-item.mega-menu-item-has-children > a.mega-menu-link {
                        color: #c5dadc;
                    }
                    .mega-submenu-link-style:after,
                    #{$wrap} #{$menu} > li.mega-menu-item.mega-current-menu-parent ul.mega-sub-menu .mega-menu-item.mega-menu-item-has-children > a.mega-menu-link:after,
                    #{$wrap} #{$menu} > li.mega-menu-item.mega-current-menu-ancestor ul.mega-sub-menu .mega-menu-item.mega-menu-item-has-children > a.mega-menu-link:after {
                        content: "\\\f054" !important;
                        font-family: "FontAwesome" !important;
                        font-size: 7px;
                    }
                    .mega-submenu-link-style:hover,
                    #{$wrap} #{$menu} > li.mega-menu-item.mega-current-menu-parent ul.mega-sub-menu .mega-menu-item.mega-menu-item-has-children > a.mega-menu-link:hover,
                    #{$wrap} #{$menu} > li.mega-menu-item.mega-current-menu-ancestor ul.mega-sub-menu .mega-menu-item.mega-menu-item-has-children > a.mega-menu-link:hover,
                    .mega-submenu-link-style:focus,
                    #{$wrap} #{$menu} > li.mega-menu-item.mega-current-menu-parent ul.mega-sub-menu .mega-menu-item.mega-menu-item-has-children > a.mega-menu-link:focus,
                    #{$wrap} #{$menu} > li.mega-menu-item.mega-current-menu-ancestor ul.mega-sub-menu .mega-menu-item.mega-menu-item-has-children > a.mega-menu-link:focus {
                        color: #fff;
                    }
                    #{$wrap} {
                        position: static;
                        float: left;
                    }
                    #{$wrap} #{$menu} {
                        position: static;
                    }
                    #{$wrap} #{$menu} > li.mega-menu-item > a.mega-menu-link {
                        height: auto;
                        line-height: 30px;
                        font-weight: 500;
                    }
                    #{$wrap} #{$menu} > li.mega-menu-item.mega-current-menu-parent > a.mega-menu-link,
                    #{$wrap} #{$menu} > li.mega-menu-item.mega-current-menu-ancestor > a.mega-menu-link {
                        color: #fff;
                    }
                    #{$wrap} #{$menu} > li.mega-menu-item.mega-current-menu-parent ul.mega-sub-menu .mega-menu-item.mega-current-menu-item > a.mega-menu-link,
                    #{$wrap} #{$menu} > li.mega-menu-item.mega-current-menu-parent ul.mega-sub-menu .mega-menu-item.mega-current-menu-ancestor > a.mega-menu-link,
                    #{$wrap} #{$menu} > li.mega-menu-item.mega-current-menu-ancestor ul.mega-sub-menu .mega-menu-item.mega-current-menu-item > a.mega-menu-link,
                    #{$wrap} #{$menu} > li.mega-menu-item.mega-current-menu-ancestor ul.mega-sub-menu .mega-menu-item.mega-current-menu-ancestor > a.mega-menu-link {
                        color: #fff;
                    }
                    #{$wrap} #{$menu} > li.mega-menu-item ul.mega-sub-menu {
                        width: 100%;
                    }
                    #{$wrap} #{$menu} > li.mega-menu-item.mega-menu-flyout ul.mega-sub-menu {
                        width: auto;
                    }
                    #{$wrap} #{$menu} > li.mega-menu-item.mega-menu-flyout ul.mega-sub-menu ul.mega-sub-menu {
                        background: rgba(7, 23, 64, 0.8);
                    }
                    #{$wrap} #{$menu} > li.mega-menu-item.mega-menu-megamenu ul.mega-sub-menu ul.mega-sub-menu {
                        padding-bottom: 0;
                        padding-top: 0;
                    }
                    #{$wrap} #{$menu} li ul.mega-sub-menu ul.mega-sub-menu {
                        background: transparent;
                    }
                    #{$wrap} #{$menu} > li.mega-menu-megamenu > ul.mega-sub-menu > li.mega-menu-item {
                        padding: 0 15px;
                    }
                    #{$wrap} #{$menu} > li.mega-menu-megamenu > ul.mega-sub-menu > li.mega-menu-item li.mega-menu-item {
                        padding-bottom: 5px;
                    }
                    #{$wrap} #{$menu} > li.mega-menu-megamenu > ul.mega-sub-menu > li.mega-menu-item li.mega-menu-item > a.mega-menu-link {
                        padding: 5px 25px 5px 0;
                        color: #c5dadc;
                    }
                    #{$wrap} #{$menu} > li.mega-menu-flyout ul.mega-sub-menu {
                        background: rgba(7, 23, 64, 0.8);
                    }
                    #{$wrap} #{$menu} > li.mega-menu-flyout ul.mega-sub-menu li.mega-menu-item a.mega-menu-link {
                        line-height: 16px;
                        font-weight: 500 !important;
                    }
                    .mega-menu-link-style-header-02,
                    .header-style-02 #{$wrap} #{$menu} > li.mega-menu-item > a.mega-menu-link,
                    .header-style-02 #{$wrap} #{$menu} li.mega-menu-flyout.mega-menu-item-has-children > a.mega-menu-link,
                    .header-style-02 #{$wrap} #{$menu} li.mega-menu-flyout li.mega-menu-item-has-children > a.mega-menu-link,
                    .header-style-02 #{$wrap} #{$menu} li > li.mega-menu-item-has-children > a.mega-menu-link {
                        color: #748182;
                        font-weight: 600;
                    }
                    .mega-menu-link-style-header-02:after,
                    .header-style-02 #{$wrap} #{$menu} > li.mega-menu-item > a.mega-menu-link:after,
                    .header-style-02 #{$wrap} #{$menu} li.mega-menu-flyout.mega-menu-item-has-children > a.mega-menu-link:after,
                    .header-style-02 #{$wrap} #{$menu} li.mega-menu-flyout li.mega-menu-item-has-children > a.mega-menu-link:after,
                    .header-style-02 #{$wrap} #{$menu} li > li.mega-menu-item-has-children > a.mega-menu-link:after {
                        content: "\\\f078" !important;
                        font-family: "FontAwesome" !important;
                        font-size: 7px;
                    }
                    .mega-menu-link-style-header-02:hover,
                    .header-style-02 #{$wrap} #{$menu} > li.mega-menu-item > a.mega-menu-link:hover,
                    .header-style-02 #{$wrap} #{$menu} li.mega-menu-flyout.mega-menu-item-has-children > a.mega-menu-link:hover,
                    .header-style-02 #{$wrap} #{$menu} li.mega-menu-flyout li.mega-menu-item-has-children > a.mega-menu-link:hover,
                    .header-style-02 #{$wrap} #{$menu} li > li.mega-menu-item-has-children > a.mega-menu-link:hover,
                    .mega-menu-link-style-header-02:focus,
                    .header-style-02 #{$wrap} #{$menu} > li.mega-menu-item > a.mega-menu-link:focus,
                    .header-style-02 #{$wrap} #{$menu} li.mega-menu-flyout.mega-menu-item-has-children > a.mega-menu-link:focus,
                    .header-style-02 #{$wrap} #{$menu} li.mega-menu-flyout li.mega-menu-item-has-children > a.mega-menu-link:focus,
                    .header-style-02 #{$wrap} #{$menu} li > li.mega-menu-item-has-children > a.mega-menu-link:focus {
                        font-weight: 600;
                    }
                    .header-style-02.header-negative-bottom #{$wrap} #{$menu} > li.mega-menu-item > a.mega-menu-link {
                        padding-top: 18px;
                        padding-bottom: 15px;
                        color: #c5dadc;
                        line-height: 20px;
                        padding-left: 0;
                        margin-left: 20px;
                    }
                    .header-style-02.header-negative-bottom #{$wrap} #{$menu} > li.mega-menu-item > a.mega-menu-link:hover,
                    .header-style-02.header-negative-bottom #{$wrap} #{$menu} > li.mega-menu-item > a.mega-menu-link:focus {
                        color: #fff;
                    }
                    .header-style-02.header-negative-bottom #{$wrap} #{$menu} > li.mega-menu-item.mega-current-menu-parent > a.mega-menu-link {
                        color: #fff;
                    }
                    .header-style-02.header-negative-bottom #{$wrap} #{$menu} li.mega-menu-item.mega-toggle-on > a.mega-menu-link {
                        color: #fff;
                    }
                    .header-style-02 #{$wrap} {
                        position: static;
                    }
                    .header-style-02 #{$wrap} #{$menu} > li.mega-menu-item > a.mega-menu-link {
                        height: auto;
                        line-height: 30px;
                    }
                    .header-style-02 #{$wrap} #{$menu} > li.mega-menu-item:first-child > a.mega-menu-link {
                        padding-left: 0;
                    }
                    .header-style-02 #{$wrap} #{$menu} > li.mega-menu-item.mega-menu-flyout ul.mega-sub-menu {
                        width: auto;
                    }
                    .header-style-02 #{$wrap} #{$menu} > li.mega-menu-item.mega-menu-flyout ul.mega-sub-menu ul.mega-sub-menu {
                        background: rgba(3, 13, 38, 0.8);
                    }
                    .header-style-02 #{$wrap} #{$menu} li ul.mega-sub-menu {
                        background: rgba(3, 13, 38, 0.8);
                    }
                    #{$wrap} #{$menu} > li.mega-menu-item {
                    font-weight: 500;
                    }
                    /* =============================================================================
                    SMALL SCREENS - MOBILE PHONE PORTRAIT
                    ============================================================================= */
                    @media only screen and (min-width: 0) and (max-width: 479px) {
                        #{$wrap} {
                            padding-top: 15px;
                            float: none;
                        }
                        #{$wrap} .mega-menu-toggle {
                            display: none;
                        }
                        #{$wrap} .mega-menu-toggle + #{$menu} {
                            display: block;
                        }
                        #{$wrap} #{$menu} > li.mega-menu-megamenu > ul.mega-sub-menu > li.mega-menu-item {
                            width: 100%;
                            margin-bottom: 20px;
                        }
                    }
                    /* =============================================================================
                    MOBILE AND TABLETS
                    ========================================================================= */
                    @media only screen and (max-width: 992px) {
                        #{$wrap} {
                            padding-top: 15px;
                            float: none;
                        }
                        #{$wrap} .mega-menu-toggle {
                            display: none;
                        }
                        #{$wrap} .mega-menu-toggle + #{$menu} {
                            display: block;
                        }
                        #{$wrap} #{$menu} > li.mega-menu-item.cloned {
                        display: block;
                        }
                        #{$wrap} #{$menu} > li.mega-menu-item > a.mega-menu-link {
                            padding: 5px 15px;
                            color: #c5dadc;
                            font-weight: 500;
                        }
                        #{$wrap} #{$menu} > li.mega-menu-item > a.mega-menu-link:hover,
                        #{$wrap} #{$menu} > li.mega-menu-item > a.mega-menu-link:focus {
                            color: #fff;
                            font-weight: 500;
                        }

                        #{$wrap} #{$menu} > li.mega-menu-item ul.mega-sub-menu {
                            padding: 10px 0;
                            width: 100%;
                        }
                        #{$wrap} #{$menu} > li.mega-menu-item.mega-menu-flyout ul.mega-sub-menu {
                            width: 100%;
                        }
                        #{$wrap} #{$menu} > li.mega-menu-megamenu > ul.mega-sub-menu {
                            background: transparent;
                        }
                        #{$wrap} #{$menu} li.mega-menu-flyout.mega-menu-item-has-children > a.mega-menu-link {
                            padding: 5px 15px;
                            color: #c5dadc;
                        }
                        #{$wrap} #{$menu} li.mega-menu-flyout.mega-menu-item-has-children > a.mega-menu-link:hover,
                        #{$wrap} #{$menu} li.mega-menu-flyout.mega-menu-item-has-children > a.mega-menu-link:focus {
                            color: #fff;
                        }
                        #{$wrap} #{$menu} li.mega-menu-flyout li.mega-menu-item-has-children > a.mega-menu-link {
                            padding: 5px 15px;
                            color: #c5dadc;
                        }
                        #{$wrap} #{$menu} li.mega-menu-flyout li.mega-menu-item-has-children > a.mega-menu-link:hover,
                        #{$wrap} #{$menu} li.mega-menu-flyout li.mega-menu-item-has-children > a.mega-menu-link:focus {
                            color: #fff;
                        }
                        #{$wrap} #{$menu} li.mega-menu-flyout ul.mega-sub-menu {
                            background: rgba(7, 23, 64, 0);
                        }
                        #{$wrap} #{$menu} li > li.mega-menu-item-has-children > a.mega-menu-link {
                            padding: 5px 15px;
                            color: #c5dadc;
                        }

                        #{$wrap} #{$menu} li > li.mega-menu-item-has-children > a.mega-menu-link:hover,
                        #{$wrap} #{$menu} li > li.mega-menu-item-has-children > a.mega-menu-link:focus {
                            color: #fff;
                        }
                        #{$wrap} #{$menu} > li.mega-menu-flyout ul.mega-sub-menu li.mega-menu-item a.mega-menu-link {
                            padding: 10px 23px;
                        }
                        #{$wrap} #{$menu} > li.mega-menu-flyout ul.mega-sub-menu li.mega-menu-item ul.mega-sub-menu {
                            width: 100%;
                        }
                        #{$wrap} #{$menu} > li.mega-menu-flyout ul.mega-sub-menu li.mega-menu-item ul.mega-sub-menu a.mega-menu-link {
                            padding-left: 35px;
                        }
                        .header-style-02.header-negative-bottom #{$wrap} #{$menu} > li.mega-menu-item > a.mega-menu-link {
                            margin-left: 0;
                        }
                        .header-style-02 #{$wrap} #{$menu} > li.mega-menu-item > a.mega-menu-link {
                            padding: 5px 15px 5px 0;
                        }
                        .header-style-02 #{$wrap} #{$menu} > li.mega-menu-item ul.mega-sub-menu {
                            padding: 10px 0;
                            width: 100% !important;
                        }
                        .header-style-02 #{$wrap} #{$menu}.mega-menu-flyout ul.mega-sub-menu {
                            width: 100% !important;
                        }
                    }',
        );

        $merged_theme = array_merge($default, $consulting);
        $themes['consulting'] = $merged_theme;

        return $themes;
    }

    /**
     * Filter post types configuration where we register
     * post types that are needed in theme
     *
     * @param array $post_types_config Array with post types
     * @return array Array with configuration
     */
    static function register_custom_post_types($post_types_config) {

        $post_types_config = array(
            'pi_case_studies' => array(
                'cpt' => '1',
                'taxonomy' => '1'
            ),
        );

        return $post_types_config;
    }

    /**
     * Title customization
     *
     * @global int $page
     * @global int $paged
     * @global object $post
     * @param string $title
     * @param string $sep
     * @return string
     */
    static function wp_title($title, $sep) {
        if (is_feed()) {
            return $title;
        }

        global $page, $paged, $post;

        $title_name = get_bloginfo('name', 'display');
        $site_description = get_bloginfo('description', 'display');

        if ($site_description && (is_home() || is_front_page())) {
            $title = "$title_name $sep $site_description";
        } elseif (is_page()) {
            $title = get_the_title($post->ID);
            if (($paged >= 2 || $page >= 2) && !is_404()) {
                $title .= " $sep " . sprintf(esc_html__('Page %s', 'consultingpress'), max($paged, $page));
            }
        } elseif (($paged >= 2 || $page >= 2) && !is_404()) {
            $title = "$title_name $sep " . sprintf(esc_html__('Page %s', 'consultingpress'), max($paged, $page));
        } elseif (is_author()) {
            $author = get_queried_object();
            $title = $author->display_name;
        } elseif (is_search()) {
            $title = 'Search results for: ' . get_search_query() . '';
        }

        return $title;
    }

    /**
     * Overrides the load textdomain functionality when 'volcanno' is the domain in use.  The purpose of
     * this is to allow theme translations to handle the framework's strings.  What this function does is
     * sets the 'volcanno' domain's translations to the theme's.
     *
     * @global type $l10n
     * @param boolean $override
     * @param type $domain
     * @param type $mofile
     * @return boolean
     */
    static function override_load_textdomain($override, $domain, $mofile) {

        if ($domain == 'volcanno') {
            global $l10n;

            $theme_text_domain = Volcanno::get_theme_textdomain();

            // If the theme's textdomain is loaded, use its translations instead.
            if ($theme_text_domain && isset($l10n[$theme_text_domain]))
                $l10n[$domain] = $l10n[$theme_text_domain];

            // Always override.  We only want the theme to handle translations.
            $override = true;
        }

        return $override;
    }

    /**
     * Enable Menu Name
     *
     * @return Menu Name
     */
    static function main_menu_name() {

        return 'primary';
    }

    /**
     * Add class "volcanno-portfolio-not-active" to create/edit page screen if
     * Custom Post Types plugin isn't active
     *
     * @global type $pagenow
     * @global type $typenow
     * @param string $classes Body classes to filter
     * @return string All body classes
     */
    static function admin_body_class($classes) {
        global $pagenow, $typenow;

        if (!VOLCANNO_CPTS && is_admin() && ($pagenow == 'post-new.php' || $pagenow == 'post.php') && $typenow == 'page') {
            $classes .= 'volcanno-cpts-not-active';
        } elseif ($pagenow == 'home.php') {
            $classes .= ' home-page';
        } else {
            $classes .=' inner-page';
        }

        return $classes;
    }

    /**
     * Sets the post excerpt length to 40 words.
     *
     * To override this length in a child theme, remove the filter and add your own
     * function tied to the excerpt_length filter hook.
     *
     * @param int $length
     * @return int
     */
    static function excerpt_length($length) {
        return 40;
    }

    /**
     * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
     *
     * @param array $args
     * @return boolean
     */
    static function page_menu_args($args) {
        $args['show_home'] = true;
        return $args;
    }

    /**
     * Tags widget customizations
     *
     * @param array $args
     * @return array
     */
    static function tag_cloud_args($args) {
        $args['smallest'] = 11;
        $args['largest'] = 11;
        $args['unit'] = "px";
        return $args;
    }

    /**
     * Comment Form styling
     *
     * @param array $fields
     * @return array
     */
    static function get_comment_form($fields) {

        // get current commenter data
        $commenter = wp_get_current_commenter();

        // check if field is required
        $req = get_option('require_name_email');
        $aria_req = ($req ? " aria-required='true' required" : '');

        // change fields style
        $fields['fields']['author'] = '<fieldset class="name-container"><label for="comment-name">' . esc_html__('Name:', 'consultingpress') . ($req ? ' <span class="text-color">*</span>' : '') . '</label>' .
                '<span class="comment-name-container comment-input-container"><input type="text" name="author" class="name" id="comment-name" value="' . esc_attr($commenter['comment_author']) . '" size="22" tabindex="1"' . $aria_req . '/></span></fieldset>';

        $fields['fields']['email'] = '<fieldset class="email-container"><label for="comment-email">' . esc_html__('E-Mail:', 'consultingpress') . ($req ? ' <span class="text-color">*</span>' : '') . '</label>' .
                '<span class="comment-email-container comment-input-container"><input type="email" name="email" class="email" id="comment-email" value="' . esc_attr($commenter['comment_author_email']) . '" size="22" tabindex="2" ' . $aria_req . '/></span></fieldset>';

        $fields['fields']['url'] = '';

        $fields['comment_field'] = '<fieldset class="message"><label for="comment-message">' . esc_html__('Comment:', 'consultingpress') . ($req ? ' <span class="text-color">*</span>' : '') . '</label><span class="comment-message-container comment-input-container"><textarea name="comment" class="comment-text" id="comment-message" rows="8" tabindex="4" aria-required="true" required></textarea></span></fieldset>';

        $fields['comment_notes_before'] = '';
        $fields['comment_notes_after'] = '<p class="reguired-fields">' . esc_html__('Required fields are marked ', 'consultingpress') . '<span class="text-color">*</span></p>';
        $fields['cancel_reply_link'] = ' - ' . esc_html__('Cancel reply', 'consultingpress');
        $fields['title_reply'] = esc_html__('Leave a comment', 'consultingpress');
        $fields['id_submit'] = 'comment-reply';
        $fields['label_submit'] = esc_html__('Post Comment', 'consultingpress');

        return $fields;
    }

    /**
     * Move comment form below other fields
     * 
     * @param  array $fields
     * @return array
     */
    static function move_comment_form_below($fields) {
        $comment_field = $fields['comment'];
        unset($fields['comment']);
        $fields['comment'] = $comment_field;
        return $fields;
    }

    /**
     * Edit Tinymce settings i.e. add custom classes for Tables in Editor
     *
     * @param array $settings Tinymce settings
     * @return array
     */
    static function add_tinymce_tables($settings) {
        $new_styles = array(
            array(
                'title' => 'None',
                'value' => ''
            ),
            array(
                'title' => 'Events',
                'value' => 'events-table',
            )
        );
        $settings['table_class_list'] = json_encode($new_styles);
        return $settings;
    }

    /*
     * Customize Form of widget
     * @param $form object
     * */

    static function volcanno_get_search_form($form) {
        $search_placeholder = Volcanno::return_theme_option('search_placeholder') ? : esc_attr__('Type and hit enter...', 'consultingpress');
        $form = '<form role="search" method="get" id="searchform" class="searchform" action="' . home_url('/') . '" >
                <input type="text" value="' . get_search_query() . '" name="s" id="s" class="a_search" placeholder="' . esc_attr($search_placeholder) . '"/>
                <input type="submit" id="searchsubmit" value="' . esc_attr__('Search', 'consultingpress') . '" class="search-submit"/>
                </form>';

        return $form;
    }

}

Volcanno_Theme_Filters::init();
