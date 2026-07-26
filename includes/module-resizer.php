<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------------------- *
 * MODUL: Image Resizer 800px / 1200px mit Bulk-Action
 * Version: 2.9.1 - Mit Live-Update und Massenbearbeitung
 * ------------------------------------------------------------------------- */

/**
 * Whether the current user may resize this specific attachment.
 *
 * @param int $attachment_id Attachment ID.
 * @return bool
 */
function ir800_user_may_resize( $attachment_id ) {
    $attachment_id = (int) $attachment_id;

    if ( $attachment_id <= 0 || ! current_user_can( 'upload_files' ) ) {
        return false;
    }

    return current_user_can( 'edit_post', $attachment_id );
}

function ir800_add_resize_button( $form_fields, $post ) {
    if ( ! wp_attachment_is_image( $post->ID ) || ! ir800_user_may_resize( $post->ID ) ) { return $form_fields; }
    $metadata = wp_get_attachment_metadata( $post->ID );
    $current_size = $metadata && isset( $metadata['width'] ) 
        ? '<span class="ir800-current-size" style="color:#666; font-size:12px;">Aktuell: <strong>' . $metadata['width'] . ' × ' . $metadata['height'] . ' px</strong></span><br>' 
        : '';
    $form_fields['ir800_resize'] = array(
        'label' => __( 'Skalierung', 'seo-wunderkiste' ),
        'input' => 'html',
        'html'  => $current_size . '<div style="display: flex; gap: 8px; margin-bottom: 8px;">
            <button type="button" class="button button-small ir800-trigger" data-id="' . $post->ID . '" data-size="800" data-nonce="' . wp_create_nonce('ir800_resize_' . $post->ID) . '">800px</button>
            <button type="button" class="button button-small ir800-trigger" data-id="' . $post->ID . '" data-size="1200" data-nonce="' . wp_create_nonce('ir800_resize_' . $post->ID) . '">1200px</button>
        </div><p class="description">Überschreibt das Originalbild (92% Qualität).</p><span class="ir800-status" style="color: #2271b1; font-weight: bold; display: none;"></span>'
    );
    return $form_fields;
}
add_filter( 'attachment_fields_to_edit', 'ir800_add_resize_button', 10, 2 );

