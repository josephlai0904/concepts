<?php
/* Minimal fake WordPress for load / render smoke tests. Hooks and shortcodes are real; data is in-memory. */
define( 'ABSPATH', __DIR__ . '/' );
define( 'MINUTE_IN_SECONDS', 60 );
define( 'HOUR_IN_SECONDS', 3600 );
define( 'OBJECT', 'OBJECT' );

$GLOBALS['ISSUES'] = array();
set_error_handler( function ( $no, $str, $file, $line ) {
	$GLOBALS['ISSUES'][] = "PHP[$no] $str @ " . basename( $file ) . ":$line";
	return true;
} );

/* ---- hooks ---- */
$GLOBALS['H'] = array(); $GLOBALS['DID'] = array(); $GLOBALS['SC'] = array();
function add_filter( $t, $cb, $p = 10, $n = 1 ) { $GLOBALS['H'][ $t ][ $p ][] = array( $cb, $n ); return true; }
function add_action( $t, $cb, $p = 10, $n = 1 ) { return add_filter( $t, $cb, $p, $n ); }
function apply_filters( $t, $v = null, ...$a ) {
	if ( empty( $GLOBALS['H'][ $t ] ) ) return $v;
	ksort( $GLOBALS['H'][ $t ] );
	foreach ( $GLOBALS['H'][ $t ] as $list ) foreach ( $list as $h ) { $v = call_user_func_array( $h[0], array_slice( array_merge( array( $v ), $a ), 0, max( 1, $h[1] ) ) ); }
	return $v;
}
function do_action( $t, ...$a ) {
	$GLOBALS['DID'][ $t ] = ( $GLOBALS['DID'][ $t ] ?? 0 ) + 1;
	if ( empty( $GLOBALS['H'][ $t ] ) ) return;
	ksort( $GLOBALS['H'][ $t ] );
	foreach ( $GLOBALS['H'][ $t ] as $list ) foreach ( $list as $h ) call_user_func_array( $h[0], array_slice( $a, 0, $h[1] ) );
}
function did_action( $t ) { return $GLOBALS['DID'][ $t ] ?? 0; }
$GLOBALS['ACT'] = array(); $GLOBALS['DEACT'] = array();
function register_activation_hook( $f, $cb ) { $GLOBALS['ACT'][] = $cb; }
function register_deactivation_hook( $f, $cb ) { $GLOBALS['DEACT'][] = $cb; }
function add_shortcode( $t, $cb ) { $GLOBALS['SC'][ $t ] = $cb; }
function shortcode_atts( $d, $a, $s = '' ) { $a = (array) $a; $o = array(); foreach ( $d as $k => $v ) $o[ $k ] = array_key_exists( $k, $a ) ? $a[ $k ] : $v; return $o; }
function do_shortcode( $c ) { return preg_replace_callback( '/\[(\w+)([^\]]*)\]/', function ( $m ) { return isset( $GLOBALS['SC'][ $m[1] ] ) ? call_user_func( $GLOBALS['SC'][ $m[1] ], array(), '', $m[1] ) : $m[0]; }, (string) $c ); }
function has_shortcode( $c, $t ) { return false !== strpos( (string) $c, '[' . $t ); }

