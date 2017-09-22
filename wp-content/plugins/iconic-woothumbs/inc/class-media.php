<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Iconic_WooThumbs_Media.
 *
 * @class    Iconic_WooThumbs_Media
 * @version  1.0.0
 * @package  Iconic_WooThumbs
 * @category Class
 * @author   Iconic
 */
class Iconic_WooThumbs_Media {

    /**
     * @var string
     */
    private static $media_meta_key = 'iconic_woothumbs_media';

    /**
     * @var string
     */
    private static $media_aspect_ratio_meta_key = 'iconic_woothumbs_media_aspect_ratio';


    /**
     * Run
     */
    public static function run() {

	    add_action( 'iconic_woothumbs_before_thumbnail', array( __CLASS__, 'thumbnail_play_icon' ), 10, 2 );

        add_filter( 'attachment_fields_to_edit', array( __CLASS__, 'attachment_fields_to_edit' ), 10, 2 );
        add_filter( 'attachment_fields_to_save', array( __CLASS__, 'attachment_fields_to_save' ), 10, 2 );
        add_filter( 'oembed_result', array( __CLASS__, 'oembed_result' ), 10, 3 );
        add_filter( 'iconic_woothumbs_single_image_data', array( __CLASS__, 'single_image_data' ), 10, 2 );

    }

    /**
     * Add fields to the $form_fields array
     *
     * @param array $form_fields
     * @param object $post
     * @return array
     */
    public static function attachment_fields_to_edit( $form_fields, $post ) {

        $form_fields['iconic_woothumbs_media_title'] = array(
            'tr' => sprintf( '<hr><h2>%s</h2>', __('WooThumbs Media Details', 'iconic-woothumbs') )
        );

        $form_fields[ self::$media_meta_key ] = array(
            'label' => __('URL', 'iconic-woothumbs'),
            'input' => "text",
            'value' => get_post_meta( $post->ID, '_'.self::$media_meta_key, true )
        );

        $form_fields[ self::$media_aspect_ratio_meta_key ] = array(
            'label' => __('Aspect Ratio', 'iconic-woothumbs'),
            'input' => "text",
            'value' => self::get_aspect_ratio( $post->ID )
        );

        return $form_fields;

    }

    /**
     * Get aspect ratio.
     *
     * @param int $attachment_id
     * @param bool $percentage
     * @return str|int
     */
    public static function get_aspect_ratio( $attachment_id, $percentage = false ) {

        $aspect_ratio = get_post_meta( $attachment_id, '_'.self::$media_aspect_ratio_meta_key, true );
        $aspect_ratio = !empty( $aspect_ratio ) ? $aspect_ratio : "16:9";

        if( !$percentage )
            return $aspect_ratio;

        $aspect_ratio_parts = explode(':', $aspect_ratio);

        return ( $aspect_ratio_parts[1] / $aspect_ratio_parts[0] )*100;

    }

    /**
     * Save attachment fields
     *
     * @param array $post
     * @param array $attachment
     * @return array
     */
    public static function attachment_fields_to_save( $post, $attachment ) {

        if( isset( $attachment[ self::$media_meta_key ] ) ){
            update_post_meta( $post['ID'], '_'.self::$media_meta_key, $attachment[ self::$media_meta_key ] );
        }

        if( isset( $attachment[ self::$media_aspect_ratio_meta_key ] ) ){
            update_post_meta( $post['ID'], '_'.self::$media_aspect_ratio_meta_key, $attachment[ self::$media_aspect_ratio_meta_key ] );
        }

        if( !empty( $post['post_parent'] ) )
            Iconic_WooThumbs::delete_transients( true, $post['post_parent'] );

        return $post;

    }

    /**
     * Oembed result.
     */
    public static function oembed_result( $result, $url, $args ) {

        if( empty( $args['iconic-woothumbs'] ) )
            return $result;

        $embed = wp_oembed_get( $url );

        if( strpos( $url, 'youtube' ) !== false ) {
            $embed = self::modify_embed_src( $embed, $url, array( 'showinfo' => 0, 'rel' => 0, 'autoplay' => 0 ) );
        } elseif( strpos( $url, 'vimeo' ) !== false ) {
            $embed = self::modify_embed_src( $embed, $url, array( 'byline' => 0, 'title' => 0, 'portrait' => 0 ) );
        }

        return $embed;

    }

    /**
     * Modify embed src.
     */
    public static function modify_embed_src( $html, $url, $args ) {

        $join = strpos( $html, '?' ) !== false ? "&amp;" : "?";
        $query = http_build_query( $args );
        $patterns = '/src="(.*?)"/';
        $replacements = sprintf( 'src="${1}%s%s"', $join, $query );

        return preg_replace( $patterns, $replacements, $html );

    }

    /**
     * Get media URL.
     *
     * @param $attachment_id
     * @return bool|str
     */
    public static function get_media_url( $attachment_id ) {

        return get_post_meta( $attachment_id, '_'.self::$media_meta_key, true );

    }

    /**
     * Get media embed.
     *
     * @param $attachment_id
     * @return bool|str
     */
    public static function get_media_embed( $attachment_id ) {

        $media_url = self::get_media_url( $attachment_id );

        if( empty( $media_url ) ) {
            return false;
        }

        return sprintf( '<div class="iconic-woothumbs-responsive-media" style="padding-bottom: %s%%;">%s</div>', self::get_aspect_ratio( $attachment_id, true ), wp_oembed_get( $media_url, array( 'iconic-woothumbs' => true ) ) );

    }

    /**
     * Add thumbnail play icon.
     *
     * @param array $image
     * @param int $i
     */
    public static function thumbnail_play_icon( $image, $i ) {

	    if( empty( $image['media_embed'] ) ) {
	    	return;
	    }

	    echo '<div class="iconic-woothumbs-thumbnails__play-overlay"><i class="iconic-woothumbs-icon iconic-woothumbs-icon-play"></i></div>';

    }

    /**
     * Modify single image sizes.
     *
     * @param int $data
     * @param int $attachment_id
     * @return bool|array
     */
	public static function single_image_data( $data, $attachment_id ) {

		$data['media_embed'] = self::get_media_embed( $attachment_id );

		return $data;

	}
}