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
add_action('widgets_init', 'volcanno_register_testimonial_carousel');

// Register widget
function volcanno_register_testimonial_carousel() {
    register_widget('Volcanno_Testimonial_Carousel');
}

// Widget class
class Volcanno_Testimonial_Carousel extends WP_Widget {
    
    /* ----------------------------------------------------------------------------------- */
    /*  Widget Setup
    /*----------------------------------------------------------------------------------- */

    function __construct() {

        // Widget settings
        $widget_options = array(
            'classname' => 'testimonial-caousel',
            'description' => esc_html__('Widget that displays testimonial carousel.', 'consultingpress')
        );


        // Create the widget
        parent::__construct('Volcanno_Testimonial_Carousel', esc_html__('Testimonial Carousel', 'consultingpress'), $widget_options);
    }

    /* ----------------------------------------------------------------------------------- */
    /*  Display Widget
    /*----------------------------------------------------------------------------------- */

    function widget($args, $instance) {
        global $volcanno_theme_config;
        
        extract($args);

        // Enqueue styles
        wp_enqueue_style( 'owl-carousel' );
        // Enqueue scripts
        wp_enqueue_script( 'owl-carousel' );
        // Initialize owl carousel
        wp_enqueue_script( 'owl-carousel-init' );

        // Our variables from the widget settings
        $title = apply_filters('widget_title', $instance['title']);

        // Before widget (defined by theme functions file)
        echo $before_widget;

        // Display the widget title if one was input
        if ($title)
            echo $before_title . $title . $after_title;

        // Get properties
        $owl_items = !empty( $instance['content'] ) ? $instance['content'] : '';

        // Extract attributes and values from shortcode
        preg_match_all("/\[\s?(.*?)=\"([\s\S]*?)\"\s*(.*?)=\"([\s\S]*?)\"\s?\]/", $owl_items, $owl_items_atts);

        $slides = '';

        foreach ($owl_items_atts[0] as $index => $shortcode ) {
            $atts = array(
                $owl_items_atts[1][$index] => $owl_items_atts[2][$index],
                $owl_items_atts[3][$index] => $owl_items_atts[4][$index]
            );

            $text = !empty( $atts['text'] ) ? $atts['text'] : '';
            $author = !empty( $atts['author'] ) ? $atts['author'] : '';

            $slides .=  '<div class="owl-item">
                            <div class="testimonial-style-02">
                                <p>' . strip_tags( $text ) . '</p>
                                <span class="author">' . wp_kses( $author, $volcanno_theme_config['allowed_html_tags'] ) . '</span>
                            </div>
                        </div>';
        }

        // Echo html
        echo '<div class="carousel-container">
                    <div class="owl-carousel testimonial-carousel-04" data-type="testimonial-carousel-04">
                        ' . $slides . '
                    </div>
                </div>';

        // After widget (defined by theme functions file)
        echo $after_widget;
    }

    /* ----------------------------------------------------------------------------------- */
    /*  Update Widget
    /*----------------------------------------------------------------------------------- */

    function update($new_instance, $old_instance) {
        global $volcanno_theme_config;
        
        $instance = $old_instance;

        // Strip tags to remove HTML (important for text inputs)
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['content'] = $new_instance['content'];

        return $instance;
    }

    /* ----------------------------------------------------------------------------------- */
    /*  Widget Settings (Displays the widget settings controls on the widget panel)
    /*----------------------------------------------------------------------------------- */

    function form($instance) {
        global $volcanno_theme_config;

        // Set up some default widget settings
        $defaults = array(
            'title' => 'What our clients say',
            'content' => '',
            'url' => '',
            'button_text' => 'Contact us',
        );

        $instance = wp_parse_args((array) $instance, $defaults);
        ?>

        <!-- Widget Title: Text Input -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'consultingpress') ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo sanitize_text_field($instance['title']); ?>" />
        </p>

        <!-- Content: Textarea -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('content')); ?>"><?php esc_html_e('Content:', 'consultingpress') ?></label>
            <textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('content')); ?>" name="<?php echo esc_attr($this->get_field_name('content')); ?>"><?php echo $instance['content']; ?></textarea>
            <em><?php echo esc_html__('Each testimonial enter in format [text="Sample text" author="Sample author"]', 'consultingpress'); ?></em>
        </p>

        <?php
    }    

}
?>