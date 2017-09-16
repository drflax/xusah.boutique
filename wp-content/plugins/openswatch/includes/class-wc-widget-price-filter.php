<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Price Filter Widget and related functions
 *
 * Generates a range slider to filter products by price.
 *
 * @author   WooThemes
 * @category Widgets
 * @package  WooCommerce/Widgets
 * @version  2.3.0
 * @extends  WC_Widget
 */
class OpenSwatch_Widget_Price_Filter extends WC_Openswatch_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->widget_cssclass    = 'openswatch_widget_price_filter';
		$this->widget_description = __( 'Shows a price filter slider in a widget which lets you narrow down the list of shown products when viewing product categories.', 'openswatch' );
		$this->widget_id          = 'openswatch_price_filter';
		$this->widget_name        = __( 'Openswatch Price Filter', 'openswatch' );
		$this->settings           = array(
			'title'  => array(
				'type'  => 'text',
				'std'   => __( 'Filter by price', 'openswatch' ),
				'label' => __( 'Title', 'openswatch' )
			),
            'display_type' => array(
                'type'    => 'select',
                'std'     => 'list',
                'label'   => __( 'Display type', 'openswatch' ),
                'options' => array(
                    'list'     => __( 'List', 'openswatch' ),
                    'dropdown' => __( 'Dropdown', 'openswatch' )
                )
            )
		);

		parent::__construct();
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
		global $_chosen_attributes, $wpdb, $wp;
		global $woocommerce;
		$woo_version = $woocommerce->version;

		if ( ! is_post_type_archive( 'product' ) && ! is_tax( get_object_taxonomies( 'product' ) ) ) {
			return;
		}
		if($woo_version >= 2.6)
		{
			global $wp_the_query;
			if ( ! $wp_the_query->post_count ) {
				return;
			}
		}else{
			if ( sizeof( WC()->query->unfiltered_product_ids ) == 0 ) {
				return; // None shown - return
			}
		}

        $display_type = isset( $instance['display_type'] ) ? $instance['display_type'] : $this->settings['display_type']['std'];
        $min_price = isset( $_GET['min_price'] ) ? esc_attr( $_GET['min_price'] ) : '';
		$max_price = isset( $_GET['max_price'] ) ? esc_attr( $_GET['max_price'] ) : '';

		wp_enqueue_script( 'wc-price-slider' );

		// Remember current filters/search
		$fields = '';

		if ( get_search_query() ) {
			$fields .= '<input type="hidden" name="s" value="' . get_search_query() . '" />';
		}

		if ( ! empty( $_GET['post_type'] ) ) {
			$fields .= '<input type="hidden" name="post_type" value="' . esc_attr( $_GET['post_type'] ) . '" />';
		}

		if ( ! empty ( $_GET['product_cat'] ) ) {
			$fields .= '<input type="hidden" name="product_cat" value="' . esc_attr( $_GET['product_cat'] ) . '" />';
		}

		if ( ! empty( $_GET['product_tag'] ) ) {
			$fields .= '<input type="hidden" name="product_tag" value="' . esc_attr( $_GET['product_tag'] ) . '" />';
		}

		if ( ! empty( $_GET['orderby'] ) ) {
			$fields .= '<input type="hidden" name="orderby" value="' . esc_attr( $_GET['orderby'] ) . '" />';
		}

		if ( $_chosen_attributes ) {
			foreach ( $_chosen_attributes as $attribute => $data ) {
				$taxonomy_filter = 'filter_' . str_replace( 'pa_', '', $attribute );

				$fields .= '<input type="hidden" name="' . esc_attr( $taxonomy_filter ) . '" value="' . esc_attr( implode( ',', $data['terms'] ) ) . '" />';

				if ( 'or' == $data['query_type'] ) {
					$fields .= '<input type="hidden" name="' . esc_attr( str_replace( 'pa_', 'query_type_', $attribute ) ) . '" value="or" />';
				}
			}
		}
		if($woo_version >= 2.6)
		{
			$layered_count = wp_list_pluck( $wp_the_query->posts, 'ID' );

		}else{
			$layered_count = WC()->query->layered_nav_product_ids;
		}

		if ( 0 === sizeof( $layered_count ) ) {
			$min = floor( $wpdb->get_var( "
				SELECT min(meta_value + 0)
				FROM {$wpdb->posts} as posts
				LEFT JOIN {$wpdb->postmeta} as postmeta ON posts.ID = postmeta.post_id
				WHERE meta_key IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_meta_keys', array( '_price', '_min_variation_price' ) ) ) ) . "')
				AND meta_value != ''
			" ) );
			$max = ceil( $wpdb->get_var( "
				SELECT max(meta_value + 0)
				FROM {$wpdb->posts} as posts
				LEFT JOIN {$wpdb->postmeta} as postmeta ON posts.ID = postmeta.post_id
				WHERE meta_key IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_meta_keys', array( '_price' ) ) ) ) . "')
			" ) );
		} else {
			$min = floor( $wpdb->get_var( "
				SELECT min(meta_value + 0)
				FROM {$wpdb->posts} as posts
				LEFT JOIN {$wpdb->postmeta} as postmeta ON posts.ID = postmeta.post_id
				WHERE meta_key IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_meta_keys', array( '_price', '_min_variation_price' ) ) ) ) . "')
				AND meta_value != ''
				AND (
					posts.ID IN (" . implode( ',', array_map( 'absint', $layered_count ) ) . ")
					OR (
						posts.post_parent IN (" . implode( ',', array_map( 'absint', $layered_count ) ) . ")
						AND posts.post_parent != 0
					)
				)
			" ) );
			$max = ceil( $wpdb->get_var( "
				SELECT max(meta_value + 0)
				FROM {$wpdb->posts} as posts
				LEFT JOIN {$wpdb->postmeta} as postmeta ON posts.ID = postmeta.post_id
				WHERE meta_key IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_meta_keys', array( '_price' ) ) ) ) . "')
				AND (
					posts.ID IN (" . implode( ',', array_map( 'absint', $layered_count ) ) . ")
					OR (
						posts.post_parent IN (" . implode( ',', array_map( 'absint', $layered_count ) ) . ")
						AND posts.post_parent != 0
					)
				)
			" ) );
		}

		if ( $min == $max ) {
			return;
		}

		$this->widget_start( $args, $instance );

		if ( '' == get_option( 'permalink_structure' ) ) {
			$form_action = remove_query_arg( array( 'page', 'paged' ), add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
		} else {
			$form_action = preg_replace( '%\/page/[0-9]+%', '', home_url( trailingslashit( $wp->request ) ) );
		}

		if ( wc_tax_enabled() && 'incl' === get_option( 'woocommerce_tax_display_shop' ) && ! wc_prices_include_tax() ) {
			$tax_classes = array_merge( array( '' ), WC_Tax::get_tax_classes() );
			$min         = 0;

			foreach ( $tax_classes as $tax_class ) {
				$tax_rates = WC_Tax::get_rates( $tax_class );
				$class_min = $min + WC_Tax::get_tax_total( WC_Tax::calc_exclusive_tax( $min, $tax_rates ) );
				$class_max = $max + WC_Tax::get_tax_total( WC_Tax::calc_exclusive_tax( $max, $tax_rates ) );

				if ( $min === 0 || $class_min < $min ) {
					$min = $class_min;
				}
				if ( $class_max > $max ) {
					$max = $class_max;
				}
			}
		}
        $ranges = openswatch_price_ranges();

        if($ranges && !empty($ranges))
        {
            if($display_type == 'dropdown'):
            ?>
           <form>
                <div class="openswatch_price_slider_wrapper">
                    <div class="dropdown-select">
                        <?php
                        $selected = implode(',',array($min_price,$max_price));
                        ?>
                        <select id="price_range" class="swatch-filter">
                            <option value=""><?php _e('Price','openswatch'); ?></option>
                            <?php foreach($ranges as $key => $r): ?>
                                <?php
                                    $url = add_query_arg( array('min_price'=>$r['min'],'max_price'=>$r['max']) );
                                ?>
                                <option <?php selected( $selected,  esc_attr($key) ); ?>   value="<?php echo esc_url($url); ?>"><?php echo (string)$r['label']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                </div>
            </form>
            <?php
            wc_enqueue_js( "
						jQuery( '.openswatch_price_slider_wrapper select' ).change( function() {
						    var url = $(this).val();
						    if(url != '')
						    {
						        location.href = url;
						    }
						});
					" );
                else:
                    ?>
                    <ul class="openswatch_price_list_wrapper">
                        <?php
                        $selected = implode(',',array($min_price,$max_price));
                        ?>

                        <?php foreach($ranges as $key => $r): ?>
                            <?php
                                $url = add_query_arg( array('min_price'=>$r['min'],'max_price'=>$r['max']) );
                            ?>
                            <li class="<?php echo esc_attr($key); ?> <?php if(selected( $selected,  esc_attr($key),false )): ?> active <?php endif; ?>"><a href="<?php echo esc_url($url); ?>"><?php echo (string)$r['label']; ?></a></li>

                        <?php endforeach; ?>


                    </ul>
                    <?php
                endif;
        }


		$this->widget_end( $args );
	}
}
