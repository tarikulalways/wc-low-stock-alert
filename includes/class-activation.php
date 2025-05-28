<?php

namespace LSAW\Includes;

if ( ! defined('ABSPATH') ) {
    exit;
}

class Activation {

    public static function active_lsaw() {
        global $wpdb;

        $table = $wpdb->prefix . 'lsaw_alert_logs';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            product_name VARCHAR(255) NOT NULL,
            stock INT NOT NULL,
            alert_time DATETIME DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);

        update_option('stock_threshold', 5);
    }
}
