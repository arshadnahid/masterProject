<?php
if (isset($_POST['start_date'])):
    $referenceId = $this->input->post('referenceId');
    $from_date = $this->input->post('start_date');
    $to_date = $this->input->post('end_date');
endif;
?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                   <?php echo get_phrase(' Reference Sales Report')?> </div>

            </div>

            <div class="portlet-body">

                <form id="publicForm" action=""  method="post" class="form-horizontal">
                    <div class="col-sm-12">

                        <div style="background-color: grey!important;">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"><?php echo get_phrase('Reference')?></label>
                                    <div class="col-sm-9">
                                        <select  id="referenceId" name="referenceId"  class="chosen-select form-control" id="form-field-select-3" data-placeholder="Search by Customer ID or Name">
                                            <option <?php
                                            if ($referenceId == 'all') {
                                                echo "selected";
                                            }
                                            ?> value="all">All</option>
                                                <?php foreach ($referenceList as $key => $eachReference): ?>
                                                <option <?php
                                                if (!empty($referenceId) && $referenceId == $eachReference->reference_id) {
                                                    echo "selected";
                                                }
                                                ?> value="<?php echo $eachReference->reference_id; ?>"><?php echo $eachReference->refCode . ' [ ' . $eachReference->referenceName . ' ] '; ?></option>
                                                <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> <?php echo get_phrase('From')?> <?php echo get_phrase('Date')?></label>
                                    <div class="col-sm-8">
                                        <input type="text"class="date-picker form-control" id="start_date" name="start_date" value="<?php
                                        if (!empty($from_date)) {
                                            echo $from_date;
                                        } else {
                                            echo date('d-m-Y');
                                        }
                                        ?>" data-date-format='dd-mm-yyyy' placeholder="Start Date: dd-mm-yyyy"/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> <?php echo get_phrase('To')?> <?php echo get_phrase('Date')?></label>
                                    <div class="col-sm-8">
                                        <input type="text" class="date-picker form-control" id="end_date" name="end_date" value="<?php
                                        if (!empty($to_date)):

                                            echo $to_date;

                                        else:

                                            echo date('d-m-Y');

                                        endif;
                                        ?>" data-date-format='dd-mm-yyyy' placeholder="End Date: dd-mm-yyyy"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <div class="col-sm-6">
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                                            <?php echo get_phrase('Search')?>
                                        </button>
                                    </div>
                                    <div class="col-sm-6">
                                        <button type="button" class="btn btn-info btn-sm"  onclick="window.print();" style="cursor:pointer;">
                                            <i class="ace-icon fa fa-print  align-top bigger-125 icon-on-right"></i>
                                            <?php echo get_phrase('Print')?>
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="clearfix"></div>

                </form>



                <!-- /.col -->

                <?php
                if (isset($_POST['start_date'])):

                    $referenceId = $this->input->post('referenceId');

                    if ($referenceId != 'all'):
                        ?>
                        <div class="row">
                            <div class="col-xs-12">

                                <table class="table table-responsive">
                                    <tr>
                                        <td style="text-align:center;">
                                            <h3><?php echo $companyInfo->companyName; ?></h3>
                                            <p><?php echo $companyInfo->dist_address; ?></p>
                                            <strong><?php echo get_phrase('Phone')?> : </strong><?php echo $companyInfo->dist_phone; ?><br>
                                            <strong><?php echo get_phrase('Email')?> : </strong><?php echo $companyInfo->dist_email; ?><br>
                                            <strong><?php echo get_phrase('Website')?> : </strong><?php echo $companyInfo->dis_website; ?><br>
                                            <strong><?php echo get_phrase($pageTitle); ?></strong>
                                            <strong><?php echo get_phrase('Reference Sales Report')?> </strong> <?php echo get_phrase('From')?> <?php echo $from_date; ?> <?php echo get_phrase('To')?> <?php echo $to_date; ?>
                                        </td>
                                    </tr>
                                </table>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <td align="center"><strong><?php echo get_phrase('Date')?>Date</strong></td>
                                            <td align="center"><strong><?php echo get_phrase('Voucher No')?>.</strong></td>
                                            <td align="center"><strong><?php echo get_phrase('Payment Type')?></strong></td>
                                            <td align="center"><strong><?php echo get_phrase('Memo')?></strong></td>
                                            <td align="center"><strong><?php echo get_phrase('Amount')?></strong></td>
                                        </tr>
                                    </thead>
                                    <tbody>                             
                                        <?php
                                        $totalAmount = 0;
                                        $totalOpening = 0;
                                        foreach ($refOpList as $key => $row):

                                            if ($key == 0):
                                                ?>                       
                                                <tr>
                                                    <td align="right" colspan="4"><strong>Opening</strong></td>
                                                    <td align="right"><?php
                                                        echo $row->totalOpening;
                                                        $totalOpening+=$row->totalOpening;
                                                        ?></td>
                                                </tr>
                                                <?php
                                            endif;
                                            ?>
                                            <tr>
                                                <td><?php echo date('M d, Y', strtotime($row->date)); ?></td> 
                                                <td><?php echo $row->voucher_no; ?></td> 
                                                <td><?php
                                                    if ($row->payType == 1) {
                                                        echo "Cash";
                                                    } elseif ($row->payType == 2) {
                                                        echo "Credit";
                                                    } else if ($row->payTpe == 3) {
                                                        echo "Bank";
                                                    } else {
                                                        echo "Cash";
                                                    }
                                                    ?></td> 
                                                <td><?php echo $row->memo; ?></td>                                
                                                <td align="right"><?php
                                                    echo number_format((float) abs($row->individualAmount), 2, '.', ',');
                                                    $total_debit += $row->individualAmount;
                                                    ?></td> 
                                            </tr>
                                        <?php endforeach; ?>
                                        <!-- /Search Balance -->                            
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4" align="right"><strong>Total Sales Amount</strong></td>    
                                            <td align="right"><strong><?php echo number_format((float) abs($total_debit + $totalOpening), 2, '.', ','); ?>&nbsp;</strong></td>
                                        </tr>
                                    </tfoot>                            
                                </table> 
                            </div>
                        </div>
                    <?php else:
                        ?>

                        <div class="row">

                            <div class="col-xs-12">

                              
                                <!--                            <div class="noPrint">
                                
                                                                <a style="border-radius:100px 0 100px 0;" href="<?php echo site_url('SalesController/customerSalesReport_export_excel/') ?>" class="btn btn-success pull-right">
                                                                    <i class="ace-icon fa fa-download"></i>
                                                                    Excel 
                                                                </a></div>-->
                                <table class="table table-responsive">
                                    <tr>
                                        <td style="text-align:center;">
                                            <h3><?php echo $companyInfo->companyName; ?>.</h3>
                                            <p><?php echo $companyInfo->dist_address; ?>
                                            </p>
                                            <strong><?php echo get_phrase('Phone')?> : </strong><?php echo $companyInfo->dist_phone; ?><br>
                                            <strong><?php echo get_phrase('Email')?> : </strong><?php echo $companyInfo->dist_email; ?><br>
                                            <strong><?php echo get_phrase('Website')?> : </strong><?php echo $companyInfo->dis_website; ?><br>

                                            <strong><?php echo get_phrase($pageTitle)?></strong> <?php echo get_phrase('From')?> <?php echo $from_date; ?><?php echo get_phrase('To')?>  <?php echo $to_date; ?>
                                        </td>
                                    </tr>
                                </table>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <td align="center"><strong><?php echo get_phrase('Sl')?></strong></td>
                                            <td align="center"><strong><?php echo get_phrase('Reference')?></strong></td>
                                            <td align="center"><strong><?php echo get_phrase('Amount')?></strong></td>
                                        </tr>
                                    </thead>
                                    <tbody>                             
                                        <?php
                                        $total_debit = 0;
                                        $totalOpenig = 0;
                                        $sl = 1;

                                        foreach ($refOpList as $key => $value):

                                            if ($key == 0):
                                                $totalOpenig+=$value->totalOpening;
                                                ?>
                                                <tr>
                                                    <td colspan="2" align="right"><strong>Opening Balance</strong></td>
                                                    <td align="right" ><?php echo $value->totalOpening; ?></td>
                                                </tr>
                                                <?php
                                            endif;
                                            if (!empty($value->individualAmount)):
                                                ?>                                
                                                <tr>
                                                    <td><?php echo $sl++; ?></td> 
                                                    <td><?php echo $value->refCode . ' [ ' . $value->referenceName . ' ] '; ?></td> 
                                                    <td align="right"><?php
                                                        echo number_format((float) abs($value->individualAmount), 2, '.', ',');
                                                        $total_debit += $value->individualAmount;
                                                        ?>
                                                    </td>                                    
                                                </tr>
                                                <?php
                                            endif;
                                        endforeach;
                                        ?>
                                        <!-- /Search Balance -->                            
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2" align="right"><strong>Total Sales Amount</strong></td>                             
                                            <td align="right"><strong><?php echo number_format((float) abs($total_debit + $totalOpenig), 2, '.', ','); ?>&nbsp;</strong></td>
                                        </tr>
                                    </tfoot>                            
                                </table> 
                            </div>
                        </div>
                    <?php endif; ?>            
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {


        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        })

    });
</script>

