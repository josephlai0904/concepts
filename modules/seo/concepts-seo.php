<?php
/**
 * Concepts Group Suite module: SEO / GEO / AEO.
 * Page titles and descriptions, social sharing tags, structured data (Organization, WebSite,
 * WebPage, BreadcrumbList, FAQPage) and sitemap cleanup. Steps aside when an SEO plugin is active.
 * Loaded by ../../concepts-group-suite.php. Not a standalone plugin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CGSEO_VERSION', '1.2.4' );

/**
 * Default title and description per page slug ('front' = homepage).
 * Rules: title <= 62 chars, description 70-160 chars, no unverified figures or claims.
 * Editors override any page in its "SEO (Google & AI)" box.
 */
function cgseo_defaults() {
	return array(
		'front'                      => array( 'Centralised Labour Quarters & Labour Supply | Concepts Group', 'Centralised labour quarters (CLQ) and worker accommodation in Klang Valley, plus foreign worker recruitment, transport and workforce support since 1991.' ),
		'our-story'                  => array( 'Our Story: Workforce Solutions Since 1991 | Concepts Group', 'Since 1991, Concepts Group has supported Malaysian employers with worker recruitment, accommodation, transport and workforce management. Read our story.' ),
		'vision-mission'             => array( 'Vision & Mission | Concepts Group Malaysia', 'Our vision and mission: safe, well-managed worker accommodation and dependable workforce support that help Malaysian employers grow responsibly.' ),
		'foreign-worker-recruitment' => array( 'Foreign Worker Recruitment Agency Malaysia | Concepts Group', 'Foreign and local worker recruitment for Malaysian employers, from quota application and source-country partners to documentation and onboarding.' ),
		'transport-management'       => array( 'Worker Transport Management in Klang Valley | Concepts Group', 'Worker transport between accommodation and your workplace, planned around your shifts and routes. Ask Concepts Group for a transport quote.' ),
		'document-renewal'           => array( 'Foreign Worker Permit & Document Renewal | Concepts Group', 'Help with foreign worker permit (PLKS) renewals, passports and related documents, so your workforce stays compliant and on the job.' ),
		'blog'                       => array( 'Blog: Workforce, CLQ and Recruitment Guides | Concepts Group', 'Practical guides for Malaysian employers on foreign worker recruitment, CLQ worker accommodation, compliance and workforce management.' ),
		'contact'                    => array( 'Contact Concepts Group | Worker Accommodation & Recruitment', 'Talk to Concepts Group about worker accommodation, recruitment or transport. Call +603-3318 7773, WhatsApp +60 17-887 3361 or send an enquiry.' ),
	);
}

/* Company facts used in structured data. Only verified, public details. */
function cgseo_org() {
	$home = home_url( '/' );
	return array(
		'@type'        => 'Organization',
		'@id'          => $home . '#organization',
		'name'         => 'Concepts Group',
		'legalName'    => 'CONCEPTS ATC SDN BHD',
		'identifier'   => '199501034798 (364000-P)',
		'url'          => $home,
		'logo'         => defined( 'CGS_LOGO_DARK' ) ? CGS_LOGO_DARK : null,
		'foundingDate' => '1991',
		'telephone'    => '+603-3318 7773',
		'email'        => 'support@concepts-groups.com',
		'areaServed'   => array( 'Klang Valley', 'Selangor', 'Malaysia' ),
		'address'      => array( '@type' => 'PostalAddress', 'streetAddress' => 'No 2-9, Block 9, Jalan Batu Nilam 16, Bandar Bukit Tinggi 2', 'postalCode' => '41200', 'addressLocality' => 'Klang', 'addressRegion' => 'Selangor', 'addressCountry' => 'MY' ),
		'sameAs'       => array( 'https://www.facebook.com/conceptsgroup.my' ),
		'contactPoint' => array( '@type' => 'ContactPoint', 'telephone' => '+603-3318 7773', 'contactType' => 'sales', 'areaServed' => 'MY', 'availableLanguage' => array( 'English', 'Malay' ) ),
	);
}

/* Default share image: a 1200x630 JPG (WhatsApp often skips .webp previews). */
define( 'CGSEO_IMAGE', 'https://concepts.manyproject.my/wp-content/uploads/2026/10/Concepts-Group-CLQ-share-1200x630-1.jpg' );

function cgseo_off() {
	return defined( 'WPSEO_VERSION' ) || class_exists( 'RankMath' ) || defined( 'AIOSEO_VERSION' ) || defined( 'SEOPRESS_VERSION' );
}

/* Page (or homepage) being shown, or 0. */
function cgseo_page_id() {
	if ( ! is_singular( 'page' ) ) {
		return 0;
	}
	return (int) get_queried_object_id();
}

