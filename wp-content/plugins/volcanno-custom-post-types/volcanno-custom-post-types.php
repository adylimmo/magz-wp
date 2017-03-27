<?php

/* -----------------------------------------------------------------------------------

  Plugin Name: Custom Post Types
  Plugin URI: http://www.pixel-industry.com
  Description: A plugin that registers custom post types.
  Version: 1.1.5
  Author: Pixel Industry
  Author URI: http://www.pixel-industry.com

  ----------------------------------------------------------------------------------- */

if ( !defined( 'VCPT_PLUGIN_URL' ) )
    define( 'VCPT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

if ( !function_exists( 'vcpt_load_admin_styles' ) ) {

    function vcpt_load_admin_styles() {
        $current_screen = get_current_screen();

        if ( $current_screen->id == 'pi_portfolio' || $current_screen->id == 'pi_gallery' || $current_screen->id == 'page' ) {
            wp_enqueue_style( 'portfolio-formats', plugins_url( '', __FILE__ ) . '/css/volcanno-custom-post-types.css', array(), '1.0', 'screen' );
        }
    }

}

add_action( 'admin_enqueue_scripts', 'vcpt_load_admin_styles' );

if ( !function_exists( 'vcpt_cpt_config' ) ) {

    function vcpt_cpt_config() {
        global $post_types_config;

        // configuration
        $post_types_config = array(
            'pi_portfolio' => array(
                'cpt' => '0',
                'taxonomy' => '0'
            ),
            'pi_gallery' => array(
                'cpt' => '0',
                'taxonomy' => '0'
            ),
            'pi_vehicles' => array(
                'cpt' => '0',
                'taxonomy' => '0'
            ),
            'pi_catering' => array(
                'cpt' => '0',
                'taxonomy' => '0'
            ),
            'pi_dish_drinks' => array(
                'cpt' => '0',
                'taxonomy' => '0'
            ),
            'pi_case_studies' => array(
                'cpt' => '0',
                'taxonomy' => '0'
            ),
            'pi_photography' => array(
                'cpt' => '0',
                'taxonomy' => '0'
            )
        );

        $post_types_config = array_merge( $post_types_config, apply_filters( 'vcpt_register_custom_post_types', $post_types_config ) );
    }

}
add_action( 'after_setup_theme', 'vcpt_cpt_config', 9 );

/* Function that returns active Custom Post Types */

function vcpt_get_cpts() {
    global $post_types_config;

    return $post_types_config;
}

/* ------------------------------------------------------------------
 * Portfolio post type
  ------------------------------------------------------------------- */
if ( !function_exists( 'vcpt_cpt_create_post_type_portfolio' ) ) {

    function vcpt_cpt_create_post_type_portfolio() {
        global $post_types_config;

        if ( $post_types_config['pi_portfolio']['cpt'] != '1' )
            return;

        $labels = array(
            'name' => __( 'Portfolio', 'pi_framework' ),
            'singular_name' => __( 'Portfolio', 'pi_framework' ),
            'add_new' => _x( 'Add New', 'portfolio', 'pi_framework' ),
            'add_new_item' => __( 'Add New Item', 'pi_framework' ),
            'edit_item' => __( 'Edit Item', 'pi_framework' ),
            'new_item' => __( 'New Item', 'pi_framework' ),
            'view_item' => __( 'View Item', 'pi_framework' ),
            'search_items' => __( 'Search Item', 'pi_framework' ),
            'not_found' => __( 'No Portoflio images found', 'pi_framework' ),
            'not_found_in_trash' => __( 'No portfolio images found in Trash', 'pi_framework' ),
            'parent_item_colon' => ''
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'rewrite' => array( 'slug' => 'portfolio-item', 'with_front' => true ),
            'show_ui' => true,
            'query_var' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'menu_position' => '20',
            'menu_icon' => VCPT_PLUGIN_URL . '/imgs/portfolio.png',
            'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'excerpt', 'post-formats' )
        );

        register_post_type( 'pi_portfolio', $args );
    }

}
add_action( 'init', 'vcpt_cpt_create_post_type_portfolio' );

/* Create taxonomy Portfolio Category */
if ( !function_exists( 'vcpt_cpt_create_portfolio_taxonomies' ) ) {

    function vcpt_cpt_create_portfolio_taxonomies() {
        global $post_types_config;

        if ( $post_types_config['pi_portfolio']['cpt'] != '1' && $post_types_config['pi_portfolio']['taxonomy'] != '1' )
            return;

        register_taxonomy( "portfolio-category", array( "pi_portfolio" ), array( "hierarchical" => true, "label" => __( "Categories", 'pi_framework' ), "singular_label" => __( "Portfolio Category", 'pi_framework' ), "rewrite" => array( 'slug' => 'portfolio-category', 'hierarchical' => true ) ) );
        if ( !term_exists( 'default', 'portfolio-category' ) ) {
            $parent_term = term_exists( 'default', 'portfolio-category' ); // array is returned if taxonomy is given
            $parent_term_id = $parent_term['term_id']; // get numeric term id
            wp_insert_term(
                    __( 'Default', 'pi_framework' ), // the term 
                    'portfolio-category', // the taxonomy
                    array(
                'description' => __( 'Default portfolio category.', 'pi_framework' ),
                'slug' => 'default',
                'parent' => $parent_term_id
                    )
            );
        }
    }

}
add_action( 'init', 'vcpt_cpt_create_portfolio_taxonomies' );

/* Set default portfolio category when publishing portfolio post */
if ( !function_exists( 'vcpt_cpt_set_default_object_terms' ) ) {

    function vcpt_cpt_set_default_object_terms( $post_id, $post ) {
        global $post_types_config;

        if ( $post_types_config['pi_portfolio']['cpt'] != '1' && $post_types_config['pi_portfolio']['taxonomy'] != '1' )
            return;

        if ( $post->post_status === 'publish' && $post->post_type == "pi_portfolio" ) {
            $defaults = array(
                'portfolio-category' => array( 'default' )
            );
            $taxonomies = get_object_taxonomies( $post->post_type );
            foreach ( ( array ) $taxonomies as $taxonomy ) {
                $terms = wp_get_post_terms( $post_id, $taxonomy );
                if ( empty( $terms ) && array_key_exists( $taxonomy, $defaults ) ) {
                    wp_set_object_terms( $post_id, $defaults[$taxonomy], $taxonomy );
                }
            }
        }
    }

}
add_action( 'save_post', 'vcpt_cpt_set_default_object_terms', 100, 2 );

/* Show Portfolio image in list of Portfolios */
if ( !function_exists( 'vcpt_cpt_columns_head_only_pi_portfolio' ) ) {

// Create new column
    function vcpt_cpt_columns_head_only_pi_portfolio( $defaults ) {
        global $post_types_config;

        if ( $post_types_config['pi_portfolio']['cpt'] != '1' )
            return;

        $defaults['portfolio_image'] = 'Image';

        return $defaults;
    }

}

if ( !function_exists( 'vcpt_cpt_columns_content_only_pi_portfolio' ) ) {

// show image in column
    function vcpt_cpt_columns_content_only_pi_portfolio( $column_name, $post_ID ) {
        global $post_types_config;

        if ( $post_types_config['pi_portfolio']['cpt'] != '1' )
            return;

        if ( $column_name == 'portfolio_image' ) {
            $images = get_post_custom_values( 'pf_image', $post_ID );
            if ( !empty( $images ) ) {
                $featured_image = wp_get_attachment_image_src( $images[0], 'thumbnail' );
            } else {
                $images = get_post_custom_values( 'volcannoimage', $post_ID );
                $featured_image = wp_get_attachment_image_src( $images[0], 'thumbnail' );
            }

            if ( $featured_image ) {
                // image found
                echo '<img src="' . $featured_image[0] . '" alt="Image"/>';
            } else {
                // no image
                $default_image = VCPT_PLUGIN_URL . '/imgs/placeholder.jpg';
                echo "<img src='{$default_image}' width='150' alt='placeholder image'/>";
            }
        }
    }

}
// Portfolio post type filter
add_filter( 'manage_pi_portfolio_posts_columns', 'vcpt_cpt_columns_head_only_pi_portfolio', 10 );
add_action( 'manage_pi_portfolio_posts_custom_column', 'vcpt_cpt_columns_content_only_pi_portfolio', 10, 2 );

/* ------------------------------------------------------------------
 * Gallery post type
  ------------------------------------------------------------------- */
if ( !function_exists( 'vcpt_cpt_create_post_type_gallery' ) ) {

    function vcpt_cpt_create_post_type_gallery() {
        global $post_types_config;

        if ( $post_types_config['pi_gallery']['cpt'] != '1' )
            return;

        $labels = array(
            'name' => __( 'Gallery', 'pi_framework' ),
            'singular_name' => __( 'Gallery', 'pi_framework' ),
            'add_new' => _x( 'Add New', 'gallery', 'pi_framework' ),
            'add_new_item' => __( 'Add New Item', 'pi_framework' ),
            'edit_item' => __( 'Edit Item', 'pi_framework' ),
            'new_item' => __( 'New Item', 'pi_framework' ),
            'view_item' => __( 'View Item', 'pi_framework' ),
            'search_items' => __( 'Search Item', 'pi_framework' ),
            'not_found' => __( 'No Galleries images found', 'pi_framework' ),
            'not_found_in_trash' => __( 'No galleries found in Trash', 'pi_framework' ),
            'parent_item_colon' => ''
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'rewrite' => array( 'slug' => 'gallery-item', 'with_front' => true ),
            'show_ui' => true,
            'query_var' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'menu_position' => '20',
            'menu_icon' => VCPT_PLUGIN_URL . '/imgs/portfolio.png',
            'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'excerpt', 'post-formats' )
        );

        register_post_type( 'pi_gallery', $args );
    }

}
add_action( 'init', 'vcpt_cpt_create_post_type_gallery' );

/* Create taxonomy Portfolio Category */
if ( !function_exists( 'vcpt_cpt_create_gallery_taxonomies' ) ) {

    function vcpt_cpt_create_gallery_taxonomies() {
        global $post_types_config;

        if ( $post_types_config['pi_gallery']['cpt'] != '1' && $post_types_config['pi_gallery']['taxonomy'] != '1' )
            return;

        register_taxonomy( "gallery-category", array( "pi_gallery" ), array( "hierarchical" => true, "label" => __( "Categories", 'pi_framework' ), "singular_label" => __( "Gallery Category", 'pi_framework' ), "rewrite" => array( 'slug' => 'gallery-category', 'hierarchical' => true ) ) );
        if ( !term_exists( 'default', 'gallery-category' ) ) {
            $parent_term = term_exists( 'default', 'gallery-category' ); // array is returned if taxonomy is given
            $parent_term_id = $parent_term['term_id']; // get numeric term id
            wp_insert_term(
                    __( 'Default', 'pi_framework' ), // the term 
                    'gallery-category', // the taxonomy
                    array(
                'description' => __( 'Default gallery category.', 'pi_framework' ),
                'slug' => 'default',
                'parent' => $parent_term_id
                    )
            );
        }
    }

}
add_action( 'init', 'vcpt_cpt_create_gallery_taxonomies' );

/* Set default portfolio category when publishing portfolio post */
if ( !function_exists( 'vcpt_cpt_gallery_set_default_object_terms' ) ) {

    function vcpt_cpt_gallery_set_default_object_terms( $post_id, $post ) {
        global $post_types_config;

        if ( $post_types_config['pi_gallery']['cpt'] != '1' && $post_types_config['pi_gallery']['taxonomy'] != '1' )
            return;

        if ( $post->post_status === 'publish' && $post->post_type == "pi_gallery" ) {
            $defaults = array(
                'gallery-category' => array( 'default' )
            );
            $taxonomies = get_object_taxonomies( $post->post_type );
            foreach ( ( array ) $taxonomies as $taxonomy ) {
                $terms = wp_get_post_terms( $post_id, $taxonomy );
                if ( empty( $terms ) && array_key_exists( $taxonomy, $defaults ) ) {
                    wp_set_object_terms( $post_id, $defaults[$taxonomy], $taxonomy );
                }
            }
        }
    }

}
add_action( 'save_post', 'vcpt_cpt_gallery_set_default_object_terms', 100, 2 );

/* Show Portfolio image in list of Portfolios */
if ( !function_exists( 'vcpt_cpt_columns_head_only_pi_gallery' ) ) {

// Create new column
    function vcpt_cpt_columns_head_only_pi_gallery( $defaults ) {
        global $post_types_config;

        if ( $post_types_config['pi_gallery']['cpt'] != '1' )
            return;

        $defaults['gallery_image'] = 'Image';
        return $defaults;
    }

}

if ( !function_exists( 'vcpt_cpt_columns_content_only_pi_gallery' ) ) {

// show image in column
    function vcpt_cpt_columns_content_only_pi_gallery( $column_name, $post_ID ) {
        global $post_types_config;

        if ( $post_types_config['pi_gallery']['cpt'] != '1' )
            return;

        if ( $column_name == 'gallery_image' ) {
            $images = get_post_custom_values( 'pf_image', $post_ID );
            if ( !empty( $images ) ) {
                $featured_image = wp_get_attachment_image_src( $images[0], 'thumbnail' );
            } else {
                $featured_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_ID ), 'thumbnail' );
            }

            if ( $featured_image ) {
                // image found
                echo '<img src="' . $featured_image[0] . '" alt="Image"/>';
            } else {
                // no image
                $default_image = VCPT_PLUGIN_URL . '/imgs/placeholder.jpg';
                echo "<img src='{$default_image}' width='150' alt='placeholder image'/>";
            }
        }
    }

}
// Portfolio post type filter
add_filter( 'manage_pi_gallery_posts_columns', 'vcpt_cpt_columns_head_only_pi_gallery', 10 );
add_action( 'manage_pi_gallery_posts_custom_column', 'vcpt_cpt_columns_content_only_pi_gallery', 10, 2 );


