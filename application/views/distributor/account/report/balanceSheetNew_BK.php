<?php
if (isset($_POST['to_date'])):
    $to_date = $this->input->post('to_date');
endif;
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    Balance Sheet
                </div>
            </div>
            <div class="portlet-body">
                <div class="row noPrint">
                    <div class="col-md-12">
                        <form id="publicForm" action="" method="post" class="form-horizontal">
                            <div class="col-sm-10 col-sm-offset-1">
                                <div style="background-color: grey!important;">

                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label no-padding-right"
                                                   for="form-field-1"><span style="color:red;"> *</span> As At </label>
                                            <div class="col-sm-8">
                                                <input type="text" class="date-picker form-control" id="start_date"
                                                       name="to_date" value="<?php
                                                if (!empty($to_date)) {
                                                    echo $to_date;
                                                } else {
                                                    echo date('d-m-Y');
                                                }
                                                ?>" data-date-format='dd-mm-yyyy' placeholder="Start Date: dd-mm-yyyy"/>
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
                if (isset($_POST['to_date'])):
                    //  dumpVar($_POST);
                    $to_date = date('Y-m-d', strtotime($this->input->post('to_date')));
                    unset($_SESSION["to_date"]);
                    $_SESSION["to_date"] = $to_date;
                    $dist_id = $this->dist_id;
                    $total_income = '';
                    $total_costs = '';
                    $total_assets = '';
                    $total_liabilityies = '';
                    $total_equity_govt = '';
                    ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-responsive">
                                <tr>
                                    <td style="text-align:center;">
                                        <h3><?php echo $companyInfo->companyName; ?>.</h3>
                                        <span><?php echo $companyInfo->address; ?></span><br>
                                        <strong>Phone : </strong><?php echo $companyInfo->phone; ?><br>
                                        <strong>Email : </strong><?php echo $companyInfo->email; ?><br>
                                        <strong>Website : </strong><?php echo $companyInfo->website; ?><br>
                                        <strong><?php echo $pageTitle; ?></strong>
                                        <?php echo $to_date; ?>
                                    </td>
                                </tr>
                            </table>

                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <td align="center"><strong>Code</strong></td>
                                    <td align="center"><strong>Description</strong></td>
                                    <td align="center"><strong>Amount</strong></td>
                                    <td align="center"><strong>Amount</strong></td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td colspan="4" align="center">
                                        <strong>
                                            ASSETS
                                        </strong>
                                    </td>
                                </tr>
                                <?php
                                $secondLebelArray = array();
                                $total_assets=0;
                                foreach ($assetList as $key => $value) {
                                    if (!in_array($value->PARENT_ID1, $secondLebelArray)) {
                                        array_push($secondLebelArray, $value->PARENT_ID1);
                                        ?>

                                        <tr>
                                            <td>
                                                <?php echo '<b>' . $value->PN1_Code . '</b>' ?>
                                            </td>
                                            <td colspan="3"><?php echo '<b>' . $value->PN1 . '</b>' ?></td>

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
                                                $dr_amount=$value->dr_amount;
                                                $cr_amount=$value->cr_amount;
                                                if($dr_amount-$cr_amount>0){
                                                    $amount= $dr_amount-$cr_amount;
                                                    $total_assets=$total_assets+$amount;
                                                }else{
                                                    $amount= $cr_amount-$dr_amount;
                                                    $total_assets=$total_assets+$amount;
                                                    $amount="(".$amount.")";
                                                }
                                                echo $amount;
                                                ?>
                                            </td>
                                            <td></td>
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
                                                $dr_amount=$value->dr_amount;
                                                $cr_amount=$value->cr_amount;
                                                if($dr_amount-$cr_amount>0){
                                                   $amount= $dr_amount-$cr_amount;
                                                    $total_assets=$total_assets+$amount;
                                                }else{
                                                    $amount= $cr_amount-$dr_amount;
                                                    $total_assets=$total_assets+$amount;
                                                    $amount="(".$amount.")";
                                                }
                                                echo $amount;
                                                ?>
                                            </td>
                                            <td></td>
                                        </tr>


                                    <?php }
                                } ?>
                                <tr>
                                    <td  colspan="2">

                                    </td>
                                    <td align="right">
                                        <?php echo $total_assets?>
                                    </td>
                                    <td>

                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="4" align="center">
                                        <strong>
                                            Capital & Liabilities
                                        </strong>
                                    </td>
                                </tr>
                                <?php
                                $secondLebelArray = array();
                                $total_liability=0;
                                foreach ($liabilityList as $key => $value) {
                                    if (!in_array($value->PARENT_ID1, $secondLebelArray)) {
                                        array_push($secondLebelArray, $value->PARENT_ID1);
                                        ?>

                                        <tr>
                                            <td>
                                                <?php echo '<b>' . $value->PN1_Code . '</b>' ?>
                                            </td>
                                            <td colspan="3"><?php echo '<b>' . $value->PN1 . '</b>' ?></td>

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
                                                $dr_amount=$value->dr_amount;
                                                $cr_amount=$value->cr_amount;
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
                                            <td></td>
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
                                                $dr_amount=$value->dr_amount;
                                                $cr_amount=$value->cr_amount;
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
                                            <td></td>
                                        </tr>


                                    <?php }
                                } ?>
                                <tr>
                                    <td  colspan="2">

                                    </td>
                                    <td align="right">
                                        <?php echo $total_liability?>
                                    </td>
                                    <td>

                                    </td>
                                </tr>






                                </tbody>


                            </table>


                        </div>
                    </div>
                <?php endif; ?>
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div>
<style>
    .show_hide {
        display: none;
    }

    .a {
        font: 2em Arial;
        text-decoration: none
    }


</style>

<script>

    $(document).ready(function () {
        $('.show_hide').toggle(function () {
            alert("hello");
            $("#plus51").text("-");


        }, function () {
            $("#plus51").text("+");

        });
    });

</script>
<script>

    $(document).ready(function () {


        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        })

    });
</script>
