<?php
/**
 * The Sidebar containing the main widget area.
 *
 */
global $volcanno_page_title;
$aside_classes = array( 'col-md-4' );

if ( is_home() ) {
    $page_id = ( 'page' == get_option( 'show_on_front' ) ? get_option( 'page_for_posts' ) : get_the_ID() );
    $volcanno_page_style = get_post_meta( $page_id, 'pg_sidebar', true );
} else if ( VOLCANNO_WOOCOMMERCE && is_shop() ) {
    $volcanno_page_style = Volcanno::return_theme_option( 'shop_sidebar_position' );
} else if ( VOLCANNO_WOOCOMMERCE && (is_product() || is_product_tag() || is_product_category()) ) {
    $volcanno_page_style = Volcanno::return_theme_option( 'shop_sidebar_position' );
} else {
    global $volcanno_page_style;
}

if ( $volcanno_page_style == 'left' ) {
    $aside_classes[] = 'aside-left';
} else if ( $volcanno_page_style == 'right' ) {
    $aside_classes[] = 'aside-right';
}

// get animation classes
$animation = Volcanno::return_theme_option( 'blog_sidebar_animation' );
if ( !empty( $animation ) && $animation != "disabled" ) {
    $aside_classes[] = 'triggerAnimation';
    $aside_classes[] = 'animated';
    $aside_classes[] = $animation;
}
?>
<!-- sidebar start -->
<aside class="<?php echo join( ' ', $aside_classes ); ?>">
    <div class="aside-widgets">
<div class="sidebar">
<?php dynamic_sidebar('sidebar2'); ?>
</div>
        <?php
        wp_reset_postdata();
        global $post;

        if ( is_page() ) {

            $page_id = $post->ID;

            $volcanno_page_sidebar_generator = get_post_meta( $page_id, 'pg_sidebar_generator', true );
            $volcanno_page_sidebar_name = get_post_meta( $page_id, 'pg_sidebar_name', true );

            if ( !empty( $volcanno_page_sidebar_generator ) && $volcanno_page_sidebar_generator == 'existing' && !empty( $volcanno_page_sidebar_name ) ) {
                $page_sidebar_id = $volcanno_page_sidebar_name;
            } else {
                $page_sidebar_id = "volcanno-page-sidebar-" . $page_id;
            }
        } else if ( VOLCANNO_WOOCOMMERCE && is_shop() ) {
            $page_sidebar_id = "volcanno-shop-sidebar";
        } else if ( VOLCANNO_WOOCOMMERCE && (is_product() || is_product_tag() || is_product_category()) ) {
            $page_sidebar_id = 'volcanno-shop-sidebar';
        } else if ( is_singular( 'event' ) && is_single() ) {
            $page_sidebar_id = 'volcanno-events-sidebar';
        } else if ( is_category() ) {
            global $volcanno_parent_id;

            $volcanno_page_sidebar_generator = get_post_meta( $volcanno_parent_id, 'pg_sidebar_generator', true );
            $volcanno_page_sidebar_name = get_post_meta( $volcanno_parent_id, 'pg_sidebar_name', true );

            if ( !empty( $volcanno_page_sidebar_generator ) && $volcanno_page_sidebar_generator == 'existing' && !empty( $volcanno_page_sidebar_name ) ) {
                $page_sidebar_id = $volcanno_page_sidebar_name;
            } else {
                $page_sidebar_id = "volcanno-page-sidebar-" . $volcanno_parent_id;
            }
        } else if ( is_home() ) {

            if ( 'page' == get_option( 'show_on_front' ) ) {
                $page_id = ( 'page' == get_option( 'show_on_front' ) ? get_option( 'page_for_posts' ) : get_the_ID() );

                $volcanno_page_sidebar_generator = get_post_meta( $page_id, 'pg_sidebar_generator', true );
                $volcanno_page_sidebar_name = get_post_meta( $page_id, 'pg_sidebar_name', true );

                if ( !empty( $volcanno_page_sidebar_generator ) && $volcanno_page_sidebar_generator == 'existing' && !empty( $volcanno_page_sidebar_name ) ) {
                    $page_sidebar_id = $volcanno_page_sidebar_name;
                } else {
                    $page_sidebar_id = "volcanno-page-sidebar-" . $page_id;
                }
                $volcanno_page_sidebar_name = get_post_meta( $page_id, 'pg_sidebar_name', true );
            } else {
                $page_sidebar_id = "volcanno-blog-sidebar";
            }
        } else {
            global $volcanno_parent_id;
            if ( !empty( $volcanno_parent_id ) ) {
                $page_sidebar_id = "volcanno-page-sidebar-" . $volcanno_parent_id;
            } else {
                $page_sidebar_id = 'volcanno-blog-sidebar';
            }
        }
        if ( is_active_sidebar( $page_sidebar_id ) ) {
            dynamic_sidebar( $page_sidebar_id );
        }
        ?>
    </div>
</aside>
<!-- sidebar end -->
