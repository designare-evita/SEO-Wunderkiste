<?php
/**
 * Plugin Name: SEO Wunderkiste
 * Plugin URI: https://designare.at/seo-wunderkiste
 * Description: Deine modulare All-in-One Lösung: SEO Schema, Meta Settings, Bild-Optimierung, Cleaner, Security, Tracking & mehr.
 * Version: 2.11
 * Author: Michael Kanda
 * Author URI: https://designare.at
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: seo-wunderkiste
 * Domain Path: /languages
 * Requires at least: 6.3
 * Requires PHP: 7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/* ------------------------------------------------------------------------- *
 * PLUGIN CONSTANTS
 * ------------------------------------------------------------------------- */

define( 'SEOWK_VERSION', '2.11' );
define( 'SEOWK_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SEOWK_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SEOWK_PLUGIN_FILE', __FILE__ );

/* ------------------------------------------------------------------------- *
 * TEXTDOMAIN LADEN (Übersetzungen) - FIX #1
 * ------------------------------------------------------------------------- */

function seowk_load_textdomain() {
    load_plugin_textdomain(
        'seo-wunderkiste',
        false,
        dirname( plugin_basename( __FILE__ ) ) . '/languages'
    );
}
// Since WP 6.7 translations must not be loaded before `init`.
add_action( 'init', 'seowk_load_textdomain' );

/* ------------------------------------------------------------------------- *
 * HELPER FUNCTION: Optionen abrufen (kapselt globale Variable) - FIX #5
 * ------------------------------------------------------------------------- */

function seowk_get_options() {
    static $options = null;
    
    if ( $options === null ) {
        $options = get_option( 'seowk_settings', array() );
    }
    
    return $options;
}

function seowk_is_module_active( $module_key ) {
    $options = seowk_get_options();
    return ! empty( $options[ $module_key ] );
}

/* ------------------------------------------------------------------------- *
 * ADMIN SETTINGS LADEN
 * ------------------------------------------------------------------------- */

require_once SEOWK_PLUGIN_DIR . 'includes/admin-settings.php';

/* ------------------------------------------------------------------------- *
 * MODULE BEDINGT LADEN (nutzt jetzt Helper-Funktion)
 * ------------------------------------------------------------------------- */

// SEO & CONTENT MODULE
if ( seowk_is_module_active( 'seowk_enable_meta_settings' ) ) {
    require_once SEOWK_PLUGIN_DIR . 'includes/module-meta-settings.php';
}

if ( seowk_is_module_active( 'seowk_enable_schema' ) ) {
    require_once SEOWK_PLUGIN_DIR . 'includes/module-schema.php';
}

if ( seowk_is_module_active( 'seowk_enable_bulk_noindex' ) ) {
    require_once SEOWK_PLUGIN_DIR . 'includes/module-bulk-noindex.php';
}

if ( seowk_is_module_active( 'seowk_enable_seo_redirects' ) ) {
    require_once SEOWK_PLUGIN_DIR . 'includes/module-seo-redirects.php';
}

if ( seowk_is_module_active( 'seowk_enable_conversion_tracker' ) ) {
    require_once SEOWK_PLUGIN_DIR . 'includes/module-conversion-tracker.php';
}

// BILD & MEDIA MODULE
if ( seowk_is_module_active( 'seowk_enable_resizer' ) ) {
    require_once SEOWK_PLUGIN_DIR . 'includes/module-resizer.php';
}

if ( seowk_is_module_active( 'seowk_enable_cleaner' ) ) {
    require_once SEOWK_PLUGIN_DIR . 'includes/module-cleaner.php';
}

if ( seowk_is_module_active( 'seowk_enable_image_seo' ) ) {
    require_once SEOWK_PLUGIN_DIR . 'includes/module-image-seo.php';
}

if ( seowk_is_module_active( 'seowk_enable_media_columns' ) ) {
    require_once SEOWK_PLUGIN_DIR . 'includes/module-media-columns.php';
}

if ( seowk_is_module_active( 'seowk_enable_svg' ) ) {
    require_once SEOWK_PLUGIN_DIR . 'includes/module-svg.php';
}

if ( seowk_is_module_active( 'seowk_enable_lightbox' ) ) {
    require_once SEOWK_PLUGIN_DIR . 'includes/module-decent-lightbox.php';
}

// PERFORMANCE MODULE
if ( seowk_is_module_active( 'seowk_disable_emojis' ) ) {
    require_once SEOWK_PLUGIN_DIR . 'includes/module-disable-emojis.php';
}