/* [title, description] for a page: SEO box first, then the built-in default, then the page itself. */
function cgseo_page_meta( $id ) {
	$d    = cgseo_defaults();
	$key  = is_front_page() ? 'front' : (string) get_post_field( 'post_name', $id );
	$def  = isset( $d[ $key ] ) ? $d[ $key ] : array( '', '' );
	$t    = trim( (string) get_post_meta( $id, '_cgs_seo_title', true ) );
	$desc = trim( (string) get_post_meta( $id, '_cgs_seo_desc', true ) );
	if ( '' === $t ) {
		$t = '' !== $def[0] ? $def[0] : wp_strip_all_tags( get_the_title( $id ) ) . ' | Concepts Group';
	}
	if ( '' === $desc ) {
		$desc = $def[1];
	}
	if ( '' === $desc ) {
		$text = trim( preg_replace( '/\[[^\]]*\]/', ' ', wp_strip_all_tags( (string) get_post_field( 'post_content', $id ) ) ) );
		$desc = '' !== $text ? wp_trim_words( $text, 26, '…' ) : '';
	}
	return array( $t, $desc );
}

/* Titles: pages only. Posts, the blog list and CLQ pages are titled by their own modules. */
add_filter( 'pre_get_document_title', function ( $t ) {
	$id = cgseo_page_id();
	if ( ! $id || cgseo_off() ) {
		return $t;
	}
	return esc_html( cgseo_page_meta( $id )[0] );
}, 22 );

/* Social tags shared by every page type. */
function cgseo_social( $title, $desc, $url, $img, $type = 'website' ) {
	/* No image, or a .webp one (WhatsApp often shows no picture for those): use the JPG default. */
	$img = ( $img && ! preg_match( '/\.webp(\?|$)/i', $img ) ) ? $img : CGSEO_IMAGE;
	$tags = array(
		'og:type'        => $type,
		'og:site_name'   => 'Concepts Group',
		'og:locale'      => 'en_MY',
		'og:title'       => $title,
		'og:description' => $desc,
		'og:url'         => $url,
		'og:image'       => $img,
	);
	if ( CGSEO_IMAGE === $img ) {
		$tags['og:image:width']  = '1200';
		$tags['og:image:height'] = '630';
		$tags['og:image:alt']    = 'Concepts Group centralised labour quarters buildings with sports courts';
	}
	foreach ( $tags as $k => $v ) {
		if ( '' !== (string) $v ) {
			echo '<meta property="' . esc_attr( $k ) . '" content="' . esc_attr( $v ) . '">' . "\n";
		}
	}
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">' . "\n";
	if ( '' !== (string) $desc ) {
		echo '<meta name="twitter:description" content="' . esc_attr( $desc ) . '">' . "\n";
	}
	echo '<meta name="twitter:image" content="' . esc_attr( $img ) . '">' . "\n";
}

