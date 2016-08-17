<?php
	// set cookies to be used with httponly, so no other scripts can access them
	ini_set('session.cookie_httponly', 1);
	session_start();
	// prevent session hijacking
	if(isset($_SESSION['last_ip']) === false) {
		$_SESSION['last_ip'] = $_SERVER['REMOTE_ADDR'];
	}

	if($_SESSION['last_ip'] !== $_SERVER['REMOTE_ADDR']) {
		session_unset();
		session_destroy();
		header('Location: login');
		exit();
	}
	$authorize = false;
	if(!isset($_SESSION['user_id'])) {
		if(isset($_COOKIE['t']) && isset($_COOKIE['u'])) {
			$token = $_COOKIE['t'];
			$dbc = mysqli_connect('localhost','root','','nerdcms_db') or die('Error connecting to server.');
			$query = "SELECT * FROM users WHERE token='$token'";
			$data = mysqli_query($dbc,$query);
			if(mysqli_num_rows($data) == 1){
				$row = mysqli_fetch_array($data);
				$username = md5($row['username'].$row['token']);
				if($username == $_COOKIE['u']) {   			
					$_SESSION['user_id'] = (int)$row['user_id'];
					$_SESSION['username'] = $row['username'];
					$_SESSION['rights'] = $row['rights'];
					$authorize = true;
				}
			} else {
				$authorize = false;
			}	
			
		} else {
			$authorize = false;
		}
	} else {
		$authorize = true;
	}
	// if user is not authorized, redirect to the login page and exit the script.
	if (!$authorize) {
		header('Location: '.ADMIN.'login.php');
		exit();
	}
?>