<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------------------- *
 * MODUL: SEO Zombie Killer (Attachment Redirects)
 * Leitet sinnlose Anhang-Seiten auf den Eltern-Beitrag um.
 * ------------------------------------------------------------------------- */

function cts_redirect_attachment_pages() {
    // Wir feuern nur, wenn wir auf einer Anhang-Seite sind
    if ( is_attachment() ) {
        global $post;

        // Fall A: Das Bild gehört zu einem Beitrag/Seite (hat einen Elternteil)
        if ( $post && $post->post_parent ) {
            wp_redirect( get_permalink( $post->post_parent ), 301 );
            exit;
        } 
        
        // Fall B: Das Bild ist "verwaist" (hat keinen Elternteil) -> Zur Startseite
        else {
            wp_redirect( home_url( '/' ), 302 );
            exit;
        }
    }
}
// Wir hängen uns in den 'template_redirect' Hook, bevor die Seite geladen wird
add_action( 'template_redirect', 'cts_redirect_attachment_pages' );