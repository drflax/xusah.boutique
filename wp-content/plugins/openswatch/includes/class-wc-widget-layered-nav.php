<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Layered Navigation Widget
 *
 * @author   WooThemes
 * @category Widgets
 * @package  WooCommerce/Widgets
 * @version  2.3.0
 * @extends  WC_Widget
 */
class OpenSwatch_Widget_Layered_Nav extends WC_Openswatch_Widget {

	/**
	 * Constructor
	 */
    public function __construct() {
        $this->widget_cssclass    = 'openswatch_widget_layered_nav';
        $this->widget_description = __( 'Shows a custom attribute with openswatch in a widget which lets you narrow down the list of products when viewing product categories.', 'openswatch' );
        $this->widget_id          = 'openswatch_layered_nav';
        $this->widget_name        = __( 'Openswatch Layered Nav', 'openswatch' );

        parent::__construct();
    }

	/**
	 * update function.
	 *
	 * @see WP_Widget->update
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$this->init_settings();

		return parent::update( $new_instance, $old_instance );
	}

	/**
	 * form function.
	 *
	 * @see WP_Widget->form
	 *
	 * @param array $instance
	 */
	public function form( $instance ) {
		$this->init_settings();

		parent::form( $instance );
	}

	/**
	 * Init settings after post types are registered
	 */
	public function init_settings() {
		$attribute_array      = array();
		$attribute_taxonomies = wc_get_attribute_taxonomies();

		if ( $attribute_taxonomies ) {
			foreach ( $attribute_taxonomies as $tax ) {
				if ( taxonomy_exists( wc_attribute_taxonomy_name( $tax->attribute_name ) ) ) {
					$attribute_array[ $tax->attribute_name ] = $tax->attribute_name;
				}
			}
		}

		$this->settings = array(
			'title' => array(
				'type'  => 'text',
				'std'   => __( 'Filter by', 'openswatch' ),
				'label' => __( 'Title', 'openswatch' )
			),
			'attribute' => array(
				'type'    => 'select',
				'std'     => '',
				'label'   => __( 'Attribute', 'openswatch' ),
				'options' => $attribute_array
			),
			'display_type' => array(
				'type'    => 'select',
				'std'     => 'list',
				'label'   => __( 'Display type', 'openswatch' ),
				'options' => array(
					'list'     => __( 'List', 'openswatch' ),
					'dropdown' => __( 'Dropdown', 'openswatch' )
				)
			),
			'query_type' => array(
				'type'    => 'select',
				'std'     => 'and',
				'label'   => __( 'Query type', 'openswatch' ),
				'options' => array(
					'and' => __( 'AND', 'openswatch' ),
					'or'  => __( 'OR', 'openswatch' )
				)
			),
            'display_qty' => array(
                'type'    => 'select',
                'std'     => '1',
                'label'   => __( 'Display Qty', 'openswatch' ),
                'options' => array(
                    '1'     => __( 'Yes', 'openswatch' ),
                    '0' => __( 'No', 'openswatch' )
                )
            ),
		);
	}

