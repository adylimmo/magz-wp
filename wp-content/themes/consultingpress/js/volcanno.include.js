"use strict";
/*
 * PIXEL INDUSTRY INCLUDE FILE
 * 
 * Includes functions necessary for proper theme work and some helper functions.
 * 
 */

/**
 * Funftion for converting to SVG
 * @param void
 * @return void
 */

function convertToSVG() {
    jQuery(".svg-animate").each(function () {
        var $img = jQuery(this);
        var imgID = $img.attr("id");
        var imgClass = $img.attr("class");
        var imgURL = $img.attr("src");

        jQuery.get(imgURL, function (data) {
            // Get the SVG tag, ignore the rest
            var $svg = jQuery(data).find("svg");

            // Add replaced image's ID to the new SVG
            if (typeof imgID !== "undefined") {
                $svg = $svg.attr("id", imgID);
            }
            // Add replaced image's classes to the new SVG
            if (typeof imgClass !== "undefined") {
                $svg = $svg.attr("class", imgClass + " replaced-svg");
            }

            // Remove any invalid XML tags as per http://validator.w3.org
            $svg = $svg.removeAttr("xmlns:a");

            // Replace image with new SVG
            $img.replaceWith($svg);

        }, "xml");

    });
}

/**
 * Runs on load only
 */
jQuery(window).on("load", function () {
    /**
     * Convert to SVG
     */
    convertToSVG();

    /**
     * More button navigation
     */
    if (window.innerWidth > 993) {
        VolcannoInclude.volcannoExtendedMenu();
    } else {
        var isLoadedMobile = true;
        jQuery(window).on("resize", function () {
            if (window.innerWidth > 993 && isLoadedMobile) {
                VolcannoInclude.volcannoExtendedMenu();
                isLoadedMobile = false;
            }
        });
    }

    /**
     * Loader
     */
    jQuery("#loading-status").delay(500).fadeOut();
    jQuery("#loader").delay(1000).fadeOut("slow");
    setTimeout(function () {
        VolcannoInclude.triggerAnimation();
    }, 1000);
});

/**
 * Runs on both load and resize
 */
jQuery(window).on("load resize", function () {

    if (
            ((navigator.userAgent.match(/iPad/i)) && (navigator.userAgent.match(/iPad/i) !== null)
                    || (navigator.userAgent.match(/iPhone/i)) && (navigator.userAgent.match(/iPhone/i) !== null)
                    || (navigator.userAgent.match(/Android/i)) && (navigator.userAgent.match(/Android/i) !== null)
                    || (navigator.userAgent.match(/BlackBerry/i)) && (navigator.userAgent.match(/BlackBerry/i) !== null)
                    || (navigator.userAgent.match(/iemobile/i)) && (navigator.userAgent.match(/iemobile/i) !== null))
            && (jQuery(window).width < 993)
            )
    {
        jQuery(".header-wrapper.header-transparent").css({"position": "relative", "padding-top": "15px"});
        jQuery(".header-wrapper.header-style-02").css({"position": "relative"});
    }

    /**
     * Navigation
     */
    var windowWidth = jQuery(window).width();

    if (!VolcannoInclude.isTouchDevice() && (windowWidth >= 1200)) {
        jQuery(".navbar-nav > li.dropdown").addClass("hover");
    } else {
        jQuery(".navbar-nav > li.dropdown").removeClass("hover");
    }
    /**
     * Extended menu dropdown
     */
    VolcannoInclude.extendedMenuDropdown();

});

