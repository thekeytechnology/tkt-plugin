<?php

class TkTemplate
{
    private $twig;

    function __construct()
    {
        $templateDir = get_stylesheet_directory() . "/assets/twig/";

        if (file_exists($templateDir)) {
            $dirs = $this->getTemplateFolders($templateDir);

            $loader = new Twig_Loader_Filesystem($dirs);

            $parameters = array("autoescape" => false);

            if (TK_TEMPLATE_CACHE) {
                $parameters["cache"] = tkGetWPRootPath() . "/wp-content/cache/twig";
            }

            try {
                $this->twig = new Twig_Environment($loader, $parameters);
            } catch(Exception $ex) {
                print_a($ex);
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


    public function renderTemplate($templateName, $args = [])
    {
        if (!isset($this->twig)) {
            return "<marquee class='tk-error'>TEMPLATE SYSTEM NOT INITIALIZED - DID YOU CREATE assets/twig FOLDER IN THEME?!!!</marquee>";
        }

        try {
            return $this->twig->render($templateName, $args);
        } catch (Exception $e) {
            error_log("Template Exception: " . $e->getMessage() . " - " . $e->getTraceAsString());
            return "<marquee class='tk-error'>TEMPLATE ERROR CHECK LOGS!!!</marquee>";
        }
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
$tkTwig = new TkTemplate();


function tkTemplate($atts)
{
    global $tkTwig;
    $atts["queriedObject"] = get_queried_object();
    return $tkTwig->renderTemplate($atts["name"], $atts);
}

add_shortcode("tkTemplate", "tkTemplate");