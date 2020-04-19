<?php


    $branch_id = isset($_GET['branch_id']) ? $_GET['branch_id'] : 'all';
$to_date = isset($_GET['end_date']) ? $this->input->get('end_date') : '';
$from_date = isset($_GET['branch_id']) ? $this->input->get('start_date') : '';

?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    Daily Sales Statement
                </div>
            </div>

            <div class="portlet-body">
                <form id="publicForm" action="" method="get" class="form-horizontal noPrint">
                    <div class="col-md-12">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="col-md-4 control-label no-padding-right"
                                       for="form-field-1"> <?php echo get_phrase('Branch') ?> </label>
                                <div class="col-md-8">
                                    <select name="branch_id" class="chosen-select form-control"
                                            id="BranchAutoId" data-placeholder="Select Branch">
                                        <option value=""></option>
                                        <?php
                                        if (!empty($branch_id)) {
                                            $selected = $branch_id;
                                        } else {
                                            $selected = 'all';
                                        }
                                        // come from branch_dropdown_helper
                                        echo branch_dropdown('all', $selected);
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="col-md-4 control-label no-padding-right" for="form-field-1"> From </label>
                                <div class="col-md-8">
                                    <input type="text" class="date-picker form-control" id="start_date"
                                           name="start_date" value="<?php
                                    if (!empty($from_date)) {
                                        echo $from_date;
                                    } else {
                                        echo date('d-m-Y');
                                    }
                                    ?>" data-date-format='dd-mm-yyyy' placeholder=" dd-mm-yyyy" style="width:100%"/>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="col-md-4 control-label no-padding-right"
                                       for="form-field-1">&nbsp;&nbsp;To</label>
                                <div class="col-md-8">
                                    <input type="text" class="date-picker form-control" id="end_date" name="end_date"
                                           value="<?php
                                           if (!empty($to_date)):
                                               echo $to_date;
                                           else:
                                               echo date('d-m-Y');
                                           endif;
                                           ?>" data-date-format='dd-mm-yyyy' placeholder="End Date: dd-mm-yyyy"
                                           style="width:100%"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-success btn-sm">
                                    <span class="ace-icon fa fa-search icon-on-right bigger-110"></span> Search
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-info btn-sm" onclick="window.print();"
                                        style="cursor:pointer;">
                                    <i class="ace-icon fa fa-print  align-top bigger-125 icon-on-right"></i>
                                    Print
                                </button>
                            </div>
                        </div>

                    </div>
                    <div class="clearfix"></div>

                </form>


                <!-- /.col -->

                <?php
                if (isset($daily_sales_statement)) {
                    ?>
                    <div class="row">
                        <div class="col-md-12">

                            <table class="table">
                                <thead>
                                <tr>
                                    <td style="text-align:center;">

                                        <h3><?php echo $companyInfo->companyName; ?>.</h3>

                                        <span><?php echo $companyInfo->address; ?></span><br>

                                        <strong>Phone : </strong><?php echo $companyInfo->phone; ?><br>

                                        <strong>Email : </strong><?php echo $companyInfo->email; ?><br>

                                        <strong>Website : </strong><?php echo $companyInfo->website; ?><br>

                                        <strong><?php echo $pageTitle; ?></strong>

                                    </td>
                                </tr>
                                </thead>
                            </table>

                            <table class="table table-bordered" id="datatable">
                                <thead>


                                <tr>

                                    <th class="text-center">Sales Date</th>
                                    <th class="text-center">Total Sales</th>
                                   <!-- <th class="text-center">Received Amount</th>

                                    <!-- <th class="text-center">Customer Due Rec.</th>-->

                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $ttDueAmout=0;
                                $ttSalesAmout=0;
                                $ttPaidAmout=0;
                                $brandNameArray=array();
                                foreach ($daily_sales_statement as $ind => $element2) {
                                    if (!in_array($element2->branch_name, $brandNameArray)) {
                                        array_push($brandNameArray, $element2->branch_name);?>
                                        <tr>
                                            <td colspan="4">
                                                <?php echo $element2->branch_name?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                                <?php echo $element2->invoice_date ?>
                                            </td>
                                            <td class="text-right">
                                                <?php echo numberFromatfloat($element2->sales_amount);$ttSalesAmout=$ttSalesAmout+$element2->sales_amount; ?>
                                            </td>


                                        </tr>

                                    <?php } else {
                                        ?>
                                        <tr>
                                            <td class="text-center">
                                                <?php echo $element2->invoice_date ?>
                                            </td>
                                            <td class="text-right">
                                                <?php echo numberFromatfloat($element2->sales_amount);
                                                $ttSalesAmout=$ttSalesAmout+$element2->sales_amount; ?>
                                            </td>


                                        </tr>
                                    <?php }
                                } ?>
                                </tbody>
                                <tf>
                                    <tr>
                                        <td colspan="1" align="right"> Total</td>
                                        <td  align="right"> <?php echo numberFromatfloat($ttSalesAmout)?></td>


                                    </tr>
                                </tf>

                            </table>

                        </div>
                    </div>
                <?php } ?>
                <!-- /.page-content -->
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">


    $("#datatablefff").DataTable({
        buttons: [
            'copy', 'excel', 'pdf'
        ],
        "columns": [
            {"title": "Sales Date"},
            {"title": "Total Sales"},
            {"title": "Discount"},
            {"title": "Customer Due"},
            {"title": "Customer Due Rec."},
            {"title": "Net Amount"},
        ],
        dom: "Bfrtip",
        "bProcessing": true,
        //"bServerSide" : true
    });
</script>
<script>
    $(document).ready(function () {


        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        })

    });</script>



