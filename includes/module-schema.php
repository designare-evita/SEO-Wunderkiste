<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------------------- *
 * MODUL: Custom Schema Meta Box (JSON-LD)
 * Version: 2.9 - Mit verbesserter Validierung
 * ------------------------------------------------------------------------- */

function seowk_schema_add_meta_box() {
    foreach ( array( 'post', 'page' ) as $screen ) {
        add_meta_box( 'seowk_schema_box_id', __( 'Strukturierte Daten (JSON-LD)', 'seo-wunderkiste' ), 'seowk_schema_render_meta_box', $screen, 'normal', 'high' );
    }
}
add_action( 'add_meta_boxes', 'seowk_schema_add_meta_box' );

function seowk_schema_render_meta_box( $post ) {
    $value = get_post_meta( $post->ID, '_seowk_schema_value', true );
    wp_nonce_field( 'seowk_schema_save_data', 'seowk_schema_nonce' );
    echo '<p><label for="seowk_schema_field">' . esc_html__( 'Füge hier dein JSON-LD Objekt ein (ohne Script Tags):', 'seo-wunderkiste' ) . '</label></p>';
    echo '<textarea id="seowk_schema_field" name="seowk_schema_field" rows="10" style="width:100%; font-family:monospace;">' . esc_textarea( $value ) . '</textarea>';
    echo '<p class="description">' . esc_html__( 'Beispiel: { "@context": "https://schema.org", "@type": "Article", ... }', 'seo-wunderkiste' ) . '</p>';
    
    // Validierungs-Hinweis
    if ( ! empty( $value ) && json_decode( $value ) === null ) {
        echo '<p style="color: #d63638; margin-top: 10px;"><strong>⚠️ ' . esc_html__( 'Warnung: Ungültiges JSON-Format!', 'seo-wunderkiste' ) . '</strong></p>';
    }
}

function seowk_schema_save_postdata( $post_id ) {
    // Nonce prüfen
    if ( ! isset( $_POST['seowk_schema_nonce'] ) ) { 
        return; 
    }
    
    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['seowk_schema_nonce'] ) ), 'seowk_schema_save_data' ) ) { 
        return; 
    }
    
    // Autosave überspringen
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { 
        return; 
    }
    
    // Berechtigungen prüfen
    if ( ! current_user_can( 'edit_post', $post_id ) ) { 
        return; 
    }
    
    // Schema speichern
    if ( isset( $_POST['seowk_schema_field'] ) ) {
        $schema_input = wp_unslash( $_POST['seowk_schema_field'] );
        
        // Leeres Feld = Meta löschen
        if ( empty( trim( $schema_input ) ) ) {
            delete_post_meta( $post_id, '_seowk_schema_value' );
            return;
        }
        
        // JSON validieren bevor gespeichert wird
        $decoded = json_decode( $schema_input );
        if ( $decoded !== null || trim( $schema_input ) === 'null' ) {
            // Gültiges JSON - speichern (formatiert)
            update_post_meta( $post_id, '_seowk_schema_value', $schema_input );
        } else {
            // Ungültiges JSON - trotzdem speichern damit User es korrigieren kann
            // aber Warnung wird in der Meta Box angezeigt
            update_post_meta( $post_id, '_seowk_schema_value', $schema_input );
        }
    }
}
add_action( 'save_post', 'seowk_schema_save_postdata' );

function seowk_schema_output_head() {
    if ( is_singular() ) {
        $schema_json = get_post_meta( get_the_ID(), '_seowk_schema_value', true );
        
        // Nur ausgeben wenn gültiges JSON
        if ( ! empty( $schema_json ) ) {
            $decoded = json_decode( $schema_json );
            if ( $decoded !== null ) {
                /*
                 * JSON_HEX_TAG escapes < and > as \u003C / \u003E. Without it a
                 * "</script>" inside any string value closes the ld+json block
                 * early and everything after it is parsed as executable HTML -
                 * a stored XSS for anyone who may edit a post.
                 * JSON_UNESCAPED_SLASHES is deliberately NOT used for the same
                 * reason.
                 */
                $safe_json = wp_json_encode(
                    $decoded,
                    JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
                    | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
                );

                if ( false !== $safe_json ) {
                    echo "\n" . '<script type="application/ld+json">' . "\n" . $safe_json . "\n" . '</script>' . "\n";
                }
            }
        }
    }
}
add_action( 'wp_head', 'seowk_schema_output_head' );
