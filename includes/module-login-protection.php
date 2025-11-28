<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------------------- *
 * MODUL: Login Türsteher
 * ------------------------------------------------------------------------- */

function seowk_secret_login_parameter() {
    // 1. Hole den Schlüssel aus den Einstellungen (Fallback: 'hintereingang')
    $options = get_option( 'seowk_settings' );
    $secret_key = ! empty( $options['seowk_login_protection_key'] ) ? trim( $options['seowk_login_protection_key'] ) : 'hintereingang';
    
    // 2. Prüfen, ob wir auf der Login-Seite sind und NICHT eingeloggt sind
    if ( strpos( $_SERVER['REQUEST_URI'], 'wp-login.php' ) !== false && ! is_user_logged_in() ) {
        // 3. Wenn der Parameter in der URL fehlt, wirf den Nutzer raus
        if ( ! isset( $_GET[$secret_key] ) ) {
            wp_redirect( home_url() );
            exit;
        }
    }
}
add_action( 'init', 'seowk_secret_login_parameter' );
