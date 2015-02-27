<?php

require_once('./main_controller.php');

$id = $_POST['id'];
$url = trim($_POST['url']);
$name = trim($_POST['name']);

// Make sure ID is passed and URL & Name are not empty or null
if (!($id === NULL || ($url === NULL || $url === '') || ($name === NULL || $name === ''))) {
    $controller_obj = new MainController();
    $controller_obj->edit_bookmark($id, $url, $name);
}
else {
    header("Location: ../views/dashboard.php");
}

?>