
<?php
$headlogin = $this->session->userdata('headOffice');
?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                      <?php echo get_phrase('Customer Opening List')?> </div>
                      </div>
                      <div class="portlet-body">
            <div class="row">
                
                   <table id="example" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th> <?php echo get_phrase('Si')?></th>
                                <th> <?php echo get_phrase('Customer Name')?></th>
                                <th> <?php echo get_phrase('Receivable')?></th>
                                <th> <?php echo get_phrase('Action')?></th>
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
                                            <a class="btn btn-icon-only red" href="javascript:void(0)" onclick="deleteOpening('<?php echo $value->ledger_id; ?>')">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <?php
                                        else:
                                            //head office login
                                            if (!empty($headlogin)):
                                                ?>

                                               <!-- <a class="btn btn-icon-only red" href="javascript:void(0)" onclick="deleteOpening('<?php /*echo $value->ledger_id; */?>')">
                                                    <i class="fa fa-trash"></i>
                                                </a>-->
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
                        <td colspan="2" align="right"><strong><?php echo get_phrase('Total Receivable')?></strong></td>
                        <td align="right"><?php echo number_format($totalReceive); ?></td>
                        <td></td>
                        </tfoot>
                    </table>
                
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>
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
