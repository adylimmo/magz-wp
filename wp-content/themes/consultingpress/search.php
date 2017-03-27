<?php
get_header();

global $volcanno_page_style;

// Get category id
$category_id = get_query_var( 'cat' );
// Set id of parent if exist else set default
$page_id = get_term_meta( $category_id, 'parent_blog_page', true ) ? : get_the_id();

// Sidebar placement
$volcanno_page_style = get_post_meta( $page_id, 'pg_sidebar', true ) ? : Volcanno::return_theme_option( 'default_sidebar_position' );
$content_grid = $volcanno_page_style == 'fullwidth' ? 'col-md-12' : 'col-md-8';

// Blog style
$blog_style = rwmb_meta( 'pg_blog_options_style', '', $page_id ) ? : Volcanno::return_theme_option( 'default_blog_style' );
$blog_style = is_search() ? Volcanno::return_theme_option( 'default_blog_style' ) : $blog_style;

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

                <ul class="col-md-12 blog-posts <?php echo sanitize_html_class( $blog_style ); ?> clearfix">
                    <?php
                    global $wp_query;

                    if ( have_posts() ) : while ( have_posts() ) : the_post();

                            $post_image = Volcanno::get_image( get_the_ID(), array( 360, 244 ), true, 'retina', true );
                            $post_image_exist = empty( $post_image ) ? ' no-post-image' : '';
                            ?>

                            <li class="post-container clearfix">
                                <div class="post-media">
                                    <a href="<?php esc_url( the_permalink() ); ?>">
                                        <?php echo Volcanno::get_image( get_the_ID(), array( 360, 244 ), true, 'retina', true ); ?>
                                    </a>
                                </div><!-- .post-media end -->
                                <div class="post-body <?php echo sanitize_html_class( $post_image_exist ); ?>">
                                    <span class="date"><?php echo get_the_date(); ?></span>
                                    <a href="<?php esc_url( the_permalink() ); ?>">
                                        <h3><?php the_title(); ?></h3>
                                    </a>
                                    <?php
                                    if ( $blog_style == 'blog-list' ) {
                                        echo Volcanno::generate_excerpt( 20, 'p' );
                                    }
                                    ?>
                                    <a href="<?php esc_url( the_permalink() ); ?>" class="read-more">
                                        <?php esc_html_e( 'Read more', 'consultingpress' ); ?>
                                    </a><!-- .read-more end -->  
                                </div><!-- .post-body end -->
                            </li><!-- .post-container end -->

                        <?php endwhile; ?>

                        <?php echo Volcanno_Partials::pagination( 'blog' ); ?>

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