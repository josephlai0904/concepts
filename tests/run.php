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
		if ( 'property hub' === $__name && shortcode_exists( 'cgs_fwr_faq' ) && empty( $GLOBALS['cg_suite_skipped']['sections'] ) ) {
			check( 'CLQ hub page shows the employer FAQ', false !== strpos( $html, 'What is a CLQ' ) );
		}
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
	$ft = call_user_func( $GLOBALS['SC']['cgs_footer'], array( 'style' => 'modern', 'logo' => '999' ), '', 'cgs_footer' );
	$cu = call_user_func( $GLOBALS['SC']['cgs_footer'], array( 'style' => 'modern', 'logo' => '42' ), '', 'cgs_footer' );
	$cd = call_user_func( $GLOBALS['SC']['cgs_footer'], array( 'logo_dark' => '43' ), '', 'cgs_footer' );
	if ( empty( $GLOBALS['cg_suite_skipped']['sections'] ) ) { // only when the suite's own Sections module is loaded
		check( 'header default logo = since-1991 white', false !== strpos( $hd, 'Concepts-Group-Logo-since-1991-white.webp' ) );
		check( 'old white logo picked in WPBakery swaps to new', false !== strpos( $ft, 'Concepts-Group-Logo-since-1991-white.webp' ) && false === strpos( $ft, 'Concepts-Group-White-Logo.webp' ) );
		check( 'card footer: dark logo picked in WPBakery is used', false !== strpos( $cd, 'i43.jpg' ) );
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
		// 2.4.3: phone header = toggle left, logo centre, arrow button right; full-screen white panel slides from top.
		check( 'phone header: arrow button + slide panel in markup', false !== strpos( $hdr, 'class="cgs-mgo"' ) && false !== strpos( $hdr, 'class="cgs-mpanel"' ) );
		$hcss = cgs_header_css();
		check( 'phone header: 3-column grid (toggle | logo | arrow)', false !== strpos( $hcss, '.cgs-hd .cgs-bar{display:grid;grid-template-columns:44px 1fr 44px;' ) && false !== strpos( $hcss, '.cgs-hd .cgs-burger{display:grid;grid-column:1;grid-row:1;justify-self:start' ) && false !== strpos( $hcss, '.cgs-hd .cgs-mgo{display:grid;grid-column:3' ) );
		check( 'phone menu: white sheet only as tall as the menu, slides from top', false !== strpos( $hcss, '.cgs-hd nav{position:fixed;top:0;left:0;right:0;bottom:auto;z-index:-1;display:flex;visibility:hidden;transform:translateY(-100%);' ) && false !== strpos( $hcss, '.cgs-hd nav.open{visibility:visible;transform:none;' ) && false === strpos( $hcss, 'bottom:24px;background:none' ) );
		check( 'phone menu: dark overlay behind, tap overlay closes', false !== strpos( $hcss, '.cgs-hd .cgs-mpanel{display:block;position:fixed;inset:0;z-index:-2;background:rgba(7,26,51,.55);opacity:0;' ) && false !== strpos( cgs_header_js(), "querySelector('.cgs-mpanel').addEventListener('click'" ) );
		check( 'phone header: script opens/closes panel, locks page scroll, Esc closes', false !== strpos( cgs_header_js(), "h.classList.toggle('mo'" ) && false !== strpos( cgs_header_js(), 'cgs-menu-open' ) && false !== strpos( cgs_header_js(), "'Escape'" ) );
		check( 'phone menu: clear hierarchy (group label small blue caps, pages grey with guide line)', false !== strpos( $hcss, '.cgs-hd nav .cgs-dd-menu a.cgs-subt{padding:14px 0 6px;font-size:12px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#0b6fd1}' ) && false !== strpos( $hcss, '.cgs-hd nav .cgs-sub-menu{margin:2px 0 6px 2px;padding:0 0 0 14px;border-left:2px solid #e6edf5}' ) );
		check( 'Our Approach cards: no hover lift / colour change', false === strpos( cgs_process_css(), '.cgs-pr-card:hover' ) );
		check( 'header button default = Get in touch', false !== strpos( $hdr, 'Get in touch' ) && false === strpos( $hdr, 'Get a quote' ) );
		check( 'hero eyebrow default = Workforce · Since 1991', false !== strpos( $sky, '>Workforce · Since 1991</h2>' ) );
		// 2.4.2: gentler parallax; phone scene without empty sky under the building.
		check( 'parallax gentler: 0.12 of scroll, max 12% of the workers height', false !== strpos( $js, 'Math.min(w.offsetHeight*.12,Math.max(0,-r.top*.12))' ) );
		check( 'phone scene shorter and building sits low (no empty sky above the wave)', false !== strpos( cgs_hero_css(), '.cgs-hero-sky .cgs-sky-scene{aspect-ratio:5/4}' ) && false !== strpos( cgs_hero_css(), '.cgs-hero-sky .cgs-sky-bld{width:96%;right:-12%;bottom:6%}' ) );
		check( 'homepage hero H1 = max(24px,min(6cqw,52px))', (bool) preg_match( '/\.cgs-hero h1\.cgs-hero-h\{margin:0;[^}]*font-size:max\(24px,min\(6cqw,52px\)\)\}/', cgs_hero_css() ) );
		check( 'header bar uses 6vw gutter at <=1024px like the content', false !== strpos( cgs_header_css(), '@media(max-width:1024px){.cgs-hd .cgs-bar{width:calc(100% - 12vw)}}' ) );
	}

	// 2.2.5: CLQ page wording + taller middle Surroundings photo; smaller homepage hero text.
	if ( empty( $GLOBALS['cg_suite_skipped']['clq-sites'] ) ) {
		$pg = ccq_render( $site[0] );
		$sjs = file_get_contents( CCQ_DIR . 'assets/site.js' );
		check( 'CLQ map: scroll-wheel zoom is OFF (page scrolls past the map)', false !== strpos( $sjs, 'scrollWheelZoom: false' ) && false === strpos( $sjs, 'scrollWheelZoom: true' ) );
		check( 'CLQ: quote buttons now say "Talk to a specialist"', 2 === substr_count( $pg, '>Talk to a specialist</a>' ) && false === strpos( $pg, 'Get a quote' ) && false === strpos( $pg, 'Get a written quote' ) );
		$ccss = file_get_contents( CCQ_DIR . 'assets/site.css' );
		check( 'CLQ: contact box background is darker navy #071A33', false !== strpos( $ccss, '#ccq-page.ccq .ccq-black{display:flex;flex-direction:column;justify-content:space-between;gap:40px;padding:clamp(20px,2.2vw,32px);border-radius:clamp(18px,1.9vw,26px);background:#071A33;' ) );
		check( 'CLQ: middle Surroundings photo 22% taller, carousel grows to fit', false !== strpos( $ccss, '.ccq-car-i[data-pos=m]{left:37%;width:45%;--h:calc(var(--hm) * 1.22)}' ) && false !== strpos( $ccss, 'height:calc(var(--hm) * 1.22 + 118px)' ) );
	}
	if ( empty( $GLOBALS['cg_suite_skipped']['sections'] ) ) {
		$hc2 = cgs_hero_css();
		check( 'Home hero text slightly smaller on desktop/tablet (H1 max 46px, lead, buttons, stats)', false !== strpos( $hc2, '@media(min-width:768px){body.cgs-site #lqd-site-content .cgs-hero-sky h1.cgs-hero-h{font-size:max(24px,min(5.4cqw,46px))}' ) && false !== strpos( $hc2, '.cgs-hero-sky .cgs-hero-stats b{font-size:clamp(24px,1.8vw,30px)}' ) );
		check( 'Home hero buttons padding halfway (14px 26px)', false !== strpos( $hc2, '.cgs-hero-sky .cgs-hb{padding:14px 26px;font-size:15px}' ) );
	}

	// 2.3.0: modern footer (reference style), auto year, company reg. no., classic restore.
	if ( empty( $GLOBALS['cg_suite_skipped']['sections'] ) ) {
		$yr  = gmdate( 'Y' );
		$ft2 = call_user_func( $GLOBALS['SC']['cgs_footer'], array( 'style' => 'modern' ), '', 'cgs_footer' );
		$ftO = call_user_func( $GLOBALS['SC']['cgs_footer'], array( 'style' => 'modern', 'copy' => '© 2026 CONCEPTS ATC SDN BHD. All Rights Reserved.' ), '', 'cgs_footer' );
		$ftC = call_user_func( $GLOBALS['SC']['cgs_footer'], array( 'style' => 'classic' ), '', 'cgs_footer' );
		$want = 'CONCEPTS ATC SDN BHD (Reg. No.: 199501034798 / 364000-P) © ' . $yr . ' All Rights Reserved.';
		check( 'footer: new copyright line with current year', false !== strpos( $ft2, esc_html( $want ) ) );
		check( 'footer: old saved copyright text upgrades to the new line', false !== strpos( $ftO, esc_html( $want ) ) );
		check( 'footer: year is automatic (custom text with an old year is updated)', false !== strpos( call_user_func( $GLOBALS['SC']['cgs_footer'], array( 'copy' => 'ACME © 2019 All rights.' ), '', 'cgs_footer' ), 'ACME © ' . $yr . ' All rights.' ) );
		check( 'footer: modern layout has CTA, columns, wordmark, no credit', false !== strpos( $ft2, 'cgs-ft2' ) && false !== strpos( $ft2, 'cgs-ft-cta' ) && false !== strpos( $ft2, 'cgs-ft-mark' ) && false !== strpos( $ft2, 'Back to top' ) && false === strpos( $ft2, 'Many Project' ) );
		check( 'footer: heading breaks after the question mark', false !== strpos( $ft2, 'questions?<br>Talk to our team' ) );
		do_action( 'wp_enqueue_scripts' );
		check( 'footer: wordmark fit-to-width script registered', false !== strpos( implode( "\n", (array) ( $GLOBALS['INLINE']['cgs-footer'] ?? array() ) ), '.cgs-ft-mark' ) );
		// 2.4.0: card footer (default) + floating contact band.
		$ft3 = call_user_func( $GLOBALS['SC']['cgs_footer'], array(), '', 'cgs_footer' );
		check( 'footer: default is the light card style', false !== strpos( $ft3, 'cgs-ft cgs-ft3' ) && false !== strpos( $ft3, 'cgs-ft-card' ) && false !== strpos( $ft3, 'cgs-ft-mark' ) );
		check( 'footer card: dark logo, socials, columns, copyright, back to top, no credit', false !== strpos( $ft3, 'Concepts-Group-Logo-since-1991.webp' ) && false !== strpos( $ft3, 'cgs-ft-soc3' ) && 2 === substr_count( $ft3, '<nav class="cgs-ft-c' ) && false !== strpos( $ft3, esc_html( $want ) ) && false !== strpos( $ft3, 'Back to top' ) && false === strpos( $ft3, 'Many Project' ) );
		check( 'footer card: no CTA heading (contact band above does that job)', false === strpos( $ft3, 'cgs-ft-cta' ) );
		$cta = call_user_func( $GLOBALS['SC']['cgs_cta'], array(), '', 'cgs_cta' );
		check( 'contact band: floating (narrower + rounded on scroll) class and script', false !== strpos( $cta, 'cgs-ct-float' ) && false !== strpos( implode( "\n", (array) ( $GLOBALS['INLINE']['cgs-cta'] ?? array() ) ), 'cgs-ct-float' ) );
		$cc = cgs_cta_css();
		check( 'contact band: area behind the panel matches the footer (#eef3f9)', false !== strpos( $cc, '.cgs-ct.cgs-ct-float{background:#eef3f9' ) );
		check( 'contact band: panel lines up with the footer card (5vw desktop / 6vw phone)', false !== strpos( $cc, '--cgs-ctx:calc(var(--cgs-ctp,1) * 5vw)' ) && false !== strpos( $cc, '--cgs-ctx:calc(var(--cgs-ctp,1) * 6vw)' ) );
		check( 'contact band: same corner radius as the footer card', false !== strpos( $cc, '--cgs-ctr:calc(var(--cgs-ctp,1) * clamp(20px,2vw,32px))' ) );
		check( 'footer: classic style restores old footer', false === strpos( $ftC, 'cgs-ft2' ) && false !== strpos( $ftC, 'cgs-ft-cols' ) );
	}

	// 2.4.3: homepage CLQ buttons -> property pages; contact band inner padding; phone menu has no card shadow.
	if ( empty( $GLOBALS['cg_suite_skipped']['sections'] ) && empty( $GLOBALS['cg_suite_skipped']['clq-sites'] ) ) {
		foreach ( get_posts( array( 'post_type' => 'ccq_site', 'post_status' => 'any' ) ) as $sp ) { $sp->post_status = 'publish'; }
		$cq = call_user_func( $GLOBALS['SC']['cgs_clq'], array(), '', 'cgs_clq' );
		check( 'home CLQ: each button links to its property page and says "View property"', false !== strpos( $cq, '/x/shah-alam/' ) && false !== strpos( $cq, '/x/jenjarom/' ) && substr_count( $cq, '>View property<' ) >= 4 );
		$cq2 = call_user_func( $GLOBALS['SC']['cgs_clq'], array( 'locations' => rawurlencode( wp_json_encode( array( array( 'name' => 'Nowhere Town', 'desc' => 'x' ) ) ) ) ), '', 'cgs_clq' );
		check( 'home CLQ: no matching property page -> falls back to Enquire us / contact', false !== strpos( $cq2, '>Enquire us<' ) && false !== strpos( $cq2, '/contact/' ) );
		check( 'contact band: background photo = candid hostel conversation', false !== strpos( $cta, 'Candid-hostel-conversation-by-the-bunks.webp' ) );
		check( 'contact band: inner padding inside the panel', false !== strpos( cgs_cta_css(), '.cgs-ct.cgs-ct-float .cgs-ct-in{padding-left:calc(var(--cgs-ctx) + clamp(20px,4vw,56px));padding-right:calc(var(--cgs-ctx) + clamp(20px,4vw,56px))}' ) );
		check( 'phone menu sheet: rounded bottom corners only, soft shadow', false !== strpos( cgs_header_css(), 'border-radius:0 0 28px 28px;box-shadow:0 24px 48px -20px rgba(7,26,51,.45)' ) );
	}

	// 2.6.0 SEO / GEO / AEO module.
	if ( empty( $GLOBALS['cg_suite_skipped']['seo'] ) && function_exists( 'cgseo_defaults' ) ) {
		$seo_html = function ( $q, $cur ) { $GLOBALS['Q'] = $q; $GLOBALS['CUR'] = $cur; $GLOBALS['post'] = get_post( $cur ); ob_start(); do_action( 'wp_head' ); $h = ob_get_clean(); return array( apply_filters( 'pre_get_document_title', '' ), $h ); };
		$front = wp_insert_post( array( 'post_type' => 'page', 'post_title' => 'Home', 'post_name' => 'home', 'post_content' => '[cgs_hero]' ) );
		$cpage = wp_insert_post( array( 'post_type' => 'page', 'post_title' => 'Contact', 'post_name' => 'contact', 'post_content' => 'Talk to us.' ) );
		$xpage = wp_insert_post( array( 'post_type' => 'page', 'post_title' => 'Careers', 'post_name' => 'careers', 'post_content' => 'We are hiring site supervisors and drivers across Klang Valley. Apply today with your CV and we will reply within a week.' ) );
		$d = cgseo_defaults();
		list( $t, $h ) = $seo_html( array( 'is_front_page' => true, 'is_singular' => array( 'page' ), 'is_page' => true ), $front );
		check( 'SEO: homepage title', esc_html( $d['front'][0] ) === $t );
		check( 'SEO: homepage meta description', false !== strpos( $h, '<meta name="description" content="' . esc_attr( $d['front'][1] ) . '">' ) );
		check( 'SEO: social tags (og + twitter) with image', false !== strpos( $h, 'property="og:title"' ) && false !== strpos( $h, 'property="og:image"' ) && false !== strpos( $h, 'name="twitter:card" content="summary_large_image"' ) );
		check( 'SEO: Organization schema (legal name, reg. no., founded 1991, phone)', false !== strpos( $h, '"legalName":"CONCEPTS ATC SDN BHD"' ) && false !== strpos( $h, '199501034798' ) && false !== strpos( $h, '"foundingDate":"1991"' ) && false !== strpos( $h, '+603-3318 7773' ) );
		check( 'SEO: WebSite + WebPage schema', false !== strpos( $h, '"@type":"WebSite"' ) && false !== strpos( $h, '"@type":"WebPage"' ) );
		list( $t, $h ) = $seo_html( array( 'is_singular' => array( 'page' ), 'is_page' => true ), $cpage );
		check( 'SEO: contact page title + description', esc_html( $d['contact'][0] ) === $t && false !== strpos( $h, esc_attr( $d['contact'][1] ) ) );
		check( 'SEO: page breadcrumb schema', false !== strpos( $h, '"@type":"BreadcrumbList"' ) );
		update_post_meta( $cpage, '_cgs_seo_title', 'Custom contact title' );
		update_post_meta( $cpage, '_cgs_seo_desc', 'Custom contact description written by the team for this page only.' );
		list( $t, $h ) = $seo_html( array( 'is_singular' => array( 'page' ), 'is_page' => true ), $cpage );
		check( 'SEO: SEO box override wins', 'Custom contact title' === $t && false !== strpos( $h, 'Custom contact description written by the team' ) );
		list( $t, $h ) = $seo_html( array( 'is_singular' => array( 'page' ), 'is_page' => true ), $xpage );
		check( 'SEO: unknown page falls back to "Title | Concepts Group" + text excerpt', 'Careers | Concepts Group' === $t && false !== strpos( $h, 'We are hiring site supervisors' ) );
		list( $t, $h ) = $seo_html( array( 'is_home' => true ), 0 );
		check( 'SEO: blog listing gets a description', false !== strpos( $h, esc_attr( $d['blog'][1] ) ) );
		$bad = array();
		foreach ( $d as $k => $td ) { if ( mb_strlen( $td[0] ) > 62 || mb_strlen( $td[1] ) < 70 || mb_strlen( $td[1] ) > 160 ) { $bad[] = $k . ' (' . mb_strlen( $td[0] ) . '/' . mb_strlen( $td[1] ) . ')'; } }
		check( 'SEO: every default title <= 62 chars, description 70-160 chars' . ( $bad ? ' FAIL: ' . implode( ', ', $bad ) : '' ), ! $bad );
		if ( empty( $GLOBALS['cg_suite_skipped']['sections'] ) ) {
		$GLOBALS['Q'] = array( 'is_front_page' => true ); call_user_func( $GLOBALS['SC']['cgs_faq'], array(), '', 'cgs_faq' ); ob_start(); do_action( 'wp_footer' ); $f = ob_get_clean();
		$fi = cgs_faq_items_default();
		check( 'AEO: FAQPage schema built from the visible FAQ', false !== strpos( $f, '"@type":"FAQPage"' ) && false !== strpos( $f, wp_json_encode( $fi[0]['q'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) ) );
		$GLOBALS['cgs_faq_seen'] = array(); call_user_func( $GLOBALS['SC']['cgs_fwr_faq'], array(), '', 'cgs_fwr_faq' ); ob_start(); do_action( 'wp_footer' ); $f = ob_get_clean();
		$fw = cgs_fwr_faq_items_default();
		check( 'AEO: recruitment FAQ also gets FAQPage schema', false !== strpos( $f, '"@type":"FAQPage"' ) && false !== strpos( $f, wp_json_encode( $fw[0]['q'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) ) );
		}
		$pt = apply_filters( 'wp_sitemaps_post_types', array( 'post' => 1, 'page' => 1, 'liquid-header' => 1, 'liquid-footer' => 1 ) );
		check( 'Sitemap: theme header/footer posts removed', ! isset( $pt['liquid-header'] ) && ! isset( $pt['liquid-footer'] ) && isset( $pt['page'] ) );
		check( 'Sitemap: author pages removed', false === apply_filters( 'wp_sitemaps_add_provider', 'provider', 'users' ) && 'p' === apply_filters( 'wp_sitemaps_add_provider', 'p', 'posts' ) );
		$qa = apply_filters( 'wp_sitemaps_posts_query_args', array(), 'ccq_site' );
		$has_sample = function_exists( 'ccq_get' ) && array_filter( get_posts( array( 'post_type' => 'ccq_site', 'post_status' => 'publish', 'numberposts' => 200, 'fields' => 'ids' ) ), function ( $id ) { return ! empty( ccq_get( $id )['sample'] ); } );
		check( 'Sitemap: sample CLQ sites excluded', ! $has_sample || ! empty( $qa['post__not_in'] ) );
		$GLOBALS['Q'] = array( 'is_singular' => array( 'page' ), 'is_page' => true ); $GLOBALS['CUR'] = $xpage;
		check( 'SEO: Rank Math gets our default when it has none', 'Careers | Concepts Group' === apply_filters( 'rank_math/frontend/title', 'Careers - Concepts Group' ) );
		update_post_meta( $xpage, 'rank_math_title', 'Set in Rank Math' );
		check( 'SEO: Rank Math own value is kept', 'Set in Rank Math' === apply_filters( 'rank_math/frontend/title', 'Set in Rank Math' ) );
		check( 'SEO: page SEO fields registered for REST/editing', isset( $GLOBALS['REGMETA']['page']['_cgs_seo_title'], $GLOBALS['REGMETA']['page']['_cgs_seo_desc'] ) );
	}
	if ( empty( $GLOBALS['cg_suite_skipped']['clq-sites'] ) ) {
		check( 'CLQ title: "CLQ & Worker Accommodation in Shah Alam | Concepts Group"', 'CLQ & Worker Accommodation in Shah Alam | Concepts Group' === ccq_page_title( 'Shah Alam' ) );
		check( 'CLQ title long area shortened to <= 62 chars', 'CLQ & Worker Hostel in Telok Panglima Garang | Concepts Group' === ccq_page_title( 'Telok Panglima Garang' ) );
		$r0 = ccq_get( $site[0] ); $r0['sample'] = '1';
		check( 'CLQ description: no unverified bed figures while sample', ! preg_match( '/\d[\d,]* beds/', ccq_description( $r0 ) ) && false !== strpos( ccq_description( $r0 ), 'CLQ' ) );
		$hf = function_exists( 'ccq_hub_faq' ) ? ccq_hub_faq() : array();
		check( 'CLQ hub FAQ: at least 5 employer questions with answers', count( $hf ) >= 5 && ! array_filter( $hf, function ( $i ) { return empty( $i['q'] ) || empty( $i['a'] ); } ) );
		check( 'CLQ hub FAQ: no unverified figures', ! preg_match( '/\d[\d,]* (beds|days|workers)/', wp_json_encode( $hf ) ) );
		check( 'CLQ hub template shows the FAQ', false !== strpos( file_get_contents( $SUITE . '/modules/clq-sites/templates/hub.php' ), 'ccq_hub_faq()' ) );
		check( 'Wave after an FAQ section matches the FAQ background', false !== strpos( file_get_contents( $SUITE . '/modules/sections/concepts-sections.php' ), '.vc_row:has(.cgs-fq)+.vc_row .cgs-ct-wv:not(.a){--c:#eaf4ff!important}' ) );
		check( 'CLQ hub H1 default: Centralised Labour Quarters in Klang Valley', 'Centralised Labour Quarters in Klang Valley' === ccq_hub()['title'] );
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
