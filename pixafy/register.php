<?php

require_once('db_config.php');
require_once('user.php');

$email = $_POST["email"];
$password = $_POST["password"];

$user_obj = new user($email);
$user_obj->add_user($password);

?>