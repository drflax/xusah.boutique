<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Iconic_WooThumbs_Settings.
 *
 * @class    Iconic_WooThumbs_Settings
 * @version  1.0.0
 * @package  Iconic_WooThumbs
 * @category Class
 * @author   Iconic
 */
class Iconic_WooThumbs_Settings {

    /**
     * Get a list of image sizes for the site
     *
     * @return arr
     */
    public static function get_image_sizes() {

        $image_sizes = array_merge(get_intermediate_image_sizes(), array('full'));

        return array_combine($image_sizes, $image_sizes);

    }

    /**
     * Clear image cache link.
     */
    public static function clear_image_cache_link() {

        ob_start();

        ?>
        <button name="iconic-woothumbs-delete-image-cache" class="button button-secondary"><?php _e('Clear Image Cache', 'iconic-woothumbs'); ?></button>
        <?php

        return ob_get_clean();

    }

    /**
     * Account link.
     */
    public static function support_link() {

        return sprintf( '<a href="https://iconicwp.ticksy.com" class="button button-secondary" target="_blank">%s</a>', __('Create Support Ticket', 'iconic-woothumbs') );

    }

    /**
     * Documentation link.
     */
    public static function documentation_link() {

        return sprintf( '<a href="https://docs.iconicwp.com/" class="button button-secondary" target="_blank">%s</a>', __('Read Documentation', 'iconic-woothumbs') );

    }

}