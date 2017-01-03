<?php


class TkTemplate
{
    private $twig;

    function __construct()
    {
        $templateDir = get_stylesheet_directory() . "/assets/twig/";

        $dirs = $this->getTemplateFolders($templateDir);

        $loader = new Twig_Loader_Filesystem($dirs);

        $parameters = array("autoescape" => false);

        if (TK_TEMPLATE_CACHE) {
            $parameters["cache"] = get_home_path() . "/wp-content/cache/twig";
        }

        $this->twig = new Twig_Environment($loader, $parameters);
    }


    function addFilter($name, $filterFunction)
    {
        $this->twig->addFilter(new Twig_SimpleFilter($name, $filterFunction));
    }

    function addFunction($name, $function)
    {
        $this->twig->addFunction(new Twig_SimpleFunction($name, $function));
    }


    public function renderTemplate($templateName, $args = [])
    {
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
    return $tkTwig->renderTemplate($atts["name"], $atts);
}

add_shortcode("tkTemplate", "tkTemplate");