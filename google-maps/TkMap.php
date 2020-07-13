<?php
/*
 * How to use:
 * $map = new TkMap("YourMapId");
 * $map->addMarker("YourLat", "YourLng", "YourTitle");
 * $map->addMarker("52.391689", "13.066726", "Potsdam HBF");
 * $map->setZoom(15);
 * $map->setStyle("satellite");
 * $map->setClass("map-class");
 * $map->generateMap();
 *
 * OR
 * $map = new TkMap( "YourMapId", array( "class" => "map-class", "style" => "satellite", "zoom" => "3", "image" => "PathToImage" ) );
 * $map->addMarker("52.391689", "13.066726", "Potsdam HBF");
 * $map->generateMap();
 */


require_once("tk-enqueue-google-maps-scripts.php");

class TkMap {
    public $id;
    public $class = "tk-map-initialized";
    public $style = "terrain";
    public $zoom = 13;
    public $center;
    public $markers = array();
    public $placeholderImage;
    public $placeholderContent;
    public $controls = array(
        "zoomControl" => true,
        "mapTypeControl" => true,
        "streetViewControl" => true,
        "fullscreenControl" => true,
        "draggable" => true,
        "scrollwheel" => false
    );

    public function __construct($id = "", $params = array(), $markers = array()) {
        $this->id = $id;
        $this->class = isset($params["class"]) ? $params["class"] : $this->class;
        $this->style = isset($params["style"]) ? $params["style"] : $this->style;
        $this->zoom = isset($params["zoom"]) ? $params["zoom"] : $this->zoom;
        $this->controls = isset($params["controls"]) ? $params["controls"] : $this->controls;
        $this->placeholderImage = isset($params["image"]) ? $params["image"] : plugin_dir_url(__FILE__) . '/tk-map-default-image.png';
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

    public function setControls($value) {
        $this->controls = array_map(function($control) use ($value) {
            return $value;
        }, $this->controls);
    }

    public function setControl($key, $value) {
        $this->controls[$key] = $value;
    }

    public function addMarker($lat, $lng, $title = "") {
        $this->markers[] = ["lat" => $lat, "lng" => $lng, "title" => $title];
    }

    public function render() {

        $parameters = array();

        $id = $this->id ? $this->id : "tk-map-" . wp_generate_uuid4();
        $parameters["id"] = $id;

        if ($this->class) {
            $parameters["class"] = $this->class;
        }
        if ($this->center && $this->center) {
            $parameters["center"] = $this->center;

            if (empty($this->markers)) {
                $this->addMarker($this->center[0], $this->center[1]);
            }
        } elseif (count($this->markers) == 1) {
            $parameters["center"] = [$this->markers[0]['lat'], $this->markers[0]['lng']];
        }

        $parametersJson = json_encode($parameters);

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
        $mapArgsJson = json_encode($mapArgs);

        $markersJson = json_encode($this->markers);

        $placeholderContent = $this->placeholderContent ? $this->placeholderContent : '
            <p> class="text-center">Klicken Sie hier, um die Karte anzuzeigen.<br> Dabei werden Inhalte von Google
                    Maps nachgeladen <br><a href="https://policies.google.com/privacy" style="color: red;" target="_blank">Mehr
                        erfahren</a></p>
        ';



        ob_start();
        ?>
        <div
                id="<?php echo $id; ?>"
                class="<?php echo $this->class; ?> tk-map tk-map-not-initialized"
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
            tkMaps = typeof (tkMaps) !== "undefined" ? tkMaps : {};

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



