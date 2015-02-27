<?php

function db_conn() {
    $db_conn = mysqli_connect('localhost', 'hearst', 'hearstpass', 'hearst_mark');
    if (!$db_conn) {
        die('Could not connect: ' . $db->connect_error);
    }
    return $db_conn;
}

?>