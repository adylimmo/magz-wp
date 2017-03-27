<?php

/* -------------------------------------------------------------------------------
 * Cropping images
 *
 * Class that crops images when provided width and height. In case one of
 * parameters is not set, ratio of original image will be calcualted
 * and used for cropped image.
 * 
  ------------------------------------------------------------------------------- */

class Volcanno_Image_Resizer {

    /**
     * Width of original image
     * 
     * @var string
     */
    static $orig_width;

    /**
     * Height of original image
     * 
     * @var string
     */
    static $orig_height;

    /**
     * Image width
     * 
     * @var string
     */
    static $width;

    /**
     * Image height
     * 
     * @var string
     */
    static $height;

    /**
     * Image ID
     * 
     * @var int
     */
    static $image_id;

    /**
     * Image crop ratio
     * 
     * @var float
     */
    static $crop_ratio;

    /**
     * Init and return image
     * 
     * @param int $id
     * @param string $width
     * @param string $height
     * 
     * @return string
     */
    static function init( $id, $width, $height ) {

        if ( empty( $id ) ) {
            return false;
        }

        self::$image_id = trim( $id, ',' );
        self::$width = $width;
        self::$height = $height;

        self::get_original_image_meta();

        self::calculate_crop_ratio();

        return self::get_cropped_image();
    }

    /**
     * Get meta data for original image
     */
    static function get_original_image_meta() {

        // Get original image width & height
        $orig_meta = wp_get_attachment_metadata( self::$image_id );

        self::$orig_width = isset( $orig_meta['width'] ) ? $orig_meta['width'] : 0;
        self::$orig_height = isset( $orig_meta['height'] ) ? $orig_meta['height'] : 0;
    }

    /**
     * Return image URL
     * 
     * @return mixed
     */
    static function get_cropped_image() {

        if ( empty( self::$width ) && empty( self::$height ) ) {
            self::$width = self::$orig_width;
            self::$height = self::$orig_height;
        }

        if ( empty( self::$width ) || empty( self::$height ) ) {
            self::$width = empty( self::$width ) ? self::$height * self::$crop_ratio : self::$width;
            if ( self::$crop_ratio != 0 ) {
                self::$height = empty( self::$height ) ? self::$width / self::$crop_ratio : self::$height;
            }
        }

        $image_path = get_attached_file( self::$image_id );

        // This would be the path of our resized image if the dimensions existed
        $image_ext = pathinfo( $image_path, PATHINFO_EXTENSION );
        $image_path = preg_replace( '/^(.*)\.' . $image_ext . '$/', sprintf( '$1-%sx%s.%s', floor( self::$width ), floor( self::$height ), $image_ext ), $image_path );

        $att_url = wp_get_attachment_url( self::$image_id );

        // If it already exists, serve it
        if ( file_exists( $image_path ) ) {
            return dirname( $att_url ) . '/' . basename( $image_path );
        }

        // If not, resize the image...
        $resized = image_make_intermediate_size(
                get_attached_file( self::$image_id ), self::$width, self::$height, true
        );

        // Get attachment meta so we can add new size
        $imagedata = wp_get_attachment_metadata( self::$image_id );

        // Save the new size in WP so that it can also perform actions on it
        $imagedata['sizes'][self::$width . 'x' . self::$height] = $resized;
        wp_update_attachment_metadata( self::$image_id, $imagedata );

        // Resize somehow failed
        if ( !$resized ) {
            return $att_url;
        }

        // Then serve it
        return dirname( $att_url ) . '/' . $resized['file'];
    }

    /**
     * Calculate image dimension ratio
     */
    static function calculate_crop_ratio() {

        if ( !empty( self::$width ) && !empty( self::$height ) ) {
            // Define crop width & height ratio
            if ( self::$height != 0 ) {
                self::$crop_ratio = self::$width / self::$height;
            }
        } else {
            if ( self::$orig_height != 0 ) {
                self::$crop_ratio = self::$orig_width / self::$orig_height;
            }
        }
    }

}

// wrapper function
function volcanno_crop_image( $id, $width = null, $height = null ) {
    return Volcanno_Image_Resizer::init( $id, $width, $height );
}
