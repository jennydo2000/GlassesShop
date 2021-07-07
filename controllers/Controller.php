<?php
include_once __DIR__ . '/../models/DB.php';

class Controller {
    protected DB $db;

    function __construct() {
        $this->db = new DB();
        $this->db->connect();
    }

    protected function view(string $view, $params = null) {
        $view = str_replace('.', '/', $view);
        $_USER = $this->authorize() ? $this->get_user() : null;
        $_IS_ADMIN = isset($_USER);
        include("views/$view.php");
    }

    function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime(date('Y-m-d H:i:s', $datetime));
        $diff = $now->diff($ago);
    
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
    
        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }
    
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    function authorize() : bool {
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            $username = isset($user['username']) ? $user['username'] : '' ;
            $token = isset($user['token']) ? $user['token'] : '' ;
            $user_db = $this->db->query("SELECT * FROM users WHERE username = '$username' AND token = '$token'");
            if ($user_db->num_rows == 0) {
                unset($_SESSION['user']);
                return false;
            }
            return true;
        } else {
            return false;
        }
    }

    function get_user() : array {
        if ($this->authorize()) {
            $user = $_SESSION['user'];
            $username = isset($user['username']) ? $user['username'] : '' ;
            $token = isset($user['token']) ? $user['token'] : '' ;
            return $this->db->query("SELECT * FROM users WHERE username = '$username' AND token = '$token'")->fetch_assoc();
        } else
            return null;
    }
}