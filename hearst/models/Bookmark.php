<?php
require_once('../db_config.php');

class Bookmark {
    private $id;
    private $url;
    private $name;

    function __construct($in_url, $in_name) {
        // Determine if data passed is existing DB object with ID
        if (preg_match('/^\d+$/', $in_url)) {
            $this->id = intval($in_url);
        }
        // Create new object
        else {
            $this->url = $in_url;
            $this->name = $in_name;
        }
    }

    function insert() {
        $db = db_conn();
        $name = $this->name;
        $url = $this->url;
        // Make sure users don't enter a duplicate URL. If so, update name.
        // May still increment ID, but BIGINT exceeds the scope of the project.
        // Would check if URL exists and then insert/update, if that were an issue.
        $insert_sql = 'INSERT INTO bookmarks (url, name) VALUES ("'.$url.'", "'.$name.'")'.
            'ON DUPLICATE KEY UPDATE name = "'.$name.'"';
        $db->query($insert_sql);
        header("Location: ../views/dashboard.php");
    }

    // Update and Delete methods

    function update($in_new_url, $in_new_name) {
        $db = db_conn();
        $id = $this->id;
        $update_sql = $sql = 'UPDATE bookmarks SET url = "'.$in_new_url.'", name = "'.$in_new_name.'" WHERE id = '.$id ;
        $db->query($update_sql);
        header("Location: ../views/dashboard.php");
    }

    function remove() {
        $db = db_conn();
        $delete_sql = 'DELETE FROM bookmarks WHERE id = '.$this->id;
        $db->query($delete_sql);
        header("Location: ../views/dashboard.php");
    }

}

?>