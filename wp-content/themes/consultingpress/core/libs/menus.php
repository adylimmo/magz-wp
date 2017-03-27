<?php

/* ---------------------------------------------------------
 * Menu Walker
 *
 * Custom Menu Walker with addition of icons.
  ---------------------------------------------------------- */
if ( !class_exists( 'Volcanno_Theme_Menu_Walker' ) ) {


    class Volcanno_Theme_Menu_Walker extends Walker_Nav_Menu {

        /**
         * Starts the list before the elements are added.
         *
         * @see Walker::start_lvl()
         *
         * @since 3.0.0
         *
         * @param string $output Passed by reference. Used to append additional content.
         * @param int    $depth  Depth of menu item. Used for padding.
         * @param array  $args   An array of arguments. @see wp_nav_menu()
         */
        function start_lvl( &$output, $depth = 0, $args = array() ) {
            $indent = str_repeat( "\t", $depth );
            if ( $depth > 0 ) {
                $depth_level = "depth-" . $depth;
                $output .= "\n$indent<ul class=\"dropdown-menu {$depth_level}\">\n";
            } else {
                $output .= "\n$indent<ul class=\"dropdown-menu\">\n";
            }
        }

        /**
         * Start the element output.
         *
         * @see Walker::start_el()
         *
         * @since 3.0.0
         *
         * @param string $output Passed by reference. Used to append additional content.
         * @param object $item   Menu item data object.
         * @param int    $depth  Depth of menu item. Used for padding.
         * @param array  $args   An array of arguments. @see wp_nav_menu()
         * @param int    $id     Current item ID.
         */
        function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
            global $wp_query, $volcanno_theme_config;


            $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
            //$hide_icons = of_get_option('menu_hide_icons', 0);
            $class_names = $value = '';  

            $classes = empty( $item->classes ) ? array() : ( array ) $item->classes;
            $classes[] = 'menu-item-' . $item->ID;
            $classes[] = ( !empty( $args->has_children ) ) ? 'dropdown' : '';
            $classes[] = ( !empty( $args->has_children ) ) ? 'dropdown-submenu' : '';

            $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
            $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

            $id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args );
            $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

            $output .= $indent . '<li' . $id . $value . $class_names . '>';

            $attributes = !empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) . '"' : '';
            $attributes .=!empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
            $attributes .=!empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . '"' : '';
            $attributes .=!empty( $item->url ) ? ' href="' . esc_attr( $item->url ) . '"' : '';
            $attributes .= (!empty( $args->has_children )) ? ' data-toggle="dropdown"' : '';
            $attributes .= (!empty( $args->has_children )) ? ' class="dropdown-toggle"' : '';

            $item_output = !empty( $args->before ) ? $args->before : '';
            $item_output .= '<a' . $attributes . '>';
            $link_before = !empty( $args->link_before ) ? $args->link_before : '';
            $link_after = !empty( $args->link_after ) ? $args->link_after : '';
            $item_output .= $link_before . apply_filters( 'volcanno_theme_menu_icon', $item ) . apply_filters( 'the_title', $item->title, $item->ID ) . $link_after;

            if ( isset( $volcanno_theme_config['menu_caret'] ) && $volcanno_theme_config['menu_caret'] == '1' ) {
                $item_output .= ($depth == 0 && !empty( $args->has_children)) ? '<b class="caret"></b>' : "";
            }

            $item_output .= ($depth == 0) ? '<span>' . $item->description . '</span>' : "";

            $item_output .= '</a>';

            $item_output .= !empty( $args->after );

            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
        }

        /**
         * Add has_children parameter for each menu item.
         * 
         * @param object $element
         * @param array $children_elements
         * @param int $max_depth
         * @param int $depth
         * @param array $args
         * @param string $output
         * @return type
         */
        function display_element( $element, &$children_elements, $max_depth, $depth = 0, $args, &$output ) {
            $id_field = $this->db_fields['id'];
            if ( is_object( $args[0] ) ) {
                $args[0]->has_children = !empty( $children_elements[$element->$id_field] );
            }
            return parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
        }

    }

}

