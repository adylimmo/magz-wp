<?php
get_header();
global $volcanno_page_style;
global $volcanno_parent_id;
global $wp_query;

// Get term id
$term = get_query_var( 'term' );
$term_object = get_term_by( 'slug', $term, 'case-studies-category' );
$term_id = $term_object->term_id;
// Set id of parent if exist else set default
$volcanno_parent_id = get_term_meta( $term_id, 'parent_case_studies_page', true ) ? : get_the_ID();
// Sidebar placement
$volcanno_page_style = get_post_meta( $volcanno_parent_id, 'pg_sidebar', true ) ? : Volcanno::return_theme_option( 'default_case_studiessidebar_position' );
$content_grid = $volcanno_page_style == 'fullwidth' ? 'col-md-12' : 'col-md-8';

// Blog style
$blog_style = rwmb_meta( 'pg_case_studies_options_style', '', $volcanno_parent_id ) ? : Volcanno::return_theme_option( 'default_case_studies_style' );

// Page title template
get_template_part( 'template-parts/page', 'title' );
?>
<!-- .page-content start -->
<div class="page-content">
    <!-- .container start -->
    <div class="container">

        <div class="row portfolio-items-holder">

            <?php if ( $volcanno_page_style == 'left' ) get_sidebar(); ?>

            <div class="<?php echo sanitize_html_class( $content_grid ); ?>">

                <?php get_template_part( 'template-parts/page', 'filters' ); ?> 

                <ul id="portfolioitems" class="<?php echo sanitize_html_class( $blog_style ); ?> clearfix">
                    <?php
                    global $wp_query;

                    // set the "paged" parameter (use 'page' if the query is on a static front page)
                    $paged = 1;

                    if ( get_query_var( 'paged' ) )
                        $paged = get_query_var( 'paged' );
                    if ( get_query_var( 'page' ) )
                        $paged = get_query_var( 'page' );

                    if ( have_posts() ) : while ( have_posts() ) : the_post();

                            global $post;

                            $items_style = $blog_style == 'cases-grid' ? 'col-md-4 col-sm-6' : 'col-md-12';
                            ?>

                            <li class="<?php echo sanitize_html_class( $items_style ); ?>">
                                <div class="portfolio-item">
                                    <div class="media">
                                        <a href="<?php esc_url( the_permalink() ); ?>">
                                            <?php echo Volcanno::get_image( get_the_ID(), array( 360, 190 ), true, 'retina', true ); ?>
                                        </a>
                                    </div><!-- .media end -->

                                    <div class="body">
                                        <a href="<?php esc_url( the_permalink() ); ?>">
                                            <h2><?php the_title(); ?></h2>
                                        </a>

                                        <?php echo Volcanno_Partials::get_tags_case_studies( get_the_ID() ); ?>

                                        <?php if ( $blog_style == 'cases-list' ) : ?>
                                            <a href="<?php esc_url( the_permalink() ); ?>" class="read-more"><?php esc_html_e( "View case study", 'consultingpress' ); ?></a>
                                        <?php endif; ?>
                                    </div><!-- .body end -->
                                </div><!-- .portfolio-item end -->
                            </li>

                            <?php
                        endwhile;
                        echo Volcanno_Partials::pagination( 'blog', $wp_query );
                        wp_reset_postdata();
                        ?>
                    <?php else : ?>
                        <p><?php echo esc_html__( 'Sorry, no posts matched your criteria.', 'consultingpress' ); ?></p>
                    <?php endif; ?>
                </ul><!-- #portfolioitems end -->
            </div>
            <?php if ( $volcanno_page_style == 'right' ) get_sidebar(); ?>
        </div><!-- .row end -->
    </div><!-- .container end -->
</div><!-- .page-content end -->

<?php get_footer(); ?>