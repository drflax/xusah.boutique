<?php
/**
 * Created by PhpStorm.
 * User: anhvnit
 * Date: 5/4/17
 * Time: 23:14
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
global $product;
$get_variations = sizeof( $product->get_children() ) <= apply_filters( 'woocommerce_ajax_variation_threshold', 30, $product );
$attributes = $product->get_variation_attributes();
$available_variations = $get_variations ? $product->get_available_variations() : false;
$enable_pre_select = openwatch_get_option('openwatch_attribute_pre_select');

if(!$swatch_attrs = openwatch_get_option('openwatch_attribute_swatch'))
{
    $swatch_attrs = array();
}
$default = array();
$allow_swatch = false;
foreach($swatch_attrs as $s)
{
    if($s == 1)
    {
        $allow_swatch = true;
    }
}
$openwatch_attribute_image_swatch = openwatch_get_option('openwatch_attribute_image_swatch');

?>
<?php if($available_variations): ?>
<script type="text/javascript">
    (function($) {
        "use strict";
        function swatchImage(productId,option)
        {
            var gallery_html = openwatch_galleries[option];
            $(document.body).trigger('openswatch_update_images',{"html":gallery_html,"productId":productId});

        }
        $(document).ready(function(){
            <?php if($openwatch_attribute_image_swatch):?>
                $(document).on('click','ul#<?php echo $openwatch_attribute_image_swatch?> li',function(){
                    var value = $(this).attr('option-value');
                    swatchImage(<?php echo $product->get_id(); ?>,value);
                });
            <?php endif; ?>
            <?php if(openwatch_get_option('openwatch_attribute_tooltips')): ?>
            $('[data-toggle="tooltip"]').tooltipster();
            <?php endif; ?>

            var attributes = [<?php foreach ( $attributes as $name => $options ): ?> '<?php echo esc_attr( sanitize_title( $name ));?>', <?php endforeach; ?>];
            var $variation_form = $('.variations_form');

            $('li.swatch-item').on('click touchstart',function(){
                if($(this).hasClass('disable'))
                {
                    return;
                }

                var current = $(this);
                if(!current.hasClass('selected'))
                {
                    var value = current.attr('option-value');
                    var selector_name = current.closest('ul').attr('id');
                    if(selector_name == attributes[0])
                    {
                        $('ul.swatch li').each(function(){
                            $(this).removeClass('selected');
                        });
                        $variation_form.find( '.variations select' ).val( '' ).change();
                        $variation_form.trigger('reset_data');

                    }

                    if($("select#"+selector_name).find('option[value="'+value+'"]').length > 0)
                    {
                        $(this).closest('ul').children('li').each(function(){
                            $(this).removeClass('selected');
                            $(this).removeClass('disable');
                        });
                        if(!$(this).hasClass('selected'))
                        {
                            current.addClass('selected');
                            $("select#"+selector_name).val(value).change();
                            $("select#"+selector_name).trigger('change');
                            $variation_form.trigger( 'wc_variation_form' );
                            $variation_form
                                .trigger( 'woocommerce_variation_select_change' )
                                .trigger( 'check_variations', [ '', false ] );
                        }
                    }else{
                        current.addClass('disable');
                    }
                    <?php if($enable_pre_select): ?>

                    if(selector_name == attributes[0])
                    {
                        var check = false;
                        $('ul#'+selector_name+' li').each(function(){
                            if($(this).hasClass('selected'))
                            {
                                $(this).trigger('click');
                                check = true;

                            }
                        });
                        if(check)
                        {
                            for(var i = 1;i<attributes.length;i++)
                            {
                                var attribute = attributes[i];
                                var check = false;
                                $('ul#'+attribute+' li').each(function(){
                                    if($(this).hasClass('selected'))
                                    {
                                        check = true;
                                    }
                                });

                                if(!check)
                                {
                                    if($('select#'+attribute+' option').length > 1)
                                    {
                                        var value = $('select#'+attribute+' option:last-child').val();
                                        var enable_values = [];
                                        $('select#'+attribute+' option').each(function(){
                                            if($(this).val())
                                            {
                                                enable_values.push($(this).val());
                                            }
                                        });
                                        $('ul#'+attribute+' li').each(function(){
                                            if(value == $(this).attr('option-value'))
                                            {
                                                $(this).removeClass('disable');
                                            }else{
                                                $(this).addClass('disable');
                                            }

                                        });
                                        for(var i = 0; i < enable_values.length; i++)
                                        {
                                            $('ul#'+attribute+' li[option-value="'+enable_values[i]+'"]').removeClass('disable');
                                        }


                                        $('ul#'+attribute+' li[option-value="'+value+'"]').trigger('click');
                                        $('select#'+attribute+' option[value="'+value+'"]').prop('selected',true);
                                        $variation_form.trigger( 'wc_variation_form' );
                                        $variation_form
                                            .trigger( 'woocommerce_variation_select_change' )
                                            .trigger( 'check_variations', [ '', false ] );
                                    }else{
                                        $('ul#'+attribute+' li').each(function(){
                                            $(this).addClass('disable');
                                        });
                                    }

                                }

                            }
                        }
                    }
                    <?php endif; ?>
                }
            });

            $variation_form.on('wc_variation_form', function() {
                $( this ).on( 'click', '.reset_variations', function( event ) {
                    $('ul.swatch li').each(function(){
                        $(this).removeClass('selected');
                    });

                });
            });

            $variation_form.on('reset_data',function(){
                $variation_form.find( '.variations select').each(function(){
                    if($(this).val() == '')
                    {
                        var id = $(this).attr('id');
                        $('ul#'+id+' li').removeClass('selected');
                    }
                });
            });

            $variation_form.on('wc_variation_form',function(){
               
                if(typeof openwatch_pre_select != undefined && openwatch_pre_select == "1")
                {
                    if(typeof openwatch_product_id != undefined)
                    {
                        $('#'+openwatch_swatch_attr+' li.selected').click();

                    }
                }
            });


        });

    } )( jQuery );
</script>
<?php endif; ?>