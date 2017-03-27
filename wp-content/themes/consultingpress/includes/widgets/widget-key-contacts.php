<?php
/* -----------------------------------------------------------------------------------

  Plugin Name: Key contacts Widget
  Plugin URI: http://www.pixel-industry.com
  Description: Widget that displays key contacts.
  Version: 1.0
  Author: Pixel Industry
  Author URI: http://www.pixel-industry.com

  ----------------------------------------------------------------------------------- */


// Add function to widgets_init that'll load our widget
add_action('widgets_init', 'volcanno_register_key_contacts');

// Register widget
function volcanno_register_key_contacts() {
    register_widget('Volcanno_Key_Contacts');
}

// Widget class
class Volcanno_Key_Contacts extends WP_Widget {
    
    /* ----------------------------------------------------------------------------------- */
    /* 	Widget Setup
      /*----------------------------------------------------------------------------------- */

    function __construct() {

        // Widget settings
        $widget_options = array(
            'classname' => 'widget-key-contacts',
            'description' => esc_html__('Widget that displays key contacts.', 'consultingpress')
        );


        // Create the widget
        parent::__construct('Volcanno_Key_Contacts', esc_html__('Key contact', 'consultingpress'), $widget_options);
    }

    /* ----------------------------------------------------------------------------------- */
    /* 	Display Widget
      /*----------------------------------------------------------------------------------- */

    function widget($args, $instance) {
        global $volcanno_theme_config;
        
        extract($args);

        // Our variables from the widget settings
        $title = apply_filters( 'widget_title', $instance['title'] );

        // Before widget (defined by theme functions file)
        echo $before_widget;

        // Display the widget title if one was input
        if ( $title ) {
            echo $before_title . $title . $after_title;
        }

        // Image
        $image_id = !empty( $instance['image_id'] ) ? strip_tags( $instance['image_id'] ) : '';
        $image = Volcanno_Visual_Composer::get_image( $image_id, array(263), true, 'retina');
        // Name
        $name = !empty( $instance['name'] ) ? '<h4>' . strip_tags( $instance['name'] ) . '</h4>' : '';
        // Position
        $position = !empty( $instance['position'] ) ? '<span class="position">' . strip_tags( $instance['position'] ) . '</span>' : '';
        // Contact info first
        $first_info_icon = !empty( $instance['first_info_icon'] ) ? '<i class="' . strip_tags( $instance['first_info_icon'] ) . '"></i>' : '';
        $first_info_text = !empty( $instance['first_info_text'] ) ? strip_tags( $instance['first_info_text'] ) : '';
        $first_info_text = !empty( $instance['first_info_url'] ) ? '<p><a href="' . esc_url( $instance['first_info_url'] ) . '">' . $first_info_text . '</a></p>' : '<p>' . $first_info_text . '</p>';
        $info_first = ( !empty($first_info_text) || !empty($first_info_icon) ) ? '<li>' . $first_info_icon . $first_info_text . '</li>' : '';
        // Contact info second
        $second_info_icon = !empty( $instance['second_info_icon'] ) ? '<i class="' . strip_tags( $instance['second_info_icon'] ) . '"></i>' : '';
        $second_info_text = !empty( $instance['second_info_text'] ) ? strip_tags( $instance['second_info_text'] ) : '';
        $second_info_text = !empty( $instance['second_info_url'] ) ? '<p><a href="' . esc_url( $instance['second_info_url'] ) . '">' . $second_info_text . '</a></p>' : '<p>' . $second_info_text . '</p>';
        $info_second = ( !empty($second_info_text) || !empty($second_info_icon) ) ? '<li>' . $second_info_icon . $second_info_text . '</li>' : '';

        echo '<div class="key-contacts"><ul class="clearfix"><li>' . $image . '<div class="text-container"><div class="contacts-title">' . $name . $position . '</div><ul class="fa-ul default clearfix">' . $info_first . $info_second . '</ul></div></li></ul></div>';
        // After widget (defined by theme functions file)
        echo $after_widget;
    }

    /* ----------------------------------------------------------------------------------- */
    /* 	Update Widget
      /*----------------------------------------------------------------------------------- */

    function update($new_instance, $old_instance) {
        global $volcanno_theme_config;
        
        $instance = $old_instance;

        // Strip tags to remove HTML (important for text inputs)
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['image_id'] = strip_tags( $new_instance['image_id'] );
        $instance['name'] = strip_tags( $new_instance['name'] );
        $instance['position'] = strip_tags( $new_instance['position'] );
        $instance['first_info_icon'] = strip_tags( $new_instance['first_info_icon'] );
        $instance['first_info_text'] = strip_tags( $new_instance['first_info_text'] );
        $instance['first_info_url'] = strip_tags( $new_instance['first_info_url'] );
        $instance['second_info_icon'] = strip_tags( $new_instance['second_info_icon'] );
        $instance['second_info_text'] = strip_tags( $new_instance['second_info_text'] );
        $instance['second_info_url'] = strip_tags( $new_instance['second_info_url'] );

        // No need to strip tags
        return $instance;
    }

