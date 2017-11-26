</div> <!-- End Main-->
<div class="container-fluid">
	<footer class="row">
		<div class="col-xs-6 col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3">
		<p><img src="<?php echo IMG."atom-white.png";?>" alt="atom"/><span> CRAFT Admin MVC: Some may say it's simple, we call it minimal. </span><img src="<?php echo IMG."atom-white.png";?>" alt="atom"/></p>
		</div>
	</footer>
		</div>
</div><!-- End Wrapper--> 
	<script src="<?php echo ADMIN."rs-nav/rs-nav.js";?>"></script>
	<?php
		if(isset($data['js']) && !empty($data['js'])) {
			foreach ($data['js'] as $js) {
				echo '<script src="' . $js . '"></script>';
			}
		}
	?>

</body>
</html>
