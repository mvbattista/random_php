<?php

require_once('user.php');

$email = $_POST["email"];
$password = $_POST["password"];

$user_obj = new user($email);
$user_obj->login($password);

?>