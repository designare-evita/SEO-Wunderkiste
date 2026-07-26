<?php
/**
 * Module: Decent Lightbox
 *
 * Schlanker, vanilla-JS Lightbox für Mediathek-Bilder.
 * Pro Bild in der Mediathek aktivierbar (Meta-Checkbox).
 *
 * Ursprünglich als eigenständiges Plugin "Decent Lightbox" entwickelt,
 * mit Version 2.10 in die SEO Wunderkiste integriert.
 *
 * @package SEO_Wunderkiste
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! defined( 'DECENT_LIGHTBOX_VERSION' ) ) {
    define( 'DECENT_LIGHTBOX_VERSION', SEOWK_VERSION );
    define( 'DECENT_LIGHTBOX_FILE', __FILE__ );
    define( 'DECENT_LIGHTBOX_PATH', SEOWK_PLUGIN_DIR . 'includes/lightbox/' );
    define( 'DECENT_LIGHTBOX_URL',  SEOWK_PLUGIN_URL  . 'includes/lightbox/' );
    define( 'DECENT_LIGHTBOX_META_KEY', 'decent_lightbox_enabled' );

    require_once DECENT_LIGHTBOX_PATH . 'class-decent-lightbox.php';
    require_once DECENT_LIGHTBOX_PATH . 'class-decent-lightbox-admin.php';
    require_once DECENT_LIGHTBOX_PATH . 'class-decent-lightbox-frontend.php';

    /**
     * Bootstrap der Lightbox-Klasse innerhalb der SEO Wunderkiste.
     */
    function seowk_decent_lightbox() {
        return Decent_Lightbox::instance();
    }

    seowk_decent_lightbox();
}
