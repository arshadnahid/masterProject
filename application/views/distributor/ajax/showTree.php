<div class="table-header">
    Chart of account tree
</div>

<?php if (!empty($rootId) && empty($parentId) ):
    ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>SL</th>
                <th>Root Account</th>
                <th>Child Account</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rootList as $key => $value): ?>
                <tr>
                    <td><?php echo $key + 1; ?></td>
                    <td><?php echo $this->Common_model->tableRow('ac_account_ledger_coa', 'id', $value->parent_id)->parent_name; ?></td>
                    <td><?php echo $value->parrent_name; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
elseif ( !empty($parentId) ):
    ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>SL</th>
                <th>Root Account</th>

                <th>Child Account</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($parentList as $key => $value): ?>
                <tr>
                    <td><?php echo $key + 1; ?></td>
                    <td><?php echo $this->Common_model->tableRow('ac_account_ledger_coa', 'id', $value->parent_id)->parent_name; ?></td>
                    <td><?php echo $this->Common_model->tableRow('ac_account_ledger_coa', 'id', $value->id)->parent_name; ?></td>

                   <!-- <td><?php /*echo $value->title; */?></td>-->
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php //else: ?>
    <!--<table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Root Account</th>
                <th>Parent Account</th>
                <th>Child Account</th>
                <th>Account Head</th>
            </tr>
        </thead>
        <tbody>
            <?php
/*            foreach ($chartList as $key => $value):
                $parentId = $this->Common_model->tableRow('ac_account_ledger_coa', 'id', $value->id)->id;
                */?>
                <tr>
                    <td><?php /*echo $key + 1; */?></td>
                    <td><?php /*echo $this->Common_model->tableRow('ac_account_ledger_coa', 'id', $value->rootId)->parrent_name; */?></td>
                    <td><?php /*echo $this->Common_model->tableRow('ac_account_ledger_coa', 'id', $parentId)->parrent_name; */?></td>
                    <td><?php /*echo $this->Common_model->tableRow('ac_account_ledger_coa', 'id', $value->parrent_id)->parrent_name; */?></td>
                    <td><?php /*echo $value->parrent_name; */?></td>
                </tr>
            <?php /*endforeach; */?>
        </tbody>
    </table>-->
<?php endif; ?>