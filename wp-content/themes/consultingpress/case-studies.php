<?php
/*
 * Template name: Case Studies
 */
get_header();


global $volcanno_page_style;

// Sidebar placement
$volcanno_page_style = get_post_meta( get_the_ID(), 'pg_sidebar', true ) ? : Volcanno::return_theme_option( 'default_case_studies_sidebar_position' );
$content_grid = $volcanno_page_style == 'fullwidth' ? 'col-md-12' : 'col-md-8';

// Blog style
$blog_style = rwmb_meta( 'pg_case_studies_options_style' ) ? : Volcanno::return_theme_option( 'default_case_studies_style' );

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
                    // set the "paged" parameter (use 'page' if the query is on a static front page)
                    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

                    // Query
                    $args = array(
                        'post_type' => 'pi_case_studies',
                        'paged' => $paged,
                    );

                    // Blog pagination
                    $args['posts_per_page'] = rwmb_meta( 'pg_case_studies_pagination' ) ? rwmb_meta( 'pg_case_studies_pagination' ) : Volcanno::return_theme_option( 'default_case_studies_pagination' );

                    // All categories check
                    $case_studies_all_categories = rwmb_meta( 'pg_case_studies_display_posts' );
                    // Display posts category
                    $case_studies_categories = rwmb_meta( 'pg_case_studies_display_from_category' );

                    if ( $case_studies_all_categories == 'category' ) {
                        $args['tax_query'] = array(
                            'relation' => 'AND',
                            array(
                                'taxonomy' => 'case-studies-category',
                                'field' => 'slug',
                                'terms' => $case_studies_categories,
                            )
                        );
                    }

                    // Create new query
                    $query = new WP_Query( apply_filters( 'volcanno_case_studies_query_args', $args ) );

                    if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();

                            $items_style = '';
                            if ( $blog_style == 'cases-grid' ) {
                                $items_style[] = 'col-md-4';
                                $items_style[] = 'col-sm-6';
                            } else {
                                $items_style[] = 'col-md-12';
                            }
                            ?>

                            <li class="<?php echo join( ' ', $items_style ); ?>">
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
                        echo Volcanno_Partials::pagination( 'blog', $query );
                        wp_reset_postdata();
                        wp_reset_query();
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