<?php

/**
 * Redux Framework is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Redux Framework is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Redux Framework. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package     ReduxFramework
 * @subpackage  Field_Menu_Manager
 * @author      Daniel J Griffiths (Ghost1227)
 * @author      Dovy Paukstys
 * @version     3.0.0
 */
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

// Don't duplicate me!
if (!class_exists('ReduxFramework_select_label')) {

    /**
     * Main ReduxFramework_menu_manager class
     *
     * @since       1.0.0
     */
    class ReduxFramework_select_label extends ReduxFramework {

        /**
         * Field Constructor.
         *
         * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        function __construct($field = array(), $value = '', $parent) {

            //parent::__construct( $parent->sections, $parent->args );
            $this->parent = $parent;
            $this->field = $field;
            $this->value = $value;
        }

        /**
         * Field Render Function.
         *
         * Takes the vars and outputs the HTML for the field in the settings
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function render() {
            $this->add_text = ( isset($this->field['add_text']) ) ? $this->field['add_text'] : esc_html__('Add More', 'consultingpress');

            $this->show_empty = ( isset($this->field['show_empty']) ) ? $this->field['show_empty'] : true;

            $key_prefix = isset($this->field['key_prefix']) ? $this->field['key_prefix'] : '';

            echo '<ul id="' . $this->field['id'] . '-ul" class="redux-menu-manager">';
            if (isset($this->value) && is_array($this->value)) {

                if ($this->field['options']) {
                    foreach ($this->field['options'] as $opt_key => $opt_value) {
                        echo '<li><label for="' . esc_attr($opt_key) . '" class="select-label">' . esc_html($opt_value) . '</label>';

                        echo '<select class="select-label-select" id="' . $this->field['id'] . '-select">';

                        echo '<option value=""></option>';

                        if (isset($this->value[$opt_key])) {
                            $value = trim($this->value[$opt_key]);
                        } else {
                            $value = '';
                        }

                        foreach ($this->field['icons'] as $icon_key => $icon_value) {
                            if (!empty($key_prefix)) {
                                $icon_key = $key_prefix . $icon_key;
                            }

                            if ($icon_key == $value) {
                                $selected = ' selected="selected"';
                                $selected_key = $icon_key;
                            } else {
                                $selected = '';
                            }

                            echo '<option' . $selected . ' value="' . esc_attr($icon_key) . '">' . esc_html($icon_value) . '</option>';
                        }
                        if (empty($selected_key))
                            $selected_key = 'not-selected';

                        echo '<input type="hidden" id="' . $this->field['id'] . '-' . $selected_key . '" name="' . $this->field['name'] . '[' . $opt_key . ']' . $this->field['name_suffix'] . '" value="' . esc_attr($value) . '" class="regular-text value-store ' . $this->field['class'] . '" />';

                        echo '</li>';
                    }
                } else {
                    echo esc_html__('Please set primary Menu.', 'consultingpress');
                }
            } elseif ($this->show_empty == true) {

                if (!empty($this->field['options'])) {

                    foreach ($this->field['options'] as $opt_key => $opt_value) {
                        echo '<li><label for="' . $opt_key . '-select" class="select-label">' . esc_html($opt_value) . '</label>';
                        echo '<select class="select-label-select" id="' . $this->field['id'] . '-select">';
                        echo '<option value=""></option>';
                        foreach ($this->field['icons'] as $icon_key => $icon_value) {
                            if (!empty($key_prefix)) {
                                $icon_key = $key_prefix . $icon_key;
                            }

                            echo '<option value="' . esc_attr($icon_key) . '">' . esc_html($icon_value) . '</option>';
                        }

                        echo '</select>';
                        echo '<input type="hidden" id="' . $this->field['id'] . '-' . $opt_key . '" name="' . $this->field['name'] . '[' . $opt_key . ']' . $this->field['name_suffix'] . '" value="" class="regular-text value-store ' . $this->field['class'] . '" />';
                        echo '</li>';
                    }
                } else {
                    echo esc_html__('Please set primary Menu.', 'consultingpress');
                }
            }

            echo '</ul>';
        }

        /**
         * Enqueue Function.
         *
         * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function enqueue() {

            wp_enqueue_script(
                    'redux-field-menu_manager-js', VOLCANNO_TEMPLATEURL . '/core/theme-options/custom-fields/select_label/field_select_label.js', array('jquery', 'select2-js', 'redux-js'), time(), true
            );

            wp_enqueue_style(
                    'redux-field-menu_manager-css', VOLCANNO_TEMPLATEURL . '/core/theme-options/custom-fields/select_label/field_select_label.css', array('select2-css'), time()
            );
        }

    }

}