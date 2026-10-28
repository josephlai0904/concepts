<?php
/**
 * The property page body. $r is the site record from ccq_get().
 * Sections follow spec section 3: hero, layout, why, inclusions, surroundings,
 * explore, contact, map, FAQ. Every figure comes from $r.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hub     = ccq_hub_url();
$photos  = ccq_media_list( $r['photos'], '1536x1536' );
$plans   = ccq_media_list( $r['plans'], 'full' );
$near    = ccq_nearby( $r );
$beds    = ccq_beds_label( $r );
$checked = ccq_date( $r['last_checked'] );
$wa      = ccq_wa_link( $r['whatsapp'], 'Hello, I would like to ask about worker accommodation at ' . $r['site_name'] . '.' );
$years   = (int) gmdate( 'Y' ) - CCQ_FOUNDED;
$first_estate = $near['estate'] ? $near['estate'][0] : null;

$tabs = array( 'room' => 'In Room', 'communal' => 'Communal Facilities', 'services' => 'Value-added Services' );
$inc  = array( 'room' => array(), 'communal' => array(), 'services' => array() );
foreach ( ccq_rows( $r['inclusions'], 'inclusions' ) as $c ) {
	$t = strtolower( $c[0] );
	if ( isset( $inc[ $t ] ) ) {
		$inc[ $t ][] = $c;
	}
}
/* Rate basis row is built from the rate fields so the price lives in one place. */
$rate = array();
if ( $r['rate_per_bed'] ) {
	$rate[] = $r['rate_per_bed'] . ' per bed per month';
}
if ( $r['min_booking'] ) {
	$rate[] = 'minimum ' . $r['min_booking'];
}
if ( $r['deposit_months'] ) {
	$rate[] = $r['deposit_months'] . ' months\' deposit';
}
if ( $r['term'] ) {
	$rate[] = $r['term'] . ' term';
}
$rate_text = $rate ? ucfirst( implode( ', ', $rate ) ) . '.' : ( $r['pricing_factors'] ? 'Price depends on: ' . rtrim( $r['pricing_factors'], '.' ) . '.' : '' );

$faq = ccq_rows( $r['faq'], 'faq' );
if ( empty( $r['sample'] ) && $faq ) {
	/* Verified sites only: FAQPage structured data (printed by the SEO module). */
	foreach ( $faq as $f ) {
		$GLOBALS['cgs_faq_seen'][] = array( 'q' => $f[0], 'a' => $f[1] );
	}
}
$others = ccq_sites( $r['id'] );

/* Surroundings cards: the Nearby places list, or estates / expressways / daily needs as a fallback.
 * Each card: title, distance line, text, image list. */
$cards = array();
foreach ( ccq_rows( $r['places'], 'places' ) as $c ) {
	$cards[] = array( $c[1], $c[2], $c[3], ccq_media_list( $c[0], 'large' ) );
}
/* Fallback groups */
$surr = array(
	array( 'Industrial estates', $near['estate'], ccq_media_list( $r['img_estate'] ) ),
	array( 'Expressways', $near['expressway'], ccq_media_list( $r['img_road'] ) ),
	array( 'Daily needs', $near['daily'], ccq_media_list( $r['img_daily'] ) ),
);

$why = array(
	array( 'Since ' . CCQ_FOUNDED, 'Operating since ' . CCQ_FOUNDED, $r['proof_client'] ? $r['proof_client'] : 'Concepts Group has provided manpower and worker accommodation in Malaysia since ' . CCQ_FOUNDED . '. Ask the site team for a client reference.', 'ring' ),
	array( 'Licensed and checkable', 'Licensed and checkable', '', 'cert' ),
	array( 'Comfy and reliable', 'Comfortable, reliable accommodation', 'Clean, well-kept rooms and shared facilities, looked after by a site team that handles maintenance and day-to-day issues, so your workers can rest well and stay focused at work.', 'contact' ),
	array( 'Facilities you can see', 'Facilities you can see first', 'Real photographs of this site\'s rooms and facilities are on this page. Book a visit to walk the site before you commit.', 'rooms' ),
);