(jQuery)(function ($) {

    /**
     * Search animation
     */
    if (!VolcannoInclude.isTouchDevice() || (jQuery(window).width() > 992) && VolcannoInclude.isTouchDevice()) {
        jQuery("#header").on("click", "#search", function (e) {
            e.preventDefault();

            jQuery(this).find("#m_search").slideDown(200).focus();
        });

        jQuery("#m_search").focusout(function (e) {
            jQuery(e.target).slideUp();
        });
    }

    /**
     * Accordion
     */
    (function () {
        "use strict";
        jQuery(".accordion").on("click", ".title", function (event) {
            event.preventDefault();
            jQuery(this).siblings(".accordion .active").next().slideUp("normal");
            jQuery(this).siblings(".accordion .title").removeClass("active");

            if (jQuery(this).next().is(":hidden") === true) {
                jQuery(this).next().slideDown("normal");
                jQuery(this).addClass("active");
            }
        });
        jQuery(".accordion .content").hide();
        jQuery(".accordion .active").next().slideDown("normal");

        // Visual Composer accordion color
        jQuery(".vc_tta-accordion").each(function (index, el) {
            var classes = jQuery(this).attr('class');
            var color;
            var regex = /vc_tta-color-(.*?)\s/g;
            var m;
            while ((m = regex.exec(classes)) !== null) {
                // This is necessary to avoid infinite loops with zero-width matches
                if (m.index === regex.lastIndex) {
                    regex.lastIndex++;
                }
                color = m[1];
                jQuery('.vc_tta-controls-icon', this).css('background-color', color);
            }
        });

    })();

    /**
     * Content tabs
     */
    (function () {
        jQuery(".tabs").each(function () {
            var $tabLis = jQuery(this).find("li");
            var $tabContent = jQuery(this).next(".tab-content-wrap").find(".tab-content");

            $tabContent.hide();
            $tabLis.first().addClass("active").show();
            $tabContent.first().show();
        });

        jQuery(".tabs").on("click", "li", function (e) {
            var $this = jQuery(this);
            var parentUL = $this.parent();
            var tabContent = parentUL.next(".tab-content-wrap");

            parentUL.children().removeClass("active");
            $this.addClass("active");

            tabContent.find(".tab-content").hide();
            var showById = jQuery($this.find("a").attr("href"));
            tabContent.find(showById).fadeIn();

            e.preventDefault();
        });
    })();


    (function () {
        jQuery("ul.dropdown-menu [data-toggle=dropdown]").on("click", function (event) {
            // Avoid following the href location when clicking
            event.preventDefault();
            // Avoid having the menu to close when clicking
            event.stopPropagation();
            // If a menu is already open we close it
            jQuery(this).closest(".dropdown-submenu").toggleClass("open");
        });
    })();




    // Init scripts on page load
    VolcannoInclude.init();
});


