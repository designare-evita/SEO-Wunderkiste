<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------------------- *
 * MODUL: Bulk NoIndex Manager
 * Version: 2.9 - Mit Quick Edit Support
 * ------------------------------------------------------------------------- */

// Bulk Actions hinzufügen
function seowk_add_bulk_noindex_actions( $bulk_actions ) {
    $bulk_actions['seowk_set_noindex'] = __( 'NoIndex setzen (SEO)', 'seo-wunderkiste' );
    $bulk_actions['seowk_remove_noindex'] = __( 'NoIndex entfernen (SEO)', 'seo-wunderkiste' );
    return $bulk_actions;
}
add_filter( 'bulk_actions-edit-post', 'seowk_add_bulk_noindex_actions' );
add_filter( 'bulk_actions-edit-page', 'seowk_add_bulk_noindex_actions' );

// Bulk Actions verarbeiten
function seowk_handle_bulk_noindex( $redirect_to, $action, $post_ids ) {
    if ( 'seowk_set_noindex' !== $action && 'seowk_remove_noindex' !== $action ) {
        return $redirect_to;
    }

    $changed = 0;

    foreach ( (array) $post_ids as $post_id ) {
        $post_id = (int) $post_id;

        /*
         * WordPress only verifies the bulk nonce for custom bulk actions - it
         * does not check per-object rights. Without this, any user who can
         * reach edit.php could submit arbitrary IDs and de-index the whole
         * site.
         */
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            continue;
        }

        if ( 'seowk_set_noindex' === $action ) {
            update_post_meta( $post_id, '_seowk_noindex', '1' );
        } else {
            delete_post_meta( $post_id, '_seowk_noindex' );
        }

        $changed++;
    }

    $arg = ( 'seowk_set_noindex' === $action ) ? 'seowk_noindex_set' : 'seowk_noindex_removed';

    return add_query_arg( $arg, $changed, $redirect_to );
}
add_filter( 'handle_bulk_actions-edit-post', 'seowk_handle_bulk_noindex', 10, 3 );
add_filter( 'handle_bulk_actions-edit-page', 'seowk_handle_bulk_noindex', 10, 3 );

// Admin Notice für Bulk Actions
function seowk_bulk_noindex_admin_notice() {
    if ( ! empty( $_REQUEST['seowk_noindex_set'] ) ) {
        $count = intval( $_REQUEST['seowk_noindex_set'] );
        printf( '<div class="notice notice-success is-dismissible"><p>%s</p></div>', 
            sprintf( esc_html__( 'NoIndex wurde für %d Einträge gesetzt.', 'seo-wunderkiste' ), $count ) );
    }
    if ( ! empty( $_REQUEST['seowk_noindex_removed'] ) ) {
        $count = intval( $_REQUEST['seowk_noindex_removed'] );
        printf( '<div class="notice notice-success is-dismissible"><p>%s</p></div>', 
            sprintf( esc_html__( 'NoIndex wurde für %d Einträge entfernt.', 'seo-wunderkiste' ), $count ) );
    }
}
add_action( 'admin_notices', 'seowk_bulk_noindex_admin_notice' );

// Spalte hinzufügen
function seowk_add_noindex_column( $columns ) {
    $columns['seowk_noindex_status'] = '<span title="NoIndex Status">🔍 NoIndex</span>';
    return $columns;
}
add_filter( 'manage_posts_columns', 'seowk_add_noindex_column' );
add_filter( 'manage_pages_columns', 'seowk_add_noindex_column' );

// Spalte befüllen
function seowk_fill_noindex_column( $column_name, $post_id ) {
    if ( 'seowk_noindex_status' === $column_name ) {
        $is_noindex = get_post_meta( $post_id, '_seowk_noindex', true );
        if ( $is_noindex ) {
            echo '<span style="color: #d63638; font-weight: bold;" title="' . esc_attr__( 'Wird von Suchmaschinen nicht indexiert', 'seo-wunderkiste' ) . '">✗ NoIndex</span>';
        } else {
            echo '<span style="color: #00a32a;" title="' . esc_attr__( 'Wird von Suchmaschinen indexiert', 'seo-wunderkiste' ) . '">✓ Index</span>';
        }
    }
}
add_action( 'manage_posts_custom_column', 'seowk_fill_noindex_column', 10, 2 );
add_action( 'manage_pages_custom_column', 'seowk_fill_noindex_column', 10, 2 );

