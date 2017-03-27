<?php
get_header();
// Page title template
global $volcanno_header_type;
get_template_part( 'template-parts/page', 'title' );

global $volcanno_page_style;
$volcanno_page_style = Volcanno::return_theme_option( 'shop_sidebar_position' );
if ( is_product() || is_product_tag() || is_product_category() ) {
    $volcanno_page_style = 'fullwidth';
}
$content_grid = $volcanno_page_style == 'fullwidth' ? 'col-md-12' : 'col-md-8';
?>
<!-- .page-content start -->
<div class="page-content">
    <!-- .container start -->
    <div class="container">
        <div class="row">
            <?php if ( $volcanno_page_style == 'left' ) get_sidebar(); ?>
            <div class="<?php echo sanitize_html_class( $content_grid ); ?> content-wrapper">
                <?php woocommerce_content(); ?>
            </div>
            <?php if ( $volcanno_page_style == 'right' ) get_sidebar(); ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>