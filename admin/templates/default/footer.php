
	<footer>
		<p><img src="/images/atom-white.png" alt="atom"/><span> CRAFT Admin MVC: Some may say it's simple, we call it minimal. </span><img src="/images/atom-white.png" alt="atom"/></p>
	</footer>
</div><!-- End Wrapper--> 
<script src="/rs-nav/rs-nav.js"></script>
<?php
	if(isset($data['js']) && !empty($data['js'])) {
		foreach ($data['js'] as $js) {
			echo '<script src="' . $js . '"></script>';
		}
	}
?>
</body>
</html>
