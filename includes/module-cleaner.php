<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------------------- *
 * MODUL: Upload Filename Cleaner
 * ------------------------------------------------------------------------- */

function custom_tools_sanitize_upload_filename( $filename ) {
    $info = pathinfo( $filename );
    $ext  = empty( $info['extension'] ) ? '' : '.' . $info['extension'];
    $name = basename( $filename, $ext );
    
    $name = strtolower( $name );
    $ext  = strtolower( $ext );
    $name = str_replace( ['ä', 'ö', 'ü', 'ß'], ['ae', 'oe', 'ue', 'ss'], $name );
    $name = str_replace( ' ', '-', $name );
    
    return $name . $ext;
}
add_filter( 'sanitize_file_name', 'custom_tools_sanitize_upload_filename', 10 );