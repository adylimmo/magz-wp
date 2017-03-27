<?php
/* ---------------------------------------------------------
 * Helpers
 *
 * Class for helper functions
  ---------------------------------------------------------- */

class Volcanno_Helpers {
	/*
	 * Return Image Sizes
	 * parameter $name_thumbnail
	 *
	 */
	static function volcanno_get_theme_related_image_sizes($name_thubnail) {
		global $volcanno_is_retina;
		$img_height=100;
		$img_width = 200;
		switch ($name_thubnail) {
			case 'volcanno_427_273' :
				$img_width = 427;
				$img_height = 273;
				break;
			case 'volcanno_138_100' :
				$img_width = 138;
				$img_height = 100;
				break;
			case 'volcanno_580_428' :
				$img_width = 580;
				$img_height = 428;
				break;
            case 'volcanno_885_857' :
				$img_width = 885;
				$img_height = 857;
				break;
		}
		
		return array (
				'width' => $img_width,
				'height' => $img_height 
		);
	}
    
	/*
	 * Return Image Url
	 * Parameter $post_id
	 *
	 */
	static function volcanno_get_image_url($post_id) {
		$thumbnail = get_post_thumbnail_id ( $post_id );
		$image_url = wp_get_attachment_url ( $thumbnail, 'full' );
		
		return $image_url;
	}

}
