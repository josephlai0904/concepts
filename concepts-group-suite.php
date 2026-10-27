<?php
/**
 * Plugin Name:       Concepts Group Suite
 * Plugin URI:        https://manyproject.my
 * Description:       All-in-one plugin for the Concepts Group site. Combines Concepts Group Sections (global CSS, header, hero, footer and page sections), Concepts Articles (blog article and listing templates) and Concepts CLQ Sites (property pages, hub, enquiry form). Replaces the three separate plugins: they are switched off automatically on activation.
 * Version:           2.7.1
 * Requires at least: 5.9
 * Requires PHP:      7.4
 * Author:            Many Project (Web App Marketing Sdn Bhd)
 * Author URI:        https://manyproject.my
 * Text Domain:       concepts-group-suite
 * License:           Proprietary
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CG_SUITE_VERSION', '2.7.1' );
define( 'CG_SUITE_FILE', __FILE__ );
define( 'CG_SUITE_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Modules, in load order. Sections first: the other two reuse its
 * fonts handle and GSAP scripts.
 *
 * 'const'  : constant the module defines. If it already exists, the old
 *            standalone plugin is still active, so the module is skipped
 *            (loading it twice would redeclare functions and crash).
 * 'legacy' : Plugin Name of the old standalone plugin.
 */
function cg_suite_modules() {
	return array(
		'sections'  => array( 'file' => 'modules/sections/concepts-sections.php', 'const' => 'CGS_VERSION', 'legacy' => 'Concepts Group Sections' ),
		'articles'  => array( 'file' => 'modules/articles/concepts-articles.php', 'const' => 'CGA_VERSION', 'legacy' => 'Concepts Articles' ),
		'clq-sites' => array( 'file' => 'modules/clq-sites/concepts-clq-sites.php', 'const' => 'CCQ_VERSION', 'legacy' => 'Concepts CLQ Sites' ),
		'seo'       => array( 'file' => 'modules/seo/concepts-seo.php', 'const' => 'CGSEO_VERSION', 'legacy' => '' ),
	);
}

/**
 * Load every module once. Runs on plugins_loaded (after all other
 * plugins), so an old copy that is still active is always detected,
 * whatever order WordPress loads plugin folders in.
 */
function cg_suite_load() {
	static $done = false;
	if ( $done ) {
		return;
	}
	$done = true;

	$GLOBALS['cg_suite_skipped'] = array();
	$GLOBALS['cg_suite_failed']  = array();

	foreach ( cg_suite_modules() as $key => $m ) {
		if ( defined( $m['const'] ) ) {
			$GLOBALS['cg_suite_skipped'][ $key ] = $m['legacy'];
			continue;
		}
		$path = CG_SUITE_DIR . $m['file'];
		if ( ! is_readable( $path ) ) {
			$GLOBALS['cg_suite_failed'][ $key ] = 'File missing: ' . $m['file'];
			continue;
		}
		try {
			require_once $path;
		} catch ( Throwable $e ) {
			$GLOBALS['cg_suite_failed'][ $key ] = $e->getMessage();
			if ( function_exists( 'error_log' ) ) {
				error_log( '[Concepts Group Suite] Module "' . $key . '" failed to load: ' . $e->getMessage() );
			}
		}
	}
}

if ( did_action( 'plugins_loaded' ) ) {
	// Activation request: WordPress includes this file after plugins_loaded has fired.
	cg_suite_load();
} else {
	add_action( 'plugins_loaded', 'cg_suite_load', 0 );
}

/**
 * Basenames (folder/file.php) of the old standalone plugins installed on this site.
 */
function cg_suite_legacy_plugins() {
	static $found = null;
	if ( null !== $found ) {
		return $found;
	}
	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	$names = wp_list_pluck( cg_suite_modules(), 'legacy' );
	$found = array();
	foreach ( get_plugins() as $basename => $data ) {
		if ( in_array( $data['Name'], $names, true ) && plugin_basename( __FILE__ ) !== $basename ) {
			$found[] = $basename;
		}
	}
	return $found;
}

/* ------------------------------------------------------------------
 * Activation: switch off the old plugins, register the CLQ post types,
 * seed the sample sites (only if none exist) and refresh permalinks.
 * ---------------------------------------------------------------- */
register_activation_hook( __FILE__, function () {
	cg_suite_load();

	$legacy = cg_suite_legacy_plugins();
	$active = array_values( array_filter( $legacy, 'is_plugin_active' ) );
	if ( $active ) {
		deactivate_plugins( $active, true );
		set_transient( 'cg_suite_switched_off', $active, HOUR_IN_SECONDS );
	}

	if ( function_exists( 'ccq_register_types' ) ) {
		ccq_register_types();
	}
	if ( function_exists( 'ccq_seed_sites' ) ) {
		ccq_seed_sites();
	}
	flush_rewrite_rules();
} );

register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );

/* ------------------------------------------------------------------
 * Admin notices: what was switched off, and anything skipped or failed.
 * ---------------------------------------------------------------- */
add_action( 'admin_notices', function () {
	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	$off = get_transient( 'cg_suite_switched_off' );
	if ( $off ) {
		delete_transient( 'cg_suite_switched_off' );
		echo '<div class="notice notice-success is-dismissible"><p><strong>Concepts Group Suite</strong> is active. These old plugins were switched off because the suite now includes them: <code>' . esc_html( implode( '</code>, <code>', $off ) ) . '</code>. You can delete them from the Plugins page.</p></div>';
	}

	$skipped = isset( $GLOBALS['cg_suite_skipped'] ) ? $GLOBALS['cg_suite_skipped'] : array();
	if ( $skipped ) {
		echo '<div class="notice notice-warning"><p><strong>Concepts Group Suite:</strong> the old plugin(s) <em>' . esc_html( implode( ', ', $skipped ) ) . '</em> are still active, so the suite is using them instead of its own copy. Deactivate them on the Plugins page to finish switching over.</p></div>';
	}

	$failed = isset( $GLOBALS['cg_suite_failed'] ) ? $GLOBALS['cg_suite_failed'] : array();
	foreach ( $failed as $key => $msg ) {
		echo '<div class="notice notice-error"><p><strong>Concepts Group Suite:</strong> module "' . esc_html( $key ) . '" did not load. ' . esc_html( $msg ) . '</p></div>';
	}
} );

/* Plugins page: block re-activating an old copy while the suite is active (it would crash with "cannot redeclare"). */
add_filter( 'plugin_action_links', function ( $links, $file ) {
	if ( in_array( $file, cg_suite_legacy_plugins(), true ) && isset( $links['activate'] ) ) {
		$links['activate'] = '<span style="color:#787c82">Included in Concepts Group Suite</span>';
	}
	return $links;
}, 10, 2 );
