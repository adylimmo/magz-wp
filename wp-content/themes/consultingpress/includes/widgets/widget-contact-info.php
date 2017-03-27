<?php
/* -----------------------------------------------------------------------------------

  Plugin Name: Contact Info Widget
  Plugin URI: http://www.pixel-industry.com
  Description: A widget that displays contact information.
  Version: 1.0
  Author: Pixel Industry
  Author URI: http://www.pixel-industry.com

  ----------------------------------------------------------------------------------- */


// Add function to widgets_init that'll load our widget
add_action('widgets_init', 'volcanno_register_contact_info');

// Register widget
function volcanno_register_contact_info() {
    register_widget('Volcanno_Contact_Info');
}

// Widget class
class Volcanno_Contact_Info extends WP_Widget {
    
    /* ----------------------------------------------------------------------------------- */
    /* 	Widget Setup
      /*----------------------------------------------------------------------------------- */

    function __construct() {

        // Widget settings
        $widget_options = array(
            'classname' => 'contact-info',
            'description' => esc_html__('A widget that displays contact information.', 'consultingpress')
        );


        // Create the widget
        parent::__construct('Volcanno_Contact_Info', esc_html__('Contact Info', 'consultingpress'), $widget_options);
    }

    /* ----------------------------------------------------------------------------------- */
    /* 	Display Widget
      /*----------------------------------------------------------------------------------- */

    function widget($args, $instance) {
        global $volcanno_theme_config;
        
        extract($args);

        // Our variables from the widget settings
        $title = apply_filters('widget_title', $instance['title']);

        // Before widget (defined by theme functions file)
        echo $before_widget;

        // Display the widget title if one was input
        if ($title)
            echo $before_title . $title . $after_title;

        $address_icon = !empty($instance['address_icon']) ? $instance['address_icon'] : '';
        $phone_icon = !empty($instance['phone_icon']) ? $instance['phone_icon'] : '';
        $email_icon = !empty($instance['email_icon']) ? $instance['email_icon'] : '';

        // HTML allowed
        echo $instance['before'];

        echo "<ul class='contact-info-list'>";

        if (!empty($instance['address'])){
            $address = wp_kses($instance['address'], $volcanno_theme_config['allowed_html_tags']);      
            echo "<li><i class='{$address_icon}'></i>{$address}</li>";
        }

        do_action('wci_print_home');

        if (!empty($instance['phone'])) {
            $phone = strip_tags($instance['phone']);
            echo "<li><i class='{$phone_icon}'></i>{$phone}</li>";
        }

        do_action('wci_print_phone');

        if (!empty($instance['email'])) {
            $email = sanitize_email($instance['email']);
            echo "<li><i class='{$email_icon}'></i><a href=\"mailto:{$email}\">{$email}</a></li>";
        }

        do_action('wci_print_email');

        echo "</ul>";

        // HTML allowed
        echo $instance['after'];

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
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['before'] = $new_instance['before'];
        $instance['address_icon'] = strip_tags($new_instance['address_icon']);
        $instance['address'] = wp_kses($new_instance['address'], $volcanno_theme_config['allowed_html_tags']);
        $instance['phone_icon'] = strip_tags($new_instance['phone_icon']);
        $instance['phone'] = strip_tags($new_instance['phone']);
        $instance['email_icon'] = strip_tags($new_instance['email_icon']);
        $instance['email'] = strip_tags($new_instance['email']);
        $instance['after'] = $new_instance['after'];

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
            'title' => 'Contact Info',
            'before' => '',
            'address_icon' => 'lynny-home',
            'address' => '',
            'phone_icon' => 'lynny-phone-1',
            'phone' => '',
            'email_icon' => 'lynny-mail-duplicate',
            'email' => '',
            'after' => '',
        );

        $instance = wp_parse_args((array) $instance, $defaults);
        ?>

        <!-- Widget Title: Text Input -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'consultingpress') ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo sanitize_text_field($instance['title']); ?>" />
        </p>

        <!-- Before: Textarea -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('before')); ?>"><?php esc_html_e('Before:', 'consultingpress') ?></label>
            <textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('before')); ?>" name="<?php echo esc_attr($this->get_field_name('before')); ?>"><?php echo esc_textarea($instance['before']); ?></textarea>
        </p>

        <!-- Address String: Text Input -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('address_icon')); ?>"><?php echo wp_kses( __( 'Address Icon (<a href="https://fortawesome.github.io/Font-Awesome/cheatsheet/" target="_BLANK">Font Awesome</a>):', 'consultingpress' ), $volcanno_theme_config['allowed_html_tags'] ) ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('address_icon')); ?>" name="<?php echo esc_attr($this->get_field_name('address_icon')); ?>" value="<?php echo sanitize_text_field($instance['address_icon']); ?>" />
        </p>


        <!-- Address: Text Input -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('address')); ?>"><?php esc_html_e('Address:', 'consultingpress') ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('address')); ?>" name="<?php echo esc_attr($this->get_field_name('address')); ?>" value="<?php echo wp_kses($instance['address'], $volcanno_theme_config['allowed_html_tags']); ?>" />
        </p>

        <!-- Phone String: Text Input -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('phone_icon')); ?>"><?php echo wp_kses( __( 'Phone Icon (<a href="https://fortawesome.github.io/Font-Awesome/cheatsheet/" target="_BLANK">Font Awesome</a>):', 'consultingpress' ), $volcanno_theme_config['allowed_html_tags'] ) ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('phone_icon')); ?>" name="<?php echo esc_attr($this->get_field_name('phone_icon')); ?>" value="<?php echo sanitize_text_field($instance['phone_icon']); ?>" />
        </p>

        <!-- Phone: Text Input -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('phone')); ?>"><?php esc_html_e('Phone:', 'consultingpress') ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('phone')); ?>" name="<?php echo esc_attr($this->get_field_name('phone')); ?>" value="<?php echo sanitize_text_field($instance['phone']); ?>" />
        </p>

        <!-- E-Mail string: Text Input -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('email_icon')); ?>"><?php echo wp_kses( __( 'E-mail Icon (<a href="https://fortawesome.github.io/Font-Awesome/cheatsheet/" target="_BLANK">Font Awesome</a>):', 'consultingpress' ), $volcanno_theme_config['allowed_html_tags'] ) ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('email_icon')); ?>" name="<?php echo esc_attr($this->get_field_name('email_icon')); ?>" value="<?php echo sanitize_text_field($instance['email_icon']); ?>" />
        </p>

        <!-- E-Mail: Text Input -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('email')); ?>"><?php esc_html_e('E-Mail:', 'consultingpress') ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('email')); ?>" name="<?php echo esc_attr($this->get_field_name('email')); ?>" value="<?php echo sanitize_email($instance['email']); ?>" />
        </p>

        <!-- After: Textarea -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('after')); ?>"><?php esc_html_e('After:', 'consultingpress') ?></label>
            <textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('after')); ?>" name="<?php echo esc_attr($this->get_field_name('after')); ?>"><?php echo esc_textarea($instance['after']); ?></textarea>
        </p>

        <?php
    }    

}
?>