/* ---------------------------------------------------------
 * Responsive Menu Walker
 *
 * Custom Responsive Menu Walker.
  ---------------------------------------------------------- */
if ( !class_exists( 'Volcanno_Theme_Responsive_Menu_Walker' ) ) {

    class Volcanno_Theme_Responsive_Menu_Walker extends Walker_Nav_Menu {

        /**
         * Starts the list before the elements are added.
         *
         * @see Walker::start_lvl()
         *
         * @since 3.0.0
         *
         * @param string $output Passed by reference. Used to append additional content.
         * @param int    $depth  Depth of menu item. Used for padding.
         * @param array  $args   An array of arguments. @see wp_nav_menu()
         */
        function start_lvl( &$output, $depth = 0, $args = array() ) {
            $indent = str_repeat( "\t", $depth );
            $output .= "\n$indent<ul class=\"dl-submenu\">\n";
        }

        /**
         * Start the element output.
         *
         * @see Walker::start_el()
         *
         * @since 3.0.0
         *
         * @param string $output Passed by reference. Used to append additional content.
         * @param object $item   Menu item data object.
         * @param int    $depth  Depth of menu item. Used for padding.
         * @param array  $args   An array of arguments. @see wp_nav_menu()
         * @param int    $id     Current item ID.
         */
        function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
            global $wp_query;

            $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
            //$hide_icons = of_get_option('menu_hide_icons', 0);
            $class_names = $value = '';

            $classes = empty( $item->classes ) ? array() : ( array ) $item->classes;
            $classes[] = 'menu-item-' . $item->ID;

            $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
            $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

            $id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args );
            $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

            $output .= $indent . '<li' . $id . $value . $class_names . '>';

            $attributes = !empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) . '"' : '';
            $attributes .=!empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
            $attributes .=!empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . '"' : '';
            $attributes .=!empty( $item->url ) ? ' href="' . esc_attr( $item->url ) . '"' : '';
            //$attributes .= $hide_icons ? ' class="no-icons"' : '';

            $item_output = $args->before;
            $item_output .= '<a' . $attributes . '>';
            $item_output .= $args->link_before . apply_filters( 'volcanno_theme_menu_icon', $item ) . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
            $item_output .= '</a>';
            $item_output .= ($depth == 0) ? '<span>' . $item->description . '</span>' : "";
            $item_output .= $args->after;

            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
        }

    }

}

/**
* Menus class
*/
class Volcanno_Menus {
    
    static function init() {
        if ( self::child_override(__FUNCTION__, func_get_args() ) ) return self::child_override(__FUNCTION__, func_get_args() ); // Child overide

        //Add menu icon to first level menu items
        add_filter( 'volcanno_theme_menu_icon', 'Volcanno_Menus::add_menu_icon' );
    }

    /**
     * Overide method if same function in child theme exist
     * 
     * @param  string
     * @return  function
     */
    static function child_override( $function, $params = '' ) {
        
        $class = __CLASS__ . '_Child';

        if ( class_exists( $class ) && is_callable( array($class, $function) ) ) {
            return call_user_func_array(array($class, $function), $params);
        }

    }

    /**
     * Add menu icon to first level menu items
     * 
     * @param $item
     * @return string
     */
    static function add_menu_icon( $item ) {
        if ( self::child_override(__FUNCTION__, func_get_args() ) ) return self::child_override(__FUNCTION__, func_get_args() ); // Child overide

        // find selected icons from Theme options and set for each menu item
        if ( $item->post_type == 'nav_menu_item' && $item->menu_item_parent == 0 ) {

            // get icons from theme options
            $menu_icons = Volcanno::return_theme_option( 'menu_icons' );

            // if icons is set, add span element before menu item text
            if ( !empty( $menu_icons[$item->ID] ) ) {
                $menu_icon = "<span class='nav-icon {$menu_icons[$item->ID]}'></span>";
            } else {
                $menu_icon = '';
            }

            return $menu_icon;
        }
    }
}

Volcanno_Menus::init();

