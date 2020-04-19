
<?php
$headlogin = $this->session->userdata('headOffice');
?>
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Customer Opening</a>
                </li>

                <li class="active">Customer Opening List</li>
            </ul>

            <?php if (!empty($headlogin)): ?>
                <ul class="breadcrumb pull-right">
                    <li>
                        <a class="green" href="<?php echo site_url('customerOpneingAdd'); ?>">
                            <i class="ace-icon fa fa-plus"></i>
                            Add
                        </a>
                    </li>
                    <li>
                        <a class="red" href="javascript:void(0)" onclick="deleteAllData()">
                            <i class="ace-icon fa fa-trash-o"></i>&nbsp; All Delete
                        </a>
                    </li>
                    <li>
                        <a class="blue" href="<?php echo site_url('customerOpeningImport'); ?>">
                            <i class="ace-icon fa fa-upload"></i>
                            Import
                        </a>
                    </li>
                </ul>
            <?php else: ?>

                <?php if (empty($openingShowHide) || $openingShowHide < 1): ?>
                    <ul class="breadcrumb pull-right">
                        <li>
                            <a class="addPermission" href="<?php echo site_url('customerOpneingAdd'); ?>">
                                <i class="ace-icon fa fa-plus"></i>
                                Add
                            </a>
                        </li>
                        <li>
                            <a class="red deleteOpening" href="javascript:void(0)" onclick="deleteAllData()">
                                <i class="ace-icon fa fa-trash-o"></i>&nbsp; All Delete
                            </a>
                        </li>
                        <li>
                            <a class="inventoryAddPermission green" href="<?php echo site_url('customerOpeningImport'); ?>">
                                <i class="ace-icon fa fa-upload"></i>
                                Import
                            </a>
                        </li>
                    </ul>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <div class="page-content">
            <div class="row">
                <div class="table-header">
                    Customer Opening List
                </div>
                <div>
                    <table id="example" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Customer Name</th>
                                <th>Receivable</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $totalReceive = 0;
                            foreach ($allBalance as $key => $value):
                                $totalReceive+=$value->dr;
                                $CustomerName = $this->Common_model->get_single_data_by_single_column('customer', 'customer_id', $value->client_vendor_id);
                                ?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td><?php echo $CustomerName->customerName . '[' . $CustomerName->customerID . ']'; ?></td>
                                    <td align="right"><?php echo $value->dr; ?></td>
                                    <!--<td><?php echo $value->cr; ?></td>-->
                                    <td>
                                        <?php if (empty($openingShowHide) || $openingShowHide < 1):
                                            //distributor login
                                            ?>
                                            <a class="red deleteOpening" href="javascript:void(0)" onclick="deleteOpening('<?php echo $value->ledger_id; ?>')">
                                                <i class="ace-icon fa fa-trash-o bigger-130"></i>
                                            </a>
                                            <?php
                                        else:
                                            //head office login
                                            if (!empty($headlogin)):
                                                ?>

                                                <a class="red deleteOpening" href="javascript:void(0)" onclick="deleteOpening('<?php echo $value->ledger_id; ?>')">
                                                    <i class="ace-icon fa fa-trash-o bigger-130"></i>
                                                </a>
<!--                                        <a class="green deleteOpening" href="<?php //echo site_url('');?>">
                                                    <i class="ace-icon fa fa-pencil bigger-130"></i>
                                                </a>-->
                                                <?php
                                            endif;
                                        endif;
                                        ?>

                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                        <td colspan="2" align="right"><strong>Total Receivable</strong></td>
                        <td align="right"><?php echo number_format($totalReceive); ?></td>
                        <td></td>
                        </tfoot>
                    </table>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>
<script>
    function deleteOpening(deleteId){
        swal({
            title: "Are you sure ?",
            text: "You won't be able to revert this!",
            showCancelButton: true,
            confirmButtonColor: '#73AE28',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: "No",
            closeOnConfirm: true,
            closeOnCancel: true,
            type: 'success'
        },
        function (isConfirm) {
            if (isConfirm) {
                window.location.href = "FinaneController/deleteCustomerOpening/" + deleteId;
            }else{
                return false;
            }
        });
    }

    function deleteAllData(){
        swal({
            title: "Are you sure ?",
            text: "You won't be able to revert this!",
            showCancelButton: true,
            confirmButtonColor: '#73AE28',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: "No",
            closeOnConfirm: true,
            closeOnCancel: true,
            type: 'success'
        },
        function (isConfirm) {
            if (isConfirm) {
                window.location.href = "FinaneController/allSupCusOpeDelete/1";
            }else{
                return false;
            }
        });
    }

</script>
