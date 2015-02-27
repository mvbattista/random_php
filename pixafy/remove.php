<?php

require_once('db_config.php');
require_once('image.php');

$id = $_POST["id"];

$image_obj = new image($id);
$image_obj->remove();

?>