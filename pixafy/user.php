<?php
if(!isset($_SESSION)){session_start();}
require_once('db_config.php');

class user {
    private $id;
    private $email;

    function __construct($in_email) {
        if (preg_match('/^\d+$/', $in_email)) {
            $this->id = intval($in_email);
        }
        else {
            $this->email = $in_email;
        }
    }

    function login($in_password) {
        $db = db_conn();
        $sql = 'SELECT id, is_admin, email FROM users WHERE email = \'' . $this->email . '\' AND password = MD5(\'' . $in_password . '\')';
        $res = $db->query($sql);

        if($res === false) {
            trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $conn->error, E_USER_ERROR);
            header("Location: ./index.php");
        } else {
            $row = $res->fetch_row();
            var_dump($row);
            $_SESSION["user_id"] = intval($row[0]);
            $_SESSION["user_is_admin"] = intval($row[1]);
            $_SESSION["user_email"] = $row[2];
            if ($row[1] > 0) {
                header("Location: ./admin.php");
            }
            else {
                header("Location: ./dashboard.php");
            }
        }

    }

    function add_user($in_password) {
        $db = db_conn();
        $sql = 'SELECT id FROM users WHERE email = \'' . $this->email . '\'';
        $res = $db->query($sql);
        if ($res->num_rows < 1) {
            $insert_sql = 'INSERT INTO users (email, password, is_admin) VALUES (\''.$this->email.'\', MD5(\''.$in_password.'\'), 0)';
            $db->query($insert_sql);
        }
        $this->login($in_password);

    }

    function get_images() {
        $db = db_conn();
        $sql = 'SELECT id, file_location FROM images WHERE user_id = '. $this->id;
        $res = $db->query($sql);
        $result = array();
        while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
            array_push($result, $row);
        }
        return $result;
    }

    function change_email($in_new_email) {
        $db = db_conn();
        if (strlen($in_new_email) > 0) {
            $sql = "UPDATE users SET email = \"$in_new_email \" WHERE id = ". $this->id;
            $res = $db->query($sql);
            if ($res === TRUE) {
                $_SESSION["user_email"] = $in_new_email;
            }
        }
        header("Location: ./dashboard.php");
    }

    function get_all_images($in_filter_id) {
        $db = db_conn();
        $check_sql = 'SELECT is_admin FROM users WHERE id = \'' . $this->id . '\'';
        $check_res = $db->query($check_sql);
        $row = $check_res->fetch_row();
        if ($row[0] > 0) {
            $sql = 'SELECT i.id, i.file_location, u.email FROM images i INNER JOIN users u ON (u.id = i.user_id)';
            if ($in_filter_id != 0) {
                $sql = $sql." WHERE i.user_id IN($in_filter_id)";
            }
            $res = $db->query($sql);
            $result = array();
            while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
                array_push($result, $row);
            }
            return $result;
        }
    }

    function get_all_users() {
        $db = db_conn();
        $check_sql = 'SELECT is_admin FROM users WHERE id = \'' . $this->id . '\'';
        $check_res = $db->query($check_sql);
        $row = $check_res->fetch_row();
        if ($row[0] > 0) {
            $sql = 'SELECT u.id, u.email FROM users u';
            $res = $db->query($sql);
            $result = array();
            while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
                array_push($result, $row);
            }
            return $result;
        }
    }

}

?>