function ir800_admin_footer_script() {
    $screen = get_current_screen();
    if ( ! $screen || ( $screen->base !== 'upload' && $screen->base !== 'post' && $screen->id !== 'attachment' ) ) {
        return;
    }
    ?>
    <script>
    jQuery(document).ready(function($) {
        // Einzelbild Resize
        $(document).on('click', '.ir800-trigger', function(e) {
            e.preventDefault();
            var btn = $(this), 
                container = btn.closest('.ir800-container, td, .setting, .compat-field-ir800_resize'),
                status = container.find('.ir800-status'),
                sizeDisplay = container.find('.ir800-current-size');
            var id = btn.data('id'), size = btn.data('size') || 800, nonce = btn.data('nonce');
            
            if(!confirm('Bild auf ' + size + 'px verkleinern? Original wird überschrieben.')) return;
            
            container.find('.ir800-trigger').prop('disabled', true);
            status.text('⏳ Arbeite...').css('color', '#2271b1').show();
            
            $.post(ajaxurl, { 
                action: 'ir800_resize_image', 
                attachment_id: id, 
                target_size: size, 
                security: nonce 
            }, function(r) {
                if (r.success) { 
                    status.text('✓ Fertig: ' + r.data.dimensions).css('color', 'green');
                    // Update der Größenanzeige
                    if (sizeDisplay.length) {
                        sizeDisplay.html('Aktuell: <strong>' + r.data.dimensions + '</strong>');
                    }
                    // Nach 3 Sekunden Buttons wieder aktivieren
                    setTimeout(function() {
                        container.find('.ir800-trigger').prop('disabled', false);
                        status.fadeOut();
                    }, 3000);
                } else { 
                    status.text('✗ ' + (r.data || 'Fehler')).css('color', 'red'); 
                    container.find('.ir800-trigger').prop('disabled', false); 
                }
            }).fail(function() {
                status.text('✗ Verbindungsfehler').css('color', 'red');
                container.find('.ir800-trigger').prop('disabled', false);
            });
        });

        // Bulk Action Handler
        $(document).on('click', '#doaction, #doaction2', function(e) {
            var action = $(this).prev('select').val();
            if (action !== 'ir800_bulk_800' && action !== 'ir800_bulk_1200') return;
            
            e.preventDefault();
            var size = action === 'ir800_bulk_800' ? 800 : 1200;
            var checked = $('input[name="media[]"]:checked');
            
            if (checked.length === 0) {
                alert('<?php echo esc_js( __( 'Bitte wähle mindestens ein Bild aus.', 'seo-wunderkiste' ) ); ?>');
                return;
            }
            
            if (!confirm('<?php echo esc_js( __( 'Ausgewählte Bilder auf', 'seo-wunderkiste' ) ); ?> ' + size + 'px <?php echo esc_js( __( 'verkleinern? Originale werden überschrieben!', 'seo-wunderkiste' ) ); ?>')) {
                return;
            }
            
            var ids = [];
            checked.each(function() { ids.push($(this).val()); });
            
            // Progress Modal erstellen
            var modal = $('<div id="ir800-bulk-modal" style="position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.7);z-index:100000;display:flex;align-items:center;justify-content:center;">' +
                '<div style="background:#fff;padding:30px;border-radius:8px;max-width:500px;width:90%;box-shadow:0 4px 20px rgba(0,0,0,0.3);">' +
                '<h2 style="margin:0 0 20px;color:#1d2327;">🖼️ <?php echo esc_js( __( 'Bilder werden skaliert...', 'seo-wunderkiste' ) ); ?></h2>' +
                '<div class="ir800-progress-bar" style="background:#ddd;height:24px;border-radius:12px;overflow:hidden;margin-bottom:15px;">' +
                '<div class="ir800-progress-fill" style="background:linear-gradient(90deg,#2271b1,#135e96);height:100%;width:0%;transition:width 0.3s;"></div></div>' +
                '<div class="ir800-progress-text" style="text-align:center;font-size:14px;color:#50575e;">0 / ' + ids.length + '</div>' +
                '<div class="ir800-progress-log" style="max-height:200px;overflow-y:auto;margin-top:15px;font-size:12px;background:#f6f7f7;padding:10px;border-radius:4px;"></div>' +
                '</div></div>');
            $('body').append(modal);
            
            var processed = 0, success = 0, errors = 0;
            var progressFill = modal.find('.ir800-progress-fill');
            var progressText = modal.find('.ir800-progress-text');
            var progressLog = modal.find('.ir800-progress-log');
            
            function processNext(index) {
                if (index >= ids.length) {
                    // Fertig
                    progressText.html('<strong style="color:#00a32a;">✓ <?php echo esc_js( __( 'Fertig!', 'seo-wunderkiste' ) ); ?></strong> ' + success + ' <?php echo esc_js( __( 'erfolgreich', 'seo-wunderkiste' ) ); ?>, ' + errors + ' <?php echo esc_js( __( 'Fehler', 'seo-wunderkiste' ) ); ?>');
                    setTimeout(function() {
                        modal.fadeOut(300, function() { 
                            $(this).remove(); 
                            location.reload(); 
                        });
                    }, 2000);
                    return;
                }
                
                var id = ids[index];
                var row = $('input[name="media[]"][value="' + id + '"]').closest('tr');
                var title = row.find('.title a').text() || row.find('.column-title strong').text() || 'ID: ' + id;
                
                $.post(ajaxurl, {
                    action: 'ir800_resize_image',
                    attachment_id: id,
                    target_size: size,
                    security: '<?php echo wp_create_nonce( 'ir800_bulk_resize' ); ?>',
                    is_bulk: true
                }, function(r) {
                    processed++;
                    var percent = Math.round((processed / ids.length) * 100);
                    progressFill.css('width', percent + '%');
                    progressText.text(processed + ' / ' + ids.length);
                    
                    if (r.success) {
                        success++;
                        progressLog.prepend('<div style="color:#00a32a;margin-bottom:3px;">✓ ' + title + ' → ' + r.data.dimensions + '</div>');
                    } else {
                        errors++;
                        progressLog.prepend('<div style="color:#d63638;margin-bottom:3px;">✗ ' + title + ': ' + (r.data || 'Fehler') + '</div>');
                    }
                    
                    // Nächstes Bild nach kurzer Pause
                    setTimeout(function() { processNext(index + 1); }, 200);
                }).fail(function() {
                    processed++;
                    errors++;
                    progressLog.prepend('<div style="color:#d63638;margin-bottom:3px;">✗ ' + title + ': Verbindungsfehler</div>');
                    setTimeout(function() { processNext(index + 1); }, 200);
                });
            }
            
            processNext(0);
        });
    });
    </script>
    <?php
}
add_action( 'admin_footer', 'ir800_admin_footer_script' );

// Bulk Actions zur Mediathek hinzufügen
function ir800_add_bulk_actions( $bulk_actions ) {
    $bulk_actions['ir800_bulk_800'] = __( '🖼️ Auf 800px skalieren', 'seo-wunderkiste' );
    $bulk_actions['ir800_bulk_1200'] = __( '🖼️ Auf 1200px skalieren', 'seo-wunderkiste' );
    return $bulk_actions;
}
add_filter( 'bulk_actions-upload', 'ir800_add_bulk_actions' );