/* ------------------------------------------------------------------
 * Vehicle post type
  ------------------------------------------------------------------- */
if ( !function_exists( 'vcpt_cpt_create_post_type_vehicles' ) ) {

    function vcpt_cpt_create_post_type_vehicles() {
        global $post_types_config;

        if ( $post_types_config['pi_vehicles']['cpt'] != '1' )
            return;


        $labels = array(
            'name' => __( 'Vehicles', 'pi_framework' ),
            'singular_name' => __( 'Vehicles', 'pi_framework' ),
            'add_new' => _x( 'Add New', 'vehicle', 'pi_framework' ),
            'add_new_item' => __( 'Add New Item', 'pi_framework' ),
            'edit_item' => __( 'Edit Item', 'pi_framework' ),
            'new_item' => __( 'New Item', 'pi_framework' ),
            'view_item' => __( 'View Item', 'pi_framework' ),
            'search_items' => __( 'Search Item', 'pi_framework' ),
            'not_found' => __( 'No Vehicles found.', 'pi_framework' ),
            'not_found_in_trash' => __( 'No vehicles found in Trash', 'pi_framework' ),
            'parent_item_colon' => ''
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'rewrite' => array( 'slug' => 'vehicle-fleet', 'with_front' => true ),
            'show_ui' => true,
            'query_var' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'menu_position' => '20',
            'menu_icon' => VCPT_PLUGIN_URL . '/imgs/truck.png',
            'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'excerpt', 'post-formats' )
        );

        register_post_type( 'pi_vehicles', $args );
    }

}
add_action( 'init', 'vcpt_cpt_create_post_type_vehicles' );

