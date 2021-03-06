<style>
    #receiveby{
        border-bottom: 1px dashed black;

    }


</style>
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state noPrint" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="<?php echo site_url('DistributorDashboard/3'); ?>">Inventory</a>
                </li>
                <li class="active">Supplier Money Receipt</li>
            </ul>

            <ul class="breadcrumb pull-right">
                
                <li>
                    <a href="<?php echo site_url('DistributorDashboard/3'); ?>">
                        <i class="ace-icon fa fa-list"></i>
                        List
                    </a>
                </li>
                

            </ul>
            
            
            
        </div>
        <br>

        <div class="page-content">
            <div class="row">
                <div class="col-sm-10 col-sm-offset-1">
                    <div class="widget-box transparent">
                        <div class="widget-header widget-header-large">
                            <h3 class="widget-title grey lighter">
                                <i class="ace-icon fa fa-leaf green"></i>
                                Money Receipt
                            </h3>

                            <!--                            <div class="widget-toolbar no-border invoice-info">
                                                            <span class="invoice-info-label"></span>
                                                           <a  onclick="window.print();" style="cursor:pointer;">
                                                                <i class="ace-icon fa fa-print"></i>Money Receipt
                                                            </a>
                                                        </div>-->





                            <div class="widget-toolbar no-border invoice-info">
                                <span class="invoice-info-label">Payment Type:</span>
                                <span class="red"><?php
                                    echo "Cash";
                                    ?></span><br>
                                <span class="invoice-info-label">Check Status:</span>
                                <span class="red"><?php
                                    if ($moneyReceitInfo->checkStatus == 1) {
                                        echo "Pending";
                                    } else {
                                        echo "Received";
                                    }
                                    ?></span>

                            </div>




                            <div class="widget-toolbar no-border invoice-info">
                                <span class="invoice-info-label">Receipt ID:</span>
                                <span class="red"><?php echo $moneyReceitInfo->receitID; ?></span>

                                <br />
                                <span class="invoice-info-label">Date:</span>
                                <span class="blue"><?php echo $moneyReceitInfo->date ?></span>
                            </div>

                            <div class="widget-toolbar hidden-480"  class="hidden-xs">
                                <a  onclick="window.print();" style="cursor:pointer;">
                                    <i class="ace-icon fa fa-print"></i>
                                </a>
                            </div>
                        </div>

                        <div class="widget-body">
                            <div class="widget-main padding-24">
                                <div class="row"  >
                                    <div class="col-xs-6">
                                        <div class="row">
                                            <div class="col-xs-11 label label-lg label-info arrowed-in arrowed-right">
                                                Company Info
                                            </div>
                                        </div>

                                        <div>
                                            <ul class="list-unstyled spaced">
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right blue"></i><?php echo $companyInfo->companyName; ?>
                                                </li>

                                                <li>
                                                    <i class="ace-icon fa fa-caret-right blue"></i><?php echo $companyInfo->email; ?>
                                                </li>

                                                <li>
                                                    <i class="ace-icon fa fa-caret-right blue"></i><?php echo $companyInfo->phone; ?>
                                                </li>
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right blue"></i><?php echo $companyInfo->address; ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </div><!-- /.col -->

                                    <div class="col-xs-6">
                                        <div class="row">
                                            <div class="col-xs-11 label label-lg label-success arrowed-in arrowed-right">
                                                Customer Info
                                            </div>
                                        </div>

                                        <div>
                                            <ul class="list-unstyled  spaced">
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i><?php echo $customerInfo->customerID . '[' . $customerInfo->customerName . ']' ?>
                                                </li>

                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i><?php echo $customerInfo->customerEmail; ?>
                                                </li>

                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i><?php echo $customerInfo->customerPhone; ?>
                                                </li>
                                                <li>
                                                    <i class="ace-icon fa fa-caret-right green"></i><?php echo $customerInfo->customerAddress; ?>
                                                </li>


                                            </ul>
                                        </div>
                                    </div><!-- /.col -->
                                </div><!-- /.row -->

                                <div class="space"></div>

                                <div style="min-height:400px;" >
                                    <table class="table ">
                                        <tr>
                                            <td nowrap width="15%"><strong>Received with thanks from</strong></td>
                                            <td width="1%">:</td>
                                            <td id="receiveby"><?php echo $customerInfo->customerName; ?></td>
                                        </tr>
                                    </table>
                                    <table class="table ">
                                        <tr>
                                            <td  nowrap width="10%"><strong>Amount in TK</strong></td>
                                            <td width="1%">:</td>
                                            <td  class="receiveby"  id="receiveby"><?php
                                                echo $moneyReceitInfo->totalPayment;
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                    <table class="table ">
                                        <tr>
                                            <td  nowrap width="17%"><strong>The Sum of Taka in words</strong></td>
                                            <td width="1%">:</td>
                                            <td id="receiveby" class="receiveby"><?php
                                                echo $this->Common_model->get_bd_amount_in_text($moneyReceitInfo->totalPayment);
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                    <?php
                                    $invoiceinfo = json_decode($moneyReceitInfo->invoiceID);
