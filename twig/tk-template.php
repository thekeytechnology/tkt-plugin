<?php

require_once("filters/tk-pms-filters.php");
require_once("filters/tk-pods-filters.php");
require_once("filters/tk-tec-filters.php");
require_once("filters/tk-wc-filters.php");
require_once("filters/tk-wp-display.php");
require_once("filters/tk-wp-fields.php");
require_once("filters/tk-wp-terms.php");
require_once("filters/tk-elementor.php");
require_once("filters/tk-util-filters.php");
require_once("functions/tk-util-functions.php");
require_once("functions/tk-wp-functions.php");

class TkTemplate
{
    static function create($templateDir = NULL)
    {
        $tkTwig = new TkTemplate($templateDir);

        tkAddPmsFilters($tkTwig);
        tkAddPodsFilter($tkTwig);
        tkAddTecFilters($tkTwig);
        tkAddUtilFilters($tkTwig);
        tkAddWcFilters($tkTwig);
        tkAddWpDisplayFilter($tkTwig);
        tkAddWpFieldFilters($tkTwig);
        tkAddWpTermFilters($tkTwig);
        tkAddElementorFilters($tkTwig);

        tkAddUtilFunctions($tkTwig);
        tkAddWpFunctions($tkTwig);

        if (function_exists("add_action")) {
            add_action('init', function () use ($tkTwig) {
                $tkTwig->addGlobal("wpuserloggedin", is_user_logged_in());
                $tkTwig->addGlobal("wplogouturl", wp_logout_url("/"));
                $tkTwig->addGlobal("wpisajax", is_ajax());
                $tkTwig->addGlobal("wpcurrentpath", add_query_arg(NULL, NULL));
                $tkTwig->addGlobal("wpshoulddisplayrecaptcha", TK_RECAPTCHA);
            });
        }

        return $tkTwig;
    }

    private $twig;

    public function __construct($templateDir = NULL)
    {
        if (!$templateDir) {
            $templateDir = get_stylesheet_directory() . "/assets/twig/";
        }

        if (file_exists($templateDir)) {
            $dirs = $this->getTemplateFolders($templateDir);

            $loader = new Twig_Loader_Filesystem($dirs);

            $parameters = array("autoescape" => false);

            if (TK_TEMPLATE_CACHE) {
                $parameters["cache"] = tkGetWPRootPath() . "/wp-content/cache/twig";
            }

            try {
                $this->twig = new Twig_Environment($loader, $parameters);
                $this->twig->addExtension(new Twig_Extensions_Extension_Intl());
                $this->twig->addExtension(new Twig_Extensions_Extension_Array());

            } catch (Exception $ex) {
                print_r($ex);
            }
        }
    }


    function addFilter($name, $filterFunction)
    {
        if (isset($this->twig)) {
            $this->twig->addFilter(new Twig_SimpleFilter($name, $filterFunction));
        }
    }

    function addFunction($name, $function)
    {
        if (isset($this->twig)) {
            $this->twig->addFunction(new Twig_SimpleFunction($name, $function));
        }
    }

    function addGlobal($key, $value)
    {
        if (isset($this->twig)) {
            $this->twig->addGlobal($key, $value);
        }
    }


    public function renderTemplate($templateName, $args = [], $displayErrors = false)
    {
        if (!isset($this->twig)) {
            return "<marquee class='tk-error'>TEMPLATE SYSTEM NOT INITIALIZED - DID YOU CREATE assets/twig FOLDER IN THEME?!!!</marquee>";
        }

        try {
            return $this->twig->render($templateName, $args);
        } catch (Exception $e) {
            error_log("Template Exception: " . $e->getMessage() . " - " . $e->getTraceAsString());
            if ($displayErrors) {
                return "<pre>" . $e->getMessage() . "</pre>";
            } else {
                return "<marquee class='tk-error'>TEMPLATE ERROR CHECK LOGS!!!</marquee>";
            }
        }
    }

    public function displayTemplate($templateName, $args = [], $displayErrors = false) {
        echo $this->renderTemplate($templateName, $args, $displayErrors);
    }


    private function getTemplateFolders($base)
    {
        $iter = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($base, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST,
            RecursiveIteratorIterator::CATCH_GET_CHILD // Ignore "Permission denied"
        );

        $paths = array($base);
        foreach ($iter as $path => $dir) {
            if ($dir->isDir()) {
                $paths[] = $path;
            }
        }
        return $paths;
    }
}


global $tkTwig;
$tkTwig = TkTemplate::create();

function tkTemplate($atts)
{
    global $tkTwig;
    $atts["queriedObject"] = get_queried_object();
    return $tkTwig->renderTemplate($atts["name"], $atts);
}

add_shortcode("tkTemplate", "tkTemplate");