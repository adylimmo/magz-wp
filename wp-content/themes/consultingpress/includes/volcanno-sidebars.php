<?php
/* ---------------------------------------------------------
 * Sidebars
 *
 * Class that creates custom sidebar
  ---------------------------------------------------------- */

class Volcanno_Sidebars {

    /**
     * Register sidebar on page
     */
    static function custom_sidebar() {
        $args_sidebar = array(
            'name' => esc_html__( 'Blog Sidebar', 'consultingpress' ),
            'id' => 'volcanno-blog-sidebar',
            'description' => '',
            'class' => 'aside-widgets',
            'before_widget' => '<div id="%1$s" class="aside-widgets %2$s widget">',
            'after_widget' => '</div>',
            'before_title' => '<div class="title"><h3 class="title-sidebar">',
            'after_title' => '</h3></div>'
        );

        register_sidebar( $args_sidebar );
    }

    /**
     * Register sidebar on events custom post type
     */
    static function events_sidebar() {
        $args_sidebar = array(
            'name' => esc_html__( 'Events Sidebar', 'consultingpress' ),
            'id' => 'volcanno-events-sidebar',
            'description' => '',
            'class' => 'aside-widgets',
            'before_widget' => '<div id="%1$s" class="aside-widgets %2$s widget">',
            'after_widget' => '</div>',
            'before_title' => '<div class="title"><h3 class="title-sidebar">',
            'after_title' => '</h3></div>'
        );

        if ( VOLCANNO_EVENTS_MANAGER ) {
            register_sidebar( $args_sidebar );
        }
    }

    /**
     * Shop slider
     */
    static function woocommerce_sidebar() {
        $args_sidebar = array(
            'name' => esc_html__( 'Shop Sidebar', 'consultingpress' ),
            'id' => 'volcanno-shop-sidebar',
            'description' => '',
            'class' => 'aside-widgets',
            'before_widget' => '<div id="%1$s" class="aside-widgets %2$s widget">',
            'after_widget' => '</div>',
            'before_title' => '<div class="title"><h3 class="title-sidebar">',
            'after_title' => '</h3></div>'
        );

        if ( VOLCANNO_WOOCOMMERCE ) {
            register_sidebar( $args_sidebar );
        }
    }

    /**
     * Register sidebar for footer
     */
    static function footer_sidebar() {

        $number_of_sidebar = Volcanno::return_theme_option( 'footer_widget_areas' ) ?: 4;
        
        for ( $i = 1; $i <= $number_of_sidebar; $i++ ) {
            $args_sidebar = array(
                'name' => esc_html__( 'Footer Sidebar ', 'consultingpress' ) . $i,
                'id' => 'volcanno-footer-sidebar-' . ($i + 1),
                'description' => '',
                'class' => 'footer-widget',
                'before_widget' => '<li id="%1$s" class="widget %2$s">',
                'after_widget' => '</li>',
                'before_title' => ' <div class="title"><h3>',
                'after_title' => '</h3></div>'
            );

            register_sidebar( $args_sidebar );
        }
    }

    /**
     * Register sidebar for top footer
     */
    static function footer_top_sidebar() {
        $args_sidebar = array(
            'name' => esc_html__( 'Footer Top sidebar', 'consultingpress' ),
            'id' => 'volcanno-footer-sidebar-top',
            'description' => '',
            'class' => 'footer-widget',
            'before_widget' => '<div id="%1$s" class="aside-widgets %2$s widget">',
            'after_widget' => '</div>',
            'before_title' => '<div class="title"><h3 class="title-sidebar">',
            'after_title' => '</h3></div>'
        );

        if ( Volcanno::return_theme_option('footer_top_widget') ) { register_sidebar( $args_sidebar ); }
    }
    
    static function page_sidebar(){
        global $wpdb;
        
        // create sidebar for pages with left or right sidebar     
        $query = $wpdb->prepare(
                 "SELECT DISTINCT ID FROM {$wpdb->posts}
                    INNER JOIN wp_postmeta
                      ON wp_posts.ID = wp_postmeta.post_id
                  WHERE wp_postmeta.meta_key = %s
                  AND wp_postmeta.meta_value IN (%s, %s)
                  AND wp_posts.ID NOT IN (SELECT post_id FROM wp_postmeta AS meta WHERE meta.meta_key = %s AND meta.meta_value = %s)", 'pg_sidebar', 'left', 'right', 'pg_sidebar_generator', 'existing');
        
        $post_ids = $wpdb->get_results($query);

        foreach ((array) $post_ids as $id) {
            $post = get_post(intval($id->ID));

            // Wpml compatibility
            $translated = get_post_meta( $post->ID, '_icl_lang_duplicate_of' );

            if ( !empty( $translated ) && function_exists('icl_get_languages') ) {

                $langs = icl_get_languages('skip_missing=0&orderby=KEY&order=DIR&link_empty_to=str');
                $language = apply_filters( 'wpml_post_language_details', NULL, $post->ID );
                foreach ($langs as $i => $prop) {
                    if ( $prop['language_code'] == $language['language_code'] ) {
                        $display_name = ' (' . $language['native_name'] . ')';
                        $sidebar_title = $post->post_title . $display_name;
                        $sidebar_id = "volcanno-page-sidebar-" . $post->ID;
                        register_sidebar(array(
                            'name' => $sidebar_title,
                            'id' => $sidebar_id,
                            'class' => 'aside-widgets',
                            'description' => esc_html__('An optional widget area for page ', 'consultingpress') . $sidebar_title,
                            'before_widget' => '<div id="%1$s" class="widget %2$s clearfix">',
                            'after_widget' => "</div>",
                            'before_title' => '<div class="title"><h5 class="title-sidebar">',
                            'after_title' => '</h5></div>'
                        ));
                    }
                }

            } else {
                $sidebar_title = $post->post_title;
                $sidebar_id = "volcanno-page-sidebar-" . $post->ID;
                register_sidebar(array(
                    'name' => $sidebar_title,
                    'id' => $sidebar_id,
                    'class' => 'aside-widgets',
                    'description' => esc_html__('An optional widget area for page ', 'consultingpress') . $sidebar_title,
                    'before_widget' => '<div id="%1$s" class="widget %2$s clearfix">',
                    'after_widget' => "</div>",
                    'before_title' => '<div class="title"><h5 class="title-sidebar">',
                    'after_title' => '</h5></div>'
                ));

            }


        }
        
    }

}