/* ---- escaping / sanitising ---- */
function esc_html( $s ) { return htmlspecialchars( (string) $s, ENT_QUOTES ); }
function esc_attr( $s ) { return esc_html( $s ); }
function esc_textarea( $s ) { return esc_html( $s ); }
function esc_url( $s, $p = null ) { return (string) $s; }
function esc_url_raw( $s ) { return (string) $s; }
function wp_strip_all_tags( $s ) { return trim( strip_tags( (string) $s ) ); }
function sanitize_title( $s ) { return trim( preg_replace( '/[^a-z0-9]+/', '-', strtolower( (string) $s ) ), '-' ); }
function sanitize_text_field( $s ) { return trim( strip_tags( (string) $s ) ); }
function sanitize_textarea_field( $s ) { return trim( strip_tags( (string) $s ) ); }
function sanitize_email( $s ) { return trim( (string) $s ); }
function is_email( $s ) { return filter_var( $s, FILTER_VALIDATE_EMAIL ) ? $s : false; }
function wp_unslash( $s ) { return $s; }
function wp_json_encode( $d, $f = 0 ) { return json_encode( $d, $f ); }
function wp_trim_words( $t, $n = 55, $m = '…' ) { $w = preg_split( '/\s+/', trim( (string) $t ) ); return implode( ' ', array_slice( $w, 0, $n ) ) . ( count( $w ) > $n ? $m : '' ); }
function wp_parse_url( $u, $c = -1 ) { return parse_url( $u, $c ); }
function wp_http_validate_url( $u ) { return filter_var( $u, FILTER_VALIDATE_URL ) ? $u : false; }
function number_format_i18n( $n, $d = 0 ) { return number_format( $n, $d ); }
function date_i18n( $f, $ts = false ) { return gmdate( $f, $ts ?: time() ); }
function wp_date( $f, $ts = null ) { return gmdate( $f, $ts ?? time() ); }
function current_time( $t ) { return gmdate( 'Y-m-d H:i:s' ); }
function wp_list_pluck( $l, $f ) { return array_map( function ( $x ) use ( $f ) { return is_object( $x ) ? $x->$f : $x[ $f ]; }, (array) $l ); }
function selected( $a, $b = true, $e = true ) { $r = (string) $a === (string) $b ? ' selected' : ''; if ( $e ) echo $r; return $r; }
function checked( $a, $b = true, $e = true ) { $r = (string) $a === (string) $b ? ' checked' : ''; if ( $e ) echo $r; return $r; }
function is_wp_error( $x ) { return false; }
function wp_die( $m = '' ) { throw new Exception( 'wp_die: ' . $m ); }

/* ---- urls / env ---- */
function home_url( $p = '' ) { return 'https://concepts.test' . $p; }
function admin_url( $p = '' ) { return 'https://concepts.test/wp-admin/' . $p; }
function rest_url( $p = '' ) { return 'https://concepts.test/wp-json/' . $p; }
function plugin_dir_path( $f ) { return dirname( $f ) . '/'; }
function plugin_dir_url( $f ) { return 'https://concepts.test/wp-content/plugins/' . basename( dirname( $f ) ) . '/'; }
function plugins_url( $p, $f ) { return plugin_dir_url( $f ) . $p; }
function plugin_basename( $f ) { return basename( dirname( $f ) ) . '/' . basename( $f ); }
$GLOBALS['IS_ADMIN'] = true;
function is_admin() { return $GLOBALS['IS_ADMIN']; }
function current_user_can( $c ) { return true; }
function wp_nonce_url( $u ) { return $u; }
function wp_nonce_field() { echo '<input type="hidden">'; }
function wp_verify_nonce() { return true; }
function check_admin_referer() { return true; }
function submit_button() { echo '<button>Save</button>'; }
function wp_safe_redirect() {}
function get_current_screen() { return (object) array( 'post_type' => 'ccq_site', 'base' => 'post', 'id' => 'ccq_site' ); }

/* ---- options / transients ---- */
$GLOBALS['OPT'] = array( 'page_for_posts' => 740, 'admin_email' => 'a@b.c' );
function get_option( $k, $d = false ) { return $GLOBALS['OPT'][ $k ] ?? $d; }
function update_option( $k, $v ) { $GLOBALS['OPT'][ $k ] = $v; return true; }
function get_transient( $k ) { return $GLOBALS['OPT'][ 't_' . $k ] ?? false; }
function set_transient( $k, $v, $e = 0 ) { $GLOBALS['OPT'][ 't_' . $k ] = $v; return true; }
function delete_transient( $k ) { unset( $GLOBALS['OPT'][ 't_' . $k ] ); return true; }
function flush_rewrite_rules() { $GLOBALS['FLUSHED'] = ( $GLOBALS['FLUSHED'] ?? 0 ) + 1; }

/* ---- assets ---- */
$GLOBALS['REG'] = array();
function wp_register_style( $h, ...$a ) { $GLOBALS['REG'][ 'css:' . $h ] = 1; return true; }
function wp_enqueue_style( $h, ...$a ) { $GLOBALS['REG'][ 'css:' . $h ] = 1; }
function wp_add_inline_style( $h, $c ) { return true; }
function wp_register_script( $h, ...$a ) { $GLOBALS['REG'][ 'js:' . $h ] = 1; return true; }
function wp_enqueue_script( $h, ...$a ) { $GLOBALS['REG'][ 'js:' . $h ] = 1; }
function wp_add_inline_script( $h, $c, $p = 'after' ) { $GLOBALS['INLINE'][ $h ][] = $c; return true; }
function wp_script_is( $h, $l = 'enqueued' ) { return isset( $GLOBALS['REG'][ 'js:' . $h ] ); }
function wp_localize_script() { return true; }
function wp_enqueue_media() {}

