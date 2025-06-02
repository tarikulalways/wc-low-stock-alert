<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$obj = isset( $GLOBALS['lsaw_controller'] ) ? $GLOBALS['lsaw_controller'] : null;

$results = method_exists( $obj, 'show_logs' ) ? $obj->show_logs() : [];
?>

<div class="wrap">
    <h2><?php esc_html_e( 'Show All Low Stock Products', 'wc-low-stock-alert' ); ?></h2>

    <table style="margin-top: 20px;" class="wp-list-table widefat fixed striped table-view-list pages">
        <thead>
            <tr>
                <th><?php esc_html_e( 'SL', 'wc-low-stock-alert' ); ?></th>
                <th><?php esc_html_e( 'Product Name', 'wc-low-stock-alert' ); ?></th>
                <th><?php esc_html_e( 'Stock', 'wc-low-stock-alert' ); ?></th>
                <th><?php esc_html_e( 'Alert Time', 'wc-low-stock-alert' ); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ( ! empty( $results ) ) {
                $i = 1;
                foreach ( $results as $result ) :
                    ?>
                    <tr>
                        <td><?php echo esc_html( $i++ ); ?></td>
                        <td><?php echo esc_html( $result['product_name'] ); ?></td>
                        <td><span class="low-stock"><?php echo esc_html( $result['stock'] ); ?></span></td>
                        <td><?php echo esc_html( $result['alert_time'] ); ?></td>
                    </tr>
                    <?php
                endforeach;
            } else {
                ?>
                <tr>
                    <td colspan="4"><?php esc_html_e( 'No low stock logs found.', 'wc-low-stock-alert' ); ?></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>
