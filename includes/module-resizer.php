<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------------------- *
 * MODUL: Image Resizer 800px
 * ------------------------------------------------------------------------- */

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
                        status.text('Erfolg! Seite neu laden für Vorschau.').css('color', 'green');
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

function ir800_ajax_resize_image() {
    $attachment_id = intval( $_POST['attachment_id'] );
    check_ajax_referer( 'ir800_resize_' . $attachment_id, 'security' );

    if ( ! current_user_can( 'upload_files' ) ) { wp_send_json_error( 'Keine Berechtigung.' ); }

    $path = get_attached_file( $attachment_id );
    if ( ! $path || ! file_exists( $path ) ) { wp_send_json_error( 'Datei nicht gefunden.' ); }

    $editor = wp_get_image_editor( $path );
    if ( is_wp_error( $editor ) ) { wp_send_json_error( 'Bild konnte nicht geladen werden.' ); }

    $size = $editor->get_size();
    if ( $size['width'] <= 800 && $size['height'] <= 800 ) { wp_send_json_error( 'Bild ist bereits klein genug.' ); }

    $resized = $editor->resize( 800, 800, false );
    if ( is_wp_error( $resized ) ) { wp_send_json_error( 'Fehler beim Skalieren.' ); }

    $saved = $editor->save( $path );
    if ( is_wp_error( $saved ) ) { wp_send_json_error( 'Konnte Datei nicht speichern.' ); }

    $metadata = wp_generate_attachment_metadata( $attachment_id, $path );
    wp_update_attachment_metadata( $attachment_id, $metadata );

    wp_send_json_success( 'Bild erfolgreich skaliert.' );
}
add_action( 'wp_ajax_ir800_resize_image', 'ir800_ajax_resize_image' );