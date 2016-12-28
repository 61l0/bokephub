
<div class="container">

	<div id="footer-sidebar" class="secondary">
		<div id="footer-sidebar1">
		<?php
		if(is_active_sidebar('footer-sidebar-1')){
		dynamic_sidebar('footer-sidebar-1');
		}
		?>
		</div>
	</div>

  <?php wp_footer(); ?>
  Powered By <a href="http://wordpress.tubeace.com">Tube Ace + WordPress</a>
</div>

    <script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>

</body>
</html>