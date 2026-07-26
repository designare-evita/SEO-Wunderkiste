<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------------------- *
 * MODUL: XML-RPC deaktivieren
 * ------------------------------------------------------------------------- */

add_filter( 'xmlrpc_enabled', '__return_false' );
