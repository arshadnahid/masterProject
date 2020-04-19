<?php
if (isset($_POST['start_date'])):

    $from_date = $this->input->post('start_date');
    $to_date = $this->input->post('end_date');
    $distributor = $this->input->post('distributor');
endif;
?>

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state  noPrint" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Sales</a>
                </li>
                <li class="active">Sales Report</li>
            </ul>
            <span style="padding-top: 5px!important;">
                <a style="border-radius:100px 0 100px 0;" href="<?php echo site_url('DistributorDashboard'); ?>" class="btn btn-danger pull-right">
                    <i class="ace-icon fa fa-times "></i>
                    Cancel
                </a>
            </span>
        </div>
        <br>
        <div class="page-content">
            <div class="row  noPrint">
                <div class="col-md-12">
                    <form id="publicForm" action=""  method="post" class="form-horizontal">
                        <div class="col-sm-10 col-sm-offset-1">
                            <div class="table-header">
                                Sales Report
                            </div><br>
                            <div style="background-color: grey!important;">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> Distributor</label>
                                        <div class="col-sm-8">
                                            <select name="distributor" class="chosen-select form-control" id="form-field-select-3" data-placeholder="Search by payee">
                                                <?php foreach ($allDistributor as $key => $eachDis): ?>
                                                    <option <?php
                                                if ($distributor == $eachDis->dist_id) {
                                                    echo "selected";
                                                }
                                                    ?> value="<?php echo $eachDis->dist_id; ?>"><?php echo $eachDis->companyName; //. ' [ ' . $eachDis->dist_name . ' ] ';       ?></option>
                                                    <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> From Date</label>
                                        <div class="col-sm-8">
                                            <input type="text"class="date-picker" id="start_date" name="start_date" value="<?php
                                                    if (!empty($from_date)) {
                                                        echo $from_date;
                                                    } else {


                                                        echo date('Y-m-d');
                                                    }
                                                    ?>" data-date-format='yyyy-mm-dd' placeholder="Start Date: yyyy-mm-dd"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> To Date</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="date-picker" id="end_date" name="end_date" value="<?php
                                                   if (!empty($to_date)):
                                                       echo $to_date;
                                                   else:
                                                       echo date('Y-m-d');
                                                   endif;
                                                    ?>" data-date-format='yyyy-mm-dd' placeholder="End Date: yyyy-mm-dd"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">

                                        <div class="col-sm-6">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                                                Search
                                            </button>
                                        </div>
                                        <div class="col-sm-6">
                                            <button type="button" class="btn btn-info btn-sm"  onclick="window.print();" style="cursor:pointer;">
                                                <i class="ace-icon fa fa-print  align-top bigger-125 icon-on-right"></i>
                                                Print
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div><!-- /.col -->
            <?php
            if (isset($_POST['start_date'])):
                //  dumpVar($_POST);
                $from_date = $this->input->post('start_date');
                $to_date = $this->input->post('end_date');

                $dist_id = $distributor;
                $total_pvsdebit = '';
                $total_pvscredit = '';

                $total_debit = '';
                $total_credit = '';
                $total_balance = '';
                ?>
                <div class="row">
                    <div class="col-xs-10 col-xs-offset-1">
                        <div class="table-header">
                            Sales Report Period from <?php echo $from_date; ?> To <?php echo $to_date; ?>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <td align="center"><strong>Date</strong></td>
                                    <td align="center"><strong>Voucher No.</strong></td>
                                    <td align="center"><strong>Payment Type</strong></td>
                                    <td align="center"><strong>Customer</strong></td>
                                    <td align="center"><strong>Memo</strong></td>
                                    <td align="center"><strong>Amount</strong></td>
                                </tr>
                            </thead>
                            <tbody>                             

                                <?php
                                $this->db->where('dist_id', $dist_id);
                                $this->db->where('form_id', 5);
                                $this->db->where('date >=', $from_date);
                                $this->db->where('date <=', $to_date);
                                $query = $this->db->get('generals')->result_array();
                                //dumpVar($data);
                                $total_debit = 0;
                                foreach ($query as $row):
                                    ?>                                
                                    <tr>
                                        <td><?php echo date('M d, Y', strtotime($row['date'])); ?></td> 
                                        <td><a title="view invoice" href="<?php echo site_url('disSalesReportView/' . $row['generals_id']); ?>"><?php echo $row['voucher_no']; ?></a></td> 
                                        <td><?php
                            if ($row['payType'] == 1) {
                                echo "Cash";
                            } elseif ($row['payType'] == 2) {
                                echo "Credit";
                            } else {
                                echo "Bank";
                            }
                                    ?></td> 
                                        <td>
                                            <?php
                                            $customerInfo = $this->Common_model->tableRow('customer', 'customer_id', $row['customer_id']);
                                            echo $customerInfo->customerID . '[ ' . $customerInfo->customerName . ']';
                                            ?>
                                        </td>
                                        <td><?php echo $row['memo']; ?></td>                                
                                        <td align="right"><?php
                                    echo number_format((float) abs($row['debit']), 2, '.', ',');
                                    $total_debit += $row['debit'];
                                            ?></td>                                    

                                    </tr>
                                <?php endforeach; ?>
                                <!-- /Search Balance -->                            
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" align="right"><strong>Total Sales Amount</strong></td>                             
                                    <td align="right"><strong><?php echo number_format((float) abs($total_debit), 2, '.', ','); ?>&nbsp;</strong></td>
                                </tr>
                            </tfoot>                            
                        </table> 
                    </div>
                </div>
            <?php endif; ?>
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>
<script src="<?php echo base_url(); ?>assets/js/bootstrap-colorpicker.min.js"></script>
