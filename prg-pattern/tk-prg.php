<?php
function tkPrgRedirect() {
    if(!empty($_POST['tk-target'])) {
        $url = $_POST['tk-target'];
    } else {
        $url = $_SERVER['HTTP_HOST'];
    }
    header("Location: " . $url, true, 302);
    exit();
}
tkPrgRedirect();