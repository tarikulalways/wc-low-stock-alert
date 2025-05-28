<?php 
    $obj = $GLOBALS['lsaw_controller'];
    $results = $obj->show_logs();
?>
<div class="wrap">
    <h2><?php echo esc_html('Show all low stock product'); ?></h2>

    <table style="margin-top: 20px;" class="wp-list-table widefat fixed striped table-view-list pages">
        <thead>
            <tr>
                <th>SL</th>
                <th>Product Name</th>
                <th>Stock</th>
                <th>Alert Time</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $i=1; 
                if($results){
                    foreach($results as $result){
            ?>
            <tr>
                <td><?php echo esc_html($i++); ?></td>
                <td><?php echo esc_html($result['product_name']); ?></td>
                <td><span class="low-stock"><?php echo esc_html($result['stock']); ?></span></td>
                <td><?php echo esc_html($result['alert_time']); ?></td>
            </tr>
            <?php
                    }
                }
                
            ?>
        </tbody>
    </table>
</div>