<?php
global $volcanno_theme_config;

if ( Volcanno::return_theme_option('footer_copyright') || !VOLCANNO_REDUX_FRAMEWORK ) {

    // Define fields
    $copyright_left = VOLCANNO_REDUX_FRAMEWORK ? Volcanno::return_theme_option('footer_copyright_left') : esc_html__( 'Copyright © 2017 Consulting Press', 'consultingpress' );
    $copyright_right = VOLCANNO_REDUX_FRAMEWORK ? Volcanno::return_theme_option('footer_copyright_right') : esc_html__( 'Design and development by ', 'consultingpress' ) . '<a href="' . esc_url('http://www.pixel-industry.com') . '">' . esc_html__('Pixel Industry', 'consultingpress') . '</a>';

    $output =   '<div id="copyright-container" class="copyright-container">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <p>' . wp_kses( $copyright_left, $volcanno_theme_config['allowed_html_tags'] ) . '</p>
                            </div>
                            <div class="col-md-6 col-sm-6 copyright-right">
                                <p>' . wp_kses( $copyright_right, $volcanno_theme_config['allowed_html_tags'] ) . '</p>
                            </div>
                        </div>
                    </div>
                </div>'; 
    echo $output;
}