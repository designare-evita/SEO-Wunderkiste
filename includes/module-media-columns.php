<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------------------- *
 * MODUL: Media Library Inspector
 * Fügt Spalten für Dateigröße und Dimensionen in die Medienübersicht ein.
 * ------------------------------------------------------------------------- */

// 1. Spalten-Header definieren
function cts_add_media_columns( $columns ) {
    $columns['cts_filesize']   = 'Dateigröße';
    $columns['cts_dimensions'] = 'Maße (px)';
    return $columns;
}
add_filter( 'manage_upload_columns', 'cts_add_media_columns' );

// 2. Inhalt der Spalten füllen
function cts_fill_media_columns( $column_name, $post_id ) {
    
    // Wir wollen nur unsere eigenen Spalten bearbeiten
    if ( 'cts_filesize' !== $column_name && 'cts_dimensions' !== $column_name ) {
        return;
    }

    // Pfad zur Datei holen
    $file_path = get_attached_file( $post_id );
    
    // Fall A: Dateigröße
    if ( 'cts_filesize' === $column_name ) {
        if ( file_exists( $file_path ) ) {
            $bytes = filesize( $file_path );
            echo size_format( $bytes, 2 ); // WP-Funktion: Macht "MB" oder "KB" daraus
        } else {
            echo '<span style="color:#ccc;">—</span>';
        }
    }

    // Fall B: Dimensionen
    if ( 'cts_dimensions' === $column_name ) {
        $meta = wp_get_attachment_metadata( $post_id );
        if ( isset( $meta['width'] ) && isset( $meta['height'] ) ) {
            echo esc_html( $meta['width'] . ' x ' . $meta['height'] );
        } else {
            // Fallback für SVGs oder Nicht-Bilder
            echo '<span style="color:#ccc;">—</span>';
        }
    }
}
add_action( 'manage_media_custom_column', 'cts_fill_media_columns', 10, 2 );

// 3. Spalten sortierbar machen (Optionales Pro-Feature)
// Das erfordert etwas komplexere Query-Logik, daher hier für "Minimalismus" weggelassen.
// Aber wir fügen ein kleines CSS hinzu, damit die Spalten nicht zu breit sind.

function cts_media_columns_css() {
    echo '<style>
        .column-cts_filesize, .column-cts_dimensions { width: 100px; }
    </style>';
}
add_action('admin_head', 'cts_media_columns_css');