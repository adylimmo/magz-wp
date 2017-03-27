<?php 
    global $volcanno_header_type;
    global $volcanno_parent_id;
    global $wp_query;
    global $post;
    global $volcanno_theme_config;
    
    // Get title
    if ( is_year() || is_month() || is_day() ) {
        $extra_title = esc_html__("Archive ", 'consultingpress');
        if (is_year()) {
            $title = esc_html__("for ", 'consultingpress') . get_the_date(_x('Y', 'yearly archives date format', 'consultingpress'));
        } else if (is_month()) {
            $title = esc_html__("for ", 'consultingpress') . get_the_date(_x('F Y', 'monthly archives date format', 'consultingpress'));
        } else if (is_day()) {
            $title = esc_html__("for ", 'consultingpress') . get_the_date();
        }
    }

    if ( is_tag() ) {
        $extra_title = esc_html__("Tag archive for ", 'consultingpress');
        $title = single_tag_title('', false);
    }

    if ( is_search() ) {
        $extra_title = esc_html__("Search results for ", 'consultingpress');
        $title = get_search_query();
    }

    if ( is_category() ) {
        $extra_title = esc_html__("Category", 'consultingpress'); 
        $title = single_cat_title('', false);
    }

    if ( is_archive() ) {
        $extra_title = esc_html__("Category", 'consultingpress');
        $title = single_cat_title('', false);
    }

    if ( is_tag() ) {
        $extra_title = esc_html__("Tag", 'consultingpress');
        $title = single_cat_title('', false);
    }

    if ( is_home() ) {
        if ( is_front_page() ) {
            $title = get_bloginfo('name');
            $extra_title = is_home() ? get_bloginfo('description') : '';
        } else {
            $page_id = ( 'page' == get_option('show_on_front') ? get_option('page_for_posts') : get_the_ID() );
            $title = get_post_meta( $page_id, 'pg_nice_title', true );
            $title = empty( $title ) ? get_the_title( $page_id ) : $title;
        }
    }

    if ( is_author() ) {
        $author_clean = get_query_var('author');
        $extra_title = esc_html__("Author Archive for ", 'consultingpress'); 
        $title = get_the_author_meta('display_name', $author_clean);
    }

    if ( is_page() || is_single() || is_singular() ) {
        $title = rwmb_meta('pg_nice_title') ?: get_the_title();
        $extra_title = rwmb_meta('pg_extra_title') ? esc_html( rwmb_meta('pg_extra_title') ) : '';
    }

    if ( is_404() ) {
        $title = Volcanno::return_theme_option('404_top_heading') ?: esc_html__('404 - Page not found', 'consultingpress');
    }

    if ( get_post_type() == '' ) {
        $extra_title = '';
    }

    if ( isset($post->post_type) ) :
        if ( $post->post_type == 'product' ) {
            $shop_id = get_option( 'woocommerce_shop_page_id' );
            $title = rwmb_meta('pg_nice_title', array(), $shop_id) ?: get_the_title( $shop_id );
            $extra_title = rwmb_meta('pg_extra_title', array(), $shop_id) ?: '';
            $page_title_image_id = get_post_meta( $shop_id, 'pg_title_image', true) ?: '';
        }
    endif;

    // Get page title image
    if ( get_post_meta( get_the_ID(), 'pg_title_image', true) != '' ) {
        $page_title_image_id = get_post_meta( get_the_ID(), 'pg_title_image', true );
    } else if ( get_post_meta( get_the_ID(), 'pt_title_image', true) != '' ) {
        $page_title_image_id = get_post_meta( get_the_ID(), 'pt_title_image', true );
    } else if ( get_post_meta( $volcanno_parent_id, 'pg_title_image', true) ) {
        $page_title_image_id = get_post_meta( $volcanno_parent_id, 'pg_title_image', true);
    } else if ( is_404() ) {
        $page_404_image = Volcanno::return_theme_option('404_heading_image');
        $page_title_image_id = !empty( $page_404_image['id'] ) ? $page_404_image['id'] : '';
    }

    // Wrap extra title in span tag
    $extra_title = !empty( $extra_title ) ? '<span>' . $extra_title . '</span>' : '';

    // If is disabled
    if ( rwmb_meta('pg_title_visibility') && !is_search() ) :
        return;

    // Finance & Tourism
    elseif ( $volcanno_header_type == 'finance' || $volcanno_header_type == 'tourism' ) :
        if ( isset( $page_title_image_id ) ) {
            $page_title_image = Volcanno::get_image($page_title_image_id, array(1920, 480), true);
        } ?>
        <div class="page-title page-title-style-01 <?php echo $volcanno_header_type == 'finance' ? 'left-aligned' : 'centered'; ?>">
            <?php echo isset($page_title_image) ? '<div class="pt-image">' . $page_title_image . '</div>' : ''; ?>
            <div class="pt-mask"></div>
            <!-- .container start -->
            <div class="container">
                <!-- .row start -->
                <div class="row">
                    <!-- .col-md-12 start -->
                    <div class="col-md-12">
                        <!-- .pt-heading start -->
                        <div class="pt-heading">
                            <?php echo wp_kses($extra_title, $volcanno_theme_config['allowed_html_tags']); ?>
                            <h1><?php echo esc_html( $title ); ?></h1>
                        </div><!-- .pt-heading end -->

                        <div class="breadcrumb-container">
                            <?php echo Volcanno::breadcrumbs(); ?>
                        </div><!-- .breadcrumb-container end -->
                    </div><!-- .col-md-12 end -->
                </div><!-- .row end -->
            </div><!-- .container end -->
        </div><!-- .page-title-style-01 end -->
    <?php
    // Management
    elseif ( $volcanno_header_type == 'management' ) :
        if ( isset( $page_title_image_id ) ) {
            $page_title_image = Volcanno::get_image($page_title_image_id, array(1920, 240), true);
        } ?>
        <div class="page-title page-title-style-02">
            <?php echo isset($page_title_image) ? '<div class="pt-image">' . $page_title_image . '</div>' : ''; ?>
            <div class="pt-mask-light"></div>
            <!-- .container start -->
            <div class="container">
                <!-- .row start -->
                <div class="row">
                    <!-- .col-md-6 start -->
                    <div class="col-md-6">
                        <!-- .pt-heading start -->
                        <div class="pt-heading">
                            <h1><?php echo esc_html( $title ); ?></h1>
                        </div><!-- .pt-heading end -->
                    </div><!-- .col-md-6 end -->
                    <!-- .col-md-6 start -->
                    <div class="col-md-6">
                        <!-- breadcrumbs start -->
                        <div class="breadcrumb-container clearfix">
                            <?php echo Volcanno::breadcrumbs(); ?>
                        </div><!-- .breadcrumb-container end -->
                    </div><!-- .col-md-6 end -->
                </div><!-- .row end -->
            </div><!-- .container end -->
        </div><!-- .page-title-style-01 end -->
    <?php
    // IT Security
    elseif ( $volcanno_header_type == 'it_security' ) : ?>

        <div class="page-title page-title-style-03">
            <!-- .container start -->
            <div class="container">
                <!-- .row start -->
                <div class="row">
                    <!-- .col-md-12 start -->
                    <div class="col-md-12">
                        <div class="breadcrumb-container">
                            <?php echo Volcanno::breadcrumbs(); ?>
                        </div><!-- .breadcrumb-container end -->
                    </div><!-- .col-md-12 end -->
                </div><!-- .row end -->
            </div><!-- .container end -->
        </div><!-- .page-title-style-03 end -->

<?php endif; ?>