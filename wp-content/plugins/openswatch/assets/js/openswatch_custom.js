( function( $ ) {
    "use strict";

    $(document).ready(function(){

        $( document.body ).bind( 'openswatch_update_images',function(event,data){
           //start your custom code here
            // data.html is html string return by filter: openswatch_image_swatch_html ,
            // use add_filter('openswatch_image_swatch_html','your function') to change it.

            // this is for storefont theme
            var html = data.html;
            var productId = data.productId;
            if(html.length > 5)
            {
                $('.woocommerce-product-gallery').replaceWith( html );
                var $form = $('.variations_form');
                var $product          = $form.closest( '.product' );
                var $product_gallery  = $product.find( '.images' );
                $( '.woocommerce-product-gallery' ).wc_product_gallery();
                $form.trigger('reset_image');
            }
           //end your custom code here
        });
    });
} )( jQuery );
