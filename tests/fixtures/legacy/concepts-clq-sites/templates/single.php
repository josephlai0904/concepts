<?php
/* Single CLQ site: theme header (Concepts header), the property page, theme footer. */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
while ( have_posts() ) {
	the_post();
	echo ccq_render( get_post() ); // Escaped inside the template.
}
get_footer();