var VolcannoInclude = {
    /**
     * Init function
     * @param void
     * @return void
     */
    init: function () {
        if (VolcannoConfig['volcannoScrollToTop']) {
            VolcannoInclude.scrollToTop();
        }
        if (VolcannoConfig['volcannoSmoothScroll']) {
            VolcannoInclude.smoothScroll();
        }
        VolcannoInclude.placeholderFix();

        if (jQuery(".chart-container").length) {
            VolcannoInclude.chartSize();
        }
        if (jQuery(".cta-negative-top").length) {
            VolcannoInclude.ctaNegativeTop();
        }

        jQuery(window).resize(function () {
            if (jQuery(".chart-container").length) {
                VolcannoInclude.chartSize();
            }
        });

        if (VolcannoInclude.isTouchDevice() || VolcannoInclude.isIOSDevice()) {
            VolcannoInclude.wpmlDropdownShow();
        }

        if ((!VolcannoInclude.isTouchDevice() || !VolcannoInclude.isIOSDevice()) || ((VolcannoInclude.isTouchDevice() || VolcannoInclude.isIOSDevice()) && jQuery(window).width() > 991)) {
            jQuery(window).on("load resize", function () {
                var window_y = jQuery(document).scrollTop();
                if (window_y > 0) {
                    VolcannoInclude.setStaticHeader(window_y);
                }


                if (window.innerWidth > 991) {
                    var header_height_02 = jQuery(".header-wrapper.header-style-02").height();
                    jQuery(".header-wrapper.header-style-02").next().css("margin-top", header_height_02);
                } else if (window.innerWidth < 992) {
                    jQuery(".header-wrapper.header-style-02").next().attr("style", "");
                }
                jQuery(".header-wrapper.header-style-02.header-negative-bottom").next().css("margin-top", header_height_02 - 26);
            });

            var header_height = jQuery(".header-wrapper.header-transparent").height();

            if (jQuery(".header-wrapper.header-transparent").next().hasClass("page-title")) {
                jQuery(".header-wrapper.header-transparent").next().css("padding-top", header_height + 60);
            }

            jQuery(window).scroll(function () {
                var position = jQuery(this).scrollTop();
                VolcannoInclude.setStaticHeader(position);
            });
        }

        /**
         * Set footer as static if there isn't enough content
         */
        if ((jQuery(window).height() > jQuery('body').height()) && (jQuery(".page-title").css("padding-top") === true)) {
            jQuery('#footer-wrapper').addClass('static');
        }

    },
    /**
     * Set static header
     * @param position
     * @return void
     */
    setStaticHeader: function (position) {
        var header_height = jQuery(".header-wrapper.header-transparent").height();
        var header_height_02 = jQuery(".header-wrapper.header-style-02").height();
        var top_bar_height = jQuery(".top-bar-wrapper").outerHeight();

        if (position > header_height_02) {
            jQuery(".header-wrapper.header-style-02").addClass("solid-color");
        } else {
            jQuery(".header-wrapper.header-style-02").removeClass("solid-color");
        }

        if (position > header_height) {
            jQuery(".header-wrapper.header-transparent").addClass("solid-color");
        } else {
            jQuery(".header-wrapper.header-transparent").removeClass("solid-color");
        }

        if (position > header_height) {
            jQuery(".header-wrapper").stop().animate({
                top: -top_bar_height
            }, 50);
        } else {
            jQuery(".header-wrapper").stop().animate({
                top: "0px"
            }, 50);
        }
    },
    /**
     * Trigger animation function
     */
    triggerAnimation: function () {
        if (!VolcannoInclude.isTouchDevice()) {

            // ANIMATED CONTENT
            if (jQuery(".animated")[0]) {
                jQuery(".animated").css("opacity", "0");
            }

            var currentRow = -1;
            var counter = 1;

            jQuery(".triggerAnimation").waypoint(function () {
                var $this = jQuery(this);
                var rowIndex = jQuery(".row").index(jQuery(this).closest(".row"));
                if (rowIndex !== currentRow) {
                    currentRow = rowIndex;
                    jQuery(".row").eq(rowIndex).find(".triggerAnimation").each(function (i, val) {
                        var element = jQuery(this);
                        setTimeout(function () {
                            var animation = element.attr("data-animate");
                            element.css("opacity", "1");
                            element.addClass("animated " + animation);
                        }, (i * 500));
                    });

                }

                //counter++;

            },
                    {
                        offset: "80%",
                        triggerOnce: true
                    }
            );

        }
    },
    /**
     * Function to check is user is on touch device
     * @param void
     * @return bool
     */
    isTouchDevice: function () {
        return Modernizr.touch;
    },
    /**
     * Function to check is user is on iOS Device
     * @param void
     * @return bool
     */
    isIOSDevice: function () {
        if (
                (navigator.userAgent.match(/iPad/i)) && (navigator.userAgent.match(/iPad/i) !== null)
                || (navigator.userAgent.match(/iPhone/i)) && (navigator.userAgent.match(/iPhone/i) !== null)
                || (navigator.userAgent.match(/Android/i)) && (navigator.userAgent.match(/Android/i) !== null)
                || (navigator.userAgent.match(/BlackBerry/i)) && (navigator.userAgent.match(/BlackBerry/i) !== null)
                || (navigator.userAgent.match(/iemobile/i)) && (navigator.userAgent.match(/iemobile/i) !== null)
                )
        {
            return true;
        } else {
            return false;
        }
    },
    /**
     * WPML Multilanguage Dropdown on Click on touch devices
     * @param void
     * @return void
     */
    wpmlDropdownShow: function () {
        jQuery(".wpml-languages").on("click", function () {
            if (!jQuery(this).hasClass("on-click-wpml-dropdown")) {
                jQuery(this).removeClass("close-wpml-dropdown");
                jQuery(this).addClass("on-click-wpml-dropdown");
            } else {
                jQuery(this).removeClass("on-click-wpml-dropdown").addClass("close-wpml-dropdown");
            }
        });
    },
    /**
     * Function for scrool to top
     * @param void
     * @return void
     */
    scrollToTop: function () {
        jQuery(window).scroll(function () {
            if (jQuery(this).scrollTop() > 100) {
                jQuery(".scroll-up").fadeIn();
            } else {
                jQuery(".scroll-up").fadeOut();
            }
        });

        jQuery(".scroll-up").on("click", function () {
            jQuery("html, body").animate({
                scrollTop: 0
            }, 600, jQuery.bez([1, 0, 0, 1]));
            return false;
        });
    },
    /**
     * Function for smooth scroll
     * @param void
     * @return void
     */
    smoothScroll: function () {
        var $window = jQuery(window);        //Window object
        var scrollTime = 0.5;           //Scroll time
        var scrollDistance = 250;       //Distance. Use smaller value for shorter scroll and greater value for longer scroll

        $window.on("mousewheel DOMMouseScroll", function (event) {

            event.preventDefault();

            var delta = event.originalEvent.wheelDelta / 120 || -event.originalEvent.detail / 3;
            var scrollTop = $window.scrollTop();
            var finalScroll = scrollTop - parseInt(delta * scrollDistance);

            TweenMax.to($window, scrollTime, {
                scrollTo: {y: finalScroll, autoKill: true},
                ease: Power1.easeOut, //For more easing functions see http://api.greensock.com/js/com/greensock/easing/package-detail.html
                autoKill: true,
                overwrite: 5
            });

        });
    },
    /**
     * Function for old browsers placeholder fix
     * @param void
     * @return void
     */
    placeholderFix: function () {
        jQuery("input, textarea").placeholder();
    },
    /**
     * Function for positioning Call To Action On Home Page 01 - Negative Top
     * @param void
     * @return void
     */
    ctaNegativeTop: function () {
        function ctaNegativeTop() {
            var ctaHeight = jQuery(".cta-negative-top").outerHeight();
            jQuery(".cta-negative-top").css("top", -ctaHeight);
        }

        jQuery(document).ready(ctaNegativeTop);
        jQuery(document).resize(ctaNegativeTop);
    },
    /**
     * Function for positioning Featured Pages Element - Negative Top
     * Present on Management Index
     * @param void
     * @return void
     */
    fpNegativeTop: function () {
        var fpNegativeTop = jQuery(".featured-pages-negative-top .media").height();
        jQuery('.featured-pages-negative-top [class*="col-"]').css("margin-top", -fpNegativeTop);
    },
    /**
     * Function for Equal Height on Rows 
     * Required for The Map on Home Page - It Gives Height to The Map
     * @param void
     * @return void
     */
    mapHeight: function () {
        function equalHeight() {
            jQuery('.row-equal-height *[class*="custom-col-padding"]').each(function () {
                var maxHeight = jQuery(this).outerHeight();
                jQuery('.row-equal-height *[class*="empty"] *[id*="map"]').height(maxHeight);
            });
        }

        jQuery(document).ready(equalHeight);
        jQuery(window).resize(equalHeight);
    },
    /**
     * Image Parallax Animation
     * Present on Tourism Index Page
     * @param void
     * @return void
     */

    imgParallaxAnimation: function () {
        jQuery(".img-animate-container").each(function () {
            var attr_width = jQuery(this).attr("data-width");
            var attr_top = jQuery(this).attr("data-top");
            var attr_left = jQuery(this).attr("data-left");

            jQuery(this).find(".img-animate").css({
                "width": attr_width + "px",
                "top": attr_top + "px",
                "left": attr_left + "px"
            });

            var height = 0;

            jQuery(this).children(".img-animate").each(function () {
                height = Math.max(height, jQuery(this).height());
            });

            jQuery(this).parent().css("min-height", height);
        });

    },
    /**
     * Function for Message Boxes Element - Close Button
     * @param void
     * @return void
     */
    messageShowClose: function () {
        jQuery(".message-boxes").on("click", function () {
            jQuery(this).find(".message-close").css("opacity", "1");
        });
    },
    messageClose: function () {
        (function () {
            // INFORMATION BOXES 
            jQuery(".message-boxes .message-close").on("click", function () {
                jQuery(this).parent().fadeOut(300);
            });
        })();
    },
    /**
     * Function for passing chart values - width and height
     * @param void
     * @return void
     */
    chartSize: function () {
        jQuery(".chart-container").each(function () {

            var chartWidth = jQuery(this).data("width");
            var chartHeight = jQuery(this).data("height");

            var chartParentWidth = jQuery(this).parent().width();

            if (chartWidth >= chartParentWidth) {
                jQuery(this).width(chartParentWidth);
            } else {
                jQuery(this).width(chartWidth);
            }
            jQuery(this).height(chartHeight);
        });
    },
    /**
     * Function for Nivo Slider initialisation
     * @param id
     * @return void
     */
    nivoSliderInit: function (id) {
        switch (id) {
            case "nivo-slider-01":
                jQuery(".nivo-slider").nivoSlider({
                    effect: "slideInLeft",
                    pauseTime: 5000,
                    nextText: '<img class="svg-animate" src="' + VolcannoConfig['volcannoTemplateUrl'] + '/img/svg/circle-icon.svg" alt="circle icon"/>',
                    prevText: '<img class="svg-animate" src="' + VolcannoConfig['volcannoTemplateUrl'] + '/img/svg/circle-icon.svg" alt="circle icon"/>',
                    directionNav: true,
                    controlNav: false
                });
                break;
            case "nivo-slider-02":
                jQuery(".nivo-slider").nivoSlider({
                    effect: "slideInLeft",
                    pauseTime: 5000,
                    nextText: '<img class="svg-animate" src="' + VolcannoConfig['volcannoTemplateUrl'] + '/img/svg/circle-icon.svg" alt="circle icon"/>',
                    prevText: '<img class="svg-animate" src="' + VolcannoConfig['volcannoTemplateUrl'] + '/img/svg/circle-icon.svg" alt="circle icon"/>',
                    directionNav: false
                });
                break;
            default:
                // statements_def
                break;
        }
    },
    /**
     * Add custom menu if links are wider than menu wrapper and move extra links to custom menu
     **/
    volcannoExtendedMenu: function () {

        var mainWrapper = jQuery(".navbar");
        var menuWrapper = jQuery("#main-nav");

        // If is mega menu
        if (menuWrapper.children().hasClass('mega-menu-wrap')) {
            var isMegaMenu = true;
            var menu = jQuery("#main-nav .mega-menu-wrap > ul.mega-menu");
            var menuItem = jQuery("#main-nav ul.mega-menu > li");
            // If is default menu
        } else {
            var isMegaMenu = false;
            var menu = jQuery("#main-nav > ul.navbar-nav");
            var menuItem = jQuery("#main-nav > ul.navbar-nav > li");
        }

        var menuItemWidth = 0;
        var menuItemsWidth = [0];
        var numberOfAllItems = menu.children().length;
        var numberOfItems = menu.children().length;
        var extendedMenuButtonWidth = 40;

        // Get width for each menu element
        menuItem.each(function (index, el) {
            menuItemWidth = menuItemWidth + jQuery(this).width();
            menuItemsWidth.push(menuItemWidth);
        });

        function check() {

            if (isMegaMenu) {
                var menuItemLast = jQuery("#main-nav ul.mega-menu > li:not(.cloned)").last();
            } else {
                var menuItemLast = jQuery("#main-nav > ul.navbar-nav > li:not(.cloned)").last();
            }

            // Management & It Security
            if (jQuery('.header-wrapper').hasClass('header-management') || jQuery('.header-wrapper').hasClass('header-it-security')) {
                var additionalLinks = jQuery(".nav-additional-links").width();
                // Finance & Tourism
            } else if (jQuery('.header-wrapper').hasClass('header-finance') || jQuery('.header-wrapper').hasClass('header-tourism')) {
                var additionalLinks = jQuery(".navbar-header").width() + jQuery("#search").width() + 15;
            }

            var maxWidth = mainWrapper.width() - additionalLinks - extendedMenuButtonWidth;

            if (jQuery("#extended-menu").length === 0 && menuItemsWidth[numberOfAllItems] > maxWidth) {
                menuWrapper.append('<ul class="extended-menu-wrapper"><li class="extended-menu-button"><i class="fa fa-ellipsis-h" aria-hidden="true"></i><ul id="extended-menu"></ul></li></ul>');
            }

            var clonnedMenuWrapper = jQuery('.extended-menu-wrapper');
            var clonnedMenu = jQuery('#extended-menu');
            var clonnedItem = jQuery('#extended-menu li');

            if (menuItemsWidth[numberOfItems] > maxWidth) {
                menuItemLast.clone().prependTo(clonnedMenu).removeAttr('class');
                menuItemLast.addClass('cloned');
                numberOfItems -= 1;
                check();
            } else if (menuItemsWidth[numberOfItems + 1] < maxWidth) {
                clonnedItem.first().remove();
                if (isMegaMenu) {
                    jQuery("#main-nav ul.mega-menu > li.cloned").first().removeClass('cloned');
                } else {
                    jQuery("#main-nav ul.navbar-nav > li.cloned").first().removeClass('cloned');
                }
                numberOfItems += 1;
            }

            clonnedItem.each(function (index, el) {
                if (jQuery(this).children('ul').length) {
                    jQuery(this).children('a.mega-menu-link').addClass('menu-dropdown');
                    jQuery(this).children('a.dropdown-toggle').addClass('menu-dropdown');
                }
            });

            // Remove clonned menu if empty
            if (clonnedMenu.children().length === 0) {
                clonnedMenuWrapper.remove();
            }

        }

        jQuery(window).on("resize", function () {
            if (window.innerWidth > 993) {
                check();
            }
        });

        check();
    },
    /**
     * Functionality of extended menu
     */
    extendedMenuDropdown: function () {
        jQuery(".extended-menu-wrapper").on({
            mouseenter: function () {
                jQuery(this).find("#extended-menu").addClass("extended-menu-opened");
            },
            mouseleave: function () {
                jQuery(this).find("#extended-menu").removeClass("extended-menu-opened");
            }
        }, ".extended-menu-button");

        jQuery("#extended-menu").on({
            mouseenter: function () {
                jQuery(this).find("> .mega-sub-menu, > .dropdown-menu").addClass("extended-menu-opened");
            },
            mouseleave: function () {
                jQuery(this).find("> .mega-sub-menu, > .dropdown-menu").removeClass("extended-menu-opened");
            }
        }, "li");
    }


};