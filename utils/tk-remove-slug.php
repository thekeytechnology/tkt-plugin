<?php

class TKSlugRemover
{

    private $tkPostTypeSlugList = array();
    private $tkTaxonomySlugList = array();

    static function create()
    {
        return new TKSlugRemover();
    }

    function __construct()
    {
        add_filter('post_type_link', array($this, 'na_remove_slug'), 10, 3 );
        add_action('pre_get_posts', array($this, 'na_parse_request') );

        add_filter('term_link', array($this, 'na_remove_term_slug'), 10, 3 );
        add_filter('request', array($this, 'rudr_change_term_request'), 1, 1 );

        add_action('template_redirect', array($this, 'wpse101952_redirect') );
    }

    function tkAddCPT($postType, $slug)
    {
        $this->tkPostTypeSlugList[$postType] = $slug;
    }

    function tkAddTaxonomy($taxonomy, $slug)
    {
        $this->tkTaxonomySlugList[$taxonomy] = $slug;
    }

    function na_remove_slug($post_link, $post, $leavename)
    {

        if ( !array_key_exists($post->post_type, $this->tkPostTypeSlugList) || 'publish' != $post->post_status ) {
            return $post_link;
        }

        $post_link = str_replace( '/'.$this->tkPostTypeSlugList[$post->post_type].'/', '/', $post_link );

        return $post_link;
    }

    function na_remove_term_slug($url, $term, $taxonomy)
    {

        if (!array_key_exists($taxonomy, $this->tkTaxonomySlugList)) {
            return $url;
        }

        $url = str_replace( '/'.$this->tkTaxonomySlugList[$taxonomy].'/', '/', $url );

        return $url;
    }

    function na_parse_request($query)
    {

        if ( ! $query->is_main_query() || 2 != count( $query->query ) || ! isset( $query->query['page'] ) ) {
            return;
        }

        if ( ! empty( $query->query['name'] ) ) {
            $query->set( 'post_type', array_merge(array("page", "post"), array_keys($this->tkPostTypeSlugList)) );
        }
    }

    function rudr_change_term_request($query)
    {
        // Request for child terms differs, we should make an additional check
        if( isset($query['attachment']) && $query['attachment'] ){
            $include_children = true;
            $name = $query['attachment'];
        }else{
            $include_children = false;
            if(isset($query['name'])){
                $name = $query['name'];
            }else{
                $name = null;
            }
        }

        foreach(array_keys($this->tkTaxonomySlugList) as $tax_name){

            $term = get_term_by('slug', $name, $tax_name); // get the current term to make sure it exists

            if (isset($name) && $term && !is_wp_error($term)){

                if( $include_children ) {
                    unset($query['attachment']);
                    $parent = $term->parent;
                    while( $parent ) {
                        $parent_term = get_term( $parent, $tax_name);
                        $name = $parent_term->slug . '/' . $name;
                        $parent = $parent_term->parent;
                    }
                } else {
                    unset($query['name']);
                }

                switch( $tax_name ){
                    case 'category':
                        $query['category_name'] = $name;
                        break;
                    case 'post_tag':
                        $query['tag'] = $name;
                        break;
                    default:
                        $query[$tax_name] = $name;
                        break;
                }
            }

            return $query;
        }

        return $query;
    }

    function wpse101952_redirect()
    {
        $uri = $_SERVER["REQUEST_URI"];

        $slugs = array_merge($this->tkPostTypeSlugList, $this->tkTaxonomySlugList);

        foreach($slugs as $slug){
            if (strpos($uri, "/".$slug."/") !== false) {
                wp_redirect(str_replace("/".$slug."/","/", $uri), 301);
                exit();
            }
        }
    }

