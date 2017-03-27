/* 
 * GOOGLE MAPS CUSTOM STYLING - REQUIRED FOR GOOGLE MAPS ON A PAGE
 */

/* <![CDATA[ */
jQuery(document).ready(function ($) {
    'use strict';
    // GOOGLE MAPS START
    window.marker = null;

    function initialize() {
        var map;

        var locations = [
            ['London ConsultingPress Headquarters', 51.50013, -0, 126305, 4],
            ['ConsultingPress Headquarters Paris', 48.8566667, 2.3509871, 5],
            ['ConsultingPress Headquarters, Oslo', 59.9138204, 10.7387413, 3],
            ['ConsultingPress Headquarters, Rome', 41.8954656, 12.4823243, 2],
            ['ConsultingPress Support Center, Madrid', 40.4166909, -3.7003454, 1],
            ['ConsultingPress Headquarters, Moscow', 55.755786, 37.617633, 6],
            ['ConsultingPress Headquarters, Prague', 50.0878114, 14.4204598, 7],
            ['ConsultingPress Headquarters, Quebec', 52.9399159, -73.5491361, 8],
            ['ConsultingPress Headquarters, Ontario', 51.590723, -86.396484, 9],
            ['ConsultingPress Headquarters, Montana', 46.860191, -109.599609, 10],
            ['ConsultingPress Support Center, Alberta', 56.46249, -114.960937, 11],
            ['ConsultingPress Headquarters, Yukon', 63.332413, -136.098633, 12],
            ['ConsultingPress Headquarters Minesota', 46.729553, -94.6858998, 13],
            ['ConsultingPress Headquarters Virginia Beach', 36.8529263, -75.977985, 14],
            ['ConsultingPress Headquarters Chicago', 41.850033, -87.6500523, 15],
            ['ConsultingPress Headquarters Athens', 37.926868, 23.730469, 16],
            ['ConsultingPress Headquarters New Delhi', 28.574874, 77.299805, 17]
        ];

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

        var mapOptions = {
            // SET THE CENTER
            center: new google.maps.LatLng(50.0878114, 14.4204598),
            // SET THE MAP STYLE & ZOOM LEVEL
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            zoom: 3,
            // SET THE BACKGROUND COLOUR
            backgroundColor: "#eeeeee",
            // REMOVE ALL THE CONTROLS EXCEPT ZOOM
            panControl: true,
            zoomControl: true,
            mapTypeControl: true,
            scaleControl: true,
            streetViewControl: true,
            overviewMapControl: true,
            scrollwheel: false,
            draggable: false,
            zoomControlOptions: {
                style: google.maps.ZoomControlStyle.SMALL
            }
        };

        if ( document.getElementById('map') ) {
            map = new google.maps.Map(document.getElementById('map'), mapOptions);
        }

        // SET THE MAP TYPE
        var mapType = new google.maps.StyledMapType(style, {name: "Grayscale"});
        map.mapTypes.set('grey', mapType);
        map.setMapTypeId('grey');
        var infowindow = new google.maps.InfoWindow();

        //CREATE A CUSTOM PIN ICON
        var marker_image = 'img/svg/icon-marker-dark.svg';
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

        var singleLocation = new google.maps.LatLng(52.934658, -1.131450);

        if ( document.getElementById('map2') ) {
            map = new google.maps.Map(document.getElementById('map2'), mapOptions);
        
            marker = new google.maps.Marker({
                position: singleLocation,
                map: map
            });// END GOOGLE MAP STYLING
        }
    }
    
    google.maps.event.addDomListener(window, 'load', initialize);
});