// SICHERHEIT & ADMIN MODULE
if ( seowk_is_module_active( 'seowk_disable_xmlrpc' ) ) {
    require_once SEOWK_PLUGIN_DIR . 'includes/module-disable-xmlrpc.php';
}

if ( seowk_is_module_active( 'seowk_enable_login_protection' ) ) {
    require_once SEOWK_PLUGIN_DIR . 'includes/module-login-protection.php';
}

if ( seowk_is_module_active( 'seowk_enable_comment_blocker' ) ) {
    require_once SEOWK_PLUGIN_DIR . 'includes/module-comment-blocker.php';
}

// ADMIN TOOLS MODULE
if ( seowk_is_module_active( 'seowk_enable_id_column' ) ) {
    require_once SEOWK_PLUGIN_DIR . 'includes/module-id-column.php';
}

// CONTENT TOOLS MODULE
if ( seowk_is_module_active( 'seowk_enable_date_shortcode' ) ) {
    require_once SEOWK_PLUGIN_DIR . 'includes/module-date-shortcode.php';
}

if ( seowk_is_module_active( 'seowk_enable_semantic_blocks' ) ) {
    require_once SEOWK_PLUGIN_DIR . 'includes/module-semantic-blocks.php';
}

/* ------------------------------------------------------------------------- *
 * PLUGIN ACTIVATION & DEACTIVATION HOOKS
 * ------------------------------------------------------------------------- */

function seowk_plugin_activate() {
    if ( ! get_option( 'seowk_settings' ) ) {
        $default_options = array(
            // No default login key on purpose: a secret printed in the plugin
            // source, the readme and the settings placeholder protects nothing.
            'seowk_login_protection_key' => '',
            'seowk_conversion_currency'  => 'EUR',
        );
        add_option( 'seowk_settings', $default_options );
    }
    
    // Languages-Ordner erstellen
    $languages_dir = SEOWK_PLUGIN_DIR . 'languages';
    if ( ! file_exists( $languages_dir ) ) {
        wp_mkdir_p( $languages_dir );
    }

    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'seowk_plugin_activate' );

function seowk_plugin_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'seowk_plugin_deactivate' );

/* ------------------------------------------------------------------------- *
 * PLUGIN LINKS IN DER PLUGIN-ÜBERSICHT
 * ------------------------------------------------------------------------- */

function seowk_add_settings_link( $links ) {
    $settings_link = '<a href="' . esc_url( admin_url( 'options-general.php?page=seo-wunderkiste' ) ) . '">' . esc_html__( 'Einstellungen', 'seo-wunderkiste' ) . '</a>';
    array_unshift( $links, $settings_link );
    return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'seowk_add_settings_link' );

function seowk_add_plugin_meta_links( $links, $file ) {
    if ( strpos( $file, basename( __FILE__ ) ) !== false ) {
        $new_links = array(
            '<a href="https://designare.at/seo-wunderkiste" style="color: #d63638; font-weight: 600;">' . __( 'Dokumentation', 'seo-wunderkiste' ) . '</a>',
            '<a href="https://designare.at/seo-wunderkiste" style="color: #2271b1;">' . __( 'Support', 'seo-wunderkiste' ) . '</a>'
        );
        $links = array_merge( $links, $new_links );
    }
    return $links;
}
add_filter( 'plugin_row_meta', 'seowk_add_plugin_meta_links', 10, 2 );

/* ------------------------------------------------------------------------- *
 * ADMIN NOTICES
 * ------------------------------------------------------------------------- */

function seowk_admin_notice_after_activation() {
    if ( get_transient( 'seowk_activation_notice' ) ) {
        ?>
        <div class="notice notice-success is-dismissible">
            <p>
                <strong>🎉 <?php esc_html_e( 'SEO Wunderkiste aktiviert!', 'seo-wunderkiste' ); ?></strong> 
                <?php 
                printf( 
                    /* translators: %s: settings page URL */
                    esc_html__( 'Gehe zu %s, um deine gewünschten Module zu aktivieren.', 'seo-wunderkiste' ),
                    '<a href="' . esc_url( admin_url( 'options-general.php?page=seo-wunderkiste' ) ) . '">' . esc_html__( 'Einstellungen → SEO Wunderkiste', 'seo-wunderkiste' ) . '</a>'
                );
                ?>
            </p>
        </div>
        <?php
        delete_transient( 'seowk_activation_notice' );
    }
}
add_action( 'admin_notices', 'seowk_admin_notice_after_activation' );

function seowk_set_activation_transient() {
    set_transient( 'seowk_activation_notice', true, 5 );
}
register_activation_hook( __FILE__, 'seowk_set_activation_transient' );

