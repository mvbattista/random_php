<?php
require_once('../models/Bookmark.php');

class MainController {
    function load_bookmarks() {
        $db = db_conn();
        $sql = 'SELECT id, url, name FROM bookmarks';
        $res = $db->query($sql);
        if (!$res) {
            printf("Error connecting to database: %s\n", mysqli_error($db));
            exit();
        }
        $result = array();
        while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
            array_push($result, $row);
        }
        return $result;
    }

    function add_bookmark($in_url, $in_name) {
        // Check if URL is valid.
        if (preg_match('#\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))#iS', $in_url)) {
            // Add http to bookmark for URL.
            if (!preg_match("#^(?:f|ht)tps?://#i", $in_url)) {
                $in_url = "http://" . $in_url;
            }
            $bm_obj = new Bookmark($in_url, $in_name);
            $bm_obj->insert();
        }
        // Invalid input, back to view
        else {
            header("Location: ../views/dashboard.php");
        }
    }

    function delete_bookmark($in_id) {
        // ID is passed directly from generated form
        $bm_obj = new Bookmark($in_id);
        $bm_obj->remove();
    }

    function edit_bookmark($in_id, $in_new_url, $in_new_name) {
        // Check if URL is valid
        if (preg_match('#\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))#iS', $in_new_url)) {
            // Add http to bookmark for URL
            if (!preg_match("#^(?:f|ht)tps?://#i", $in_new_url)) {
                $in_new_url = "http://" . $in_new_url;
            }
            $bm_obj = new Bookmark($in_id);
            $bm_obj->update($in_new_url, $in_new_name);
        }
        // Invalid input, back to view
        else {
            header("Location: ../views/dashboard.php");
        }
    }

}

?>