<?php
/**
 * Concepts Group Suite module: CLQ Sites (property pages, hub, enquiries).
 * Loaded by ../../concepts-group-suite.php. Not a standalone plugin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CCQ_VERSION', '1.13.1' );
define( 'CCQ_DIR', plugin_dir_path( __FILE__ ) );
define( 'CCQ_URL', plugin_dir_url( __FILE__ ) );
define( 'CCQ_FOUNDED', 1991 );

/* ------------------------------------------------------------------
 * 1. FIELDS — the site record (spec section 8). One list drives the
 *    admin editor, sanitising and the page. Edit labels here.
 *    Format: key => array( type, label, hint | options | columns ).
 *    Types: text, textarea, number, date, select, checkbox, image,
 *    gallery, map, repeater.
 * ---------------------------------------------------------------- */
function ccq_fields() {
	return array(
		'Identity'            => array(
			'sample'        => array( 'checkbox', 'Sample content: hide this page from Google until the figures are verified. Untick once real figures are in.', '' ),
			'area'          => array( 'text', 'Area name (breadcrumb, H1, title)', 'e.g. Shah Alam' ),
			'operator_name' => array( 'text', 'Operator (legal entity)', 'e.g. Concepts ATC Sdn Bhd' ),
			'region'        => array( 'text', 'Region', 'Klang Valley' ),
			'status'        => array( 'select', 'Status', array( 'operating' => 'Operating', 'in_development' => 'In development', 'transitioning' => 'Transitioning' ) ),
			'fit'           => array( 'text', 'One-line fit (hero)', 'e.g. Purpose-built CLQ for manufacturers in Shah Alam\'s industrial estates.' ),
			'story'         => array( 'textarea', 'Layout and story: who the site suits (2–3 sentences)', '' ),
		),
		'Photos and plans'    => array(
			'photos'     => array( 'gallery', 'Hero slider (first 3: exterior, four-person room, canteen). Drag to reorder. Alt text comes from the Media Library.', '' ),
			'plans'      => array( 'gallery', 'Floor plans (2: typical unit, block layout). Caption comes from the Media Library.', '' ),
			'img_estate' => array( 'image', 'Surroundings: industrial estates image', '' ),
			'img_road'   => array( 'image', 'Surroundings: expressways image', '' ),
			'img_daily'  => array( 'image', 'Surroundings: daily needs image', '' ),
			'video_url'  => array( 'text', 'Walkthrough video link (YouTube etc.)', '' ),
		),
		'Location and contact' => array(
			'address'        => array( 'textarea', 'Address (one canonical version, used everywhere)', '' ),
			'postcode'       => array( 'text', 'Postcode', '' ),
			'city'           => array( 'text', 'City', '' ),
			'state'          => array( 'text', 'State', 'Selangor' ),
			'map'            => array( 'map', 'Map pin: click the map or drag the pin', '' ),
			'phone'          => array( 'text', 'Phone', '+603-3318 7773' ),
			'whatsapp'       => array( 'text', 'WhatsApp number', '+60 17-887 3361' ),
			'email'          => array( 'text', 'Site team email (the form is sent here)', '' ),
			'visiting_hours' => array( 'text', 'Visiting hours', 'e.g. Mon–Sat, 9am–5pm' ),
			'contact_name'   => array( 'text', 'Named site contact', '' ),
			'response_time'  => array( 'text', 'Response time', 'e.g. Replies within 1 working day' ),
		),
		'Key facts'           => array(
			'bed_capacity'    => array( 'number', 'Bed capacity', '' ),
			'bed_basis'       => array( 'select', 'Bed figure means', array( 'licensed' => 'Licensed capacity', 'current' => 'Current capacity', 'target' => 'Development target' ) ),
			'target_date'     => array( 'text', 'Target date (development sites)', 'e.g. Q3 2027' ),
			'land_area_sqm'   => array( 'number', 'Land area (sqm)', '' ),
			'gfa_sqm'         => array( 'number', 'Gross floor area (sqm)', '' ),
			'beds_per_room'   => array( 'text', 'Beds per room', 'e.g. 4' ),
			'rooms_per_block' => array( 'number', 'Rooms per block', '' ),
			'blocks'          => array( 'number', 'Blocks', '' ),
			'available_from'  => array( 'date', 'Available from', '' ),
			'last_checked'    => array( 'date', 'Figures last checked (shown in the hero)', '' ),
			'checked_by'      => array( 'text', 'Checked by (person responsible)', '' ),
		),
		'Why Concepts'        => array(
			'why_images'              => array( 'gallery', 'Panel background photos, in panel order (01 to 04). Drag to reorder.', '' ),
			'proof_client'            => array( 'textarea', 'Panel 01: one real client example (headcount, years served)', '' ),
			'certificate_no'          => array( 'text', 'Panel 02: accommodation certificate no.', '' ),
			'certificate_valid_until' => array( 'date', 'Certificate valid until', '' ),
			'certificate_url'         => array( 'text', 'Link to the authority\'s record', 'https://jtksm.mohr.gov.my/…' ),
			'certificate_publish'     => array( 'checkbox', 'Permitted to publish the certificate details', '' ),
		),
		'Rent inclusions'     => array(
			'inclusions'      => array( 'repeater', 'One row per item. A row labelled "Rate basis" is filled from the rate fields below.', array(
				'tab'    => array( 'select', 'Tab', array( 'room' => 'In Room', 'communal' => 'Communal Facilities', 'services' => 'Value-added Services' ) ),
				'label'  => array( 'text', 'Label', 'Bed' ),
				'item'   => array( 'text', 'What it covers', 'Bed frame and mattress for each worker' ),
				'status' => array( 'select', 'Status', array( 'INCLUDED' => 'Included', 'TO CONFIRM' => 'To confirm', 'EXTRA' => 'Extra', 'TERMS' => 'Terms' ) ),
			) ),
			'rate_per_bed'    => array( 'text', 'Price per bed per month (blank = not published)', 'e.g. RM 250' ),
			'min_booking'     => array( 'text', 'Minimum booking', 'e.g. 20 beds' ),
			'deposit_months'  => array( 'text', 'Deposit (months)', 'e.g. 2' ),
			'term'            => array( 'text', 'Contract term', 'e.g. 12 months' ),
			'pricing_factors' => array( 'textarea', 'Pricing factors (shown when no price is published)', 'Number of beds, room type, contract length and services added.' ),
		),
		'Surroundings'        => array(
			'places'      => array( 'repeater', 'Nearby places shown on the page (photo, title, distance, short text). Drag to reorder; three show at a time and they rotate.', array(
				'image' => array( 'image', 'Photo', '' ),
				'title' => array( 'text', 'Title', 'LRT station nearby' ),
				'meta'  => array( 'text', 'Distance', 'e.g. 5 min by car' ),
				'text'  => array( 'textarea', 'Short text', '' ),
			) ),
			'nearby'      => array( 'repeater', 'Industrial estates and roads with drive times. Used for the Google description, and on the page only when Nearby places is empty.', array(
				'type' => array( 'select', 'Type', array( 'estate' => 'Industrial estate', 'expressway' => 'Expressway', 'daily' => 'Daily needs' ) ),
				'name' => array( 'text', 'Name', 'Seksyen 26 industrial area' ),
				'min'  => array( 'number', 'Minutes by car', '5' ),
			) ),
			'route_basis' => array( 'text', 'How drive times were measured (shown once on the page)', 'Drive times by car, off-peak, measured on Google Maps.' ),
		),
		'FAQ'                 => array(
			'faq'          => array( 'repeater', 'Direct answer first, then conditions. The first question opens by default.', array(
				'q' => array( 'text', 'Question', 'How soon can workers move in?' ),
				'a' => array( 'textarea', 'Answer', '' ),
			) ),
			'faq_reviewed' => array( 'date', 'FAQ last reviewed', '' ),
		),
	);
}

/* Flat key => type map ('map' stores lat and lng). */
function ccq_field_types() {
	$out = array();
	foreach ( ccq_fields() as $group ) {
		foreach ( $group as $k => $f ) {
			if ( 'map' === $f[0] ) {
				$out['lat'] = 'coord';
				$out['lng'] = 'coord';
				continue;
			}
			$out[ $k ] = $f[0];
		}
	}
	return $out;
}

