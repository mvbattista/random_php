<?php
if(!isset($_SESSION)){session_start();}
require_once('db_config.php');

class image {
    private $id;
    private $file_location;
    private $user_id;

    function __construct($in_pwd, $in_user_id) {
        if (preg_match('/^\d+$/', $in_pwd)) {
            $this->id = intval($in_pwd);
        }
        else {
            $this->file_location = $in_pwd;
            $this->user_id = $in_user_id;
        }
    }

    function upload() {
        $db = db_conn();
        $insert_sql = 'INSERT INTO images (file_location, user_id) VALUES (\''.$this->file_location.'\', '.$this->user_id.')';
        $db->query($insert_sql);
        header("Location: ./dashboard.php");
    }

    function remove() {
        $db = db_conn();
        $file_loc_sql = 'SELECT file_location FROM images WHERE id = '.$this->id;
        $loc_res = $db->query($file_loc_sql);
        $row = mysqli_fetch_array($loc_res, MYSQLI_NUM);
        $pwd = $row[0];
        unlink($pwd);
        $delete_sql = 'DELETE FROM images WHERE id = '.$this->id;
        $db->query($delete_sql);
        header("Location: ./dashboard.php");
    }

}

?>