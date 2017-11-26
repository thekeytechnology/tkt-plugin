<?php

class TKSlugRemover{

    private $tkPostTypeSlugList = array();

    static function create(){
        return new TKSlugRemover();
    }

    function __construct(){
        add_filter( 'post_type_link', array($this, 'na_remove_slug'), 10, 3 );
        add_action( 'pre_get_posts', array($this, 'na_parse_request') );
        add_action( 'template_redirect', array($this, 'wpse101952_redirect') );
    }

    function tkAddCPT($postType, $slug){
        $this->tkPostTypeSlugList[$postType] = $slug;
    }

    function na_remove_slug( $post_link, $post, $leavename ) {

        if ( !array_key_exists($post->post_type, $this->tkPostTypeSlugList) || 'publish' != $post->post_status ) {
            return $post_link;
        }

        $post_link = str_replace( '/'.$this->tkPostTypeSlugList[$post->post_type].'/', '/', $post_link );

        return $post_link;
    }

    function na_parse_request( $query ) {

        if ( ! $query->is_main_query() || 2 != count( $query->query ) || ! isset( $query->query['page'] ) ) {
            return;
        }

        if ( ! empty( $query->query['name'] ) ) {
            $query->set( 'post_type', array_merge(array("page", "post"), array_keys($this->tkPostTypeSlugList)) );
        }
    }

    function wpse101952_redirect() {
        $uri = $_SERVER["REQUEST_URI"];

        foreach($this->tkPostTypeSlugList as $slug){
            if (strpos($uri, "/".$slug."/") !== false) {
                wp_redirect(str_replace("/".$slug."/","/", $uri), 301);
                exit();
            }
        }
    }
};

global $tkSlugRemover;
$tkSlugRemover = TKSlugRemover::create();

function tkInstallRemoveSlug($postType, $slug){
    global $tkSlugRemover;
    $tkSlugRemover->tkAddCPT($postType, $slug);
}