<?php
if(!isset($_SESSION)){session_start();}
require_once('user.php');

if ($_SESSION["user_id"] > 0) {
    echo '<p>'.$_SESSION["user_email"].'</p>';
    $id = $_SESSION["user_id"];
    $user_obj = new user($id);
    foreach ($user_obj->get_images() as $img) {
        echo '<img src="'.$img['file_location'].'"><br /><form action="remove.php" method="post">';
        echo '<input type="hidden" name="id" value='.$img['id'].'><br /><input type="submit" value="Delete Image"></form>';
    }
}
else {
    header("Location: ./index.php");
}
?>

File to upload:<br />

<form action="upload.php" method="post" enctype="multipart/form-data">
<input type="file" name="upfile" size="45">
<input type="hidden" name="user_id" value="<?php echo $id ?>">
<br />
<input type="submit" value="Upload File">
</form>

<form action="change_email.php" method="post">
<input type="hidden" name="user_id" value="<?php echo $id ?>">
<input type="text" name="email">
<br />
<input type="submit" value="Change Email">
</form>
