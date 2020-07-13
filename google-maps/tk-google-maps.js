tkMaps = typeof (tkMaps) !== "undefined" ? tkMaps : {};

jQuery(function ($) {

    const tkMapsNotInitializedClass = "tk-map-not-initialized";
    $("." + tkMapsNotInitializedClass).click(function() {
        let borlabsCookie = JSON.parse(tkGoogleMapsOptions.borlabsCookie);

        if (borlabsCookie) {
            window.BorlabsCookie.addConsent(borlabsCookie.group, borlabsCookie.cookie);
        }
        window.tkInitMaps();
    });

    window.tkInitMaps = function () {
        if (typeof google === 'object' && typeof google.maps === 'object') {
            window.tkCreateMaps();
        } else {
            $("body").prepend('<script src="https://maps.googleapis.com/maps/api/js?key=' + tkGoogleMapsOptions.mapKey + '&exp&callback=window.tkCreateMaps"></script>')
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
                mapArgs.center = {lat: parseFloat(parameters.center[0]), lng: parseFloat(parameters.center[1])};
            }

            let map = new google.maps.Map(element, mapArgs);
            let bounds = new google.maps.LatLngBounds();

            let addedMarkers = markers.map(marker => {

                let markerArgs = {
                    position: new google.maps.LatLng(parseFloat(marker.lat), parseFloat(marker.lng)),
                    map: map
                };

                if ("title" in marker) {
                    markerArgs.title = marker.title;
                }
                let addedMarker = new google.maps.Marker(markerArgs);

                bounds.extend(addedMarker.getPosition());
                return addedMarker;
            });

            if (!"center" in parameters) {
                map.fitBounds(bounds);
            }

            element.classList.remove(tkMapsNotInitializedClass);

            tkMaps[id].map = map;
            tkMaps[id].addedMarkers = addedMarkers;
            tkMaps[id].bounds = bounds;
        });
    };
});







