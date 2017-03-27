<?php
/*
 * The template for displaying the footer.
 */
?>
<!-- #footer-wrapper start -->
<div id="footer-wrapper">

    <!-- #footer start -->
    <footer id="footer">
        <div class="container">

            <?php if ( apply_filters( 'volcanno_footer_top_sidebar', Volcanno::return_theme_option( 'footer_top_widget' ) ) ) : ?>
                <!-- .row start -->
                <div class="row">
                    <!-- .footer-widget-container start -->
                    <ul class="footer-widget-container col-md-12">
                        <?php
                        if ( is_active_sidebar( 'volcanno-footer-sidebar-top' ) ) {
                            dynamic_sidebar( 'volcanno-footer-sidebar-top' );
                        } ?>
                    </ul><!-- .footer-widget-container end -->
                </div><!-- .row end -->
            <?php endif; ?>

            <!-- .row start -->
            <div class="row">

                <?php $number_of_sidebar = Volcanno::return_theme_option( 'footer_widget_areas' ) ? : 4; ?>

                <?php
                $grid_md = 12 / $number_of_sidebar;
                for ( $i = 1; $i <= $number_of_sidebar; $i++ ) {
                    $sidebar_id = 'volcanno-footer-sidebar-' . ( $i + 1 );

                    if ( is_active_sidebar( $sidebar_id ) ) {
                        echo '<ul class="footer-widget-container col-md-' . $grid_md . ' col-sm-6">';
                        dynamic_sidebar( $sidebar_id );
                        echo '</ul>';
                    }
                } ?>
            </div><!-- .row end -->
        </div><!-- .container end -->
    </footer><!-- #footer end -->

    <?php get_template_part( 'template-parts/footer', 'copyright' ); ?>

    <?php echo Volcanno_Partials::scrool_to_top(); ?>
</div><!-- #footer-wrapper end -->
<?php wp_footer(); ?>
</body>
</html>