/* ---- posts (in-memory) ---- */
$GLOBALS['POSTS'] = array(); $GLOBALS['META'] = array(); $GLOBALS['NEXT'] = 100;
function wp_insert_post( $a ) { $id = $GLOBALS['NEXT']++; $GLOBALS['POSTS'][ $id ] = (object) array_merge( array( 'ID' => $id, 'post_content' => '', 'post_excerpt' => '', 'post_name' => sanitize_title( $a['post_title'] ?? '' ), 'post_status' => 'publish', 'menu_order' => 0, 'post_date' => '2026-10-01' ), $a, array( 'ID' => $id ) ); return $id; }
function get_post( $p = null, $o = OBJECT ) {
	if ( null === $p ) $p = $GLOBALS['CUR'] ?? 0;
	if ( is_object( $p ) ) return $p;
	return $GLOBALS['POSTS'][ (int) $p ] ?? null;
}
function get_posts( $a = array() ) {
	$out = array();
	foreach ( $GLOBALS['POSTS'] as $p ) {
		if ( isset( $a['post_type'] ) && $p->post_type !== $a['post_type'] ) continue;
		if ( isset( $a['post_status'] ) && 'any' !== $a['post_status'] && $p->post_status !== $a['post_status'] ) continue;
		$out[] = ( $a['fields'] ?? '' ) === 'ids' ? $p->ID : $p;
	}
	return $out;
}
function get_page_by_path( $slug, $o = OBJECT, $t = 'page' ) { foreach ( $GLOBALS['POSTS'] as $p ) if ( $p->post_name === $slug && $p->post_type === $t ) return $p; return null; }
function get_post_meta( $id, $k = '', $s = false ) { $v = $GLOBALS['META'][ $id ][ $k ] ?? ''; return $s ? $v : array( $v ); }
function update_post_meta( $id, $k, $v ) { $GLOBALS['META'][ $id ][ $k ] = $v; return true; }
function get_post_field( $f, $id ) { $p = get_post( $id ); return $p ? ( $p->$f ?? '' ) : ''; }
function get_the_title( $p = null ) { $p = get_post( $p ); return $p ? $p->post_title : ''; }
function get_permalink( $p = null ) { $p = get_post( $p ); return $p ? home_url( '/x/' . $p->post_name . '/' ) : home_url( '/' ); }
function get_the_ID() { return $GLOBALS['CUR'] ?? 0; }
function get_queried_object_id() { return $GLOBALS['CUR'] ?? 0; }
function get_the_date( $f = '', $p = null ) { return gmdate( $f ?: 'j F Y' ); }
function get_the_modified_date( $f = '', $p = null ) { return gmdate( $f ?: 'j F Y' ); }
function get_the_content() { return get_post_field( 'post_content', get_the_ID() ); }
function the_title() { echo esc_html( get_the_title() ); }
function has_post_thumbnail( $p = null ) { return true; }
function get_the_post_thumbnail( $p = null, $s = '', $a = array() ) { return '<img src="t.jpg">'; }
function the_post_thumbnail( $s = '', $a = array() ) { echo '<img src="t.jpg">'; }
function get_the_post_thumbnail_url( $p = null, $s = '' ) { return 'https://concepts.test/t.jpg'; }
function get_the_category( $p = null ) { return array( (object) array( 'term_id' => 1, 'name' => 'News', 'slug' => 'news' ) ); }
function get_category_link( $c ) { return home_url( '/category/news/' ); }
function single_cat_title( $p = '', $d = true ) { return 'News'; }
function category_description() { return '<p>About news.</p>'; }
function paginate_links( $a = array() ) { $l = array( '<a class="page-numbers">2</a>' ); return ( $a['type'] ?? '' ) === 'array' ? $l : implode( '', $l ); }
function get_query_var( $k, $d = '' ) { return $d; }
function register_post_type( $t, $a ) { $GLOBALS['PT'][ $t ] = $a; }
function register_rest_route( $ns, $r, $a ) { $GLOBALS['ROUTES'][ $ns . $r ] = $a; }
function get_post_type_archive_link( $t ) { return home_url( '/properties/' ); }
function attachment_url_to_postid( $u ) { return 0; }
function wp_get_attachment_image_src( $id, $s = '' ) { return array( 'https://concepts.test/i' . $id . '.jpg', 800, 600, false ); }
function wp_get_attachment_image_url( $id, $s = '' ) { return 999 === (int) $id ? 'https://concepts.test/wp-content/uploads/2026/10/Concepts-Group-White-Logo.webp' : 'https://concepts.test/i' . $id . '.jpg'; }
function wp_get_attachment_url( $id ) { return 'https://concepts.test/i' . $id . '.jpg'; }
function wp_get_attachment_caption( $id ) { return 'Caption'; }
function wp_mail( ...$a ) { $GLOBALS['MAIL'][] = $a; return true; }
function add_meta_box( $id, $t, $cb, $s = null, $c = 'advanced', $p = 'default', $args = null ) { $GLOBALS['MB'][ $id ] = $cb; }
function add_submenu_page( ...$a ) { return 'hook'; }

