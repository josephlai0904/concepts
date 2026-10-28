<?php
/**
 * Admin: the site editor (fact sheet with galleries, map pin and repeaters),
 * saving, and the hub page settings. Loaded only in wp-admin.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'add_meta_boxes_ccq_site', function () {
	add_meta_box( 'ccq_fact', 'Site fact sheet: feeds the page, the other-site cards and the structured data', 'ccq_metabox', 'ccq_site', 'normal', 'high' );
} );

add_action( 'admin_enqueue_scripts', function ( $hook ) {
	$s = get_current_screen();
	if ( ! $s || 'ccq_site' !== $s->post_type || ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_style( 'leaflet', 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css', array(), '1.9.4' );
	wp_enqueue_script( 'leaflet', 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js', array(), '1.9.4', true );
	wp_enqueue_style( 'ccq-admin', CCQ_URL . 'assets/admin.css', array(), CCQ_VERSION );
	wp_enqueue_script( 'ccq-admin', CCQ_URL . 'assets/admin.js', array( 'jquery', 'jquery-ui-sortable', 'leaflet' ), CCQ_VERSION, true );
} );

/* One input for a field or a repeater cell. $name is the full input name. */
function ccq_input( $type, $name, $id, $v, $hint ) {
	switch ( $type ) {
		case 'select':
			$o = '<select id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '">';
			foreach ( $hint as $ov => $ol ) {
				$o .= '<option value="' . esc_attr( $ov ) . '"' . selected( $v, $ov, false ) . '>' . esc_html( $ol ) . '</option>';
			}
			return $o . '</select>';
		case 'image':
			return ccq_media_field( $name, $v, false );
		case 'textarea':
			return '<textarea id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" rows="3" placeholder="' . esc_attr( $hint ) . '">' . esc_textarea( $v ) . '</textarea>';
		case 'number':
		case 'date':
			return '<input type="' . $type . '" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $v ) . '" placeholder="' . esc_attr( $hint ) . '"' . ( 'number' === $type ? ' min="0" step="any"' : '' ) . '>';
		default:
			return '<input type="text" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $v ) . '" placeholder="' . esc_attr( $hint ) . '">';
	}
}

/* Image or gallery picker: thumbnails (drag to reorder, x to remove) + hidden comma list. */
function ccq_media_field( $name, $v, $multiple ) {
	$name = false === strpos( $name, '[' ) ? 'ccq[' . $name . ']' : $name;
	$o = '<div class="ccq-media" data-multiple="' . ( $multiple ? '1' : '0' ) . '"><input type="hidden" name="' . esc_attr( $name ) . '" value="' . esc_attr( $v ) . '"><ul class="ccq-thumbs">';
	foreach ( array_filter( array_map( 'trim', explode( ',', (string) $v ) ) ) as $x ) {
		$m = ccq_media_list( $x, 'thumbnail' );
		if ( $m ) {
			$o .= '<li data-v="' . esc_attr( $x ) . '"><img src="' . esc_url( $m[0]['src'] ) . '" alt=""><button type="button" class="ccq-x" aria-label="Remove image">×</button></li>';
		}
	}
	return $o . '</ul><button type="button" class="button ccq-pick">' . ( $multiple ? 'Add images' : 'Choose image' ) . '</button></div>';
}

/* Repeater: rows of cells, drag handle, remove, and an "Add row" template. */
function ccq_repeater_field( $k, $rows, $cols ) {
	$row = function ( $i, $vals ) use ( $k, $cols ) {
		$o = '<div class="ccq-rrow"><span class="ccq-drag" title="Drag to reorder" aria-hidden="true">⋮⋮</span><div class="ccq-rcells">';
		foreach ( $cols as $c => $def ) {
			$id = 'ccq-' . $k . '-' . $i . '-' . $c;
			$o .= '<label class="ccq-rc ccq-rc-' . esc_attr( $def[0] ) . ' ccq-col-' . esc_attr( $c ) . '"><span>' . esc_html( $def[1] ) . '</span>' . ccq_input( $def[0], 'ccq[' . $k . '][' . $i . '][' . $c . ']', $id, isset( $vals[ $c ] ) ? $vals[ $c ] : '', $def[2] ) . '</label>';
		}
		return $o . '</div><button type="button" class="button-link ccq-del" aria-label="Remove row">Remove</button></div>';
	};
	$o = '<div class="ccq-rep" data-key="' . esc_attr( $k ) . '"><div class="ccq-rrows">';
	foreach ( is_array( $rows ) ? array_values( $rows ) : array() as $i => $vals ) {
		$o .= $row( $i, $vals );
	}
	$o .= '</div><script type="text/template" class="ccq-tpl">' . $row( '__i__', array() ) . '</script>';
	return $o . '<button type="button" class="button ccq-add">+ Add row</button></div>';
}

