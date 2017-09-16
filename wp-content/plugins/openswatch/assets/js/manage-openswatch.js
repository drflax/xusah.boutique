( function( $ ) {
    "use strict";
    $(document).ready(function($) {

        $(document).on("click", ".look_upload_image_button", function() {

            $.data(document.body, 'prevElement', $(this).prev());

            window.send_to_editor = function(html) {
                var imgurl = $('img',html).attr('src');
                var inputText = $.data(document.body, 'prevElement');

                if(inputText != undefined && inputText != '')
                {
                    inputText.val(imgurl);
                }

                tb_remove();
            };
            tb_show('', 'media-upload.php?type=image&TB_iframe=true');
            return false;
        });


        var product_gallery_frame;

        jQuery( '.add_product_swatch_images' ).on( 'click', 'a', function( event ) {
            var $el = $( this );
            $('.product_swatch_images').removeClass('swatch-active');
            var $product_images    = $( this).closest('.inside').first().find('.product_swatch_images').first();
            var $image_gallery_ids = $( this).closest('.inside').first().find('.input_product_image_swatch_gallery').first();
            $product_images.addClass('swatch-active');
            event.preventDefault();

            // If the media frame already exists, reopen it.
            if ( product_gallery_frame ) {
                product_gallery_frame.open();
                return;
            }

            // Create the media frame.
            product_gallery_frame = wp.media.frames.product_gallery = wp.media({
                // Set the title of the modal.
                title: $el.data( 'choose' ),
                button: {
                    text: $el.data( 'update' )
                },
                states: [
                    new wp.media.controller.Library({
                        title: $el.data( 'choose' ),
                        filterable: 'all',
                        multiple: true
                    })
                ]
            });

            // When an image is selected, run a callback.
            product_gallery_frame.on( 'select', function() {
                var $product_images    = $( document).find('.swatch-active').first();
                var $image_gallery_ids = $product_images.closest('#product_images_swatch_container').first().find('.input_product_image_swatch_gallery').first();
                var selection = product_gallery_frame.state().get( 'selection' );

                var attachment_ids = $image_gallery_ids.val();

                selection.map( function( attachment ) {
                    attachment = attachment.toJSON();

                    if ( attachment.id ) {
                        attachment_ids   = attachment_ids ? attachment_ids + ',' + attachment.id : attachment.id;
                        var attachment_image = attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;

                        $product_images.append( '<li class="image" data-attachment_id="' + attachment.id + '"><img src="' + attachment_image + '" /><ul class="actions"><li><a href="#" class="delete" title="' + $el.data('delete') + '">' + $el.data('text') + '</a></li></ul></li>' );
                    }
                });

                $image_gallery_ids.val( attachment_ids );
            });

            // Finally, open the modal.
            product_gallery_frame.open();
        });

        // Image ordering
        if($( 'ul.product_swatch_images').length > 0)
        {
            $( 'ul.product_swatch_images' ).sortable({
                items: 'li.image',
                cursor: 'move',
                scrollSensitivity: 40,
                forcePlaceholderSize: true,
                forceHelperSize: false,
                helper: 'clone',
                opacity: 0.65,
                placeholder: 'wc-metabox-sortable-placeholder',
                start: function( event, ui ) {

                    ui.item.css( 'background-color', '#f6f6f6' );
                },
                stop: function( event, ui ) {
                    ui.item.removeAttr( 'style' );
                },
                update: function() {
                    var attachment_ids = '';
                    var $image_gallery_ids = $( this).closest('.inside').find('.input_product_image_swatch_gallery').first();
                    $( this).find('li.image').css( 'cursor', 'default' ).each( function() {
                        var attachment_id = jQuery( this ).attr( 'data-attachment_id' );
                        attachment_ids = attachment_ids + attachment_id + ',';
                    });

                    $image_gallery_ids.val( attachment_ids );
                }
            });
        }


        // Remove images
        $( '.product_swatch_images' ).on( 'click', 'a.delete', function() {
            var current = $( this ).closest( 'li.image' );
            var $image_gallery_ids = $( this).closest('#product_images_swatch_container').find('.input_product_image_swatch_gallery');
            var attachment_ids = '';
            var $ul = $( this).closest('ul.product_swatch_images');
            current.remove();
            $ul.first().find('li.image').css( 'cursor', 'default' ).each( function() {
                var attachment_id = jQuery( this ).attr( 'data-attachment_id' );
                attachment_ids = attachment_ids + attachment_id + ',';
            });
            $image_gallery_ids.val( attachment_ids );
            // remove any lingering tooltips
            $( '#tiptip_holder' ).removeAttr( 'style' );
            $( '#tiptip_arrow' ).removeAttr( 'style' );

            return false;
        });

    });
} )( jQuery );