function cgseo_jsonld( $graph ) {
	echo '<script type="application/ld+json">' . wp_json_encode( array( '@context' => 'https://schema.org', '@graph' => array_values( array_filter( $graph ) ) ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}

add_action( 'wp_head', function () {
	if ( cgseo_off() ) {
		return;
	}
	$home = home_url( '/' );

	/* Pages and the homepage: description, social tags, full company + page graph. */
	$id = cgseo_page_id();
	if ( $id ) {
		list( $t, $desc ) = cgseo_page_meta( $id );
		$url = is_front_page() ? $home : get_permalink( $id );
		if ( '' !== $desc ) {
			echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
		}
		$img = has_post_thumbnail( $id ) ? get_the_post_thumbnail_url( $id, 'full' ) : '';
		cgseo_social( $t, $desc, $url, $img );
		$type  = is_front_page() ? 'WebPage' : ( 'contact' === get_post_field( 'post_name', $id ) ? 'ContactPage' : ( in_array( get_post_field( 'post_name', $id ), array( 'our-story', 'vision-mission' ), true ) ? 'AboutPage' : 'WebPage' ) );
		$graph = array(
			array_filter( cgseo_org() ),
			array( '@type' => 'WebSite', '@id' => $home . '#website', 'url' => $home, 'name' => 'Concepts Group', 'publisher' => array( '@id' => $home . '#organization' ), 'inLanguage' => 'en-MY' ),
			array_filter( array(
				'@type'       => $type,
				'@id'         => $url . '#webpage',
				'url'         => $url,
				'name'        => $t,
				'description' => $desc,
				'isPartOf'    => array( '@id' => $home . '#website' ),
				'about'       => array( '@id' => $home . '#organization' ),
				'inLanguage'  => 'en-MY',
			) ),
		);
		if ( ! is_front_page() ) {
			$graph[] = array( '@type' => 'BreadcrumbList', 'itemListElement' => array(
				array( '@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $home ),
				array( '@type' => 'ListItem', 'position' => 2, 'name' => wp_strip_all_tags( get_the_title( $id ) ), 'item' => $url ),
			) );
		}
		cgseo_jsonld( $graph );
		return;
	}

	/* Blog list and categories: description + social tags (title comes from the Articles module). */
	if ( ( is_home() && ! is_front_page() ) || is_category() ) {
		$d    = cgseo_defaults();
		$desc = $d['blog'][1];
		$t    = $d['blog'][0];
		$url  = get_option( 'page_for_posts' ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url( '/blog/' );
		if ( is_category() ) {
			$cat  = single_term_title( '', false );
			$cd   = trim( wp_strip_all_tags( (string) term_description() ) );
			$desc = '' !== $cd ? wp_trim_words( $cd, 26, '…' ) : $cat . ': guides for Malaysian employers on worker accommodation, foreign worker recruitment and workforce management.';
			$t    = $cat . ' | Concepts Group Blog';
			$url  = get_term_link( get_queried_object() );
			$url  = is_string( $url ) ? $url : home_url( '/blog/' );
		}
		echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
		cgseo_social( $t, $desc, $url, '' );
		return;
	}

	/* Posts and CLQ sites already print description + schema; add social tags. */
	if ( is_singular( 'post' ) ) {
		$pid  = get_queried_object_id();
		$t    = trim( (string) get_post_meta( $pid, '_cga_seo_title', true ) );
		$t    = '' !== $t ? $t : wp_strip_all_tags( get_the_title( $pid ) );
		$desc = trim( (string) get_post_meta( $pid, '_cga_seo_desc', true ) );
		$desc = '' !== $desc ? $desc : wp_trim_words( wp_strip_all_tags( (string) get_post_field( 'post_content', $pid ) ), 28, '…' );
		cgseo_social( $t, $desc, get_permalink( $pid ), get_the_post_thumbnail_url( $pid, 'full' ), 'article' );
		return;
	}
	if ( is_singular( 'ccq_site' ) && function_exists( 'ccq_get' ) ) {
		$r = ccq_get();
		cgseo_social( ccq_page_title( $r['area'] ), ccq_description( $r ), get_permalink( get_queried_object_id() ), '' );
		return;
	}
	if ( is_post_type_archive( 'ccq_site' ) ) {
		cgseo_social( 'CLQ & Worker Accommodation in Klang Valley | Concepts Group', 'Centralised labour quarters (CLQ) and worker accommodation in Klang Valley, Selangor: compare Concepts Group sites, rooms, rent inclusions and request a quote.', get_post_type_archive_link( 'ccq_site' ), '' );
	}
}, 4 );

/* AEO: the FAQ questions shown on the page, as FAQPage structured data. */
add_action( 'wp_footer', function () {
	/* Runs with SEO plugins too: none of them reads the [cgs_faq] section. */
	if ( empty( $GLOBALS['cgs_faq_seen'] ) ) {
		return;
	}
	$qa   = array();
	$seen = array();
	foreach ( (array) $GLOBALS['cgs_faq_seen'] as $i ) {
		$q = trim( wp_strip_all_tags( (string) ( isset( $i['q'] ) ? $i['q'] : '' ) ) );
		$a = trim( wp_strip_all_tags( (string) ( isset( $i['a'] ) ? $i['a'] : '' ) ) );
		if ( '' === $q || '' === $a || isset( $seen[ $q ] ) ) {
			continue;
		}
		$seen[ $q ] = 1;
		$qa[]       = array( '@type' => 'Question', 'name' => $q, 'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $a ) );
	}
	if ( $qa ) {
		echo '<script type="application/ld+json">' . wp_json_encode( array( '@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $qa ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
	}
}, 20 );

/* Sitemap: drop theme layout posts, author pages and sample CLQ sites. */
add_filter( 'wp_sitemaps_post_types', function ( $types ) {
	unset( $types['liquid-header'], $types['liquid-footer'], $types['liquid-mega-menu'], $types['ld-mega-menu'] );
	return $types;
} );
add_filter( 'wp_sitemaps_add_provider', function ( $provider, $name ) {
	return 'users' === $name ? false : $provider;
}, 10, 2 );
add_filter( 'wp_sitemaps_posts_query_args', function ( $args, $type ) {
	if ( 'ccq_site' === $type ) {
		$ids = get_posts( array( 'post_type' => 'ccq_site', 'post_status' => 'publish', 'numberposts' => 200, 'fields' => 'ids' ) );
		$ids = array_values( array_filter( (array) $ids, function ( $id ) {
			return function_exists( 'ccq_get' ) ? ! empty( ccq_get( $id )['sample'] ) : false;
		} ) );
		if ( $ids ) {
			$args['post__not_in'] = isset( $args['post__not_in'] ) ? array_merge( (array) $args['post__not_in'], $ids ) : $ids;
		}
	}
	return $args;
}, 10, 2 );

/* Editor: "SEO (Google & AI)" box on pages. */
add_action( 'init', function () {
	foreach ( array( '_cgs_seo_title', '_cgs_seo_desc' ) as $k ) {
		register_post_meta( 'page', $k, array(
			'type'              => 'string',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => 'sanitize_text_field',
			'auth_callback'     => function () {
				return current_user_can( 'edit_pages' );
			},
		) );
	}
} );

add_action( 'add_meta_boxes', function () {
	if ( cgseo_off() ) {
		return;
	}
	add_meta_box( 'cgseo-box', 'SEO (Google & AI)', 'cgseo_box', 'page', 'normal', 'high' );
} );

function cgseo_box( $post ) {
	$d   = cgseo_defaults();
	$key = (int) get_option( 'page_on_front' ) === (int) $post->ID ? 'front' : $post->post_name;
	$def = isset( $d[ $key ] ) ? $d[ $key ] : array( $post->post_title . ' | Concepts Group', 'Text from the page' );
	wp_nonce_field( 'cgseo_save', 'cgseo_nonce' );
	$t = get_post_meta( $post->ID, '_cgs_seo_title', true );
	$s = get_post_meta( $post->ID, '_cgs_seo_desc', true );
	?>
	<p style="margin-top:0">Leave blank to use the default shown in grey. Keep titles under 60 characters and descriptions between 120 and 155.</p>
	<p><label for="cgseo-t"><strong>Title</strong> <span class="cgseo-n" data-for="cgseo-t"></span></label><br>
	<input type="text" id="cgseo-t" name="cgseo_title" class="widefat" maxlength="90" value="<?php echo esc_attr( $t ); ?>" placeholder="<?php echo esc_attr( $def[0] ); ?>"></p>
	<p><label for="cgseo-d"><strong>Description</strong> <span class="cgseo-n" data-for="cgseo-d"></span></label><br>
	<textarea id="cgseo-d" name="cgseo_desc" class="widefat" rows="3" maxlength="220" placeholder="<?php echo esc_attr( $def[1] ); ?>"><?php echo esc_textarea( $s ); ?></textarea></p>
	<script>document.querySelectorAll('.cgseo-n').forEach(function(n){var f=document.getElementById(n.dataset.for);function u(){var l=(f.value||f.placeholder).length;n.textContent='('+l+' characters)';}f.addEventListener('input',u);u();});</script>
	<?php
}

add_action( 'save_post_page', function ( $id ) {
	if ( ! isset( $_POST['cgseo_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cgseo_nonce'] ) ), 'cgseo_save' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $id ) ) {
		return;
	}
	foreach ( array( 'cgseo_title' => '_cgs_seo_title', 'cgseo_desc' => '_cgs_seo_desc' ) as $f => $k ) {
		$v = isset( $_POST[ $f ] ) ? sanitize_text_field( wp_unslash( $_POST[ $f ] ) ) : '';
		if ( '' === $v ) {
			delete_post_meta( $id, $k );
		} else {
			update_post_meta( $id, $k, $v );
		}
	}
} );

/*
 * Rank Math: when a page, property or the blog has no title/description of its own in
 * Rank Math, use the defaults above, so the site is never left with "Page – Concepts Group".
 */
function cgseo_rm_fallback( $kind ) {
	$i  = 'title' === $kind ? 0 : 1;
	$id = cgseo_page_id();
	if ( $id ) {
		return '' === trim( (string) get_post_meta( $id, 'rank_math_' . $kind, true ) ) ? cgseo_page_meta( $id )[ $i ] : '';
	}
	if ( is_singular( 'ccq_site' ) && function_exists( 'ccq_get' ) ) {
		$pid = get_queried_object_id();
		if ( '' !== trim( (string) get_post_meta( $pid, 'rank_math_' . $kind, true ) ) ) {
			return '';
		}
		$r = ccq_get( $pid );
		return 'title' === $kind ? ccq_page_title( $r['area'] ) : ccq_description( $r );
	}
	if ( is_post_type_archive( 'ccq_site' ) ) {
		return 'title' === $kind ? 'CLQ & Worker Accommodation in Klang Valley | Concepts Group' : 'Centralised labour quarters (CLQ) and worker accommodation in Klang Valley, Selangor: compare Concepts Group sites, rooms, rent inclusions and request a quote.';
	}
	if ( is_home() && ! is_front_page() ) {
		return cgseo_defaults()['blog'][ $i ];
	}
	return '';
}
add_filter( 'rank_math/frontend/title', function ( $t ) {
	$f = cgseo_rm_fallback( 'title' );
	return '' !== $f ? $f : $t;
}, 20 );
add_filter( 'rank_math/frontend/description', function ( $d ) {
	$f = cgseo_rm_fallback( 'description' );
	return '' !== $f ? $f : $d;
}, 20 );
