<?php 

    if ( isset( $_POST['low_quantity'], $_POST['_wpnonce'] ) ) {

        // Unslash and sanitize the nonce
        $nonce = sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) );

        if ( wp_verify_nonce( $nonce, 'low_stock_nonce' ) ) {

            // Sanitize the quantity (convert to integer)
            $quantity = intval( wp_unslash( $_POST['low_quantity'] ) );

            // Update the option safely
            update_option( 'stock_threshold', $quantity );

        } else {
            wp_die( 'Security check failed' );
        }

    }


?>
<div class="wrap">
    <h2><?php echo esc_html('Low Stock Quantity'); ?></h2>

    <form method="POST">
        <?php wp_nonce_field('low_stock_nonce'); ?>
        <table>
            <tbody>
                <tr>
                    <th><?php echo esc_html('Low Stock Quantity : '); ?></th>
                    <td>
                        <input type="number" min="1"  name="low_quantity" class="regular-text" value="<?php echo esc_attr(get_option('stock_threshold')); ?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <button type="submit" class="button button-primary"><?php echo esc_html('Update Quantity'); ?></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</div>