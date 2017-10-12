<?php
defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );
/**
 * Plugin Name: Fix Varnish Auto Purge with Proxy and WP Rocket
 * Author:      WP Rocket Support Team
 * Author URI:  http://wp-rocket.me/
 * License:     GNU General Public License v3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

add_filter( 'rocket_varnish_purge_request_host', '__rocket_varnish_custom_hostname' );
function __rocket_varnish_custom_hostname() {
	return 'https://xusah.boutique/';
}


add_filter( 'rocket_varnish_ip', '__rocket_varnish_custom_ip' );
function __rocket_varnish_custom_ip() {
	return 'localhost';
}