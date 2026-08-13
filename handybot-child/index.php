<?php
/**
 * Fallback template.
 *
 * @package HandyBot_Demo
 */

get_header();

while ( have_posts() ) {
	the_post();
	the_content();
}

get_footer();
