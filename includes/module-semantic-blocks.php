<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------------------- *
 * MODUL: SEMANTIC BLOCKS - SEO Wunderkiste
 * Version: 2.9 - Mit eigenem Block-Abschnitt und konfigurierbaren Icons
 * Semantische HTML5 Wrapper-Blöcke
 * ------------------------------------------------------------------------- */

/**
 * Eigene Block-Kategorie "Semantic Blocks" registrieren
 */
function seowk_register_block_category( $categories ) {
    return array_merge(
        array(
            array(
                'slug'  => 'seowk-semantic',
                'title' => __( 'Semantic Blocks', 'seo-wunderkiste' ),
                'icon'  => 'code-standards',
            ),
        ),
        $categories
    );
}
add_filter( 'block_categories_all', 'seowk_register_block_category', 10, 1 );

/**
 * Verfügbare Icons für Details/Accordion Block
 */
function seowk_get_available_icons() {
    return array(
        'arrow'    => array(
            'label'  => __( 'Pfeil', 'seo-wunderkiste' ),
            'closed' => '▶',
            'open'   => '▼',
        ),
        'plus'     => array(
            'label'  => __( 'Plus/Minus', 'seo-wunderkiste' ),
            'closed' => '+',
            'open'   => '−',
        ),
        'chevron'  => array(
            'label'  => __( 'Chevron', 'seo-wunderkiste' ),
            'closed' => '›',
            'open'   => '⌄',
        ),
        'caret'    => array(
            'label'  => __( 'Caret', 'seo-wunderkiste' ),
            'closed' => '⯈',
            'open'   => '⯆',
        ),
        'folder'   => array(
            'label'  => __( 'Ordner', 'seo-wunderkiste' ),
            'closed' => '📁',
            'open'   => '📂',
        ),
        'circle'   => array(
            'label'  => __( 'Kreis', 'seo-wunderkiste' ),
            'closed' => '⊕',
            'open'   => '⊖',
        ),
        'square'   => array(
            'label'  => __( 'Quadrat', 'seo-wunderkiste' ),
            'closed' => '⊞',
            'open'   => '⊟',
        ),
        'dot'      => array(
            'label'  => __( 'Punkt', 'seo-wunderkiste' ),
            'closed' => '●',
            'open'   => '○',
        ),
        'none'     => array(
            'label'  => __( 'Kein Icon', 'seo-wunderkiste' ),
            'closed' => '',
            'open'   => '',
        ),
    );
}

