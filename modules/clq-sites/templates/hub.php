<?php
/* /properties/ hub: every published property, linking to its fact sheet. */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
$hub = ccq_hub();
?>
<div id="ccq-page" class="ccq">
	<section class="ccq-sec ccq-hub-head">
		<div class="ccq-wrap">
			<nav class="ccq-bc" aria-label="Breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span aria-hidden="true">›</span><span aria-current="page">Properties</span></nav>
			<h1 class="ccq-h1"><?php echo esc_html( $hub['title'] ); ?></h1>
			<p class="ccq-lead"><?php echo esc_html( $hub['intro'] ); ?></p>
		</div>
	</section>
	<?php ccq_explore( ccq_sites(), false ); ?>
</div>
<?php
/* Employer FAQ (uses the Recruitment FAQ design from the Sections module when it is loaded). */
$ccq_faq = ccq_hub_faq();
if ( $ccq_faq && shortcode_exists( 'cgs_fwr_faq' ) ) {
	echo do_shortcode( '[cgs_fwr_faq h2="CLQ FAQs" h3="Questions employers ask" h3e="employers ask" t="Need worker accommodation?" p="Tell us your headcount, workplace and move-in date. Our team will confirm availability and send a written quote." s1="Klang Valley CLQs" s2="Reliable Workforce Support" url="https://concepts.manyproject.my/contact/" items="' . rawurlencode( wp_json_encode( $ccq_faq ) ) . '"]' );
}
get_footer();
