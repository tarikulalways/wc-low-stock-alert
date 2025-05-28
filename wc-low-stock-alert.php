<?php
/*
 * Plugin Name:       Low Stock Alert for WooCommerce
 * Plugin URI:        https://wordpress.org/plugins/wc-low-stock-alert/
 * Description:       Get instant email alerts when any product in your WooCommerce store runs low on stock. Stay one step ahead and never lose sales due to out-of-stock items!
 * Version:           1.0.0
 * Requires at least: 5.8
 * Tested up to:      6.8
 * Requires PHP:      7.4
 * Author:            Tarikul
 * Author URI:        https://profiles.wordpress.org/tarikulalways/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wc-low-stock-alert
 */


 if(! defined('ABSPATH')){
    exit;
 }

 class Low_Stock{

    public function __construct(){

        define('LSAW_PLUGIN_PATH', plugin_dir_path(__FILE__));
        define('LSAW_PLUGIN_URL', plugin_dir_url(__FILE__));

        add_action('admin_notices', [$this, 'show_woocommerce_alert']);
        add_action('plugins_loaded', [$this, 'run']);

        register_activation_hook(__FILE__, [$this, 'plugin_active']);
        // register_deactivation_hook(__FILE__, [$this, 'plugin_deactive']);
    }

    public function show_woocommerce_alert(){
        if(! class_exists('WooCommerce')){
            ?>
            <div class="notice notice-error is-dismissible">
                <p>
                    <strong><?php echo esc_html('Low Stock Alert:'); ?> </strong><?php echo esc_html('This plugin requires WooCommerce to be installed and activated.'); ?>
                </p>
            </div>
            <?php
        }
    }

    public function run(){
        if(! class_exists('WooCommerce')){
            return;
        }
        require_once LSAW_PLUGIN_PATH . 'includes/class-controller.php';
        $GLOBALS['lsaw_controller'] = new \LSAW\Includes\LSAW_Controller();
    }

    public function plugin_active(){
        require_once LSAW_PLUGIN_PATH . 'includes/class-activation.php';
        \LSAW\Includes\Activation::active_lsaw();
    }

    // public function plugin_deactive(){
    //     // Remove plugin option
    //     delete_option('stock_threshold');

    //     // Drop custom table if exists
    //     global $wpdb;
    //     $table_name = $wpdb->prefix . 'lsaw_alert_logs';

    //     $wpdb->query("DROP TABLE IF EXISTS {$table_name}");
    // }

 }

new Low_Stock();