function seowk_register_semantic_blocks() {
    if ( ! function_exists( 'register_block_type' ) ) {
        return;
    }

    // Blöcke mit InnerBlocks Support
    $wrapper_blocks = array(
        'article' => array(
            'title'       => __( 'Article', 'seo-wunderkiste' ),
            'description' => __( 'Semantischer Container für eigenständige Inhalte.', 'seo-wunderkiste' ),
            'icon'        => 'media-text',
        ),
        'section' => array(
            'title'       => __( 'Section', 'seo-wunderkiste' ),
            'description' => __( 'Thematischer Abschnitt mit Überschrift.', 'seo-wunderkiste' ),
            'icon'        => 'screenoptions',
        ),
        'aside'   => array(
            'title'       => __( 'Aside', 'seo-wunderkiste' ),
            'description' => __( 'Ergänzender Inhalt, Sidebar-Element.', 'seo-wunderkiste' ),
            'icon'        => 'align-right',
        ),
        'header'  => array(
            'title'       => __( 'Header', 'seo-wunderkiste' ),
            'description' => __( 'Einleitungsbereich eines Abschnitts.', 'seo-wunderkiste' ),
            'icon'        => 'arrow-up-alt',
        ),
        'footer'  => array(
            'title'       => __( 'Footer', 'seo-wunderkiste' ),
            'description' => __( 'Fußbereich eines Abschnitts.', 'seo-wunderkiste' ),
            'icon'        => 'arrow-down-alt',
        ),
        'main'    => array(
            'title'       => __( 'Main', 'seo-wunderkiste' ),
            'description' => __( 'Hauptinhalt der Seite (nur einmal pro Seite).', 'seo-wunderkiste' ),
            'icon'        => 'editor-expand',
        ),
        'figure'  => array(
            'title'       => __( 'Figure', 'seo-wunderkiste' ),
            'description' => __( 'Abbildung mit optionaler Beschriftung.', 'seo-wunderkiste' ),
            'icon'        => 'format-image',
        ),
        'address' => array(
            'title'       => __( 'Address', 'seo-wunderkiste' ),
            'description' => __( 'Kontaktinformationen.', 'seo-wunderkiste' ),
            'icon'        => 'location',
        ),
        'nav'     => array(
            'title'       => __( 'Nav', 'seo-wunderkiste' ),
            'description' => __( 'Navigationsbereich.', 'seo-wunderkiste' ),
            'icon'        => 'menu',
        ),
    );

    foreach ( $wrapper_blocks as $tag => $config ) {
        register_block_type( 'seowk/' . $tag, array(
            'api_version'     => 3,
            'title'           => $config['title'],
            'description'     => $config['description'],
            'category'        => 'seowk-semantic',
            'icon'            => $config['icon'],
            'supports'        => array(
                'align'           => array( 'wide', 'full' ),
                'anchor'          => true,
                'customClassName' => true,
                'html'            => false,
            ),
            'render_callback' => 'seowk_render_wrapper_block',
            'attributes'      => array(
                'tagName'  => array( 'type' => 'string', 'default' => $tag ),
                'cssClass' => array( 'type' => 'string', 'default' => '' ),
                'cssId'    => array( 'type' => 'string', 'default' => '' ),
            ),
        ) );
    }

    // Details block mit extra attributen und Icon-Auswahl
    register_block_type( 'seowk/details', array(
        'api_version'     => 3,
        'title'           => __( 'Details / Accordion', 'seo-wunderkiste' ),
        'description'     => __( 'Aufklappbarer Bereich mit Summary und konfigurierbarem Icon.', 'seo-wunderkiste' ),
        'category'        => 'seowk-semantic',
        'icon'            => 'arrow-down',
        'supports'        => array(
            'anchor'          => true,
            'customClassName' => true,
        ),
        'render_callback' => 'seowk_render_details_block',
        'attributes'      => array(
            'cssClass'     => array( 'type' => 'string', 'default' => '' ),
            'cssId'        => array( 'type' => 'string', 'default' => '' ),
            'summary'      => array( 'type' => 'string', 'default' => 'Mehr anzeigen' ),
            'open'         => array( 'type' => 'boolean', 'default' => false ),
            'iconStyle'    => array( 'type' => 'string', 'default' => 'arrow' ),
            'iconPosition' => array( 'type' => 'string', 'default' => 'left' ),
        ),
    ) );

    // Mark block (inline)
    register_block_type( 'seowk/mark', array(
        'api_version'     => 3,
        'title'           => __( 'Mark / Highlight', 'seo-wunderkiste' ),
        'description'     => __( 'Hervorgehobener Text.', 'seo-wunderkiste' ),
        'category'        => 'seowk-semantic',
        'icon'            => 'edit',
        'supports'        => array(
            'customClassName' => true,
        ),
        'render_callback' => 'seowk_render_mark_block',
        'attributes'      => array(
            'cssClass' => array( 'type' => 'string', 'default' => '' ),
            'text'     => array( 'type' => 'string', 'default' => '' ),
        ),
    ) );
}
add_action( 'init', 'seowk_register_semantic_blocks' );

/**
 * Universelle Render-Funktion für Wrapper-Blöcke
 */
function seowk_render_wrapper_block( $attributes, $content ) {
    $tag = isset( $attributes['tagName'] ) ? $attributes['tagName'] : 'div';
    $allowed_tags = array( 'article', 'section', 'aside', 'header', 'footer', 'main', 'figure', 'address', 'nav', 'div' );
    
    if ( ! in_array( $tag, $allowed_tags, true ) ) {
        $tag = 'div';
    }
    
    $attrs = seowk_get_block_attributes_html( $attributes );
    
    return '<' . $tag . $attrs . '>' . $content . '</' . $tag . '>';
}

