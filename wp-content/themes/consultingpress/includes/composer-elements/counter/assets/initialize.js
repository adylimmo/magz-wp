jQuery(document).ready(function ($) {
    'use strict';

    jQuery(".odometer-container").waypoint(function () {
        jQuery(".odometer").each(function () {
            var v = jQuery(this).data("to");
            var speed = jQuery(this).data("speed");
            var o = new Odometer({
                el: this,
                value: 0,
                duration: speed
            });
            o.render();
            setInterval(function () {
                o.update(v);
            });
        });
    },
            {
                offset: "80%",
                triggerOnce: true
            }
    );
});