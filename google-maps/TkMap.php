<?php
/*
 * How to use:
 * $map = new TkMap("YourMapId");
 * $map->addMarker("YourLat", "YourLng", "YourTitle");
 * $map->addMarker("52.391689", "13.066726", "Potsdam HBF");
 * $map->setZoom(15);
 * $map->setStyle("satellite");
 * $map->setClass("map-class");
 * $map->render();
 *
 * OR
 * $map = new TkMap( "YourMapId", array( "class" => "map-class", "style" => "satellite", "zoom" => "3", "image" => "PathToImage" ) );
 * $map->addMarker("52.391689", "13.066726", "Potsdam HBF");
 * $map->render();
 */


require_once("tk-enqueue-google-maps-files.php");

class TkMap {

    const CONTROL_ZOOM = "zoomControl";
    const CONTROL_MAP_TYPE = "mapTypeControl";
    const CONTROL_STREET_VIEW = "streetViewControl";
    const CONTROL_FULLSCREEN = "fullscreenControl";
    const CONTROL_DRAGGABLE = "draggable";
    const CONTROL_SCROLL_WHEEL = "scrollwheel";

    public $id;
    public $class = "";
    public $style = "terrain";
    public $zoom = 13;
    public $center;
    public $markers = array();
    public $placeholderImage;
    public $placeholderContent;
    public $height;
    public $controls = array(
        "zoomControl" => true,
        "mapTypeControl" => true,
        "streetViewControl" => true,
        "fullscreenControl" => true,
        "draggable" => true,
        "scrollwheel" => false
    );

    public function __construct($params = array(), $markers = array()) {
        $this->id = isset($params["id"]) ? $params["id"] : $this->id;
        $this->class = isset($params["class"]) ? $params["class"] : $this->class;
        $this->style = isset($params["style"]) ? $params["style"] : $this->style;
        $this->zoom = isset($params["zoom"]) ? $params["zoom"] : $this->zoom;
        $this->controls = isset($params["controls"]) ? $params["controls"] : $this->controls;
        $this->height = isset($params["height"]) ? $params["height"] : $this->height;
        $this->placeholderImage = isset($params["placeholderImage"]) ? $params["placeholderImage"] : plugin_dir_url(__FILE__) . 'tk-map-default-image.png';
        $this->markers = $markers;
    }

    public function setStyle($style) {
        $this->style = $style;
    }

    public function setPlaceholderImage($url) {
        $this->placeholderImage = $url;
    }

    public function setZoom($zoom) {
        $this->zoom = $zoom;
    }

    public function setClass($class) {
        $this->class = $class;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setCenter($center) {
        $this->center = $center;
    }

    public function setPlaceholderContent($placeholderContent) {
        $this->placeholderContent = $placeholderContent;
    }

    public function setControls($bool) {
        $this->controls = array_map(function ($control) use ($bool) {
            return $bool;
        }, $this->controls);
    }

    public function setControl($key, $value) {
        $this->controls[$key] = $value;
    }

    public function setHeight($height) {
        $this->height = $height;
    }

    public function addMarker($marker) {

        if ($marker instanceof TkMapMarker) {
            $this->markers[] = $marker;
        } else {
            $tkMarker = new TkMapMarker($marker);
            $this->markers[] = $tkMarker;
        }
    }

    public function render() {

        $parameters = array();

        $id = $this->id ? $this->id : "tk-map-" . wp_generate_uuid4();
        $parameters["id"] = $id;

        if ($this->class) {
            $parameters["class"] = $this->class;
        }
        if ($this->center) {
            $parameters["center"] = $this->center;

//            if (empty($this->markers)) {
//                $this->addMarker(array(
//                    "lat" => $this->center["lat"],
//                    "lng" => $this->center["lng "]
//                ));
//            }
        } elseif (count($this->markers) == 1) {
            /** @var TkMapMarker $firstMarker */
            $firstMarker = $this->markers[0];
            $parameters["center"] = array(
                "lat" => $firstMarker->lat,
                "lng" => $firstMarker->lng
            );
        }

        $parametersJson = json_encode(apply_filters("tk-google-maps-parameters", $parameters, $this));

        $mapArgs = array();
        if ($this->style) {
            $mapArgs["style"] = $this->style;
        }
        if ($this->zoom) {
            $mapArgs["zoom"] = $this->zoom;
        }
        foreach ($this->controls as $key => $value) {
            if ($key) {
                $mapArgs[$key] = $value;
            }
        }
        $mapArgsJson = json_encode(apply_filters("tk-google-maps-map-args", $mapArgs, $this));

        $markersJson = json_encode(apply_filters("tk-google-maps-markers", array_map(function ($marker) {
            /** @var TkMapMarker $marker */
            return $marker->getArray();
        }, $this->markers), $this));

        $placeholderContent = $this->placeholderContent ? $this->placeholderContent : '
            <p>Klicken Sie hier, um die Karte anzuzeigen.<br> Dabei werden Inhalte von Google
                    Maps nachgeladen.<br><a class="tk-google-maps-policy-link" href="https://policies.google.com/privacy?hl=de" style="color: red;" target="_blank">Mehr
                        erfahren</a></p>
        ';


        ob_start();
        ?>
        <div
                id="<?php echo $id; ?>"
                class="<?php echo $this->class; ?> tk-map tk-map-not-initialized"
            <?php echo $this->height ? 'style="height: ' . $this->height . 'px;"' : ""; ?>
        >
            <div
                    class="tk-map-placeholder-overlay"
                    style="
                            background: url('<?php echo $this->placeholderImage; ?>');
                            cursor: pointer;
                            height: 100%;
                            background-size: cover;
                            background-position: center;
                            "
            >
                <?php echo $placeholderContent; ?>
            </div>
        </div>
        <script>
            var tkMaps = typeof (tkMaps) !== "undefined" ? tkMaps : {};

            tkMaps["<?php echo $id; ?>"] = {
                parameters: <?php echo $parametersJson; ?>,
                markers: <?php echo $markersJson; ?>,
                mapArgs: <?php echo $mapArgsJson; ?>
            }
        </script>
        <?php

        $output = ob_get_clean();
        return $output;
    }
}