/* Repeater column keys, in order, for a field. */
function ccq_repeater_cols( $key ) {
	foreach ( ccq_fields() as $group ) {
		if ( isset( $group[ $key ] ) && 'repeater' === $group[ $key ][0] ) {
			return array_keys( $group[ $key ][2] );
		}
	}
	return array();
}

/* Repeater value -> rows of cells in column order. */
function ccq_rows( $v, $key ) {
	$cols = ccq_repeater_cols( $key );
	$rows = array();
	foreach ( is_array( $v ) ? $v : array() as $row ) {
		$cells = array();
		foreach ( $cols as $c ) {
			$cells[] = isset( $row[ $c ] ) ? trim( (string) $row[ $c ] ) : '';
		}
		if ( '' !== implode( '', $cells ) ) {
			$rows[] = $cells;
		}
	}
	return $rows;
}

/* Read the record for a site post, with defaults filled in. */
function ccq_get( $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return array();
	}
	$raw = get_post_meta( $post->ID, '_ccq', true );
	$raw = is_array( $raw ) ? $raw : array();
	$r   = array();
	foreach ( ccq_field_types() as $k => $t ) {
		$r[ $k ] = isset( $raw[ $k ] ) ? $raw[ $k ] : '';
	}
	$r['id']        = $post->ID;
	$r['site_name'] = get_the_title( $post );
	$r['url']       = get_permalink( $post );
	$r['area']      = '' !== $r['area'] ? $r['area'] : preg_replace( '/^Concepts\s+/i', '', $r['site_name'] );
	$r['region']    = '' !== $r['region'] ? $r['region'] : 'Klang Valley';
	$r['status']    = '' !== $r['status'] ? $r['status'] : 'in_development';
	return $r;
}

/* ------------------------------------------------------------------
 * 2. POST TYPES — public "CLQ Site" (/centralised-labour-quarters/{slug}/, hub at /centralised-labour-quarters/;
 *    old /properties/ links redirect there)
 *    and private "Enquiry" records (the spreadsheet rows).
 * ---------------------------------------------------------------- */
/* Address of the hub and property pages. */
if ( ! defined( 'CCQ_BASE' ) ) {
	define( 'CCQ_BASE', 'centralised-labour-quarters' );
}

/* Old /properties/... links (menus, bookmarks, Google) move permanently to the new address. */
add_action( 'template_redirect', function () {
	$path = trim( (string) wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '', PHP_URL_PATH ), '/' );
	$home = trim( (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH ), '/' );
	if ( '' !== $home && 0 === strpos( $path, $home . '/' ) ) {
		$path = substr( $path, strlen( $home ) + 1 );
	}
	if ( 'properties' === $path || 0 === strpos( $path, 'properties/' ) ) {
		$rest  = trim( substr( $path, strlen( 'properties' ) ), '/' );
		$query = isset( $_SERVER['QUERY_STRING'] ) && '' !== $_SERVER['QUERY_STRING'] ? '?' . wp_unslash( $_SERVER['QUERY_STRING'] ) : '';
		wp_safe_redirect( home_url( '/' . CCQ_BASE . '/' . ( '' !== $rest ? $rest . '/' : '' ) ) . $query, 301 );
		exit;
	}
}, 1 );

add_action( 'init', 'ccq_register_types' );
add_action( 'init', function () {
	if ( get_option( 'ccq_rewrite_ver' ) !== CCQ_VERSION ) {
		flush_rewrite_rules();
		update_option( 'ccq_rewrite_ver', CCQ_VERSION );
	}
}, 20 );
function ccq_register_types() {
	register_post_type( 'ccq_site', array(
		'label'        => 'CLQ Sites',
		'labels'       => array( 'singular_name' => 'CLQ Site', 'add_new_item' => 'Add CLQ site', 'edit_item' => 'Edit CLQ site', 'all_items' => 'All sites' ),
		'public'       => true,
		'has_archive'  => ccq_hub_page_id() ? false : CCQ_BASE, /* An editable page at that address takes over. */
		'rewrite'      => array( 'slug' => CCQ_BASE, 'with_front' => false ),
		'menu_icon'    => 'dashicons-building',
		'menu_position'=> 21,
		'supports'     => array( 'title', 'page-attributes', 'revisions' ),
		'show_in_rest' => true,
	) );
	register_post_type( 'ccq_enquiry', array(
		'label'           => 'Site enquiries',
		'labels'          => array( 'singular_name' => 'Enquiry' ),
		'public'          => false,
		'show_ui'         => true,
		'show_in_menu'    => 'edit.php?post_type=ccq_site',
		'supports'        => array( 'title' ),
		'capability_type' => 'post',
		'capabilities'    => array( 'create_posts' => 'do_not_allow' ),
		'map_meta_cap'    => true,
	) );
}

/* Activation / deactivation are handled by the suite's main file. */

/* Sample "Nearby places" (photos from the Media Library). Replace the text per site. */
function ccq_sample_places( $pics ) {
	return array(
		array( 'image' => $pics['lrt'], 'title' => 'LRT station nearby', 'meta' => '5 min by car', 'text' => 'The nearest LRT station links workers to the Klang Valley rail network for days off, banking and errands.' ),
		array( 'image' => $pics['mart'], 'title' => 'Convenience mart nearby', 'meta' => '3 min walk', 'text' => 'A mini mart for groceries, toiletries and phone top-ups, open late, within walking distance of the site.' ),
		array( 'image' => $pics['mosque'], 'title' => 'Mosque nearby', 'meta' => '5 min walk', 'text' => 'A mosque close by for daily and Friday prayers, in addition to the surau on site.' ),
		array( 'image' => $pics['clinic'], 'title' => '24-hour clinic nearby', 'meta' => '6 min by car', 'text' => 'A 24-hour clinic for walk-in treatment and after-hours emergencies. The site team can arrange transport.' ),
	);
}

/* Seed the four sites as DRAFTS with dummy content (spec worked example), all flagged as sample.
 * Images: the Media Library ID when the file is already uploaded, else its URL. */