function seowk_get_block_attributes_html( $attributes ) {
    $html = '';
    
    if ( ! empty( $attributes['cssClass'] ) ) {
        $html .= ' class="' . esc_attr( $attributes['cssClass'] ) . '"';
    }
    
    if ( ! empty( $attributes['cssId'] ) ) {
        $html .= ' id="' . esc_attr( $attributes['cssId'] ) . '"';
    }
    
    return $html;
}

function seowk_render_details_block( $attributes, $content ) {
    $css_class = ! empty( $attributes['cssClass'] ) ? ' ' . esc_attr( $attributes['cssClass'] ) : '';
    $css_id = ! empty( $attributes['cssId'] ) ? ' id="' . esc_attr( $attributes['cssId'] ) . '"' : '';
    $open = ! empty( $attributes['open'] ) ? ' open' : '';
    $summary = ! empty( $attributes['summary'] ) ? $attributes['summary'] : __( 'Mehr anzeigen', 'seo-wunderkiste' );
    
    // Icon-Stil
    $icon_style = isset( $attributes['iconStyle'] ) ? $attributes['iconStyle'] : 'arrow';
    $icon_position = isset( $attributes['iconPosition'] ) ? $attributes['iconPosition'] : 'left';
    $icons = seowk_get_available_icons();
    $icon_data = isset( $icons[ $icon_style ] ) ? $icons[ $icon_style ] : $icons['arrow'];
    
    // CSS-Klassen für Icon-Position
    $position_class = 'seowk-icon-' . $icon_position;
    
    // Data-Attribute für JavaScript
    $data_attrs = sprintf(
        ' data-icon-closed="%s" data-icon-open="%s"',
        esc_attr( $icon_data['closed'] ),
        esc_attr( $icon_data['open'] )
    );
    
    // Icon-Span erstellen (wenn nicht "none")
    $icon_span = '';
    if ( $icon_style !== 'none' ) {
        $current_icon = ! empty( $attributes['open'] ) ? $icon_data['open'] : $icon_data['closed'];
        $icon_span = '<span class="seowk-details-icon">' . esc_html( $current_icon ) . '</span>';
    }
    
    // Summary mit Icon je nach Position
    if ( $icon_position === 'right' ) {
        $summary_content = '<span class="seowk-details-text">' . esc_html( $summary ) . '</span>' . $icon_span;
    } else {
        $summary_content = $icon_span . '<span class="seowk-details-text">' . esc_html( $summary ) . '</span>';
    }
    
    return sprintf(
        '<details class="seowk-details seowk-icon-%s %s%s"%s%s%s><summary class="seowk-details-summary">%s</summary><div class="seowk-details-content">%s</div></details>',
        esc_attr( $icon_style ),
        esc_attr( $position_class ),
        $css_class,
        $css_id,
        $open,
        $data_attrs,
        $summary_content,
        $content
    );
}

function seowk_render_mark_block( $attributes, $content ) {
    $attrs = seowk_get_block_attributes_html( $attributes );
    $text = ! empty( $attributes['text'] ) ? $attributes['text'] : $content;
    return '<mark' . $attrs . '>' . esc_html( $text ) . '</mark>';
}

/**
 * Editor Assets laden
 */
