<?php
/**
 * SEO Wunderkiste Uninstall
 *
 * Wird ausgeführt, wenn das Plugin über das WordPress-Admin deinstalliert wird.
 * Entfernt alle Plugin-Optionen und Post-Meta-Daten aus der Datenbank.
 *
 * @package SEO_Wunderkiste
 * @since 2.8
 */

// Sicherheitscheck: Nur ausführen, wenn von WordPress aufgerufen
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

/* ------------------------------------------------------------------------- *
 * AUFRÄUMEN PRO SITE
 * ------------------------------------------------------------------------- */

/**
 * Removes every option, meta value and transient the plugin created.
 *
 * @return void
 */
function seowk_uninstall_cleanup_site() {
    global $wpdb;

    delete_option( 'seowk_settings' );
    delete_transient( 'seowk_activation_notice' );

    $meta_keys = array(
        // SEO Meta Settings.
        '_seowk_meta_title',
        '_seowk_meta_description',
        '_seowk_meta_robots',
        '_seowk_og_title',
        '_seowk_og_description',
        '_seowk_og_image',

        // Schema.
        '_seowk_schema_value',

        // NoIndex.
        '_seowk_noindex',

        // Conversion Tracker.
        '_seowk_ga4_conversion_enabled',
        '_seowk_ga4_conversion_event',
        '_seowk_ga4_conversion_value',
        '_seowk_ads_conversion_enabled',
        '_seowk_ads_conversion_id',
        '_seowk_ads_conversion_label',
        '_seowk_ads_conversion_value',

        // Decent Lightbox.
        'decent_lightbox_enabled',
    );

    foreach ( $meta_keys as $meta_key ) {
        delete_post_meta_by_key( $meta_key );
    }

    $user_meta_keys = array(
        'seowk_svg_notice_dismissed',
    );

    foreach ( $user_meta_keys as $user_meta_key ) {
        $wpdb->delete( $wpdb->usermeta, array( 'meta_key' => $user_meta_key ), array( '%s' ) );
    }
}

/* ------------------------------------------------------------------------- *
 * AUSFÜHREN - EINZELSITE ODER MULTISITE
 * ------------------------------------------------------------------------- */

/*
 * The previous version only looped over sites to delete the option; post meta
 * was removed on the current site only, so every other site in a network kept
 * its rows.
 */
if ( is_multisite() ) {
    $seowk_site_ids = get_sites(
        array(
            'fields' => 'ids',
            'number' => 0,
        )
    );

    foreach ( $seowk_site_ids as $seowk_site_id ) {
        switch_to_blog( (int) $seowk_site_id );
        seowk_uninstall_cleanup_site();
        restore_current_blog();
    }
} else {
    seowk_uninstall_cleanup_site();
}
