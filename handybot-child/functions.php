<?php
/**
 * HandyBot Demo child theme helpers.
 *
 * @package HandyBot_Demo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'wp_enqueue_scripts',
	static function (): void {
		wp_enqueue_style(
			'handybot-demo',
			get_stylesheet_uri(),
			array(
				'hello-elementor',
				'hello-elementor-theme-style',
				'hello-elementor-header-footer',
				'elementor-frontend',
			),
			wp_get_theme()->get( 'Version' )
		);

		wp_enqueue_script(
			'handybot-demo-navigation',
			get_stylesheet_directory_uri() . '/navigation.js',
			array(),
			wp_get_theme()->get( 'Version' ),
			true
		);
	},
	1000
);

add_action(
	'after_setup_theme',
	static function (): void {
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
	}
);
