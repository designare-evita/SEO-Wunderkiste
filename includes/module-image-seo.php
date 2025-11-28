<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------------------- *
 * MODUL: Zero-Click Image SEO
 * Automatische Generierung von Alt-Tags und Titeln aus dem Dateinamen.
 * ------------------------------------------------------------------------- */

function cts_auto_image_attributes( $post_ID ) {
    
    // Prüfen, ob es wirklich ein Bild/Attachment ist
    if ( ! wp_attachment_is_image( $post_ID ) ) {
        return;
    }

    // Dateipfad/Name holen
    $file_path = get_attached_file( $post_ID );
    $filename  = pathinfo( $file_path, PATHINFO_FILENAME );

    // Aufhübschen: Bindestriche/Unterstriche zu Leerzeichen
    $clean_title = str_replace( array( '-', '_' ), ' ', $filename );
    
    // Ersten Buchstaben jedes Wortes groß schreiben (für Titel)
    $clean_title = ucwords( $clean_title );

    // 1. UPDATE: Bild-Titel (post_title) in der Datenbank aktualisieren
    $my_post = array(
        'ID'         => $post_ID,
        'post_title' => $clean_title,
    );
    wp_update_post( $my_post );

    // 2. UPDATE: Alt-Text (post_meta) setzen, falls noch leer
    // Wir überschreiben es nur, wenn es nicht schon manuell gesetzt wurde (beim Upload selten der Fall)
    $existing_alt = get_post_meta( $post_ID, '_wp_attachment_image_alt', true );
    
    if ( empty( $existing_alt ) ) {
        update_post_meta( $post_ID, '_wp_attachment_image_alt', $clean_title );
    }
}

// Wir hängen uns an 'add_attachment', das feuert direkt nach dem Upload
add_action( 'add_attachment', 'cts_auto_image_attributes' );