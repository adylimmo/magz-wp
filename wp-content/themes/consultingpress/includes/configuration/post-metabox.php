<?php

/*
 * ---------------------------------------------------------
 * MetaBox
 *
 * Custom fields registration
 * ----------------------------------------------------------
 */

class Volcanno_Meta_Box {

    function __construct() {
        self::init();
    }

    static function init() {
        add_filter( 'rwmb_meta_boxes', 'Volcanno_Meta_Box::register_meta_boxes' );
    }

    /**
     * Overide mehod if same function in child theme exist
     * @param  string
     * @return  function
     */
    static function child_override( $function, $params = '' ) {
        
        $class = __CLASS__ . '_Child';

        if ( class_exists( $class ) && is_callable( array($class, $function) ) ) {
            return call_user_func_array(array($class, $function), $params);
        }

    }


    /* --------------------------------------------------------------------
     * MetaBox tabs visibility based on Template value
     * -------------------------------------------------------------------- */
    static function metabox_tabs_visibility() {
        if ( self::child_override(__FUNCTION__, func_get_args() ) ) return self::child_override(__FUNCTION__, func_get_args() );

        /**
         * key => value
         * tab_name => template_name
         * 
         * Description:
         * tab_name - MetaBox tab name
         * template_name - array (or string if single value) of page template 
         * FILE names (not template name) that will control visibility of current tab  
         * 
         * Note:
         * WordPress create list of templates based on template file name, not template name
         */
        $tabs = array(
            'page_title' => '',
            'blog_options' => 'blog.php',
            'case_studies' => 'case-studies.php'
        );

        return $tabs;
    }

    /* --------------------------------------------------------------------
     * MetaBox registration for pages, posts and Custom Post Types
     * -------------------------------------------------------------------- */

