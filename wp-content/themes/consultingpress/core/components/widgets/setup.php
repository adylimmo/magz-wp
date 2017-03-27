<?php

/**
* Load Visual Composer content blocks
*/
class Volcanno_Widgets {

	static function init() {
		Volcanno_Widgets::load_widget_element_files();
	}

	/**
	 * Load widget files from directory inside widgets folder. Additional directory 
	 * can be added using 'widgets_elements_directory' hook
	 * 
	 * @return void 
	 */
	static function load_widget_element_files() {
	    
	    $widgets_dir = apply_filters('widgets_elements_directory', array( VOLCANNO_THEME_DIR . "/includes/widgets/") );

	    // reverse values in array so that user can override values by using the filter
	    $widgets_dir = array_reverse($widgets_dir);

	    foreach ($widgets_dir as $dir) {
	        $dir_files = glob($dir . '*.php');
	        $dir_in_directory = glob($dir . '*/*.php');

	        if (is_array($dir_files) && count($dir_files) > 0) {
	            foreach ($dir_files as $file) {
	                require_once( $file );
	            }
	        }

	        if (is_array($dir_in_directory) && count($dir_in_directory) > 0) {
	            foreach ($dir_in_directory as $file) {
	                require_once( $file );
	            }
	        }
	    }
	}

}

Volcanno_Widgets::init();