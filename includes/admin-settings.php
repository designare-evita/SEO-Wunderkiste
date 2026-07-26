<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------------------- *
 * ADMIN SETTINGS PAGE - SEO WUNDERKISTE v2.8
 * ------------------------------------------------------------------------- */

function seowk_add_admin_menu() {
    add_options_page(
        __( 'SEO Wunderkiste Einstellungen', 'seo-wunderkiste' ),
        __( 'SEO Wunderkiste', 'seo-wunderkiste' ),
        'manage_options',
        'seo-wunderkiste',
        'seowk_options_page_html'
    );
}
add_action( 'admin_menu', 'seowk_add_admin_menu' );

function seowk_settings_init() {
    register_setting( 
        'seowk_plugin_group', 
        'seowk_settings',
        array(
            'sanitize_callback' => 'seowk_sanitize_settings',
        )
    );

    add_settings_section(
        'seowk_plugin_section',
        __( 'Aktive Module der Wunderkiste', 'seo-wunderkiste' ),
        'seowk_section_callback',
        'seo-wunderkiste'
    );

    // SEO & CONTENT MODULE
    seowk_add_module_field( 'seowk_enable_meta_settings', __( 'SEO Meta Settings', 'seo-wunderkiste' ), __( 'Erweiterte Meta-Tags: Title, Description, Open Graph, Twitter Cards pro Seite.', 'seo-wunderkiste' ) );
    seowk_add_module_field( 'seowk_enable_schema', __( 'SEO Schema (JSON-LD)', 'seo-wunderkiste' ), __( 'Fügt ein Eingabefeld für strukturierte Daten hinzu.', 'seo-wunderkiste' ) );
    seowk_add_module_field( 'seowk_enable_bulk_noindex', __( 'Bulk NoIndex Manager', 'seo-wunderkiste' ), __( 'Ermöglicht das massenhafte Setzen/Entfernen von NoIndex.', 'seo-wunderkiste' ) );
    seowk_add_module_field( 'seowk_enable_seo_redirects', __( 'SEO Zombie Killer', 'seo-wunderkiste' ), __( 'Leitet leere Anhang-Seiten auf Beiträge um (301).', 'seo-wunderkiste' ) );
    seowk_add_module_field( 'seowk_enable_conversion_tracker', __( 'Conversion Tracker', 'seo-wunderkiste' ), __( 'Ermöglicht GA4 und Google Ads Conversion-Tracking.', 'seo-wunderkiste' ) );
    
    // BILD & MEDIA MODULE
    seowk_add_module_field( 'seowk_enable_resizer', __( 'Image Resizer (800px/1200px)', 'seo-wunderkiste' ), __( 'Button in Mediendetails zum Skalieren (92% Qualität).', 'seo-wunderkiste' ) );
    seowk_add_module_field( 'seowk_enable_cleaner', __( 'Upload Cleaner', 'seo-wunderkiste' ), __( 'Dateinamen beim Upload automatisch bereinigen.', 'seo-wunderkiste' ) );
    seowk_add_module_field( 'seowk_enable_image_seo', __( 'Zero-Click Image SEO', 'seo-wunderkiste' ), __( 'Auto-Titel & Alt-Tags aus Dateinamen generieren.', 'seo-wunderkiste' ) );
    seowk_add_module_field( 'seowk_enable_media_columns', __( 'Media Inspector', 'seo-wunderkiste' ), __( 'Zeigt Dateigröße und Pixelmaße in der Medienübersicht.', 'seo-wunderkiste' ) );
    seowk_add_module_field( 'seowk_enable_svg', __( 'SVG Upload Support', 'seo-wunderkiste' ), __( 'Erlaubt das Hochladen von SVG-Dateien mit Sicherheits-Sanitization.', 'seo-wunderkiste' ) );
    seowk_add_module_field( 'seowk_enable_lightbox', __( 'Decent Lightbox', 'seo-wunderkiste' ), __( 'Schlanker Bild-Lightbox: Pro Bild in der Mediathek aktivierbar (Vanilla JS, ohne Abhängigkeiten).', 'seo-wunderkiste' ) );
    
    // PERFORMANCE MODULE
    seowk_add_module_field( 'seowk_disable_emojis', __( 'Emoji Bloat Remover', 'seo-wunderkiste' ), __( 'Entfernt WordPress Emoji-Skripte für schnellere Ladezeiten.', 'seo-wunderkiste' ) );
    
    // SICHERHEIT & ADMIN MODULE
    seowk_add_module_field( 'seowk_disable_xmlrpc', __( 'XML-RPC Blocker', 'seo-wunderkiste' ), __( 'Schließt die XML-RPC Schnittstelle.', 'seo-wunderkiste' ) );
    seowk_add_module_field( 'seowk_enable_login_protection', __( 'Login Türsteher', 'seo-wunderkiste' ), __( 'Versteckt die Login-Seite hinter einem geheimen Parameter.', 'seo-wunderkiste' ) );
    
    add_settings_field(
        'seowk_login_protection_key',
        __( 'Türsteher Schlüssel', 'seo-wunderkiste' ),
        'seowk_text_render',
        'seo-wunderkiste',
        'seowk_plugin_section',
        array(
            'label_for' => 'seowk_login_protection_key',
            'description' => __( 'Dein geheimes Wort. Login danach nur noch über <code>wp-login.php?DEINWORT</code>. Ohne Eintrag bleibt das Modul wirkungslos – einen Standardwert gibt es bewusst nicht. Logout, Passwort-Reset und geschützte Beiträge funktionieren weiterhin ohne Schlüssel.', 'seo-wunderkiste' )
        )
    );
    
    seowk_add_module_field( 'seowk_enable_comment_blocker', __( 'Comment Blocker', 'seo-wunderkiste' ), __( 'Deaktiviert Kommentare global auf der Website.', 'seo-wunderkiste' ) );
    seowk_add_module_field( 'seowk_enable_id_column', __( 'ID Column Display', 'seo-wunderkiste' ), __( 'Zeigt die Post/Page/Media ID in allen Übersichten an.', 'seo-wunderkiste' ) );
    
    // CONTENT TOOLS MODULE
    seowk_add_module_field( 'seowk_enable_date_shortcode', __( 'Date Shortcode', 'seo-wunderkiste' ), __( 'Fügt aktuelles Datum via Shortcode ein.', 'seo-wunderkiste' ) );
    seowk_add_module_field( 'seowk_enable_semantic_blocks', __( 'Semantic Blocks', 'seo-wunderkiste' ), __( 'HTML5 Wrapper-Blöcke für bessere Struktur und SEO.', 'seo-wunderkiste' ) );
    
    // ZUSÄTZLICHE EINSTELLUNGEN SECTION
    add_settings_section(
        'seowk_additional_section',
        __( 'Zusätzliche Einstellungen', 'seo-wunderkiste' ),
        'seowk_additional_section_callback',
        'seo-wunderkiste'
    );
    
    // Währung für Conversion Tracking
    add_settings_field(
        'seowk_conversion_currency',
        __( 'Conversion Währung', 'seo-wunderkiste' ),
        'seowk_currency_render',
        'seo-wunderkiste',
        'seowk_additional_section',
        array(
            'label_for' => 'seowk_conversion_currency',
            'description' => __( 'Währungscode für GA4 und Google Ads Conversion Tracking.', 'seo-wunderkiste' )
        )
    );
}
add_action( 'admin_init', 'seowk_settings_init' );