    /* ----------------------------------------------------------------------------------- */
    /* 	Widget Settings (Displays the widget settings controls on the widget panel)
    /*----------------------------------------------------------------------------------- */

    function form($instance) {
        global $volcanno_theme_config;

        // Set up some default widget settings
        $defaults = array(
            'title' => esc_html__( 'Key contacts', 'consultingpress' ),
            'image_id' => '',
            'name' => esc_html__('Joshua Turner', 'consultingpress'),
            'position' => esc_html__('Management Consultant', 'consultingpress'),
            'first_info_icon' => esc_html__('lynny-phone-1', 'consultingpress'),
            'first_info_text' => esc_html__('+00 385 01 258 7856', 'consultingpress'),
            'first_info_url' => '',
            'second_info_icon' => esc_html__('lynny-mail-duplicate', 'consultingpress'),
            'second_info_text' => esc_html__('joshua@consulting.com', 'consultingpress' ),
            'second_info_url' => esc_url('mailto:joshua@consulting.com'),
        );

        $instance = wp_parse_args((array) $instance, $defaults); 
        ?>

        <!-- Widget Title: Text Input -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'consultingpress') ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo sanitize_text_field($instance['title']); ?>" />
        </p>

        <!-- Widget Image ID: Text Input -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('image_id')); ?>"><?php esc_html_e('Image ID:', 'consultingpress') ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('image_id')); ?>" name="<?php echo esc_attr($this->get_field_name('image_id')); ?>" value="<?php echo sanitize_text_field($instance['image_id']); ?>" />
        </p>

        <!-- Widget Name: Text Input -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('name')); ?>"><?php esc_html_e('Name:', 'consultingpress') ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('name')); ?>" name="<?php echo esc_attr($this->get_field_name('name')); ?>" value="<?php echo sanitize_text_field($instance['name']); ?>" />
        </p>

        <!-- Widget Position: Text Input -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('position')); ?>"><?php esc_html_e('Position:', 'consultingpress') ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('position')); ?>" name="<?php echo esc_attr($this->get_field_name('position')); ?>" value="<?php echo sanitize_text_field($instance['position']); ?>" />
        </p>

        <!-- Widget First icon : Text Input -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('first_info_icon')); ?>"><?php esc_html_e('First info icon:', 'consultingpress') ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('first_info_icon')); ?>" name="<?php echo esc_attr($this->get_field_name('first_info_icon')); ?>" value="<?php echo sanitize_text_field($instance['first_info_icon']); ?>" />
        </p>

        <!-- Widget First text : Text Input -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('first_info_text')); ?>"><?php esc_html_e('First info text:', 'consultingpress') ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('first_info_text')); ?>" name="<?php echo esc_attr($this->get_field_name('first_info_text')); ?>" value="<?php echo sanitize_text_field($instance['first_info_text']); ?>" />
        </p>

        <!-- Widget First url : Text Input -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('first_info_url')); ?>"><?php esc_html_e('First info url:', 'consultingpress') ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('first_info_url')); ?>" name="<?php echo esc_attr($this->get_field_name('first_info_url')); ?>" value="<?php echo wp_kses($instance['first_info_url'], $volcanno_theme_config['allowed_html_tags']); ?>" />
        </p>

        <!-- Widget Second icon : Text Input -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('second_info_icon')); ?>"><?php esc_html_e('Second info icon:', 'consultingpress') ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('second_info_icon')); ?>" name="<?php echo esc_attr($this->get_field_name('second_info_icon')); ?>" value="<?php echo sanitize_text_field($instance['second_info_icon']); ?>" />
        </p>

        <!-- Widget Second text : Text Input -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('second_info_text')); ?>"><?php esc_html_e('Second info text:', 'consultingpress') ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('second_info_text')); ?>" name="<?php echo esc_attr($this->get_field_name('second_info_text')); ?>" value="<?php echo sanitize_text_field($instance['second_info_text']); ?>" />
        </p>

        <!-- Widget Second url : Text Input --> 
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('second_info_url')); ?>"><?php esc_html_e('Second info url:', 'consultingpress') ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('second_info_url')); ?>" name="<?php echo esc_attr($this->get_field_name('second_info_url')); ?>" value="<?php echo wp_kses($instance['second_info_url'], $volcanno_theme_config['allowed_html_tags']); ?>" />
        </p> 

        <?php 
    }    

}
?>