<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------------------- *
 * MODUL: Image Resizer 800px
 * Version: 2.2 - Mit verbesserter Bildqualität
 * ------------------------------------------------------------------------- */

// 1. Button im "Attachment Details" Modal (Einzelansicht)
function ir800_add_resize_button( $form_fields, $post ) {
    if ( ! wp_attachment_is_image( $post->ID ) ) {
        return $form_fields;
    }

    $form_fields['ir800_resize'] = array(
        'label' => 'Skalierung',
        'input' => 'html',
        'html'  => '
            <button type="button" class="button button-small ir800-trigger" data-id="' . $post->ID . '" data-nonce="' . wp_create_nonce('ir800_resize_' . $post->ID) . '">
                Auf 800px skalieren
            </button>
            <p class="description" style="margin-top:5px;">Überschreibt das Originalbild (max. 800px Breite/Höhe).</p>
            <span class="ir800-status" style="color: #2271b1; font-weight: bold; display: none;"></span>
        ',
    );
    return $form_fields;
}
add_filter( 'attachment_fields_to_edit', 'ir800_add_resize_button', 10, 2 );

// 2. JavaScript (Funktioniert für Modal UND Listenansicht)
function ir800_admin_footer_script() {
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        $(document).on('click', '.ir800-trigger', function(e) {
            e.preventDefault();
            var button = $(this);
            var status = button.siblings('.ir800-status');
            var attachmentId = button.data('id');
            var nonce = button.data('nonce');

            if(!confirm('Möchtest du dieses Bild wirklich permanent auf 800px verkleinern? Das Original wird überschrieben.')) { return; }

            button.prop('disabled', true);
            status.text('Arbeite...').show();

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: { action: 'ir800_resize_image', attachment_id: attachmentId, security: nonce },
                success: function(response) {
                    if (response.success) {
                        status.text('Erledigt!').css('color', 'green');
                    } else {
                        status.text('Fehler: ' + (response.data || 'Unbekannt')).css('color', 'red');
                        button.prop('disabled', false);
                    }
                },
                error: function() {
                    status.text('Server Fehler.').css('color', 'red');
                    button.prop('disabled', false);
                }
            });
        });
    });
    </script>
    <?php
}
add_action( 'admin_footer', 'ir800_admin_footer_script' );

// 3. PHP Logik (AJAX Handler) - MIT VERBESSERTER QUALITÄT
function ir800_ajax_resize_image() {
    $attachment_id = intval( $_POST['attachment_id'] );
    check_ajax_referer( 'ir800_resize_' . $attachment_id, 'security' );

    if ( ! current_user_can( 'upload_files' ) ) { 
        wp_send_json_error( 'Keine Berechtigung.' ); 
    }

    $path = get_attached_file( $attachment_id );
    if ( ! $path || ! file_exists( $path ) ) { 
        wp_send_json_error( 'Datei nicht gefunden.' ); 
    }

    $editor = wp_get_image_editor( $path );
    if ( is_wp_error( $editor ) ) { 
        wp_send_json_error( 'Bildfehler.' ); 
    }

    // QUALITÄT SETZEN - 92 für hohe Qualität bei akzeptabler Dateigröße
    // Werte: 82 = WP-Standard, 88-90 = Kompromiss, 92-95 = Hohe Qualität
    $editor->set_quality( 92 );

    $size = $editor->get_size();
    if ( $size['width'] <= 800 && $size['height'] <= 800 ) { 
        wp_send_json_error( 'Bereits klein genug.' ); 
    }

    $resized = $editor->resize( 800, 800, false );
    if ( is_wp_error( $resized ) ) { 
        wp_send_json_error( 'Resize-Fehler.' ); 
    }

    $saved = $editor->save( $path );
    if ( is_wp_error( $saved ) ) { 
        wp_send_json_error( 'Speicherfehler.' ); 
    }

    // Metadaten aktualisieren (wichtig für korrekte Anzeige in Mediathek)
    $metadata = wp_generate_attachment_metadata( $attachment_id, $path );
    wp_update_attachment_metadata( $attachment_id, $metadata );

    wp_send_json_success( 'Skaliert auf 800px (92% Qualität).' );
}
add_action( 'wp_ajax_ir800_resize_image', 'ir800_ajax_resize_image' );


/* ------------------------------------------------------------------------- *
 * 4. Spalte in der Listenansicht
 * ------------------------------------------------------------------------- */

// Spalten-Überschrift hinzufügen
function ir800_add_list_column( $columns ) {
    $columns['ir800_action'] = 'Resizer';
    return $columns;
}
add_filter( 'manage_upload_columns', 'ir800_add_list_column' );

// Spalten-Inhalt (Button)
function ir800_fill_list_column( $column_name, $post_id ) {
    if ( 'ir800_action' !== $column_name ) {
        return;
    }
    
    if ( wp_attachment_is_image( $post_id ) ) {
        echo '<button type="button" class="button button-small ir800-trigger" data-id="' . $post_id . '" data-nonce="' . wp_create_nonce('ir800_resize_' . $post_id) . '">800px</button>';
        echo '<span class="ir800-status" style="display:block; font-size:11px; margin-top:2px;"></span>';
    }
}
add_action( 'manage_media_custom_column', 'ir800_fill_list_column', 10, 2 );

// CSS für Spaltenbreite
function ir800_list_css() {
    echo '<style>.column-ir800_action { width: 100px; }</style>';
}
add_action('admin_head', 'ir800_list_css');