/* Create taxonomy Vehicle Category */
if ( !function_exists( 'vcpt_cpt_create_vehicle_taxonomies' ) ) {

    function vcpt_cpt_create_vehicle_taxonomies() {
        global $post_types_config;

        if ( $post_types_config['pi_vehicles']['cpt'] != '1' && $post_types_config['pi_vehicles']['taxonomy'] != '1' )
            return;

        register_taxonomy( "vehicle-category", array( "pi_vehicles" ), array( "hierarchical" => true, "label" => __( "Categories", 'pi_framework' ), "singular_label" => __( "Vehicles Category", 'pi_framework' ), "rewrite" => array( 'slug' => 'vehicles-category', 'hierarchical' => true ) ) );
        if ( !term_exists( 'default', 'vehicle-category' ) ) {
            $parent_term = term_exists( 'default', 'vehicle-category' ); // array is returned if taxonomy is given
            $parent_term_id = $parent_term['term_id']; // get numeric term id
            wp_insert_term(
                    __( 'Default', 'pi_framework' ), // the term 
                    'vehicle-category', // the taxonomy
                    array(
                'description' => __( 'Default vehicle category.', 'pi_framework' ),
                'slug' => 'default',
                'parent' => $parent_term_id
                    )
            );
        }
    }

}
add_action( 'init', 'vcpt_cpt_create_vehicle_taxonomies' );

