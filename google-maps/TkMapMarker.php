<?php


class TkMapMarker {

    const DEFAULT_ICON_SIZE = 25;
    const MARKER_PREFIX = "tkMarker_";
    const CLICK_TYPE_URL = "url";
    const CLICK_TYPE_INFOWINDOW = "infowindow";
    const CLICK_TYPE_NONE = "none";

    public $lat;
    public $lng;
    public $icon;
    public $iconHeight = TkMapMarker::DEFAULT_ICON_SIZE;
    public $iconWidth = TkMapMarker::DEFAULT_ICON_SIZE;

    public $markerId;
    public $title;

    public $clickType = TkMapMarker::CLICK_TYPE_NONE;
    public $clickContent;

    public $addToBounds = true;

    const POSSIBLE_PARAMETERS_FOR_PARAMETER_LIST = array(
        "lat",
        "lng",
        "icon",
        "iconWidth",
        "iconHeight",
        "id",
        "title",
        "clickType",
        "clickContent",
        "addToBounds"
    );

    /**
     * TkMapMarker constructor.
     * @param string $latlng "12.345, 67.890"
     * @param string $title
     * @param string $markerId
     */
    public function __construct($params = array()) {
        foreach (TkMapMarker::POSSIBLE_PARAMETERS_FOR_PARAMETER_LIST as $index => $parameter) {
            if (isset($params[$parameter])) {
                switch ($parameter) {
                    case "lat":
                        $this->lat = $params[$parameter];
                        break;
                    case "lng":
                        $this->lng = $params[$parameter];
                        break;
                    case "icon":
                        $this->icon = $params[$parameter];
                        break;
                    case "iconWidth":
                        $this->iconWidth = $params[$parameter];
                        break;
                    case "iconHeight":
                        $this->iconHeight = $params[$parameter];
                        break;
                    case "id":
                        $this->markerId = $params[$parameter];
                        break;
                    case "title":
                        $this->title = $params[$parameter];
                        break;
                    case "clickType":
                        $this->clickType = $params[$parameter];
                        break;
                    case "clickContent":
                        $this->clickContent = $params[$parameter];
                        break;
                    case "addToBounds":
                        $this->addToBounds = $params[$parameter];
                        break;
                }
            }
        }
    }


    public function addUrl($url) {
        $this->clickContent = $url;
        $this->clickType = TkMapMarker::CLICK_TYPE_URL;
    }

    public function addInfowindow($content) {
        $this->clickContent = $content;
        $this->clickType = TkMapMarker::CLICK_TYPE_INFOWINDOW;
    }

    public function setClickType($clickType) {
        $this->clickType = $clickType;
    }

    public function setCoords($lat, $lng) {
        $this->lat = $lat;
        $this->lng = $lng;
    }

    public function setId($id) {
        $this->markerId = $id;
    }

    public function setIcon(
        $icon,
        $iconWidth = TkMapMarker::DEFAULT_ICON_SIZE,
        $iconHeight = false
    ) {
        $this->icon = $icon;
        $this->iconWidth = $iconWidth ? $iconWidth : TkMapMarker::DEFAULT_ICON_SIZE;
        $this->iconHeight = $iconHeight ? $iconHeight : $iconWidth;
    }

    public function setAddToBounds($bool) {
        $this->addToBounds = $bool;
    }

    public function getArray() {
        $marker = array();

        if (isset($this->lat)) {
            $marker['lat'] = $this->lat;
        }
        if ($this->lng) {
            $marker['lng'] = $this->lng;
        }
        if ($this->icon) {
            $marker['icon'] = $this->icon;
        }
        if ($this->iconWidth) {
            $marker['iconWidth'] = $this->iconWidth;
        }
        if ($this->iconHeight) {
            $marker['iconHeight'] = $this->iconHeight;
        }
        if ($this->markerId) {
            $marker['id'] = $this->markerId;
        } else {
            $marker['id'] = TkMapMarker::MARKER_PREFIX . wp_generate_uuid4();
        }
        if ($this->title) {
            $marker['title'] = $this->title;
        }
        if ($this->clickType && $this->clickType !== TkMapMarker::CLICK_TYPE_NONE) {
            $marker['clickType'] = $this->clickType;
        }
        if ($this->clickContent) {
            $marker['clickContent'] = $this->clickContent;
        }

        $marker['addToBounds'] = $this->addToBounds;

        return $marker;
    }

    public function getJson() {
        return json_encode($this->getArray());
    }

}
