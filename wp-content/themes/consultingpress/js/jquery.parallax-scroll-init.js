/* <![CDATA[ */
jQuery(window).on('load', function(){
    'use strict';
    // IMAGE PARALLAX ANIMATION
    if (!VolcannoInclude.isTouchDevice() || !VolcannoInclude.isIOSDevice()) {
        ParallaxScroll.init();
        VolcannoInclude.imgParallaxAnimation();
    }
});
/* ]]> */