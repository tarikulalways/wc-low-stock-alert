<?php

namespace LSAW\Includes;

if(! defined('ABSPATH')){   
    exit;
}

class LSAW_Controller{

    private $table_name;
    private $database;
    
    public function __construct(){
        global $wpdb;
        
        $this->database = $wpdb;
        $this->table_name = $this->database->prefix . 'lsaw_alert_logs';

        add_action('admin_menu', [$this, 'lsaw_menu']);

        // add_action('woocommerce_product_set_stock', [$this, 'check_stock']);
        add_action( 'woocommerce_order_status_changed', [$this, 'check_stock'], 10, 4 );
        add_action('admin_enqueue_scripts', [$this, 'low_stock_css']);
    }

    // register admin menu
    public function lsaw_menu(){
        add_menu_page('low-stock', 'Low Stock Alert', 'manage_options', 'low-stock', [$this, 'low_stock_admin_form'], 'dashicons-store', 58);

        add_submenu_page('low-stock', 'show logs', 'Logs', 'manage_options', 'show-logs', [$this, 'lsaw_display_logs']);
    }

    // display stock alert quantity change form
    public function low_stock_admin_form(){
        require_once LSAW_PLUGIN_PATH . 'includes/view/low-stock-form.php';
    }

    // display low stock logs file
    public function lsaw_display_logs(){
        require_once LSAW_PLUGIN_PATH . 'includes/view/low-stock-logs.php';
    }

    // include the low stock alert color
    public function low_stock_css($screen){
        if($screen == 'low-stock-alert_page_show-logs'){
            wp_enqueue_style('low-stock-css', LSAW_PLUGIN_URL . 'assets/css/style.css', [], '1.0.0', 'all');
        }
    }

    // low stock quantity main logic
    public function check_stock($order_id, $old_status, $new_status, $order){

        if ( $new_status !== 'processing' && $new_status !== 'completed' ) {
            return;
        }

        $threshold = get_option('stock_threshold');

        foreach ( $order->get_items() as $item ) {
            $product = $item->get_product();
            $product_name = $product->get_name();

            if ( ! $product || ! $product->managing_stock() ) {
                continue;
            }

            $stock_quantity = $product->get_stock_quantity();

            // Check if stock is low
            if ( $stock_quantity !== null && $stock_quantity <= $threshold ) {
                // Send email to admin
                $to      = get_option( 'admin_email' );
                $subject = 'Low Stock Alert: ' . $product->get_name();
                $message = 'Product "' . $product->get_name() . '" has only ' . $stock_quantity . ' item(s) left in stock.';

                wp_mail( $to, $subject, $message );

                $this->database->insert(
                    $this->table_name,
                    [
                        'product_name' => $product_name,
                        'stock' => $stock_quantity,
                        'alert_time' => current_time('mysql')
                    ],
                    [
                        '%s', '%d', '%s'
                    ]
                );
            }
        }

    }

    // display low stock logs retrive
    public function show_logs(){

        $data = $this->database->get_results(
            $this->database->prepare("SELECT * FROM $this->table_name"),
            ARRAY_A
        );

        if ( ! empty( $data ) ) {
            return $data;
        }

    }


}