<?php

require_once('user.php');

$email = $_POST["email"];
$id = $_POST["user_id"];

$user_obj = new user($id);
$user_obj->change_email($email);

?>
