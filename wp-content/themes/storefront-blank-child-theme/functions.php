<?php

define('XUSAH_VERSION', '1.0.8');

/**
 * storefront child theme functions.php file.
 *
 * @package storefront-child
 */
add_action( 'wp_enqueue_scripts', 'enqueue_custom_changes');
function enqueue_custom_changes() {
    wp_enqueue_style('custom-style', get_stylesheet_directory_uri() . '/custom.css', array(), XUSAH_VERSION);
	wp_enqueue_script('jquery');
	wp_enqueue_script('custom-script', get_stylesheet_directory_uri() . '/custom.js', array('jquery'), XUSAH_VERSION);
}

// DO NOT REMOVE THIS FUNCTION AS IT LOADS THE PARENT THEME STYLESHEET
add_action( 'wp_enqueue_scripts', 'enqueue_parent_theme_style');
function enqueue_parent_theme_style() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css', array(), XUSAH_VERSION );
}

/* Add any custom PHP below this line of text */


add_filter( 'woocommerce_breadcrumb_defaults', 'jk_change_breadcrumb_home_text' );
function jk_change_breadcrumb_home_text( $defaults ) {
	$defaults['home'] = 'home';
	return $defaults;
}

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 20 );
remove_action ( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5); 
add_action ( 'woocommerce_after_single_product_summary', 'woocommerce_template_single_title', 1); 
remove_action ( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10); 
add_action ( 'woocommerce_after_single_product_summary', 'woocommerce_template_single_rating', 2); 

remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
add_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 100 );

remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

add_action( 'init', 'storefront_custom_logo' );
function storefront_custom_logo() {
	remove_action( 'storefront_header', 'storefront_site_branding', 20 );
	add_action( 'storefront_header', 'storefront_display_custom_logo', 20 );
}

function storefront_display_custom_logo() {
?>
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo-link" rel="home">
        <span><?php echo __('xusah.boutique', 'storefront' ); ?></span>
		<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/logo.png" alt="<?php echo get_bloginfo( 'name' ); ?>" />
	</a>
<?php
}

// add_filter('woocommerce_variable_price_html', 'custom_variation_price', 10, 2);

