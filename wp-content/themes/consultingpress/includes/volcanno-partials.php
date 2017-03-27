<?php
/* ---------------------------------------------------------
 * Partials
 *
 * Class for functions that return text / HTML content
  ---------------------------------------------------------- */

class Volcanno_Partials {

    /**
     * Return array of all categories for blog
     * 
     * @param  int  $page_id
     * @param  boolean $reverse
     * @return array
     */
    static function all_page_blog_categories( $page_id, $reverse = true ) {
        $all_categories = get_categories();
        $categories = array();

        foreach ( $all_categories as $category => $cat ) {

            // Get parent term if exist
            $parent_template = get_term_meta( $cat->term_id, 'parent_blog_page', true );

            if ( $parent_template == '' || $parent_template == $page_id ) {

                // For metabox and Redux
                if ( $reverse ) {

                    // Add term prefix with parent term name
                    $parent_taxonomy = get_term_by( 'term_id', $cat->parent, 'category' );
                    $parent_taxonomy_name = !empty( $parent_taxonomy->name ) ? $parent_taxonomy->name . ' > ' : '';

                    $categories[$cat->slug] = $parent_taxonomy_name . $cat->cat_name;

                    // For Visual Composer
                } else {
                    $categories[$cat->cat_name] = $cat->slug;
                }
            }
        }

        // Sort array by value
        if ( is_array( $categories ) ) {
            asort( $categories );
        }

        return $categories;
    }

    /**
     * Returns all avalible case studies categories
     * 
     * @param  int $page_id
     * @return array
     */
    static function volcanno_case_studies_categories( $page_id ) {
        if ( !empty( $page_id ) && taxonomy_exists( 'case-studies-category' ) ) {
            // Get all terms from taxonomy
            $terms = get_terms( array( 'taxonomy' => 'case-studies-category', 'hierarchical' => true ) );
            $output = array();

            foreach ( $terms as $term => $data ) {

                // Get parent term if exist
                $parent_template = get_term_meta( $data->term_id, 'parent_case_studies_page', true );

                if ( $parent_template == '' || $parent_template == $page_id ) {

                    // Add term prefix with parent term name
                    $parent_taxonomy = get_term_by( 'term_id', $data->parent, 'case-studies-category' );
                    $parent_taxonomy_name = !empty( $parent_taxonomy->name ) ? $parent_taxonomy->name . ' > ' : '';

                    $output[$data->slug] = $parent_taxonomy_name . $data->name;
                }
            }

            // Sort array by value
            if ( is_array( $output ) ) {
                asort( $output );
            }

            return $output;
        }
    }

    /**
     * Scrool to top
     * @return string
     */
    static function scrool_to_top() {
        return Volcanno::return_theme_option( 'scrool_to_top' ) ? '<a href="#" class="scroll-up"><i class="fa fa-chevron-up"></i></a>' : '';
    }

