<?php

namespace CMS\Core\Auth;

use CMS\Models\DBC\DBC;
use CMS\Models\Support\Session;
use CMS\Models\Users\Users;

class Auth
{
    public static function authenticate()
    {
        // set cookies to be used with httponly, so no other scripts can access them
        ini_set('session.cookie_httponly', 1);
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // prevent session hijacking
        if(isset($_SESSION['last_ip']) === false) {
            $_SESSION['last_ip'] = $_SERVER['REMOTE_ADDR'];
        }

        if($_SESSION['last_ip'] !== $_SERVER['REMOTE_ADDR']) {
            session_unset();
            session_destroy();
            return false;
        }
        // expire session after 30 minutes of inactivity;
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
            // last request was more than 30 minutes ago
            session_unset();     // unset $_SESSION variable for the run-time
            session_destroy();   // destroy session data in storage
            return false;
        }

        $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

        if(!isset($_SESSION['user_id'])) {
            if(isset($_COOKIE['t']) && isset($_COOKIE['u'])) {
                $token = $_COOKIE['t'];
                $db = new DBC;
                $dbc = $db->connect();
                $query = "SELECT * FROM users WHERE token='$token'";
                $data = mysqli_query($dbc,$query);
                if(mysqli_num_rows($data) == 1){
                    $row = mysqli_fetch_array($data);
                    $username = md5($row['username'].$row['token']);
                    if($username == $_COOKIE['u']) {
                        $_SESSION['user_id'] = (int)$row['user_id'];
                        $_SESSION['username'] = $row['username'];
                        $_SESSION['rights'] = $row['rights'];
                        return true;
                    }
                }
                return false;

            }
            return false;
        }
        return true;

    }

    public static function user()
    {
        if(Auth::authenticate()){
            $user_id = Session::get('user_id');
            return Users::one($user_id);
        }

        return false;
    }
}