function seowk_add_module_field( $id, $title, $description ) {
    add_settings_field(
        $id,
        $title,
        'seowk_checkbox_render',
        'seo-wunderkiste',
        'seowk_plugin_section',
        array(
            'label_for' => $id,
            'description' => $description
        )
    );
}

function seowk_sanitize_settings( $input ) {
    $sanitized = array();
    
    $checkbox_fields = array(
        'seowk_enable_meta_settings', 'seowk_enable_schema', 'seowk_enable_bulk_noindex',
        'seowk_enable_seo_redirects', 'seowk_enable_conversion_tracker', 'seowk_enable_resizer',
        'seowk_enable_cleaner', 'seowk_enable_image_seo', 'seowk_enable_media_columns',
        'seowk_enable_svg', 'seowk_enable_lightbox', 'seowk_disable_emojis', 'seowk_disable_xmlrpc',
        'seowk_enable_login_protection', 'seowk_enable_comment_blocker', 'seowk_enable_id_column',
        'seowk_enable_date_shortcode', 'seowk_enable_semantic_blocks',
    );
    
    foreach ( $checkbox_fields as $field ) {
        $sanitized[ $field ] = ! empty( $input[ $field ] ) ? 1 : 0;
    }
    
    if ( isset( $input['seowk_login_protection_key'] ) ) {
        // Restrict to characters that survive as a URL query key unchanged.
        $key = sanitize_text_field( wp_unslash( $input['seowk_login_protection_key'] ) );
        $key = (string) preg_replace( '/[^A-Za-z0-9_-]/', '', $key );

        $sanitized['seowk_login_protection_key'] = $key;
    }
    
    // Währung validieren (3-Buchstaben-Code)
    if ( isset( $input['seowk_conversion_currency'] ) ) {
        $currency = strtoupper( sanitize_text_field( $input['seowk_conversion_currency'] ) );
        $currency = preg_replace( '/[^A-Z]/', '', $currency );
        $sanitized['seowk_conversion_currency'] = substr( $currency, 0, 3 );
    }
    
    return $sanitized;
}

