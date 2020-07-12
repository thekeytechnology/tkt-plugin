<?php

function tkGoogleMapsShortcode($args = array()) {

    $map = new TkMap();

    if (isset($args['id'])) {
        $map->setId($args['id']);
    }

    if (isset($args['class'])) {
        $map->setClass($args['class']);
    }

    if (isset($args['style'])) {
        $map->setStyle($args['style']);
    }

    if (isset($args['zoom'])) {
        $map->setZoom($args['zoom']);
    }

    if (isset($args['image'])) {
        $map->setPlaceholderImage($args['image']);
    }

    if (isset($args['center'])) {
        $centerTuple = explode(",", $args['center']);

        if (count($centerTuple) == 2) {
            $map->setCenter($centerTuple);
        }
    }

    if (isset($args['$controls'])) {
        $controls = $args['$controls'];
        $map->setControls(false);
        if (strpos($controls, 'zoomControl') !== false) $map->setControl('zoomControl', true);
        if (strpos($controls, 'mapTypeControl') !== false) $map->setControl('mapTypeControl', true);
        if (strpos($controls, 'streetViewControl') !== false) $map->setControl('streetViewControl', true);
        if (strpos($controls, 'fullscreenControl') !== false) $map->setControl('fullscreenControl', true);
        if (strpos($controls, 'draggable') !== false) $map->setControl('draggableControl', true);
    }

    if (isset($args['markers'])) {
        $markerString = $args['markers'];
        $markerStrings = explode("|", $markerString);
        $markers = array_map(function($markerAsString) {
            return explode(",", $markerAsString);
        }, $markerStrings);

        foreach ($markers as $marker) {
            $map->addMarker();
        }
    }

    return $map->render();
}
add_shortcode("tk-google-maps", "tkGoogleMapsShortcode");
