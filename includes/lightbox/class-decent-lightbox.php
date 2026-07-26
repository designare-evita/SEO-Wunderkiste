<?php
/**
 * Main plugin class.
 *
 * @package DecentLightbox
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Decent_Lightbox
 *
 * Bootstraps the plugin and registers the meta field that drives the
 * per-image lightbox toggle.
 */
final class Decent_Lightbox {

	/**
	 * Singleton instance.
	 *
	 * @var Decent_Lightbox|null
	 */
	private static ?Decent_Lightbox $instance = null;

	/**
	 * Returns the singleton instance.
	 *
	 * @return Decent_Lightbox
	 */
	public static function instance(): Decent_Lightbox {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		add_action( 'init', array( $this, 'register_meta' ) );

		// Boot subsystems.
		new Decent_Lightbox_Admin();
		new Decent_Lightbox_Frontend();
	}

	/**
	 * Registers the post meta that stores the per-image lightbox flag.
	 *
	 * Registered with `show_in_rest` so the block editor and REST clients
	 * can read and write the value too.
	 *
	 * @return void
	 */
	public function register_meta(): void {
		register_post_meta(
			'attachment',
			DECENT_LIGHTBOX_META_KEY,
			array(
				'type'              => 'boolean',
				'description'       => __( 'Whether to open this image in the Decent Lightbox.', 'seo-wunderkiste' ),
				'single'            => true,
				'default'           => false,
				'show_in_rest'      => true,
				'sanitize_callback' => array( $this, 'sanitize_bool_meta' ),
				'auth_callback'     => static function ( bool $allowed, string $meta_key, int $object_id ): bool {
					return current_user_can( 'edit_post', $object_id );
				},
			)
		);
	}

	/**
	 * Sanitizes a boolean meta value.
	 *
	 * @param mixed $value Raw meta value.
	 * @return bool
	 */
	public function sanitize_bool_meta( $value ): bool {
		return (bool) rest_sanitize_boolean( $value );
	}

	/**
	 * Returns whether lightbox is enabled for the given attachment.
	 *
	 * @param int $attachment_id Attachment ID.
	 * @return bool
	 */
	public static function is_enabled_for_attachment( int $attachment_id ): bool {
		if ( $attachment_id <= 0 ) {
			return false;
		}

		return (bool) get_post_meta( $attachment_id, DECENT_LIGHTBOX_META_KEY, true );
	}
}