function ccq_seed_sites() {
	if ( get_posts( array( 'post_type' => 'ccq_site', 'post_status' => 'any', 'numberposts' => 1, 'fields' => 'ids' ) ) ) {
		return;
	}
	$up  = 'https://concepts.manyproject.my/wp-content/uploads/2026/10/';
	$img = function ( $file ) use ( $up ) {
		$url = $up . $file;
		$id  = attachment_url_to_postid( $url );
		return $id ? (string) $id : $url;
	};
	$pics = array(
		'ext'   => $img( 'Concept-Group-HostelBgImg1.webp' ),
		'bunk'  => $img( 'Rich-blue-blankets-on-hostel-bunks.webp' ),
		'campus'=> $img( 'Mint-and-Purple-Hostel-Campus.webp' ),
		'bath'  => $img( 'Clean-Shared-Hostel-Bathroom-Interior.webp' ),
		'rev'   => $img( 'Reverse-view-of-the-hostel-bunk-room.webp' ),
		'plan'  => $img( 'modern-3-floor-office-building-floor-plan-layout-6x15m-with-reception-staff-area-meeting-room-copy-1.webp' ),
		'lrt'   => $img( '1mj2a12000rnh078y9880_Z_640_10000_R5.png_.webp' ),
		'mart'  => $img( '81a37333-kk-mart-2-fb-pic-18324.webp' ),
		'mosque'=> $img( 'ara-damansara-mosque-selangor-malaysia-a-june-modern-design-green-technology-concept-was-awarded-60276092-1.webp' ),
		'clinic'=> $img( 'images-1.jpeg' ),
		'woke'  => $img( 'foreign-workers-just-woke-up.webp' ),
		'woke2' => $img( 'foreign-workers-happy-at-just-woke-up.webp' ),
		'home'  => $img( 'foreign-workers-happy-at-home.webp' ),
		'home2' => $img( 'foreign-workers-happy-at-home2.webp' ),
		'work'  => $img( 'foreign-workers-happy-at-work.webp' ),
		'farm'  => $img( 'foreign-workers-happy-at-farm.webp' ),
		'farm2' => $img( 'foreign-workers-happy-at-farm2.webp' ),
		'farm3' => $img( 'foreign-workers-happy-at-farm3.webp' ),
		'farm4' => $img( 'foreign-workers-happy-at-farm4.webp' ),
	);
	$inc = function ( $rate_status ) {
		$rows = array(
			array( 'room', 'Bed', 'Single bed frame, mattress and pillow for each worker', 'INCLUDED' ),
			array( 'room', 'Storage', 'Personal lockable locker per bed', 'INCLUDED' ),
			array( 'room', 'Bathroom', 'Shared bathroom inside each unit, one per 8 workers', 'INCLUDED' ),
			array( 'room', 'Power', 'Lighting, ceiling fans and power points at each bed', 'INCLUDED' ),
			array( 'room', 'Utilities', 'Electricity and water up to a fair-use limit', 'TERMS' ),
			array( 'communal', 'Kitchen', 'Shared cooking area with gas stoves, per floor', 'INCLUDED' ),
			array( 'communal', 'Living', 'Common room with TV and seating', 'INCLUDED' ),
			array( 'communal', 'Laundry', 'Washing area and drying yard', 'INCLUDED' ),
			array( 'communal', 'Prayer', 'Surau on site', 'INCLUDED' ),
			array( 'communal', 'Canteen', 'Canteen run by an appointed operator; meals paid by workers', 'EXTRA' ),
			array( 'services', 'Security', '24-hour guard, access card and CCTV', 'INCLUDED' ),
			array( 'services', 'Cleaning', 'Daily cleaning of common areas', 'INCLUDED' ),
			array( 'services', 'Maintenance', 'Repairs and 24-hour emergency call-out', 'INCLUDED' ),
			array( 'services', 'Transport', 'Bus to your factory, priced by route and shift', 'EXTRA' ),
			array( 'services', 'Rate basis', 'Price per bed per month, minimum booking and deposit', $rate_status ),
		);
		return array_map( function ( $r ) {
			return array( 'tab' => $r[0], 'label' => $r[1], 'item' => $r[2], 'status' => $r[3] );
		}, $rows );
	};
	$near = function ( $list ) {
		return array_map( function ( $r ) {
			return array( 'type' => $r[0], 'name' => $r[1], 'min' => (string) $r[2] );
		}, $list );
	};
	$faq = function ( $area, $days ) {
		return array(
			array( 'q' => 'How soon can workers move in?', 'a' => 'Usually within ' . $days . ' days of a signed agreement, if beds are free. We need the workers\' passports, permits and medical clearance first, and confirm the move-in date in writing.' ),
			array( 'q' => 'What is included in the rent?', 'a' => 'The bed, locker, bathroom, utilities up to a fair-use limit, communal facilities, security, cleaning and maintenance. Canteen meals and transport are extra. See Rent inclusions above; your written quote lists the same items.' ),
			array( 'q' => 'Can we add or release beds later?', 'a' => 'Yes. Add beds at any time, subject to availability. Release beds with 30 days\' written notice after the minimum term.' ),
			array( 'q' => 'How do we verify your accommodation certificate?', 'a' => 'The certificate number and validity date are on this page, with a link to the authority\'s record. Ask the ' . $area . ' site team for a copy for your audit file.' ),
			array( 'q' => 'Is transport to our factory available?', 'a' => 'Yes. Concepts Group runs its own buses and vans. Tell us your factory location and shift times for a transport quote.' ),
		);
	};
	$base = array(
		'sample' => '1', 'region' => 'Klang Valley', 'state' => 'Selangor', 'operator_name' => 'Concepts ATC Sdn Bhd',
		'phone' => '+603-3318 7773', 'whatsapp' => '+60 17-887 3361', 'email' => 'support@concepts-groups.com',
		'visiting_hours' => 'Mon–Sat, 9am–5pm', 'response_time' => 'Replies within 1 working day',
		'beds_per_room' => '4', 'last_checked' => gmdate( 'Y-m-d' ), 'checked_by' => 'Sample data', 'faq_reviewed' => gmdate( 'Y-m-d' ),
		'route_basis' => 'Drive times by car, off-peak, measured on Google Maps.',
		'pricing_factors' => 'Number of beds, room type, contract length and services added.',
		'proof_client' => 'Sample: a semiconductor manufacturer has housed 600 workers with Concepts for 8 years. Replace with a real client example and their permission.',
		'certificate_no' => 'SAMPLE-0000', 'certificate_valid_until' => gmdate( 'Y-m-d', strtotime( '+1 year' ) ), 'certificate_url' => 'https://jtksm.mohr.gov.my/', 'certificate_publish' => '1',
		'min_booking' => '20 beds', 'deposit_months' => '2', 'term' => '12 months',
	);
	$sites = array(
		'Shah Alam' => array(
			'status' => 'operating', 'bed_basis' => 'licensed', 'bed_capacity' => '1840', 'land_area_sqm' => '12400', 'gfa_sqm' => '21800', 'rooms_per_block' => '115', 'blocks' => '4',
			'fit' => 'Purpose-built CLQ for manufacturers in Shah Alam\'s industrial estates, minutes from Seksyen 26 and Hicom Glenmarie, with four-person rooms and a named site team.',
			'story' => 'Suits manufacturers in Seksyen 26 and Hicom Glenmarie who need 20 to 500 beds close to the factory gate. Four-person rooms, managed on site by a named team.',
			'address' => "Lot 1234, Jalan Sample 26/1\nSeksyen 26", 'postcode' => '40400', 'city' => 'Shah Alam', 'lat' => '3.0236', 'lng' => '101.5398',
			'contact_name' => 'Site manager (sample)', 'rate_per_bed' => 'RM 260', 'available_from' => gmdate( 'Y-m-d', strtotime( '+1 month' ) ),
			'photos' => array( 'woke', 'home', 'home2' ), 'est' => 'work', 'road' => 'farm', 'daily' => 'woke2', 'days' => 14,
			'nearby' => array( array( 'estate', 'Seksyen 26 industrial area', 4 ), array( 'estate', 'Hicom Glenmarie', 12 ), array( 'estate', 'Bukit Kemuning', 10 ), array( 'expressway', 'KESAS', 6 ), array( 'expressway', 'Federal Highway', 9 ), array( 'daily', 'Klinik Seksyen 26', 5 ), array( 'daily', 'Pasar Seksyen 25', 7 ), array( 'daily', 'Masjid Seksyen 26', 4 ) ),
		),
		'Telok Panglima Garang' => array(
			'status' => 'in_development', 'bed_basis' => 'target', 'bed_capacity' => '2200', 'target_date' => 'Q4 2027', 'land_area_sqm' => '15600', 'gfa_sqm' => '26400',
			'fit' => 'New CLQ for factories in southern Klang Valley and Kuala Langat, close to the Telok Panglima Garang and Pulau Indah industrial parks, with on-site management.',
			'story' => 'Planned for employers around Telok Panglima Garang and Pulau Indah who need large, compliant housing near the coast-side industrial parks.',
			'address' => "Lot 5678, Jalan Sample\nTelok Panglima Garang", 'postcode' => '42500', 'city' => 'Telok Panglima Garang', 'lat' => '2.9420', 'lng' => '101.4560',
			'photos' => array( 'home2', 'woke2', 'farm2' ), 'est' => 'farm3', 'road' => 'farm4', 'daily' => 'home', 'days' => 21,
			'nearby' => array( array( 'estate', 'Telok Panglima Garang industrial area', 5 ), array( 'estate', 'Pulau Indah Industrial Park', 20 ), array( 'expressway', 'SKVE', 8 ), array( 'expressway', 'KESAS', 15 ), array( 'daily', 'Klinik Telok Panglima Garang', 6 ), array( 'daily', 'Pasar Telok', 5 ) ),
		),
		'Port Klang' => array(
			'status' => 'in_development', 'bed_basis' => 'target', 'bed_capacity' => '1500', 'target_date' => 'Q2 2027', 'land_area_sqm' => '10800', 'gfa_sqm' => '18000',
			'fit' => 'CLQ for logistics, warehousing and port-side manufacturing around Northport, Westport and the Port Klang Free Zone, built for shift work and a short ride to the gate.',
			'story' => 'Planned for operators around Northport, Westport and the Port Klang free zone who run shift work and need beds a short bus ride from the gate.',
			'address' => "Lot 9012, Jalan Sample\nPelabuhan Klang", 'postcode' => '42000', 'city' => 'Port Klang', 'lat' => '3.0010', 'lng' => '101.3940',
			'photos' => array( 'work', 'woke', 'farm' ), 'est' => 'farm2', 'road' => 'farm3', 'daily' => 'home2', 'days' => 21,
			'nearby' => array( array( 'estate', 'Port Klang Free Zone', 8 ), array( 'estate', 'Northport', 10 ), array( 'estate', 'Westport', 25 ), array( 'expressway', 'NKVE (Klang)', 12 ), array( 'expressway', 'KESAS', 14 ), array( 'daily', 'Klinik Pelabuhan Klang', 6 ), array( 'daily', 'Pasar Port Klang', 7 ) ),
		),
		'Jenjarom' => array(
			'status' => 'in_development', 'bed_basis' => 'target', 'bed_capacity' => '1600', 'target_date' => 'Q1 2028', 'land_area_sqm' => '11500', 'gfa_sqm' => '19200',
			'fit' => 'CLQ for factories in Jenjarom and the Kuala Langat corridor, with four-person rooms, a named site team and room to grow as your headcount does.',
			'story' => 'Planned for manufacturers in Jenjarom and Banting who need practical, well-managed housing close to their lines, with room to grow.',
			'address' => "Lot 3456, Jalan Sample\nJenjarom", 'postcode' => '42600', 'city' => 'Jenjarom', 'lat' => '2.8870', 'lng' => '101.5050',
			'photos' => array( 'farm4', 'home', 'woke2' ), 'est' => 'work', 'road' => 'farm', 'daily' => 'farm2', 'days' => 21,
			'nearby' => array( array( 'estate', 'Jenjarom industrial area', 4 ), array( 'estate', 'Banting industrial area', 18 ), array( 'expressway', 'SKVE', 10 ), array( 'expressway', 'ELITE', 20 ), array( 'daily', 'Klinik Jenjarom', 5 ), array( 'daily', 'Pasar Jenjarom', 4 ) ),
		),
	);
	$order = 0;
	foreach ( $sites as $area => $s ) {
		$id = wp_insert_post( array(
			'post_type'   => 'ccq_site',
			'post_status' => 'draft',
			'post_title'  => 'Concepts ' . $area,
			'post_name'   => sanitize_title( $area ),
			'menu_order'  => $order++,
		) );
		if ( ! $id || is_wp_error( $id ) ) {
			continue;
		}
		$m = array_merge( $base, $s, array(
			'area'       => $area,
			'photos'     => implode( ',', array( $pics['ext'], $pics['bunk'], $pics['campus'], $pics['bath'] ) ),
			'plans'      => $pics['plan'],
			'places'     => ccq_sample_places( $pics ),
			'why_images' => implode( ',', array( $pics['rev'], $pics['campus'], $pics['bunk'], $pics['bath'] ) ),
			'img_estate' => $pics[ $s['est'] ],
			'img_road'   => $pics[ $s['road'] ],
			'img_daily'  => $pics[ $s['daily'] ],
			'inclusions' => $inc( 'operating' === $s['status'] ? 'TERMS' : 'TO CONFIRM' ),
			'nearby'     => $near( $s['nearby'] ),
			'faq'        => $faq( $area, $s['days'] ),
		) );
		unset( $m['est'], $m['road'], $m['daily'], $m['days'] );
		update_post_meta( $id, '_ccq', $m );
	}
}

