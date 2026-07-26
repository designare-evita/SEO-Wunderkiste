/**
 * SEO Wunderkiste - Semantic Blocks Editor
 * Version: 2.9 - Mit Icon-Auswahl und eigener Kategorie
 * Gutenberg Block Registration for HTML5 Semantic Elements
 */
( function( blocks, element, blockEditor, components, i18n ) {
    const { registerBlockType } = blocks;
    const { createElement: el, Fragment } = element;
    const { InnerBlocks, InspectorControls, useBlockProps } = blockEditor;
    const { PanelBody, TextControl, ToggleControl, SelectControl, RadioControl } = components;
    const { __ } = i18n;

    // Verfügbare Icon-Stile für Details Block
    const iconStyles = [
        { label: __( 'Pfeil (▶ ▼)', 'seo-wunderkiste' ), value: 'arrow', closed: '▶', open: '▼' },
        { label: __( 'Plus/Minus (+ −)', 'seo-wunderkiste' ), value: 'plus', closed: '+', open: '−' },
        { label: __( 'Chevron (› ⌄)', 'seo-wunderkiste' ), value: 'chevron', closed: '›', open: '⌄' },
        { label: __( 'Caret (⯈ ⯆)', 'seo-wunderkiste' ), value: 'caret', closed: '⯈', open: '⯆' },
        { label: __( 'Ordner (📁 📂)', 'seo-wunderkiste' ), value: 'folder', closed: '📁', open: '📂' },
        { label: __( 'Kreis (⊕ ⊖)', 'seo-wunderkiste' ), value: 'circle', closed: '⊕', open: '⊖' },
        { label: __( 'Quadrat (⊞ ⊟)', 'seo-wunderkiste' ), value: 'square', closed: '⊞', open: '⊟' },
        { label: __( 'Punkt (● ○)', 'seo-wunderkiste' ), value: 'dot', closed: '●', open: '○' },
        { label: __( 'Kein Icon', 'seo-wunderkiste' ), value: 'none', closed: '', open: '' },
    ];

    // Helper: Icon für aktuellen Stil finden
    function getIconForStyle( styleValue, isOpen ) {
        const style = iconStyles.find( s => s.value === styleValue ) || iconStyles[0];
        return isOpen ? style.open : style.closed;
    }

    // Wrapper Blocks (article, section, aside, etc.)
    const wrapperBlocks = [
        { tag: 'article', title: 'Article', icon: 'media-text', description: __( 'Semantischer Container für eigenständige Inhalte.', 'seo-wunderkiste' ) },
        { tag: 'section', title: 'Section', icon: 'screenoptions', description: __( 'Thematischer Abschnitt mit Überschrift.', 'seo-wunderkiste' ) },
        { tag: 'aside', title: 'Aside', icon: 'align-right', description: __( 'Ergänzender Inhalt, Sidebar-Element.', 'seo-wunderkiste' ) },
        { tag: 'header', title: 'Header', icon: 'arrow-up-alt', description: __( 'Einleitungsbereich eines Abschnitts.', 'seo-wunderkiste' ) },
        { tag: 'footer', title: 'Footer', icon: 'arrow-down-alt', description: __( 'Fußbereich eines Abschnitts.', 'seo-wunderkiste' ) },
        { tag: 'main', title: 'Main', icon: 'editor-expand', description: __( 'Hauptinhalt der Seite (nur einmal pro Seite).', 'seo-wunderkiste' ) },
        { tag: 'figure', title: 'Figure', icon: 'format-image', description: __( 'Abbildung mit optionaler Beschriftung.', 'seo-wunderkiste' ) },
        { tag: 'address', title: 'Address', icon: 'location', description: __( 'Kontaktinformationen.', 'seo-wunderkiste' ) },
        { tag: 'nav', title: 'Nav', icon: 'menu', description: __( 'Navigationsbereich.', 'seo-wunderkiste' ) },
    ];

    // Register each wrapper block
    wrapperBlocks.forEach( function( config ) {
        registerBlockType( 'seowk/' + config.tag, {
            title: config.title,
            description: config.description,
            icon: config.icon,
            category: 'seowk-semantic',
            supports: {
                align: [ 'wide', 'full' ],
                anchor: true,
                customClassName: true,
                html: false,
            },
            attributes: {
                tagName: { type: 'string', default: config.tag },
                cssClass: { type: 'string', default: '' },
                cssId: { type: 'string', default: '' },
            },
            edit: function( props ) {
                const { attributes, setAttributes } = props;
                const blockProps = useBlockProps( {
                    className: 'seowk-semantic-block seowk-semantic-' + config.tag + ( attributes.cssClass ? ' ' + attributes.cssClass : '' ),
                } );

                return el( Fragment, {},
                    el( InspectorControls, {},
                        el( PanelBody, { title: __( 'Einstellungen', 'seo-wunderkiste' ), initialOpen: true },
                            el( TextControl, {
                                label: __( 'CSS Klasse', 'seo-wunderkiste' ),
                                value: attributes.cssClass,
                                onChange: function( value ) { setAttributes( { cssClass: value } ); },
                            } ),
                            el( TextControl, {
                                label: __( 'CSS ID', 'seo-wunderkiste' ),
                                value: attributes.cssId,
                                onChange: function( value ) { setAttributes( { cssId: value } ); },
                            } )
                        )
                    ),
                    el( 'div', blockProps,
                        el( 'div', { className: 'seowk-semantic-label' }, '<' + config.tag + '>' ),
                        el( InnerBlocks, { 
                            templateLock: false,
                            renderAppender: InnerBlocks.ButtonBlockAppender,
                        } ),
                        el( 'div', { className: 'seowk-semantic-label seowk-semantic-label-end' }, '</' + config.tag + '>' )
                    )
                );
            },
            save: function() {
                return el( InnerBlocks.Content );
            },
        } );
    } );

    // Details/Accordion Block mit Icon-Auswahl
    registerBlockType( 'seowk/details', {
        title: __( 'Details / Accordion', 'seo-wunderkiste' ),
        description: __( 'Aufklappbarer Bereich mit Summary und konfigurierbarem Icon.', 'seo-wunderkiste' ),
        icon: 'arrow-down',
        category: 'seowk-semantic',
        supports: {
            anchor: true,
            customClassName: true,
        },
        attributes: {
            cssClass: { type: 'string', default: '' },
            cssId: { type: 'string', default: '' },
            summary: { type: 'string', default: 'Mehr anzeigen' },
            open: { type: 'boolean', default: false },
            iconStyle: { type: 'string', default: 'arrow' },
            iconPosition: { type: 'string', default: 'left' },
        },
        edit: function( props ) {
            const { attributes, setAttributes } = props;
            const blockProps = useBlockProps( {
                className: 'seowk-details-editor seowk-icon-' + attributes.iconStyle + ' seowk-icon-' + attributes.iconPosition + ( attributes.cssClass ? ' ' + attributes.cssClass : '' ),
            } );

            const currentIcon = getIconForStyle( attributes.iconStyle, attributes.open );
            const iconPositionOptions = [
                { label: __( 'Links', 'seo-wunderkiste' ), value: 'left' },
                { label: __( 'Rechts', 'seo-wunderkiste' ), value: 'right' },
            ];

            return el( Fragment, {},
                el( InspectorControls, {},
                    el( PanelBody, { title: __( 'Summary & Verhalten', 'seo-wunderkiste' ), initialOpen: true },
                        el( TextControl, {
                            label: __( 'Summary Text', 'seo-wunderkiste' ),
                            value: attributes.summary,
                            onChange: function( value ) { setAttributes( { summary: value } ); },
                        } ),
                        el( ToggleControl, {
                            label: __( 'Standardmäßig geöffnet', 'seo-wunderkiste' ),
                            checked: attributes.open,
                            onChange: function( value ) { setAttributes( { open: value } ); },
                        } )
                    ),
                    el( PanelBody, { title: __( 'Icon Einstellungen', 'seo-wunderkiste' ), initialOpen: true },
                        el( SelectControl, {
                            label: __( 'Icon Stil', 'seo-wunderkiste' ),
                            value: attributes.iconStyle,
                            options: iconStyles.map( function( style ) {
                                return { label: style.label, value: style.value };
                            } ),
                            onChange: function( value ) { setAttributes( { iconStyle: value } ); },
                        } ),
                        el( RadioControl, {
                            label: __( 'Icon Position', 'seo-wunderkiste' ),
                            selected: attributes.iconPosition,
                            options: iconPositionOptions,
                            onChange: function( value ) { setAttributes( { iconPosition: value } ); },
                        } ),
                        el( 'div', { className: 'seowk-icon-preview', style: { marginTop: '15px', padding: '10px', background: '#f0f0f0', borderRadius: '4px', textAlign: 'center' } },
                            el( 'p', { style: { margin: '0 0 5px', fontSize: '12px', color: '#666' } }, __( 'Vorschau:', 'seo-wunderkiste' ) ),
                            el( 'span', { style: { fontSize: '24px', display: 'block' } }, 
                                getIconForStyle( attributes.iconStyle, false ) + ' → ' + getIconForStyle( attributes.iconStyle, true )
                            )
                        )
                    ),
                    el( PanelBody, { title: __( 'CSS Einstellungen', 'seo-wunderkiste' ), initialOpen: false },
                        el( TextControl, {
                            label: __( 'CSS Klasse', 'seo-wunderkiste' ),
                            value: attributes.cssClass,
                            onChange: function( value ) { setAttributes( { cssClass: value } ); },
                        } ),
                        el( TextControl, {
                            label: __( 'CSS ID', 'seo-wunderkiste' ),
                            value: attributes.cssId,
                            onChange: function( value ) { setAttributes( { cssId: value } ); },
                        } )
                    )
                ),
                el( 'div', blockProps,
                    el( 'div', { 
                        className: 'seowk-details-summary-editor',
                        style: { 
                            flexDirection: attributes.iconPosition === 'right' ? 'row-reverse' : 'row',
                            justifyContent: attributes.iconPosition === 'right' ? 'space-between' : 'flex-start'
                        }
                    },
                        attributes.iconStyle !== 'none' && el( 'span', { 
                            className: 'seowk-details-icon-editor',
                            style: { fontSize: '16px', minWidth: '24px', textAlign: 'center' }
                        }, currentIcon ),
                        el( TextControl, {
                            value: attributes.summary,
                            onChange: function( value ) { setAttributes( { summary: value } ); },
                            placeholder: __( 'Summary eingeben...', 'seo-wunderkiste' ),
                            style: { flex: 1 }
                        } )
                    ),
                    el( 'div', { className: 'seowk-details-content-editor' },
                        el( InnerBlocks, { 
                            templateLock: false,
                            renderAppender: InnerBlocks.ButtonBlockAppender,
                        } )
                    )
                )
            );
        },
        save: function() {
            return el( InnerBlocks.Content );
        },
    } );

    // Mark/Highlight Block
    registerBlockType( 'seowk/mark', {
        title: __( 'Mark / Highlight', 'seo-wunderkiste' ),
        description: __( 'Hervorgehobener Text.', 'seo-wunderkiste' ),
        icon: 'edit',
        category: 'seowk-semantic',
        supports: {
            customClassName: true,
        },
        attributes: {
            cssClass: { type: 'string', default: '' },
            text: { type: 'string', default: '' },
        },
        edit: function( props ) {
            const { attributes, setAttributes } = props;
            const blockProps = useBlockProps( {
                className: 'seowk-mark-editor',
            } );

            return el( Fragment, {},
                el( InspectorControls, {},
                    el( PanelBody, { title: __( 'Einstellungen', 'seo-wunderkiste' ), initialOpen: true },
                        el( TextControl, {
                            label: __( 'CSS Klasse', 'seo-wunderkiste' ),
                            value: attributes.cssClass,
                            onChange: function( value ) { setAttributes( { cssClass: value } ); },
                        } )
                    )
                ),
                el( 'div', blockProps,
                    el( 'mark', { className: attributes.cssClass || '' },
                        el( TextControl, {
                            value: attributes.text,
                            onChange: function( value ) { setAttributes( { text: value } ); },
                            placeholder: __( 'Hervorgehobenen Text eingeben...', 'seo-wunderkiste' ),
                        } )
                    )
                )
            );
        },
        save: function( props ) {
            const { attributes } = props;
            return el( 'mark', { className: attributes.cssClass || null }, attributes.text );
        },
    } );

} )(
    window.wp.blocks,
    window.wp.element,
    window.wp.blockEditor,
    window.wp.components,
    window.wp.i18n
);
