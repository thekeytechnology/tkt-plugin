<?php

function tkGetBeThemeTemplate($atts){
    $args = shortcode_atts(array(
        "id" => "",
        "stripdown" => "",
        "enable_nesting" => false
    ), $atts, "tk-betemplate");
    

    //prevent nesting this shortcode unless explicitly enabled
    //WARNING: do not enable nesting if the shortcode is used in the_content
    //mfn_builder_print always calls the_content
    //this can only be prevented with the mfn-post-hide-content flag, which it reads directly from post meta
    if(!$args["enable_nesting"]){
        remove_shortcode("tk-betemplate");
    }

    //capture muffin builder output
    ob_start();
    mfn_builder_print($args["id"]);
    $output = ob_get_clean();

    //re-enable the shortcode if it was disabled earlier
    if(!$args["enable_nesting"]){
        add_shortcode("tk-betemplate", "tkGetBeThemeTemplate");
    }


    //remove the_content (less of a hassle than messing with post meta)
    $output = trim($output);
    $output = preg_replace( '/(.*?)<div class="section the_content.*?"?\s*>?\s*<\/div>?\s*$/sD' , '$1' , $output );

    $stripdownLevel = 0;
    if(($args["stripdown"]==="wrap")){
        $stripdownLevel = 1;
    }
    if(($args["stripdown"]==="element")){
        $stripdownLevel = 2;
    }
    //this setting applies visual editor auto-paragraphs
    if(($args["stripdown"]==="prettytext")){
        $stripdownLevel = 3;
    }
    //this setting reduces to just the text
    if(($args["stripdown"]==="plaintext")){
        $stripdownLevel = 4;
    }

    //caution: stripdown assumes that only one section and one wrap are used
    //text stripdown assumes that only one column or visual editor element are used (in addition to the above)

    //remove mcb-section divs
    if( $stripdownLevel > 0 ){
        $output = preg_replace( '/<div class=".*?mcb-section[^\-].*?"?\s*>(.*?)<\/div>?\s*$/sD' , '$1' , $output );
        $output = preg_replace( '/<div class=".*?mcb-section-inner.*?"?\s*>(.*?)<\/div>?\s*$/sD' , '$1' , $output );

        //remove mcb-wrap divs
        if( $stripdownLevel > 1 ){
            $output = trim($output);
            $output = preg_replace( '/<div class=".*?mcb-wrap[^\-].*?"?\s*>(.*?)<\/div>?\s*$/sD' , '$1' , $output );
            $output = preg_replace( '/<div class=".*?mcb-wrap-inner.*?"?\s*>(.*?)<\/div>?\s*$/sD' , '$1' , $output );

            //remove text column divs
            if( $stripdownLevel > 2 ){
                $output = trim($output);
                //visual editor element
                if($stripdownLevel == 3){
                    $output = preg_replace( '/<div class=".*?column_visual.*?"?\s*>(.*?)<\/div>?\s*$/sD' , '$1' , $output );
                    $output = wpautop($output);
                }
                //column element
                if($stripdownLevel == 4){
                    $output = preg_replace( '/<div class=".*?mcb-column[^\-].*?"?\s*>(.*?)<\/div>?\s*$/sD' , '$1' , $output );
                    $output = preg_replace( '/<div class=".*?column_attr.*?"?\s*>(.*?)<\/div>?\s*$/sD' , '$1' , $output );
                }
            }
        }
    }

    return $output;
}

add_shortcode("tk-betemplate", "tkGetBeThemeTemplate");