<?php
if ( ! function_exists( 'is_ajax' ) ) {

    /**
     * is_ajax - Returns true when the page is loaded via ajax.
     * @return bool
     */
    function is_ajax() {
        return defined( 'DOING_AJAX' );
    }
}


class TkTemplate
{
    private $twig;

    function __construct($templateDir = NULL)
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
            } catch(Exception $ex) {
                print_a($ex);
            }
        }

        tkAddPmsFilters($this);
        tkAddUtilFilters($this);
        tkAddWcFilters($this);
        tkAddWpDisplayFilter($this);
        tkAddWpFieldFilters($this);
        tkAddWpTermFilters($this);
        tkAddWpFunctions($this);

        add_action('init', function ()
        {
            $this->addGlobal("wpuserloggedin", is_user_logged_in());
            $this->addGlobal("wplogouturl", wp_logout_url("/"));
            $this->addGlobal("wpisajax", is_ajax());
            $this->addGlobal("wpcurrentpath", add_query_arg(NULL, NULL));
            $this->addGlobal("wpshoulddisplayrecaptcha", TK_RECAPTCHA);
        });
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