function seowk_section_callback() {
    echo '<p style="font-size: 14px; color: #666;">' . esc_html__( 'Wähle hier die Werkzeuge aus, die du aktivieren möchtest.', 'seo-wunderkiste' ) . '</p>';
}

function seowk_additional_section_callback() {
    echo '<p style="font-size: 14px; color: #666;">' . esc_html__( 'Weitere Konfigurationsoptionen für aktive Module.', 'seo-wunderkiste' ) . '</p>';
}

function seowk_checkbox_render( $args ) {
    $options = get_option( 'seowk_settings' );
    $field   = $args['label_for'];
    $checked = isset( $options[ $field ] ) ? $options[ $field ] : false;
    $desc    = isset( $args['description'] ) ? $args['description'] : '';
    ?>
    <label style="display: flex; align-items: center;">
        <input type="checkbox" id="<?php echo esc_attr( $field ); ?>" name="seowk_settings[<?php echo esc_attr( $field ); ?>]" value="1" <?php checked( 1, $checked ); ?>>
        <?php if ( ! empty( $desc ) ) : ?>
            <span style="margin-left: 8px; color: #666;"><?php echo esc_html( $desc ); ?></span>
        <?php endif; ?>
    </label>
    <?php
}

function seowk_text_render( $args ) {
    $options = get_option( 'seowk_settings' );
    $field   = $args['label_for'];
    $value   = isset( $options[ $field ] ) ? $options[ $field ] : '';
    $desc    = isset( $args['description'] ) ? $args['description'] : '';
    ?>
    <input type="text" id="<?php echo esc_attr( $field ); ?>" name="seowk_settings[<?php echo esc_attr( $field ); ?>]" value="<?php echo esc_attr( $value ); ?>" class="regular-text" autocomplete="off" placeholder="<?php esc_attr_e( 'langes, zufälliges Wort', 'seo-wunderkiste' ); ?>">
    <?php if ( ! empty( $desc ) ) : ?>
        <p class="description"><?php echo wp_kses( $desc, array( 'code' => array() ) ); ?></p>
    <?php endif; ?>
    <?php
}

