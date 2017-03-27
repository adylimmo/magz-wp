jQuery(function ($) {

    /**
     * Call function that will check field visibility on page load
     * 
     * @returns void
     */
    (function () {
        volcannoPageTemplateFieldVisibility();
    })();

    /**
     * Call function that will check field visibility on value change
     * 
     * @returns void
     */
    $(document).on('change', '#page_template', function () {
        volcannoPageTemplateFieldVisibility();
    });

    /**
     * Loops through configuration array and finds tabs that will be hidden
     * or visible
     * 
     * @returns void
     */
    function volcannoPageTemplateFieldVisibility() {

        // check that configuration object exists
        if (VolcannoRwmbConfig != "undefined" && VolcannoRwmbConfig.tabs != "undefined") {
            var selectedVal = $('#page_template').val();

            var showTabs = new Array();
            var hideTabs = new Array();

            // loop through array and find tabs that we will hide or show
            _.each(VolcannoRwmbConfig.tabs, function (value, key, list) {

                if (!_.isEmpty(value)) {

                    //if value is array check presense of value in array
                    // and add to appropriate array
                    if (_.isArray(value)) {

                        if ($.inArray(selectedVal, value) != -1) {
                            showTabs.push(key);
                        } else {
                            hideTabs.push(key);
                        }
                        // if value is string, compare values
                        // and add to appropriate array
                    } else {

                        if (value == selectedVal) {
                            showTabs.push(key);
                        } else {
                            hideTabs.push(key);
                        }
                    }

                } else {
                    showTabs.push(key);
                }

            });

            // call function that will change visibility in DOM
            volcannoChangeFieldVisibility(showTabs, hideTabs);
        }
    }

    /**
     * Loop through arrays for hidden and visible tabs and apply changes in DOM
     * 
     * @param array showTabs
     * @param array hideTabs
     * @returns void
     */
    function volcannoChangeFieldVisibility(showTabs, hideTabs) {

        // show tabs
        _.each(showTabs, function (value, key, list) {
            var selector = ".rwmb-tab-" + value;

            $(selector).show();
        });

        // hide tabs
        _.each(hideTabs, function (value, key, list) {
            var selector = ".rwmb-tab-" + value;

            $(selector).hide();
        });

    }


    /*
     * Change MetaBox fields visibility based on post format value.
     */

    (function () {
        var selectedVal = $('#post-formats-select .post-format:checked').val();
        if (selectedVal == '0') {
            $('#pt_video_url, #pt_quote_text, #pt_quote_author, #pt_audio_files, #pt_gallery_images_description, #pt_link_title, #pt_link_url').closest('.rwmb-field').hide();
        } else if (selectedVal == 'video') {
            $('#pt_quote_text, #pt_audio_files, #pt_quote_author, #pt_gallery_images_description, #pt_link_title, #pt_link_url, .rwmb-heading-wrapper h4').closest('.rwmb-field').hide();
        } else if (selectedVal == 'quote') {
            $('#pt_video_url, #pt_audio_files, #pt_gallery_images_description, #pt_link_title, #pt_link_url, .rwmb-heading-wrapper h4').closest('.rwmb-field').hide();
        } else if (selectedVal == 'audio') {
            $('#pt_video_url, #pt_quote_text, #pt_quote_author, #pt_gallery_images_description, #pt_link_title, #pt_link_url, .rwmb-heading-wrapper h4').closest('.rwmb-field').hide();
        } else if (selectedVal == 'gallery') {
            $('#pt_video_url, #pt_quote_text, #pt_quote_author, #pt_audio_files, #pt_link_title, #pt_link_url, .rwmb-heading-wrapper h4').closest('.rwmb-field').hide();
        } else if (selectedVal == 'link') {
            $('#pt_video_url, #pt_quote_text, #pt_quote_author, #pt_audio_files, #pt_gallery_images_description, .rwmb-heading-wrapper h4').closest('.rwmb-field').hide();
        }
    })();

    $('#post-formats-select .post-format').change(function () {
        var selectedVal = $(this).val();
        if (selectedVal == '0') {
            $('#pt_video_url, #pt_quote_text, #pt_quote_author, #pt_audio_files, #pt_gallery_images_description, #pt_link_title, #pt_link_url').closest('.rwmb-field').hide();
            $('.rwmb-heading-wrapper h4').closest('.rwmb-field').show();
        } else if (selectedVal == 'video') {
            $('#pt_video_url, #pt_quote_text, #pt_quote_author, #pt_audio_files, #pt_gallery_images_description, #pt_link_title, #pt_link_url, .rwmb-heading-wrapper h4').closest('.rwmb-field').hide();
            $('#pt_video_url').closest('.rwmb-field').show();
        } else if (selectedVal == 'quote') {
            $('#pt_video_url, #pt_quote_text, #pt_audio_files, #pt_gallery_images_description, #pt_link_title, #pt_link_url, .rwmb-heading-wrapper h4').closest('.rwmb-field').hide();
            $('#pt_quote_text, #pt_quote_author').closest('.rwmb-field').show();
        } else if (selectedVal == 'audio') {
            $('#pt_video_url, #pt_quote_text, #pt_quote_author, #pt_audio_files, #pt_gallery_images_description, #pt_link_title, #pt_link_url, .rwmb-heading-wrapper h4').closest('.rwmb-field').hide();
            $('#pt_audio_files').closest('.rwmb-field').show();
        } else if (selectedVal == 'gallery') {
            $('#pt_video_url, #pt_quote_text, #pt_quote_author, #pt_audio_files, #pt_gallery_images_description, #pt_link_title, #pt_link_url, .rwmb-heading-wrapper h4').closest('.rwmb-field').hide();
            $('#pt_gallery_images_description').closest('.rwmb-field').show();
        } else if (selectedVal == 'link') {
            $('#pt_video_url, #pt_quote_text, #pt_quote_author, #pt_audio_files, #pt_gallery_images_description, .rwmb-heading-wrapper h4').closest('.rwmb-field').hide();
            $('#pt_link_title, #pt_link_url').closest('.rwmb-field').show();
        }
    });


});