/* Set default vehicle category when publishing vehicles post */
if ( !function_exists( 'vcpt_cpt_vehicles_set_default_object_terms' ) ) {

    function vcpt_cpt_vehicles_set_default_object_terms( $post_id, $post ) {
        global $post_types_config;

        if ( $post_types_config['pi_vehicles']['cpt'] != '1' && $post_types_config['pi_vehicles']['taxonomy'] != '1' )
            return;

        if ( $post->post_status === 'publish' && $post->post_type == "pi_vehicles" ) {
            $defaults = array(
                'vehicle-category' => array( 'default' )
            );
            $taxonomies = get_object_taxonomies( $post->post_type );
            foreach ( ( array ) $taxonomies as $taxonomy ) {
                $terms = wp_get_post_terms( $post_id, $taxonomy );
                if ( empty( $terms ) && array_key_exists( $taxonomy, $defaults ) ) {
                    wp_set_object_terms( $post_id, $defaults[$taxonomy], $taxonomy );
                }
            }
        }
    }

}
add_action( 'save_post', 'vcpt_cpt_vehicles_set_default_object_terms', 100, 2 );

/* Show vehicle image in list of Vehicles */
if ( !function_exists( 'vcpt_cpt_columns_head_only_pi_vehicles' ) ) {

// Create new column
    function vcpt_cpt_columns_head_only_pi_vehicles( $defaults ) {
        global $post_types_config;

        if ( $post_types_config['pi_vehicles']['cpt'] != '1' )
            return;

        $defaults['vehicle_image'] = 'Image';

        return $defaults;
    }

}

if ( !function_exists( 'vcpt_cpt_columns_content_only_pi_vehicles' ) ) {

// show image in column
    function vcpt_cpt_columns_content_only_pi_vehicles( $column_name, $post_ID ) {
        global $post_types_config;

        if ( $post_types_config['pi_vehicles']['cpt'] != '1' )
            return;

        if ( $column_name == 'vehicle_image' ) {
            $images = get_post_custom_values( 'vh_image', $post_ID );
            $featured_image = wp_get_attachment_image_src( $images[0], 'thumbnail' );

            if ( $featured_image ) {
                // image found
                echo '<img src="' . $featured_image[0] . '" alt="Image"/>';
            } else {
                // no image
                $default_image = VCPT_PLUGIN_URL . '/imgs/placeholder.jpg';
                echo "<img src='{$default_image}' width='150' alt='placeholder image'/>";
            }
        }
    }

}
// Vehicles post type filter
add_filter( 'manage_pi_vehicles_posts_columns', 'vcpt_cpt_columns_head_only_pi_vehicles', 10 );
add_action( 'manage_pi_vehicles_posts_custom_column', 'vcpt_cpt_columns_content_only_pi_vehicles', 10, 2 );

/* ------------------------------------------------------------------
 * Catering post type
  ------------------------------------------------------------------- */
if ( !function_exists( 'vcpt_cpt_create_post_type_catering' ) ) {

    function vcpt_cpt_create_post_type_catering() {
        global $post_types_config;

        if ( $post_types_config['pi_catering']['cpt'] != '1' )
            return;


        $labels = array(
            'name' => __( 'Catering', 'pi_framework' ),
            'singular_name' => __( 'Catering', 'pi_framework' ),
            'add_new' => _x( 'Add New', 'Catering', 'pi_framework' ),
            'add_new_item' => __( 'Add New Item', 'pi_framework' ),
            'edit_item' => __( 'Edit Item', 'pi_framework' ),
            'new_item' => __( 'New Item', 'pi_framework' ),
            'view_item' => __( 'View Item', 'pi_framework' ),
            'search_items' => __( 'Search Item', 'pi_framework' ),
            'not_found' => __( 'No Catering images found', 'pi_framework' ),
            'not_found_in_trash' => __( 'No catering images found in Trash', 'pi_framework' ),
            'parent_item_colon' => ''
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'rewrite' => array( 'slug' => 'catering-item', 'with_front' => true ),
            'show_ui' => true,
            'query_var' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'menu_position' => '20',
            'menu_icon' => VCPT_PLUGIN_URL . '/imgs/truck.png',
            'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'excerpt' )
        );

        register_post_type( 'pi_catering', $args );
    }

}
add_action( 'init', 'vcpt_cpt_create_post_type_catering' );