	/**
	 * widget function.
	 *
	 * @see WP_Widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		global $_chosen_attributes;
		global $woocommerce;
		$woo_version = $woocommerce->version;
		if ( ! is_post_type_archive( 'product' ) && ! is_tax( get_object_taxonomies( 'product' ) ) ) {

			return;
		}

		$current_term = is_tax() ? get_queried_object()->term_id : '';
		$current_tax  = is_tax() ? get_queried_object()->taxonomy : '';
		$taxonomy     = isset( $instance['attribute'] ) ? wc_attribute_taxonomy_name( $instance['attribute'] ) : $this->settings['attribute']['std'];
		$query_type   = isset( $instance['query_type'] ) ? $instance['query_type'] : $this->settings['query_type']['std'];
		$display_type = isset( $instance['display_type'] ) ? $instance['display_type'] : $this->settings['display_type']['std'];
        $display_qty = isset( $instance['display_qty'] ) ? $instance['display_qty'] : $this->settings['display_qty']['std'];

        if ( ! taxonomy_exists( $taxonomy ) ) {
			return;
		}

		$get_terms_args = array( 'hide_empty' => '1' );

		$orderby = wc_attribute_orderby( $taxonomy );

		switch ( $orderby ) {
			case 'name' :
				$get_terms_args['orderby']    = 'name';
				$get_terms_args['menu_order'] = false;
			break;
			case 'id' :
				$get_terms_args['orderby']    = 'id';
				$get_terms_args['order']      = 'ASC';
				$get_terms_args['menu_order'] = false;
			break;
			case 'menu_order' :
				$get_terms_args['menu_order'] = 'ASC';
			break;
		}

		$terms = get_terms( $taxonomy, $get_terms_args );

		if ( 0 < count( $terms ) ) {

			ob_start();

			$found = false;

			$this->widget_start( $args, $instance );

			// Force found when option is selected - do not force found on taxonomy attributes
			if ( ! is_tax() && is_array( $_chosen_attributes ) && array_key_exists( $taxonomy, $_chosen_attributes ) ) {
				$found = true;
			}
			if($woo_version >= 2.6)
			{
				$_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();
				if ( 'dropdown' == $display_type ) {

					// skip when viewing the taxonomy
					if ( $current_tax && $taxonomy == $current_tax ) {

						$found = false;

					} else {

						$taxonomy_filter = str_replace( 'pa_', '', $taxonomy );

						$found = false;

						echo '<div class="dropdown-select"><select class="dropdown_layered_nav_' . $taxonomy_filter . '">';

						echo '<option value="">' . sprintf( __( 'Any %s', 'woocommerce' ), wc_attribute_label( $taxonomy ) ) . '</option>';

						foreach ( $terms as $term ) {

							// If on a term page, skip that term in widget list
							if ( $term->term_id == $current_term ) {
								continue;
							}

							// Get count based on current view - uses transients
							$transient_name = 'wc_ln_count_' . md5( sanitize_key( $taxonomy ) . sanitize_key( $term->term_taxonomy_id ) );

							if ( false === ( $_products_in_term = get_transient( $transient_name ) ) ) {

								$_products_in_term = get_objects_in_term( $term->term_id, $taxonomy );

								set_transient( $transient_name, $_products_in_term, DAY_IN_SECONDS * 30 );
							}

							$option_is_set = ( isset( $_chosen_attributes[ $taxonomy ] ) && in_array( $term->term_id, $_chosen_attributes[ $taxonomy ]['terms'] ) );

							// If this is an AND query, only show options with count > 0
							if($woo_version >= 2.6)
							{
								global $wp_the_query;
								$layered_count = wp_list_pluck( $wp_the_query->posts, 'ID' );

							}else{
								$layered_count = WC()->query->layered_nav_product_ids;
							}
							if ( 'and' == $query_type ) {

								$count = sizeof( array_intersect( $_products_in_term, $layered_count ) );

								if ( 0 < $count ) {
									$found = true;
								}

								if ( 0 == $count && ! $option_is_set ) {
									continue;
								}

								// If this is an OR query, show all options so search can be expanded
							} else {

								$count = sizeof( array_intersect( $_products_in_term, WC()->query->unfiltered_product_ids ) );

								if ( 0 < $count ) {
									$found = true;
								}

							}
							if($display_qty == 1)
							{
								$count = isset($count) ? $count : 0;
								echo '<option value="' . esc_attr( $term->slug ) . '" ' . selected( isset( $_GET[ 'filter_' . $taxonomy_filter ] ) ? $_GET[ 'filter_' . $taxonomy_filter ] : '' , $term->slug, false ) . '>' . esc_html( $term->name ).'('.$count.')' . '</option>';
							}else{
								echo '<option value="' . esc_attr( $term->slug ) . '" ' . selected( isset( $_GET[ 'filter_' . $taxonomy_filter ] ) ? $_GET[ 'filter_' . $taxonomy_filter ] : '' , $term->slug, false ) . '>' . esc_html( $term->name ) . '</option>';
							}

						}

						echo '</select></div>';

						echo '</select>';

						wc_enqueue_js( "
						jQuery( '.dropdown_layered_nav_". esc_js( $taxonomy_filter ) . "' ).change( function() {
								var slug = jQuery( this ).val();
								location.href = '" . preg_replace( '%\/page\/[0-9]+%', '', str_replace( array( '&amp;', '%2C' ), array( '&', ',' ), esc_js( add_query_arg( 'filtering', '1', remove_query_arg( array( 'page', 'filter_' . $taxonomy_filter ) ) ) ) ) ) . "&filter_". esc_js( $taxonomy_filter ) . "=' + slug;
							});
						" );


					}

				} else {

					$openwatch_attribute_image_swatch = openwatch_get_option('openwatch_attribute_image_swatch');
					// List display
					echo '<ul>';

					foreach ( $terms as $term ) {

						// Get count based on current view - uses transients
						$transient_name = 'wc_ln_count_' . md5( sanitize_key( $taxonomy ) . sanitize_key( $term->term_taxonomy_id ) );

						if ( false === ( $_products_in_term = get_transient( $transient_name ) ) ) {

							$_products_in_term = get_objects_in_term( $term->term_id, $taxonomy );

							set_transient( $transient_name, $_products_in_term );
						}

						$option_is_set = ( isset( $_chosen_attributes[ $taxonomy ] ) && in_array( $term->term_id, $_chosen_attributes[ $taxonomy ]['terms'] ) );

						// skip the term for the current archive
						if ( $current_term == $term->term_id ) {
							continue;
						}
						if($woo_version >= 2.6)
						{
							global $wp_the_query;
							$layered_count = wp_list_pluck( $wp_the_query->posts, 'ID' );

						}else{
							$layered_count = WC()->query->layered_nav_product_ids;
						}
						// If this is an AND query, only show options with count > 0
						if ( 'and' == $query_type ) {

							$count = sizeof( array_intersect( $_products_in_term, $layered_count) );

							if ( 0 < $count && $current_term !== $term->term_id ) {
								$found = true;
							}

							if ( 0 == $count && ! $option_is_set ) {
								continue;
							}

							// If this is an OR query, show all options so search can be expanded
						} else {

							$count = sizeof( array_intersect( $_products_in_term, WC()->query->unfiltered_product_ids ) );

							if ( 0 < $count ) {
								$found = true;
							}
						}

						$arg = 'filter_' . sanitize_title( $instance['attribute'] );

						$current_filter = ( isset( $_GET[ $arg ] ) ) ? explode( ',', $_GET[ $arg ] ) : array();

						if ( ! is_array( $current_filter ) ) {
							$current_filter = array();
						}

						$current_filter = array_map( 'esc_attr', $current_filter );

						if ( ! in_array( $term->slug, $current_filter ) ) {
							$current_filter[] = $term->slug;
						}

						// Base Link decided by current page
						if ( defined( 'SHOP_IS_ON_FRONT' ) ) {
							$link = home_url();
						} elseif ( is_post_type_archive( 'product' ) || is_page( wc_get_page_id('shop') ) ) {
							$link = get_post_type_archive_link( 'product' );
						} else {
							$link = get_term_link( get_query_var('term'), get_query_var('taxonomy') );
						}

						// All current filters
						if ( $_chosen_attributes ) {
							foreach ( $_chosen_attributes as $name => $data ) {
								if ( $name !== $taxonomy ) {

									// Exclude query arg for current term archive term
									while ( in_array( $current_term, $data['terms'] ) ) {
										$key = array_search( $current_term, $data );
										unset( $data['terms'][$key] );
									}

									// Remove pa_ and sanitize
									$filter_name = sanitize_title( str_replace( 'pa_', '', $name ) );

									if ( ! empty( $data['terms'] ) ) {
										$link = add_query_arg( 'filter_' . $filter_name, implode( ',', $data['terms'] ), $link );
									}

									if ( 'or' == $data['query_type'] ) {
										$link = add_query_arg( 'query_type_' . $filter_name, 'or', $link );
									}
								}
							}
						}

						// Min/Max
						if ( isset( $_GET['min_price'] ) ) {
							$link = add_query_arg( 'min_price', $_GET['min_price'], $link );
						}

						if ( isset( $_GET['max_price'] ) ) {
							$link = add_query_arg( 'max_price', $_GET['max_price'], $link );
						}

						// Orderby
						if ( isset( $_GET['orderby'] ) ) {
							$link = add_query_arg( 'orderby', $_GET['orderby'], $link );
						}

						// Current Filter = this widget
						if ( isset( $_chosen_attributes[ $taxonomy ] ) && is_array( $_chosen_attributes[ $taxonomy ]['terms'] ) && in_array( $term->slug, $_chosen_attributes[ $taxonomy ]['terms'] ) ) {

							$class = 'class="chosen"';

							// Remove this term is $current_filter has more than 1 term filtered
							if ( sizeof( $current_filter ) > 1 ) {

								$current_filter_without_this = array_diff( $current_filter, array( $term->slug ) );

								$link = add_query_arg( $arg, implode( ',', $current_filter_without_this ), $link );
							}

						} else {

							$class = '';
							$link = add_query_arg( $arg, implode( ',', $current_filter ), $link );

						}

						// Search Arg
						if ( get_search_query() ) {
							$link = add_query_arg( 's', get_search_query(), $link );
						}

						// Post Type Arg
						if ( isset( $_GET['post_type'] ) ) {
							$link = add_query_arg( 'post_type', $_GET['post_type'], $link );
						}

						// Query type Arg
						if ( $query_type == 'or' && ! ( sizeof( $current_filter ) == 1 && isset( $_chosen_attributes[ $taxonomy ]['terms'] ) && is_array( $_chosen_attributes[ $taxonomy ]['terms'] ) && in_array( $term->term_id, $_chosen_attributes[ $taxonomy ]['terms'] ) ) ) {
							$link = add_query_arg( 'query_type_' . sanitize_title( $instance['attribute'] ), 'or', $link );
						}

						echo '<li ' . $class . '>';

						echo ( $count > 0 || $option_is_set ) ? '<a title="'.$term->name.'" href="' . esc_url( apply_filters( 'woocommerce_openswatch_layered_nav_link', $link ) ) . '">' : '<span>';
						$image = ColorSwatch::getSwatchImage($term->term_id);

						if($image)
						{
							echo '<span class="swatch-item"><img src="'.$image.'"/></span>';
						}else{
							echo '<span class="swatch-item">'.$term->name.'</span>';
						}


						echo ( $count > 0 || $option_is_set ) ? '</a>' : '</span>';
						if($display_qty == 1)
						{
							echo ' <span class="count">(' . $count . ')</span>';
						}

						echo '</li>';

					}

					echo '</ul>';

				} // End display type conditional
			}else{
				if ( 'dropdown' == $display_type ) {

					// skip when viewing the taxonomy
					if ( $current_tax && $taxonomy == $current_tax ) {

						$found = false;

					} else {

						$taxonomy_filter = str_replace( 'pa_', '', $taxonomy );

						$found = false;

						echo '<div class="dropdown-select"><select class="dropdown_layered_nav_' . $taxonomy_filter . '">';

						echo '<option value="">' . sprintf( __( 'Any %s', 'woocommerce' ), wc_attribute_label( $taxonomy ) ) . '</option>';

						foreach ( $terms as $term ) {

							// If on a term page, skip that term in widget list
							if ( $term->term_id == $current_term ) {
								continue;
							}

							// Get count based on current view - uses transients
							$transient_name = 'wc_ln_count_' . md5( sanitize_key( $taxonomy ) . sanitize_key( $term->term_taxonomy_id ) );

							if ( false === ( $_products_in_term = get_transient( $transient_name ) ) ) {

								$_products_in_term = get_objects_in_term( $term->term_id, $taxonomy );

								set_transient( $transient_name, $_products_in_term, DAY_IN_SECONDS * 30 );
							}

							$option_is_set = ( isset( $_chosen_attributes[ $taxonomy ] ) && in_array( $term->term_id, $_chosen_attributes[ $taxonomy ]['terms'] ) );

							// If this is an AND query, only show options with count > 0
							if($woo_version >= 2.6)
							{
								global $wp_the_query;
								$layered_count = wp_list_pluck( $wp_the_query->posts, 'ID' );

							}else{
								$layered_count = WC()->query->layered_nav_product_ids;
							}
							if ( 'and' == $query_type ) {

								$count = sizeof( array_intersect( $_products_in_term, $layered_count ) );

								if ( 0 < $count ) {
									$found = true;
								}

								if ( 0 == $count && ! $option_is_set ) {
									continue;
								}

								// If this is an OR query, show all options so search can be expanded
							} else {

								$count = sizeof( array_intersect( $_products_in_term, WC()->query->unfiltered_product_ids ) );

								if ( 0 < $count ) {
									$found = true;
								}

							}
							if($display_qty == 1)
							{
								$count = isset($count) ? $count : 0;
								echo '<option value="' . esc_attr( $term->term_id ) . '" ' . selected( isset( $_GET[ 'filter_' . $taxonomy_filter ] ) ? $_GET[ 'filter_' . $taxonomy_filter ] : '' , $term->term_id, false ) . '>' . esc_html( $term->name ).'('.$count.')' . '</option>';
							}else{
								echo '<option value="' . esc_attr( $term->term_id ) . '" ' . selected( isset( $_GET[ 'filter_' . $taxonomy_filter ] ) ? $_GET[ 'filter_' . $taxonomy_filter ] : '' , $term->term_id, false ) . '>' . esc_html( $term->name ) . '</option>';
							}

						}

						echo '</select></div>';

						wc_enqueue_js( "
						jQuery( '.dropdown_layered_nav_$taxonomy_filter' ).change( function() {
							var term_id = parseInt( jQuery( this ).val(), 10 );
							location.href = '" . preg_replace( '%\/page\/[0-9]+%', '', str_replace( array( '&amp;', '%2C' ), array( '&', ',' ), esc_js( add_query_arg( 'filtering', '1', remove_query_arg( array( 'page', 'filter_' . $taxonomy_filter ) ) ) ) ) ) . "&filter_$taxonomy_filter=' + ( isNaN( term_id ) ? '' : term_id );
						});
					" );

					}

				} else {

					$openwatch_attribute_image_swatch = openwatch_get_option('openwatch_attribute_image_swatch');
					// List display
					echo '<ul>';

					foreach ( $terms as $term ) {

						// Get count based on current view - uses transients
						$transient_name = 'wc_ln_count_' . md5( sanitize_key( $taxonomy ) . sanitize_key( $term->term_taxonomy_id ) );

						if ( false === ( $_products_in_term = get_transient( $transient_name ) ) ) {

							$_products_in_term = get_objects_in_term( $term->term_id, $taxonomy );

							set_transient( $transient_name, $_products_in_term );
						}

						$option_is_set = ( isset( $_chosen_attributes[ $taxonomy ] ) && in_array( $term->term_id, $_chosen_attributes[ $taxonomy ]['terms'] ) );

						// skip the term for the current archive
						if ( $current_term == $term->term_id ) {
							continue;
						}
						if($woo_version >= 2.6)
						{
							global $wp_the_query;
							$layered_count = wp_list_pluck( $wp_the_query->posts, 'ID' );

						}else{
							$layered_count = WC()->query->layered_nav_product_ids;
						}
						// If this is an AND query, only show options with count > 0
						if ( 'and' == $query_type ) {

							$count = sizeof( array_intersect( $_products_in_term, $layered_count) );

							if ( 0 < $count && $current_term !== $term->term_id ) {
								$found = true;
							}

							if ( 0 == $count && ! $option_is_set ) {
								continue;
							}

							// If this is an OR query, show all options so search can be expanded
						} else {

							$count = sizeof( array_intersect( $_products_in_term, WC()->query->unfiltered_product_ids ) );

							if ( 0 < $count ) {
								$found = true;
							}
						}

						$arg = 'filter_' . sanitize_title( $instance['attribute'] );

						$current_filter = ( isset( $_GET[ $arg ] ) ) ? explode( ',', $_GET[ $arg ] ) : array();

						if ( ! is_array( $current_filter ) ) {
							$current_filter = array();
						}

						$current_filter = array_map( 'esc_attr', $current_filter );

						if ( ! in_array( $term->term_id, $current_filter ) ) {
							$current_filter[] = $term->term_id;
						}

						// Base Link decided by current page
						if ( defined( 'SHOP_IS_ON_FRONT' ) ) {
							$link = home_url();
						} elseif ( is_post_type_archive( 'product' ) || is_page( wc_get_page_id('shop') ) ) {
							$link = get_post_type_archive_link( 'product' );
						} else {
							$link = get_term_link( get_query_var('term'), get_query_var('taxonomy') );
						}

						// All current filters
						if ( $_chosen_attributes ) {
							foreach ( $_chosen_attributes as $name => $data ) {
								if ( $name !== $taxonomy ) {

									// Exclude query arg for current term archive term
									while ( in_array( $current_term, $data['terms'] ) ) {
										$key = array_search( $current_term, $data );
										unset( $data['terms'][$key] );
									}

									// Remove pa_ and sanitize
									$filter_name = sanitize_title( str_replace( 'pa_', '', $name ) );

									if ( ! empty( $data['terms'] ) ) {
										$link = add_query_arg( 'filter_' . $filter_name, implode( ',', $data['terms'] ), $link );
									}

									if ( 'or' == $data['query_type'] ) {
										$link = add_query_arg( 'query_type_' . $filter_name, 'or', $link );
									}
								}
							}
						}

						// Min/Max
						if ( isset( $_GET['min_price'] ) ) {
							$link = add_query_arg( 'min_price', $_GET['min_price'], $link );
						}

						if ( isset( $_GET['max_price'] ) ) {
							$link = add_query_arg( 'max_price', $_GET['max_price'], $link );
						}

						// Orderby
						if ( isset( $_GET['orderby'] ) ) {
							$link = add_query_arg( 'orderby', $_GET['orderby'], $link );
						}

						// Current Filter = this widget
						if ( isset( $_chosen_attributes[ $taxonomy ] ) && is_array( $_chosen_attributes[ $taxonomy ]['terms'] ) && in_array( $term->term_id, $_chosen_attributes[ $taxonomy ]['terms'] ) ) {

							$class = 'class="chosen"';

							// Remove this term is $current_filter has more than 1 term filtered
							if ( sizeof( $current_filter ) > 1 ) {
								$current_filter_without_this = array_diff( $current_filter, array( $term->term_id ) );

								$link = add_query_arg( $arg, implode( ',', $current_filter_without_this ), $link );
							}

						} else {

							$class = '';
							$link = add_query_arg( $arg, implode( ',', $current_filter ), $link );

						}

						// Search Arg
						if ( get_search_query() ) {
							$link = add_query_arg( 's', get_search_query(), $link );
						}

						// Post Type Arg
						if ( isset( $_GET['post_type'] ) ) {
							$link = add_query_arg( 'post_type', $_GET['post_type'], $link );
						}

						// Query type Arg
						if ( $query_type == 'or' && ! ( sizeof( $current_filter ) == 1 && isset( $_chosen_attributes[ $taxonomy ]['terms'] ) && is_array( $_chosen_attributes[ $taxonomy ]['terms'] ) && in_array( $term->term_id, $_chosen_attributes[ $taxonomy ]['terms'] ) ) ) {
							$link = add_query_arg( 'query_type_' . sanitize_title( $instance['attribute'] ), 'or', $link );
						}

						echo '<li ' . $class . '>';

						echo ( $count > 0 || $option_is_set ) ? '<a title="'.$term->name.'" href="' . esc_url( apply_filters( 'woocommerce_layered_nav_link', $link ) ) . '">' : '<span>';
						$image = ColorSwatch::getSwatchImage($term->term_id);

						if($image)
						{
							echo '<span class="swatch-item"><img src="'.$image.'"/></span>';
						}else{
							echo '<span class="swatch-item">'.$term->name.'</span>';
						}


						echo ( $count > 0 || $option_is_set ) ? '</a>' : '</span>';
						if($display_qty == 1)
						{
							echo ' <span class="count">(' . $count . ')</span>';
						}

						echo '</li>';

					}

					echo '</ul>';

				} // End display type conditional
			}

			$this->widget_end( $args );

			if ( ! $found ) {
				ob_end_clean();
			} else {
				echo ob_get_clean();
			}
		}
	}
}
