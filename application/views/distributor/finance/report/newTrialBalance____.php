<?php
if (isset($_POST['start_date'])):

    $account = $this->input->post('accountHead');

    $from_date = $this->input->post('start_date');

    $to_date = $this->input->post('end_date');
    //echo "<pre>";
    //print_r($assetList);

endif;
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    Trial Balance
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">

                    <div class="col-md-12">

                        <form id="publicForm" action="" method="post" class="form-horizontal">

                            <div class="col-sm-10 col-sm-offset-1">
                                <div style="background-color: grey!important;">


                                    <div class="col-md-4">

                                        <div class="form-group">

                                            <label class="col-sm-4 control-label no-padding-right" for="form-field-1">
                                                From Date</label>

                                            <div class="col-sm-8">

                                                <input type="text" class="date-picker form-control" id="start_date"
                                                       name="start_date" value="<?php
                                                if (!empty($from_date)) {

                                                    echo $from_date;
                                                } else {

                                                    echo date('d-m-Y');
                                                }
                                                ?>" data-date-format='dd-mm-yyyy' placeholder="Start Date: dd-mm-yyyy"/>

                                            </div>

                                        </div>


                                    </div>

                                    <div class="col-md-4">

                                        <div class="form-group">

                                            <label class="col-sm-4 control-label no-padding-right" for="form-field-1">
                                                To Date</label>

                                            <div class="col-sm-8">

                                                <input type="text" class="date-picker form-control" id="end_date"
                                                       name="end_date" value="<?php
                                                if (!empty($to_date)):

                                                    echo $to_date;

                                                else:

                                                    echo date('d-m-Y');

                                                endif;
                                                ?>" data-date-format='dd-mm-yyyy' placeholder="End Date: dd-mm-yyyy"/>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-md-4">

                                        <div class="form-group">

                                            <div class="col-sm-2"></div>

                                            <div class="col-sm-5">

                                                <button type="submit" class="btn btn-success btn-sm">

                                                    <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>

                                                    Search

                                                </button>

                                            </div>

                                            <div class="col-sm-5">

                                                <button type="button" class="btn btn-info btn-sm"
                                                        onclick="window.print();" style="cursor:pointer;">

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
                if (isset($_POST['start_date']) && isset($_POST['end_date'])):

                    $sub_total_pvsdebit = 0;

                    $sub_total_pvscredit = 0;

                    $sub_total_debit = 0;

                    $sub_total_credit = 0;

                    $sub_total_debit_balance = 0;

                    $sub_total_credit_balance = 0;


                    $opDr = 0;

                    $opCr = 0;

                    $pDr = 0;

                    $pCr = 0;

                    $cDr = 0;

                    $cCr = 0;


                    unset($_SESSION["start_date"]);

                    unset($_SESSION["end_date"]);


                    $_SESSION["start_date"] = $from_date;

                    $_SESSION["end_date"] = $to_date;

                    $dist_id = $this->dist_id;

                    $finalTrialBalancedr = '';

                    $finalTrialBalancecr = '';
                    ?>

                    <div class="row">
                        <div class="col-sm-10 col-sm-offset-1">
                            <table class="table table-responsive">
                                <tr>
                                    <td style="text-align:center;">
                                        <h3><?php echo $companyInfo->companyName; ?>.</h3>
                                        <span><?php echo $companyInfo->address; ?></span><br>
                                        <strong>Phone : </strong><?php echo $companyInfo->phone; ?><br>
                                        <strong>Email : </strong><?php echo $companyInfo->email; ?><br>
                                        <strong>Website : </strong><?php echo $companyInfo->website; ?><br>
                                        <strong><?php echo $pageTitle; ?></strong>: From <?php echo $from_date; ?>
                                        To <?php echo $to_date; ?>
                                    </td>
                                </tr>
                            </table>
                            <table class="table table-bordered">
                                <thead>
                                <!-- <tr>
                                     <td rowspan="2" align="center" style="padding-top:15px;"><strong>Account
                                             Name</strong></td>
                                     <td colspan="2" align="center"><strong>Code</strong></td>
                                     <td colspan="2" align="center"><strong>This Period</strong></td>
                                     <td colspan="2" align="center"><strong>Balance (In BDT.)</strong></td>
                                 </tr>-->
                                <tr>
                                    <td align="center"><strong>Account
                                            Name</strong></td>
                                    <td align="center"><strong>Code</strong></td>
                                    <td align="center"><strong>Previous Bal</strong></td>
                                    <td align="center"><strong>Debit Amount</strong></td>

                                    <td align="center"><strong>Credit Amount</strong></td>
                                </tr>
                                </thead>
                                <tbody>
                                <!-- Assets -->

                                <?php
                                $twoa = 1; $twoa = 1;
                                foreach ($assetList as $row_cta):


                                    $style = 0;
                                    $textColor = "";
                                    $textSize = "";
                                    $fontWeight = "";
                                    if ($row_cta['label'] == 0) {
                                        $style = "10";
                                        $textColor = 'red';
                                        $fontWeight = '800';
                                        $textSize = '22px;';
                                    } elseif ($row_cta['label'] == 1) {
                                        $style = (20 * ($row_cta['label']));
                                        $textColor = '#000';
                                        //$fontWeight='500';
                                        $textSize = '19px';
                                    } elseif ($row_cta['label'] == 2) {
                                        $style = (20 * ($row_cta['label']));
                                        $textColor = '#000';
                                        //$fontWeight='500';
                                        $textSize = '16px';
                                    } elseif ($row_cta['label'] == 3) {
                                        $style = (20 * ($row_cta['label']));
                                        $textColor = '#000';
                                        //$fontWeight='500';
                                        $textSize = '14px';
                                    } else {
                                        $style = (20 * ($row_cta['label']));
                                        $textColor = '#000';
                                        //$fontWeight='500';
                                        $textSize = '12px';
                                    }


                                    $ledger_cr = '';
                                    $ledger_dr = '';
                                    $ledger_balance = '';
                                    $ledger_balance_string = '';

                                    if ($row_cta['posted'] == 1) {
                                        $ledger_cr = 0;
                                        $ledger_dr = 0;
                                        $ledger_balance = 0;
                                        $this->db->select('SUM(GR_DEBIT) as total_GR_DEBIT,SUM(GR_CREDIT) as total_GR_CREDIT');
                                        $this->db->from('ac_tb_accounts_voucherdtl');
                                        $this->db->where('CHILD_ID', $row_cta['id']);
                                        $this->db->where('IsActive', 1);
                                        $this->db->group_by("CHILD_ID");
                                        $ledger_cr_dr = $this->db->get()->row();
                                        if (!empty($ledger_cr_dr)) {
                                            $ledger_dr = $ledger_cr_dr->total_GR_DEBIT;
                                            $ledger_cr = $ledger_cr_dr->total_GR_CREDIT;
                                        }
                                        if (($ledger_dr - $ledger_cr) > 0) {
                                            $ledger_balance = $ledger_dr - $ledger_cr;
                                            $ledger_balance = number_format((float)$ledger_balance, 2, '.', ',');
                                            $ledger_balance_string = $ledger_balance .= '&nbsp;&nbsp;Dr';

                                        } else {
                                            $ledger_balance = $ledger_cr - $ledger_dr;
                                            $ledger_balance = number_format((float)$ledger_balance, 2, '.', ',');
                                            $ledger_balance_string = $ledger_balance .= '&nbsp;&nbsp;Cr';
                                        }


                                    }
                                   // if ($ledger_balance > 0) {
                                        ?>

                                        <tr class="item-row">
                                            <td style="
                                                    padding-left:<?php echo $style . 'px' ?>!important;
                                                    color: <?php echo $textColor; ?>;
                                                    font-weight: <?php echo $fontWeight; ?>;
                                                    font-size: <?php echo $textSize ?>;">
                                                <strong>&nbsp;&nbsp; <?php echo $row_cta['parent_name']; ?>
                                                </strong>
                                            </td>
                                            <td>
                                                <?php echo $row_cta['code']; ?>
                                            </td>
                                            <td></td>
                                            <td class="text-right"><?php echo $ledger_dr ?></td>
                                            <td class="text-right"><?php echo $ledger_cr ?></td>
                                            <td class="text-right"><?php echo $ledger_balance_string ?></td>
                                        </tr>
                                        <?php $twoa++; ?>

                                        <?php
                                   // }
                                endforeach;
                                ?>


                                </tbody>

                                <!--<tfoot>

                                <tr>

                                    <td align="right"><strong>Total Ending Balance (In BDT.)</strong></td>

                                    <td align="right">

                                        <strong><?php /*echo number_format((float)$opDr, 2, '.', ','); */
                                ?></strong>

                                    </td>

                                    <td align="right"><strong><?php
                                /*                                            echo number_format((float)$opCr + $opnignReturn, 2, '.', ',');
                                                                            */
                                ?></strong>

                                    </td>

                                    <td align="right">
                                        <strong><?php /*echo number_format((float)$pDr, 2, '.', ','); */
                                ?></strong></td>

                                    <td align="right">
                                        <strong><?php /*echo number_format((float)$pCr, 2, '.', ','); */
                                ?></strong></td>

                                    <td align="right">
                                        <strong><?php /*echo number_format((float)$cDr, 2, '.', ','); */
                                ?></strong></td>

                                    <td align="right">
                                        <strong><?php /*echo number_format((float)$cCr + $opnignReturn, 2, '.', ','); */
                                ?></strong>
                                    </td>

                                </tr>

                                </tfoot>-->

                            </table>

                        </div>

                    </div>

                    <?php
                else:

                endif;
                ?>

            </div><!-- /.row -->

        </div><!-- /.page-content -->

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




