/* ------------------------------------------------------------------
 * 3. ADMIN — the site editor lives in admin.php.
 * ---------------------------------------------------------------- */
if ( is_admin() ) {
	require CCQ_DIR . 'admin.php';
}

/* ------------------------------------------------------------------
 * 4. HELPERS — media, lists, display rules (spec section 8 rules).
 * ---------------------------------------------------------------- */

/* "12,https://…/a.webp" -> [ [src, alt, caption, w, h], … ]. IDs read alt and caption from the Media Library. */
function ccq_media_list( $v, $size = 'large' ) {
	$out = array();
	foreach ( array_filter( array_map( 'trim', explode( ',', (string) $v ) ) ) as $x ) {
		if ( ctype_digit( $x ) ) {
			$img = wp_get_attachment_image_src( (int) $x, $size );
			if ( $img ) {
				$out[] = array(
					'src' => $img[0], 'w' => $img[1], 'h' => $img[2],
					'alt' => (string) get_post_meta( (int) $x, '_wp_attachment_image_alt', true ),
					'cap' => (string) wp_get_attachment_caption( (int) $x ),
				);
			}
		} elseif ( wp_http_validate_url( $x ) ) {
			$out[] = array( 'src' => $x, 'w' => 0, 'h' => 0, 'alt' => '', 'cap' => '' );
		}
	}
	return $out;
}

function ccq_date( $ymd ) {
	return $ymd ? date_i18n( 'j F Y', strtotime( $ymd ) ) : '';
}

function ccq_num( $n ) {
	return '' === (string) $n ? '' : number_format_i18n( (float) $n );
}

/* A dashed placeholder for unverified figures. */
function ccq_tbc( $v, $suffix = '' ) {
	return '' === (string) $v ? '<span class="ccq-tbc">To confirm</span>' : esc_html( $v . $suffix );
}

/* Development sites never show beds as if operating. */
function ccq_beds_label( $r ) {
	if ( '' === (string) $r['bed_capacity'] ) {
		return '';
	}
	$n = ccq_num( $r['bed_capacity'] ) . ' beds';
	if ( 'operating' !== $r['status'] || 'target' === $r['bed_basis'] ) {
		return 'Target ' . $n . ( $r['target_date'] ? ' by ' . $r['target_date'] : '' );
	}
	return $n . ( 'licensed' === $r['bed_basis'] ? ' (licensed)' : '' );
}

function ccq_status_label( $s ) {
	$m = array( 'operating' => 'Operating', 'in_development' => 'In development', 'transitioning' => 'Transitioning' );
	return isset( $m[ $s ] ) ? $m[ $s ] : 'In development';
}

/* Nearby rows grouped by type, with the nearest estate first. */
function ccq_nearby( $r ) {
	$g = array( 'estate' => array(), 'expressway' => array(), 'daily' => array() );
	foreach ( ccq_rows( $r['nearby'], 'nearby' ) as $c ) {
		$t = strtolower( $c[0] );
		if ( isset( $g[ $t ] ) ) {
			$g[ $t ][] = array( 'name' => $c[1], 'min' => (int) $c[2] );
		}
	}
	foreach ( $g as &$list ) {
		usort( $list, function ( $a, $b ) {
			return $a['min'] - $b['min'];
		} );
	}
	return $g;
}

function ccq_wa_link( $num, $text = '' ) {
	$d = preg_replace( '/\D/', '', (string) $num );
	return $d ? 'https://wa.me/' . $d . ( $text ? '?text=' . rawurlencode( $text ) : '' ) : '';
}

function ccq_tel( $num ) {
	return 'tel:' . preg_replace( '/[^0-9+]/', '', (string) $num );
}

/* Published sites, in menu order. */
function ccq_sites( $exclude = 0 ) {
	return get_posts( array(
		'post_type'   => 'ccq_site',
		'post_status' => 'publish',
		'numberposts' => 20,
		'orderby'     => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
		'exclude'     => $exclude ? array( $exclude ) : array(),
	) );
}

/* Hub page heading and intro (CLQ Sites > Properties page), shown at /centralised-labour-quarters/. */
function ccq_hub() {
	$h = get_option( 'ccq_hub', array() );
	return array(
		'title' => ! empty( $h['title'] ) ? $h['title'] : 'Centralised Labour Quarters in Klang Valley',
		'intro' => ! empty( $h['intro'] ) ? $h['intro'] : 'Every site has its own fact sheet: beds, address, nearby estates and the date we last checked each figure.',
	);
}

/* Employer FAQ on the hub (also FAQPage schema). No figures here: each property page carries its own verified facts. */
function ccq_hub_faq() {
	return apply_filters( 'ccq_hub_faq', array(
		array( 'q' => 'What is a CLQ (centralised labour quarters)?', 'a' => 'A CLQ is purpose-built, centrally managed accommodation for workers, planned around Malaysia\'s worker housing requirements under Act 446. Employers rent beds or rooms instead of running their own hostels.' ),
		array( 'q' => 'Where are Concepts Group\'s CLQs?', 'a' => 'All our properties are in Selangor, Klang Valley: Shah Alam is operating, while Telok Panglima Garang, Jenjarom and Port Klang are in development. Each property has its own page with its status and details.' ),
		array( 'q' => 'What is included in the rent?', 'a' => 'Inclusions vary by property and are listed on each property page. Every quote we send sets out exactly what is included, so you can compare costs clearly.' ),
		array( 'q' => 'How many workers can you accommodate?', 'a' => 'It depends on the property and current availability. Tell us your headcount, workplace location and move-in date, and we will confirm availability and send a written quote.' ),
		array( 'q' => 'Can employers visit the accommodation before signing?', 'a' => 'Yes. You can book a site visit to see the rooms and facilities, and ask our team about the documents available for your compliance review.' ),
		array( 'q' => 'How soon can workers move in?', 'a' => 'At operating sites, move-in depends on bed availability at the time of your enquiry. Properties still in development accept registrations of interest only.' ),
	) );
}