function ccq_metabox( $post ) {
	$raw = get_post_meta( $post->ID, '_ccq', true );
	$raw = is_array( $raw ) ? $raw : array();
	wp_nonce_field( 'ccq_save', 'ccq_nonce' );
	echo '<div class="ccq-mb">';
	if ( ! empty( $raw['sample'] ) ) {
		echo '<p class="ccq-sample-note">This site has <strong>sample content</strong>. Its page is hidden from Google. Replace the figures, then untick "Sample content" under Identity.</p>';
	}
	$first = true;
	foreach ( ccq_fields() as $group => $fields ) {
		echo '<details class="ccq-grp"' . ( $first ? ' open' : '' ) . '><summary>' . esc_html( $group ) . '</summary><div class="ccq-grid">';
		$first = false;
		foreach ( $fields as $k => $f ) {
			list( $type, $label, $hint ) = $f;
			$v    = isset( $raw[ $k ] ) ? $raw[ $k ] : '';
			$wide = in_array( $type, array( 'textarea', 'gallery', 'repeater', 'map', 'checkbox' ), true ) ? ' ccq-wide' : '';
			echo '<div class="ccq-fld' . $wide . '">';
			if ( 'checkbox' === $type ) {
				echo '<label class="ccq-check"><input type="checkbox" name="ccq[' . esc_attr( $k ) . ']" value="1"' . checked( $v, '1', false ) . '> ' . esc_html( $label ) . '</label>';
			} elseif ( 'image' === $type || 'gallery' === $type ) {
				echo '<span class="ccq-lbl">' . esc_html( $label ) . '</span>' . ccq_media_field( $k, $v, 'gallery' === $type ); // Escaped inside.
			} elseif ( 'repeater' === $type ) {
				echo '<span class="ccq-lbl">' . esc_html( $label ) . '</span>' . ccq_repeater_field( $k, $v, $hint ); // Escaped inside.
			} elseif ( 'map' === $type ) {
				$lat = isset( $raw['lat'] ) ? $raw['lat'] : '';
				$lng = isset( $raw['lng'] ) ? $raw['lng'] : '';
				echo '<span class="ccq-lbl">' . esc_html( $label ) . '</span><div class="ccq-mapbox"><div id="ccq-map-pick"></div><div class="ccq-coords"><label>Latitude <input type="text" id="ccq-lat" name="ccq[lat]" value="' . esc_attr( $lat ) . '"></label><label>Longitude <input type="text" id="ccq-lng" name="ccq[lng]" value="' . esc_attr( $lng ) . '"></label></div></div>';
			} else {
				echo '<label class="ccq-lbl" for="ccq-' . esc_attr( $k ) . '">' . esc_html( $label ) . '</label>' . ccq_input( $type, 'ccq[' . $k . ']', 'ccq-' . $k, $v, $hint ); // Escaped inside.
			}
			echo '</div>';
		}
		echo '</div></details>';
	}
	echo '</div>';
}

function ccq_clean( $type, $v ) {
	$v = is_array( $v ) ? '' : (string) $v;
	switch ( $type ) {
		case 'textarea':
			return sanitize_textarea_field( $v );
		case 'number':
			return '' === trim( $v ) ? '' : (string) abs( (float) $v );
		case 'checkbox':
			return $v ? '1' : '';
		case 'date':
			return preg_match( '/^\d{4}-\d{2}-\d{2}$/', $v ) ? $v : '';
		case 'coord':
			return is_numeric( $v ) ? (string) (float) $v : '';
		case 'image':
		case 'gallery':
			$keep = array();
			foreach ( array_filter( array_map( 'trim', explode( ',', $v ) ) ) as $x ) {
				if ( ctype_digit( $x ) || wp_http_validate_url( $x ) ) {
					$keep[] = ctype_digit( $x ) ? $x : esc_url_raw( $x );
				}
			}
			return implode( ',', 'image' === $type ? array_slice( $keep, 0, 1 ) : $keep );
		default:
			return sanitize_text_field( $v );
	}
}