    /**
     * Header elements
     * 
     * @param  $element
     * @param  $args   
     * @return string 
     */
    static function header_element( $element, $args = '' ) {
        $output = '';
        switch ( $element ) {
            // Quick links
            case 'quick_links':
                $quick_links = Volcanno::return_theme_option( 'quick_links' );
                if ( $quick_links != '' ) :
                    $icons = $quick_links['icon'];
                    $texts = $quick_links['text'];
                    $quick_links = array_combine( $icons, $texts );
                    $output = '<ul id="quick-links" class="clearfix">';
                    foreach ( $quick_links as $icon => $text ) {
                        $output .= '<li><i class="' . $icon . '"></i>';
                        if ( $text != strip_tags( $text ) ) {
                            $output .= $text;
                        } else {
                            $output .= '<span>' . $text . '</span>';
                        }
                        $output .= '</li>';
                    }
                    $output .= '</ul>';
                elseif ( !VOLCANNO_REDUX_FRAMEWORK ) : 
                    $output = '<ul id="quick-links" class="clearfix"><li><i class="lynny-phone-1"></i><span>+00 41 258 9854</span></li><li><i class="lynny-mail-duplicate"></i><a href="http://localhost/wordpress-templates/consulting-press-clean/">Contact us</a></li></ul>';
                endif;
                break;
            // Cart
            case 'cart':
                if ( Volcanno::return_theme_option( 'display_cart' ) && VOLCANNO_WOOCOMMERCE ) :
                    global $woocommerce;
                    $cart = $woocommerce->cart;
                    $count = !$cart->is_empty() ? '<span class="cart-count">' . $cart->get_cart_contents_count() . '</span>' : '';
                    $amount = !$cart->is_empty() && Volcanno::return_theme_option( 'display_cart_amount' ) ? '<span class="amount">' . $cart->get_total() . '</span>' : '';
                    $output = '<div id="header-cart-ajax" class="header-cart clearfix">
                                    <div class="cart-container icon-cart-3">
                                        <a class="cart-contents" href="' . esc_url( wc_get_cart_url() ) . '" title="' . esc_html__( "View your shopping cart", 'consultingpress' ) . '">
                                            ' . $count . ' 
                                            ' . $amount . '     
                                        </a>
                                    </div>
                                </div>';
                endif;
                break;
            // Wpml Language switcher
            case 'language_switcher':
                if ( Volcanno::return_theme_option( 'display_language_switcher' ) && VOLCANNO_WPML_SITE_PRESS ) {
                    $languages = icl_get_languages( 'skip_missing=0&orderby=code' );
                    $display_lang = '';
                    $active_lang = '';
                    if ( !empty( $languages ) ) {
                        foreach ( $languages as $l ) {
                            if ( $l['active'] ) {
                                $active_lang = '<a class="active" href="' . esc_url( $l['url'] ) . '"><span>' . $l['language_code'] . '</span><i class="fa fa-chevron-down"></i></a>';
                            } else {
                                $display_lang .= '<li><a href="' . esc_url( $l['url'] ) . '">' . $l['language_code'] . '</a></li>';
                            }
                        }
                    }
                    $output = '<div class="wpml-languages enabled">
                                    ' . $active_lang . '
                                    <ul class="wpml-lang-dropdown">' . $display_lang . '</ul>
                                </div>';
                } else if ( Volcanno::return_theme_option( 'display_language_switcher' ) && VOLCANNO_POLYLANG ) {
                    $languages = pll_the_languages( array( 'raw' => '1' ) );
                    if ( !empty( $languages ) ) {
                        $display_lang = '';
                        $active_lang = '';
                        foreach ( $languages as $l ) {
                            if ( $l['current_lang'] ) {
                                $active_lang = '<a class="active" href="' . esc_url( $l['url'] ) . '"><span>' . $l['name'] . '</span><i class="fa fa-chevron-down"></i></a>';
                            } else {
                                $display_lang .= '<li><a href="' . esc_url( $l['url'] ) . '">' . $l['name'] . '</a></li>';
                            }
                        }
                    }
                    $output = '<div class="wpml-languages enabled">
                                    ' . $active_lang . '
                                    <ul class="wpml-lang-dropdown">' . $display_lang . '</ul>
                                </div>';
                } else {
                    $output = apply_filters( 'volcanno_custom_language_switcher', '' );
                }
                break;
            // Social links
            case 'social_links':
                $social_links = Volcanno::return_theme_option( 'social_links' );
                if ( $social_links != '' ) :
                    $icons = $social_links['icon'];
                    $texts = $social_links['text'];
                    $social_links = array_combine( $icons, $texts );
                    $output = '<ul id="social-links">';
                    foreach ( $social_links as $icon => $text ) {
                        $output .= '<li><a target="_blank" href="' . esc_url( $text ) . '" class="' . $icon . '"></a></li>';
                    }
                    $output .= '</ul>';
                elseif ( !VOLCANNO_REDUX_FRAMEWORK ) :
                    $output = '<ul id="social-links"><li><a target="_blank" href="http://linkedin.com" class="fa fa-linkedin"></a></li><li><a target="_blank" href="http://facebook.com" class="fa fa-facebook"></a></li><li><a target="_blank" href="http://twitter.com" class="fa fa-twitter"></a></li></ul>';
                endif;
                break;
            // Search
            case 'search':
                $border = has_nav_menu('primary') ? '' : ' class="no-border"'; 
                $search_placeholder = Volcanno::return_theme_option( 'search_placeholder' ) ? : esc_attr__( 'Type and hit enter...', 'consultingpress' );
                $output = '<div id="search"' . $border . '>
                                <form action="' . esc_url( home_url( '/' ) ) . '" method="get">
                                    <input id="m_search" name="s" type="text" placeholder="' . esc_attr( $search_placeholder ) . '" onkeydown="' . esc_js( "if (event.keyCode == 13) { this.form.submit(); return false; }" ) . '"/>
                                    <input class="search-submit" type="submit">
                                </form>
                            </div>';
                break;
            // Navbar toggle button
            case 'navbar_toggle':
                $output = '<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-nav" aria-expanded="false">
                                <span class="sr-only">' . esc_html__( "Toggle navigation", 'consultingpress' ) . '</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                           </button>';
                break;
            // Info widgets
            case 'info_widgets':
                $info_widgets = Volcanno::return_theme_option( 'info_widgets' );
                if ( $info_widgets != '' ) :
                    $icons = $info_widgets['icon'];
                    $texts = $info_widgets['text'];
                    $info_widgets = array_combine( $icons, $texts );
                    $output = '<div class="header-info-widgets"><ul class="clearfix">';
                    foreach ( $info_widgets as $icon => $text ) {

                        $output .= '<li><div class="icon-container"><i class="' . $icon . '"></i></div>';
                        $output .= '<div class="text-container">';

                        $text = explode( '|', $text );

                        $output .= '<span>' . $text[0] . '</span>';

                        if ( isset( $text[2] ) ) {
                            $output .= '<a href="' . esc_url( $text[2] ) . '">' . $text[1] . '</a>';
                        } else if ( isset( $text[1] ) ) {
                            $output .= '<p>' . $text[1] . '</p>';
                        }

                        $output .= '</li>';
                    }
                    $output .= '</ul></div>';
                endif;
                break;
            // Default
            default:
                $output = '';
                break;
        }

        return $output;
    }

