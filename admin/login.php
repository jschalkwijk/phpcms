<?php
	ob_start();
	require_once('config.php');
	use CMS\Models\DBC\DBC;
	use Defuse\Crypto\Crypto;
	use Defuse\Crypto\Key;
?>
<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<link type="text/css" rel="stylesheet" href="<?php echo ADMIN."templates/default/style.css";?>"/>
</head>
<body>

<?php
	// for some reason the autoloader does not work on this page for the crypto class..
	// require_once 'CMS/Models/Encryption/Crypto.php';
	$output_form = False;

    // prevent session hijacking
    ini_set('session.cookie_httponly', true);

	session_start();

	if(isset($_SESSION['last_ip']) === false) {
		$_SESSION['last_ip'] = $_SERVER['REMOTE_ADDR'];
	}

	if($_SESSION['last_ip'] !== $_SERVER['REMOTE_ADDR']) {
		session_unset();
		session_destroy();
	}
	// If the user wants to log out the info will be send with a Get request
	// Here it checks to see if the user needs to be logged out.
	if (isset($_GET['id']) && isset($_GET['username'])) {
		// Grab the score data from the GET
		$_SESSION = array();
		if(isset($_COOKIE[session_name()])) {
			setcookie(session_name(),'',time() - 3600);
		}
		session_unset();
		session_destroy(); 
		setcookie('t',' ',time() - 3600,'/', null, null, true);
		setcookie('u',' ',time() - 3600,'/', null, null, true);
		echo '<div class="container">'.'You are logged out'.'</div>';
		$output_form = true;
	} 
	// If there is no get/logout request we check for a Cookie with a user id and if not found
	// display the form.
	if(!isset($_SESSION['user_id'])) {
		// if the form is submitted we start a session and create the cookies.
		if(isset($_POST['submit'])) {
			$db = new DBC;
            $dbc = $db->connect();
			if(isset($_POST['username']) && isset($_POST['password'])) {
                $username = $_POST['username'];
                $password = $_POST['password'];
                try {
                    $query = $dbc->prepare("SELECT * FROM users WHERE username = ?");
                    $query->execute([$username]);
                    if ($query->rowCount() == 1) {
                        $row = $query->fetch();
                        $hash = $row['password'];
                        // decrypt password
                        $passwordCheck = password_verify($password, $hash);
                        if ($passwordCheck) {
                            if(isset($_COOKIE[session_name()])) {
                                setcookie(session_name(), '', time() + 3600);
                            }
                            $user_id = (int)$row['user_id'];
                            $_SESSION['user_id'] = (int)$row['user_id'];
                            $_SESSION['username'] = $row['username'];
							$_SESSION['key'] = $row['protected_key'];
							$_SESSION['password'] = $password;
                            $shared_key = file_get_contents('keys/Shared/shared.txt');
							$returnKey = Key::loadFromAsciiSafeString($shared_key);
//                            $_SESSION['rights'] = Crypto::decrypt($row['rights'], $returnKey);
                            $new_token = md5($row['username'] . $_SESSION['rights'] . $row['token']);
                            try {
                                $query = $dbc->prepare("UPDATE users SET token = '$new_token' WHERE user_id = ? ");
                                $query->execute([$user_id]);
                                if (isset($_POST['remember'])) {
                                    setcookie('t', $new_token, time() + 60 * 60 * 10, '/', null, null, true); // httponly=  ,/ ,null, null, true
                                    setcookie('u', md5($username . $new_token), time() + 60 * 60 * 10, '/', null, null, true);
                                }
                                header('Location: ' . ADMIN);
                            } catch(\PDOException $e){
                                echo $e->getMessage();
                            }
                        }
                    } else {
                        echo '<div class="container">Enter a valid email and/or password</div>';
                        $output_form = True;
                    }
                } catch (\PDOException $e) {
                    echo $e->getMessage();
                }
            }
			$db->close();
		} else {
			// if there is no user logged in, and no post is submitted, show this, and the form.
			$output_form = True; 
		}
	} else if (isset($_SESSION['username'])) {
		// if there is someone already logged in and visits this page directly, we will tell him he is logged in.
		// and ask if he wants to log out.
		echo '<div class="container">';
		echo 'You are already logged in as '.$_SESSION['username'].'<br />';
		echo 'Not '.$_SESSION['username'].'? ';
		echo '<a href="login.php?id='.$_SESSION['user_id'].'&amp;username='.$_SESSION['username'].'">Logout</a>';
		echo '</div>';
	}
	
	// if there is no user logged in and if the form isnt submitted yet, or if output_form is true, show form.
	if ($output_form) { ?>
		<div class="container">
			<div class="row">
				<div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3">
					<div class="center">
						<form id="login" action="<?= $_SERVER['PHP_SELF'];?>" method="post" enctype="mulipart/form-data">
							<input type="text" name="username" placeholder="Username"/><br />
							<input type="password" name="password" placeholder="Password"/><br />
							<input type="checkbox" name="remember"/><span> Remember me today.</span><br />
							<button type="submit" name="submit">Login</button>
						</form>
					</div>
				</div>
			</div>
		</div>
<?php
	} 
?>

</body>
</html>
<?php ob_flush();?>