/* Create taxonomy catering Category */
if ( !function_exists( 'vcpt_cpt_create_catering_taxonomies' ) ) {

    function vcpt_cpt_create_catering_taxonomies() {
        global $post_types_config;

        if ( $post_types_config['pi_catering']['cpt'] != '1' && $post_types_config['pi_catering']['taxonomy'] != '1' )
            return;

        register_taxonomy( "catering-category", array( "pi_catering" ), array( "hierarchical" => true, "label" => __( "Categories", 'pi_framework' ), "singular_label" => __( "catering Category", 'pi_framework' ), "rewrite" => array( 'slug' => 'catering-category', 'hierarchical' => true ) ) );
        if ( !term_exists( 'default', 'catering-category' ) ) {
            $parent_term = term_exists( 'default', 'catering-category' ); // array is returned if taxonomy is given
            $parent_term_id = $parent_term['term_id']; // get numeric term id
            wp_insert_term(
                    __( 'Default', 'pi_framework' ), // the term 
                    'catering-category', // the taxonomy
                    array(
                'description' => __( 'Default catering category.', 'pi_framework' ),
                'slug' => 'default',
                'parent' => $parent_term_id
                    )
            );
        }
    }

}
add_action( 'init', 'vcpt_cpt_create_catering_taxonomies' );

/* Set default catering category when publishing catering post */
if ( !function_exists( 'vcpt_cpt_set_default_object_terms' ) ) {

    function vcpt_cpt_set_default_object_terms( $post_id, $post ) {
        global $post_types_config;

        if ( $post_types_config['pi_catering']['cpt'] != '1' && $post_types_config['pi_catering']['taxonomy'] != '1' )
            return;

        if ( $post->post_status === 'publish' && $post->post_type == "pi_catering" ) {
            $defaults = array(
                'catering-category' => array( 'default' )
            );
            $taxonomies = get_object_taxonomies( $post->post_type );
            foreach ( ( array ) $taxonomies as $taxonomy ) {
                $terms = wp_get_post_terms( $post_id, $taxonomy );
                if ( empty( $terms ) && array_key_exists( $taxonomy, $defaults ) ) {
                    wp_set_object_terms( $post_id, $defaults[$taxonomy], $taxonomy );
                }
            }
        }
    }

}
add_action( 'save_post', 'vcpt_cpt_set_default_object_terms', 100, 2 );

/* Show catering image in list of caterings */
if ( !function_exists( 'vcpt_cpt_columns_head_only_pi_catering' ) ) {

// Create new column
    function vcpt_cpt_columns_head_only_pi_catering( $defaults ) {
        global $post_types_config;

        if ( $post_types_config['pi_catering']['cpt'] != '1' )
            return;

        $defaults['catering_image'] = 'Image';

        return $defaults;
    }

}

if ( !function_exists( 'vcpt_cpt_columns_content_only_pi_catering' ) ) {

// show image in column
    function vcpt_cpt_columns_content_only_pi_catering( $column_name, $post_ID ) {
        global $post_types_config;

        if ( $post_types_config['pi_catering']['cpt'] != '1' )
            return;

        if ( $column_name == 'catering_image' ) {

            $featured_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_ID ), 'thumbnail' );

            if ( $featured_image ) {
                // image found
                echo '<img src="' . $featured_image[0] . '" alt="Image"/>';
            } else {
                // no image
                $default_image = VCPT_PLUGIN_URL . '/imgs/placeholder.jpg';
                echo "<img src='{$default_image}' width='150' alt='placeholder image'/>";
            }
        }
    }

}
// catering post type filter
add_filter( 'manage_pi_catering_posts_columns', 'vcpt_cpt_columns_head_only_pi_catering', 10 );
add_action( 'manage_pi_catering_posts_custom_column', 'vcpt_cpt_columns_content_only_pi_catering', 10, 2 );

/* ------------------------------------------------------------------
 * Menus post type
  ------------------------------------------------------------------- */
if ( !function_exists( 'vcpt_cpt_create_post_type_dish_drinks' ) ) {

    function vcpt_cpt_create_post_type_dish_drinks() {
        global $post_types_config;

        if ( $post_types_config['pi_dish_drinks']['cpt'] != '1' )
            return;


        $labels = array(
            'name' => __( 'Dish and drinks', 'pi_framework' ),
            'singular_name' => __( 'Dish and drinks', 'pi_framework' ),
            'add_new' => _x( 'Add New', 'Dish and drinks', 'pi_framework' ),
            'add_new_item' => __( 'Add New Item', 'pi_framework' ),
            'edit_item' => __( 'Edit Item', 'pi_framework' ),
            'new_item' => __( 'New Item', 'pi_framework' ),
            'view_item' => __( 'View Item', 'pi_framework' ),
            'search_items' => __( 'Search Item', 'pi_framework' ),
            'not_found' => __( 'No Dish and drinks found.', 'pi_framework' ),
            'not_found_in_trash' => __( 'No Dish and drinks found in Trash', 'pi_framework' ),
            'parent_item_colon' => ''
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'rewrite' => array( 'slug' => 'dish-and-drinks', 'with_front' => true ),
            'show_ui' => true,
            'query_var' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'menu_position' => '20',
            'menu_icon' => VCPT_PLUGIN_URL . '/imgs/food.png',
            'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'excerpt' )
        );

        register_post_type( 'pi_dish_drinks', $args );
    }

}
add_action( 'init', 'vcpt_cpt_create_post_type_dish_drinks' );

