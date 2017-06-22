<?php

function tkSendMail($email, $subject, $content, $replyTo)
{
    wp_mail($email, $subject, $content, array("Content-type" => "text/html", "Reply-To" => $replyTo));
}

function wpse27856_set_content_type()
{
    return "text/html";
}


if(TK_MAIL_HTML) {
    add_filter('wp_mail_content_type', 'wpse27856_set_content_type');
}

add_filter("retrieve_password_message", function ($message, $key, $user_login, $user_data) {
    $message = str_replace("<", "", $message);
    $message = str_replace(">", "", $message);
    return $message;
}, 10, 4);


