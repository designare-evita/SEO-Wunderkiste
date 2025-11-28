<?php
/**
 * Plugin Name: SEO Wunderkiste
 * Description: Deine All-in-One Lösung: SEO Schema, Bild-Optimierung, Upload Cleaner, Auto-Alt-Tags & Redirects.
 * Version: 2.0
 * Author: Michael Kanda
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Konstante für den Pfad (angepasst auf neuen Namen)
define( 'SEOWK_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

// 1. Immer das Admin-Menü laden
require_once SEOWK_PLUGIN_DIR . 'includes/admin-settings.php';

// 2. Gespeicherte Optionen abrufen (neuer Datenbank-Schlüssel)
$options = get_option( 'seowk_settings' );

/* ------------------------------------------------------------------------- *
 * 3. Module bedingt laden (Nur wenn aktiv)
 * ------------------------------------------------------------------------- */

// Modul 1: SEO Schema
if ( ! empty( $options['seowk_enable_schema'] ) ) {
    require_once SEOWK_PLUGIN_DIR . 'includes/module-schema.php';
}

// Modul 2: Image Resizer
if ( ! empty( $options['seowk_enable_resizer'] ) ) {
    require_once SEOWK_PLUGIN_DIR . 'includes/module-resizer.php';
}

// Modul 3: Upload Cleaner
if ( ! empty( $options['seowk_enable_cleaner'] ) ) {
    require_once SEOWK_PLUGIN_DIR . 'includes/module-cleaner.php';
}

// Modul 4: Image SEO (Zero-Click)
if ( ! empty( $options['seowk_enable_image_seo'] ) ) {
    require_once SEOWK_PLUGIN_DIR . 'includes/module-image-seo.php';
}

// Modul 5: Media Library Inspector
if ( ! empty( $options['seowk_enable_media_columns'] ) ) {
    require_once SEOWK_PLUGIN_DIR . 'includes/module-media-columns.php';
}

// Modul 6: SEO Zombie Killer (Redirects)
if ( ! empty( $options['seowk_enable_seo_redirects'] ) ) {
    require_once SEOWK_PLUGIN_DIR . 'includes/module-seo-redirects.php';
}