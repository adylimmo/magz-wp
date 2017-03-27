"use strict";
(jQuery)(function ($) {
    /**
     * Function for owl carousel initialisation
     * @param id
     * @return void
     */
    var VolcannoOwlInit = {
        owlCarouselInit: function (id) {
            switch (id) {
                // Services carousel
                case "services-carousel":
                    jQuery(".services-carousel").owlCarousel({
                        items: 3,
                        dots: false,
                        nav: true,
                        navText: ['<img class="svg-animate" src="' + VolcannoConfig['volcannoTemplateUrl'] + '/img/svg/circle-icon.svg" alt="circle icon"/>', '<img class="svg-animate" src="' + VolcannoConfig['volcannoTemplateUrl'] + '/img/svg/circle-icon.svg" alt="circle icon"/>'],
                        loop: true,
                        autoplay: false,
                        autoplayTimeout: 3000,
                        autoplayHoverPause: true,
                        margin: 0,
                        responsiveClass: true,
                        mouseDrag: true,
                        responsive: {
                            0: {
                                items: 1,
                                autoHeight: true
                            },
                            600: {
                                items: 2
                            },
                            1000: {
                                items: 3
                            }
                        }
                    });
                    break;
                    // Services carousel 02
                case "services-carousel-02":
                    jQuery(".services-carousel-02").owlCarousel({
                        items: 3,
                        dots: false,
                        nav: true,
                        navText: ['<img class="svg-animate" src="' + VolcannoConfig['volcannoTemplateUrl'] + '/img/svg/circle-icon.svg" alt="circle icon"/>', '<img class="svg-animate" src="' + VolcannoConfig['volcannoTemplateUrl'] + '/img/svg/circle-icon.svg" alt="circle icon"/>'],
                        loop: true,
                        autoplay: false,
                        autoHeight: false,
                        autoplayTimeout: 3000,
                        autoplayHoverPause: true,
                        margin: 30,
                        responsiveClass: true,
                        mouseDrag: true,
                        responsive: {
                            0: {
                                items: 1
                            },
                            600: {
                                items: 2
                            },
                            1000: {
                                items: 3
                            }
                        }
                    });
                    break;
                    // Testimonial carousel
                case "testimonial-carousel":
                    jQuery(".testimonial-carousel").owlCarousel({
                        items: 1,
                        dots: false,
                        nav: true,
                        navText: ['<img class="svg-animate" src="' + VolcannoConfig['volcannoTemplateUrl'] + '/img/svg/circle-icon.svg" alt="circle icon"/>', '<img class="svg-animate" src="' + VolcannoConfig['volcannoTemplateUrl'] + '/img/svg/circle-icon.svg" alt="circle icon"/>'],
                        loop: true,
                        autoplay: false,
                        autoHeight: true,
                        autoplayTimeout: 3000,
                        autoplayHoverPause: true,
                        margin: 0,
                        responsiveClass: true,
                        mouseDrag: true,
                        responsive: {
                            0: {
                                items: 1
                            },
                            600: {
                                items: 1
                            },
                            1000: {
                                items: 1
                            }
                        }
                    });
                    break;
                    // Testimonial carousel 02
                case "testimonial-carousel-02":
                    jQuery(".testimonial-carousel-02").owlCarousel({
                        items: 2,
                        dots: false,
                        nav: true,
                        navText: ['<img class="svg-animate" src="' + VolcannoConfig['volcannoTemplateUrl'] + '/img/svg/circle-icon.svg" alt="circle icon"/>', '<img class="svg-animate" src="' + VolcannoConfig['volcannoTemplateUrl'] + '/img/svg/circle-icon.svg" alt="circle icon"/>'],
                        loop: true,
                        autoplay: true,
                        autoHeight: false,
                        autoplayTimeout: 3000,
                        autoplayHoverPause: true,
                        margin: 30,
                        responsiveClass: true,
                        mouseDrag: true,
                        responsive: {
                            0: {
                                items: 1
                            },
                            600: {
                                items: 2
                            },
                            1000: {
                                items: 2
                            }
                        }
                    });
                    break;
                // Testimonial carousel 04
                case "testimonial-carousel-04":
                    jQuery(".testimonial-carousel-04").owlCarousel({
                        items: 1,
                        dots: false,
                        nav: false,
                        navText: ['<img class="svg-animate" src="' + VolcannoConfig['volcannoTemplateUrl'] + '/img/svg/circle-icon.svg" alt="circle icon"/>', '<img class="svg-animate" src="' + VolcannoConfig['volcannoTemplateUrl'] + '/img/svg/circle-icon.svg" alt="circle icon"/>'],
                        loop: true,
                        autoplay: true,
                        autoHeight: false,
                        autoplayTimeout: 3000,
                        autoplayHoverPause: true,
                        margin: 30,
                        responsiveClass: true,
                        mouseDrag: true,
                        responsive: {
                            0: {
                                items: 1
                            },
                            600: {
                                items: 1
                            },
                            1000: {
                                items: 1
                            }
                        }
                    });
                    break;
                    // Testimonial carousel 03
                case "testimonial-carousel-03":
                    jQuery(".testimonial-carousel-03").owlCarousel({
                        items: 1,
                        dots: false,
                        nav: false,
                        navText: ['<img class="svg-animate" src="' + VolcannoConfig['volcannoTemplateUrl'] + '/img/svg/circle-icon.svg" alt="circle icon"/>', '<img class="svg-animate" src="' + VolcannoConfig['volcannoTemplateUrl'] + '/img/svg/circle-icon.svg" alt="circle icon"/>'],
                        loop: true,
                        autoplay: true,
                        autoHeight: false,
                        autoplayTimeout: 3000,
                        autoplayHoverPause: true,
                        margin: 30,
                        responsiveClass: true,
                        mouseDrag: true,
                        responsive: {
                            0: {
                                items: 1
                            },
                            600: {
                                items: 1
                            },
                            1000: {
                                items: 1
                            }
                        }
                    });
                    break;
                    // Clients carousel
                case "client-carousel":
                    jQuery(".client-carousel").owlCarousel({
                        items: 6,
                        dots: false,
                        nav: false,
                        navText: ['<img class="svg-animate" src="' + VolcannoConfig['volcannoTemplateUrl'] + '/img/svg/circle-icon.svg" alt="circle icon"/>', '<img class="svg-animate" src="' + VolcannoConfig['volcannoTemplateUrl'] + '/img/svg/circle-icon.svg" alt="circle icon"/>'],
                        loop: true,
                        autoplay: true,
                        autoHeight: false,
                        autoplayTimeout: 3000,
                        autoplayHoverPause: true,
                        margin: 30,
                        responsiveClass: true,
                        mouseDrag: true,
                        responsive: {
                            0: {
                                items: 2
                            },
                            600: {
                                items: 3
                            },
                            1000: {
                                items: 6
                            }
                        }
                    });
                    break;
                    // Featured Pages Carousel
                case "featured-pages-carousel":
                    jQuery(".featured-pages-carousel").owlCarousel({
                        items: 3,
                        dots: false,
                        nav: true,
                        navText: ['<img class="svg-animate" src="' + VolcannoConfig['volcannoTemplateUrl'] + '/img/svg/circle-icon.svg" alt="circle icon"/>', '<img class="svg-animate" src="' + VolcannoConfig['volcannoTemplateUrl'] + '/img/svg/circle-icon.svg" alt="circle icon"/>'],
                        loop: true,
                        autoplay: true,
                        autoHeight: false,
                        autoplayTimeout: 3000,
                        autoplayHoverPause: true,
                        margin: 30,
                        responsiveClass: true,
                        mouseDrag: true,
                        responsive: {
                            0: {
                                items: 1
                            },
                            600: {
                                items: 2
                            },
                            1000: {
                                items: 3
                            }
                        }
                    });
                    // Latest posts carousel
                case "latest-posts-carousel":
                    jQuery(".latest-posts-carousel").owlCarousel({
                        items: 3,
                        dots: false,
                        nav: true,
                        navText: ['<img class="svg-animate" src="' + VolcannoConfig['volcannoTemplateUrl'] + '/img/svg/circle-icon.svg" alt="circle icon"/>', '<img class="svg-animate" src="' + VolcannoConfig['volcannoTemplateUrl'] + '/img/svg/circle-icon.svg" alt="circle icon"/>'],
                        loop: true,
                        autoplay: false,
                        autoHeight: true,
                        autoplayTimeout: 3000,
                        autoplayHoverPause: true,
                        margin: 30,
                        responsiveClass: true,
                        mouseDrag: true,
                        responsive: {
                            0: {
                                items: 1
                            },
                            600: {
                                items: 2
                            },
                            1000: {
                                items: 3
                            }
                        }
                    });
                    break;
                default:
                    // statements_def
                    break;
            }
        }
    }
    // Get type param
    jQuery(".owl-carousel").each(function() {
        var type = jQuery(this).data('type');
        VolcannoOwlInit.owlCarouselInit(type);
    });
});

/* <![CDATA[ */
jQuery(document).ready(function ($) {
    'use strict';
    // FEATURED PAGES NEGATIVE TOP POSITIONING
    VolcannoInclude.fpNegativeTop();
});

jQuery(window).on("load", function () {
    'use strict';
    // IMAGE PARALLAX ANIMATION
    if (!VolcannoInclude.isTouchDevice() || !VolcannoInclude.isIOSDevice()) {
        // POSITIONING NOTE NEGATIVE TOP
        VolcannoInclude.fpNegativeTop();
    }
});
/* ]]> */