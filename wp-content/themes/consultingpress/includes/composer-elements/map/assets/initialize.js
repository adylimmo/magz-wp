(jQuery)(function ($) { 

    /* ================ GOOGLE MAPS ================ */
    $('.map-canvas').each(function () {
        // init
        var map, marker, markerPosition;
        var mapTypeId = google.maps.MapTypeId.ROADMAP;

        // get options from html data attribute
        var mapType = $(this).data('type');
        var longitude = $(this).data('long');
        var latitude = $(this).data('lat');
        var zoom = parseInt($(this).data('zoom'));
        var scrollWheel = $(this).data('scroll-wheel') == '1';
        var contrast = $(this).data('contrast');
        var data_locations = $(this).data('locations');
        var locations = [];

        if ( !data_locations == '' ) {

            loc = data_locations.split('%&');

            for (var i = loc.length - 1; i >= 0; i--) {
                single_loc = loc[i];
                single_loc = single_loc.replace('[', '');
                single_loc = single_loc.replace(']', '');
                single_loc = single_loc.split('|');
                locations[i] = single_loc;
            }

        }

        // map type
        if (mapType == 'roadmap') {
            mapTypeId = google.maps.MapTypeId.ROADMAP;
        } else if (mapType == 'terrain') {
            mapTypeId = google.maps.MapTypeId.TERRAIN;
        } else if (mapType == 'satellite') {
            mapTypeId = google.maps.MapTypeId.SATELLITE;
        }

        // latitude and longitude
        var mapCenter = new google.maps.LatLng(latitude, longitude);

        markerPosition = mapCenter;
        
        // map options
        var mapOptions = {
            // SET THE CENTER
            center: mapCenter,
            // SET THE MAP STYLE & ZOOM LEVEL
            mapTypeId: mapTypeId,
            zoom: zoom,
            // SET THE BACKGROUND COLOUR
            backgroundColor: "#eeeeee",
            panControl: true,
            zoomControl: true,
            mapTypeControl: false,
            scaleControl: true,
            streetViewControl: true,
            overviewMapControl: true,
            scrollwheel: scrollWheel,
            zoomControlOptions: {
                style: google.maps.ZoomControlStyle.SMALL
            }

        };

        map = new google.maps.Map(this, mapOptions);

        if ( locations == '' ) {
            marker = new google.maps.Marker({
                position: markerPosition,
                map: map
            });// END GOOGLE MAP STYLING
        }

        //CREATE A CUSTOM PIN ICON
        if ( !locations == '' ) {
            var infowindow = new google.maps.InfoWindow();
            var marker_image = VolcannoConfig['volcannoTemplateUrl'] + '/img/svg/icon-marker-dark.svg';
            var pinIcon = new google.maps.MarkerImage(marker_image, null, null, null, new google.maps.Size(40, 40));
            var marker, i;
            for (i = 0; i < locations.length; i++) {
                marker = new google.maps.Marker({
                    position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                    map: map,
                    icon: pinIcon
                });
                google.maps.event.addListener(marker, 'click', (function (marker, i) {
                    return function () {
                        infowindow.setContent(locations[i][0]);
                        infowindow.open(map, marker);
                    };
                })(marker, i));
            };
        }

        if (contrast == 'grayscale') {
            //MAP BLACK AND WHITE STYLE
            var style = [
                {"featureType": "road",
                    "elementType":
                            "labels.icon",
                    "stylers": [
                        {"saturation": 1},
                        {"gamma": 1},
                        {"visibility": "on"},
                        {"hue": "#e6ff00"}
                    ]
                },
                {"elementType": "geometry", "stylers": [
                        {"saturation": -100}
                    ]
                }
            ];

            // SET THE MAP TYPE
            var mapType = new google.maps.StyledMapType(style, {name: "Grayscale"});
            map.mapTypes.set('grey', mapType);
            map.setMapTypeId('grey');

        } else if (contrast == 'saturated') {
            var style = [
                {"featureType": "road",
                    "elementType":
                            "labels.icon",
                    "stylers": [
                        {"saturation": 1},
                        {"gamma": 1},
                        {"visibility": "on"},
                        {"hue": "#e6ff00"}
                    ]
                },
                {"elementType": "geometry", "stylers": [
                        {"saturation": -85}
                    ]
                }
            ];

            // SET THE MAP TYPE
            var mapType = new google.maps.StyledMapType(style, {name: "Grayscale"});
            map.mapTypes.set('grey', mapType);
            map.setMapTypeId('grey');

        } else if (contrast == 'volcanno') {
            var style = [
                {
                    "featureType": "administrative",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "color": "#444444"
                        }
                    ]
                },
                {
                    "featureType": "landscape",
                    "elementType": "all",
                    "stylers": [
                        {
                            "color": "#f2f2f2"
                        }
                    ]
                },
                {
                    "featureType": "poi",
                    "elementType": "all",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "road",
                    "elementType": "all",
                    "stylers": [
                        {
                            "saturation": -100
                        },
                        {
                            "lightness": 45
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "all",
                    "stylers": [
                        {
                            "visibility": "simplified"
                        }
                    ]
                },
                {
                    "featureType": "road.arterial",
                    "elementType": "labels.icon",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "transit",
                    "elementType": "all",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "water",
                    "elementType": "all",
                    "stylers": [
                        {
                            "color": "#b8d7e4"
                        },
                        {
                            "visibility": "on"
                        }
                    ]
                }
            ];
            
            // SET THE MAP TYPE
            var mapType = new google.maps.StyledMapType(style, {name: "Grayscale"});
            map.mapTypes.set('grey', mapType);
            map.setMapTypeId('grey');
        }

    });
});

