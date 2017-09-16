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
                var lga = $(html).find('.product-gallery-slider').html();
                var images_options = $('.product-gallery-slider').data('flickity-options');
                
                var thumbnails_options = $('.product-thumbnails').data('flickity-options');
	            var thumbnails_js_flickity = $('.product-thumbnails').flickity(thumbnails_options);
                
                var images_js_flickity = $('.product-gallery-slider').flickity(images_options);
                images_js_flickity.flickity('destroy');
                thumbnails_js_flickity.flickity('destroy');
                
                $('.product-gallery-slider').html($(html).find('.product-gallery-slider').first().html());
                $('.product-thumbnails').html($(html).find('.product-thumbnails').first().html());
                
                images_js_flickity = $('.product-gallery-slider').flickity(images_options);
                thumbnails_js_flickity = $('.product-thumbnails').flickity(thumbnails_options);
            }
           //end your custom code here
        });
    });
} )( jQuery );
