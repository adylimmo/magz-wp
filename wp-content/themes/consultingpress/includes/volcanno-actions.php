<?php
/* ---------------------------------------------------------
 * Actions
 *
 * Class for registering actions
  ---------------------------------------------------------- */

class Volcanno_Theme_Actions {

    static $hooks = array();

    /**
     * Setup all theme actions
     */
    static function init() {

        do_action( 'volcanno_before_actions_setup' );

        include_once VOLCANNO_THEME_DIR . 'includes/volcanno-helpers.php';
        include_once VOLCANNO_THEME_DIR . 'includes/volcanno-enqueue.php';
        include_once VOLCANNO_THEME_DIR . 'includes/volcanno-sidebars.php';

        /**
         * Array of action hooks
         * 
         * Usage:
         * 
         * 'action_name' => array(
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
            'after_setup_theme' => array(
                'Volcanno_Theme_Actions::content_width',
                'Volcanno_Theme_Actions::theme_support',
                'Volcanno_Theme_Actions::register_nav_menu',
                'Volcanno_Theme_Actions::theme_textdomain',
            //'Volcanno_Theme_Actions::register_acf_fields',
            ),
            'wp_enqueue_scripts' => array(
                'Volcanno_Enqueue::load_css',
                'Volcanno_Enqueue::load_js',
                'Volcanno_Enqueue::localize_script',
                'Volcanno_Enqueue::register_css_js',
                'Volcanno_Enqueue::google_fonts',
            ),
            'admin_enqueue_scripts' => array(
                'Volcanno_Enqueue::load_admin_css_js',
            ),
            'widgets_init' => array(
                'Volcanno_Sidebars::custom_sidebar',
                'Volcanno_Sidebars::events_sidebar',
                'Volcanno_Sidebars::woocommerce_sidebar',
                'Volcanno_Sidebars::footer_sidebar',
                'Volcanno_Sidebars::footer_top_sidebar',
                'Volcanno_Sidebars::page_sidebar',
            ),
            'pre_get_posts' => array(
                'Volcanno_Theme_Actions::set_global_query',
                'Volcanno_Theme_Actions::case_studies_set_posts_per_page'
            ),
            'save_post' => array(
                'Volcanno_Theme_Actions::save_blog_category_parent',
                'Volcanno_Theme_Actions::save_case_studies_category_parent'
            ),
            'manage_posts_custom_column' => array(
                'Volcanno_Theme_Actions::admin_display_posts_parent' => array( 10, 2 )
            ),
        );

        // register actions
        Volcanno::register_hooks( static::$hooks, 'action' );
    }

    /**
     * Enable all required features for proper theme work
     */
    static function theme_support() {

        // Add default posts and comments RSS feed links to <head>.
        add_theme_support( 'automatic-feed-links' );

        // title tag
        add_theme_support( 'title-tag' );

        // WooCommerce
        add_theme_support( 'woocommerce' );

        // Add support for a variety of post formats
        add_theme_support( 'post-formats', array( 'video' ) );

        // This theme uses Featured Images (also known as post thumbnails) for per-post/per-page Custom Header images
        add_theme_support( 'post-thumbnails', array( 'post', 'pi_case_studies' ) );
    }

    /**
     * Register all theme menus
     */
    static function register_nav_menu() {
        // Registering Main menu
        register_nav_menu( 'primary', 'Primary Menu' );
    }

    /**
     * Make theme available for translation
     */
    static function theme_textdomain() {

        $theme_name = sanitize_title( VOLCANNO_THEME_NAME );

        // Make theme available for translation
        load_theme_textdomain( $theme_name, VOLCANNO_THEME_DIR . 'languages' );
        $locale = get_locale();
        $locale_file = VOLCANNO_THEME_DIR . "languages/$locale.php";
        if ( is_readable( $locale_file ) )
            require_once $locale_file;
    }

    /**
     * Set the content width based on the theme's design and stylesheet.
     */
    static function content_width() {
        if ( !isset( $content_width ) )
            $content_width = 1140;
    }


    /**
     * Display column Parent in admin post list
     * 
     * @param  string $column  
     * @param  int $post_id 
     * @return void
     */
    static function admin_display_posts_parent( $column, $post_id ) {
        if ($column == 'parent') {
            $post_type = get_post_type( $post_id );
            // If is Case Studies post type
            if ( $post_type == 'pi_case_studies' ) {
                $main_category = get_the_terms( get_the_id(), 'case-studies-category');
                $main_category_id = !empty( $main_category[0]->term_id ) ? $main_category[0]->term_id : '';
                $parent_id = get_term_meta( $main_category_id, 'parent_case_studies_page', true ) ? : '';
            // If is post
            } else {
                $main_category = get_the_category();
                $main_category_id = !empty( $main_category[0]->cat_ID ) ? $main_category[0]->cat_ID : '';
                $parent_id = get_term_meta( $main_category_id, 'parent_blog_page', true ) ? : '';
            }

            if ( !empty( $parent_id ) ) {
                echo '<span><a href="' . esc_url( get_permalink( $parent_id ) ) . '">' . get_the_title( $parent_id ) . '</a></span>';
            } else {
                echo esc_html__( '—', 'consultingpress' );
            }
        }
    }

    /**
     * Set category parent blog page ID
     * @param  $post_ID 
     * @return void      
     */
    static function save_blog_category_parent( $post_ID ) {

        // Verify this is not an auto save routine. 
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
            return;
        // Check nonces and permissions here
        if ( !current_user_can( 'edit_page', $post_ID ) )
            return;

        // Check post type
        $post_type = get_post_type( $post_ID );
        // If this isn't a page post type
        if ( "page" != $post_type )
            return;

        // Get all unassigned or from category
        $display_posts_from = get_post_meta( $post_ID, 'pg_blog_display_posts', true );
        // Get selected categories
        $post_meta_categories = get_post_meta( $post_ID, 'pg_blog_display_from_category' );
        // Get all categories
        $all_categories = get_categories();
        // Get current page template
        $page_template = isset( $_REQUEST['page_template'] ) ? $_REQUEST['page_template'] : '';

        // Delete parent blog page meta where is equal to post id
        foreach ( $all_categories as $category => $cat ) {
            $cat_id = $cat->term_id;
            $parent_blog_page = get_term_meta( $cat_id, 'parent_blog_page', true );
            if ( $post_ID == $parent_blog_page ) {
                delete_term_meta( $cat_id, 'parent_blog_page' );
            }
        }

        // Check if is blog template
        if ( esc_attr( $page_template ) == 'blog.php' ) {

            if ( $display_posts_from == 'category' ) {
                // Set parent blog page meta id for selected categories
                foreach ( $post_meta_categories as $category ) {
                    $cat_id = get_category_by_slug( $category );
                    $cat_id = $cat_id->term_id;
                    $cat_parrent = get_term_meta( $cat_id, 'parent_blog_page' );
                    update_term_meta( $cat_id, 'parent_blog_page', $post_ID );
                }
            } else if ( $display_posts_from == 'default' ) {
                // Set parent blog page meta id for all available categories
                $all_avalible_categories = '';
                foreach ( $all_categories as $category => $cat ) {
                    $cat_id = $cat->term_id;
                    $parent_blog_page = get_term_meta( $cat_id, 'parent_blog_page', true );
                    if ( empty( $parent_blog_page ) ) {
                        update_term_meta( $cat_id, 'parent_blog_page', $post_ID );
                        $comma = $category > 0 ? ',' : '';
                        $all_avalible_categories .= $comma . $cat->slug;
                    }
                }
                update_post_meta( $post_ID, 'all_avalible_categories', $all_avalible_categories );
            }
        }
    }

    /**
     * Set category parent case studies page ID
     * @param  $post_ID 
     * @return void      
     */
    static function save_case_studies_category_parent( $post_ID ) {

        // Verify this is not an auto save routine. 
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
            return;
        // Check nonces and permissions here
        if ( !current_user_can( 'edit_page', $post_ID ) )
            return;

        // Check post type
        $post_type = get_post_type( $post_ID );
        
        // If this isn't a page post type
        if ( "page" != $post_type )
            return;

        // Get all unassigned or from category
        $display_posts_from = get_post_meta( $post_ID, 'pg_case_studies_display_posts', true );
        // Get selected categories
        $post_meta_categories = get_post_meta( $post_ID, 'pg_case_studies_display_from_category' );
        // Get all categories
        $all_categories = get_terms( 'case-studies-category' );
        // Get current page template
        $page_template = isset( $_REQUEST['page_template'] ) ? $_REQUEST['page_template'] : '';

        // Delete parent blog page meta where is equal to post id
        foreach ( $all_categories as $category => $cat ) {
            if ( is_object( $cat ) ) {
                $cat_id = $cat->term_id;
                $parent_blog_page = get_term_meta( $cat_id, 'parent_case_studies_page', true );
                if ( $post_ID == $parent_blog_page ) {
                    delete_term_meta( $cat_id, 'parent_case_studies_page' );
                }
            }
        }

        // Check if is blog template
        if ( esc_attr( $page_template ) == 'case-studies.php' ) {

            if ( $display_posts_from == 'category' ) {
                // Set parent blog page meta id for selected categories
                foreach ( $post_meta_categories as $category ) {
                    $cat_id = get_term_by( 'slug', $category, 'case-studies-category' );
                    $cat_id = $cat_id->term_id;
                    $cat_parrent = get_term_meta( $cat_id, 'parent_case_studies_page' );
                    update_term_meta( $cat_id, 'parent_case_studies_page', $post_ID );
                }
            } else if ( $display_posts_from == 'default' ) {
                // Set parent blog page meta id for all available categories
                $all_avalible_categories = '';
                foreach ( $all_categories as $category => $cat ) {
                    $cat_id = $cat->term_id;
                    $parent_blog_page = get_term_meta( $cat_id, 'parent_case_studies_page', true );
                    if ( empty( $parent_blog_page ) ) {
                        update_term_meta( $cat_id, 'parent_case_studies_page', $post_ID );
                        $comma = $category > 0 ? ',' : '';
                        $all_avalible_categories .= $comma . $cat->slug;
                    }
                }
                update_post_meta( $post_ID, 'all_avalible_categories', $all_avalible_categories );
            }
        }
    }

    /**
     * Portfolio taxonomy archive.
     * Set posts_per_page variable based on value from Theme options.
     *  
     * @param object $query
     * @return object
     */
    static function case_studies_set_posts_per_page( $query ) {

        if ( !is_admin() && $query->is_tax() && ( $query->is_archive() ) ) {
            $taxonomy_vars = $query->query_vars;

            // check that taxonomy is set
            if ( isset( $taxonomy_vars['case-studies-category'] ) ) {

                $posts_per_page = Volcanno::return_theme_option('default_case_studies_pagination');

                if ( !empty( $posts_per_page ) ) {
                    $query->set( 'posts_per_page', $posts_per_page );
                }

                $query->set( 'post_type', "pi_case_studies" );
            }
        }

        return $query;
    }

    /**
     * Set global query parameters
     * 
     * @param $query 
     */
    static function set_global_query( $query ) {

        if ( is_admin() || !$query->is_main_query() ) {
            return;
        }

        // If is category
        if ( is_category() ) {
            // Get current category id
            $category = get_query_var( 'cat' );
            // Get parent id
            $parent_id = get_term_meta( $category, 'parent_blog_page', true );

            // Check if parent exist
            if ( !empty( $parent_id ) ) {
                $posts_per_page = rwmb_meta( 'pg_blog_pagination', '', $parent_id ) ? : Volcanno::return_theme_option( 'default_blog_pagination' );
            } else {
                // Get default pagination value from theme options if exist else get global wordpress pagination
                $posts_per_page = Volcanno::return_theme_option( 'default_blog_pagination' ) ? : get_query_var( 'posts_per_page' );
            }
            // Set pagination if parent ID exist
            $query->set( 'posts_per_page', $posts_per_page );
        }

        if ( is_home() || is_archive() || is_day() || is_date() || is_month() || is_author() || is_feed() || is_tag() || is_time() || is_year() ) {

            $posts_per_page = Volcanno::return_theme_option( 'default_blog_pagination' ) ? : get_query_var( 'posts_per_page' );
            // Set pagination if parent ID exist
            $query->set( 'posts_per_page', $posts_per_page );
        }
    }

}

Volcanno_Theme_Actions::init();
