<?php

// Direct access protection
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Remove plugin option
delete_option( 'stock_threshold' );

// Drop custom table if it exists
global $wpdb;

$table_name = $wpdb->prefix . 'lsaw_alert_logs';

// Use prepare to prevent possible SQL injection—even though it's a drop query
$wpdb->query( $wpdb->prepare( "DROP TABLE IF EXISTS `%s`", $table_name ) );
