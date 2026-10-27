<?php
/* Usage: php run.php <suite-dir> <src-dir> <scenario: fresh|legacy-all|legacy-sections> */
require __DIR__ . '/wp-stub.php';
list( , $SUITE, $SRC, $SCEN ) = $argv;

$legacy = array(
	'concepts-sections/concepts-sections.php'   => array( 'Name' => 'Concepts Group Sections', 'path' => "$SRC/concepts-sections/concepts-sections.php" ),
	'concepts-articles/concepts-articles.php'   => array( 'Name' => 'Concepts Articles', 'path' => "$SRC/concepts-articles/concepts-articles.php" ),
	'concepts-clq-sites/concepts-clq-sites.php' => array( 'Name' => 'Concepts CLQ Sites', 'path' => "$SRC/concepts-clq-sites/concepts-clq-sites.php" ),
);
foreach ( $legacy as $b => $d ) $GLOBALS['INSTALLED'][ $b ] = array( 'Name' => $d['Name'] );
$GLOBALS['INSTALLED']['concepts-group-suite/concepts-group-suite.php'] = array( 'Name' => 'Concepts Group Suite' );

$out = array();
function check( $label, $ok ) { global $out; $out[] = ( $ok ? 'PASS ' : 'FAIL ' ) . $label; }

if ( 'fresh' === $SCEN ) {
	require "$SUITE/concepts-group-suite.php";
	do_action( 'plugins_loaded' );
} elseif ( 'legacy-all' === $SCEN ) {
	// Old plugins loaded the normal way (folders before AND after the suite alphabetically), suite deferred to plugins_loaded.
	require $legacy['concepts-articles/concepts-articles.php']['path'];
	require $legacy['concepts-clq-sites/concepts-clq-sites.php']['path'];
	require "$SUITE/concepts-group-suite.php";
	require $legacy['concepts-sections/concepts-sections.php']['path'];
	$GLOBALS['ACTIVE'] = array_keys( $legacy );
	do_action( 'plugins_loaded' );
	check( 'all 3 modules skipped while old plugins active', 3 === count( $GLOBALS['cg_suite_skipped'] ) );
	// Now the activation request: suite activated -> old ones switched off.
	foreach ( $GLOBALS['ACT'] as $cb ) $cb();
	check( 'old plugins deactivated on activation', array() === $GLOBALS['ACTIVE'] );
	check( 'switched-off notice queued', 3 === count( (array) get_transient( 'cg_suite_switched_off' ) ) );
} elseif ( 'legacy-sections' === $SCEN ) {
	require "$SUITE/concepts-group-suite.php";
	require $legacy['concepts-sections/concepts-sections.php']['path']; // loads after suite folder
	$GLOBALS['ACTIVE'] = array( 'concepts-sections/concepts-sections.php' );
	do_action( 'plugins_loaded' );
	check( 'only sections skipped', array( 'sections' ) === array_keys( $GLOBALS['cg_suite_skipped'] ) );
	check( 'articles + clq loaded from suite', function_exists( 'cga_headings' ) && function_exists( 'ccq_get' ) );
} elseif ( 'activate-request' === $SCEN ) {
	// Real WP activation flow: old plugins active & loaded, plugins_loaded done, THEN suite file included in sandbox.
	foreach ( $legacy as $d ) require $d['path'];
	$GLOBALS['ACTIVE'] = array_keys( $legacy );
	do_action( 'plugins_loaded' );
	do_action( 'init' );
	require "$SUITE/concepts-group-suite.php";
	foreach ( $GLOBALS['ACT'] as $cb ) $cb();
	check( 'activation in sandbox did not crash, old switched off', array() === $GLOBALS['ACTIVE'] );
}

