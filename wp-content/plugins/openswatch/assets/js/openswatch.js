/**
 * Created by Vu Anh on 8/26/2015.
 */
(function($) {
    "use strict";
    $(document).ready(function(){

        //start generate frontend
        var product_variations = $('form.variations_form').data('product_variations');
        
        //end

        $('select.openswatch-filters').change(function(){
            $(this).closest('form').submit();
        });


        // colorswatch on list

        $('body').on('click','.product-list-color-swatch a',function(){
            var src = $(this).data('thumb');
            if(src != '')
            {
                $(this).closest('li.product').find('img.wp-post-image').first().attr('src',src);
                $(this).closest('li.product').find('img.wp-post-image').first().attr('srcset',src);
                $(this).closest('.grid-item').find('img.wp-post-image').first().attr('src',src);

            }
        });

    })
} )( jQuery );