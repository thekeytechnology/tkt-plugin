<?php

function tkSendMail($email, $subject, $content)
{
    wp_mail($email, $subject, $content, array("Content-type" => "text/html", "Reply-To", "molly@blinkist.com"));
}

function wpse27856_set_content_type(){
    return "text/html";
}

if(TK_MAIL_HTML){
    add_filter( 'wp_mail_content_type','wpse27856_set_content_type' );
}