    /**
     * Generate page loader
     * 
     * @return string
     */
    static function generate_loader() {
        $loader_status = Volcanno::return_theme_option( 'theme_loader_status' );
        return $loader_status ? '<div id="loader"><div id="loading-status"></div></div>' : '';
    }

    /**
     * Returns HTML for logo
     *
     * @return string
     */
    static function generate_logo() {

        $logo = apply_filters( 'volcanno_header_logo_id', Volcanno::return_theme_option( 'logo' ) );

        if ( !empty( $logo['url'] ) ) {
            $logo_type = wp_check_filetype( $logo['url'] );
            if ( isset( $logo['id'] ) ) {
                $logo_metadata = Volcanno_Helper::get_attachment( $logo['id'] );
                $logo_alt = $logo_metadata['alt'] ? ' alt="' . $logo_metadata['alt'] . '"' : '';
            } else {
                $logo_alt = ' alt="' . get_bloginfo( 'name' ) . '"';
            }

            if ( $logo_type['ext'] == 'svg' ) {
                $logo_image = '<img src="' . $logo['url'] . '"' . $logo_alt . '>';
            } else {
                if ( !empty( $logo['id'] ) ) {
                    $logo_image = Volcanno::get_image( $logo['id'], array( 127 ), false, 'retina' );
                } else {
                    $logo_image = '<img src="' . $logo['url'] . '" alt="' . get_bloginfo( 'name' ) . '"">';
                }
            }

            $output = '<div id="logo"><a href="' . esc_url( apply_filters( 'volcanno_logo_home_url', home_url( '/' ) ) ) . '">' . $logo_image . '</a></div>';
        } else {
            // Default placeholder logo
            $logo_url = VOLCANNO_TEMPLATEURL . '/img/svg/consultingpress-logo-red.svg';
            $output = '<div id="logo"><a href="' . esc_url( apply_filters( 'volcanno_logo_home_url', home_url( '/' ) ) ) . '"><img src="' . $logo_url . '" alt="' . get_bloginfo( 'name' ) . '""></a></div>';
        }

        return $output;
    }

    /**
     * Blog content search form
     *
     * @return string
     */
    static function get_content_serch_form() {
        $form = '<form action="' . esc_url( home_url( '/' ) ) . '" method="get" id="header_form">
                 <input class="search-submit" type="submit" />
                 <input name="s" type="text" placeholder="Type and hit enter..." id="m_search" onkeydown="if (event.keyCode == 13) {
                                this.form.submit();
                                return false;
                            }"/>
                 </form>';