function seowk_currency_render( $args ) {
    $options = get_option( 'seowk_settings' );
    $field   = $args['label_for'];
    $value   = isset( $options[ $field ] ) ? $options[ $field ] : 'EUR';
    $desc    = isset( $args['description'] ) ? $args['description'] : '';
    
    $currencies = array(
        'EUR' => 'EUR - Euro',
        'USD' => 'USD - US Dollar',
        'GBP' => 'GBP - British Pound',
        'CHF' => 'CHF - Swiss Franc',
        'AUD' => 'AUD - Australian Dollar',
        'CAD' => 'CAD - Canadian Dollar',
        'JPY' => 'JPY - Japanese Yen',
        'CNY' => 'CNY - Chinese Yuan',
        'INR' => 'INR - Indian Rupee',
        'BRL' => 'BRL - Brazilian Real',
        'MXN' => 'MXN - Mexican Peso',
        'PLN' => 'PLN - Polish Zloty',
        'SEK' => 'SEK - Swedish Krona',
        'NOK' => 'NOK - Norwegian Krone',
        'DKK' => 'DKK - Danish Krone',
        'CZK' => 'CZK - Czech Koruna',
        'HUF' => 'HUF - Hungarian Forint',
        'RUB' => 'RUB - Russian Ruble',
        'TRY' => 'TRY - Turkish Lira',
        'ZAR' => 'ZAR - South African Rand',
    );
    ?>
    <select id="<?php echo esc_attr( $field ); ?>" name="seowk_settings[<?php echo esc_attr( $field ); ?>]">
        <?php foreach ( $currencies as $code => $label ) : ?>
            <option value="<?php echo esc_attr( $code ); ?>" <?php selected( $value, $code ); ?>>
                <?php echo esc_html( $label ); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php if ( ! empty( $desc ) ) : ?>
        <p class="description"><?php echo esc_html( $desc ); ?></p>
    <?php endif; ?>
    <p class="description">
        <code><?php esc_html_e( 'Filter:', 'seo-wunderkiste' ); ?> seowk_conversion_currency</code>
    </p>
    <?php
}

function seowk_options_page_html() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    settings_errors( 'seowk_messages' );
    ?>
    <div class="wrap">
        <h1 style="display: flex; align-items: center; gap: 10px;">
            <span>📦</span>
            <span><?php esc_html_e( 'SEO Wunderkiste', 'seo-wunderkiste' ); ?></span>
            <span style="font-size: 14px; background: #2271b1; color: white; padding: 4px 12px; border-radius: 3px;">v<?php echo esc_html( SEOWK_VERSION ); ?></span>
        </h1>
        
        <p style="font-size: 16px; margin: 20px 0;">
            <?php esc_html_e( 'Deine modulare All-in-One Lösung für SEO, Performance und Verwaltung.', 'seo-wunderkiste' ); ?>
        </p>

        <div style="background: #f0f6fc; border-left: 4px solid #2271b1; padding: 15px; margin: 20px 0;">
            <h3 style="margin-top: 0;">💡 <?php esc_html_e( 'So funktioniert\'s:', 'seo-wunderkiste' ); ?></h3>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li>✅ <?php esc_html_e( 'Aktiviere nur die Module, die du wirklich brauchst', 'seo-wunderkiste' ); ?></li>
                <li>🚀 <?php esc_html_e( 'Jedes Modul arbeitet unabhängig und performant', 'seo-wunderkiste' ); ?></li>
                <li>🔒 <?php esc_html_e( 'Standardmäßig sind alle Module deaktiviert', 'seo-wunderkiste' ); ?></li>
            </ul>
        </div>

        <form action="options.php" method="post" style="background: white; border: 1px solid #ccd0d4; padding: 20px; border-radius: 4px;">
            <?php
            settings_fields( 'seowk_plugin_group' );
            do_settings_sections( 'seo-wunderkiste' );
            submit_button( __( 'Einstellungen speichern', 'seo-wunderkiste' ), 'primary large' );
            ?>
        </form>

        <div style="margin: 30px 0; padding: 15px; background: #f9f9f9; border-radius: 4px; text-align: center; color: #666;">
            <p style="margin: 0;">
                <?php 
                printf( 
                    /* translators: %s: author name */
                    esc_html__( 'Entwickelt mit ❤️ von %s', 'seo-wunderkiste' ), 
                    '<strong>Michael Kanda</strong>' 
                ); 
                ?>
            </p>
        </div>
    </div>

    <style>
    .form-table th { width: 250px; font-weight: 600; }
    .form-table td { padding: 15px 10px; }
    </style>
    <?php
}
