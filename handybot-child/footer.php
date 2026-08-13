<?php
/**
 * Site footer.
 *
 * @package HandyBot_Demo
 */
?>
<footer class="hb-site-footer">
	<a class="hb-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
		<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/handybot-logo.png' ); ?>" alt="HandyBot">
	</a>
	<p>Lokální Elementor demo — není oficiálním webem HandyBot.</p>
	<p>Praha · info@handybot.cz</p>
</footer>
<?php wp_footer(); ?>
</body>
</html>