/* Create taxonomy dish and drinks Category */
if ( !function_exists( 'vcpt_cpt_create_dish_drinks_taxonomies' ) ) {

    function vcpt_cpt_create_dish_drinks_taxonomies() {
        global $post_types_config;

        if ( $post_types_config['pi_dish_drinks']['cpt'] != '1' && $post_types_config['pi_dish_drinks']['taxonomy'] != '1' )
            return;

        register_taxonomy( "menus-category", array( "pi_dish_drinks" ), array( "hierarchical" => true, "label" => __( "Menus", 'pi_framework' ), "singular_label" => __( "Menus Category", 'pi_framework' ), "rewrite" => array( 'slug' => 'menus-category', 'hierarchical' => true ) ) );
        if ( !term_exists( 'featured', 'menus-category' ) ) {
            $parent_term = term_exists( 'featured', 'menus-category' ); // array is returned if taxonomy is given
            $parent_term_id = $parent_term['term_id']; // get numeric term id
            wp_insert_term(
                    __( 'Featured', 'pi_framework' ), // the term 
                    'menus-category', // the taxonomy
                    array(
                'description' => __( 'Default dish and drinks category.', 'pi_framework' ),
                'slug' => 'featured',
                'parent' => $parent_term_id
                    )
            );
        }
    }

}
add_action( 'init', 'vcpt_cpt_create_dish_drinks_taxonomies' );

/* Set default dish and drinks category when publishing menus post */
if ( !function_exists( 'vcpt_cpt_dish_drinks_set_default_object_terms' ) ) {

    function vcpt_cpt_dish_drinks_set_default_object_terms( $post_id, $post ) {
        global $post_types_config;

        if ( $post_types_config['pi_dish_drinks']['cpt'] != '1' && $post_types_config['pi_dish_drinks']['taxonomy'] != '1' )
            return;

        if ( $post->post_status === 'publish' && $post->post_type == "pi_dish_drinks" ) {
            $defaults = array(
                'menus-category' => array( 'featured' )
            );
            $taxonomies = get_object_taxonomies( $post->post_type );
            foreach ( ( array ) $taxonomies as $taxonomy ) {
                $terms = wp_get_post_terms( $post_id, $taxonomy );
                if ( empty( $terms ) && array_key_exists( $taxonomy, $defaults ) ) {
                    wp_set_object_terms( $post_id, $defaults[$taxonomy], $taxonomy );
                }
            }
        }
    }

}
add_action( 'save_post', 'vcpt_cpt_dish_drinks_set_default_object_terms', 100, 2 );

/* Show dish and drinks image in list of menus */
if ( !function_exists( 'vcpt_cpt_columns_head_only_pi_dish_drinks' ) ) {

// Create new column
    function vcpt_cpt_columns_head_only_pi_dish_drinks( $defaults ) {
        global $post_types_config;

        if ( $post_types_config['pi_dish_drinks']['cpt'] != '1' )
            return;

        $defaults['menus_image'] = 'Image';

        return $defaults;
    }

}

if ( !function_exists( 'vcpt_cpt_columns_content_only_pi_dish_drinks' ) ) {

    // show image in column
    function vcpt_cpt_columns_content_only_pi_dish_drinks( $column_name, $post_ID ) {
        global $post_types_config;

        if ( $post_types_config['pi_dish_drinks']['cpt'] != '1' )
            return;

        if ( $column_name == 'menus_image' ) {
            $featured_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_ID ), 'thumbnail' );

            if ( $featured_image ) {
                // image found
                echo '<img src="' . $featured_image[0] . '" alt="Image"/>';
            } else {
                // no image
                $default_image = VCPT_PLUGIN_URL . '/imgs/placeholder.jpg';
                echo "<img src='{$default_image}' width='150' alt='placeholder image'/>";
            }
        }
    }

}
// dish and drinks post type filter
add_filter( 'manage_pi_dish_drinks_posts_columns', 'vcpt_cpt_columns_head_only_pi_dish_drinks', 10 );
add_action( 'manage_pi_dish_drinks_posts_custom_column', 'vcpt_cpt_columns_content_only_pi_dish_drinks', 10, 2 );

/* ------------------------------------------------------------------
 * Case Studies post type
  ------------------------------------------------------------------- */
