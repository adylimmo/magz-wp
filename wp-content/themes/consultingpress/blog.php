<?php
/*
 * Template name: Blog
 */
get_header();

global $volcanno_page_style;
global $wp_embed;

// Sidebar placement
$volcanno_page_style = get_post_meta( get_the_ID(), 'pg_sidebar', true ) ? : Volcanno::return_theme_option( 'default_sidebar_position' );
$content_grid = $volcanno_page_style == 'fullwidth' ? 'col-md-12' : 'col-md-8';

// Blog style
$blog_style = rwmb_meta( 'pg_blog_options_style' ) ? : Volcanno::return_theme_option( 'default_blog_style' );

// Page title template
get_template_part( 'template-parts/page', 'title' );
?>
<!-- .page-content start -->
<div class="page-content">
    <!-- .container start -->
    <div class="container">
        <div class="row">

            <?php if ( $volcanno_page_style == 'left' ) get_sidebar(); ?>

            <div class="<?php echo sanitize_html_class( $content_grid ); ?>">

                <ul class="blog-posts <?php echo sanitize_html_class( $blog_style ); ?> clearfix">

                    <?php
                    // set the "paged" parameter (use 'page' if the query is on a static front page)
                    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

                    // Query
                    $args = array(
                        'post_type' => 'post',
                        'paged' => $paged,
                    );

                    // Blog pagination
                    $args['posts_per_page'] = rwmb_meta( 'pg_blog_pagination' ) ? rwmb_meta( 'pg_blog_pagination' ) : Volcanno::return_theme_option( 'default_blog_pagination' );

                    $blog_display_from_category = rwmb_meta( 'pg_blog_display_from_category' );

                    // Categories
                    if ( !empty( $blog_display_from_category ) && rwmb_meta( 'pg_blog_display_posts' ) == 'category' ) {
                        $args['category_name'] = implode( ',', rwmb_meta( 'pg_blog_display_from_category' ) );
                    }

                    // Create new query
                    $query = new WP_Query( apply_filters( 'volcanno_blog_query_args', $args ) );

                    if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();

                            $post_image = Volcanno_Partials::return_blog_post_image( get_the_ID() );

                            $post_image_exist = empty( $post_image ) ? ' no-post-image' : '';
                            ?>

                            <li class="post-container clearfix">
                                <div class="post-media">
                                    <a href="<?php esc_url( the_permalink() ); ?>">
                                        <?php echo Volcanno_Partials::return_blog_post_image( get_the_ID() ); ?>
                                    </a>
                                </div><!-- .post-media end -->
                                <div class="post-body <?php echo sanitize_html_class( $post_image_exist ); ?>">
                                    <a href="<?php esc_url( the_permalink() ); ?>" class="date"><?php echo get_the_date(); ?></a>
                                    <a href="<?php esc_url( the_permalink() ); ?>">
                                        <h3><?php the_title(); ?></h3>
                                    </a>
                                    <?php
                                    if ( $blog_style == 'blog-list' ) {
                                        echo Volcanno::generate_excerpt( 20, 'p' );
                                    }
                                    ?>
                                    <?php
                                    wp_link_pages( array(
                                        'before' => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'consultingpress' ) . '</span>',
                                        'after' => '</div>',
                                        'link_before' => '<span>',
                                        'link_after' => '</span>',
                                        'pagelink' => '<span class="screen-reader-text">' . esc_html__( 'Page', 'consultingpress' ) . ' </span>%',
                                        'separator' => '<span class="screen-reader-text">, </span>'
                                    ) );
                                    ?>
                                    <a href="<?php esc_url( the_permalink() ); ?>" class="read-more">
                                        <?php esc_html_e( 'Read more', 'consultingpress' ); ?>
                                    </a><!-- .read-more end -->  
                                </div><!-- .post-body end -->
                            </li><!-- .post-container end -->

                            <?php
                        endwhile;

                        echo Volcanno_Partials::pagination( 'blog', $query );
                        wp_reset_postdata();
                        wp_reset_query();
                        ?>
                    <?php else : ?>
                        <p><?php echo esc_html__( 'Sorry, no posts matched your criteria.', 'consultingpress' ); ?></p>
                    <?php endif; ?>
                </ul><!-- .col-md-12.blog-posts end -->
            </div>

            <?php if ( $volcanno_page_style == 'right' ) get_sidebar(); ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>