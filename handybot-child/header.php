<?php
/**
 * Site header.
 *
 * @package HandyBot_Demo
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="hb-site-header">
	<a class="hb-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'HandyBot domů', 'handybot-demo' ); ?>">
		<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/handybot-logo.png' ); ?>" alt="HandyBot">
	</a>
	<nav class="hb-site-nav" aria-label="<?php esc_attr_e( 'Hlavní navigace', 'handybot-demo' ); ?>">
		<a href="<?php echo esc_url( home_url( '/#sluzby' ) ); ?>">Co umíme</a>
		<a href="<?php echo esc_url( home_url( '/#vysledky' ) ); ?>">Výsledky</a>
		<a href="<?php echo esc_url( home_url( '/#kalkulacka' ) ); ?>">Kalkulačka</a>
		<a href="<?php echo esc_url( home_url( '/#rozhovor' ) ); ?>">Rozhovor</a>
		<a href="<?php echo esc_url( home_url( '/#otazky' ) ); ?>">Otázky</a>
	</nav>
	<a class="hb-header-cta" href="<?php echo esc_url( home_url( '/#kontakt' ) ); ?>">Nezávazná konzultace <span>↗</span></a>
	<button class="hb-menu-button" type="button" aria-expanded="false" aria-label="<?php esc_attr_e( 'Otevřít menu', 'handybot-demo' ); ?>">☰</button>
</header>