function ir800_ajax_resize_image() {
    // Validierung
    if ( ! isset( $_POST['attachment_id'] ) || ! isset( $_POST['security'] ) ) {
        wp_send_json_error( __( 'Ungültige Anfrage.', 'seo-wunderkiste' ), 400 );
    }
    
    $attachment_id = absint( wp_unslash( $_POST['attachment_id'] ) );
    $target_size = isset( $_POST['target_size'] ) ? absint( wp_unslash( $_POST['target_size'] ) ) : 800;
    $nonce = sanitize_text_field( wp_unslash( $_POST['security'] ) );

    if ( ! in_array( $target_size, array( 800, 1200 ), true ) ) { $target_size = 800; }

    /*
     * Both nonces are accepted, but which one is checked is no longer decided
     * by an "is_bulk" request parameter the caller controls. Neither nonce
     * authorises anything on its own - the capability check below does.
     */
    $nonce_valid = wp_verify_nonce( $nonce, 'ir800_resize_' . $attachment_id )
        || wp_verify_nonce( $nonce, 'ir800_bulk_resize' );

    if ( ! $nonce_valid ) {
        wp_send_json_error( __( 'Sicherheitsprüfung fehlgeschlagen. Bitte die Seite neu laden.', 'seo-wunderkiste' ), 403 );
    }

    /*
     * upload_files alone is not enough: Authors have it, and the resize
     * overwrites the original file irreversibly. Edit rights on this specific
     * attachment are required.
     */
    if ( ! ir800_user_may_resize( $attachment_id ) ) {
        wp_send_json_error( __( 'Du darfst dieses Bild nicht bearbeiten.', 'seo-wunderkiste' ), 403 );
    }
    
    // Prüfen ob es ein Bild ist
    if ( ! wp_attachment_is_image( $attachment_id ) ) {
        wp_send_json_error( __( 'Kein Bild.', 'seo-wunderkiste' ) );
    }
    
    $path = get_attached_file( $attachment_id );
    if ( ! $path || ! file_exists( $path ) ) { 
        wp_send_json_error( __( 'Datei nicht gefunden.', 'seo-wunderkiste' ) ); 
    }
    
    $editor = wp_get_image_editor( $path );
    if ( is_wp_error( $editor ) ) { 
        wp_send_json_error( __( 'Bildfehler.', 'seo-wunderkiste' ) ); 
    }
    
    $editor->set_quality( 92 );
    $size = $editor->get_size();
    
    if ( $size['width'] <= $target_size && $size['height'] <= $target_size ) { 
        wp_send_json_error( sprintf( 
            __( 'Bereits %dpx oder kleiner.', 'seo-wunderkiste' ), 
            $target_size 
        ) ); 
    }
    
    $resized = $editor->resize( $target_size, $target_size, false );
    if ( is_wp_error( $resized ) ) { 
        wp_send_json_error( __( 'Resize-Fehler.', 'seo-wunderkiste' ) ); 
    }
    
    $saved = $editor->save( $path );
    if ( is_wp_error( $saved ) ) { 
        wp_send_json_error( __( 'Speicherfehler.', 'seo-wunderkiste' ) ); 
    }
    
    // Metadata aktualisieren
    $metadata = wp_generate_attachment_metadata( $attachment_id, $path );
    wp_update_attachment_metadata( $attachment_id, $metadata );
    
    $new_size = $editor->get_size();
    wp_send_json_success( array(
        'dimensions' => $new_size['width'] . ' × ' . $new_size['height'] . ' px',
        'width'      => $new_size['width'],
        'height'     => $new_size['height'],
    ) );
}
add_action( 'wp_ajax_ir800_resize_image', 'ir800_ajax_resize_image' );

function ir800_add_list_column( $columns ) { 
    $columns['ir800_action'] = __( 'Resizer', 'seo-wunderkiste' ); 
    return $columns; 
}
add_filter( 'manage_upload_columns', 'ir800_add_list_column' );

function ir800_fill_list_column( $column_name, $post_id ) {
    if ( 'ir800_action' !== $column_name ) return;
    if ( wp_attachment_is_image( $post_id ) && ir800_user_may_resize( $post_id ) ) {
        $nonce = wp_create_nonce('ir800_resize_' . $post_id);
        $metadata = wp_get_attachment_metadata( $post_id );
        $current_size = '';
        if ( $metadata && isset( $metadata['width'] ) ) {
            $current_size = '<span class="ir800-current-size" style="font-size:11px;color:#666;display:block;margin-bottom:4px;">' . 
                $metadata['width'] . '×' . $metadata['height'] . '</span>';
        }
        echo $current_size . '<div class="ir800-container" style="display: flex; gap: 4px;">
            <button type="button" class="button button-small ir800-trigger" data-id="' . $post_id . '" data-size="800" data-nonce="' . $nonce . '">800</button>
            <button type="button" class="button button-small ir800-trigger" data-id="' . $post_id . '" data-size="1200" data-nonce="' . $nonce . '">1200</button>
        </div><span class="ir800-status" style="display:block; font-size:11px; margin-top:2px;"></span>';
    } else {
        echo '<span style="color:#999;">—</span>';
    }
}
add_action( 'manage_media_custom_column', 'ir800_fill_list_column', 10, 2 );

function ir800_list_css() { 
    echo '<style>
        .column-ir800_action { width: 120px; } 
        .ir800-container .button { min-width: 45px; padding: 0 8px; }
        .ir800-current-size strong { color: #1d2327; }
    </style>'; 
}
add_action('admin_head', 'ir800_list_css');
