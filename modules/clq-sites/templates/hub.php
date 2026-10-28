<?php
/* Hub at /centralised-labour-quarters/: the same first two sections as the service pages
 * (white-to-blue hero with a framed photo, then the navy band), then the employer FAQ. */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
$hub   = ccq_hub();
$sites = ccq_sites();
global $wp_query;
$page_sites = ( $wp_query && is_array( $wp_query->posts ) ) ? $wp_query->posts : array_slice( $sites, 0, 3 ); /* This page of the grid (3 per page). */
$areas = array();
$photo = '';
foreach ( $sites as $p ) {
	$o       = ccq_get( $p );
	$areas[] = $o['area'];
	if ( '' === $photo ) {
		$ph    = ccq_media_list( $o['photos'], 'large' );
		$photo = $ph ? $ph[0]['src'] : '';
	}
}
$last  = count( $areas ) > 1 ? array_pop( $areas ) : '';
$where = $areas ? implode( ', ', $areas ) . ( $last ? ' and ' . $last : '' ) : 'Klang Valley';
$opt   = get_option( 'ccq_hub', array() );
$lead  = ! empty( $opt['intro'] ) ? $opt['intro'] : 'Managed worker accommodation near the industrial areas of ' . $where . '. Compare each property\'s rooms, rent inclusions and nearby places, then book a site visit.';
/* "Centralised Labour Quarters in Klang Valley" -> first line, blue second line. */
$cut   = strrpos( $hub['title'], ' in ' );
$h1    = false !== $cut ? substr( $hub['title'], 0, $cut ) : $hub['title'];
$h2    = false !== $cut ? substr( $hub['title'], $cut + 1 ) : '';
?>
<?php /* The hero sits outside #ccq-page so the property-page styles do not touch the shared hero. */ ?>
	<?php if ( shortcode_exists( 'cgs_story_hero' ) ) :
		$q = function ( $v ) { return str_replace( array( '"', '[', ']' ), array( '&quot;', '&#91;', '&#93;' ), $v ); };
		echo do_shortcode( '[cgs_story_hero eyebrow="Worker accommodation" h1="' . $q( $h1 ) . '" h2="' . $q( $h2 ) . '" lead="' . $q( $lead ) . '"' . ( $photo ? ' img="' . esc_url( $photo ) . '"' : '' ) . ']' );
	?>
<div id="ccq-page" class="ccq ccq-hub">
	<?php else : ?>
<div id="ccq-page" class="ccq ccq-hub">
	<section class="ccq-sec ccq-hub-head">
		<div class="ccq-wrap">
			<h1 class="ccq-h1"><?php echo esc_html( $hub['title'] ); ?></h1>
			<p class="ccq-lead"><?php echo esc_html( $lead ); ?></p>
		</div>
	</section>
	<?php endif; ?>
	<?php ccq_hub_band( $page_sites, $sites ); ?>
	<?php ccq_hub_about(); ?>
</div>
<?php
/* Employer FAQ (uses the Recruitment FAQ design from the Sections module when it is loaded). */
$ccq_faq = ccq_hub_faq();
if ( $ccq_faq && shortcode_exists( 'cgs_fwr_faq' ) ) {
	echo '<div class="ccq-hub-faq">';
	echo do_shortcode( '[cgs_fwr_faq h2="CLQ FAQs" h3="Questions employers ask" h3e="employers ask" t="Need worker accommodation?" p="Tell us your headcount, workplace and move-in date. Our team will confirm availability and send a written quote." s1="Klang Valley CLQs" s2="Reliable Workforce Support" url="' . esc_url( get_page_by_path( 'contact' ) ? get_permalink( get_page_by_path( 'contact' ) ) : home_url( '/#contact' ) ) . '" items="' . rawurlencode( wp_json_encode( $ccq_faq ) ) . '"]' );
	echo ccq_layer_wave( '#eef3f9' ); // Into the footer's light background.
	echo '</div>';
}
get_footer();
