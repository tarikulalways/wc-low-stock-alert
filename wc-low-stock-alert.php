<?php
/*
 * Plugin Name:       Low Stock Alert for WooCommerce
 * Plugin URI:        https://wordpress.org/plugins/wc-low-stock-alert/
 * Description:       Receive instant email alerts when any WooCommerce product stock runs low. Stay ahead and prevent lost sales due to out-of-stock items.
 * Version:           1.0.0
 * Requires at least: 5.8
 * Tested up to:      6.8
 * Requires PHP:      7.4
 * Author:            Tarikul
 * Author URI:        https://profiles.wordpress.org/tarikulalways/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wc-low-stock-alert
 * Requires Plugins:  woocommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class LSAW_Low_Stock {

    public function __construct() {
        define( 'LSAW_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
        define( 'LSAW_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

        add_action( 'admin_notices', [ $this, 'lsaw_show_woocommerce_alert' ] );
        add_action( 'plugins_loaded', [ $this, 'lsaw_run' ] );

        register_activation_hook( __FILE__, [ $this, 'lsaw_plugin_activate' ] );
    }

    public function lsaw_show_woocommerce_alert() {
        if ( ! class_exists( 'WooCommerce' ) ) {
            ?>
            <div class="notice notice-error is-dismissible">
                <p>
                    <strong><?php echo esc_html__( 'Low Stock Alert:', 'wc-low-stock-alert' ); ?></strong>
                    <?php echo esc_html__( 'This plugin requires WooCommerce to be installed and activated.', 'wc-low-stock-alert' ); ?>
                </p>
            </div>
            <?php
        }
    }

    public function lsaw_run() {
        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }

        require_once LSAW_PLUGIN_PATH . 'includes/class-controller.php';

        // Global controller instance
        $GLOBALS['lsaw_controller'] = new \LSAW\Includes\LSAW_Controller();
    }

    public function lsaw_plugin_activate() {
        require_once LSAW_PLUGIN_PATH . 'includes/class-activation.php';
        \LSAW\Includes\Activation::active_lsaw();
    }

}

new LSAW_Low_Stock();
