<?php get_header();

global $volcanno_page_style;
global $volcanno_header_type;
global $volcanno_parent_id;
global $wp_embed;
global $wp_query;

// Page title template
get_template_part( 'template-parts/page', 'title' );

// Sidebar position
$volcanno_page_style = get_post_meta( $volcanno_parent_id, 'pg_sidebar', true ) ? : Volcanno::return_theme_option( 'default_sidebar_position' );
$content_grid = $volcanno_page_style == 'fullwidth' ? 'col-md-12' : 'col-md-8';

$featured_image_size = $volcanno_page_style == 'fullwidth' ? '1140' : '750';

if ( have_posts() ) : the_post();
    ?>
    <!-- .page-conent start -->
    <div id="post-<?php the_ID(); ?>" <?php post_class( 'page-content' ); ?>>

        <!-- .container start -->
        <div class="container">

            <!-- .row start -->
            <div class="row">
                <?php if ( $volcanno_page_style == 'left' ) get_sidebar(); ?>
                <ul class="<?php echo sanitize_html_class( $content_grid ); ?> blog-posts blog-grid blog-single clearfix">
                    <li class="post-container clearfix">
                        <div class="post-media">
                            <?php
                            // Check if has post format
                            if ( has_post_format( 'video' ) ) {
                                $video_url = get_post_meta( get_the_id(), 'pt_post_format_video', true );
                                echo $wp_embed->run_shortcode( "[embed width='" . $featured_image_size . "']" . esc_url( $video_url ) . "[/embed]" );
                            } else {
                                echo Volcanno::get_image( get_the_ID(), array( $featured_image_size ), false, 'responsive', true );
                            }
                            ?>
                        </div><!-- .post-media end -->

                        <div class="post-body clearfix">
                            <span class="date"><?php the_date(); ?></span>
                            <?php the_content(); ?>
                            <?php echo Volcanno_Partials::get_tags_lists( get_the_id() ); ?>
                            <?php echo Volcanno_Partials::get_categories_lists( get_the_id() ); ?>
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
                        </div><!-- .post-body end -->

                        <?php get_template_part( 'template-parts/post', 'author' ); ?>

                        <?php comments_template(); ?>
                    </li><!-- .post-container end -->
                </ul>
            <?php if ( $volcanno_page_style == 'right' ) get_sidebar(); ?>
            </div><!-- .row end -->
        </div><!-- .container end -->
    </div><!-- .page-content end -->
    <?php get_template_part( 'template-parts/post', 'pagination' ); ?>

<?php endif;

get_footer();
?>