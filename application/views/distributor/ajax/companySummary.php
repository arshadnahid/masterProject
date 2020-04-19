<table class="table table-bordered cusTable" style="height:200px;">
    <thead>
        <tr>
            <th>Account Head</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><a href="<?php echo site_url('generalLedger/58'); ?>">Account Receivable</a></td>
            <td align="right"><?php
if ($accountReceiable < 0):
    echo '(' . number_format(abs($accountReceiable)) . ')';
else:
    echo number_format($accountReceiable);
endif;
?></td>
        </tr>
        <tr>
            <td><a  href="<?php echo site_url('generalLedger/50'); ?>">Account Payable</a></td>
            <td align="right"><?php echo number_format(abs($accountPayable)); ?></td>
        </tr>
        <tr>
            <td><a href="<?php echo site_url('generalLedger/54'); ?>">Cash In Hand</a></td>
            <td align="right"><?php
                if ($cashInHand < 0):
                    echo '(' . number_format(abs($cashInHand)) . ')';
                else:
                    echo number_format($cashInHand);
                endif;
?></td>
        </tr>
        <tr>
            <td><a href="<?php echo site_url('bankBook'); ?>">Cash at Bank</a></td>
            <td align="right"><?php
                if ($totalCashAtBank < 0):
                    echo '(' . number_format(abs($totalCashAtBank)) . ')';
                else:
                    echo number_format(abs($totalCashAtBank));
                endif;
?></td>
        </tr>
        <tr>
            <td><a href="<?php echo site_url('salesReport'); ?>">Total Sales  | <?php echo date('F'); ?>(<?php echo date('d'); ?>)</a></td>
            <td align="right"><?php echo number_format($totalSalesAmount); ?></td>
        </tr>
        <tr>
            <td><a href="<?php echo site_url('stockReport'); ?>">Inventory</a></td>
            <td align="right"><?php echo number_format(abs($inventoryAmount)); ?></td>
        </tr>
    </tbody>
</table>