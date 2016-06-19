	<div class="container-fluid">
	<footer class="row">
		<p class="full-width"><img src="/images/atom-white.png" alt="atom"/><span> CRAFT Admin MVC: Some may say it's simple, we call it minimal. </span><img src="/images/atom-white.png" alt="atom"/></p>
	</footer>
		</div>
</div><!-- End Wrapper--> 
	<script src="/rs-nav/rs-nav.js"></script>
	<?php
		if(isset($data['js']) && !empty($data['js'])) {
			foreach ($data['js'] as $js) {
				echo '<script src="' . $js . '"></script>';
			}
		}
	?>
	<!-- Placed at the end of the document so the pages load faster -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
</body>
</html>
