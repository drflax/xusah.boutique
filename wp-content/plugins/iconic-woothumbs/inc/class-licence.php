<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Iconic_WooThumbs_Licence.
 *
 * @class    Iconic_WooThumbs_Licence
 * @version  1.0.0
 * @package  Iconic_WooThumbs
 * @category Class
 * @author   Iconic
 */
class Iconic_WooThumbs_Licence {

    /**
     * Run.
     */
    public static function run() {

        self::configure();
        self::add_filters();

    }

    /**
     * Configure.
     */
    public static function configure() {

        global $woothumbs_fs;

        if ( ! isset( $woothumbs_fs ) ) {
            // Include Freemius SDK.
            require_once ICONIC_WOOTHUMBS_INC_PATH . 'freemius/start.php';

            $woothumbs_fs = fs_dynamic_init( array(
                'id'                  => '869',
                'slug'                => 'woothumbs',
                'type'                => 'plugin',
                'public_key'          => 'pk_3e970b87cd0ed00b398a760433a79',
                'is_premium'          => ! ICONIC_WOOTHUMBS_IS_ENVATO,
                'is_premium_only'     => ! ICONIC_WOOTHUMBS_IS_ENVATO,
                'has_premium_version' => ! ICONIC_WOOTHUMBS_IS_ENVATO,
                'has_paid_plans'      => ! ICONIC_WOOTHUMBS_IS_ENVATO,
                'has_addons'          => false,
                'is_org_compliant'    => false,
                'menu'                => array(
                    'slug'           => 'iconic-woothumbs-settings',
                    'contact'        => false,
                    'support'        => false,
                    'account'        => false,
                    'pricing'		 => ! ICONIC_WOOTHUMBS_IS_ENVATO,
                    'parent'         => array(
                        'slug' => 'woocommerce',
                    ),
                ),
            ) );
        }

        return $woothumbs_fs;

    }

    /**
     * Add filters.
     */
    public static function add_filters() {

        global $woothumbs_fs;

        $woothumbs_fs->add_filter( 'show_trial', '__return_false' );
        $woothumbs_fs->add_filter( 'templates/account.php', array( __CLASS__, 'back_to_settings_link' ), 10, 1 );
		$woothumbs_fs->add_filter( 'templates/billing.php', array( __CLASS__, 'back_to_settings_link' ), 10, 1 );
        add_filter( 'parent_file', array( __CLASS__, 'highlight_menu' ), 10, 1 );

    }

    /**
     * Highlight menu.
     */
    public static function highlight_menu( $parent_file ) {
	    global $plugin_page;

	    $page = empty( $_GET['page'] ) ? false : $_GET['page'];

	    if( 'iconic-woothumbs-settings-account' == $page ) {
		    $plugin_page = 'iconic-woothumbs-settings';
	    }

	    return $parent_file;
    }

    /**
     * Account link.
     */
    public static function account_link() {

        global $woothumbs_fs;

        return sprintf( '<a href="%s" class="button button-secondary">%s</a>', $woothumbs_fs->get_account_url(), __('Manage Licence', 'iconic-woothumbs') );

    }

    /**
     * Billing link.
     */
    public static function billing_link() {

        global $woothumbs_fs;

        return sprintf( '<a href="%s" class="button button-secondary">%s</a>', $woothumbs_fs->get_account_tab_url('billing'), __('Manage Billing', 'iconic-woothumbs') );

    }

    /**
     * Contact link.
     */
    public static function contact_link() {

        global $woothumbs_fs;

        return sprintf( '<a href="%s" class="button button-secondary">%s</a>', $woothumbs_fs->contact_url(), __('Create Support Ticket', 'iconic-woothumbs') );

    }

    /**
     * Get contact URL.
     */
    public static function get_contact_url( $subject = false, $message = false ) {

        global $woothumbs_fs;

        return $woothumbs_fs->contact_url( $subject, $message );

    }

    /**
     * Back to settings link.
     */
    public static function back_to_settings_link( $html ) {
	    return $html . sprintf( '<a href="%s" class="button button-secondary">&larr; %s</a>', admin_url( 'admin.php?page=iconic-woothumbs-settings' ), __('Back to Settings', 'iconic-woothumbs') );
    }

}