$why_imgs = ccq_media_list( $r['why_images'], 'large' );
$arrow_l  = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19 12H5M11 6l-6 6 6 6" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
$arrow_r  = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
$slides   = array_slice( $photos, 0, 9 );
?>
<div id="ccq-page" class="ccq" data-site="<?php echo (int) $r['id']; ?>" data-area="<?php echo esc_attr( $r['area'] ); ?>">

<!-- 1. HERO -->
<section class="ccq-hero" aria-labelledby="ccq-h1">
	<div class="ccq-hero-txt">
		<nav class="ccq-bc" aria-label="Breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span aria-hidden="true">›</span><a href="<?php echo esc_url( $hub ); ?>">Centralised Labour Quarters</a><span aria-hidden="true">›</span><span aria-current="page"><?php echo esc_html( $r['area'] ); ?></span></nav>
		<h1 id="ccq-h1" class="ccq-h1"><?php echo esc_html( $r['area'] . ' workers accommodation' ); ?><br><?php echo esc_html( 'and hostel' ); ?></h1>
		<p class="ccq-lead"><?php echo esc_html( $r['fit'] ? $r['fit'] : $r['story'] ); ?></p>
		<a class="ccq-btn" href="#ccq-contact" data-ccq-ev="quote_click">Talk to a specialist</a>
	</div>
	<div class="ccq-hero-vis">
		<div class="ccq-slides" aria-roledescription="carousel" aria-label="<?php echo esc_attr( $r['site_name'] . ' photos' ); ?>">
			<?php if ( $slides ) : foreach ( $slides as $i => $p ) : ?>
				<img class="ccq-slide<?php echo 0 === $i ? ' on' : ''; ?>" src="<?php echo esc_url( $p['src'] ); ?>" alt="<?php echo esc_attr( $p['alt'] ? $p['alt'] : $r['site_name'] . ' photo ' . ( $i + 1 ) ); ?>"<?php echo $p['w'] ? ' width="' . (int) $p['w'] . '" height="' . (int) $p['h'] . '"' : ''; ?> <?php echo 0 === $i ? 'fetchpriority="high"' : 'loading="lazy"'; ?> decoding="async">
			<?php endforeach; else : ?>
				<div class="ccq-slide on ccq-ph" role="img" aria-label="Photo to come"><span>Photo to come</span></div>
			<?php endif; ?>
		</div>
		<div class="ccq-count">
			<?php if ( count( $slides ) > 1 ) : ?>
			<button type="button" class="ccq-prev" aria-label="Previous photo"><?php echo $arrow_l; // Static SVG. ?></button>
			<button type="button" class="ccq-next" aria-label="Next photo"><?php echo $arrow_r; // Static SVG. ?></button>
			<?php endif; ?>
			<span class="ccq-num" aria-live="polite"><b class="ccq-cur">01</b><span> / <?php echo esc_html( sprintf( '%02d', max( 1, count( $slides ) ) ) ); ?></span></span>
		</div>
	</div>
</section>

<!-- 2. LAYOUT, STORY, INFO -->
<section class="ccq-sec ccq-plan" aria-labelledby="ccq-plan-h">
	<div class="ccq-wrap ccq-plan-in">
		<div class="ccq-plans<?php echo count( $plans ) === 1 ? ' one' : ''; ?>">
			<?php
			$plan_caps = array( 'Typical unit', 'Block layout' );
			$show      = $plans ? array_slice( $plans, 0, 2 ) : array( null, null );
			foreach ( $show as $i => $p ) : ?>
				<figure class="ccq-planfig">
					<figcaption><?php echo esc_html( $p && $p['cap'] ? $p['cap'] : ( 1 === count( $show ) ? 'Floor plan' : $plan_caps[ $i ] ) ); ?></figcaption>
					<?php if ( $p ) : ?>
						<img src="<?php echo esc_url( $p['src'] ); ?>" alt="<?php echo esc_attr( $p['alt'] ? $p['alt'] : 'Floor plan, ' . $r['site_name'] ); ?>"<?php echo $p['w'] ? ' width="' . (int) $p['w'] . '" height="' . (int) $p['h'] . '"' : ''; ?> loading="lazy" decoding="async">
					<?php else : ?>
						<div class="ccq-ph ccq-ph-plan" role="img" aria-label="Floor plan to come"><span>Floor plan to come</span></div>
					<?php endif; ?>
				</figure>
			<?php endforeach; ?>
		</div>
		<div class="ccq-story">
			<h2 id="ccq-plan-h" class="ccq-h2">Layout and story</h2>
			<p class="ccq-p"><?php echo esc_html( $r['story'] ); ?></p>
			<dl class="ccq-facts">
				<div><dt>Bed capacity</dt><dd><?php echo $beds ? esc_html( $beds ) : ccq_tbc( '' ); ?></dd></div>
				<div><dt>Land area</dt><dd><?php echo ccq_tbc( ccq_num( $r['land_area_sqm'] ), ' sqm' ); ?></dd></div>
				<div><dt>Gross floor area</dt><dd><?php echo ccq_tbc( ccq_num( $r['gfa_sqm'] ), ' sqm' ); ?></dd></div>
				<div><dt>Beds per room</dt><dd><?php echo ccq_tbc( $r['beds_per_room'] ); ?></dd></div>
			</dl>
			<a class="ccq-btn" href="#ccq-contact" data-ccq-ev="visit_click">Request a site visit</a>
		</div>
	</div>
