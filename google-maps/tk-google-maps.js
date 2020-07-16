tkMaps = typeof (tkMaps) !== "undefined" ? tkMaps : {};

jQuery(function ($) {


    const tkMapsNotInitializedClass = "tk-map-not-initialized";
    const borlabsCookie = JSON.parse(tkGoogleMapsOptions.borlabsCookie);
    let tkMapsScriptLoaded = false;

    $("." + tkMapsNotInitializedClass).click(function () {
        if (borlabsCookie && typeof window.BorlabsCookie !== "undefined") {
            window.BorlabsCookie.addConsent(borlabsCookie.group, borlabsCookie.cookie);
        }
        window.tkInitMaps();
    });

    window.tkInitMaps = function () {
        if (typeof google === 'object' && typeof google.maps === 'object' ) {
            window.tkCreateMaps();
        } else if(tkMapsScriptLoaded !== true) {
            $("body").prepend('<script src="https://maps.googleapis.com/maps/api/js?key=' + tkGoogleMapsOptions.mapKey + '&exp&callback=window.tkCreateMaps"></script>')
            tkMapsScriptLoaded = true
        }
    };

    window.tkCreateMaps = function () {
        const maps = document.querySelectorAll("." + tkMapsNotInitializedClass);
        maps.forEach(element => {
            let id = element.getAttribute('id');
            let parameters = tkMaps[id].parameters;
            let markers = tkMaps[id].markers;
            let mapArgs = tkMaps[id].mapArgs;

            if ("center" in parameters) {
                mapArgs.center = {lat: parseFloat(parameters.center.lat), lng: parseFloat(parameters.center.lng)};
            }

            let map = new google.maps.Map(element, mapArgs);
            let bounds = new google.maps.LatLngBounds();

            let addedMarkers = markers.map(marker => {

                let markerArgs = {
                    position: new google.maps.LatLng(parseFloat(marker.lat), parseFloat(marker.lng)),
                    map: map
                };

                /* Values */
                if ("title" in marker) {
                    markerArgs.title = marker.title;
                }
                if ("id" in marker) {
                    markerArgs.id = marker.id;
                }

                /* Custom icon */
                let icon = {};
                if ("iconWidth" in marker) {
                    if ("iconHeight" in marker) {
                        icon.scaledSize = new google.maps.Size(marker['iconWidth'], marker['iconHeight']);
                    } else {
                        icon.scaledSize = new google.maps.Size(marker['iconWidth'], marker['iconWidth']);
                    }
                }
                if ("icon" in marker) {
                   icon.url = marker['icon'];
                   markerArgs.icon = icon;
                }

                /* Url Callback */
                if ("clickType" in marker) {
                    if ("clickContent" in marker) {
                        if (marker['clickType'] === 'url') {
                            markerArgs.url = marker['clickContent'];
                        }
                    }
                }

                let addedMarker = new google.maps.Marker(markerArgs);

                /* Infowindow Callback */
                if ("clickType" in marker) {
                    if ("clickContent" in marker) {
                        if (marker['clickType'] === 'infowindow') {
                            let infoWindow = new google.maps.InfoWindow({
                                'content': marker['clickContent']
                            });
                            addedMarker.addListener('click', function () {
                                infoWindow.open(map, addedMarker);
                            });

                        }
                    }
                }

                if (!("addToBounds" in marker) || marker["addToBounds"]) {
                    bounds.extend(addedMarker.getPosition());
                }

                return addedMarker;
            });

            if (!("center" in parameters)) {
                map.fitBounds(bounds);
            }

            element.classList.remove(tkMapsNotInitializedClass);

            tkMaps[id].map = map;
            tkMaps[id].addedMarkers = addedMarkers;
            tkMaps[id].bounds = bounds;
        });
    };

    $("a.tk-google-maps-policy-link").click(function (event) {
        event.stopPropagation();
    });

    if (tkGoogleMapsOptions.alwaysLoad) {
        window.tkInitMaps();
    }
});







