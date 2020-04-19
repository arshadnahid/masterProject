<?php
if (isset($_POST['start_date'])):
    $type_id = $this->input->post('type_id');
    $balance_type = $this->input->post('balance_type');
    $sub_cus_id = $this->input->post('sub_cus_id');

    $from_date = $this->input->post('start_date');
    $to_date = $this->input->post('end_date');
endif;
?>
<style>
    table.issue_table{width: 100%;border-collapse: collapse;}
    .issue_table td {border: 1px solid #000;text-align: center;}
    .issue_table th {border: 1px solid #000;text-align: center;}
</style>

    <div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    nder Details Report </div>
                      </div>
                      <div class="portlet-body">
                      <div class="row">
                           <div class="col-md-12">
                              <form id="publicForm" action=""  method="post" class="form-horizontal">
                           
                                  
                                  <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="form-field-1">Type</label>
                                            <div class="col-md-8">
                                                <select  onchange="selectPayType(this.value)" class="form-control"  id="type_id" name="type_id"   data-placeholder="Select Type">

                                                            <option value="">-Select-</option>

                                                            <option <?php
                                                            if ($type_id == 2) {
                                                                echo "selected";
                                                            }
                                                            ?> value="2">Customer</option>
                                                            <option <?php
                                                            if ($type_id == 3) {
                                                                echo "selected";
                                                            }
                                                            ?>  value="3">Supplier </option>
                                                        </select>
                                            </div>
                                        </div>
                                    </div>
                                  
                                  
                                    <div class="col-md-4">
                                         <div class="form-group">
                                        <div id="searchValue"></div>
                                        <div id="oldValue">
                                           
                                                <label class="col-md-4 control-label" for="form-field-1"> <span style="color:red;">*</span>Balance Type </label>
                                                <div class="col-md-8">
                                                    <select class="form-control"  id="balance_type" name="balance_type"   data-placeholder="Select Type">

                                                            <option value="-1">-Select-</option>

                                                            <option <?php
                                                            if ($balance_type == 1) {
                                                                echo "selected";
                                                            }
                                                            ?> value="1">All</option>
                                                            <option <?php
                                                            if ($balance_type == 2) {
                                                                echo "selected";
                                                            }
                                                            ?>  value="2"> Available Balance </option>
                                                        </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label no-padding-right" for="sub_cus_id" id="sub_cus_label"> Customer Id </label>
                                            <div class="col-md-8">
                                                <select style=""   class="form-control chosen-select form-control"  id="sub_cus_id" name="sub_cus_id"   data-placeholder="Select Type">
                                                            <option value="">-Select-</option>

                                                        </select>
                                            </div>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="form-field-1">  From </label>
                                            <div class="col-md-8">
                                                <input type="text"class="date-picker form-control" id="start_date" name="start_date" value="<?php
                                                                if (!empty($from_date)) {
                                                                    echo $from_date;
                                                                } else {
                                                                    echo date('d-m-Y');
                                                                }
                                                                ?>" data-date-format='dd-mm-yyyy' placeholder=" dd-mm-yyyy" style="width:100%"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label no-padding-right" for="form-field-1">&nbsp;&nbsp;To</label>
                                            <div class="col-md-8">
                                                <input type="text" class="date-picker form-control" id="end_date" name="end_date" value="<?php
                                                            if (!empty($to_date)):
                                                                echo $to_date;
                                                            else:
                                                                echo date('d-m-Y');
                                                            endif;
                                                            ?>" data-date-format='dd-mm-yyyy' placeholder="End Date: dd-mm-yyyy" style="width:100%"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                         <div class="form-group">
                                         <label class="col-md-4 control-label no-padding-right" for="form-field-1"></label>
                                          <div class="col-md-8">
                                            <button type="button" onclick="checkValidation()" class="btn btn-success btn-sm">
                                                <span class="fa fa-search"></span> Search
                                            </button>
                                   
                                       
                                            <button type="button" class="btn btn-info btn-sm"  onclick="window.print();" style="cursor:pointer;">

                                                <i class="fa fa-print"></i> Print
                                            </button>
                                        </div>
                                        </div>
                                        </div>
                            
                        </form>
                                 
               
                </div>
            </div><!-- /.col -->
            <?php
            if (isset($_POST['start_date'])):
                $type_id = $this->input->post('type_id');
                $searchId = $this->input->post('searchId');
                $productId = $this->input->post('productId');
                $from_date = date('Y-m-d', strtotime($this->input->post('start_date')));
                $to_date = date('Y-m-d', strtotime($this->input->post('end_date')));
                $total_pvsdebit = '';
                $total_pvscredit = '';
                $total_debit = '';
                $total_credit = '';
                $total_balance = '';
                ?>
                <div class="row">
                    <div class="col-xs-12">
                        
                        <table class="table table-responsive">
                            <tr>
                                <td style="text-align:center;">
                                    <h3><?php echo $companyInfo->companyName; ?>.</h3>
                                    <span><?php echo $companyInfo->address; ?></span><br>
                                    <strong>Phone : </strong><?php echo $companyInfo->phone; ?><br>
                                    <strong>Email : </strong><?php echo $companyInfo->email; ?><br>
                                    <strong>Website : </strong><?php echo $companyInfo->website; ?><br>
                                    <strong><?php echo $pageTitle; ?></strong>
                                    <strong></strong><?php echo $type_id == '2'?'Customer Product Balance Summary':'Supplier Product Balance Summary'?><br>
                                    <strong>Date : </strong><?php echo $from_date.' To ' .$to_date?><br>
                                     
                                </td>
                            </tr>
                        </table>
                        <table class="table table-bordered" id="ALLPRODUCT">

                            <thead>
                                <tr>

                                    <td align="center"><strong><?php echo $type_id == '2'?'Customer Name':'Supplier Name'?></strong></td>
                                    <td align="center"><strong>Product</strong></td>

                                    <td align="center"><strong>Balance ( Pcs )</strong></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($dataresult as $key => $eachResult):

                                    $balence = 0;
                                    $StockIn = $eachResult->StockIn != '' ? $eachResult->StockIn : 0;
                                    $StockOut = $eachResult->StockOut != '' ? $eachResult->StockOut : 0;
                                    $balence = $StockIn - $StockOut;
                                    if ($balence > 0 && $balance_type == '2') {
                                        ?>
                                        <tr>

                                            <td class="align-middle" style="text-align: center; vertical-align: middle;"><?php echo $eachResult->subCusName; ?></td>
                                            <td style="text-align: center"> <?php echo $eachResult->productName . ' [ ' . $eachResult->brandName . ' ] '; ?> </td>

                                            <td style="text-align: right;padding-right: 10px !important;"><?php echo ($balence); ?></td>
                                        </tr>
                                    <?php } else if($balance_type==1) {
                                        ?>
                                        <tr>

                                            <td class="align-middle" style="text-align: center; vertical-align: middle;"><?php echo $eachResult->subCusName; ?></td>
                                            <td style="text-align: center"> <?php echo $eachResult->productName . ' [ ' . $eachResult->brandName . ' ] '; ?> </td>

                                            <td style="text-align: right;padding-right: 10px !important;"><?php echo ($balence); ?></td>
                                        </tr>
                                        <?php
                                    }
                                endforeach;
                                ?>

                            </tbody>
<!--                            <tfoot>
                                <tr>
                                    <td colspan="1" align="right"><strong>Total</strong></td>

                                    <td align="right"><?php //echo $tstockOut      ?></td>
                                    <td align="right"><?php //echo ($topening + $tstockIn) - $tstockOut      ?></td>
                                </tr>
                            </tfoot>-->





                        </table>
                    </div>
                </div>
            <?php endif; ?>

        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>
</div>
<script>
    function checkValidation() {
        var type_id = $("#type_id").val();
        var sub_cus_id = $("#sub_cus_id").val();
        if (type_id == '') {
            swal("Select Customer or Supplier Type!", "Validation Error!", "error");
        }
//        else if (sub_cus_id == '' || typeof sub_cus_id == 'undefined') {
//            swal("Select Customer or Supplier ID!", "Validation Error!", "error");
//        } 
        else {
            $("#publicForm").submit();
        }
    }
</script>
<script>
<?php if (!empty($type_id)):
    ?>

    <?php if ($type_id != '-1' && $balance_type != '-1'): ?>

            selectPayType();


            var type_id = '<?php echo $this->input->post('type_id') ?>';
            var balance_type = '<?php echo $this->input->post('balance_type') ?>';
            var sub_cus_id = '<?php echo $this->input->post('sub_cus_id') ?>';
            var start_date = '<?php echo $this->input->post('start_date') ?>';
            var end_date = '<?php echo $this->input->post('end_date') ?>';
            $('#type_id').val(type_id);
            $('#balance_type').val(balance_type);
            $('#sub_cus_id').val(sub_cus_id);
            $('#start_date').val(start_date);
            $('#end_date').val(end_date);

    <?php else: ?>
            $("#searchValue").hide(1000);
            $("#oldValue").show(1000);

    <?php
    endif;
endif;
?>
    function selectPayType() {

        var url = '<?php echo site_url("InventoryController/supplierCusstomerLoad") ?>';


        var type_id = $('#type_id').val();
        var balance_type = $('#balance_type').val();
        var sub_cus_id = $('#sub_cus_id').val();
        if (type_id == 2) {
            $('#sub_cus_label').html('Customer Id')
        } else {
            $('#sub_cus_label').html('Supplier Id')
        }



        $.ajax({
            type: 'POST',
            url: url,
            data: {'type_id': type_id, 'balance_type': balance_type, 'sub_cus_id': sub_cus_id},
            beforeSend: function () {
                // $('#sub_cus_id').val('');
                // $('select').val(2);
                //$('select').trigger("chosen:updated");
            },
            success: function (data)
            {
                $('#sub_cus_id').chosen();

                $('#sub_cus_id option').remove();

                $('#sub_cus_id').append($(data));

                $("#sub_cus_id").trigger("chosen:updated");

            }
        });





    }


    MergeCommonRowsForSearchResult($('#ALLPRODUCT'), 1, 0);



    function MergeCommonRowsForSearchResult(table, startCol, HowManyCol) {


        var firstColumnBrakes = [];
        // iterate through the columns instead of passing each column as function parameter:
        for (var i = startCol; i <= (startCol + HowManyCol); i++) {
            var previous = null, cellToExtend = null, rowspan = 1;
            table.find("td:nth-child(" + i + ")").each(function (index, e) {
                var jthis = $(this), content = jthis.text();

                // check if current row "break" exist in the array. If not, then extend rowspan:
                if (previous == content && content !== "" && $.inArray(index, firstColumnBrakes) === -1) {
                    console.log(content);
                    // hide the row instead of remove(), so the DOM index won't "move" inside loop.
                    jthis.addClass('hidden');
                    cellToExtend.attr("rowspan", (rowspan = rowspan + 1));
                    //sum

                    

                     
                        


                } else {
                    // store row breaks only for the first column:
                    if (i === 1)
                        firstColumnBrakes.push(index);
                    rowspan = 1;
                    previous = content;
                    cellToExtend = jthis;
                }

            });
            //sumColValue(table,startCol);
        }

        // now remove hidden td's (or leave them hidden if you wish):
        $('td.hidden').remove();
    }
    
    function sumColValue(table,startCol){
      
        
    }
</script>
<script>
            
            $(document).ready(function () {


            $('.date-picker').datepicker({
                autoclose: true,
                todayHighlight: true
            })

        });
         </script>