    static function register_meta_boxes( $meta_boxes ) {
        if ( self::child_override(__FUNCTION__, func_get_args() ) ) return self::child_override(__FUNCTION__, func_get_args() );

        global $volcanno_theme_config;

        // Using GET becase can't get $post on init
        $page_id = isset( $_GET['post'] ) ? $_GET['post'] : '';

        // Get avaliable pages
        $all_avaliable_pages = get_pages( 'sort_column=post_parent,menu_order' );
        $pages [''] = 'Select a page';
        foreach ( $all_avaliable_pages as $page ) {
            $pages [$page->ID] = $page->post_title;
        }

        // get all registered sidebars in system
        $all_registered_sidebars = Volcanno_Helper::get_registered_sidebars();

        // Define a meta box for Pages
        $prefix = 'pg_';

        $meta_boxes [] = array(
            'title' => esc_html__( 'Page Options', 'consultingpress' ),
            'pages' => array(
                'page'
            ),
            // Works with Meta Box Tabs plugin
            'tabs' => array(
                'page_title' => esc_html__( 'Page title', 'consultingpress' ),
                'blog_options' => esc_html__( 'Blog', 'consultingpress' ),
                'sidebar' => esc_html__( 'Sidebar', 'consultingpress' ),
                'case_studies' => esc_html__( 'Case Studies', 'consultingpress' )
            ),
            'tab_wrapper' => true,
            'tab_style' => 'default',
            'fields' => array(

                /*
                 * Page title tab
                 */
                // Disable page title
                array(
                    'name' => esc_html__( 'Disable page title', 'consultingpress' ),
                    'id' => "{$prefix}title_visibility",
                    'desc' => esc_html__( 'Check this to Disable Page Title.', 'consultingpress' ),
                    'type' => 'checkbox',
                    'std' => '',
                    'tab' => 'page_title'
                ),
                array(
                    'name' => esc_html__( 'Title', 'consultingpress' ),
                    'id' => "{$prefix}nice_title",
                    'desc' => esc_html__( 'Here you can overide default page title.', 'consultingpress' ),
                    'type' => 'text',
                    'std' => '',
                    'tab' => 'page_title',
                    'visible' => array( 'title_visibility', "=", '0' )
                ),
                array(
                    'name' => esc_html__( 'Extra title', 'consultingpress' ),
                    'id' => "{$prefix}extra_title",
                    'desc' => esc_html__( 'Extra title will appear before page title. Excluding managment header style', 'consultingpress' ),
                    'type' => 'text',
                    'std' => '',
                    'tab' => 'page_title',
                    'visible' => array( 'title_visibility', "=", '0' )                
                ),
                // Image Upload
                array(
                    'name' => esc_html__( 'Background image.', 'consultingpress' ),
                    'desc' => esc_html__( 'Recomended minimum image size: 1920 x 480', 'consultingpress' ),
                    'id' => "{$prefix}title_image",
                    'type' => 'image_advanced',
                    'max_file_uploads' => 1,
                    'tab' => 'page_title',
                    'visible' => array( 'title_visibility', "=", '0' )
                ),
                array(
                    'name' => esc_html__( 'Header type', 'consultingpress' ),
                    'desc' => "",
                    'id' => "{$prefix}page_custom_header_type",
                    'type' => 'select',
                    'std' => '0',
                    'options' => array( 
                        '0' => esc_html__('Inherit from theme options', 'consultingpress'), 
                        'finance' => esc_html__('Finance', 'consultingpress'),
                        'tourism' => esc_html__('Tourism', 'consultingpress'),
                        'it_security' => esc_html__('It Security', 'consultingpress'),
                        'management' => esc_html__('Management', 'consultingpress')
                    ),
                    'tab' => 'page_title',
                ),

                /*
                 * Sidebar options tab
                 */
                
                // Sidebar
                array(
                    'name' => esc_html__( 'Sidebar placement', 'consultingpress' ),
                    'desc' => esc_html__( 'Page can have left or right sidebar.', 'consultingpress' ),
                    'id' => "{$prefix}sidebar",
                    'type' => 'image_select',
                    'std' => 'fullwidth',
                    'options' => array(
                        'fullwidth' => VOLCANNO_TEMPLATEURL . '/core/assets/images/fullwidth.png',
                        'left' => VOLCANNO_TEMPLATEURL . '/core/assets/images/left.png',
                        'right' => VOLCANNO_TEMPLATEURL . '/core/assets/images/right.png'
                    ),
                    'tab' => 'sidebar',
                ),
                // Sidebar generator
                array(
                    'name' => esc_html__( 'Sidebar generator', 'consultingpress' ),
                    'desc' => esc_html__( 'Choose between new sidebar generation or selecting sidebar. We recommend Custom Sidebars plugin in case you want to generate new sidebar and use it for set of pages.', 'consultingpress' ),
                    'id' => "{$prefix}sidebar_generator",
                    'type' => 'select',
                    'std' => 'existing',
                    'options' => array( 'new' => esc_html__('Generate new sidebar', 'consultingpress'), 'existing' => esc_html__('Select existing sidebar', 'consultingpress') ),
                    'tab' => 'sidebar',
                    'visible' => array( 'pg_sidebar', "in", array( 'left', 'right' ) )
                ),
                // Sidebar name
                array(
                    'name' => esc_html__( 'Sidebar name', 'consultingpress' ),
                    'desc' => esc_html__( 'Select sidebar you want to use on this page. If not set, Main Sidebar is used.', 'consultingpress' ),
                    'id' => "{$prefix}sidebar_name",
                    'type' => 'select',
                    'std' => '',
                    'options' => $all_registered_sidebars,
                    'tab' => 'sidebar',
                    'visible' => array( 'sidebar_generator', "=", 'existing' )
                ),

                /*
                 * Blog options tab
                 */
                
                // Blog style
                array(
                    'name' => esc_html__( 'Blog style', 'consultingpress' ),
                    'desc' => esc_html__( 'Choose between grid and list view.', 'consultingpress' ),
                    'id' => "{$prefix}blog_options_style",
                    'type' => 'select',
                    'std' => 'existing',
                    'options' => array( 'blog-grid' => esc_html__('Grid', 'consultingpress'), 'blog-list' => esc_html__('List', 'consultingpress') ),
                    'tab' => 'blog_options',
                ),
                // Display posts
                array(
                    'name' => esc_html__( 'Display posts', 'consultingpress' ),
                    'desc' => esc_html__( "Selected categories can't be used in more than one page template.", 'consultingpress' ),
                    'id' => "{$prefix}blog_display_posts",
                    'type' => 'select',
                    'tab' => 'blog_options',
                    'std' => 'default',
                    'options' => array(
                        'default' => esc_html__('All available categories', 'consultingpress'),
                        'category' => esc_html__('From category', 'consultingpress')
                    ),
                ),
                // Display posts from category
                array(
                    'name' => esc_html__( 'Category', 'consultingpress' ),
                    'desc' => esc_html__( 'Select categories for blog posts', 'consultingpress' ),
                    'id' => "{$prefix}blog_display_from_category",
                    'type' => 'select',
                    'tab' => 'blog_options',
                    'std' => '',
                    'multiple' => true,
                    'select_all_none' => true,
                    'options' => Volcanno_Partials::all_page_blog_categories($page_id),
                    'visible' => array( 'blog_display_posts', "=", 'category' )
                ),
                // Pagination
                array(
                    'name' => esc_html__( 'Pagination', 'consultingpress' ),
                    'id' => "{$prefix}blog_pagination",
                    'desc' => esc_html__( 'Number of items per page.', 'consultingpress' ),
                    'type' => 'slider',
                    'std' => '4',
                    'tab' => 'blog_options'
                ),

                /*
                 * Case studies options tab
                 */
                
                // Case Studies style
                array(
                    'name' => esc_html__( 'Case Studies style', 'consultingpress' ),
                    'desc' => esc_html__( 'Choose between grid and list view.', 'consultingpress' ),
                    'id' => "{$prefix}case_studies_options_style",
                    'type' => 'select',
                    'std' => 'existing',
                    'options' => array( 'cases-grid' => esc_html__('Grid', 'consultingpress'), 'cases-list' => esc_html__('List', 'consultingpress') ),
                    'tab' => 'case_studies',
                ),
                // Enable category filter
                array(
                    'name' => esc_html__( 'Category filter', 'consultingpress' ),
                    'id' => "{$prefix}case_studies_category_filter",
                    'desc' => esc_html__( 'Check this to Enable category filter.', 'consultingpress' ),
                    'type' => 'checkbox',
                    'std' => '',
                    'tab' => 'case_studies',
                ),
                // Display posts
                array(
                    'name' => esc_html__( 'Display posts', 'consultingpress' ),
                    'desc' => esc_html__( "Selected categories can't be used in more than one page template.", 'consultingpress' ),
                    'id' => "{$prefix}case_studies_display_posts",
                    'type' => 'select',
                    'tab' => 'case_studies',
                    'std' => 'default',
                    'options' => array(
                        'default' => esc_html__('All available categories', 'consultingpress'),
                        'category' => esc_html__('From category', 'consultingpress')
                    ),
                ),
                // Display posts from category
               array(
                    'name' => esc_html__( 'Category', 'consultingpress' ),
                    'desc' => esc_html__( 'Select categories for case studies', 'consultingpress' ),
                    'id' => "{$prefix}case_studies_display_from_category",
                    'type' => 'select',
                    'tab' => 'case_studies',
                    'std' => '',
                    'multiple' => true,
                    'select_all_none' => true,
                    'options' => Volcanno_Partials::volcanno_case_studies_categories($page_id),
                    'visible' => array( 'case_studies_display_posts', "=", 'category' )
                ),
                // Pagination
                array(
                    'name' => esc_html__( 'Pagination', 'consultingpress' ),
                    'id' => "{$prefix}case_studies_pagination",
                    'desc' => esc_html__( 'Number of items per page.', 'consultingpress' ),
                    'type' => 'slider',
                    'std' => '4',
                    'tab' => 'case_studies'
                ),
            )
        );

        // Define a meta box for Blog Posts
        $prefix = 'pt_';
        $meta_boxes [] = array(
            'title' => esc_html__( 'Single Post Options', 'consultingpress' ),
            'post_types' => array('post', 'pi_case_studies', 'event'),
            'fields' => array(
                // Sidebar placement
                array(
                    'name' => esc_html__( 'Sidebar', 'consultingpress' ),
                    'desc' => esc_html__( "Here you can select if you want custom sidebar position.", 'consultingpress' ),
                    'id' => "{$prefix}custom_sidebar_placement",
                    'type' => 'select',
                    'options' => array(
                        'inherit' => esc_html__('Inherit', 'consultingpress'),
                        'custom' => esc_html__('Custom', 'consultingpress'),
                    ),
                    'default' => 'inherit',
                    'visible' => array('post_type', 'in', array('pi_case_studies','event'))
                ),
                array(
                    'name' => esc_html__( 'Sidebar placement', 'consultingpress' ),
                    'desc' => esc_html__( 'Here you can define sidebar placement for single case study.', 'consultingpress' ),
                    'id' => "{$prefix}sidebar",
                    'type' => 'image_select',
                    'std' => 'fullwidth',
                    'options' => array(
                        'fullwidth' => VOLCANNO_TEMPLATEURL . '/core/assets/images/fullwidth.png',
                        'left' => VOLCANNO_TEMPLATEURL . '/core/assets/images/left.png',
                        'right' => VOLCANNO_TEMPLATEURL . '/core/assets/images/right.png'
                    ),
                    'visible' => array('custom_sidebar_placement', 'custom')
                ),
                // Background image
                array(
                    'name' => esc_html__( 'Page title background image', 'consultingpress' ),
                    'desc' => esc_html__( 'Recomended minimum image size: 1920 x 480', 'consultingpress' ),
                    'id' => "{$prefix}title_image",
                    'type' => 'image_advanced',
                    'max_file_uploads' => 1,
                ),
                // Show featured image on single case study
                array(
                    'name' => esc_html__( 'Featured image on single case study', 'consultingpress' ),
                    'id' => "{$prefix}single_featured_image",
                    'desc' => esc_html__( 'Check this to Disable featured image on single case study.', 'consultingpress' ),
                    'type' => 'checkbox',
                    'std' => '',
                    'visible' => array('post_type', 'pi_case_studies')
                ),
                // Title section background color
                array(
                    'name' => esc_html__( 'Video url', 'consultingpress' ),
                    'desc' => esc_html__( 'Enter video url for post format.', 'consultingpress' ),
                    'id' => "{$prefix}post_format_video",
                    'type' => 'url',
                    'visible' => array('post_format', 'video')
                ),
            )
        );

        return $meta_boxes;
    }
}


Volcanno_Meta_Box::init();
