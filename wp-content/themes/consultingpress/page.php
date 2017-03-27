<?php
get_header();
// Page title template
get_template_part( 'template-parts/page', 'title' );

global $volcanno_page_style;
$volcanno_page_style = get_post_meta( get_the_ID(), 'pg_sidebar', true ) ? : 'fullwidth';
$content_grid = $volcanno_page_style == 'fullwidth' ? 'col-md-12' : 'col-md-8';
?>
<!-- .page-content start -->
<div class="page-content">
    <!-- .container start -->
    <div class="container">
        <div class="row">
            <?php
            if ( have_posts() ) : the_post();
                if ( $volcanno_page_style == 'left' )
                    get_sidebar();
                ?>
                <div class="<?php echo sanitize_html_class( $content_grid ); ?> content-wrapper">
                    <?php the_content(); ?>
                </div>
                <?php comments_template(); ?>
                <?php if ( $volcanno_page_style == 'right' ) get_sidebar(); ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>