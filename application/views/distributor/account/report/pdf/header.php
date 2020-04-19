<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 3/12/2020
 * Time: 10:08 AM
 */
?>
<table class="table table-responsive">
    <tr>
        <td style="text-align:center;">
            <h3>
                <?php echo $companyInfo->companyName ?>
            </h3>
            <span>
                <?php echo $companyInfo->address ?>
            </span>
            <br>
            <strong>
                <?php echo get_phrase('Phone') ?>:
            </strong>
            <?php echo $companyInfo->phone ?>
            <br><strong>
                <?php echo get_phrase('Email') ?> : </strong><?php echo $companyInfo->email ?>
            <br><strong><?php echo get_phrase('Website') ?>: </strong><?php echo  $companyInfo->website ?>
            <br><strong><?php echo 'General Ledger Report' ?></strong>
            <br><strong> <?php echo get_phrase('') ?> </strong>From <?php echo $start_date . ' To ' . $end_date ?>
        </td>
    </tr>
</table>