    function tkAddDefaultRewriteRules($after = "bottom", $priority = 10)
    {
        add_action("init", function() use ($after)
        {
            global $wp_rewrite;

            add_rewrite_rule('[^/]+/attachment/([^/]+)/?$', 'index.php?attachment=$matches[1]', $after);
            add_rewrite_rule('[^/]+/attachment/([^/]+)/trackback/?$', 'index.php?attachment=$matches[1]&tb=1', $after);
            add_rewrite_rule('[^/]+/attachment/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$', 'index.php?attachment=$matches[1]&feed=$matches[2]', $after);
            add_rewrite_rule('[^/]+/attachment/([^/]+)/(feed|rdf|rss|rss2|atom)/?$', 'index.php?attachment=$matches[1]&feed=$matches[2]', $after);
            add_rewrite_rule('[^/]+/attachment/([^/]+)/comment-page-([0-9]{1,})/?$', 'index.php?attachment=$matches[1]&cpage=$matches[2]', $after);
            add_rewrite_rule('[^/]+/attachment/([^/]+)/embed/?$', 'index.php?attachment=$matches[1]&embed=true', $after);
            add_rewrite_rule('([^/]+)/embed/?$', 'index.php?name=$matches[1]&embed=true', $after);
            add_rewrite_rule('([^/]+)/trackback/?$', 'index.php?name=$matches[1]&tb=1', $after);
            add_rewrite_rule('([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$', 'index.php?name=$matches[1]&feed=$matches[2]', $after);
            add_rewrite_rule('([^/]+)/(feed|rdf|rss|rss2|atom)/?$', 'index.php?name=$matches[1]&feed=$matches[2]', $after);
            add_rewrite_rule('([^/]+)/'.$wp_rewrite->pagination_base.'/?([0-9]{1,})/?$', 'index.php?name=$matches[1]&paged=$matches[2]', $after);
            add_rewrite_rule('([^/]+)/comment-page-([0-9]{1,})/?$', 'index.php?name=$matches[1]&cpage=$matches[2]', $after);
            add_rewrite_rule('([^/]+)(?:/([0-9]+))?/?$', 'index.php?name=$matches[1]&page=$matches[2]', $after);
            add_rewrite_rule('[^/]+/([^/]+)/?$', 'index.php?attachment=$matches[1]', $after);
            add_rewrite_rule('[^/]+/([^/]+)/trackback/?$', 'index.php?attachment=$matches[1]&tb=1', $after);
            add_rewrite_rule('[^/]+/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$', 'index.php?attachment=$matches[1]&feed=$matches[2]', $after);
            add_rewrite_rule('[^/]+/([^/]+)/(feed|rdf|rss|rss2|atom)/?$', 'index.php?attachment=$matches[1]&feed=$matches[2]', $after);
            add_rewrite_rule('[^/]+/([^/]+)/comment-page-([0-9]{1,})/?$', 'index.php?attachment=$matches[1]&cpage=$matches[2]', $after);
            add_rewrite_rule('[^/]+/([^/]+)/embed/?$', 'index.php?attachment=$matches[1]&embed=true', $after);
        }, $priority);
    }
}


class TKSlugRemoverRewriteOptions
{
    public $rewriteRulesPosition;
    public $rewriteRulesPriority;
    public $enableVerbosePageRules;

    public function __construct($rewriteRulesPosition = "bottom", $rewriteRulesPriority = 10, $enableVerbosePageRules = false)
    {
        $this->rewriteRulesPosition = $rewriteRulesPosition;
        $this->rewriteRulesPriority = $rewriteRulesPriority;
        $this->enableVerbosePageRules = $enableVerbosePageRules;
    }
}


global $tkSlugRemover;
$tkSlugRemover = TKSlugRemover::create();

function tkInstallRemoveSlug($postType, $slug, $isTaxonomy = false, TKSlugRemoverRewriteOptions $options = null)
{
    global $tkSlugRemover;

    if($isTaxonomy){
        $tkSlugRemover->tkAddTaxonomy($postType, $slug);
    }else{
        $tkSlugRemover->tkAddCPT($postType, $slug);
    }

    if($options){
        $tkSlugRemover->tkAddDefaultRewriteRules($options->rewriteRulesPosition, $options->rewriteRulesPriority);
        if($options->enableVerbosePageRules){
            add_action("init", function(){
                global $wp_rewrite;
                $wp_rewrite->use_verbose_page_rules;
            });
        }
    }
}