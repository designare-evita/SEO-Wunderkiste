<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------------------- *
 * ADMIN SETTINGS PAGE - SEO WUNDERKISTE
 * ------------------------------------------------------------------------- */

// 1. Menüpunkt unter "Einstellungen" hinzufügen
function seowk_add_admin_menu() {
    add_options_page(
        'SEO Wunderkiste Einstellungen', // Seitentitel
        'SEO Wunderkiste',               // Menütitel
        'manage_options',                // Berechtigung
        'seo-wunderkiste',               // Slug
        'seowk_options_page_html'        // Callback
    );
}
add_action( 'admin_menu', 'seowk_add_admin_menu' );

// 2. Einstellungen registrieren
function seowk_settings_init() {
    // Wir speichern alles in einem Array namens 'seowk_settings'
    register_setting( 'seowk_plugin_group', 'seowk_settings' );

    add_settings_section(
        'seowk_plugin_section',
        'Aktive Module der Wunderkiste',
        'seowk_section_callback',
        'seo-wunderkiste'
    );

    /* --- Modul 1: Schema --- */
    add_settings_field(
        'seowk_enable_schema',
        'SEO Schema (JSON-LD)',
        'seowk_checkbox_render',
        'seo-wunderkiste',
        'seowk_plugin_section',
        array( 
            'label_for' => 'seowk_enable_schema',
            'description' => 'Fügt ein Eingabefeld für strukturierte Daten in Beiträge/Seiten ein.' 
        )
    );

    /* --- Modul 2: Resizer --- */
    add_settings_field(
        'seowk_enable_resizer',
        'Image Resizer (800px)',
        'seowk_checkbox_render',
        'seo-wunderkiste',
        'seowk_plugin_section',
        array( 
            'label_for' => 'seowk_enable_resizer',
            'description' => 'Fügt einen Button in den Mediendetails hinzu, um Bilder manuell auf 800px zu skalieren.' 
        )
    );

    /* --- Modul 3: Cleaner --- */
    add_settings_field(
        'seowk_enable_cleaner',
        'Upload Cleaner',
        'seowk_checkbox_render',
        'seo-wunderkiste',
        'seowk_plugin_section',
        array( 
            'label_for' => 'seowk_enable_cleaner',
            'description' => 'Bereinigt Dateinamen beim Upload automatisch (Kleinschreibung, keine Umlaute, Bindestriche).' 
        )
    );

    /* --- Modul 4: Image SEO --- */
    add_settings_field(
        'seowk_enable_image_seo',
        'Zero-Click Image SEO',
        'seowk_checkbox_render',
        'seo-wunderkiste',
        'seowk_plugin_section',
        array( 
            'label_for' => 'seowk_enable_image_seo',
            'description' => 'Generiert automatisch Titel & Alt-Tags aus dem bereinigten Dateinamen.' 
        )
    );

    /* --- Modul 5: Media Inspector --- */
    add_settings_field(
        'seowk_enable_media_columns',
        'Media Library Inspector',
        'seowk_checkbox_render',
        'seo-wunderkiste',
        'seowk_plugin_section',
        array( 
            'label_for' => 'seowk_enable_media_columns',
            'description' => 'Zeigt Dateigröße und Dimensionen direkt in der Listenansicht der Mediathek an.' 
        )
    );

    /* --- Modul 6: Zombie Killer --- */
    add_settings_field(
        'seowk_enable_seo_redirects',
        'SEO Zombie Killer',
        'seowk_checkbox_render',
        'seo-wunderkiste',
        'seowk_plugin_section',
        array( 
            'label_for' => 'seowk_enable_seo_redirects',
            'description' => 'Leitet leere Anhang-Seiten automatisch auf den zugehörigen Beitrag um (verhindert Thin Content).' 
        )
    );
}
add_action( 'admin_init', 'seowk_settings_init' );

// Callback für Beschreibungstext
function seowk_section_callback() {
    echo '<p>Wähle hier die Werkzeuge aus, die du aktivieren möchtest.</p>';
}

// 3. Render-Funktion für Checkboxen
function seowk_checkbox_render( $args ) {
    $options = get_option( 'seowk_settings' );
    $field   = $args['label_for'];
    $checked = isset( $options[ $field ] ) ? $options[ $field ] : false;
    $desc    = isset( $args['description'] ) ? $args['description'] : '';
    ?>
    <input type="checkbox" id="<?php echo esc_attr( $field ); ?>" name="seowk_settings[<?php echo esc_attr( $field ); ?>]" value="1" <?php checked( 1, $checked ); ?>>
    <?php if ( ! empty( $desc ) ) : ?>
        <p class="description" style="display:inline-block; margin-left: 5px; vertical-align: middle;"><?php echo esc_html( $desc ); ?></p>
    <?php endif; ?>
    <?php
}

// 4. HTML Ausgabe der gesamten Seite
function seowk_options_page_html() {
    if ( ! current_user_can( 'manage_options' ) ) { return; }
    ?>
    <div class="wrap">
        <h1>SEO Wunderkiste 📦✨</h1>
        <form action="options.php" method="post">
            <?php
            settings_fields( 'seowk_plugin_group' );
            do_settings_sections( 'seo-wunderkiste' );
            submit_button( 'Einstellungen speichern' );
            ?>
        </form>
    </div>
    <?php
}