/* conditional tags (set per test) */
$GLOBALS['Q'] = array();
foreach ( array( 'is_singular', 'is_home', 'is_front_page', 'is_category', 'is_post_type_archive', 'is_page', 'is_404', 'is_search' ) as $fn ) {
	eval( 'function ' . $fn . '( $t = null ) { $v = $GLOBALS["Q"]["' . $fn . '"] ?? false; return is_array( $v ) ? ( null === $t || in_array( $t, $v, true ) ) : (bool) $v; }' );
}
/* The Loop: one post. */
$GLOBALS['LOOP'] = 0;
function have_posts() { return $GLOBALS['LOOP']++ < 1; }
function the_post() {}
function get_header() { echo '<header-stub>'; }
function get_footer() { echo '<footer-stub>'; }

/* nav menus */
function wp_get_nav_menus() { return array( (object) array( 'term_id' => 5, 'name' => 'Main' ) ); }
function wp_get_nav_menu_object( $m ) { return (object) array( 'term_id' => 5, 'name' => 'Main' ); }
function get_nav_menu_locations() { return array( 'primary' => 5 ); }
function wp_get_nav_menu_items( $m ) { return array( (object) array( 'ID' => 1, 'title' => 'Home', 'url' => '/', 'menu_item_parent' => 0, 'object_id' => 1 ), (object) array( 'ID' => 2, 'title' => 'Sub', 'url' => '/s', 'menu_item_parent' => 1, 'object_id' => 2 ) ); }

/* plugin admin */
$GLOBALS['INSTALLED'] = array();
$GLOBALS['ACTIVE'] = array();
function get_plugins() { return $GLOBALS['INSTALLED']; }
function is_plugin_active( $p ) { return in_array( $p, $GLOBALS['ACTIVE'], true ); }
function deactivate_plugins( $ps, $silent = false ) { $GLOBALS['ACTIVE'] = array_values( array_diff( $GLOBALS['ACTIVE'], (array) $ps ) ); }

/* WPBakery */
$GLOBALS['VC'] = array();
function vc_map( $a ) { $GLOBALS['VC'][] = $a['base']; }
function vc_param_group_parse_atts( $s ) { $d = json_decode( urldecode( $s ), true ); return is_array( $d ) ? $d : array(); }

class WP_REST_Request { private $p; function __construct( $p ) { $this->p = $p; } function get_param( $k ) { return $this->p[ $k ] ?? null; } }
class WP_REST_Response { public $data; public $status; function __construct( $d, $s ) { $this->data = $d; $this->status = $s; } }
/* SEO helpers */
function register_post_meta( $t, $k, $a ) { $GLOBALS['REGMETA'][ $t ][ $k ] = $a; return true; }
function get_queried_object() { return get_post( $GLOBALS['CUR'] ?? 0 ); }
function get_post_thumbnail_id( $p = null ) { return 0; }
function get_bloginfo( $k = '' ) { return 'name' === $k ? 'Concepts Group' : ''; }
function strip_shortcodes( $c ) { return preg_replace( '/\[[^\]]*\]/', '', (string) $c ); }
function get_term_link( $t ) { return home_url( '/category/news/' ); }
function single_term_title( $p = '', $d = true ) { return 'News'; }
function term_description() { return ''; }