// Meta-Tag im Frontend ausgeben
function seowk_output_noindex_meta() {
    if ( ! is_singular() ) {
        return;
    }

    /*
     * The Meta Settings module already prints a robots tag and honours this
     * flag itself. Printing a second one here would put two contradictory
     * robots directives in the same document.
     */
    if ( function_exists( 'seowk_is_module_active' ) && seowk_is_module_active( 'seowk_enable_meta_settings' ) ) {
        return;
    }

    if ( get_post_meta( get_the_ID(), '_seowk_noindex', true ) ) {
        echo '<meta name="robots" content="noindex, nofollow">' . "\n";
    }
}
add_action( 'wp_head', 'seowk_output_noindex_meta', 1 );

// Spalten-CSS
function seowk_noindex_column_css() {
    echo '<style>.column-seowk_noindex_status { width: 100px; text-align: center; }</style>';
}
add_action( 'admin_head', 'seowk_noindex_column_css' );

/* ------------------------------------------------------------------------- *
 * QUICK EDIT SUPPORT
 * ------------------------------------------------------------------------- */

// Quick Edit Feld hinzufügen
function seowk_add_quick_edit_noindex( $column_name, $post_type ) {
    if ( $column_name !== 'seowk_noindex_status' ) {
        return;
    }
    if ( ! in_array( $post_type, array( 'post', 'page' ), true ) ) {
        return;
    }
    ?>
    <fieldset class="inline-edit-col-right">
        <div class="inline-edit-col">
            <label class="inline-edit-seowk-noindex">
                <input type="checkbox" name="seowk_noindex" value="1">
                <span class="checkbox-title"><?php esc_html_e( 'NoIndex (nicht indexieren)', 'seo-wunderkiste' ); ?></span>
            </label>
        </div>
    </fieldset>
    <?php
}
add_action( 'quick_edit_custom_box', 'seowk_add_quick_edit_noindex', 10, 2 );

// Quick Edit speichern
function seowk_save_quick_edit_noindex( $post_id ) {
    // Autosave überspringen
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    
    // Berechtigungen prüfen
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    
    // Nur bei Quick Edit (nicht bei normalem Save)
    if ( ! isset( $_POST['_inline_edit'] ) ) {
        return;
    }
    
    // Nonce prüfen
    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_inline_edit'] ) ), 'inlineeditnonce' ) ) {
        return;
    }
    
    // NoIndex speichern oder löschen
    if ( isset( $_POST['seowk_noindex'] ) && $_POST['seowk_noindex'] === '1' ) {
        update_post_meta( $post_id, '_seowk_noindex', '1' );
    } else {
        delete_post_meta( $post_id, '_seowk_noindex' );
    }
}
add_action( 'save_post', 'seowk_save_quick_edit_noindex' );

// JavaScript für Quick Edit (Wert laden)
function seowk_quick_edit_javascript() {
    $screen = get_current_screen();
    if ( ! $screen || ! in_array( $screen->id, array( 'edit-post', 'edit-page' ), true ) ) {
        return;
    }
    ?>
    <script type="text/javascript">
    jQuery(function($) {
        var $wp_inline_edit = inlineEditPost.edit;
        
        inlineEditPost.edit = function( id ) {
            $wp_inline_edit.apply( this, arguments );
            
            var post_id = 0;
            if ( typeof( id ) === 'object' ) {
                post_id = parseInt( this.getId( id ) );
            }
            
            if ( post_id > 0 ) {
                var $row = $( '#post-' + post_id );
                // Verwende das versteckte Data-Attribut statt Farb-Check
                var $noindex_data = $row.find( '.seowk-noindex-data' );
                var is_noindex = $noindex_data.length > 0 && $noindex_data.data( 'noindex' ) === 1;
                
                // Checkbox setzen
                $( 'input[name="seowk_noindex"]' ).prop( 'checked', is_noindex );
            }
        };
    });
    </script>
    <?php
}
add_action( 'admin_footer', 'seowk_quick_edit_javascript' );

// Verstecktes Feld für JavaScript-Zugriff
function seowk_add_noindex_inline_data( $column_name, $post_id ) {
    if ( $column_name !== 'seowk_noindex_status' ) {
        return;
    }
    $is_noindex = get_post_meta( $post_id, '_seowk_noindex', true ) ? '1' : '0';
    echo '<div class="seowk-noindex-data hidden" data-noindex="' . esc_attr( $is_noindex ) . '"></div>';
}
add_action( 'manage_posts_custom_column', 'seowk_add_noindex_inline_data', 11, 2 );
add_action( 'manage_pages_custom_column', 'seowk_add_noindex_inline_data', 11, 2 );
