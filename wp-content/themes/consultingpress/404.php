<?php
get_header();
// Page title template
get_template_part( 'template-parts/page', 'title' );

// 404 page message
$message = Volcanno::return_theme_option( '404_message' ) ? : esc_html__( "We're sorry, we couldn't find the page you're looking for. The reason might be that it doesn't exist anymore, it has moved elsewhere or the address is not correct.", 'consultingpress' );
?>
<!-- .page-content start -->
<div class="page-content">
    <!-- .container start -->
    <div class="container">
        <div class="row">
            <div class="content-wrapper">
                <div class="404-page-wrapper clearfix">
                    <div class="col-md-2 icon-404"><i class="fa fa-ban"></i></div>
                    <div class="col-md-10">
                        <p><?php echo esc_html( $message ); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php get_footer(); ?>