/* Sample records stay out of search results. */
add_filter( 'wp_robots', function ( $robots ) {
	if ( is_singular( 'ccq_site' ) && ccq_get()['sample'] ) {
		$robots['noindex']  = true;
		$robots['nofollow'] = true;
	}
	return $robots;
} );

/* ------------------------------------------------------------------
 * 5. FRONT END — template routing, assets only on site pages.
 * ---------------------------------------------------------------- */
function ccq_is_page() {
	global $post;
	return is_singular( 'ccq_site' ) || is_post_type_archive( 'ccq_site' ) || ( $post && ( has_shortcode( (string) $post->post_content, 'ccq_site' ) || has_shortcode( (string) $post->post_content, 'ccq_hub_sections' ) ) );
}

add_filter( 'template_include', function ( $t ) {
	if ( is_singular( 'ccq_site' ) ) {
		return CCQ_DIR . 'templates/single.php';
	}
	if ( is_post_type_archive( 'ccq_site' ) ) {
		return CCQ_DIR . 'templates/hub.php';
	}
	return $t;
}, 99 );

add_action( 'wp_enqueue_scripts', function () {
	if ( ! ccq_is_page() ) {
		return;
	}
	wp_enqueue_style( 'cgs-fonts', 'https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,600;12..96,700&family=Inter:wght@400;500;600;700&display=swap', array(), null );
	wp_enqueue_style( 'ccq', CCQ_URL . 'assets/site.css', array( 'cgs-fonts' ), CCQ_VERSION );
	/* Load after the Sections plugin's GSAP when it is there, so the scroll reveals match the other pages. */
	$deps = wp_script_is( 'cgs-gsap-st', 'registered' ) ? array( 'cgs-gsap-st' ) : array();
	wp_enqueue_script( 'ccq', CCQ_URL . 'assets/site.js', $deps, CCQ_VERSION, true );
	wp_localize_script( 'ccq', 'CCQ', array( 'endpoint' => esc_url_raw( rest_url( 'ccq/v1/enquiry' ) ) ) );
}, 50 );

/* Preload the first hero photo (likely LCP). */
add_action( 'wp_head', function () {
	if ( ! is_singular( 'ccq_site' ) ) {
		return;
	}
	$p = ccq_media_list( ccq_get()['photos'], '1536x1536' );
	if ( $p ) {
		echo '<link rel="preload" as="image" href="' . esc_url( $p[0]['src'] ) . '" fetchpriority="high">' . "\n";
	}
}, 2 );

/* Render the whole property page for a site (used by the template and the shortcode). */
function ccq_render( $post ) {
	$r = ccq_get( $post );
	if ( ! $r ) {
		return '';
	}
	ob_start();
	include CCQ_DIR . 'templates/sections.php';
	return ob_get_clean();
}

/* One property card: photo, name with a round arrow, then one meta line:
 * status and beds (property pages) or postcode, city and state ($place, the hub). */
function ccq_card( $p, $place = false ) {
	$o    = ccq_get( $p );
	$ph   = ccq_media_list( $o['photos'], 'medium_large' );
	$meta = $place
		? array_filter( array( trim( $o['postcode'] . ' ' . ( $o['city'] ? $o['city'] : $o['area'] ) ), $o['state'] ) )
		: array_filter( array( ccq_status_label( $o['status'] ), ccq_beds_label( $o ) ) );
	?>
<a class="ccq-card" href="<?php echo esc_url( $o['url'] ); ?>">
	<span class="ccq-card-ph"><?php if ( $ph ) : ?><img src="<?php echo esc_url( $ph[0]['src'] ); ?>" alt="" loading="lazy" decoding="async"><?php endif; ?></span>
	<span class="ccq-card-row"><span class="ccq-card-n"><?php echo esc_html( $o['area'] ); ?></span><span class="ccq-card-go" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span></span>
	<span class="ccq-card-b"><?php echo esc_html( implode( $place ? ', ' : ' · ', $meta ) ); ?></span>
</a>
	<?php
}

/* "Other properties" band on a property page: pale gradient, label, centred heading, simple cards, "All properties" card. */
function ccq_explore( $sites, $with_all ) {
	$hub = ccq_hub_url();
	?>
<section class="ccq-sec ccq-explore" aria-labelledby="ccq-ex-h">
	<div class="ccq-wrap">
		<span class="ccq-pilllabel"><?php echo $with_all ? 'Other properties' : 'All properties'; ?></span>
		<h2 id="ccq-ex-h" class="ccq-ex-h">Explore Concepts <strong>accommodation</strong></h2>
		<div class="ccq-cards">
			<?php foreach ( array_values( $sites ) as $p ) { ccq_card( $p ); } ?>
			<?php if ( $with_all ) : ?>
			<a class="ccq-card ccq-card-all" href="<?php echo esc_url( $hub ); ?>"><span class="ccq-card-t">See all properties</span><span class="ccq-card-go" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span></a>
			<?php endif; ?>
		</div>
	</div>
</section>
	<?php
}

/* The homepage hero's three-layer wave (blue gradient, pale blue, then $front = the colour of the next section),
 * sitting on the bottom edge of a section. */
function ccq_layer_wave( $front ) {
	static $n = 0;
	$n++;
	$g = 'ccqWaveG' . $n;
	return '<svg class="ccq-lwave" viewBox="0 0 1440 200" preserveAspectRatio="none" aria-hidden="true">'
		. '<defs><linearGradient id="' . $g . '" x1="0" x2="1" y1="0" y2="0"><stop offset="0" stop-color="#0b5fc4"/><stop offset=".55" stop-color="#1e90ff"/><stop offset="1" stop-color="#7cc0ff"/></linearGradient></defs>'
		. '<path d="M0 200V18C210 6 430 40 640 92S1080 176 1260 118C1340 92 1400 52 1440 34V200Z" fill="url(#' . $g . ')"/>'
		. '<path d="M0 200V70C250 52 480 88 700 132S1120 182 1290 150C1360 136 1410 112 1440 96V200Z" fill="#cfe6ff" fill-opacity=".95"/>'
		. '<path d="M0 200V118C260 92 500 120 730 150S1140 192 1310 172C1370 165 1415 150 1440 138V200Z" fill="' . esc_attr( $front ) . '"/>'
		. '</svg>';
}

/* ------------------------------------------------------------------
 * HUB BLOCK — [ccq_hub_sections]: the navy property grid (ticker, heading, cards 3 per row, page numbers)
 * and the "What is a CLQ" explainer. Editable in WPBakery as "Concepts CLQ Properties".
 * Cards come from the published CLQ sites: the first 6 show, "See more" reveals 6 more each click
 * (all cards are in the HTML, so search engines see every property link).
 * ---------------------------------------------------------------- */
function ccq_hub_defaults() {
	return array(
		'eb'     => 'All properties',
		'h'      => 'Explore Concepts accommodation',
		'he'     => 'accommodation',
		'per'    => '6',
		'about'  => 'yes',
		'aimg'   => home_url( '/wp-content/uploads/2026/10/Candid-hostel-conversation-by-the-bunks.webp' ),
		'aalt'   => 'Workers talking beside their bunk beds in a Concepts hostel room',
		'aeb'    => 'About CLQ',
		'ah'     => 'What is a centralised labour quarters (CLQ)?',
		'ahe'    => 'centralised labour quarters',
		'ap'     => 'A centralised labour quarters (CLQ) is purpose-built, centrally managed housing for workers. Instead of running their own hostels, employers rent beds or rooms in a CLQ, and the operator looks after the building, facilities and daily upkeep, planned around Malaysia\'s worker housing law, Act 446.',
		'points' => '',
	);
}

function ccq_hub_points_default() {
	return array(
		array( 't' => 'Planned around Act 446', 'd' => 'The Workers\' Minimum Standards of Housing and Amenities Act 1990 sets the standard for worker housing, from living space to basic amenities.' ),
		array( 't' => 'Managed for you', 'd' => 'Upkeep, shared-area cleaning and day-to-day site issues are handled by the operator\'s team instead of your HR staff.' ),
		array( 't' => 'Close to industrial areas', 'd' => 'Our properties sit near the industrial estates of Klang Valley, which keeps the daily trip to work short.' ),
		array( 't' => 'Easy to compare', 'd' => 'Each property page lists its rooms, rent inclusions and nearby places, so you can shortlist before a site visit.' ),
	);
}

