<?php 
    get_header(); 
    // Page title template
    get_template_part( 'template-parts/page', 'title' );

    // Parent page/category
    $main_category = get_the_terms( get_the_id(), 'case-studies-category');
    $main_category_id = !empty( $main_category[0]->term_id ) ? $main_category[0]->term_id : '';
    $parent_id = get_term_meta( $main_category_id, 'parent_case_studies_page', true ) ? : get_the_ID();

    // Sidebar position
    global $volcanno_page_style;
    $volcanno_page_style = get_post_meta( get_the_ID(), 'pt_custom_sidebar_placement', true ) == 'custom' ? get_post_meta( get_the_ID(), 'pt_sidebar', true ) : get_post_meta( $parent_id, 'pg_sidebar', true );
    $volcanno_page_style = empty( $volcanno_page_style ) ? Volcanno::return_theme_option( 'default_case_studies_sidebar_position' ) : $volcanno_page_style;
    $content_grid = $volcanno_page_style == 'fullwidth' ? 'col-md-12' : 'col-md-8';

    $show_featured_image = get_post_meta( get_the_ID(), 'pt_single_featured_image', true );

    $featured_image_size = $volcanno_page_style == 'fullwidth' ? '1140' : '750';

    if ( have_posts() ) : the_post(); ?>
    <!-- .page-conent start -->
    <div id="post-<?php the_ID(); ?>" <?php post_class('page-content'); ?>>

        <!-- .container start -->
        <div class="container">

            <!-- .row start -->
            <div class="row">
                <?php if ( $volcanno_page_style == 'left' ) get_sidebar(); ?>
                <ul class="<?php echo sanitize_html_class($content_grid); ?> blog-posts blog-grid blog-single clearfix">
                    <li class="post-container clearfix">
                        <?php if ( empty( $show_featured_image ) ) : ?>
                            <div class="post-media">
                                <?php echo Volcanno::get_image( get_the_ID(), array( $featured_image_size ), false, 'responsive', true); ?>
                            </div><!-- .post-media end -->
                        <?php endif; ?>

                        <div class="post-body">
                            <?php the_content(); ?>
                        </div><!-- .post-body end -->

                    </li><!-- .post-container end -->
                </ul>
                <?php if ( $volcanno_page_style == 'right' ) get_sidebar(); ?>
            </div><!-- .row end -->
        </div><!-- .container end -->
    </div><!-- .page-content end -->
    <?php endif;

get_footer(); ?>