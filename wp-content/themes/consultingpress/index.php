<?php
get_header();

global $wp_query;
global $volcanno_header_type;
global $volcanno_page_style;
global $wp_embed;
global $volcanno_parent_id;
global $post;

// Get category id
$category_id = get_query_var( 'cat' );
// Set id of parent if exist else set default
$volcanno_parent_id = get_term_meta( $category_id, 'parent_blog_page', true ) ? : get_the_ID();

// Sidebar placement
$volcanno_page_style = get_post_meta( $volcanno_parent_id, 'pg_sidebar', true ) ? : Volcanno::return_theme_option( 'default_sidebar_position' );
$content_grid = $volcanno_page_style == 'fullwidth' ? 'col-md-12' : 'col-md-8';
    
// Blog style
$blog_style = rwmb_meta( 'pg_blog_options_style', '', $volcanno_parent_id ) ? : Volcanno::return_theme_option( 'default_blog_style' );
$blog_style = is_search() ? Volcanno::return_theme_option( 'default_blog_style' ) : $blog_style;

$post_classes = apply_filters( 'volcanno_post_classes', array('clearfix') );

if ( !VOLCANNO_REDUX_FRAMEWORK ) {
    $blog_style = 'blog-list';
    $volcanno_page_style = 'right';
    $volcanno_parent_id = '0';
}
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
                    if ( have_posts() ) : while ( have_posts() ) : the_post();

                            $post_image = Volcanno_Partials::return_blog_post_image( get_the_ID() );

                            $post_image_exist = empty( $post_image ) ? ' no-post-image' : '';
                            ?>

                            <li id="post-<?php the_ID(); ?>" <?php post_class( $post_classes ) ?>>
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
                                        if ( preg_match( '/<!--more(.*?)?-->/', $post->post_content ) ) {
                                            the_content();
                                        } else {
                                            echo Volcanno::generate_excerpt( 20, 'p' );
                                        }
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