/* Text with one accent phrase in blue. */
function ccq_accent( $text, $accent ) {
	$h = esc_html( $text );
	$a = esc_html( $accent );
	return ( '' !== $a && false !== strpos( $h, $a ) ) ? str_replace( $a, '<em>' . $a . '</em>', $h ) : $h;
}

/* Image field: attachment ID or URL. */
function ccq_img_src( $v, $fallback ) {
	$v = trim( (string) $v );
	if ( ctype_digit( $v ) ) {
		$u = wp_get_attachment_image_url( (int) $v, 'large' );
		return $u ? $u : $fallback;
	}
	return '' !== $v ? $v : $fallback;
}

add_shortcode( 'ccq_hub_sections', function ( $atts ) {
	$d = ccq_hub_defaults();
	$a = shortcode_atts( $d, $atts, 'ccq_hub_sections' );
	foreach ( array( 'eb', 'h', 'aeb', 'ah', 'ap' ) as $k ) {
		if ( '' === trim( (string) $a[ $k ] ) ) {
			$a[ $k ] = $d[ $k ];
		}
	}
	$per  = max( 1, min( 24, (int) $a['per'] ) );
	$all  = ccq_sites();
	$pts  = array();
	if ( '' !== trim( (string) $a['points'] ) && function_exists( 'vc_param_group_parse_atts' ) ) {
		$pts = array_values( array_filter( (array) vc_param_group_parse_atts( $a['points'] ), function ( $p ) {
			return is_array( $p ) && ! empty( $p['t'] );
		} ) );
	}
	$pts = $pts ? $pts : ccq_hub_points_default();
	$pin = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 22s7-6.2 7-12a7 7 0 1 0-14 0c0 5.8 7 12 7 12Z" fill="none" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="10" r="2.6" fill="currentColor"/></svg>';
	$arw = function ( $dir ) {
		return '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="' . ( 'l' === $dir ? 'M19 12H5M11 6l-6 6 6 6' : 'M5 12h14M13 6l6 6-6 6' ) . '" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
	};
	ob_start();
	?>
<div id="ccq-page" class="ccq ccq-hub">
<section class="cgs-ab2 ccq-hubx" id="ccq-properties" aria-labelledby="ccq-ex-h">
	<?php if ( $all ) : ?>
	<div class="ccq-hubx-bar" aria-hidden="true"><div class="ccq-hubx-track">
		<?php for ( $r = 0; $r < 4; $r++ ) : foreach ( $all as $p ) : $o = ccq_get( $p ); ?>
		<span class="ccq-hubx-c"><?php echo $pin; // Static SVG. ?><?php echo esc_html( $o['area'] ); ?></span>
		<?php endforeach; endfor; ?>
	</div></div>
	<?php endif; ?>
	<div class="ccq-hubx-body">
		<div class="ccq-hubx-head">
			<h2 class="cgs-hero-eb"><?php echo esc_html( $a['eb'] ); ?></h2>
			<h3 id="ccq-ex-h" class="cgs-about-h"><?php echo ccq_accent( $a['h'], $a['he'] ); // Escaped in ccq_accent. ?></h3>
		</div>
		<div class="ccq-cards ccq-hubx-grid" id="ccq-grid" data-step="<?php echo (int) $per; ?>">
			<?php foreach ( array_values( $all ) as $i => $p ) {
				if ( $i >= $per ) {
					echo '<div class="ccq-more-item" hidden>';
				}
				ccq_card( $p, true );
				if ( $i >= $per ) {
					echo '</div>';
				}
			} ?>
		</div>
		<?php if ( count( $all ) > $per ) : ?>
		<div class="ccq-seemore"><button type="button" class="ccq-more-b" aria-controls="ccq-grid">See more<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14M6 13l6 6 6-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></button></div>
		<?php endif; ?>
	</div>
	<?php echo ccq_layer_wave( 'yes' === $a['about'] ? '#fff' : '#eaf4ff' ); // Static SVG. ?>
</section>
	<?php if ( 'yes' === $a['about'] ) : ?>
<section class="ccq-about" aria-labelledby="ccq-about-h">
	<div class="ccq-about-in">
		<figure class="ccq-about-ph"><img src="<?php echo esc_url( ccq_img_src( $a['aimg'], $d['aimg'] ) ); ?>" alt="<?php echo esc_attr( $a['aalt'] ); ?>" loading="lazy" decoding="async"></figure>
		<div class="ccq-about-txt">
			<p class="ccq-about-eb"><?php echo esc_html( $a['aeb'] ); ?></p>
			<h2 id="ccq-about-h" class="ccq-about-h"><?php echo ccq_accent( $a['ah'], $a['ahe'] ); // Escaped in ccq_accent. ?></h2>
			<p class="ccq-about-lead"><?php echo esc_html( $a['ap'] ); ?></p>
			<div class="ccq-about-pts">
				<?php foreach ( $pts as $pt ) : ?>
				<div><h3><?php echo esc_html( $pt['t'] ); ?></h3><p><?php echo esc_html( isset( $pt['d'] ) ? $pt['d'] : '' ); ?></p></div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<?php echo ccq_layer_wave( '#eaf4ff' ); // Into the FAQ. ?>
</section>
	<?php endif; ?>
</div>
	<?php
	return ob_get_clean();
} );

add_action( 'vc_before_init', function () {
	if ( ! function_exists( 'vc_map' ) ) {
		return;
	}
	$d = ccq_hub_defaults();
	$g = 'What is a CLQ';
	vc_map( array(
		'name'        => 'Concepts CLQ Properties',
		'base'        => 'ccq_hub_sections',
		'category'    => 'Concepts Group',
		'icon'        => 'icon-wpb-row',
		'description' => 'Property grid (cards from CLQ Sites, with page numbers) and the "What is a CLQ" section',
		'params'      => array(
			array( 'type' => 'textfield', 'heading' => 'Label above heading', 'param_name' => 'eb', 'value' => $d['eb'] ),
			array( 'type' => 'textfield', 'heading' => 'Heading', 'param_name' => 'h', 'value' => $d['h'] ),
			array( 'type' => 'textfield', 'heading' => 'Blue words in heading', 'param_name' => 'he', 'value' => $d['he'] ),
			array( 'type' => 'textfield', 'heading' => 'Cards shown at first', 'param_name' => 'per', 'value' => $d['per'], 'description' => 'Also how many each "See more" click adds. 6 = two rows of three.' ),
			array( 'type' => 'dropdown', 'heading' => 'Show the "What is a CLQ" section', 'param_name' => 'about', 'value' => array( 'Yes' => 'yes', 'No' => 'no' ), 'std' => 'yes', 'group' => $g ),
			array( 'type' => 'attach_image', 'heading' => 'Photo', 'param_name' => 'aimg', 'group' => $g, 'description' => 'Empty = the hostel conversation photo.' ),
			array( 'type' => 'textfield', 'heading' => 'Photo description (alt text)', 'param_name' => 'aalt', 'value' => $d['aalt'], 'group' => $g ),
			array( 'type' => 'textfield', 'heading' => 'Label above heading', 'param_name' => 'aeb', 'value' => $d['aeb'], 'group' => $g ),
			array( 'type' => 'textfield', 'heading' => 'Heading (a question works best for Google and AI answers)', 'param_name' => 'ah', 'value' => $d['ah'], 'group' => $g ),
			array( 'type' => 'textfield', 'heading' => 'Blue words in heading', 'param_name' => 'ahe', 'value' => $d['ahe'], 'group' => $g ),
			array( 'type' => 'textarea', 'heading' => 'Short answer (2-3 sentences)', 'param_name' => 'ap', 'value' => $d['ap'], 'group' => $g ),
			array(
				'type'       => 'param_group',
				'heading'    => 'Points',
				'param_name' => 'points',
				'group'      => $g,
				'value'      => rawurlencode( wp_json_encode( ccq_hub_points_default() ) ),
				'params'     => array(
					array( 'type' => 'textfield', 'heading' => 'Title', 'param_name' => 't', 'admin_label' => true ),
					array( 'type' => 'textarea', 'heading' => 'Text', 'param_name' => 'd' ),
				),
			),
		),
	) );
} );