</section>

<!-- 3. WHY CONCEPTS: photo panels -->
<section class="ccq-sec ccq-why" aria-labelledby="ccq-why-h">
	<div class="ccq-wrap">
		<div class="ccq-why-head">
			<h2 id="ccq-why-h" class="ccq-why-intro">Licensed worker accommodation since <?php echo (int) CCQ_FOUNDED; ?>, with a named team on site</h2>
			<div class="ccq-arrows"><button type="button" class="ccq-wprev" aria-label="Previous"><?php echo $arrow_l; // Static SVG. ?></button><button type="button" class="ccq-wnext" aria-label="Next"><?php echo $arrow_r; // Static SVG. ?></button></div>
		</div>
		<div class="ccq-panels">
			<?php foreach ( $why as $i => $w ) :
				$open = 0 === $i;
				$bg   = isset( $why_imgs[ $i ] ) ? $why_imgs[ $i ]['src'] : ( isset( $photos[ $i ] ) ? $photos[ $i ]['src'] : '' ); ?>
			<div class="ccq-panel<?php echo $open ? ' on' : ''; ?>"<?php echo $bg ? ' style="--bg:url(\'' . esc_url( $bg ) . '\')"' : ''; ?>>
				<button type="button" class="ccq-panel-b" aria-expanded="<?php echo $open ? 'true' : 'false'; ?>" aria-controls="ccq-pn<?php echo (int) $i; ?>">
					<span class="ccq-panel-n"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
					<span class="ccq-panel-l"><?php echo esc_html( $w[0] ); ?></span>
				</button>
				<div class="ccq-panel-c" id="ccq-pn<?php echo (int) $i; ?>">
					<div class="ccq-panel-t">
						<h3 class="ccq-h3"><?php echo esc_html( $w[1] ); ?></h3>
						<?php if ( 'ring' === $w[3] ) : ?>
							<p><?php echo esc_html( $w[2] ); ?></p>
						<?php elseif ( 'cert' === $w[3] ) : ?>
							<?php if ( $r['certificate_publish'] && $r['certificate_no'] ) : ?>
								<p>Accommodation certificate <?php echo esc_html( $r['certificate_no'] ); ?><?php echo $r['certificate_valid_until'] ? esc_html( ', valid until ' . ccq_date( $r['certificate_valid_until'] ) ) : ''; ?>.<?php if ( $r['certificate_url'] ) : ?> <a href="<?php echo esc_url( $r['certificate_url'] ); ?>" target="_blank" rel="noopener">Check the record</a><?php endif; ?></p>
							<?php else : ?>
								<p>Ask the site team for the certificate number, validity date and the authority's reference.</p>
							<?php endif; ?>
						<?php elseif ( 'contact' === $w[3] ) : ?>
							<p><?php echo esc_html( $w[2] ); ?></p>
							<?php if ( $r['contact_name'] ) : ?><p><?php echo esc_html( trim( 'Site contact: ' . $r['contact_name'] . ( $r['visiting_hours'] ? ', ' . $r['visiting_hours'] : '' ) . '.' ) ); ?></p><?php endif; ?>
						<?php else : ?>
							<p><?php echo esc_html( $w[2] ); ?><?php if ( $r['video_url'] ) : ?> <a href="<?php echo esc_url( $r['video_url'] ); ?>" target="_blank" rel="noopener">Watch the walkthrough</a><?php endif; ?></p>
						<?php endif; ?>
					</div>
					<?php if ( 'ring' === $w[3] ) : ?>
					<div class="ccq-ring" aria-label="<?php echo esc_attr( $years . ' years' ); ?>"><svg viewBox="0 0 100 100" aria-hidden="true"><circle cx="50" cy="50" r="46"/><circle class="arc" cx="50" cy="50" r="46" pathLength="100"/></svg><b><?php echo (int) $years; ?></b></div>
					<?php endif; ?>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- 4. RENT INCLUSIONS (the reference's list rows) -->
<section class="ccq-sec ccq-inc" aria-labelledby="ccq-inc-h">
	<div class="ccq-wrap">
		<div class="ccq-row-head">
			<h2 id="ccq-inc-h" class="ccq-h2">Rent inclusions</h2>
			<div class="ccq-tabs" role="tablist" aria-label="Rent inclusions">
				<?php $k = 0; foreach ( $tabs as $slug => $label ) : ?>
				<button type="button" role="tab" id="ccq-tab-<?php echo esc_attr( $slug ); ?>" aria-controls="ccq-tp-<?php echo esc_attr( $slug ); ?>" aria-selected="<?php echo 0 === $k ? 'true' : 'false'; ?>" tabindex="<?php echo 0 === $k ? '0' : '-1'; ?>"><?php echo esc_html( $label ); ?></button>
				<?php $k++; endforeach; ?>
			</div>
		</div>
		<div class="ccq-dots">
			<?php $k = 0; foreach ( $tabs as $slug => $label ) : ?>
			<div class="ccq-tp" role="tabpanel" id="ccq-tp-<?php echo esc_attr( $slug ); ?>" aria-labelledby="ccq-tab-<?php echo esc_attr( $slug ); ?>"<?php echo 0 === $k ? '' : ' hidden'; ?>>
				<?php if ( ! $inc[ $slug ] ) : ?><p class="ccq-empty">Details to confirm with the site team.</p><?php endif; ?>
				<ol class="ccq-rows">
					<?php foreach ( $inc[ $slug ] as $n => $c ) :
						$status = strtoupper( $c[3] );
						$status = in_array( $status, array( 'INCLUDED', 'TO CONFIRM', 'EXTRA', 'TERMS' ), true ) ? $status : 'TO CONFIRM';
						$item   = ( 'rate basis' === strtolower( $c[1] ) && $rate_text ) ? $rate_text : $c[2]; ?>
					<li class="ccq-row"><span class="ccq-row-n"><?php echo esc_html( sprintf( '%02d', $n + 1 ) ); ?></span><span class="ccq-row-l"><?php echo esc_html( $c[1] ); ?></span><span class="ccq-row-i"><?php echo esc_html( $item ); ?></span><span class="ccq-st"><?php echo esc_html( $status ); ?></span></li>
					<?php endforeach; ?>
				</ol>
			</div>
			<?php $k++; endforeach; ?>
		</div>
		<div class="ccq-center"><a class="ccq-btn" href="#ccq-contact" data-ccq-ev="quote_click">Talk to a specialist</a></div>
	</div>
</section>

<!-- 5. SURROUNDINGS (the reference's projects strip) -->
<section class="ccq-sec ccq-surr" aria-labelledby="ccq-surr-h">
	<div class="ccq-wrap">
		<div class="ccq-row-head">
			<div class="ccq-title"><h2 id="ccq-surr-h" class="ccq-h2">Surroundings</h2><?php if ( $r['route_basis'] ) : ?><p class="ccq-p"><?php echo esc_html( $r['route_basis'] ); ?></p><?php endif; ?></div>
			<div class="ccq-arrows"><button type="button" class="ccq-sprev" aria-label="Previous place"><?php echo $arrow_l; // Static SVG. ?></button><button type="button" class="ccq-snext" aria-label="Next place"><?php echo $arrow_r; // Static SVG. ?></button></div>
		</div>
		<div class="ccq-car" aria-label="Nearby places">
			<?php
			if ( ! $cards ) {
				foreach ( $surr as $s ) {
					$lines = array();
					foreach ( array_slice( $s[1], 0, 3 ) as $x ) {
						$lines[] = $x['name'] . ' · ' . $x['min'] . ' min';
					}
					$cards[] = array( $s[0], '', $lines ? implode( "\n", $lines ) : 'Drive times to confirm', $s[2] );
				}
			}
			foreach ( $cards as $i => $c ) :
				$pos = $i < 3 ? array( 'l', 'm', 'r' )[ $i ] : 'x'; ?>
			<div class="ccq-car-i" data-pos="<?php echo esc_attr( $pos ); ?>" tabindex="<?php echo 'x' === $pos ? '-1' : '0'; ?>" role="button" aria-label="<?php echo esc_attr( 'Show ' . $c[0] ); ?>">
				<div class="ccq-car-ph">
					<?php if ( $c[3] ) : ?><img src="<?php echo esc_url( $c[3][0]['src'] ); ?>" alt="<?php echo esc_attr( $c[3][0]['alt'] ? $c[3][0]['alt'] : $c[0] ); ?>" loading="lazy" decoding="async"><?php else : ?><span class="ccq-ph"></span><?php endif; ?>
				</div>
				<h3 class="ccq-car-t"><?php echo esc_html( $c[0] ); ?></h3>
				<?php if ( $c[1] ) : ?><p class="ccq-car-m"><?php echo esc_html( $c[1] ); ?></p><?php endif; ?>
				<p class="ccq-car-d"><?php echo nl2br( esc_html( $c[2] ) ); ?></p>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- 6. EXPLORE OTHER PROPERTIES -->
<?php ccq_explore( $others, true ); ?>

<!-- 7. CONTACT -->
<section class="ccq-sec ccq-contact" id="ccq-contact" aria-labelledby="ccq-contact-h">
	<div class="ccq-wrap ccq-contact-in">
		<div class="ccq-contact-l">
			<h2 id="ccq-contact-h" class="ccq-xl">Contact</h2>
			<p class="ccq-strong">Still have questions?</p>
			<p class="ccq-p">Talk to the <?php echo esc_html( $r['area'] ); ?> site team on WhatsApp.</p>
			<?php if ( $wa ) : ?><a class="ccq-chip" href="<?php echo esc_url( $wa ); ?>" target="_blank" rel="noopener" data-ccq-ev="whatsapp_click">Let's chat</a><?php endif; ?>
		</div>
		<address class="ccq-black">
			<div class="ccq-black-top">
				<span>Contact</span>
				<?php if ( $r['phone'] ) : ?><a href="<?php echo esc_attr( ccq_tel( $r['phone'] ) ); ?>" data-ccq-ev="phone_click"><?php echo esc_html( $r['phone'] ); ?></a><?php endif; ?>
				<?php if ( $r['email'] ) : ?><a class="ccq-black-big" href="mailto:<?php echo esc_attr( $r['email'] ); ?>" data-ccq-ev="email_click"><?php echo esc_html( $r['email'] ); ?></a><?php endif; ?>
			</div>
			<div class="ccq-black-bot">
				<div><span>WhatsApp</span><?php if ( $wa ) : ?><a href="<?php echo esc_url( $wa ); ?>" target="_blank" rel="noopener" data-ccq-ev="whatsapp_click"><?php echo esc_html( $r['whatsapp'] ); ?></a><?php endif; ?><?php if ( $r['visiting_hours'] ) : ?><em><?php echo esc_html( $r['visiting_hours'] ); ?></em><?php endif; ?></div>
				<div><span>Address</span><em><?php echo $r['address'] ? nl2br( esc_html( $r['address'] . ( $r['postcode'] ? ', ' . $r['postcode'] : '' ) ) ) : 'To confirm'; ?></em></div>
			</div>
		</address>
		<div class="ccq-form-wrap">
			<form class="ccq-form" novalidate>
				<input type="hidden" name="site" value="<?php echo (int) $r['id']; ?>">
				<p class="ccq-hp" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></p>
				<p class="ccq-f"><label for="ccq-company">Company</label><input id="ccq-company" name="company" type="text" autocomplete="organization" required placeholder="Company name" aria-describedby="ccq-company-e"><span class="ccq-err" id="ccq-company-e"></span></p>
				<p class="ccq-f"><label for="ccq-workers">Number of workers</label><input id="ccq-workers" name="workers" type="number" min="1" inputmode="numeric" required placeholder="e.g. 40" aria-describedby="ccq-workers-e"><span class="ccq-err" id="ccq-workers-e"></span></p>
				<p class="ccq-f"><label for="ccq-email">Email</label><input id="ccq-email" name="email" type="email" autocomplete="email" required placeholder="you@company.com" aria-describedby="ccq-email-e"><span class="ccq-err" id="ccq-email-e"></span></p>
				<p class="ccq-f"><label for="ccq-phone">Phone or WhatsApp</label><input id="ccq-phone" name="phone" type="tel" autocomplete="tel" placeholder="Optional"></p>
				<p class="ccq-f"><label for="ccq-message">Message</label><textarea id="ccq-message" name="message" rows="3" placeholder="Preferred move-in date" aria-describedby="ccq-message-e"></textarea><span class="ccq-err" id="ccq-message-e"></span></p>
				<p class="ccq-err ccq-err-form" role="alert"></p>
				<button type="submit" class="ccq-send">Send message</button>
				<p class="ccq-terms">By submitting this form you agree to our <a href="<?php echo esc_url( home_url( '/terms-and-conditions/' ) ); ?>">Terms and Conditions</a>.</p>
			</form>
			<div class="ccq-ok" hidden tabindex="-1"><p class="ccq-strong">Thank you. Your message is with the <?php echo esc_html( $r['area'] ); ?> site team.</p><p class="ccq-p">We have emailed you a copy. For anything urgent, use WhatsApp.</p></div>
		</div>
	</div>
</section>

<!-- 8. MAP (greyscale) -->
<section class="ccq-map" id="ccq-map" aria-label="<?php echo esc_attr( $r['site_name'] . ' location map' ); ?>"<?php if ( $r['lat'] && $r['lng'] ) : ?> data-lat="<?php echo esc_attr( $r['lat'] ); ?>" data-lng="<?php echo esc_attr( $r['lng'] ); ?>"<?php endif; ?>>
	<div class="ccq-map-card">
		<strong><?php echo esc_html( $r['site_name'] ); ?></strong>
		<span><?php echo $r['address'] ? esc_html( preg_replace( '/\s*\n\s*/', ', ', $r['address'] ) ) : 'Address to confirm'; ?></span>
		<?php if ( $r['lat'] && $r['lng'] ) : ?><a href="<?php echo esc_url( 'https://www.google.com/maps/dir/?api=1&destination=' . rawurlencode( $r['lat'] . ',' . $r['lng'] ) ); ?>" target="_blank" rel="noopener" data-ccq-ev="directions_click">Get directions</a><?php endif; ?>
	</div>
	<span class="ccq-pin" aria-hidden="true"></span>
</section>

<!-- 9. FAQ -->
<section class="ccq-sec ccq-faq" aria-labelledby="ccq-faq-h">
	<div class="ccq-wrap ccq-faq-in">
		<div class="ccq-faq-l">
			<h2 id="ccq-faq-h" class="ccq-xl">Faq</h2>
			<p class="ccq-p">Direct answers for employers, using this property's figures.</p>
			<div class="ccq-faq-box">
				<p><strong>Have a question?</strong><br>Not listed here? Ask the site team directly.</p>
				<a class="ccq-pill" href="#ccq-contact">Let's talk</a>
			</div>
		</div>
		<div class="ccq-acc">
			<?php foreach ( $faq as $i => $f ) : ?>
			<details name="ccq-faq"<?php echo 0 === $i ? ' open' : ''; ?>>
				<summary><h3 class="ccq-q"><?php echo esc_html( $f[0] ); ?></h3><i aria-hidden="true"></i></summary>
				<p><?php echo esc_html( $f[1] ); ?></p>
			</details>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- Mobile action bar -->
<div class="ccq-bar">
	<?php if ( $wa ) : ?><a href="<?php echo esc_url( $wa ); ?>" target="_blank" rel="noopener" data-ccq-ev="whatsapp_click">WhatsApp</a><?php endif; ?>
	<?php if ( $r['phone'] ) : ?><a href="<?php echo esc_attr( ccq_tel( $r['phone'] ) ); ?>" data-ccq-ev="phone_click">Call</a><?php endif; ?>
</div>

</div>
