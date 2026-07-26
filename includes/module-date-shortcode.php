<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------------------- *
 * MODUL: DATE SHORTCODE - SEO Wunderkiste
 * ------------------------------------------------------------------------- */

function seowk_get_german_months() {
    return array( 1 => 'Januar', 2 => 'Februar', 3 => 'März', 4 => 'April', 5 => 'Mai', 6 => 'Juni', 
                  7 => 'Juli', 8 => 'August', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Dezember' );
}

function seowk_get_german_weekdays() {
    return array( 'Monday' => 'Montag', 'Tuesday' => 'Dienstag', 'Wednesday' => 'Mittwoch',
        'Thursday' => 'Donnerstag', 'Friday' => 'Freitag', 'Saturday' => 'Samstag', 'Sunday' => 'Sonntag' );
}

function seowk_get_date_presets() {
    return array( 'numeric' => 'd.m.Y', 'numeric_short' => 'j.n.Y', 'full' => 'j. F Y', 'full_day' => 'l, j. F Y',
        'month_year' => 'F Y', 'year' => 'Y', 'month' => 'F', 'day' => 'l', 'iso' => 'Y-m-d', 'us' => 'm/d/Y',
        'time' => 'H:i', 'datetime' => 'd.m.Y H:i' );
}

function seowk_generate_date_output( $atts ) {
    $atts = wp_parse_args( $atts, array( 'format' => 'numeric', 'timezone' => '', 'prefix' => '', 'suffix' => '', 'wrapper' => '', 'class' => '', 'lang' => 'de' ) );
    
    if ( ! empty( $atts['timezone'] ) ) {
        try { $tz = new DateTimeZone( $atts['timezone'] ); $datetime = new DateTime( 'now', $tz ); }
        catch ( Exception $e ) { $datetime = new DateTime( 'now', wp_timezone() ); }
    } else { $datetime = new DateTime( 'now', wp_timezone() ); }

    $presets = seowk_get_date_presets();
    $format = isset( $presets[ $atts['format'] ] ) ? $presets[ $atts['format'] ] : $atts['format'];
    /*
     * Escaped right here: format() passes unknown characters through verbatim,
     * so a shortcode attribute like format="<\s\c\r\i\p\t>" would otherwise
     * emit raw markup and bypass kses for users without unfiltered_html.
     */
    $date_output = esc_html( $datetime->format( $format ) );

    if ( $atts['lang'] === 'de' ) {
        $months = seowk_get_german_months();
        $english_months = array( 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' );
        $date_output = str_replace( $english_months, array_values( $months ), $date_output );
        $weekdays = seowk_get_german_weekdays();
        $date_output = str_replace( array_keys( $weekdays ), array_values( $weekdays ), $date_output );
    }

    $date_output = esc_html( $atts['prefix'] ) . $date_output . esc_html( $atts['suffix'] );

    if ( ! empty( $atts['wrapper'] ) ) {
        $allowed_tags = array( 'span', 'time', 'div', 'p', 'strong', 'em' );
        $tag = in_array( strtolower( $atts['wrapper'] ), $allowed_tags, true ) ? strtolower( $atts['wrapper'] ) : 'span';
        $attributes = ! empty( $atts['class'] ) ? ' class="' . esc_attr( $atts['class'] ) . '"' : '';
        if ( $tag === 'time' ) { $attributes .= ' datetime="' . $datetime->format( 'c' ) . '"'; }
        $date_output = '<' . $tag . $attributes . '>' . $date_output . '</' . $tag . '>';
    }
    return $date_output;
}

function seowk_date_shortcode( $atts ) {
    $atts = shortcode_atts( array( 'format' => 'numeric', 'timezone' => '', 'prefix' => '', 'suffix' => '', 'wrapper' => '', 'class' => '', 'lang' => 'de' ), $atts, 'seowk_date' );
    return seowk_generate_date_output( $atts );
}
add_shortcode( 'seowk_date', 'seowk_date_shortcode' );
add_shortcode( 'datum', 'seowk_date_shortcode' );

function seowk_jahr_shortcode( $atts ) {
    $atts = is_array( $atts ) ? $atts : array();
    $atts['format'] = 'year';
    return seowk_date_shortcode( $atts );
}
add_shortcode( 'jahr', 'seowk_jahr_shortcode' );

function seowk_monat_shortcode( $atts ) {
    $atts = is_array( $atts ) ? $atts : array();
    $atts['format'] = 'month';
    return seowk_date_shortcode( $atts );
}
add_shortcode( 'monat', 'seowk_monat_shortcode' );