//dumpVar($invoiceinfo);
                                    ?>

                                    <table class="table ">
                                        <tr>
                                            <td  nowrap  width="10%"><strong>On Account of</strong></td>
                                            <td nowrap width="1%">:</td>
                                            <td  nowrap class="receiveby"  id="receiveby">
                                                <?php
                                                foreach ($invoiceinfo as $eachInfo):
                                                    $voucher = explode('_', $eachInfo);
                                                    echo $voucher[0] . ',';
                                                endforeach;
                                                ?>


                                            </td>
                                        </tr>
                                    </table>

                                    <?php
                                    if ($moneyReceitInfo->paymentType == 1):
                                        ?>
                                        <table class="table ">
                                            <tr>
                                                <td><strong>By Cash</strong></td>

                                            </tr>
                                        </table>
                                    <?php else: ?>
                                        <table class="table ">
                                            <tr>
                                                <td nowrap width="5%"><strong>By Bank:</strong></td>
                                                <td nowrap id="receiveby" width="20%"><?php echo $moneyReceitInfo->bankName; ?></td>
                                                <td  nowrap width="5%"><strong>Cheque No:</strong></td>
                                                <td  id="receiveby" width="20%"><?php echo $moneyReceitInfo->checkNo; ?></td>
                                                <td  nowrap width="5%"><strong>Branch Name: </strong></td>
                                                <td id="receiveby" width="20%"><?php echo $moneyReceitInfo->branchName; ?></td>
                                                <td  nowrap width="5%"><strong>Date: </strong></td>
                                                <td id="receiveby" width="20%"><?php echo $moneyReceitInfo->checkDate; ?></td>
                                            </tr>
                                        </table>
                                        <br>
                                        <p align="left;">&nbsp;&nbsp;NB: Payment by cheque will be valid subject to realization of the cheque.</p>
                                    <?php endif; ?>
                                </div>

                                <div class="hr hr8 hr-double hr-dotted"></div>


                                <div class="row">
                                    <div class="col-xs-4 text-center">
                                        <p>Prepared By:_____________<br />
                                            Date:____________________
                                        </p>                        
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <p>Checked By:_______________<br />
                                            Date:__________________</p>                       
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <p>Approved By:________________<br />
                                            Date:_________________</p>  
                                    </div>
                                </div>

                                <hr />
                                <p class="text-center"><?php //echo $this->mtcb->table_row('system_config', 'option', 'ADDRESS')->value;                                ?></p>

                                <!--                                <div class="space-6"></div>
                                                                <div class="well">
                                                                    Thank you for choosing Ace Company products.
                                                                    We believe you will be satisfied by our services.
                                                                </div>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>
