<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------------------- *
 * ADMIN SETTINGS PAGE - SEO WUNDERKISTE
 * ------------------------------------------------------------------------- */

// 1. Menüpunkt hinzufügen
function seowk_add_admin_menu() {
    add_options_page(
        'SEO Wunderkiste Einstellungen',
        'SEO Wunderkiste',
        'manage_options',
        'seo-wunderkiste',
        'seowk_options_page_html'
    );
}
add_action( 'admin_menu', 'seowk_add_admin_menu' );

// 2. Einstellungen registrieren
function seowk_settings_init() {
    register_setting( 'seowk_plugin_group', 'seowk_settings' );

    add_settings_section(
        'seowk_plugin_section',
        'Aktive Module der Wunderkiste',
        'seowk_section_callback',
        'seo-wunderkiste'
    );

    /* --- BESTEHENDE MODULE --- */
    
    add_settings_field(
        'seowk_enable_schema', 'SEO Schema (JSON-LD)', 'seowk_checkbox_render', 'seo-wunderkiste', 'seowk_plugin_section',
        array( 'label_for' => 'seowk_enable_schema', 'description' => 'Fügt ein Eingabefeld für strukturierte Daten hinzu.' )
    );

    add_settings_field(
        'seowk_enable_resizer', 'Image Resizer (800px)', 'seowk_checkbox_render', 'seo-wunderkiste', 'seowk_plugin_section',
        array( 'label_for' => 'seowk_enable_resizer', 'description' => 'Button in Mediendetails zum Skalieren auf 800px.' )
    );

    add_settings_field(
        'seowk_enable_cleaner', 'Upload Cleaner', 'seowk_checkbox_render', 'seo-wunderkiste', 'seowk_plugin_section',
        array( 'label_for' => 'seowk_enable_cleaner', 'description' => 'Dateinamen beim Upload automatisch bereinigen.' )
    );

    add_settings_field(
        'seowk_enable_image_seo', 'Zero-Click Image SEO', 'seowk_checkbox_render', 'seo-wunderkiste', 'seowk_plugin_section',
        array( 'label_for' => 'seowk_enable_image_seo', 'description' => 'Auto-Titel & Alt-Tags aus Dateinamen generieren.' )
    );

    add_settings_field(
        'seowk_enable_media_columns', 'Media Inspector', 'seowk_checkbox_render', 'seo-wunderkiste', 'seowk_plugin_section',
        array( 'label_for' => 'seowk_enable_media_columns', 'description' => 'Zeigt Dateigröße und Pixelmaße in der Übersicht.' )
    );

    add_settings_field(
        'seowk_enable_seo_redirects', 'SEO Zombie Killer', 'seowk_checkbox_render', 'seo-wunderkiste', 'seowk_plugin_section',
        array( 'label_for' => 'seowk_enable_seo_redirects', 'description' => 'Leitet leere Anhang-Seiten auf Beiträge um.' )
    );

    /* --- NEUE MODULE (Updates) --- */

    add_settings_field(
        'seowk_enable_svg', 'SVG Upload erlauben', 'seowk_checkbox_render', 'seo-wunderkiste', 'seowk_plugin_section',
        array( 'label_for' => 'seowk_enable_svg', 'description' => 'Erlaubt das Hochladen von SVG-Dateien in die Mediathek.' )
    );

    add_settings_field(
        'seowk_disable_emojis', 'Emoji-Bloat entfernen', 'seowk_checkbox_render', 'seo-wunderkiste', 'seowk_plugin_section',
        array( 'label_for' => 'seowk_disable_emojis', 'description' => 'Entfernt WordPress Emoji-Skripte für schnellere Ladezeiten.' )
    );

    add_settings_field(
        'seowk_disable_xmlrpc', 'XML-RPC deaktivieren', 'seowk_checkbox_render', 'seo-wunderkiste', 'seowk_plugin_section',
        array( 'label_for' => 'seowk_disable_xmlrpc', 'description' => 'Schließt die XML-RPC Schnittstelle (Schutz vor Angriffen).' )
    );

    /* --- TÜRSTEHER (Special) --- */
    
    add_settings_field(
        'seowk_enable_login_protection', 'Login Türsteher', 'seowk_checkbox_render', 'seo-wunderkiste', 'seowk_plugin_section',
        array( 'label_for' => 'seowk_enable_login_protection', 'description' => 'Versteckt die Login-Seite hinter einem geheimen Parameter.' )
    );

    add_settings_field(
        'seowk_login_protection_key', 'Türsteher Schlüssel', 'seowk_text_render', 'seo-wunderkiste', 'seowk_plugin_section',
        array( 'label_for' => 'seowk_login_protection_key', 'description' => 'Dein geheimes Wort. Login nur via: <code>wp-login.php?DEINWORT</code>. (Standard: hintereingang)' )
    );
}
add_action( 'admin_init', 'seowk_settings_init' );

function seowk_section_callback() {
    echo '<p>Wähle hier die Werkzeuge aus, die du aktivieren möchtest.</p>';
}

// Checkbox Render
function seowk_checkbox_render( $args ) {
    $options = get_option( 'seowk_settings' );
    $field   = $args['label_for'];
    $checked = isset( $options[ $field ] ) ? $options[ $field ] : false;
    $desc    = isset( $args['description'] ) ? $args['description'] : '';
    ?>
    <input type="checkbox" id="<?php echo esc_attr( $field ); ?>" name="seowk_settings[<?php echo esc_attr( $field ); ?>]" value="1" <?php checked( 1, $checked ); ?>>
    <?php if ( ! empty( $desc ) ) : ?>
        <p class="description" style="display:inline-block; margin-left: 5px; vertical-align: middle;"><?php echo $desc; ?></p>
    <?php endif; ?>
    <?php
}

// Text Input Render (NEU)
function seowk_text_render( $args ) {
    $options = get_option( 'seowk_settings' );
    $field   = $args['label_for'];
    $value   = isset( $options[ $field ] ) ? $options[ $field ] : '';
    $desc    = isset( $args['description'] ) ? $args['description'] : '';
    ?>
    <input type="text" id="<?php echo esc_attr( $field ); ?>" name="seowk_settings[<?php echo esc_attr( $field ); ?>]" value="<?php echo esc_attr( $value ); ?>" class="regular-text" placeholder="hintereingang">
    <?php if ( ! empty( $desc ) ) : ?>
        <p class="description"><?php echo $desc; ?></p>
    <?php endif; ?>
    <?php
}

// HTML Page Output
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
