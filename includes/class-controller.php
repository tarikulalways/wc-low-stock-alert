<?php

namespace LSAW\Includes;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class LSAW_Controller {

    private $table_name;
    private $database;

    public function __construct() {
        global $wpdb;

        $this->database   = $wpdb;
        $this->table_name = $this->database->prefix . 'lsaw_alert_logs';

        add_action( 'admin_menu', [ $this, 'lsaw_menu' ] );
        add_action( 'woocommerce_order_status_changed', [ $this, 'check_stock' ], 10, 4 );
        add_action( 'admin_enqueue_scripts', [ $this, 'low_stock_css' ] );
    }

    public function lsaw_menu() {
        add_menu_page(
            esc_html__( 'Low Stock', 'wc-low-stock-alert' ),
            esc_html__( 'Low Stock Alert', 'wc-low-stock-alert' ),
            'manage_options',
            'low-stock',
            [ $this, 'low_stock_admin_form' ],
            'dashicons-store',
            58
        );

        add_submenu_page(
            'low-stock',
            esc_html__( 'Alert Logs', 'wc-low-stock-alert' ),
            esc_html__( 'Logs', 'wc-low-stock-alert' ),
            'manage_options',
            'show-logs',
            [ $this, 'lsaw_display_logs' ]
        );
    }

    public function low_stock_admin_form() {
        require_once LSAW_PLUGIN_PATH . 'includes/view/low-stock-form.php';
    }

    public function lsaw_display_logs() {
        require_once LSAW_PLUGIN_PATH . 'includes/view/low-stock-logs.php';
    }

    public function low_stock_css( $screen ) {
        if ( $screen === 'low-stock-alert_page_show-logs' ) {
            wp_enqueue_style(
                'low-stock-css',
                LSAW_PLUGIN_URL . 'assets/css/style.css',
                [],
                '1.0.0',
                'all'
            );
        }
    }

    public function check_stock( $order_id, $old_status, $new_status, $order ) {
        if ( $new_status !== 'processing' && $new_status !== 'completed' ) {
            return;
        }

        $threshold = (int) get_option( 'stock_threshold', 5 );

        foreach ( $order->get_items() as $item ) {
            $product       = $item->get_product();
            $product_name  = $product ? $product->get_name() : '';

            if ( ! $product || ! $product->managing_stock() ) {
                continue;
            }

            $stock_quantity = $product->get_stock_quantity();

            if ( $stock_quantity !== null && $stock_quantity <= $threshold ) {
                wp_mail(
                    get_option( 'admin_email' ),
                    sprintf( __( 'Low Stock Alert: %s', 'wc-low-stock-alert' ), $product_name ),
                    sprintf( __( 'Product "%s" has only %d item(s) left in stock.', 'wc-low-stock-alert' ), $product_name, $stock_quantity )
                );

                $this->database->insert(
                    $this->table_name,
                    [
                        'product_name' => $product_name,
                        'stock'        => $stock_quantity,
                        'alert_time'   => current_time( 'mysql' ),
                    ],
                    [ '%s', '%d', '%s' ]
                );
            }
        }
    }

    public function show_logs() {
        return $this->database->get_results(
            "SELECT * FROM {$this->table_name} ORDER BY id DESC LIMIT 100",
            ARRAY_A
        );
    }
}
