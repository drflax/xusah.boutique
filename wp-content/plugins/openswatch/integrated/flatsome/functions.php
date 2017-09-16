<?php
// Add custom Theme Functions here

add_action( 'flatsome_footer', 'bb_custom_footer' );
function bb_custom_footer() {
  echo do_shortcode('[fl_builder_insert_layout slug="footer"]');  
}

function your_openswatch_image_swatch_html($images,$productId,$attachment_ids)
{
// return $images;
global $post, $woocommerce, $product;
$product = wc_get_product( $productId );
$post = get_post($productId);
$slider_classes = array('slider','slider-nav-small','mb-half');

// Image Zoom
if(get_theme_mod('product_zoom')){
  	$slider_classes[] = 'has-image-zoom';
}

$rtl = 'false';
if(is_rtl()) $rtl = 'true';

$image_size = 'shop_single';


   if(count($attachment_ids) > 0)
   {
   	?>
  <div id="vna-si">
	
		
		<div class="product-gallery-slider <?php echo implode(' ', $slider_classes); ?>"
				data-flickity-options='{ 
		            "cellAlign": "center",
		            "wrapAround": true,
		            "autoPlay": false,
		            "prevNextButtons":true,
		            "adaptiveHeight": true,
		            "percentPosition": true,
		            "imagesLoaded": true,
		            "lazyLoad": 1,
		            "dragThreshold" : 15,
		            "pageDots": false,
		            "rightToLeft": false
		        }'>

		<?php
		        
                foreach( $attachment_ids as $key =>  $attachment_id)
                {
				$image_title 	= esc_attr( get_the_title( $attachment_id ) );
				$image_caption 	= get_post( $attachment_id )->post_excerpt;
				$image_link  	= wp_get_attachment_url( $attachment_id );
				$image       	= wp_get_attachment_image($attachment_id, apply_filters( 'single_product_large_thumbnail_size', $image_size ), array(
                            'title'	=> $image_title,
                            'alt'	=> $image_title
                        ) );
				
			

				$attachment_count = count( $attachment_ids );

				if ( $attachment_count > 0 ) {
					$gallery = '[product-gallery]';
				} else {
					$gallery = '';
				}
                if($k == 0)
                {
                    echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div class="slide first"><a href="%s" itemprop="image" class="woocommerce-main-image zoom" title="%s" data-rel="prettyPhoto' . $gallery . '">%s</a></div>', $image_link, $image_caption, $image ), $post->ID );
                
                }else{
                    echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div class="slide"><a href="%s" itemprop="image" class="woocommerce-main-image zoom" title="%s" data-rel="prettyPhoto' . $gallery . '">%s</a></div>', $image_link, $image_caption, $image ), $post->ID );
                
                }
				//echo $image;
                //;
				// Add additional images
				//do_action('flatsome_single_product_images');
                }
			
		?>

		</div><!-- .product-gallery-slider -->

<?php


$thumb_count = count($attachment_ids)+1;

// Disable thumbnails if there is only one extra image.

$rtl = 'false';

if(is_rtl()) $rtl = 'true';

$thumb_cell_align = "left";

if ( $attachment_ids ) {
	$loop 		= 0;
	$columns 	= apply_filters( 'woocommerce_product_thumbnails_columns', 4 );

	$gallery_class = array('product-thumbnails','thumbnails');

	if($thumb_count <= 5){
		$gallery_class[] = 'slider-no-arrows';
	}

	$gallery_class[] = 'slider row row-small row-slider slider-nav-small small-columns-4';
	?>

	<div class="<?php echo implode(' ', $gallery_class); ?>"
		data-flickity-options='{
	            "cellAlign": "<?php echo $thumb_cell_align;?>",
	            "wrapAround": false,
	            "autoPlay": false,
	            "prevNextButtons":true,
	            "asNavFor": ".product-gallery-slider",
	            "percentPosition": true,
	            "imagesLoaded": true,
	            "pageDots": false,
	            "rightToLeft": <?php echo $rtl; ?>,
	            "contain": true
	        }'
		><?php


	
		foreach ( $attachment_ids as $key => $attachment_id ) {
            if ( $key == 0 ) : ?>
			<div class="col is-nav-selected first"><a><?php echo wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ) ) ?></a></div>
		    <?php else: ?>
		    <?php
			$classes = array( '' );
			$image_title 	= esc_attr( get_the_title( $attachment_id ) );
			$image_caption 	= esc_attr( get_post_field( 'post_excerpt', $attachment_id ) );
			$image_class = esc_attr( implode( ' ', $classes ) );

			$image       = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ), 0, $attr = array(
				'title'	=> $image_title,
				'alt'	=> $image_title
				) );

			echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '<div class="col"><a class="%s" title="%s" >%s</a></div>', $image_class, $image_caption, $image ), $attachment_id, $post->ID, $image_class );

			$loop++;
			 endif;
		}
	?>

	<?php
} ?>
</div>
   	<?
   }
   exit;
}
add_filter('openswatch_image_swatch_html','your_openswatch_image_swatch_html',10,3);