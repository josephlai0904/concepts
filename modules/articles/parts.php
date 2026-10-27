<?php
/* Shared pieces in the Concepts Group page language: the inner-page hero and the navy wave CTA band. */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Heading with one accent phrase in blue, like the rest of the site. */
function cga_accent( $text, $accent ) {
	$h = esc_html( $text );
	$a = esc_html( $accent );
	return ( '' !== $a && false !== strpos( $h, $a ) ) ? str_replace( $a, '<em>' . $a . '</em>', $h ) : $h;
}

/* Navy band with the site's double wave, heading and two pills — closes every blog page. */
function cga_cta_band() {
	?>
<section class="cga-cta" aria-labelledby="cga-cta-h">
	<i class="cga-wv cga-wv-a" aria-hidden="true"></i><i class="cga-wv" aria-hidden="true"></i>
	<div class="cga-cta-in">
		<div>
			<p class="cga-eb">Workforce support</p>
			<h2 id="cga-cta-h" class="cga-cta-h"><?php echo cga_accent( 'Need workers housed or hired?', 'housed or hired?' ); // Escaped in cga_accent. ?></h2>
			<p class="cga-cta-p">See our CLQ properties across Klang Valley, or tell our team your headcount and timeline for recruitment, accommodation or transport.</p>
		</div>
		<div class="cga-cta-btns">
			<a class="cga-pill cga-pill-w" href="<?php echo esc_url( home_url( '/#contact' ) ); ?>">Get a quote</a>
			<a class="cga-pill cga-pill-o" href="<?php echo esc_url( home_url( '/properties/' ) ); ?>">View properties</a>
		</div>
	</div>
</section>
	<?php
}
