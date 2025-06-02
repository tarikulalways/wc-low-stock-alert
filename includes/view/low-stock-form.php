<?php 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( isset( $_POST['low_quantity'], $_POST['_wpnonce'] ) ) {

    // Verify nonce for security
    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'low_stock_nonce' ) ) {
        wp_die( esc_html__( 'Security check failed.', 'wc-low-stock-alert' ) );
    }

    // Sanitize and validate the quantity input
    $quantity = intval( wp_unslash( $_POST['low_quantity'] ) );
    if ( $quantity < 1 ) {
        $quantity = 1; // minimum allowed value
    }

    // Update the option safely
    update_option( 'stock_threshold', $quantity );

    // Optional: Add an admin notice for success message
    add_action( 'admin_notices', function() {
        ?>
        <div class="notice notice-success is-dismissible">
            <p><?php esc_html_e( 'Low stock quantity updated successfully.', 'wc-low-stock-alert' ); ?></p>
        </div>
        <?php
    } );
}

?>

<div class="wrap">
    <h2><?php esc_html_e( 'Low Stock Quantity', 'wc-low-stock-alert' ); ?></h2>

    <form method="post">
        <?php wp_nonce_field( 'low_stock_nonce' ); ?>
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row">
                        <label for="low_quantity"><?php esc_html_e( 'Low Stock Quantity:', 'wc-low-stock-alert' ); ?></label>
                    </th>
                    <td>
                        <input type="number" min="1" id="low_quantity" name="low_quantity" class="regular-text" value="<?php echo esc_attr( get_option( 'stock_threshold', 5 ) ); ?>" required>
                    </td>
                </tr>
                <tr>
                    <th scope="row"></th>
                    <td>
                        <button type="submit" class="button button-primary"><?php esc_html_e( 'Update Quantity', 'wc-low-stock-alert' ); ?></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</div>