function seowk_semantic_blocks_editor_assets() {
    wp_enqueue_script(
        'seowk-semantic-blocks-editor',
        SEOWK_PLUGIN_URL . 'assets/js/semantic-blocks-editor.js',
        array( 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n', 'wp-block-editor' ),
        SEOWK_VERSION,
        true
    );
    
    wp_localize_script( 'seowk-semantic-blocks-editor', 'seowkBlocks', array(
        'blocks' => array(
            array( 'tag' => 'article', 'title' => __( 'Article', 'seo-wunderkiste' ), 'icon' => 'media-text' ),
            array( 'tag' => 'section', 'title' => __( 'Section', 'seo-wunderkiste' ), 'icon' => 'screenoptions' ),
            array( 'tag' => 'aside', 'title' => __( 'Aside', 'seo-wunderkiste' ), 'icon' => 'align-right' ),
            array( 'tag' => 'header', 'title' => __( 'Header', 'seo-wunderkiste' ), 'icon' => 'arrow-up-alt' ),
            array( 'tag' => 'footer', 'title' => __( 'Footer', 'seo-wunderkiste' ), 'icon' => 'arrow-down-alt' ),
            array( 'tag' => 'main', 'title' => __( 'Main', 'seo-wunderkiste' ), 'icon' => 'editor-expand' ),
            array( 'tag' => 'figure', 'title' => __( 'Figure', 'seo-wunderkiste' ), 'icon' => 'format-image' ),
            array( 'tag' => 'address', 'title' => __( 'Address', 'seo-wunderkiste' ), 'icon' => 'location' ),
            array( 'tag' => 'nav', 'title' => __( 'Nav', 'seo-wunderkiste' ), 'icon' => 'menu' ),
        ),
    ) );
    
    wp_enqueue_style(
        'seowk-semantic-blocks-editor',
        SEOWK_PLUGIN_URL . 'assets/css/semantic-blocks-editor.css',
        array( 'wp-edit-blocks' ),
        SEOWK_VERSION
    );
}
add_action( 'enqueue_block_editor_assets', 'seowk_semantic_blocks_editor_assets' );

/**
 * Frontend Styles
 */
function seowk_semantic_blocks_frontend_style() {
    $css = '
        /* Details / Accordion Block */
        .seowk-details { 
            margin: 1em 0; 
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .seowk-details-summary { 
            cursor: pointer; 
            padding: 0.75em 1em; 
            font-weight: 600; 
            background: #f9f9f9;
            border-radius: 4px 4px 0 0;
            list-style: none;
            display: flex;
            align-items: center;
            gap: 0.5em;
        }
        .seowk-details-summary::-webkit-details-marker { display: none; }
        .seowk-details-summary::marker { display: none; content: ""; }
        
        /* Icon Styling */
        .seowk-details-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 1.2em;
            transition: transform 0.2s ease;
        }
        
        /* Icon Position Right */
        .seowk-icon-right .seowk-details-summary {
            justify-content: space-between;
        }
        .seowk-icon-right .seowk-details-text {
            order: -1;
        }
        
        /* Arrow rotation for arrow style */
        .seowk-icon-arrow .seowk-details-icon {
            transition: transform 0.2s ease;
        }
        .seowk-icon-arrow[open] .seowk-details-icon {
            transform: rotate(90deg);
        }
        
        /* Chevron rotation */
        .seowk-icon-chevron .seowk-details-icon {
            transition: transform 0.2s ease;
        }
        .seowk-icon-chevron[open] .seowk-details-icon {
            transform: rotate(90deg);
        }
        
        /* Open state styling */
        .seowk-details[open] .seowk-details-summary {
            border-bottom: 1px solid #ddd;
            border-radius: 4px 4px 0 0;
        }
        
        .seowk-details-content { 
            padding: 1em; 
        }
        
        /* Mark Block */
        mark { 
            background: linear-gradient(120deg, #fff176 0%, #ffee58 100%); 
            padding: 0.1em 0.3em; 
            border-radius: 2px; 
        }
        
        /* Address Block */
        address { 
            font-style: normal; 
        }
    ';
    
    // JavaScript für Icon-Wechsel
    $js = '
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".seowk-details").forEach(function(details) {
            var iconClosed = details.dataset.iconClosed;
            var iconOpen = details.dataset.iconOpen;
            var icon = details.querySelector(".seowk-details-icon");
            
            if (icon && iconClosed && iconOpen) {
                details.addEventListener("toggle", function() {
                    icon.textContent = details.open ? iconOpen : iconClosed;
                });
            }
        });
    });
    ';
    
    wp_register_style( 'seowk-semantic-blocks', false );
    wp_enqueue_style( 'seowk-semantic-blocks' );
    wp_add_inline_style( 'seowk-semantic-blocks', $css );
    
    wp_register_script( 'seowk-semantic-blocks-js', false, array(), SEOWK_VERSION, true );
    wp_enqueue_script( 'seowk-semantic-blocks-js' );
    wp_add_inline_script( 'seowk-semantic-blocks-js', $js );
}
add_action( 'wp_enqueue_scripts', 'seowk_semantic_blocks_frontend_style' );