if ( 'activate-request' !== $SCEN ) {
	check( 'all module functions present', function_exists( 'cgs_global_css' ) && function_exists( 'cga_headings' ) && function_exists( 'ccq_get' ) );
	if ( 'fresh' === $SCEN ) {
		check( 'no modules skipped/failed', ! $GLOBALS['cg_suite_skipped'] && ! $GLOBALS['cg_suite_failed'] );
		foreach ( $GLOBALS['ACT'] as $cb ) $cb();
		check( 'activation seeded 4 CLQ sites', 4 === count( get_posts( array( 'post_type' => 'ccq_site', 'post_status' => 'any' ) ) ) );
		check( 'rewrite flushed', ! empty( $GLOBALS['FLUSHED'] ) );
	}

	// Admin request
	do_action( 'init' );
	do_action( 'vc_before_init' );
	do_action( 'admin_menu' );
	do_action( 'rest_api_init' );
	do_action( 'admin_enqueue_scripts', 'post.php' );
	ob_start(); do_action( 'admin_notices' ); $notices = ob_get_clean();
	check( 'post types registered', isset( $GLOBALS['PT']['ccq_site'], $GLOBALS['PT']['ccq_enquiry'] ) );
	check( 'REST route registered', isset( $GLOBALS['ROUTES']['ccq/v1/enquiry'] ) );
	check( 'WPBakery elements mapped: ' . count( $GLOBALS['VC'] ), count( $GLOBALS['VC'] ) > 20 );
	apply_filters( 'plugin_action_links', array( 'activate' => 'x' ), 'concepts-articles/concepts-articles.php' );

	// Admin: site editor meta boxes
	$site = get_posts( array( 'post_type' => 'ccq_site', 'post_status' => 'any' ) );
	if ( ! $site ) { $sid = wp_insert_post( array( 'post_type' => 'ccq_site', 'post_title' => 'Concepts Test', 'post_status' => 'publish' ) ); $site = array( get_post( $sid ) ); }
	do_action( 'add_meta_boxes_ccq_site', $site[0] );
	do_action( 'add_meta_boxes', 'ccq_site', $site[0] );
	foreach ( (array) ( $GLOBALS['MB'] ?? array() ) as $id => $cb ) { ob_start(); call_user_func( $cb, $site[0] ); ob_end_clean(); }
	check( 'admin meta boxes rendered: ' . count( (array) ( $GLOBALS['MB'] ?? array() ) ), true );

	// Front end
	$GLOBALS['IS_ADMIN'] = false;
	foreach ( $site as $p ) { $p->post_status = 'publish'; }
	$__pid = wp_insert_post( array( 'post_type' => 'post', 'post_title' => 'Hello', 'post_content' => '<h2>One</h2><p>Body text here.</p><h3>Two</h3><h2>One</h2>' ) );

	$pages = array(
		'home'          => array( 'is_front_page' => true ),
		'single post'   => array( 'is_singular' => array( 'post' ), 'cur' => $__pid ),
		'blog list'     => array( 'is_home' => true ),
		'category'      => array( 'is_category' => true ),
		'property'      => array( 'is_singular' => array( 'ccq_site' ), 'cur' => $site[0]->ID ),
		'property hub'  => array( 'is_post_type_archive' => array( 'ccq_site' ) ),
	);
	foreach ( $pages as $__name => $q ) {
		$GLOBALS['CUR'] = $q['cur'] ?? 0; $GLOBALS['post'] = get_post( $GLOBALS['CUR'] ); unset( $q['cur'] ); $GLOBALS['Q'] = $q; $GLOBALS['LOOP'] = 0;
		$before = count( $GLOBALS['ISSUES'] );
		do_action( 'wp_enqueue_scripts' );
		apply_filters( 'body_class', array() );
		apply_filters( 'wp_robots', array() );
		apply_filters( 'pre_get_document_title', '' );
		ob_start(); do_action( 'wp_head' ); do_action( 'wp_body_open' ); do_action( 'wp_footer' ); ob_end_clean();
		$tpl = apply_filters( 'template_include', 'theme.php' );
		$html = '';
		if ( 'theme.php' !== $tpl ) {
			try { ob_start(); include $tpl; $html = ob_get_clean(); } catch ( Throwable $e ) { ob_end_clean(); $GLOBALS['ISSUES'][] = "$__name: " . $e->getMessage() . ' @ ' . basename( $e->getFile() ) . ':' . $e->getLine(); }
		}
		check( "page '$__name' rendered (" . basename( $tpl ) . ', ' . strlen( $html ) . ' bytes)', count( $GLOBALS['ISSUES'] ) === $before );
	}

	// Every shortcode with defaults
	$GLOBALS['Q'] = array(); $GLOBALS['CUR'] = 0;
	$bad = array();
	foreach ( $GLOBALS['SC'] as $tag => $cb ) {
		$before = count( $GLOBALS['ISSUES'] );
		try { $h = call_user_func( $cb, 'ccq_site' === $tag ? array( 'slug' => $site[0]->post_name ) : array(), '', $tag ); } catch ( Throwable $e ) { $GLOBALS['ISSUES'][] = "[$tag] " . $e->getMessage() . ' @ ' . basename( $e->getFile() ) . ':' . $e->getLine(); $h = ''; }
		if ( count( $GLOBALS['ISSUES'] ) !== $before || '' === trim( (string) $h ) ) $bad[] = $tag;
	}
	check( 'all ' . count( $GLOBALS['SC'] ) . ' shortcodes render' . ( $bad ? ' (problem: ' . implode( ',', $bad ) . ')' : '' ), ! $bad );

	// Brand logo (2.0.1)
	$hd = call_user_func( $GLOBALS['SC']['cgs_header'], array(), '', 'cgs_header' );
	$ft = call_user_func( $GLOBALS['SC']['cgs_footer'], array( 'logo' => '999' ), '', 'cgs_footer' );
	$cu = call_user_func( $GLOBALS['SC']['cgs_footer'], array( 'logo' => '42' ), '', 'cgs_footer' );
	if ( empty( $GLOBALS['cg_suite_skipped']['sections'] ) ) { // only when the suite's own Sections module is loaded
		check( 'header default logo = since-1991 white', false !== strpos( $hd, 'Concepts-Group-Logo-since-1991-white.webp' ) );
		check( 'old white logo picked in WPBakery swaps to new', false !== strpos( $ft, 'Concepts-Group-Logo-since-1991-white.webp' ) && false === strpos( $ft, 'Concepts-Group-White-Logo.webp' ) );
		check( 'custom logo picked in WPBakery is kept', false !== strpos( $cu, 'i42.jpg' ) );
	}

	// Header bar matches the content width (2.0.2): same 5vw gutter as .cgs-hero-in, no fixed px cap.
	if ( empty( $GLOBALS['cg_suite_skipped']['sections'] ) ) {
		preg_match( '/\.cgs-hd \.cgs-bar\{[^}]*\}/', cgs_header_css(), $bar );
		$bar = $bar ? $bar[0] : '';
		check( 'header bar spans content width (calc(100% - 10vw))', false !== strpos( $bar, 'width:calc(100% - 10vw)' ) );
		check( 'header bar has no px max-width cap', ! preg_match( '/max-width:\d+px/', $bar ) );
		// Sky hero (2.1.0): default style; Classic stays available as a one-click restore.
		$sky = call_user_func( $GLOBALS['SC']['cgs_hero'], array(), '', 'cgs_hero' );
		$cls = call_user_func( $GLOBALS['SC']['cgs_hero'], array( 'style' => 'classic' ), '', 'cgs_hero' );
		check( 'hero default = sky style', false !== strpos( $sky, 'cgs-hero cgs-hero-sky' ) );
		check( 'sky hero uses sky, building and team images', false !== strpos( $sky, '1472.webp' ) && false !== strpos( $sky, 'Concept-Group-Hostel-and-accommodation.webp' ) && false !== strpos( $sky, 'foreign-workers.webp' ) );
		check( 'sky hero keeps H1, buttons, stats, enquiry card, wave', false !== strpos( $sky, '<h1 class="cgs-hero-h"' ) && false !== strpos( $sky, 'cgs-hb1' ) && false !== strpos( $sky, 'cgs-hero-stats' ) && false !== strpos( $sky, 'cgs-hero-enq' ) && false !== strpos( $sky, 'cgs-hero-wave' ) );
		check( 'sky hero has no "Since 1991" chip (2.1.1)', false === strpos( $sky, 'cgs-hero-chip' ) );
		check( 'classic hero still has its chip', false !== strpos( $cls, 'cgs-hero-chip' ) );
		check( 'sky team sized by hero height (heads in top 30%)', (bool) preg_match( '/\.cgs-hero-sky \.cgs-sky-team\{[^}]*height:(7[6-9]|8[0-9])%;width:auto/', cgs_hero_css() ) );
		check( 'sky building wide enough to show left wing (59vw)', false !== strpos( cgs_hero_css(), '.cgs-sky-bld{position:absolute;right:-7vw;bottom:clamp(24px,2.4vw,48px);width:59vw;' ) );
		check( 'tablet team not stretched (height:auto)', false !== strpos( cgs_hero_css(), 'width:52%;height:auto}' ) );
		check( 'sky enquiry card on the right', (bool) preg_match( '/\.cgs-hero-sky \.cgs-hero-enq\{left:auto;right:[0-9.]+vw/', cgs_hero_css() ) && false === strpos( cgs_hero_css(), 'right:42vw' ) );
		check( 'classic style restores the old hero', false !== strpos( $cls, 'cgs-hero-arch' ) && false === strpos( $cls, 'cgs-hero-sky' ) );
		check( 'sky hero CSS present (desktop + mobile)', false !== strpos( cgs_hero_css(), '.cgs-hero-sky .cgs-sky-team{' ) && false !== strpos( cgs_hero_css(), '@media(max-width:1024px){body .cgs-hero.cgs-hero-sky{' ) );
		// 2.2.0: layered wave, 3D enquiry card with entry animation, worker parallax, bigger/left workers, building right.
		check( 'sky hero has a 3-layer wave', false !== strpos( $sky, 'cgs-wave-l1' ) && false !== strpos( $sky, 'cgs-wave-l2' ) && false !== strpos( $sky, 'cgs-wave-l3' ) );
		check( 'classic hero keeps its single wave', false !== strpos( $cls, 'cgs-hero-wave' ) && false === strpos( $cls, 'cgs-wave-l1' ) );
		check( 'wave back layers sit behind the workers, white layer in front', false !== strpos( $sky, 'cgs-wave-back' ) && false !== strpos( $sky, 'cgs-wave-front' ) && false !== strpos( cgs_hero_css(), '.cgs-hero-sky .cgs-wave-back{z-index:1}' ) );
		check( 'enquiry card has icon bubble', false !== strpos( $sky, 'cgs-enq-ic' ) );
		check( 'enquiry card slides up on entry (with reduced-motion off switch)', false !== strpos( cgs_hero_css(), '@keyframes cgsEnqUp' ) && (bool) preg_match( '/prefers-reduced-motion:reduce\)\{[^@]*\.cgs-hero-sky \.cgs-hero-enq\{[^}]*animation:none/', cgs_hero_css() ) );
		do_action( 'wp_enqueue_scripts' );
		$js = implode( "\n", (array) ( $GLOBALS['INLINE']['cgs-hero'] ?? array() ) );
		check( 'workers parallax script present (respects reduced motion)', false !== strpos( $js, '.cgs-sky-team' ) && false !== strpos( $js, "cgs-hero-sky" ) && substr_count( $js, 'prefers-reduced-motion' ) >= 2 );
		check( 'workers bigger (83%) and further left (9vw); building further right (-7vw)', false !== strpos( cgs_hero_css(), '.cgs-hero-sky .cgs-sky-team{position:absolute;right:9vw;bottom:0;height:83%;' ) && false !== strpos( cgs_hero_css(), '.cgs-sky-bld{position:absolute;right:-7vw;' ) );
		check( 'sky hero bottom padding slightly shorter (2.2.4)', false !== strpos( cgs_hero_css(), 'body .cgs-hero.cgs-hero-sky{padding-bottom:clamp(130px,13vw,220px)}' ) && strpos( cgs_hero_css(), 'body .cgs-hero.cgs-hero-sky{padding-bottom:clamp(130px,13vw,220px)}' ) < strpos( cgs_hero_css(), '@media(max-width:1024px){body .cgs-hero.cgs-hero-sky{' ) );
		$hc = cgs_hero_css();
		check( 'readability: H1 tracking relaxed (-.02em) and line-height 1.1 in sky hero', false !== strpos( $hc, 'body.cgs-site #lqd-site-content .cgs-hero-sky h1.cgs-hero-h{letter-spacing:-.02em;line-height:1.1}' ) );
		check( 'readability: H1 blue line uses darker brand blue for contrast', false !== strpos( $hc, '.cgs-hero-sky .cgs-hero-h em{color:#0b6fd1}' ) );
		check( 'readability: fixed spacing scale (px, not vw) for eyebrow/lead/buttons/stats', false !== strpos( $hc, 'body.cgs-site #lqd-site-content .cgs-hero-sky h2.cgs-hero-eb{margin:0 0 18px' ) && false !== strpos( $hc, '.cgs-hero-sky .cgs-hero-lead{margin:22px 0 32px;max-width:520px' ) && false !== strpos( $hc, '.cgs-hero-sky .cgs-hero-stats{width:fit-content;max-width:100%;margin-top:40px;padding-top:24px;border-top:1px solid' ) );
		check( 'readability: primary button text passes 4.5:1 (darker blue)', false !== strpos( $hc, '.cgs-hero-sky .cgs-hb1{background:#0b6fd1;border-color:#0b6fd1}' ) );
		$hdr = call_user_func( $GLOBALS['SC']['cgs_header'], array(), '', 'cgs_header' );
		check( 'header button default = Get in touch', false !== strpos( $hdr, 'Get in touch' ) && false === strpos( $hdr, 'Get a quote' ) );
		check( 'hero eyebrow default = Workforce · Since 1991', false !== strpos( $sky, '>Workforce · Since 1991</h2>' ) );
		check( 'homepage hero H1 = max(24px,min(6cqw,52px))', (bool) preg_match( '/\.cgs-hero h1\.cgs-hero-h\{margin:0;[^}]*font-size:max\(24px,min\(6cqw,52px\)\)\}/', cgs_hero_css() ) );
		check( 'header bar uses 6vw gutter at <=1024px like the content', false !== strpos( cgs_header_css(), '@media(max-width:1024px){.cgs-hd .cgs-bar{width:calc(100% - 12vw)}}' ) );
	}

	// 2.2.5: CLQ page wording + taller middle Surroundings photo; smaller homepage hero text.
	if ( empty( $GLOBALS['cg_suite_skipped']['clq-sites'] ) ) {
		$pg = ccq_render( $site[0] );
		check( 'CLQ: quote buttons now say "Talk to a specialist"', 2 === substr_count( $pg, '>Talk to a specialist</a>' ) && false === strpos( $pg, 'Get a quote' ) && false === strpos( $pg, 'Get a written quote' ) );
		$ccss = file_get_contents( CCQ_DIR . 'assets/site.css' );
		check( 'CLQ: middle Surroundings photo 22% taller, carousel grows to fit', false !== strpos( $ccss, '.ccq-car-i[data-pos=m]{left:37%;width:45%;--h:calc(var(--hm) * 1.22)}' ) && false !== strpos( $ccss, 'height:calc(var(--hm) * 1.22 + 118px)' ) );
	}
	if ( empty( $GLOBALS['cg_suite_skipped']['sections'] ) ) {
		$hc2 = cgs_hero_css();
		check( 'Home hero text slightly smaller on desktop/tablet (H1 max 46px, lead, buttons, stats)', false !== strpos( $hc2, '@media(min-width:768px){body.cgs-site #lqd-site-content .cgs-hero-sky h1.cgs-hero-h{font-size:max(24px,min(5.4cqw,46px))}' ) && false !== strpos( $hc2, '.cgs-hero-sky .cgs-hero-stats b{font-size:clamp(24px,1.8vw,30px)}' ) );
	}

	// Enquiry endpoint
	$r = ccq_enquiry( new WP_REST_Request( array( 'site' => $site[0]->ID, 'company' => 'ACME', 'workers' => 30, 'email' => 'x@y.com', 'message' => 'hi' ) ) );
	check( 'enquiry accepted + 2 emails', is_array( $r ) && ! empty( $r['ok'] ) && 2 === count( $GLOBALS['MAIL'] ?? array() ) );
	$r = ccq_enquiry( new WP_REST_Request( array( 'site' => 0 ) ) );
	check( 'bad enquiry rejected 422', $r instanceof WP_REST_Response && 422 === $r->status );
}

echo "== $SCEN\n" . implode( "\n", $out ) . "\n";
$iss = array_unique( $GLOBALS['ISSUES'] );
echo $iss ? "ISSUES:\n  " . implode( "\n  ", $iss ) . "\n" : "No PHP warnings/notices/errors.\n";
$fails = count( array_filter( $out, function ( $l ) { return 0 === strpos( $l, 'FAIL' ); } ) );
exit( ( $fails || $iss ) ? 1 : 0 );