        return $form;
    }

    /**
     * Renders main menu in header.
     *
     * @return type string
     */
    static function generate_menu_html() {
        ob_start();

        wp_nav_menu( array(
            'theme_location' => apply_filters( 'volcanno_main_menu_location', 'primary' ),
            'container' => true,
            'items_wrap' => '<ul id="%1$s" class="nav navbar-nav pi-nav %2$s">%3$s</ul>',
            'walker' => new Volcanno_Theme_Menu_Walker (),
            'fallback_cb' => false
        ) );

        return ob_get_clean();
    }

    /**
     * Renders footer menu in header.
     *
     * @return type string
     */
    static function generate_footer_menu_html() {
        ob_start();

        wp_nav_menu( array(
            'theme_location' => 'footer',
            'container' => false,
            'items_wrap' => '<ul id="%1$s" class="%2$s footer-links">%3$s</ul>',
            'walker' => new Volcanno_Theme_Responsive_Menu_Walker (),
            'fallback_cb' => false,
        ) ); // argument added to distinguish regular menu with responsive

        return ob_get_clean();
    }

    /**
     * Paginate function for Blog and Portfolio
     *
     * @global object $wp_query
     * @global array $volcanno_options
     * @global object $wp_rewrite
     * @param string $location
     */
    static function pagination( $location, $query = '' ) {
        global $wp_query, $volcanno_options, $wp_rewrite;

        $wp_query = !empty( $query ) ? $query : $wp_query;

        $pages = '';
        $pagination = '';
        $max = $wp_query->max_num_pages;

        // if variable paged isn't set
        if ( !$current = get_query_var( 'paged' ) )
            $current = 1;

        // set parameters
        $args = array(
            'base' => str_replace( 999999999, '%#%', get_pagenum_link( 999999999 ) ),
            'format' => '',
            'total' => $max,
            'current' => $current,
            'show_all' => true,
            'type' => 'array',
            'prev_text' => '&larr;',
            'next_text' => '&rarr;',
            'prev_next' => false,
            'mid_size' => 3,
            'end_size' => 1
        );

        // previous and next links html
        $prev_page_link = $current == 1 ? "" : "<li><a class='fa fa-chevron-left' href='" . esc_url( get_pagenum_link( $current - 1 ) ) . "'></a></li>";
        $next_page_link = $current == $max ? "" : "<li><a class='fa fa-chevron-right' href='" . esc_url( get_pagenum_link( $current + 1 ) ) . "'></a></li>";

        // get page link
        $pagination_links = paginate_links( $args );

        // loop through pages
        if ( !empty( $pagination_links ) ) {
            foreach ( $pagination_links as $index => $link ) {

                $pagination .= "<li " . (($index + 1) == $current ? "class='active'>" : ">") . $link . "</li>";
            }
        }

        // if there is more then one page send html to browser
        if ( $max > 1 ) {
            if ( $location == 'portfolio' || $location == 'blog_timeline' ) {
                $container = 'div';
            } else {
                $container = 'li';
            }

            $pagination_html = "<{$container} class='pagination pagination-centered'>
                        <ul>
                        {$prev_page_link}
                        {$pagination}
                        {$next_page_link}
                        </ul>
                      </{$container}>";


            echo balanceTags( $pagination_html, true );
        }
    }

    /**
     * Template for comments and pingbacks.
     *
     * @param object $comment
     * @param array $args
     * @param int $depth
     */
    static function render_comments( $comment, $args, $depth ) {
        $GLOBALS ['comment'] = $comment;
        switch ( $comment->comment_type ) {
            case 'pingback' :
            case 'trackback' :
                ?>
                <li class="post pingback">
                    <p>
                    <?php esc_html_e( 'Pingback', 'consultingpress' ); ?><?php comment_author_link(); ?><?php edit_comment_link( esc_html__( 'Edit', 'consultingpress' ), '<span class="edit-link">', '</span>' ); ?>
                    </p>
                    <?php
                    break;
                default :
                    ?>

                <li id="li-comment-<?php comment_ID(); ?>">
                    <div id="comment-<?php comment_ID(); ?>" class="comment clearfix">
                        <div class="avatar-container">
                <?php echo get_avatar( $comment, 85, false, get_comment_author() ); ?>
                        </div><!-- .avatar-container end -->

                        <ul class="comment-meta">
                            <li>
                <?php echo get_comment_author_link(); ?>
                            </li>

                            <li class="date">
                <?php echo get_comment_date(); ?>
                            </li>
                        </ul><!-- .comment-meta end -->

                        <div class="comment-body">
                            <?php comment_text(); ?>
                <?php comment_reply_link( array_merge( $args, array( 'reply_text' => esc_html__( 'Reply', 'consultingpress' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
                        </div><!-- .comment-body end -->
                    </div><!-- .comment end -->

                    <?php
                    break;
            }
        }

        /**
         * Template for tags lists.
         *
         * @param $post_id
         */
        static function get_tags_lists( $post_id ) {
            $tags = get_the_tags( $post_id );

            $html = '';

            if ( !empty( $tags ) ) {
                $html .= '<ul class="post-tags"><li><span>' . esc_html__( 'Tags: ', 'consultingpress' ) . '</span></li>';

                foreach ( $tags as $key => $tag ) {
                    if ( $key == count( $tags ) - 1 ) {
                        $html .= '<li><a href="' . get_term_link( $tag->term_id ) . '" title="' . trim( $tag->name ) . '">' . trim( $tag->name ) . '</a></li>';
                    } else {
                        $html .= '<li><a href="' . get_term_link( $tag->term_id ) . '" title="' . trim( $tag->name ) . '">' . trim( $tag->name ) . '</a>, </li>';
                    }
                }

                $html .= '</ul>';
            }
            return $html;
        }

        /**
         * Template for categories lists.
         * 
         * @param $post_id
         * @param $no_icon for fontawesome control
         * @param $taxonomy for custom taxonomy
         */
        static function get_categories_lists( $post_id ) {
            $categories = get_the_category( $post_id );

            if ( is_array( $categories ) ) {

                $html = '<ul class="post-categories"><li><span>' . esc_html__( 'Categories: ', 'consultingpress' ) . '</span></li>';

                foreach ( $categories as $key => $cat ) {
                    if ( $key == count( $categories ) - 1 ) {
                        $html .= '<li><a href="' . get_category_link( $cat->cat_ID ) . '" title="' . $cat->name . '">' . $cat->name . '</a></li>';
                    } else {
                        $html .= '<li><a href="' . get_category_link( $cat->cat_ID ) . '" title="' . $cat->name . '">' . $cat->name . '</a>, </li>';
                    }
                }
                $html .= '</ul>';
            } else {
                $html = "";
            }
            return $html;
        }

        /**
         * Get tags for case studies
         * 
         * @param  $post_id 
         * @return string         
         */
        static function get_tags_case_studies( $post_id ) {
            $terms = wp_get_post_terms( $post_id, 'case-studies-category' );
            $count = count( $terms );
            $html = '';

            if ( $count > 0 ) {
                $html .= '<ul class="portfolio-tags"><li>';

                foreach ( $terms as $key => $value ) {

                    $term_id = $value->term_id;

                    $term = get_term_link( $term_id, 'case-studies-category' );

                    if ( $key < ($count - 1) ) {
                        $html .= '<a href="' . esc_url( $term ) . '">' . trim( $value->name ) . '</a><span> , </span>';
                    } else {
                        $html .= '<a href="' . esc_url( $term ) . '">' . trim( $value->name ) . '</a>';
                    }
                }

                $html .= '</li></ul>';
            }

            return $html;
        }

        /*
         * WPML language switcher
         *
         */

        static function generate_wpml_language_switcher() {

            // check if WPML is active
            if ( function_exists( 'icl_get_languages' ) ) {
                $languages = icl_get_languages( 'skip_missing=0&orderby=code' );

                if ( !empty( $languages ) ) {
                    $language_list = '';
                    ?>

                    <div class="wpml-languages enabled">
                        <?php
                        foreach ( $languages as $l ) {
                            $url = esc_url( $l['url'] );

                            if ( $l['active'] ) {
                                $active_lang = "<a class='active' href='{$url}'>
                                            <img src='{$l['country_flag_url']}' alt='{$l['native_name']}'/>
                                            <i class='fa fa-chevron-down'></i>
                                        </a>";
                            }

                            $language_list .= '<li><a href="' . esc_url( $l['url'] ) . '"><img src="' . $l['country_flag_url'] . '" height="12" alt="' . $l['language_code'] . '" width="18" /></a></li>';
                        }

                        echo wp_kses_post( $active_lang );
                        echo "<ul class='wpml-lang-dropdown'>";
                        echo wp_kses_post( $language_list );
                        echo "</ul>";
                        ?>

                    </div>
                    <?php
                }
            }
        }

        /**
         * Return blog post image depending on post type
         * 
         * @param  int $post_id
         * @return string     
         */
        static function return_blog_post_image( $post_id = '' ) {
            global $wp_embed;

            $post_image = Volcanno::get_image( $post_id, array( 360, 244 ), true, 'retina', true ); 

            if ( has_post_format( 'video', $post_id ) && empty( $post_image ) ) {
                $video_url = get_post_meta( $post_id, 'pt_post_format_video', true );
                $post_image = $wp_embed->run_shortcode( "[embed width='750']{$video_url}[/embed]" );
            } else {
                $post_image = Volcanno::get_image( $post_id, array( 750 ), false, 'responsive', true );
            }

            return $post_image;

        }

    }
    
