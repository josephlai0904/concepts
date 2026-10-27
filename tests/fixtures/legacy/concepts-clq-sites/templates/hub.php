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
get_footer();
