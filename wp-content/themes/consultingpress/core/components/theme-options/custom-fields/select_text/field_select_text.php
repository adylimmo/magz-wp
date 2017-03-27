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
 * @subpackage  Field_Select_Text
 * @author      Daniel J Griffiths (Ghost1227)
 * @author      Dovy Paukstys
 * @version     3.0.0
 */
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

// Don't duplicate me!
if (!class_exists('ReduxFramework_select_text')) {

    /**
     * Main ReduxFramework_select_text class
     *
     * @since       1.0.0
     */
    class ReduxFramework_select_text extends ReduxFramework {

        /**
         * Field Constructor.
         *
         * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function __construct($field = array(), $value = '', $parent) {

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

            echo '<ul id="' . $this->field['id'] . '-ul" class="redux-select-text">';

            if (isset($this->value) && is_array($this->value)) {

                foreach ($this->value['icon'] as $k => $value) {
                    $value = trim($value);
                    echo '<li><select class="select-text-select" id="' . $this->field['id'] . '-select" name="' . $this->field['name'] . '[icon][]">';

                    echo '<option value=""></option>';

                    foreach ($this->field['icons'] as $key => $option) {
                        if (!empty($key_prefix)) {
                            $key = $key_prefix . $key;
                        }

                        $selected = ($key == $value) ? ' selected="selected"' : '';

                        echo '<option' . $selected . ' value="' . esc_attr($key) . '">' . esc_html($option) . '</option>';
                    }

                    echo '<input type="text" id="' . $this->field['id'] . '-' . $k . '" name="' . $this->field['name'] . '[text][]' . $this->field['name_suffix'] . '" value="' . esc_attr($this->value['text'][$k]) . '" class="regular-text ' . $this->field['class'] . '" /> <a href="javascript:void(0);" class="deletion redux-select-text-remove">' . esc_html__('Remove', 'consultingpress') . '</a>';
                    echo '</li>';
                }
            } elseif ($this->show_empty == true) {
                echo '<li><select class="select-text-select" id="' . $this->field['id'] . '-select" name="' . $this->field['name'] . '[icon][]">';

                echo '<option value=""></option>';
                foreach ($this->field['icons'] as $k => $value) {
                    if (!empty($key_prefix)) {
                        $k = $key_prefix . $k;
                    }

                    if (is_array($this->value)) {
                        $selected = (is_array($this->value) && in_array($k, $this->value)) ? ' selected="selected"' : '';
                    } else {
                        $selected = selected($this->value, $k, false);
                    }

                    echo '<option' . $selected . ' value="' . esc_attr($k) . '">' . esc_html($value) . '</option>';
                }

                echo '</select>';
                echo '<input type="text" id="' . $this->field['id'] . '" name="' . $this->field['name'] . '[text][]' . $this->field['name_suffix'] . '" value="" class="regular-text ' . $this->field['class'] . '" /> <a href="javascript:void(0);" class="deletion redux-select-text-remove" placeholder="">' . esc_html__('Remove', 'consultingpress') . '</a>';
                echo '</li>';
            }

            echo '</ul>';
            $this->field['add_number'] = ( isset($this->field['add_number']) && is_numeric($this->field['add_number']) ) ? $this->field['add_number'] : 1;
            echo '<a href="javascript:void(0);" class="button button-primary redux-select-text-add" data-add_number="' . $this->field['add_number'] . '" data-id="' . $this->field['id'] . '-ul" data-name="' . $this->field['name'] . '[]">' . $this->add_text . '</a><br/>';
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
                    'redux-field-select_text-js', VOLCANNO_TEMPLATEURL . '/core/components/theme-options/custom-fields/select_text/field_select_text.js', array('jquery', 'select2-js', 'redux-js'), time(), true
            );

            wp_enqueue_style(
                    'redux-field-select_text-css', VOLCANNO_TEMPLATEURL . '/core/components/theme-options/custom-fields/select_text/field_select_text.css', array('select2-css'), time()
            );
        }

    }

}