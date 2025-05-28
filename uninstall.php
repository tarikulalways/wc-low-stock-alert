<?php

// Direct access protection
if ( ! defined('WP_UNINSTALL_PLUGIN') ) {
    exit;
}

// Remove plugin option
delete_option('stock_threshold');

// Drop custom table if exists
global $wpdb;

$table_name = $wpdb->prefix . sanitize_key( 'lsaw_alert_logs' );
$wpdb->query( "DROP TABLE IF EXISTS `{$table_name}`" );

