<?php

require_once('./main_controller.php');

$url = trim($_POST['url']);
$name = trim($_POST['name']);

// Make sure URL and Name are not null or empty
if (!( ($url === NULL || $url === '' ) || ($name === NULL || $name === '') )) {
    $controller_obj = new MainController();
    $controller_obj->add_bookmark($url, $name);
}
else {
    header("Location: ../views/dashboard.php");
}

?>