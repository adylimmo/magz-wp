<?php
/* -----------------------------------------------------------------------------------

  Plugin Name: Feature box Widget
  Plugin URI: http://www.pixel-industry.com
  Description: Widget that displays feature box.
  Version: 1.0
  Author: Pixel Industry
  Author URI: http://www.pixel-industry.com

  ----------------------------------------------------------------------------------- */


// Add function to widgets_init that'll load our widget
add_action( 'widgets_init', 'volcanno_register_feature_box' );

// Register widget
function volcanno_register_feature_box() {
    register_widget( 'Volcanno_Feature_Box' );
}

// Widget class
class Volcanno_Feature_Box extends WP_Widget {
    /* ----------------------------------------------------------------------------------- */
    /* 	Widget Setup
      /*----------------------------------------------------------------------------------- */

    function __construct() {

        // Widget settings
        $widget_options = array(
            'classname' => 'widget-feature-box',
            'description' => esc_html__( 'Widget that displays feature box.', 'consultingpress' )
        );


        // Create the widget
        parent::__construct( 'Volcanno_Feature_Box', esc_html__( 'Feature box', 'consultingpress' ), $widget_options );
    }

    /* ----------------------------------------------------------------------------------- */
    /* 	Display Widget
      /*----------------------------------------------------------------------------------- */

    function widget( $args, $instance ) {
        global $volcanno_theme_config;

        extract( $args );

        // Before widget (defined by theme functions file)
        echo $before_widget;

        $icon = !empty( $instance['icon'] ) ? '<div class="icon-container"><i class="' . strip_tags( $instance['icon'] ) . '"></i></div>' : '';
        $title = !empty( $instance['title'] ) ? '<h3>' . strip_tags( $instance['title'] ) . '</h3>' : '';
        $content = !empty( $instance['content'] ) ? '<p>' . strip_tags( $instance['content'] ) . '</p>' : '';
        $url = !empty( $instance['url'] ) ? $instance['url'] : '';
        $read_more = !empty( $instance['button_text'] ) ? '<a href="' . esc_url( $url ) . '" class="read-more">' . strip_tags( $instance['button_text'] ) . '</a>' : '';

        echo '<div class="feature-box custom-background bkg-color-dark dark">
                    ' . $icon . '
                    <div class="text-container">
                        ' . $title . '
                        ' . $content . '
                        ' . $read_more . '
                    </div>
                </div>';

        // After widget (defined by theme functions file)
        echo $after_widget;
    }

    /* ----------------------------------------------------------------------------------- */
    /* 	Update Widget
      /*----------------------------------------------------------------------------------- */

    function update( $new_instance, $old_instance ) {
        global $volcanno_theme_config;

        $instance = $old_instance;

        // Strip tags to remove HTML (important for text inputs)
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['icon'] = strip_tags( $new_instance['icon'] );
        $instance['content'] = wp_kses( $new_instance['content'], $volcanno_theme_config['content'] );
        $instance['url'] = esc_url( $new_instance['url'] );
        $instance['button_text'] = strip_tags( $new_instance['button_text'] );

        // No need to strip tags

        return $instance;
    }

    /* ----------------------------------------------------------------------------------- */
    /* 	Widget Settings (Displays the widget settings controls on the widget panel)
      /*----------------------------------------------------------------------------------- */

    function form( $instance ) {
        global $volcanno_theme_config;

        // Set up some default widget settings
        $defaults = array(
            'title' => 'Request a quote',
            'icon' => 'lynny-pages-1',
            'content' => 'Get professional guidance for starting up and management of successful business.',
            'url' => '',
            'button_text' => 'Contact us',
        );

        $instance = wp_parse_args( ( array ) $instance, $defaults );
        ?>

        <!-- Widget Title: Text Input -->
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'consultingpress' ) ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo sanitize_text_field( $instance['title'] ); ?>" />
        </p>

        <!-- Icon: Text Input -->
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>"><?php esc_html_e( 'Icon:', 'consultingpress' ) ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'icon' ) ); ?>" value="<?php echo sanitize_text_field( $instance['icon'] ); ?>" />
            <em><?php echo esc_html__( 'Font classes list can be found in Documentation directory.', 'consultingpress' ); ?></em>
        </p>

        <!-- Content: Textarea -->
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'content' ) ); ?>"><?php esc_html_e( 'Content:', 'consultingpress' ) ?></label>
            <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'content' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'content' ) ); ?>"><?php echo esc_textarea( $instance['content'] ); ?></textarea>
        </p>


        <!-- Url Text Input -->
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'url' ) ); ?>"><?php esc_html_e( 'Url:', 'consultingpress' ) ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'url' ) ); ?>" value="<?php echo esc_url( $instance['url'] ); ?>" />
        </p>

        <!-- Button Text: Text Input -->
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>"><?php esc_html_e( 'Button Text:', 'consultingpress' ) ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button_text' ) ); ?>" value="<?php echo sanitize_text_field( $instance['button_text'] ); ?>" />
        </p>

        <?php
    }

}
?>