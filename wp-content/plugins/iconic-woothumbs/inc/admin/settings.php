<?php
add_filter( 'wpsf_register_settings_iconic_woothumbs', 'iconic_woothumbs_settings' );

/**
 * WooThumbs Settings
 *
 * @param arr $wpsf_settings
 * @return arr
 */
function iconic_woothumbs_settings( $wpsf_settings ) {

    $wpsf_settings = array(

        /**
         * Define: Tabs
         *
         * Define the tabs and their IDs
         */
        'tabs' => array(

            array(
                'id' => 'dashboard',
                'title' => __('Dashboard', 'iconic-woothumbs')
            ),

            array(
                'id' => 'display',
                'title' => __('Display', 'iconic-woothumbs')
            ),

            array(
                'id' => 'carousel',
                'title' => __('Carousel', 'iconic-woothumbs')
            ),

            array(
                'id' => 'navigation',
                'title' => __('Navigation', 'iconic-woothumbs')
            ),

            /* array(
                'id' => 'caption',
                'title' => __('Caption', 'iconic-woothumbs')
            ), */

            array(
                'id' => 'zoom',
                'title' => __('Zoom', 'iconic-woothumbs')
            ),

            array(
                'id' => 'fullscreen',
                'title' => __('Fullscreen', 'iconic-woothumbs')
            ),

            array(
                'id' => 'responsive',
                'title' => __('Responsive', 'iconic-woothumbs')
            )

        ),

        /**
         * Define: Sections
         *
         * Define the sections within our tabs, and give each
         * section a related tab ID
         */
        'sections' => array(

            array(
                'tab_id' => 'display',
                'section_id' => 'general',
                'section_title' => __('Display Settings', 'iconic-woothumbs'),
                'section_description' => '',
                'section_order' => 0,
                'fields' => array(
                    array(
                        'id' => 'width',
                        'title' => __('Width (%)', 'iconic-woothumbs'),
                        'subtitle' => __('Enter a percentage for the width of the image display. This is entirely theme dependant, but usually resides around 48.', 'iconic-woothumbs'),
                        'type' => 'number',
                        'default' => 48
                    ),

                    array(
                        'id' => 'position',
                        'title' => __('Position', 'iconic-woothumbs'),
                        'subtitle' => __('Choose a position for the images. Go to the Responsive tab to change the position at a certain breakpoint.', 'iconic-woothumbs'),
                        'type' => 'select',
                        'default' => 'left',
                        'choices' => array(
                            'left' => __('Left', 'iconic-woothumbs'),
                            'right' => __('Right', 'iconic-woothumbs'),
                            'none' => __('None', 'iconic-woothumbs')
                        )
                    ),

                    array(
                        'id' => 'large_image_size',
                        'title' => __('Large Image Size', 'iconic-woothumbs'),
                        'subtitle' => __('Choose a size for large images. Hover zoom and fullscreen will both use the size you select here.', 'iconic-woothumbs'),
                        'type' => 'select',
                        'default' => 'full',
                        'choices' => Iconic_WooThumbs_Settings::get_image_sizes()
                    ),

                    array(
                        'id' => 'icon_colours',
                        'title' => __('Icon Colours', 'iconic-woothumbs'),
                        'subtitle' => '',
                        'type' => 'color',
                        'default' => '#7c7c7c'
                    ),

                    array(
                        'id' => 'icons_hover',
                        'title' => __('Show Icons on Hover?', 'iconic-woothumbs'),
                        'subtitle' => __('When enabled, icons will only be visible when the image is hovered.', 'iconic-woothumbs'),
                        'type' => 'select',
                        'default' => '0',
                        'choices' => array(
                            '1' => __('Yes', 'iconic-woothumbs'),
                            '0' => __('No', 'iconic-woothumbs')
                        )
                    ),

                    array(
                        'id' => 'icons_tooltips',
                        'title' => __('Show Icon Tooltips?', 'iconic-woothumbs'),
                        'subtitle' => __('When icons are hovered, a tooltip will be displayed.', 'iconic-woothumbs'),
                        'type' => 'select',
                        'default' => '0',
                        'choices' => array(
                            '1' => __('Yes', 'iconic-woothumbs'),
                            '0' => __('No', 'iconic-woothumbs')
                        )
                    )
                )

            ),

            array(
                'tab_id' => 'carousel',
                'section_id' => 'general',
                'section_title' => __('Carousel Settings', 'iconic-woothumbs'),
                'section_description' => '',
                'section_order' => 0,
                'fields' => array(
                    array(
                        'id' => 'mode',
                        'title' => __('Mode', 'iconic-woothumbs'),
                        'subtitle' => __('How should the main images transition?', 'iconic-woothumbs'),
                        'type' => 'select',
                        'default' => 'horizontal',
                        'choices' => array(
                            'horizontal' => __('Horizontal', 'iconic-woothumbs'),
                            'vertical' => __('Vertical', 'iconic-woothumbs'),
                            'fade' => __('Fade', 'iconic-woothumbs')
                        )
                    ),

                    array(
                        'id' => 'transition_speed',
                        'title' => __('Transition Speed (ms)', 'iconic-woothumbs'),
                        'subtitle' => __('The speed at which images slide or fade in milliseconds.', 'iconic-woothumbs'),
                        'type' => 'number',
                        'default' => 250
                    ),

                    array(
                        'id' => 'autoplay',
                        'title' => __('Autoplay?', 'iconic-woothumbs'),
                        'subtitle' => __('When enabled, the slider images will automatically transition.', 'iconic-woothumbs'),
                        'type' => 'select',
                        'default' => '0',
                        'choices' => array(
                            '1' => __('Yes', 'iconic-woothumbs'),
                            '0' => __('No', 'iconic-woothumbs')
                        )
                    ),

                    array(
                        'id' => 'duration',
                        'title' => __('Slide Duration (ms)', 'iconic-woothumbs'),
                        'subtitle' => __('If you have autoplay set to true, then you can set the slide duration for each slide.', 'iconic-woothumbs'),
                        'type' => 'number',
                        'default' => 5000
                    ),

                    array(
                        'id' => 'infinite_loop',
                        'title' => __('Enable Infinite Loop?', 'iconic-woothumbs'),
                        'subtitle' => __('When you get to the last image, loop back to the first. Horizontal or Vertical modes only.', 'iconic-woothumbs'),
                        'type' => 'select',
                        'default' => '1',
                        'choices' => array(
                            '1' => __('Yes', 'iconic-woothumbs'),
                            '0' => __('No', 'iconic-woothumbs')
                        )
                    )
                )

            ),

            array(
                'tab_id' => 'navigation',
                'section_id' => 'general',
                'section_title' => __('General Navigation Settings', 'iconic-woothumbs'),
                'section_description' => '',
                'section_order' => 10,
                'fields' => array(
                    array(
                        'id' => 'controls',
                        'title' => __('Enable Prev/Next Arrows?', 'iconic-woothumbs'),
                        'subtitle' => __('This will display prev/next arrows over the main slider image.', 'iconic-woothumbs'),
                        'type' => 'select',
                        'default' => '1',
                        'choices' => array(
                            '1' => __('Yes', 'iconic-woothumbs'),
                            '0' => __('No', 'iconic-woothumbs')
                        )
                    )
                )

            ),

            array(
                'tab_id' => 'navigation',
                'section_id' => 'thumbnails',
                'section_title' => __('Thumbnails Settings', 'iconic-woothumbs'),
                'section_description' => '',
                'section_order' => 20,
                'fields' => array(
                    array(
                        'id' => 'enable',
                        'title' => __('Enable Thumbnails?', 'iconic-woothumbs'),
                        'subtitle' => __('Choose whether to enable the thumbnail navigation.', 'iconic-woothumbs'),
                        'type' => 'select',
                        'default' => '1',
                        'choices' => array(
                            '1' => __('Yes', 'iconic-woothumbs'),
                            '0' => __('No', 'iconic-woothumbs')
                        )
                    ),

                    array(
                        'id' => 'type',
                        'title' => __('Thumbnails Type', 'iconic-woothumbs'),
                        'subtitle' => __('Choose either sliding or stacked thumbnails.', 'iconic-woothumbs'),
                        'type' => 'select',
                        'default' => 'sliding',
                        'choices' => array(
                            'sliding' => __('Sliding thumbnails', 'iconic-woothumbs'),
                            'stacked' => __('Stacked Thumbnails', 'iconic-woothumbs')
                        )
                    ),

                    array(
                        'id' => 'controls',
                        'title' => __('Enable Thumbnail Controls?', 'iconic-woothumbs'),
                        'subtitle' => __('If you are using sliding thumbnails, enable or disable the prev/next controls.', 'iconic-woothumbs'),
                        'type' => 'select',
                        'default' => '1',
                        'choices' => array(
                            '1' => __('Yes', 'iconic-woothumbs'),
                            '0' => __('No', 'iconic-woothumbs')
                        )
                    ),

                    array(
                        'id' => 'position',
                        'title' => __('Thumbnails Position', 'iconic-woothumbs'),
                        'subtitle' => __('Choose where the thumbnails are positioned in relation to the main images.', 'iconic-woothumbs'),
                        'type' => 'select',
                        'default' => 'below',
                        'choices' => array(
                            'above' => __('Above', 'iconic-woothumbs'),
                            'below' => __('Below', 'iconic-woothumbs'),
                            'left' => __('Left', 'iconic-woothumbs'),
                            'right' => __('Right', 'iconic-woothumbs')
                        )
                    ),

                    array(
                        'id' => 'width',
                        'title' => __('Width (%)', 'iconic-woothumbs'),
                        'subtitle' => __('If you chose to position your thumbanils on the left or right, enter a percentage width for them.', 'iconic-woothumbs'),
                        'type' => 'number',
                        'default' => 20
                    ),

                    array(
                        'id' => 'count',
                        'title' => __('Thumbnails Count', 'iconic-woothumbs'),
                        'subtitle' => __('The number of thumbnails to display in a row.', 'iconic-woothumbs'),
                        'type' => 'number',
                        'default' => 4
                    ),

                    array(
                        'id' => 'transition_speed',
                        'title' => __('Thumbnails Transition Speed (ms)', 'iconic-woothumbs'),
                        'subtitle' => __('The speed at which the sliding thumbnail navigation moves in milliseconds.', 'iconic-woothumbs'),
                        'type' => 'number',
                        'default' => 250
                    ),

                    array(
                        'id' => 'spacing',
                        'title' => __('Thumbnails Spacing', 'iconic-woothumbs'),
                        'subtitle' => __('The space between each thumbnail.', 'iconic-woothumbs'),
                        'type' => 'number',
                        'default' => 0
                    )
                )

            ),

            array(
                'tab_id' => 'navigation',
                'section_id' => 'bullets',
                'section_title' => __('Bullets Settings', 'iconic-woothumbs'),
                'section_description' => '',
                'section_order' => 30,
                'fields' => array(
                    array(
                        'id' => 'enable',
                        'title' => __('Enable Bullets?', 'iconic-woothumbs'),
                        'subtitle' => __('Choose whether to enable the bullet navigation.', 'iconic-woothumbs'),
                        'type' => 'select',
                        'default' => '0',
                        'choices' => array(
                            '1' => __('Yes', 'iconic-woothumbs'),
                            '0' => __('No', 'iconic-woothumbs')
                        )
                    )
                )

            ),
            /*
            array(
                'tab_id' => 'caption',
                'section_id' => 'general',
                'section_title' => __('Caption Settings', 'iconic-woothumbs'),
                'section_description' => '',
                'section_order' => 10,
                'fields' => array(
                    array(
                        'id' => 'enable',
                        'title' => __('Enable Capton?', 'iconic-woothumbs'),
                        'subtitle' => __('Choose whether to enable the image captions.', 'iconic-woothumbs'),
                        'type' => 'select',
                        'default' => '0',
                        'choices' => array(
                            '1' => __('Yes', 'iconic-woothumbs'),
                            '0' => __('No', 'iconic-woothumbs')
                        )
                    ),
                    array(
                        'id' => 'type',
                        'title' => __('Caption Type', 'iconic-woothumbs'),
                        'subtitle' => __('Display a single caption for the main image, or multiple captions for each thumbnail (when thumbnails are in use).', 'iconic-woothumbs'),
                        'type' => 'select',
                        'default' => 'single',
                        'choices' => array(
                            'single' => __('Single', 'iconic-woothumbs'),
                            'thumbnails' => __('Thumbnails', 'iconic-woothumbs')
                        )
                    ),
                    array(
                        'id' => 'position',
                        'title' => __('Position', 'iconic-woothumbs'),
                        'subtitle' => '',
                        'type' => 'select',
                        'default' => 'below',
                        'choices' => array(
                            'above' => __('Above', 'iconic-woothumbs'),
                            'below' => __('Below', 'iconic-woothumbs')
                        )
                    )
                )

            ),
            */
            array(
                'tab_id' => 'zoom',
                'section_id' => 'general',
                'section_title' => __('General Zoom Settings', 'iconic-woothumbs'),
                'section_description' => '',
                'section_order' => 10,
                'fields' => array(
                    array(
                        'id' => 'enable',
                        'title' => __('Enable Hover Zoom?', 'iconic-woothumbs'),
                        'subtitle' => '',
                        'type' => 'select',
                        'default' => '1',
                        'choices' => array(
                            '1' => __('Yes', 'iconic-woothumbs'),
                            '0' => __('No', 'iconic-woothumbs')
                        )
                    ),

                    array(
                        'id' => 'zoom_type',
                        'title' => __('Zoom Type', 'iconic-woothumbs'),
                        'subtitle' => '',
                        'type' => 'select',
                        'default' => 'inner',
                        'choices' => array(
                            'inner' => __('Inner', 'iconic-woothumbs'),
                            'standard' => __('Outside', 'iconic-woothumbs'),
                            'follow' => __('Follow', 'iconic-woothumbs')
                        )
                    )
                )

            ),

            array(
                'tab_id' => 'zoom',
                'section_id' => 'outside_follow_zoom',
                'section_title' => __('Outside and Follow Zoom Settings', 'iconic-woothumbs'),
                'section_description' => '',
                'section_order' => 20,
                'fields' => array(
                    array(
                        'id' => 'lens_width',
                        'title' => __('Lens Width', 'iconic-woothumbs'),
                        'subtitle' => __('The width of your zoom lens.', 'iconic-woothumbs'),
                        'type' => 'number',
                        'default' => 200
                    ),

                    array(
                        'id' => 'lens_height',
                        'title' => __('Lens Height', 'iconic-woothumbs'),
                        'subtitle' => __('The height of your zoom lens.', 'iconic-woothumbs'),
                        'type' => 'number',
                        'default' => 200
                    )
                )

            ),

            array(
                'tab_id' => 'zoom',
                'section_id' => 'outside_zoom',
                'section_title' => __('Outside Zoom Settings', 'iconic-woothumbs'),
                'section_description' => '',
                'section_order' => 30,
                'fields' => array(
                    array(
                        'id' => 'zoom_position',
                        'title' => __('Zoom Position', 'iconic-woothumbs'),
                        'subtitle' => __('Choose the position of your zoomed image in relation to the main image.', 'iconic-woothumbs'),
                        'type' => 'select',
                        'default' => 'right',
                        'choices' => array(
                            'right' => __('Right', 'iconic-woothumbs'),
                            'left' => __('Left', 'iconic-woothumbs')
                        )
                    ),

                    array(
                        'id' => 'lens_colour',
                        'title' => __('Lens Colour', 'iconic-woothumbs'),
                        'subtitle' => '',
                        'type' => 'color',
                        'default' => '#000000'
                    ),

                    array(
                        'id' => 'lens_opacity',
                        'title' => __('Lens opacity', 'iconic-woothumbs'),
                        'subtitle' => __('Set an opacity between 0 and 1 for the lens.', 'iconic-woothumbs'),
                        'type' => 'number',
                        'default' => 0.8
                    ),
                )

            ),

            array(
                'tab_id' => 'zoom',
                'section_id' => 'follow_zoom',
                'section_title' => __('Follow Zoom Settings', 'iconic-woothumbs'),
                'section_description' => '',
                'section_order' => 40,
                'fields' => array(
                    array(
                        'id' => 'zoom_shape',
                        'title' => __('Zoom Shape', 'iconic-woothumbs'),
                        'subtitle' => '',
                        'type' => 'select',
                        'default' => 'circular',
                        'choices' => array(
                            'circular' => __('Circular', 'iconic-woothumbs'),
                            'square' => __('Square', 'iconic-woothumbs')
                        )
                    ),
                )

            ),

            array(
                'tab_id' => 'fullscreen',
                'section_id' => 'general',
                'section_title' => __('Fullscreen Settings', 'iconic-woothumbs'),
                'section_description' => '',
                'section_order' => 0,
                'fields' => array(
                    array(
                        'id' => 'enable',
                        'title' => __('Enable Fullscreen?', 'iconic-woothumbs'),
                        'subtitle' => '',
                        'type' => 'select',
                        'default' => '1',
                        'choices' => array(
                            '1' => __('Yes', 'iconic-woothumbs'),
                            '0' => __('No', 'iconic-woothumbs')
                        )
                    ),

                    array(
                        'id' => 'click_anywhere',
                        'title' => __('Enable Click Anywhere?', 'iconic-woothumbs'),
                        'subtitle' => __('When enabled, click anywhere on the main image to trigger fullscreen.', 'iconic-woothumbs'),
                        'type' => 'select',
                        'default' => '0',
                        'choices' => array(
                            '1' => __('Yes', 'iconic-woothumbs'),
                            '0' => __('No', 'iconic-woothumbs')
                        )
                    ),

                    array(
                        'id' => 'image_title',
                        'title' => __('Enable Image Title?', 'iconic-woothumbs'),
                        'subtitle' => __('When enabled, the image title will be visible when viewing fullscreen.', 'iconic-woothumbs'),
                        'type' => 'select',
                        'default' => '1',
                        'choices' => array(
                            '1' => __('Yes', 'iconic-woothumbs'),
                            '0' => __('No', 'iconic-woothumbs')
                        )
                    ),
                )

            ),

            array(
                'tab_id' => 'responsive',
                'section_id' => 'general',
                'section_title' => __('Responsive Settings', 'iconic-woothumbs'),
                'section_description' => '',
                'section_order' => 0,
                'fields' => array(
                    array(
                        'id' => 'breakpoint_enable',
                        'title' => __('Enable Breakpoint?', 'iconic-woothumbs'),
                        'subtitle' => __('If your website is responsive, you can change the width of the slider after a certain breakpoint.', 'iconic-woothumbs'),
                        'type' => 'select',
                        'default' => '1',
                        'choices' => array(
                            '1' => __('Yes', 'iconic-woothumbs'),
                            '0' => __('No', 'iconic-woothumbs')
                        )
                    ),

                    array(
                        'id' => 'breakpoint',
                        'title' => __('Breakpoint (px)', 'iconic-woothumbs'),
                        'subtitle' => __('The slider width will be affected after the breakpoint.', 'iconic-woothumbs'),
                        'type' => 'number',
                        'default' => 768
                    ),

                    array(
                        'id' => 'width',
                        'title' => __('Width After Breakpoint (%)', 'iconic-woothumbs'),
                        'subtitle' => __('The width of the images display after the breakpoint.', 'iconic-woothumbs'),
                        'type' => 'number',
                        'default' => 100
                    ),

                    array(
                        'id' => 'position',
                        'title' => __('Position After Breakpoint', 'iconic-woothumbs'),
                        'subtitle' => __('Choose a position for the images after the breakpoint.', 'iconic-woothumbs'),
                        'type' => 'select',
                        'default' => 'none',
                        'choices' => array(
                            'left' => __('Left', 'iconic-woothumbs'),
                            'right' => __('Right', 'iconic-woothumbs'),
                            'none' => __('None', 'iconic-woothumbs')
                        )
                    ),

                    array(
                        'id' => 'thumbnails_below',
                        'title' => __('Move Thumbnails Below After Breakpoint?', 'iconic-woothumbs'),
                        'subtitle' => __('Choose whether to move the thumbnail navigation below the main image display after the breakpoint.', 'iconic-woothumbs'),
                        'type' => 'select',
                        'default' => '1',
                        'choices' => array(
                            '1' => __('Yes', 'iconic-woothumbs'),
                            '0' => __('No', 'iconic-woothumbs')
                        )
                    ),

                    array(
                        'id' => 'thumbnails_count',
                        'title' => __('Thumbnails Count After Breakpoint', 'iconic-woothumbs'),
                        'subtitle' => __('The number of thumbnails to display in a row after the breakpoint.', 'iconic-woothumbs'),
                        'type' => 'number',
                        'default' => 3
                    ),
                )

            ),

            'welcome' => array(
                'tab_id' => 'dashboard',
                'section_id' => 'welcome',
                'section_title' => __('Welcome', 'iconic-woothumbs'),
                'section_description' =>
                    '<h3>' . __('Welcome to WooThumbs', 'iconic-woothumbs') . '</h3>' .
                    '<p>' . __("You're awesome! We've been looking forward to having you onboard, and we're pleased to see the day has finally come.", 'iconic-woothumbs') . '</p>' .
                    '<p>' . sprintf( __('Make yourself at home. If you get stuck, check out the <a href="%s" target="_blank">documentation</a>.', 'iconic-woothumbs'), 'http://docs.iconicwp.com/category/6-woothumbs' ) . '</p>',
                'section_order' => 0,
                'type' => 'message',
                'fields' => array()

            ),

            'license' => array(
                'tab_id' => 'dashboard',
                'section_id' => 'general',
                'section_title' => __('License & Account Settings', 'iconic-woothumbs'),
                'section_description' => '',
                'section_order' => 10,
                'fields' => array(
                    array(
                        'id' => 'account',
                        'title' => __('License', 'iconic-woothumbs'),
                        'subtitle' => __('Activate or sync your license, cancel your subscription, and manage your account information.', 'iconic-woothumbs'),
                        'type' => 'custom',
                        'default' => Iconic_WooThumbs_Licence::account_link()
                    ),
                    array(
                        'id' => 'billing',
                        'title' => __('Billing', 'iconic-woothumbs'),
                        'subtitle' => __('Update your billing information and view previous invoices.', 'iconic-woothumbs'),
                        'type' => 'custom',
                        'default' => Iconic_WooThumbs_Licence::billing_link()
                    ),
                )

            ),

            array(
                'tab_id' => 'dashboard',
                'section_id' => 'tools',
                'section_title' => __('Tools', 'iconic-woothumbs'),
                'section_description' => '',
                'section_order' => 20,
                'fields' => array(
                    array(
                        'id' => 'clear-cache',
                        'title' => __('Clear Image Cache', 'iconic-woothumbs'),
                        'subtitle' => __('Clear the image cache to refresh all product imagery.', 'iconic-woothumbs'),
                        'type' => 'custom',
                        'default' => Iconic_WooThumbs_Settings::clear_image_cache_link()
                    ),
                )

            ),

            array(
                'tab_id' => 'dashboard',
                'section_id' => 'support',
                'section_title' => __('Support', 'iconic-woothumbs'),
                'section_description' => '',
                'section_order' => 30,
                'fields' => array(
                    array(
                        'id' => 'support',
                        'title' => __('Support', 'iconic-woothumbs'),
                        'subtitle' => __('Get premium support with a valid license.', 'iconic-woothumbs'),
                        'type' => 'custom',
                        'default' => Iconic_WooThumbs_Licence::contact_link()
                    ),
                    array(
                        'id' => 'documentation',
                        'title' => __('Documentation', 'iconic-woothumbs'),
                        'subtitle' => __('Read the plugin documentation.', 'iconic-woothumbs'),
                        'type' => 'custom',
                        'default' => Iconic_WooThumbs_Settings::documentation_link()
                    ),
                )

            ),

        )

    );

    if( ICONIC_WOOTHUMBS_IS_ENVATO ) {
	    unset( $wpsf_settings['sections']['license'] );
	    $wpsf_settings['sections']['welcome']['section_description'] .=
	    	'<p>' . __('Below you will find some useful plugin tools, and a link to support.', 'iconic-woothumbs') . '</p>' .
	    	'<p class="iconic-woothumbs-notice" style="padding: 20px; background-color: #DB5C59; margin: 2em 0 1em; border-radius: 5px; color: #fff; -webkit-font-smoothing: antialiased; font-weight: bold;">'.
	    	sprintf( __('NOTICE! All Iconic plugins will soon be moving away from Envato, so you will no longer be able to receive updates from CodeCanyon. <br><br>Please <a style="color: #fff; text-decoration: underline;" href="%s">send a request</a>, along with your <a style="color: #fff; text-decoration: underline;" href="%s" target="_blank">purchase code</a>, and I will provide you with a new yearly license and instructions on how to set it up. You will also be given a full year of support from your original date or purchase.', 'iconic-woothumbs'), Iconic_WooThumbs_Licence::get_contact_url( 'billing_issue', __( "Hello, I would like to request a new yearly license code. My Envato purchase code is: ", 'iconic-woothumbs' ) ), 'https://iconicwp.com/files/purchase-code.png' ) .
	    	'</p>';
    } else {
	    $wpsf_settings['sections']['welcome']['section_description'] .= '<p>' . __('Below you will find useful links to manage your license and billing, some plugin tools, and a link to support.', 'iconic-woothumbs') . '</p>';
    }

    return $wpsf_settings;

}