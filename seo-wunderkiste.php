<?php
/**
 * Plugin Name: SEO Wunderkiste
 * Description: Deine All-in-One Lösung: SEO Schema, Bild-Optimierung, Cleaner, Security & mehr.
 * Version: 2.1
 * Author: Michael Kanda
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'SEOWK_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

// 1. Admin Settings laden
require_once SEOWK_PLUGIN_DIR . 'includes/admin-settings.php';

// 2. Optionen abrufen
$options = get_option( 'seowk_settings' );

/* ------------------------------------------------------------------------- *
 * 3. Module bedingt laden
 * ------------------------------------------------------------------------- */

// Bestehende Module
if ( ! empty( $options['seowk_enable_schema'] ) ) { require_once SEOWK_PLUGIN_DIR . 'includes/module-schema.php'; }
if ( ! empty( $options['seowk_enable_resizer'] ) ) { require_once SEOWK_PLUGIN_DIR . 'includes/module-resizer.php'; }
if ( ! empty( $options['seowk_enable_cleaner'] ) ) { require_once SEOWK_PLUGIN_DIR . 'includes/module-cleaner.php'; }
if ( ! empty( $options['seowk_enable_image_seo'] ) ) { require_once SEOWK_PLUGIN_DIR . 'includes/module-image-seo.php'; }
if ( ! empty( $options['seowk_enable_media_columns'] ) ) { require_once SEOWK_PLUGIN_DIR . 'includes/module-media-columns.php'; }
if ( ! empty( $options['seowk_enable_seo_redirects'] ) ) { require_once SEOWK_PLUGIN_DIR . 'includes/module-seo-redirects.php'; }

// NEUE MODULE (v2.1)
if ( ! empty( $options['seowk_enable_svg'] ) ) { require_once SEOWK_PLUGIN_DIR . 'includes/module-svg.php'; }
if ( ! empty( $options['seowk_disable_emojis'] ) ) { require_once SEOWK_PLUGIN_DIR . 'includes/module-disable-emojis.php'; }
if ( ! empty( $options['seowk_disable_xmlrpc'] ) ) { require_once SEOWK_PLUGIN_DIR . 'includes/module-disable-xmlrpc.php'; }
if ( ! empty( $options['seowk_enable_login_protection'] ) ) { require_once SEOWK_PLUGIN_DIR . 'includes/module-login-protection.php'; }
