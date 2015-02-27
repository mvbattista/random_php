<?php

require_once('./main_controller.php');

$id = $_POST['id'];

if (!($id === NULL)) {
    $controller_obj = new MainController();
    $controller_obj->delete_bookmark($id);
}
else {
    header("Location: ../views/dashboard.php");
}

?>