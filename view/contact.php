<?php

if(isset($_POST['submit'])) {
//	require_once('packages/swiftmailer5/lib/swift_required.php');

	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	// mail to admin
	$to = 'jornschalkwijk@gmail.com';
	$from = 'info@jornschalkwijk.com';
	$subject = "New contact request :-)";

	$name= mysqli_real_escape_string($dbc,trim($_POST['name']));
	$email= mysqli_real_escape_string($dbc,trim($_POST['email']));
	$phone= mysqli_real_escape_string($dbc,trim($_POST['phone']));
	$comment = mysqli_real_escape_string($dbc,trim($_POST['comment']));

	$message1 = '<html><body>';
	$message1 .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
	$message1 .= '<tr style="background: #eee;"><td><strong>Name:</strong> </td><td>' . strip_tags($name) . "</td></tr>";
	$message1 .= '<tr><td><strong>Email:</strong> </td><td>' . strip_tags($email) . '</td></tr>';
	$message1 .= '<tr><td><strong>Phone:</strong> </td><td>' . strip_tags($phone) . '</td></tr>';
	$message1 .= '<tr><td><strong>Message:</strong> </td><td>' . strip_tags($comment) . '</td></tr>';
	$message1 .= '</table>';
	$message1 .= '</body></html>';

	$transport = Swift_MailTransport::newInstance();
	$mailer = Swift_Mailer::newInstance($transport);
	$message1 = Swift_Message::newInstance()
		->setFrom(array($from,))
		->setTo(array($to))
		->setEncoder(Swift_Encoding::get7BitEncoding())
		->setSubject($subject)
		->setBody($message1, 'text/html')
		->addPart($message1, 'text/html')//	->attach(Swift_Attachment::fromPath($pdf,$type))
	;

	$mailer->send($message1);
}
?>

<div class="container-fluid">
	<div class="display top-margin">
		<div class="container">
			<div class="row">
				<div class="center">
					<h1 class="img-title">Contact</h1>
					<div class="center">
<!--						<h3 class="center">Pick your weapon of mass communication!</h3>-->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="main" class="container xlarge">
	<div id="main-content">
		<div class="row">
			<div class="medium center">
				<h1 class="center">Call</h1>
				<a href="tel:0031620562039"><img class="phone" src="images/phone.png" alt="phone"></a>
			</div>
		</div>
		<div class="row">
			<div class="medium center">
				<h1 class="center">E-mail</h1>
				<a><img class="phone" src="images/contact.png" alt="contact"></a>
			</div>
		</div>
		<div class="row">
			<div class="medium center">
				<form id="contact" action="contact" method="post">
					<input name="name" type="text" placeholder="Name"/>
					<input name="email" placeholder="E-mail"/>
					<input name="phone" type="text" placeholder="Phone"/>
					<textarea type="text" name="comment" cols="45" rows="6" id="comment" class="bodytext"  placeholder="Comment"></textarea>
					<div class="center"><button name="submit" type="submit" value="send">Send</button></div>
				</form>
			</div>
		</div>
	</div><!-- End main content container -->
</div>