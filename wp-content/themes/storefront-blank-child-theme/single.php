<?php
/**
 * The template for displaying all single posts.
 *
 * @package storefront
 */

get_header(); ?>

	<div class="col-full col-wrapper">
		<div class="content-wrapper">
			<?php do_action( 'storefront_sidebar' ); ?>
			<div class="content-margin"></div>
			<div id="primary" class="content-area">
				<main id="main" class="site-main" role="main">

				<?php while ( have_posts() ) : the_post(); ?>

					<?php
					do_action( 'storefront_single_post_before' );

					get_template_part( 'content', 'single' );

					/**
					 * @hooked storefront_post_nav - 10
					 * @hooked storefront_display_comments - 20
					 */
					do_action( 'storefront_single_post_after' );
					?>

				<?php endwhile; // end of the loop. ?>

				</main><!-- #main -->
			</div><!-- #primary -->
		</div>

<?php get_footer(); ?>