function custom_variation_price( $price, $product ) {

     $price = '';

     if ( !$product->min_variation_price || $product->min_variation_price !== $product->max_variation_price ) $price .= '<span class="from">' . _x('', 'min_price', 'woocommerce') . ' </span>';

     $price .= woocommerce_price($product->get_price());

     if ( $product->max_variation_price && $product->max_variation_price !== $product->min_variation_price ) {
          $price .= '<span class="to"> ' . _x('-', 'max_price', 'woocommerce') . ' </span>';

          $price .= woocommerce_price($product->max_variation_price);
     }

     return $price;
}
add_filter( 'woocommerce_product_tabs', 'woo_rename_tabs', 98 );
function woo_rename_tabs( $tabs ) {

	$tabs['description']['title'] = __( 'DESIGN' );		// Rename the description tab

	return $tabs;

}
function mpwoo_subscription_section_title($title){
return __('Newsletter:<br>want to get in the mu-hq loop? subscribe to our e-mail newsletter to receive fun stuff, special offers and enlightening input from the mu space station, directly to your inbox!  *_*
', 'your-textdomain');
}

add_filter('mailpoet_woocommerce_subscription_section_title', 'mpwoo_subscription_section_title', 10, 1);


// WooCommerce Checkout Fields Hook
add_filter( 'woocommerce_checkout_fields' , 'custom_wc_checkout_fields' );
 
// Change order comments placeholder and label, and set billing phone number to not required.
function custom_wc_checkout_fields( $fields ) {
$fields['order']['order_comments']['placeholder'] = 'beam mu up! 🖖';
$fields['order']['order_comments']['label'] = 'for custom clothing, please type in the field below to include your measurements and any finer details or requests regarding fit, style, or color for your order! this is an online form, but there is a human being on the other end who will read every word and put their hands and heart into creating your order with great care... any other questions you may have concerning your purchase are most welcome as well!<div id="order-notes-heading">Order Notes:</div>';
$fields['billing']['billing_phone']['required'] = false;
return $fields;
}


add_filter( 'template_include', 'var_template_include', 1000 );
function var_template_include( $t ){
    $GLOBALS['current_theme_template'] = basename($t);
    return $t;
}

function get_current_template( $echo = false ) {
    if( !isset( $GLOBALS['current_theme_template'] ) )
        return false;
    if( $echo )
        echo $GLOBALS['current_theme_template'];
    else
        return $GLOBALS['current_theme_template'];
}
foreach ( array( 'pre_term_description' ) as $filter ) {
    remove_filter( $filter, 'wp_filter_kses' );
}

// Change PayPal Gateway Payment Icon
function newPayment_gateway_icon( $icon, $id ) {
    if ( $id === 'paypal' ) {
        return '<img src="' . get_stylesheet_directory_uri() . '/images/pp-logo.png" alt="PayPal" />';
    } else {
        return $icon;
    }
}
add_filter( 'woocommerce_gateway_icon', 'newPayment_gateway_icon', 10, 2 );

/**
 * Add new register fields for WooCommerce registration.
 *
 * @return string Register fields HTML.
 */
function wooc_extra_register_fields() {
	?>

	<p class="form-row form-row-first">
	<label for="reg_billing_first_name"><?php _e( 'First name', 'woocommerce' ); ?> <span class="required">*</span></label>
	<input type="text" class="input-text" name="billing_first_name" id="reg_billing_first_name" value="<?php if ( ! empty( $_POST['billing_first_name'] ) ) esc_attr_e( $_POST['billing_first_name'] ); ?>" />
	</p>

	<p class="form-row form-row-last">
	<label for="reg_billing_last_name"><?php _e( 'Last name', 'woocommerce' ); ?> <span class="required">*</span></label>
	<input type="text" class="input-text" name="billing_last_name" id="reg_billing_last_name" value="<?php if ( ! empty( $_POST['billing_last_name'] ) ) esc_attr_e( $_POST['billing_last_name'] ); ?>" />
	</p>

	<div class="clear"></div>

	<?php
}

add_action( 'woocommerce_register_form_start', 'wooc_extra_register_fields' );

/**
 * Validate the extra register fields.
 *
 * @param  string $username          Current username.
 * @param  string $email             Current email.
 * @param  object $validation_errors WP_Error object.
 *
 * @return void
 */
function wooc_validate_extra_register_fields( $username, $email, $validation_errors ) {
	if ( isset( $_POST['billing_first_name'] ) && empty( $_POST['billing_first_name'] ) ) {
		$validation_errors->add( 'billing_first_name_error', __( '<strong>Error</strong>: First name is required!', 'woocommerce' ) );
	}

	if ( isset( $_POST['billing_last_name'] ) && empty( $_POST['billing_last_name'] ) ) {
		$validation_errors->add( 'billing_last_name_error', __( '<strong>Error</strong>: Last name is required!.', 'woocommerce' ) );
	}


	if ( isset( $_POST['billing_phone'] ) && empty( $_POST['billing_phone'] ) ) {
		// $validation_errors->add( 'billing_phone_error', __( '<strong>Error</strong>: Phone is required!.', 'woocommerce' ) );
	}
}

add_action( 'woocommerce_register_post', 'wooc_validate_extra_register_fields', 10, 3 );

/**
 * Save the extra register fields.
 *
 * @param  int  $customer_id Current customer ID.
 *
 * @return void
 */
function wooc_save_extra_register_fields( $customer_id ) {
	if ( isset( $_POST['billing_first_name'] ) ) {
		// WordPress default first name field.
		update_user_meta( $customer_id, 'first_name', sanitize_text_field( $_POST['billing_first_name'] ) );

		// WooCommerce billing first name.
		update_user_meta( $customer_id, 'billing_first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
	}

	if ( isset( $_POST['billing_last_name'] ) ) {
		// WordPress default last name field.
		update_user_meta( $customer_id, 'last_name', sanitize_text_field( $_POST['billing_last_name'] ) );

		// WooCommerce billing last name.
		update_user_meta( $customer_id, 'billing_last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
	}

	if ( isset( $_POST['billing_phone'] ) ) {
		// WooCommerce billing phone
		update_user_meta( $customer_id, 'billing_phone', sanitize_text_field( $_POST['billing_phone'] ) );
	}
}

add_action( 'woocommerce_created_customer', 'wooc_save_extra_register_fields' );

add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );
function woo_remove_product_tabs( $tabs ) {
 
    unset( $tabs['additional_information'] );   // Remove the additional information tab
 
    return $tabs;
}
function filter_woocommerce_my_account_my_address_title( $var ) { 
    return '<span class="testclass">'.$var.'</span>'; 
}; 
add_filter( 'woocommerce_my_account_my_address_title', 'filter_woocommerce_my_account_my_address_title', 10, 1 ); 

function filter_woocommerce_my_account_get_addresses( $array, $customer_id ) { 
    $array['billing'] = '<span class="testclass">'.$array['billing'].'</span>';
    $array['shipping'] = '<span class="testclass">'.$array['shipping'].'</span>';
    return $array; 
}; 
add_filter( 'woocommerce_my_account_get_addresses', 'filter_woocommerce_my_account_get_addresses', 10, 2 ); 
function filter_woocommerce_my_account_my_orders_title( $var ) { 
    return '<span class="testclass">'.$var.'</span>'; 
}; 
add_filter( 'woocommerce_my_account_my_orders_title', 'filter_woocommerce_my_account_my_orders_title', 10, 3 ); 

/**
 * function to return an undo unsbscribe string for MailPoet newsletters
 * you could place it in the functions.php of your theme
 * @return string
 */
function mpoet_get_undo_unsubscribe(){
	if(class_exists('WYSIJA') && !empty($_REQUEST['wysija-key'])){
		$undo_paramsurl = array(
		 'wysija-page'=>1,
		 'controller'=>'confirm',
		 'action'=>'undounsubscribe',
		 'wysija-key'=>$_REQUEST['wysija-key']
	 	);

		$model_config = WYSIJA::get('config','model');
        	$link_undo_unsubscribe = WYSIJA::get_permalink($model_config->getValue('confirmation_page'),$undo_paramsurl);
		$undo_unsubscribe = str_replace(array('[link]','[/link]'), array('<a href="'.$link_undo_unsubscribe.'">','</a>'),'<strong>'.__('Made a mistake? [link]Undo unsubscribe[/link].',WYSIJA)).'</strong>';
		return $undo_unsubscribe;
	 }
	return '';
}

add_shortcode('mailpoet_undo_unsubscribe', 'mpoet_get_undo_unsubscribe');

add_filter( 'woocommerce_product_tabs', 'woo_reorder_tabs', 98 );

function woo_reorder_tabs( $tabs ) {

   $tabs['description']['priority'] = 1;     // We call it Design
   $tabs['reviews']['priority'] = 50;     

   return $tabs;

}

/**
 * Changes the redirect URL for the Return To Shop button in the cart.
 *
 * @return string
 */
function wc_empty_cart_redirect_url() {
	return '/product-category/clothing/';
}
add_filter( 'woocommerce_return_to_shop_redirect', 'wc_empty_cart_redirect_url' );

add_filter( 'wc_add_to_cart_message', 'remove_add_to_cart_message' );
apply_filters( 'rocket_common_cache_logged_users', true );

function remove_add_to_cart_message() {
    return;
}

// Remove Google Font from Storefront theme

function remove_google_fonts(){

wp_dequeue_style('storefront-fonts');

}

add_action( 'wp_enqueue_scripts', 'remove_google_fonts', 999);

?>