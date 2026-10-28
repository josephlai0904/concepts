<?php
/**
 * Concepts Group Suite module: Articles (blog single + listing templates).
 * Loaded by ../../concepts-group-suite.php. Not a standalone plugin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CGA_VERSION', '1.2.3' );
define( 'CGA_URL', plugin_dir_url( __FILE__ ) );
require_once __DIR__ . '/parts.php';

/* ------------------------------------------------------------------
 * Headings: give every H2/H3 an id and collect them for the sidebar.
 * ---------------------------------------------------------------- */
function cga_headings( $html ) {
	$toc  = array();
	$used = array();
	$html = preg_replace_callback(
		'#<(h[23])(\s[^>]*)?>(.*?)</\1>#is',
		function ( $m ) use ( &$toc, &$used ) {
			$attrs = isset( $m[2] ) ? $m[2] : '';
			$text  = trim( wp_strip_all_tags( $m[3] ) );
			if ( '' === $text ) {
				return $m[0];
			}
			if ( preg_match( '/\sid=["\']([^"\']+)["\']/i', $attrs, $idm ) ) {
				$id = $idm[1];
			} else {
				$base = sanitize_title( $text );
				$base = '' !== $base ? $base : 'section';
				$id   = $base;
				$n    = 2;
				while ( isset( $used[ $id ] ) ) {
					$id = $base . '-' . $n++;
				}
				$attrs .= ' id="' . esc_attr( $id ) . '"';
			}
			$used[ $id ] = true;
			$toc[]       = array( 'level' => strtolower( $m[1] ), 'id' => $id, 'text' => html_entity_decode( $text, ENT_QUOTES, 'UTF-8' ) );
			return '<' . $m[1] . $attrs . '>' . $m[3] . '</' . $m[1] . '>';
		},
		$html
	);
	return array( $html, $toc );
}

function cga_reading_minutes( $html ) {
	$words = str_word_count( wp_strip_all_tags( $html ) );
	return max( 1, (int) round( $words / 220 ) );
}

/* ------------------------------------------------------------------
 * Template: single posts use the article layout (theme header/footer kept).
 * ---------------------------------------------------------------- */
add_filter( 'template_include', function ( $t ) {
	if ( is_singular( 'post' ) ) {
		return plugin_dir_path( __FILE__ ) . 'single.php';
	}
	if ( cga_is_list() ) {
		return plugin_dir_path( __FILE__ ) . 'archive.php';
	}
	return $t;
}, 99 );

/* Blog listing: the posts page (/blog/) and category pages. */
function cga_is_list() {
	return ( is_home() && ! is_front_page() ) || is_category();
}

/* Nine articles per page (three rows of three). */
add_action( 'pre_get_posts', function ( $q ) {
	if ( ! is_admin() && $q->is_main_query() && ( ( $q->is_home() && ! $q->is_front_page() ) || $q->is_category() ) ) {
		$q->set( 'posts_per_page', 9 );
	}
} );

add_action( 'wp_enqueue_scripts', function () {
	if ( ! is_singular( 'post' ) && ! cga_is_list() ) {
		return;
	}
	wp_enqueue_style( 'cgs-fonts', 'https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,600;12..96,700&family=Inter:wght@400;500;600;700&display=swap', array(), null );
	wp_enqueue_style( 'cga', CGA_URL . 'assets/article.css', array( 'cgs-fonts' ), CGA_VERSION );
	if ( is_singular( 'post' ) ) {
		wp_enqueue_script( 'cga', CGA_URL . 'assets/article.js', array(), CGA_VERSION, true );
	}
}, 50 );

/* ------------------------------------------------------------------
 * SEO: the imported Yoast title/description (meta _cga_seo_title / _cga_seo_desc),
 * Article + BreadcrumbList JSON-LD. Skipped when an SEO plugin is active.
 * ---------------------------------------------------------------- */
function cga_seo_plugin() {
	return defined( 'WPSEO_VERSION' ) || class_exists( 'RankMath' ) || defined( 'AIOSEO_VERSION' ) || defined( 'SEOPRESS_VERSION' );
}

add_filter( 'pre_get_document_title', function ( $t ) {
	if ( is_singular( 'post' ) && ! cga_seo_plugin() ) {
		$s = get_post_meta( get_the_ID(), '_cga_seo_title', true );
		$s = $s ? $s : wp_strip_all_tags( get_the_title() );
		return false === stripos( $s, 'Concepts' ) ? $s . ' | Concepts Group' : $s;
	}
	return $t;
}, 20 );

add_filter( 'pre_get_document_title', function ( $t ) {
	if ( cga_is_list() && ! cga_seo_plugin() ) {
		$paged = max( 1, (int) get_query_var( 'paged' ) );
		$base  = is_category() ? single_cat_title( '', false ) . ' | Concepts Group Blog' : 'Blog: Workforce, Hostel and Recruitment Guides | Concepts Group';
		return $paged > 1 ? $base . ' (page ' . $paged . ')' : $base;
	}
	return $t;
}, 21 );

add_action( 'wp_head', function () {
	if ( ! is_singular( 'post' ) || cga_seo_plugin() ) {
		return;
	}
	$id   = get_queried_object_id();
	$desc = get_post_meta( $id, '_cga_seo_desc', true );
	$desc = $desc ? $desc : wp_trim_words( wp_strip_all_tags( get_post_field( 'post_content', $id ) ), 28, '…' );
	echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
	$home = home_url( '/' );
	$url  = get_permalink( $id );
	$img  = get_the_post_thumbnail_url( $id, 'full' );
	$blog = get_option( 'page_for_posts' ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url( '/blog/' );
	$g    = array(
		array( '@type' => 'Organization', '@id' => $home . '#organization', 'name' => 'Concepts Group', 'url' => $home ),
		array_filter( array(
			'@type'            => 'Article',
			'@id'              => $url . '#article',
			'headline'         => wp_strip_all_tags( get_the_title( $id ) ),
			'description'      => $desc,
			'image'            => $img ? $img : null,
			'datePublished'    => get_the_date( 'c', $id ),
			'dateModified'     => get_the_modified_date( 'c', $id ),
			'author'           => array( '@id' => $home . '#organization' ),
			'publisher'        => array( '@id' => $home . '#organization' ),
			'mainEntityOfPage' => $url,
		) ),
		array( '@type' => 'BreadcrumbList', 'itemListElement' => array(
			array( '@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $home ),
			array( '@type' => 'ListItem', 'position' => 2, 'name' => 'Blog', 'item' => $blog ),
			array( '@type' => 'ListItem', 'position' => 3, 'name' => wp_strip_all_tags( get_the_title( $id ) ), 'item' => $url ),
		) ),
	);
	echo '<script type="application/ld+json">' . wp_json_encode( array( '@context' => 'https://schema.org', '@graph' => $g ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}, 5 );
