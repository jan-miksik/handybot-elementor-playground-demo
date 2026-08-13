<?php
/**
 * Front page rendered from Elementor-managed content.
 *
 * @package HandyBot_Demo
 */

get_header();

while ( have_posts() ) {
	the_post();
	the_content();
}

get_footer();
