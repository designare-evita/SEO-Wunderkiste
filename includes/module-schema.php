<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------------------- *
 * MODUL: Custom Schema Meta Box (JSON-LD)
 * ------------------------------------------------------------------------- */

function custom_schema_add_meta_box() {
    $screens = ['post', 'page'];
    foreach ($screens as $screen) {
        add_meta_box(
            'custom_schema_box_id',
            'Strukturierte Daten (JSON-LD)',
            'custom_schema_render_meta_box',
            $screen,
            'normal',
            'high'
        );
    }
}
add_action('add_meta_boxes', 'custom_schema_add_meta_box');

function custom_schema_render_meta_box($post) {
    $value = get_post_meta($post->ID, '_custom_schema_value', true);
    wp_nonce_field('custom_schema_save_data', 'custom_schema_nonce');
    
    echo '<p><label for="custom_schema_field">Füge hier dein JSON-LD Objekt ein (ohne &lt;script&gt; Tags):</label></p>';
    echo '<textarea id="custom_schema_field" name="custom_schema_field" rows="10" style="width:100%; font-family:monospace;">' .
    esc_textarea($value) . '</textarea>';
    echo '<p class="description">Beispiel: { "@context": "https://schema.org", "@type": "Article", ... }</p>';
}

function custom_schema_save_postdata($post_id) {
    if (!isset($_POST['custom_schema_nonce']) || !wp_verify_nonce($_POST['custom_schema_nonce'], 'custom_schema_save_data')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    if (array_key_exists('custom_schema_field', $_POST)) {
        update_post_meta($post_id, '_custom_schema_value', $_POST['custom_schema_field']);
    }
}
add_action('save_post', 'custom_schema_save_postdata');

function custom_schema_output_head() {
    if (is_singular()) {
        $post_id = get_the_ID();
        $schema_json = get_post_meta($post_id, '_custom_schema_value', true);

        if (!empty($schema_json)) {
            echo "\n" . '<script type="application/ld+json">' . "\n";
            echo $schema_json; 
            echo "\n" . '</script>' . "\n";
        }
    }
}
add_action('wp_head', 'custom_schema_output_head');