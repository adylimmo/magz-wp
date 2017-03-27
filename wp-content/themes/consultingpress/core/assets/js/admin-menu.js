/* <![CDATA[ */
jQuery(document).ready(function ($) {
    'use strict';

    jQuery(window).on("load resize", function (e) {
        var initialHeight = jQuery(".theme-image").height();
        jQuery(".theme-presentation-box").css("min-height", initialHeight);
        jQuery(".theme-presentation-box .content").css("height", initialHeight);
    });

});
/* ]]> */