/* The hub page: a published page whose address is /centralised-labour-quarters/ (0 = none, the built-in listing is used). */
function ccq_hub_page_id() {
	static $id = null;
	if ( null === $id ) {
		$p  = get_page_by_path( CCQ_BASE );
		$id = ( $p && 'publish' === $p->post_status ) ? (int) $p->ID : 0;
	}
	return $id;
}

/* Address of the hub, wherever it comes from. */
function ccq_hub_url() {
	$id = ccq_hub_page_id();
	if ( $id ) {
		return get_permalink( $id );
	}
	$u = get_post_type_archive_link( 'ccq_site' );
	return $u ? $u : home_url( '/' . CCQ_BASE . '/' );
}

/* True on the hub, as an editable page or as the built-in listing. */
function ccq_is_hub() {
	global $post;
	return is_post_type_archive( 'ccq_site' ) || ( is_page() && $post && has_shortcode( (string) $post->post_content, 'ccq_hub_sections' ) );
}

add_filter( 'body_class', function ( $c ) {
	if ( ccq_is_hub() ) {
		$c[] = 'ccq-hub-page';
	}
	return $c;
} );

/* [ccq_site slug="shah-alam"] — the full page inside any WPBakery page. */
add_shortcode( 'ccq_site', function ( $atts ) {
	$a = shortcode_atts( array( 'slug' => '' ), $atts, 'ccq_site' );
	$p = $a['slug'] ? get_page_by_path( sanitize_title( $a['slug'] ), OBJECT, 'ccq_site' ) : null;
	return $p ? ccq_render( $p ) : '';
} );

add_action( 'vc_before_init', function () {
	if ( ! function_exists( 'vc_map' ) ) {
		return;
	}
	$choices = array( 'Choose a site' => '' );
	foreach ( get_posts( array( 'post_type' => 'ccq_site', 'post_status' => 'any', 'numberposts' => 50 ) ) as $p ) {
		$choices[ $p->post_title ] = $p->post_name;
	}
	vc_map( array(
		'name'        => 'Concepts CLQ Site Page',
		'base'        => 'ccq_site',
		'category'    => 'Concepts Group',
		'icon'        => 'icon-wpb-row',
		'description' => 'Full property page. Edit facts under CLQ Sites.',
		'params'      => array(
			array( 'type' => 'dropdown', 'heading' => 'Site', 'param_name' => 'slug', 'value' => $choices, 'admin_label' => true, 'description' => 'Every figure comes from the site record under CLQ Sites, so it stays identical on every page.' ),
		),
	) );
} );

/* ------------------------------------------------------------------
 * 6. SEARCH — title, description, structured data (spec section 9).
 *    Yoast / Rank Math, when active, own the title, description and
 *    their graph; this plugin then adds only the lodging entity.
 * ---------------------------------------------------------------- */
function ccq_seo_plugin() {
	return defined( 'WPSEO_VERSION' ) || class_exists( 'RankMath' ) || defined( 'AIOSEO_VERSION' ) || defined( 'SEOPRESS_VERSION' );
}

add_filter( 'pre_get_document_title', function ( $t ) {
	if ( is_singular( 'ccq_site' ) && ! ccq_seo_plugin() ) {
		return ccq_page_title( ccq_get()['area'] );
	}
	if ( is_post_type_archive( 'ccq_site' ) && ! ccq_seo_plugin() ) {
		return 'CLQ & Worker Accommodation in Klang Valley | Concepts Group';
	}
	return $t;
}, 20 );

/* Page title: "CLQ & Worker Accommodation in {Area} | Concepts Group", shortened for long area names (Google shows ~60 characters). */
function ccq_page_title( $area ) {
	foreach ( array( 'CLQ & Worker Accommodation in ', 'CLQ & Worker Hostel in ', 'CLQ in ' ) as $lead ) {
		$t = $lead . $area . ' | Concepts Group';
		if ( mb_strlen( $t ) <= 62 ) {
			return $t;
		}
	}
	return $t;
}

function ccq_description( $r ) {
	if ( ! empty( $r['sample'] ) ) {
		/* Draft figures are not verified yet, so the description names no numbers. */
		$d = 'Centralised labour quarters (CLQ) and worker accommodation in ' . $r['area'] . ' by Concepts Group. See rooms, rent inclusions and request a quote.';
		return mb_strlen( $d ) > 160 ? 'CLQ worker accommodation in ' . $r['area'] . ' by Concepts Group. See rooms, rent inclusions and request a quote.' : $d;
	}
	$facts = array();
	$b     = ccq_beds_label( $r );
	$near  = ccq_nearby( $r );
	if ( $b ) {
		$facts[] = $b;
	}
	if ( $near['estate'] ) {
		$facts[] = $near['estate'][0]['min'] . ' min from ' . $near['estate'][0]['name'];
	}
	$d = 'CLQ worker accommodation in ' . $r['area'] . ( $facts ? ': ' . implode( ', ', $facts ) : '' ) . '. See rooms, rent inclusions and get a written quote.';
	return mb_strlen( $d ) > 160 ? 'CLQ worker accommodation in ' . $r['area'] . ' by Concepts Group. See rooms, rent inclusions and get a written quote.' : $d;
}

