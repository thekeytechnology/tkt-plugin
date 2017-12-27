<?php

function tkSendMail($email, $subject, $content, $replyTo)
{
    wp_mail($email, $subject, $content, array("Content-type" => "text/html", "Reply-To" => $replyTo));
}

function tkInstallMailHTML()
{
    add_filter('wp_mail_content_type', function () {
        return "text/html";
    });
}

add_filter("retrieve_password_message", function ($message) {
    $message = str_replace("<", "", $message);
    $message = str_replace(">", "", $message);
    return $message;
}, 10, 1);


