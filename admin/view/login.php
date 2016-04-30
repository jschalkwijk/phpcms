<?php
	$output_form = False;
	session_start();
	// prevent session hijacking
	ini_set('session.cookie_httponly', true);

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
			$dbc = new DBC;
			if(isset($_POST['username']) && isset($_POST['password'])) {	
				$username = $_POST['username'];
				$password = $_POST['password'];
				$query = "SELECT * FROM login WHERE username='$username'";
				$data = mysqli_query($dbc->connect(),$query);
				
				if(mysqli_num_rows($data) == 1) {
					$row = mysqli_fetch_array($data);
					$set_password = $row['password'];
					// decrypt password
					$input_password = crypt($password,$set_password);
					if($set_password === $input_password){
						$user_id = (int)$row['id'];
						$_SESSION['user_id'] = (int)$row['id'];
						$_SESSION['username'] = $row['username'];
						$_SESSION['rights'] = $row['rights'];
						$new_token = md5($row['username'].$row['rights'].$row['token']);
						$query2 = "UPDATE login SET token = '$new_token' WHERE id='$user_id'";
						mysqli_query($dbc->connect(),$query2) or die('Error connecting to database.');
						if(isset($_POST['remember'])) {
							setcookie('t',$new_token, time() + 60*60*10,'/', null, null, true); // httponly=  ,/ ,null, null, true
							setcookie('u',md5($username.$new_token),time() + 60*60*10,'/', null, null, true);
						}
						header('Location: '.'admin');
					}
				} else {
					echo '<div class="container">Enter a valid email and/or password</div>';
					$output_form = True; 
				}
			}
			$dbc->disconnect(); 
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
			<form id="login" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="mulipart/form-data">
				<input type="text" name="username" placeholder="Username"/><br />
				<input type="password" name="password" placeholder="Password"/><br />
				<input type="checkbox" name="remember"/><span> Remember me today.</span><br />
				<button type="submit" name="submit">Login</button>
			</form>
		</div>
<?php
	} 
?>