/* ------------------------------------------------------------------------- *
 * DEBUG INFO (nur für Admins)
 * ------------------------------------------------------------------------- */

function seowk_admin_footer_debug() {
    // Only in debug mode - client installs should not get console noise
    // injected into every single admin screen.
    if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
        return;
    }

    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $seowk_opts = seowk_get_options();
    $active_modules = array_filter( $seowk_opts, function( $value, $key ) {
        return strpos( $key, 'seowk_enable_' ) === 0 || strpos( $key, 'seowk_disable_' ) === 0;
    }, ARRAY_FILTER_USE_BOTH );
    $active_modules = array_filter( $active_modules );
    $module_count = count( $active_modules );

    ?>
    <script>
    console.log('%c🎯 SEO Wunderkiste v<?php echo esc_js( SEOWK_VERSION ); ?>', 'background: #2271b1; color: white; padding: 5px 10px; border-radius: 3px;');
    console.log('<?php echo esc_js( __( 'Aktive Module:', 'seo-wunderkiste' ) ); ?> <?php echo esc_js( (string) $module_count ); ?>');
    <?php if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) : ?>
    console.log('Module:', <?php echo wp_json_encode( array_keys( $active_modules ) ); ?>);
    <?php endif; ?>
    </script>
    <?php
}
add_action( 'admin_footer', 'seowk_admin_footer_debug' );

/* ------------------------------------------------------------------------- *
 * MODUL-ÜBERSICHT FÜR ENTWICKLER
 * ------------------------------------------------------------------------- */

function seowk_get_available_modules() {
    return array(
        'seo_content' => array(
            'seowk_enable_meta_settings'     => __( 'SEO Meta Settings', 'seo-wunderkiste' ),
            'seowk_enable_schema'            => __( 'SEO Schema (JSON-LD)', 'seo-wunderkiste' ),
            'seowk_enable_bulk_noindex'      => __( 'Bulk NoIndex Manager', 'seo-wunderkiste' ),
            'seowk_enable_seo_redirects'     => __( 'SEO Zombie Killer', 'seo-wunderkiste' ),
            'seowk_enable_conversion_tracker'=> __( 'Conversion Tracker', 'seo-wunderkiste' ),
        ),
        'media' => array(
            'seowk_enable_resizer'       => __( 'Image Resizer (800px/1200px)', 'seo-wunderkiste' ),
            'seowk_enable_cleaner'       => __( 'Upload Cleaner', 'seo-wunderkiste' ),
            'seowk_enable_image_seo'     => __( 'Zero-Click Image SEO', 'seo-wunderkiste' ),
            'seowk_enable_media_columns' => __( 'Media Inspector', 'seo-wunderkiste' ),
            'seowk_enable_svg'           => __( 'SVG Upload Support', 'seo-wunderkiste' ),
            'seowk_enable_lightbox'      => __( 'Decent Lightbox', 'seo-wunderkiste' ),
        ),
        'performance' => array(
            'seowk_disable_emojis' => __( 'Emoji Bloat Remover', 'seo-wunderkiste' ),
        ),
        'security_admin' => array(
            'seowk_disable_xmlrpc'        => __( 'XML-RPC Blocker', 'seo-wunderkiste' ),
            'seowk_enable_login_protection'=> __( 'Login Türsteher', 'seo-wunderkiste' ),
            'seowk_enable_comment_blocker' => __( 'Comment Blocker', 'seo-wunderkiste' ),
            'seowk_enable_id_column'       => __( 'ID Column Display', 'seo-wunderkiste' ),
        ),
        'content_tools' => array(
            'seowk_enable_date_shortcode'   => __( 'Date Shortcode', 'seo-wunderkiste' ),
            'seowk_enable_semantic_blocks'  => __( 'Semantic Blocks', 'seo-wunderkiste' ),
        ),
    );
}

/* ------------------------------------------------------------------------- *
 * HELPER: Währung für Conversion Tracking - FIX #4
 * ------------------------------------------------------------------------- */

function seowk_get_conversion_currency() {
    $options = seowk_get_options();
    $currency = isset( $options['seowk_conversion_currency'] ) ? $options['seowk_conversion_currency'] : 'EUR';
    
    /**
     * Filter: seowk_conversion_currency
     * Ermöglicht das Ändern der Währung für Conversion Tracking
     *
     * @param string $currency Währungscode (z.B. 'EUR', 'USD', 'CHF')
     */
    return apply_filters( 'seowk_conversion_currency', $currency );
}