add_action( 'save_post_ccq_site', function ( $post_id ) {
	if ( ! isset( $_POST['ccq_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ccq_nonce'] ) ), 'ccq_save' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$in  = isset( $_POST['ccq'] ) && is_array( $_POST['ccq'] ) ? wp_unslash( $_POST['ccq'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
	$out = array();
	foreach ( ccq_fields() as $fields ) {
		foreach ( $fields as $k => $f ) {
			if ( 'map' === $f[0] ) {
				$out['lat'] = ccq_clean( 'coord', isset( $in['lat'] ) ? $in['lat'] : '' );
				$out['lng'] = ccq_clean( 'coord', isset( $in['lng'] ) ? $in['lng'] : '' );
			} elseif ( 'repeater' === $f[0] ) {
				$rows = array();
				foreach ( ( isset( $in[ $k ] ) && is_array( $in[ $k ] ) ) ? $in[ $k ] : array() as $i => $row ) {
					if ( '__i__' === (string) $i || ! is_array( $row ) ) {
						continue;
					}
					$clean = array();
					foreach ( $f[2] as $c => $def ) {
						$cv          = isset( $row[ $c ] ) ? $row[ $c ] : '';
						$clean[ $c ] = 'select' === $def[0] ? ( isset( $def[2][ $cv ] ) ? $cv : key( $def[2] ) ) : ccq_clean( $def[0], $cv );
					}
					if ( '' !== trim( implode( '', array_diff_key( $clean, array_filter( $f[2], function ( $d ) { return 'select' === $d[0]; } ) ) ) ) ) {
						$rows[] = $clean;
					}
				}
				$out[ $k ] = $rows;
			} elseif ( 'select' === $f[0] ) {
				$v         = isset( $in[ $k ] ) ? (string) $in[ $k ] : '';
				$out[ $k ] = isset( $f[2][ $v ] ) ? $v : key( $f[2] );
			} else {
				$out[ $k ] = ccq_clean( $f[0], isset( $in[ $k ] ) ? $in[ $k ] : '' );
			}
		}
	}
	update_post_meta( $post_id, '_ccq', $out );
} );

/* List table: show status, beds and the sample flag at a glance. */
add_filter( 'manage_ccq_site_posts_columns', function ( $c ) {
	return array_slice( $c, 0, 2, true ) + array( 'ccq_status' => 'Status', 'ccq_beds' => 'Beds', 'ccq_checked' => 'Figures checked' ) + array_slice( $c, 2, null, true );
} );
add_action( 'manage_ccq_site_posts_custom_column', function ( $col, $id ) {
	$r = ccq_get( $id );
	if ( 'ccq_status' === $col ) {
		echo esc_html( ccq_status_label( $r['status'] ) ) . ( $r['sample'] ? ' <strong style="color:#996800">· Sample</strong>' : '' );
	} elseif ( 'ccq_beds' === $col ) {
		echo esc_html( ccq_beds_label( $r ) );
	} elseif ( 'ccq_checked' === $col ) {
		echo esc_html( ccq_date( $r['last_checked'] ) );
	}
}, 10, 2 );

/* Hub page (/centralised-labour-quarters/) heading and intro. */
add_action( 'admin_menu', function () {
	add_submenu_page( 'edit.php?post_type=ccq_site', 'Properties page', 'Properties page', 'edit_pages', 'ccq-hub', function () {
		$h = ccq_hub();
		?>
<div class="wrap"><h1>Properties page</h1>
<p>The page at <a href="<?php echo esc_url( get_post_type_archive_link( 'ccq_site' ) ); ?>" target="_blank"><?php echo esc_html( get_post_type_archive_link( 'ccq_site' ) ); ?></a> lists every published site in the order set by each site's "Order" (Page Attributes). Add it to the menu under Appearance &gt; Menus &gt; Custom Links.</p>
<?php if ( isset( $_GET['saved'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification ?><div class="notice notice-success"><p>Saved.</p></div><?php endif; ?>
<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
<input type="hidden" name="action" value="ccq_hub"><?php wp_nonce_field( 'ccq_hub' ); ?>
<table class="form-table"><tr><th><label for="ccq-hub-title">Heading (H1)</label></th><td><input class="large-text" id="ccq-hub-title" name="title" value="<?php echo esc_attr( $h['title'] ); ?>"></td></tr>
<tr><th><label for="ccq-hub-intro">Intro</label></th><td><textarea class="large-text" rows="3" id="ccq-hub-intro" name="intro"><?php echo esc_textarea( $h['intro'] ); ?></textarea></td></tr></table>
<?php submit_button(); ?></form></div>
		<?php
	} );
} );
add_action( 'admin_post_ccq_hub', function () {
	if ( ! current_user_can( 'edit_pages' ) || ! check_admin_referer( 'ccq_hub' ) ) {
		wp_die( 'Not allowed.' );
	}
	update_option( 'ccq_hub', array(
		'title' => sanitize_text_field( wp_unslash( isset( $_POST['title'] ) ? $_POST['title'] : '' ) ),
		'intro' => sanitize_textarea_field( wp_unslash( isset( $_POST['intro'] ) ? $_POST['intro'] : '' ) ),
	) );
	wp_safe_redirect( admin_url( 'edit.php?post_type=ccq_site&page=ccq-hub&saved=1' ) );
	exit;
} );
