<?php
if(!isset($_SESSION)){session_start();}
require_once('user.php');

if ($_SESSION["user_id"] > 0 && $_SESSION["user_is_admin"]) {
    $filter_id = 0;
    if(isset($_POST["filter_user_id"])){$filter_id = $_POST["filter_user_id"];}

    echo '<p>'.$_SESSION["user_email"].' - ADMIN</p>';
    $id = $_SESSION["user_id"];
    $user_obj = new user($id);
    echo 'FILTER BY USER: <form action="admin.php" method="post"><select name="filter_user_id">';
    echo '<option value="0">SHOW ALL</option>'."\n";
    foreach ($user_obj->get_all_users() as $u) {
        echo '<option value="'.$u['id'].'">'.$u['email']."</option>\n";
    }
    echo '</select><br /><input type="submit" value="Filter"></form>';

    foreach ($user_obj->get_all_images($filter_id) as $img) {
        echo '<img src="'.$img['file_location'].'"><br />';
        echo $img['email'].'<br />';
    }
}
else {
    header("Location: ./index.php");
}
?>
