<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package storefront
 */
$form_flag = $_REQUEST['form_flag'];///monkey
if ($form_flag == 1) exit;////
?><!DOCTYPE html>
<html <?php language_attributes(); ?> <?php storefront_html_tag_schema(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    
<div id="page" class="hfeed site">
	<?php
	do_action( 'storefront_before_header' ); ?>

	<div class="mobile-menu">
		<button type="button" id="mobile-menu-button">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
	<span class="testclass"><-- Click here to access the site navigation</span>
	</div>
	<script>
	  jQuery('#mobile-menu-button').click( function() {
		jQuery('.right-sidebar .widget-area').toggleClass('show');
	  });
	</script>
	
	<header id="masthead" class="site-header" role="banner" <?php if ( get_header_image() != '' ) { echo 'style="background-image: url(' . esc_url( get_header_image() ) . ');"'; } ?>>
		<div class="col-full">

			<?php
			/**
			 * @hooked storefront_skip_links - 0
			 * @hooked storefront_social_icons - 10
			 * @hooked storefront_site_branding - 20
			 * @hooked storefront_secondary_navigation - 30
			 * @hooked storefront_product_search - 40
			 * @hooked storefront_primary_navigation - 50
			 * @hooked storefront_header_cart - 60
			 */
			do_action( 'storefront_header' ); ?>

		</div>
	</header><!-- #masthead -->

	<?php
	/**
	 * @hooked storefront_header_widget_region - 10
	 */
	do_action( 'storefront_before_content' ); ?>

	<div id="content" class="site-content" tabindex="-1">
		<div class="col-full col-wrapper col-top">
			<div class="content-wrapper">
				<div class="top-sidebar"></div>
				<div class="content-margin"></div>
				<?php
				/**
				 * @hooked woocommerce_breadcrumb - 10
				 */
				do_action( 'storefront_content_top' ); ?>
			</div>
		</div>