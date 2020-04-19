<?php
if (isset($_POST['start_date'])):
    $account = $this->input->post('accountHead');
    $from_date = $this->input->post('start_date');
    $start_date = date('Y-m-d', strtotime($this->input->post('start_date')));
    $to_date = $this->input->post('end_date');
    $end_date = date('Y-m-d', strtotime($this->input->post('end_date')));
endif;
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    Income Statement
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
                    //  dumpVar($_POST);
                    $from_date = date('Y-m-d', strtotime($this->input->post('start_date')));
                    $to_date = date('Y-m-d', strtotime($this->input->post('end_date')));
                    $account = $this->input->post('accountHead');
                    $dist_id = $this->dist_id;
                    unset($_SESSION["account"]);
                    unset($_SESSION["start_date"]);
                    unset($_SESSION["end_date"]);
                    $_SESSION["account"] = $account;
                    $_SESSION["start_date"] = $from_date;
                    $_SESSION["end_date"] = $to_date;
                    $dist_id = $this->dist_id;
                    $total_pvsdebit = '';
                    $total_pvscredit = '';
                    $total_debit = '';
                    $total_credit = '';
                    $total_balance = '';
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
                                        <strong><?php echo $pageTitle; ?> : </strong>
                                        From <?php echo $from_date; ?> To <?php echo $to_date; ?>
                                    </td>
                                </tr>
                            </table>
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <td align="center"><strong>Code</strong></td>
                                    <td align="center"><strong>Description</strong></td>
                                    <td align="center"><strong>Total Balance (In BDT.)</strong></td>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $sales_revenue = $this->Accounts_model->incomeStatement(3, 58, $start_date, $end_date, $branch);
                                ?>
                                <tr>
                                    <td align="left"><?php echo '<b>' . get_phrase($sales_revenue[0]->PN1_Code) .'</b>' ?></td>
                                    <td colspan=" 2"><?php echo '<b>' . get_phrase($sales_revenue[0]->PN1) .'</b>' ?></td>
                                </tr>
                                <?php
                                foreach ($sales_revenue as $key => $value) {
                                    ?>
                                    <tr>
                                        <td align="left"><?php echo '<b>' . $value->PN2_Code  .'</b>'  ?></td>
                                        <td align="left"><?php echo '<b>' . $value->PN2 .'</b>' ?></td>
                                        <td><?php echo $value->dr_amount - $value->cr_amount ?></td>
                                    </tr>

                                <?php } ?>
                                <?php
                                $cost_of_goods_sold = $this->Accounts_model->incomeStatement2(4, 44, 45, $start_date, $end_date, $branc);
                                ?>
                                <tr>
                                    <td align="left"><?php echo get_phrase($sales_revenue[0]->PN1_Code) ?></td>
                                    <td colspan=" 2"><?php echo get_phrase($cost_of_goods_sold[0]->PN1) ?></td>
                                </tr>
                                <?php
                                foreach ($cost_of_goods_sold as $key => $value) {
                                    ?>
                                    <tr>
                                        <td align="left"><?php echo $value->PN2_Code ?></td>
                                        <td align="left"><?php echo $value->PN2 ?></td>
                                        <td><?php echo $value->dr_amount - $value->cr_amount ?></td>
                                    </tr>

                                <?php } ?>
                                <tr>
                                    <td colspan="2" align="left">
                                        Gross Profit nnn
                                    </td>
                                    <td>

                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" align="left">
                                       Add, Other Income
                                    </td>

                                </tr>
                                <?php
                                $otherIncomeWothoutSales = $this->Accounts_model->incomeStatement_with_out_sales_revenue(3, 36, $start_date, $end_date, $branch);;
                                //$this->Accounts_model->balanceshit(3, $end_date);

                                //echo $this->db->last_query();

                                $secondLebelArray = array();
                                $total_liability=0;
                                foreach ($otherIncomeWothoutSales as $key => $value) {
                                    if (!in_array($value->PARENT_ID1, $secondLebelArray) && $value->PARENT_ID1!=0) {
                                        array_push($secondLebelArray, $value->PARENT_ID1);
                                        ?>

                                        <tr>
                                            <td>
                                                <?php echo '<b>' . $value->PN1_Code . '</b>' ?>
                                            </td>
                                            <td colspan="2"><?php echo '<b>' . $value->PN1 . '</b>' ?></td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <?php echo $value->PN2_Code ?>
                                            </td>
                                            <td><?php echo $value->PN2 ?></td>
                                            <td align="right">
                                                <?php
                                                $dr_amount=0;
                                                $cr_amount=0;
                                                $dr_amount=$value->dr_amount+$value->opening_debit;
                                                $cr_amount=$value->cr_amount+$value->opening_credit;
                                                if($dr_amount-$cr_amount>0){
                                                    $amount= $dr_amount-$cr_amount;
                                                    $total_liability=$total_liability+$amount;
                                                }else{
                                                    $amount= $cr_amount-$dr_amount;
                                                    $total_liability=$total_liability+$amount;
                                                    $amount="(".$amount.")";
                                                }
                                                echo $amount;
                                                ?>
                                            </td>

                                        </tr>

                                        <?php
                                    } else {
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo $value->PN2_Code ?>
                                            </td>
                                            <td><?php echo $value->PN2 ?></td>
                                            <td align="right">
                                                <?php
                                                $dr_amount=0;
                                                $cr_amount=0;
                                                $dr_amount=$value->dr_amount+$value->opening_debit;
                                                $cr_amount=$value->cr_amount+$value->opening_credit;
                                                if($dr_amount-$cr_amount>0){
                                                    $amount= $dr_amount-$cr_amount;
                                                    $total_liability=$total_liability+$amount;
                                                }else{
                                                    $amount= $cr_amount-$dr_amount;
                                                    $total_liability=$total_liability+$amount;
                                                    $amount="(".$amount.")";
                                                }
                                                echo $amount;
                                                ?>
                                            </td>

                                        </tr>


                                    <?php }
                                } ?>
                                <?php
                                $expenses=$this->Accounts_model->balanceshit(4, $end_date);
                                $secondLebelArray = array();
                                $total_liability=0;
                                foreach ($expenses as $key => $value) {
                                    if (!in_array($value->PARENT_ID1, $secondLebelArray)) {
                                        array_push($secondLebelArray, $value->PARENT_ID1);
                                        ?>

                                        <tr>
                                            <td>
                                                <?php echo '<b>' . $value->PN1_Code . '</b>' ?>
                                            </td>
                                            <td colspan="2"><?php echo '<b>' . $value->PN1 . '</b>' ?></td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <?php echo $value->PN2_Code ?>
                                            </td>
                                            <td><?php echo $value->PN2 ?></td>
                                            <td align="right">
                                                <?php
                                                $dr_amount=0;
                                                $cr_amount=0;
                                                $dr_amount=$value->dr_amount+$value->opening_debit;
                                                $cr_amount=$value->cr_amount+$value->opening_credit;
                                                if($dr_amount-$cr_amount>0){
                                                    $amount= $dr_amount-$cr_amount;
                                                    $total_liability=$total_liability+$amount;
                                                }else{
                                                    $amount= $cr_amount-$dr_amount;
                                                    $total_liability=$total_liability+$amount;
                                                    $amount="(".$amount.")";
                                                }
                                                echo $amount;
                                                ?>
                                            </td>

                                        </tr>

                                        <?php
                                    } else {
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo $value->PN2_Code ?>
                                            </td>
                                            <td><?php echo $value->PN2 ?></td>
                                            <td align="right">
                                                <?php
                                                $dr_amount=0;
                                                $cr_amount=0;
                                                $dr_amount=$value->dr_amount+$value->opening_debit;
                                                $cr_amount=$value->cr_amount+$value->opening_credit;
                                                if($dr_amount-$cr_amount>0){
                                                    $amount= $dr_amount-$cr_amount;
                                                    $total_liability=$total_liability+$amount;
                                                }else{
                                                    $amount= $cr_amount-$dr_amount;
                                                    $total_liability=$total_liability+$amount;
                                                    $amount="(".$amount.")";
                                                }
                                                echo $amount;
                                                ?>
                                            </td>

                                        </tr>


                                    <?php }
                                } ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php else:
                    ?>
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