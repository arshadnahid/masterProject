


<table class="table table-bordered">
    <?php if (!empty($IncentiveList)): ?>
        <thead>
            <tr>
                <td>Month</td>
                <td>Company</td>
                <td nowrap>Target Qty</td>
                <td nowrap>Purchases Qty</td>

                <td>Remaining  </td>
            </tr>
        </thead>

        <tbody>
            <?php
            foreach ($IncentiveList as $eachInfo):
                $new_width = $eachInfo->purchase_qty * 100 / $eachInfo->targetQty;
                ?>
                <tr>
                    <td nowrap><?php echo date('F'); ?></td>
                    <td nowrap><?php echo $eachInfo->brandName; ?></td>
                    <td nowrap><?php echo $eachInfo->targetQty; ?></td>
                    <td nowrap><?php echo $eachInfo->purchase_qty; ?></td>

                    <td width="30%">
                        <div class="progress pos-rel  progress-small progress-striped <?php
        if ($eachInfo->targetQty > $eachInfo->purchase_qty) {
            echo "active";
        }
                ?>" style="color:red!important;" data-percent="<?php echo $new_width; ?>%">
                            <div class="progress-bar progress-bar-<?php
                     if ($new_width == 100 || $new_width >= 100) {
                         echo "success";
                     } else {
                         echo "danger";
                     }
                ?>" style="width:<?php echo $new_width; ?>%;color:red!important;"></div>
                        </div>
                    </td>
                </tr>

            <?php endforeach; ?>
        </tbody>
    <?php else: ?>
        <tbody>
            <tr>
                <td colspan="6">
                    <div class="alert alert-danger">

                        You did't setup any incentive.Please setup now

                        <i class="ace-icon fa fa-arrow-right icon-on-right"></i>
                        <a href="<?php echo site_url('incentiveList'); ?>">
                            Go...
                        </a>


                    </div>
                </td>
            </tr>
        </tbody>

    <?php endif; ?>
</table>