add_action( 'wp_head', function () {
	if ( ! is_singular( 'ccq_site' ) && ! is_post_type_archive( 'ccq_site' ) ) {
		return;
	}
	$home = home_url( '/' );
	$org  = array( '@type' => 'Organization', '@id' => $home . '#organization', 'name' => 'Concepts Group', 'url' => $home, 'foundingDate' => (string) CCQ_FOUNDED );
	$hub  = ccq_hub_url();
	$g    = array();

	if ( is_post_type_archive( 'ccq_site' ) ) {
		if ( ccq_seo_plugin() ) {
			return;
		}
		echo '<meta name="description" content="' . esc_attr( 'Centralised labour quarters (CLQ) and worker accommodation in Klang Valley, Selangor: compare Concepts Group sites, rooms, rent inclusions and request a quote.' ) . '">' . "\n";
		$g = array( $org, array( '@type' => 'CollectionPage', '@id' => $hub . '#webpage', 'url' => $hub, 'name' => 'Worker accommodation properties', 'isPartOf' => array( '@id' => $home . '#website' ), 'about' => array( '@id' => $home . '#organization' ) ) );
	} else {
		$r   = ccq_get();
		$url = $r['url'];
		$biz = array(
			'@type'    => 'LodgingBusiness',
			'@id'      => $url . '#site',
			'name'     => $r['site_name'],
			'url'      => $url,
			'description' => wp_strip_all_tags( $r['story'] ? $r['story'] : ccq_description( $r ) ),
			'parentOrganization' => array( '@id' => $home . '#organization', 'name' => 'Concepts Group' ),
		);
		if ( $r['operator_name'] ) {
			$biz['legalName'] = $r['operator_name'];
		}
		if ( $r['address'] ) {
			$biz['address'] = array_filter( array( '@type' => 'PostalAddress', 'streetAddress' => preg_replace( '/\s*\n\s*/', ', ', trim( $r['address'] ) ), 'addressLocality' => $r['city'] ? $r['city'] : $r['area'], 'addressRegion' => $r['state'], 'postalCode' => $r['postcode'], 'addressCountry' => 'MY' ) );
		}
		if ( $r['lat'] && $r['lng'] ) {
			$biz['geo'] = array( '@type' => 'GeoCoordinates', 'latitude' => (float) $r['lat'], 'longitude' => (float) $r['lng'] );
		}
		if ( $r['phone'] ) {
			$biz['telephone'] = $r['phone'];
		}
		if ( $r['email'] ) {
			$biz['email'] = $r['email'];
		}
		$photos = ccq_media_list( $r['photos'], 'full' );
		if ( $photos ) {
			$biz['image'] = wp_list_pluck( $photos, 'src' );
		}
		if ( 'operating' === $r['status'] && $r['bed_capacity'] && 'target' !== $r['bed_basis'] ) {
			$biz['numberOfRooms'] = $r['blocks'] && $r['rooms_per_block'] ? (int) $r['blocks'] * (int) $r['rooms_per_block'] : null;
			$biz['amenityFeature'] = array();
			foreach ( ccq_rows( $r['inclusions'], 'inclusions' ) as $c ) {
				if ( 'INCLUDED' === strtoupper( $c[3] ) ) {
					$biz['amenityFeature'][] = array( '@type' => 'LocationFeatureSpecification', 'name' => $c[1] . ': ' . $c[2], 'value' => true );
				}
			}
			$biz = array_filter( $biz );
		}
		if ( ccq_seo_plugin() ) {
			$g = array( $biz );
		} else {
			echo '<meta name="description" content="' . esc_attr( ccq_description( $r ) ) . '">' . "\n";
			$g = array(
				$org,
				$biz,
				array( '@type' => 'WebPage', '@id' => $url . '#webpage', 'url' => $url, 'name' => ccq_page_title( $r['area'] ), 'about' => array( '@id' => $url . '#site' ), 'breadcrumb' => array( '@id' => $url . '#breadcrumb' ), 'dateModified' => get_the_modified_date( 'c' ) ),
				array( '@type' => 'BreadcrumbList', '@id' => $url . '#breadcrumb', 'itemListElement' => array(
					array( '@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $home ),
					array( '@type' => 'ListItem', 'position' => 2, 'name' => 'Centralised Labour Quarters', 'item' => $hub ),
					array( '@type' => 'ListItem', 'position' => 3, 'name' => $r['area'], 'item' => $url ),
				) ),
			);
		}
	}
	echo '<script type="application/ld+json">' . wp_json_encode( array( '@context' => 'https://schema.org', '@graph' => $g ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}, 5 );

/* ------------------------------------------------------------------
 * 7. ENQUIRY — REST endpoint: validate, honeypot, rate limit,
 *    store a row, email the site team, auto-reply the sender.
 * ---------------------------------------------------------------- */
add_action( 'rest_api_init', function () {
	register_rest_route( 'ccq/v1', '/enquiry', array(
		'methods'             => 'POST',
		'permission_callback' => '__return_true',
		'callback'            => 'ccq_enquiry',
	) );
} );

function ccq_enquiry( WP_REST_Request $q ) {
	// Honeypot: bots fill every field. Pretend success.
	if ( '' !== trim( (string) $q->get_param( 'website' ) ) ) {
		return array( 'ok' => true );
	}
	$ip  = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$key = 'ccq_rl_' . md5( $ip );
	$hits = (int) get_transient( $key );
	if ( $hits >= 5 ) {
		return new WP_REST_Response( array( 'ok' => false, 'errors' => array( 'form' => 'Too many messages in a short time. Please try again in 15 minutes or contact us on WhatsApp.' ) ), 429 );
	}

	$site    = get_post( (int) $q->get_param( 'site' ) );
	$company = sanitize_text_field( (string) $q->get_param( 'company' ) );
	$workers = (int) $q->get_param( 'workers' );
	$email   = sanitize_email( (string) $q->get_param( 'email' ) );
	$phone   = sanitize_text_field( (string) $q->get_param( 'phone' ) );
	$msg     = sanitize_textarea_field( (string) $q->get_param( 'message' ) );

	$err = array();
	if ( ! $site || 'ccq_site' !== $site->post_type ) {
		$err['form'] = 'Please reload the page and try again.';
	}
	if ( '' === $company ) {
		$err['company'] = 'Enter your company name.';
	}
	if ( $workers < 1 || $workers > 100000 ) {
		$err['workers'] = 'Enter how many workers need beds.';
	}
	if ( ! is_email( $email ) ) {
		$err['email'] = 'Enter an email address we can reply to.';
	}
	if ( mb_strlen( $msg ) > 3000 ) {
		$err['message'] = 'Please keep the message under 3,000 characters.';
	}
	if ( $err ) {
		return new WP_REST_Response( array( 'ok' => false, 'errors' => $err ), 422 );
	}
	set_transient( $key, $hits + 1, 15 * MINUTE_IN_SECONDS );

	$r    = ccq_get( $site );
	$data = array( 'site' => $r['site_name'], 'company' => $company, 'workers' => $workers, 'email' => $email, 'phone' => $phone, 'message' => $msg, 'page' => $r['url'], 'time' => current_time( 'mysql' ) );

	$row = wp_insert_post( array( 'post_type' => 'ccq_enquiry', 'post_status' => 'private', 'post_title' => $company . ' — ' . $workers . ' workers — ' . $r['area'] ) );
	if ( $row && ! is_wp_error( $row ) ) {
		update_post_meta( $row, '_ccq_enquiry', $data );
	}

	$to   = is_email( $r['email'] ) ? $r['email'] : get_option( 'admin_email' );
	$body = '';
	foreach ( $data as $k => $v ) {
		$body .= ucfirst( $k ) . ': ' . $v . "\n";
	}
	wp_mail( $to, 'New enquiry: ' . $company . ' (' . $workers . ' workers) — ' . $r['site_name'], $body, array( 'Reply-To: ' . $email ) );
	wp_mail(
		$email,
		'We received your enquiry — ' . $r['site_name'],
		"Thank you for contacting Concepts Group.\n\nThe " . $r['area'] . " site team has your enquiry for " . $workers . " workers and will reply"
			. ( $r['response_time'] ? ' (' . $r['response_time'] . ')' : ' shortly' ) . ".\n\n"
			. ( $r['phone'] ? 'Phone: ' . $r['phone'] . "\n" : '' ) . ( $r['whatsapp'] ? 'WhatsApp: ' . ccq_wa_link( $r['whatsapp'] ) . "\n" : '' ) . "\n" . $r['url'],
		array( 'Reply-To: ' . $to )
	);

	/** Hook for a CRM or Google Sheets connector. */
	do_action( 'ccq_enquiry_received', $data, $row );

	return array( 'ok' => true );
}

/* Enquiry list columns + CSV export (the spreadsheet). */
add_filter( 'manage_ccq_enquiry_posts_columns', function ( $c ) {
	return array( 'cb' => $c['cb'], 'title' => 'Enquiry', 'ccq_email' => 'Email', 'ccq_phone' => 'Phone', 'date' => 'Received' );
} );
add_action( 'manage_ccq_enquiry_posts_custom_column', function ( $col, $id ) {
	$d = (array) get_post_meta( $id, '_ccq_enquiry', true );
	$k = str_replace( 'ccq_', '', $col );
	echo esc_html( isset( $d[ $k ] ) ? $d[ $k ] : '' );
}, 10, 2 );
add_action( 'add_meta_boxes_ccq_enquiry', function () {
	add_meta_box( 'ccq_enq', 'Enquiry', function ( $post ) {
		$d = (array) get_post_meta( $post->ID, '_ccq_enquiry', true );
		echo '<table class="widefat striped">';
		foreach ( $d as $k => $v ) {
			echo '<tr><th style="width:140px">' . esc_html( ucfirst( $k ) ) . '</th><td>' . nl2br( esc_html( $v ) ) . '</td></tr>';
		}
		echo '</table>';
	}, 'ccq_enquiry', 'normal' );
} );
add_action( 'admin_menu', function () {
	add_submenu_page( 'edit.php?post_type=ccq_site', 'Export enquiries', 'Export enquiries (CSV)', 'edit_posts', 'ccq-export', function () {
		echo '<div class="wrap"><h1>Export enquiries</h1><p><a class="button button-primary" href="' . esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=ccq_export' ), 'ccq_export' ) ) . '">Download CSV</a></p></div>';
	} );
} );
add_action( 'admin_post_ccq_export', function () {
	if ( ! current_user_can( 'edit_posts' ) || ! check_admin_referer( 'ccq_export' ) ) {
		wp_die( 'Not allowed.' );
	}
	header( 'Content-Type: text/csv; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename=concepts-site-enquiries-' . gmdate( 'Y-m-d' ) . '.csv' );
	$out  = fopen( 'php://output', 'w' );
	$cols = array( 'time', 'site', 'company', 'workers', 'email', 'phone', 'message', 'page' );
	fputcsv( $out, $cols );
	foreach ( get_posts( array( 'post_type' => 'ccq_enquiry', 'post_status' => 'private', 'numberposts' => -1 ) ) as $p ) {
		$d   = (array) get_post_meta( $p->ID, '_ccq_enquiry', true );
		$row = array();
		foreach ( $cols as $c ) {
			$v     = isset( $d[ $c ] ) ? (string) $d[ $c ] : '';
			$row[] = preg_match( '/^[=+\-@]/', $v ) ? "'" . $v : $v; // Block spreadsheet formula injection.
		}
		fputcsv( $out, $row );
	}
	exit;
} );
