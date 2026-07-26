<?php
/**
 * Minimal PSR-4 autoloader for the bundled enshrined/svg-sanitize library.
 *
 * Vendored at version 0.22.0 (GPL-2.0-or-later) so the plugin does not need a
 * Composer install. Guarded so that a copy already loaded by another plugin
 * (Safe SVG ships the same library) wins and no class is declared twice.
 *
 * @package SEO_Wunderkiste
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! defined( 'SEOWK_SVG_SANITIZE_VERSION' ) ) {
    define( 'SEOWK_SVG_SANITIZE_VERSION', '0.22.0' );
}

spl_autoload_register(
    static function ( $class ) {
        $prefix = 'enshrined\\svgSanitize\\';

        if ( 0 !== strncmp( $prefix, $class, strlen( $prefix ) ) ) {
            return;
        }

        $relative = substr( $class, strlen( $prefix ) );
        $path     = SEOWK_PLUGIN_DIR . 'includes/vendor/svg-sanitize/' . str_replace( '\\', '/', $relative ) . '.php';

        if ( is_readable( $path ) ) {
            require_once $path;
        }
    }
);