if ( !function_exists( 'vcpt_cpt_create_post_type_case_studies' ) ) {

    function vcpt_cpt_create_post_type_case_studies() {
        global $post_types_config;

        if ( $post_types_config['pi_case_studies']['cpt'] != '1' )
            return;


        $labels = array(
            'name' => __( 'Case Studies', 'pi_framework' ),
            'singular_name' => __( 'Case Studies', 'pi_framework' ),
            'add_new' => _x( 'Add New', 'Case Studies', 'pi_framework' ),
            'add_new_item' => __( 'Add New Item', 'pi_framework' ),
            'edit_item' => __( 'Edit Item', 'pi_framework' ),
            'new_item' => __( 'New Item', 'pi_framework' ),
            'view_item' => __( 'View Item', 'pi_framework' ),
            'search_items' => __( 'Search Item', 'pi_framework' ),
            'not_found' => __( 'No Case Studies found', 'pi_framework' ),
            'not_found_in_trash' => __( 'No Case Studies found in Trash', 'pi_framework' ),
            'parent_item_colon' => ''
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'rewrite' => array( 'slug' => _x( 'case-studies-item', 'pi_framework' ), 'with_front' => true ),
            'show_ui' => true,
            'query_var' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'menu_position' => '20',
            'menu_icon' => VCPT_PLUGIN_URL . '/imgs/case-studies.png',
            'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'excerpt' )
        );

        register_post_type( 'pi_case_studies', $args );
    }

}
add_action( 'init', 'vcpt_cpt_create_post_type_case_studies' );

/* Create taxonomy case studies Category */
if ( !function_exists( 'vcpt_cpt_create_case_studies_taxonomies' ) ) {

    function vcpt_cpt_create_case_studies_taxonomies() {
        global $post_types_config;

        if ( $post_types_config['pi_case_studies']['cpt'] != '1' && $post_types_config['pi_case_studies']['taxonomy'] != '1' )
            return;

        register_taxonomy( "case-studies-category", array( "pi_case_studies" ), array( "hierarchical" => true, "label" => __( "Categories", 'pi_framework' ), "singular_label" => __( "Case Studies Category", 'pi_framework' ), "rewrite" => array( 'slug' => 'case-studies-category', 'hierarchical' => true ) ) );
        if ( !term_exists( 'default', 'case-studies-category' ) ) {
            $parent_term = term_exists( 'default', 'case-studies-category' ); // array is returned if taxonomy is given
            $parent_term_id = $parent_term['term_id']; // get numeric term id
            wp_insert_term(
                    __( 'Default', 'pi_framework' ), // the term 
                    'case-studies-category', // the taxonomy
                    array(
                'description' => __( 'Default case studies category.', 'pi_framework' ),
                'slug' => 'default',
                'parent' => $parent_term_id
                    )
            );
        }
    }

}
add_action( 'init', 'vcpt_cpt_create_case_studies_taxonomies', 9 );

/* Set default case studies category when publishing case studies post */
if ( !function_exists( 'vcpt_cpt_set_default_object_terms' ) ) {

    function vcpt_cpt_set_default_object_terms( $post_id, $post ) {
        global $post_types_config;

        if ( $post_types_config['pi_case_studies']['cpt'] != '1' && $post_types_config['pi_case_studies']['taxonomy'] != '1' )
            return;

        if ( $post->post_status === 'publish' && $post->post_type == "pi_case_studies" ) {
            $defaults = array(
                'case-studies-category' => array( 'default' )
            );
            $taxonomies = get_object_taxonomies( $post->post_type );
            foreach ( ( array ) $taxonomies as $taxonomy ) {
                $terms = wp_get_post_terms( $post_id, $taxonomy );
                if ( empty( $terms ) && array_key_exists( $taxonomy, $defaults ) ) {
                    wp_set_object_terms( $post_id, $defaults[$taxonomy], $taxonomy );
                }
            }
        }
    }

}
add_action( 'save_post', 'vcpt_cpt_set_default_object_terms', 100, 2 );

/* Show case studies image in list of case studies */
if ( !function_exists( 'vcpt_cpt_columns_head_only_pi_case_studies' ) ) {

    // Create new column
    function vcpt_cpt_columns_head_only_pi_case_studies( $defaults ) {
        global $post_types_config;

        if ( $post_types_config['pi_case_studies']['cpt'] != '1' )
            return;

        $defaults['case_studies_image'] = 'Image';

        return $defaults;
    }

}

if ( !function_exists( 'vcpt_cpt_columns_content_only_pi_case_studies' ) ) {

    // show image in column
    function vcpt_cpt_columns_content_only_pi_case_studies( $column_name, $post_ID ) {
        global $post_types_config;

        if ( $post_types_config['pi_case_studies']['cpt'] != '1' )
            return;

        if ( $column_name == 'case_studies_image' ) {

            $featured_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_ID ), 'thumbnail' );

            if ( $featured_image ) {
                // image found
                echo '<img src="' . $featured_image[0] . '" alt="Image"/>';
            } else {
                // no image
                $default_image = VCPT_PLUGIN_URL . '/imgs/placeholder.jpg';
                echo "<img src='{$default_image}' width='150' alt='placeholder image'/>";
            }
        }
    }

}
// Case studies post type filter
add_filter( 'manage_pi_case_studies_posts_columns', 'vcpt_cpt_columns_head_only_pi_case_studies', 10 );
add_action( 'manage_pi_case_studies_posts_custom_column', 'vcpt_cpt_columns_content_only_pi_case_studies', 10, 2 );

/* ------------------------------------------------------------------
 * Photography post type
  ------------------------------------------------------------------- */
