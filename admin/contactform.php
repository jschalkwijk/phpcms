<!DOCTYPE html>
<html>
<head>
	<title>Contact Form</title>
</head>
<body>

<?php
	require_once('globals.php');
	$output_form = False;
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die("Error connecting to server.");
	$name = mysqli_real_escape_string($dbc,trim($_POST['name']));
	$company = mysqli_real_escape_string($dbc,trim($_POST['company']));
	$email = mysqli_real_escape_string($dbc,trim($_POST['email']));
	$phone = mysqli_real_escape_string($dbc,trim($_POST['phone']));
	$comment = mysqli_real_escape_string($dbc,trim($_POST['comment']));
	$to = 'jornschalkwijk@gmail.com';
	$subject = 'Contact form Portfolio.';	
	if (isset($_POST['submit'])) {
		
		if (!empty($name) && !empty($email) && !empty($comment)) {
			$query = "INSERT INTO contact_forms(name,company,email,phone,comment) VALUE ('$name','$company','$email','$phone','$comment')";
			mysqli_query($dbc,$query);
			mail($to,$subject,$comment.' Phone: '.$phone.' '.$company.' '.$email,'From: '.$name);
			echo 'Hello '.$name.' your message has been send.';
		} else {
			if (empty($name)) {
				echo 'Please fill in your name. <br />';
				$output_form = True;
			}
			if (empty($email) && empty($phone)) {
				echo 'Please leave your mail or phone number so I can contact you. <br />';
				$output_form = True;
			}
			if (empty($message)) {
				echo 'Please leave a message regarding your reason for contact. <br />';
				$output_form = True;
			}
		} 
	} else {
		$output_form = True; 
	}
	mysqli_close($dbc);
	
	if ($output_form) { ?>
		<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
			<input type="text" name="name" placeholder="Name" value="<?php echo $name;?>"><br />
			<input type="text" name="company" placeholder="Company" value="<?php echo $company;?>"><br />
			<input type="text" name="email" placeholder="E-mail" value="<?php echo $email;?>"><br />
			<input type="text" name="phone" placeholder="Phone" value="<?php echo $phone;?>"><br />
			<textarea type="text" name="comment" placeholder="Your message or comment" cols="40" rows="5" value="<?php echo $comment;?>"></textarea><br />
			<input type="submit" name="submit" value="Send">
		</form>
<?php
	}
?>

</body>
</html>