if ( !function_exists( 'vcpt_cpt_create_post_type_photography' ) ) {

    function vcpt_cpt_create_post_type_photography() {
        global $post_types_config;

        if ( $post_types_config['pi_photography']['cpt'] != '1' )
            return;

        $labels = array(
            'name' => __( 'Photography', 'pi_framework' ),
            'singular_name' => __( 'Photography', 'pi_framework' ),
            'add_new' => _x( 'Add New', 'Photography', 'pi_framework' ),
            'add_new_item' => __( 'Add New Item', 'pi_framework' ),
            'edit_item' => __( 'Edit Item', 'pi_framework' ),
            'new_item' => __( 'New Item', 'pi_framework' ),
            'view_item' => __( 'View Item', 'pi_framework' ),
            'search_items' => __( 'Search Item', 'pi_framework' ),
            'not_found' => __( 'No Galleries images found', 'pi_framework' ),
            'not_found_in_trash' => __( 'No Photographies found in Trash', 'pi_framework' ),
            'parent_item_colon' => ''
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'rewrite' => array( 'slug' => 'photography-item', 'with_front' => true ),
            'show_ui' => true,
            'query_var' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'menu_position' => '20',
            'menu_icon' => VCPT_PLUGIN_URL . '/imgs/portfolio.png',
            'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'excerpt', 'post-formats' )
        );

        register_post_type( 'pi_photography', $args );
    }

}
add_action( 'init', 'vcpt_cpt_create_post_type_photography' );

/* Create taxonomy portfolio Category */
if ( !function_exists( 'vcpt_cpt_create_photography_taxonomies' ) ) {

    function vcpt_cpt_create_photography_taxonomies() {
        global $post_types_config;

        if ( $post_types_config['pi_photography']['cpt'] != '1' && $post_types_config['pi_photography']['taxonomy'] != '1' )
            return;

        register_taxonomy( "photography-category", array( "pi_photography" ), array( "hierarchical" => true, "label" => __( "Categories", 'pi_framework' ), "singular_label" => __( "Photography Category", 'pi_framework' ), "rewrite" => array( 'slug' => 'photography-category', 'hierarchical' => true ) ) );
        if ( !term_exists( 'default', 'photography-category' ) ) {
            $parent_term = term_exists( 'default', 'photography-category' ); // array is returned if taxonomy is given
            $parent_term_id = $parent_term['term_id']; // get numeric term id
            wp_insert_term(
                    __( 'Default', 'pi_framework' ), // the term 
                    'photography-category', // the taxonomy
                    array(
                'description' => __( 'Default photography category.', 'pi_framework' ),
                'slug' => 'default',
                'parent' => $parent_term_id
                    )
            );
        }
    }

}
add_action( 'init', 'vcpt_cpt_create_photography_taxonomies' );

/* Set default portfolio category when publishing portfolio post */
if ( !function_exists( 'vcpt_cpt_photography_set_default_object_terms' ) ) {

    function vcpt_cpt_photography_set_default_object_terms( $post_id, $post ) {
        global $post_types_config;

        if ( $post_types_config['pi_photography']['cpt'] != '1' && $post_types_config['pi_photography']['taxonomy'] != '1' )
            return;

        if ( $post->post_status === 'publish' && $post->post_type == "pi_photography" ) {
            $defaults = array(
                'photography-category' => array( 'default' )
            );
            $taxonomies = get_object_taxonomies( $post->post_type );
            foreach ( ( array ) $taxonomies as $taxonomy ) {
                $terms = wp_get_post_terms( $post_id, $taxonomy );
                if ( empty( $terms ) && array_key_exists( $taxonomy, $defaults ) ) {
                    wp_set_object_terms( $post_id, $defaults[$taxonomy], $taxonomy );
                }
            }
        }
    }

}
add_action( 'save_post', 'vcpt_cpt_photography_set_default_object_terms', 100, 2 );

/* Show portfolio image in list of portfolios */
if ( !function_exists( 'vcpt_cpt_columns_head_only_pi_photography' ) ) {

// Create new column
    function vcpt_cpt_columns_head_only_pi_photography( $defaults ) {
        global $post_types_config;

        if ( $post_types_config['pi_photography']['cpt'] != '1' )
            return;

        $defaults['photography_image'] = 'Image';
        return $defaults;
    }

}

if ( !function_exists( 'vcpt_cpt_columns_content_only_pi_photography' ) ) {

    // show image in column
    function vcpt_cpt_columns_content_only_pi_photography( $column_name, $post_ID ) {
        global $post_types_config;

        if ( $post_types_config['pi_photography']['cpt'] != '1' )
            return;

        if ( $column_name == 'photography_image' ) {
            $images = get_post_custom_values( 'pf_image', $post_ID );
            $featured_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_ID ), 'thumbnail' );

            if ( $featured_image ) {
                // image found
                echo '<img src="' . $featured_image[0] . '" alt="Image"/>';
            } else {
                // no image
                $default_image = VCPT_PLUGIN_URL . '/imgs/placeholder.jpg';
                echo "<img src='{$default_image}' width='150' alt='placeholder image'/>";
            }
        }
    }

}
// portfolio post type filter
add_filter( 'manage_pi_photography_posts_columns', 'vcpt_cpt_columns_head_only_pi_photography', 10 );
add_action( 'manage_pi_photography_posts_custom_column', 'vcpt_cpt_columns_content_only_pi_photography', 10, 2 );
?>
