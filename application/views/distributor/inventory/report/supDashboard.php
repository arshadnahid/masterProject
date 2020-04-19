<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="<?php echo site_url('DistributorDashboard/3'); ?>">Inventory</a>
                </li>
                <li class="active">Supplier Dashboard</li>
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
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    <div class="hr dotted"></div>
                    <div>
                        <div id="user-profile-1" class="user-profile row">
                            <div class="col-xs-12 col-sm-3 center">
                                <div>
                                    <span class="profile-picture">
                                        <?php if (!empty($supplierDetails->supLogo)): ?>
                                            <img width="200px" id="avatar" class="editable img-responsive" alt="<?php echo $supplierDetails->supName; ?>" src="<?php echo base_url('uploads/thumb/' . $supplierDetails->supLogo); ?>" />
                                        <?php else: ?>
                                            <img width="200px" id="avatar" class="editable img-responsive" alt="<?php echo $supplierDetails->supName; ?>" src="<?php echo base_url('assets/images/default.png'); ?>" />
                                        <?php endif; ?>
                                    </span>
                                    <div class="space-4"></div>
                                    <div class="width-80 label label-info label-xlg arrowed-in arrowed-in-right">
                                        <div class="inline position-relative">
                                            <a href="#" class="user-title-label dropdown-toggle" data-toggle="dropdown">
                                                <i class="ace-icon fa fa-circle light-green"></i>
                                                &nbsp;
                                                <span class="white"><?php echo $dist_info->dist_name ?></span>
                                            </a>
                                            <ul class="align-left dropdown-menu dropdown-caret dropdown-lighter">
                                                <li class="dropdown-header"> Change Status </li>
                                                <li>
                                                    <a href="#">
                                                        <i class="ace-icon fa fa-circle green"></i>
                                                        &nbsp;
                                                        <span class="green">Available</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <i class="ace-icon fa fa-circle red"></i>
                                                        &nbsp;
                                                        <span class="red">Busy</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <i class="ace-icon fa fa-circle grey"></i>
                                                        &nbsp;
                                                        <span class="grey">Invisible</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="space-6"></div>
                                <div class="profile-contact-info">
                                    <div class="profile-contact-links align-left">
                                        <!-- <a href="#" class="btn btn-link">
                                        <i class="ace-icon fa fa-plus-circle bigger-120 green"></i>
                                        Add as a friend
                                        </a> -->
                                        <a href="#" class="btn btn-link">
                                            <i class="ace-icon fa fa-envelope bigger-120 pink"></i>
                                            Send a message
                                        </a>
                                        <a href="#" class="btn btn-link">
                                            <i class="ace-icon fa fa-globe bigger-125 blue"></i>
                                            www.distributor.com
                                        </a>
                                    </div>
                                    <div class="space-6"></div>
                                    <div class="profile-social-links align-center">
                                        <a href="<?php echo $dist_info->dist_id ?>" class="tooltip-info" title="" data-original-title="Visit my Facebook">
                                            <i class="middle ace-icon fa fa-facebook-square fa-2x blue"></i>
                                        </a>
                                        <a href="mailto:<?php echo $dist_info->dist_email ?>" class="tooltip-error" title="" data-original-title="Visit my Pinterest">
                                            <i class="middle ace-icon fa  fa-envelope-square fa-2x red"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="hr hr12 dotted"></div>
                                <div class="hr hr16 dotted"></div>
                            </div>

                            <div class="col-xs-12 col-sm-9">
                                <div class="table-header">
                                    Supplier Dashboard
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-9">
                                <div class="tabbable">
                                    <ul class="nav nav-tabs" id="myTab">
                                        <li class="active">
                                            <a data-toggle="tab" href="#supplierInfo">
                                                <i class="green ace-icon fa fa-home bigger-120"></i>
                                                Supplier Info
                                            </a>
                                        </li>

                                        <li>
                                            <a data-toggle="tab" href="#purchasesList">
                                                Purchases List
                                                <span class="badge badge-danger"><?php echo count($purchasesList); ?></span>
                                            </a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#paymentList">
                                                Payment List
                                                <span class="badge badge-danger"><?php echo count($supplierPayment); ?></span>
                                            </a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#voucherList">
                                                Due Voucher List
                                                <span id="totalDue" class="badge badge-danger"></span>
                                            </a>
                                        </li>
                                    </ul>

                                    <div class="tab-content">


                                        <div id="purchasesList" class="tab-pane fade">
                                            <div class="table-header">
                                                Purchases List
                                            </div>
                                            <div>
                                                <table  class="table table-striped table-bordered table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Sl</th>
                                                            <th>Date</th>
                                                            <th>PV.No</th>
                                                            <th>Type</th>
                                                            <th>Supplier</th>
                                                            <th>Amount</th>
                                                            <th>Narration</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $totalPurchases = '';
                                                        foreach ($purchasesList as $key => $value):
                                                            $totalPurchases+=$value->debit;
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $key + 1; ?></td>
                                                                <td><?php echo date('M d, Y', strtotime($value->date)); ?></td>
                                                                <td><a class="blue" href="<?php echo site_url('viewPurchases/' . $value->generals_id); ?>"><?php echo $value->voucher_no; ?></a></td>
                                                                <td><?php echo $this->Common_model->tableRow('form', 'form_id', $value->form_id)->name; ?></td>
                                                                <td><?php
                                                        $suplierInfo = $this->Common_model->tableRow('supplier', 'sup_id', $value->supplier_id);

                                                        echo $suplierInfo->supID . ' [ ' . $suplierInfo->supName . ' ] ';
                                                            ?></td>
                                                                <td><?php echo number_format((float) $value->debit, 2, '.', ','); ?></td>
                                                                <td><?php echo $value->narration; ?></td>
                                                                <td>
                                                                    <div class="hidden-sm hidden-xs action-buttons">
                                                                        <a class="blue" href="<?php echo site_url('viewPurchases/' . $value->generals_id); ?>">
                                                                            <i class="ace-icon fa fa-search-plus bigger-130"></i>
                                                                        </a>

                                                                                                <!--                                            <a class="green" href="<?php echo site_url('editPurchases/' . $value->generals_id); ?>">
                                                                                                                                                <i class="ace-icon fa fa-pencil bigger-130"></i>
                                                                                                                                            </a>-->



                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div id="paymentList" class="tab-pane fade">

                                            <div class="table-header">
                                                Supplier Payment List
                                            </div>
                                            <div>
                                                <table class="table table-striped table-bordered table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Sl</th>
                                                            <th>Date</th>
                                                            <th>PV.No</th>
                                                            <th>Type</th>
                                                            <th>Supplier</th>
                                                            <th>Amount</th>
                                                            <th>Memo</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $totalPayment = '';
                                                        foreach ($supplierPayment as $key => $value):
                                                            $totalPayment+=$value->credit;
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $key + 1; ?></td>
                                                                <td><?php echo date('M d, Y', strtotime($value->date)); ?></td>
                                                                <td><?php echo $value->voucher_no; ?></td>
                                                                <td><?php echo $this->Common_model->tableRow('form', 'form_id', $value->form_id)->name; ?></td>
                                                                <td><?php
                                                        $suplierInfo = $this->Common_model->tableRow('supplier', 'sup_id', $value->supplier_id);

                                                        echo $suplierInfo->supID . ' [ ' . $suplierInfo->supName . ' ] ';
                                                            ?></td>
                                                                <td><?php echo number_format((float) $value->credit, 2, '.', ','); ?></td>
                                                                <td><?php echo $value->narration; ?></td>
                                                                <td>
                                                                    <div class="hidden-sm hidden-xs action-buttons">
                                                                        <a class="blue" href="<?php echo site_url('viewPurchases/' . $value->generals_id); ?>">
                                                                            <i class="ace-icon fa fa-search-plus bigger-130"></i>
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>



                                        </div>
                                        <div id="voucherList" class="tab-pane fade">
                                            <div class="table-header">
                                                Due Voucher List
                                            </div>
                                            <div>
                                                <table class="table table-striped table-bordered table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Sl</th>
                                                            <th>PV.No</th>
                                                            <th>Date</th>
                                                            <th>Type</th>
                                                            <th>Amount</th>
                                                            <th>Memo</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $dueVoucherList = '';
                                                        $query = $this->Inventory_Model->generals_supplier($supplierDetails->sup_id);
                                                        foreach ($query as $key => $row):
                                                            $value = $this->Inventory_Model->generals_voucher($row['voucher_no']);
                                                            if (!empty($value)):
                                                                if ($this->Inventory_Model->generals_voucher($row['voucher_no']) != 0):
                                                                    $dueVoucherList+=1;
                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo $key + 1; ?></td>
                                                                        <td><a href="<?php echo site_url('viewPurchases/' . $row['generals_id']) ?>"><?php echo $row['voucher_no']; ?></a></td>
                                                                        <td><?php echo date('d.m.Y', strtotime($row['date'])) ?></td>
                                                                        <td><?php echo $this->Common_model->tableRow('form', 'form_id', $row['form_id'])->name; ?></td>
                                                                        <td align="right"><?php echo $this->Inventory_Model->generals_voucher($row['voucher_no']) ?></td>
                                                                        <td><?php echo $row['narration']; ?></td>
                                                                    </tr>
                                                                    <?php
                                                                endif;
                                                            endif;
                                                        endforeach;
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <script>
                                            $("#totalDue").text('<?php
                                                        if (!empty($dueVoucherList)):
                                                            echo $dueVoucherList;
                                                        else: echo "0";
                                                        endif;
                                                        ?>');  
                           
                                
                                        </script>
                                        <div id="supplierInfo" class="tab-pane fade in active">

                                            <!--                                 <div class="table-header">
                                                                               Supplier Basic Information
                                                                            </div>-->
                                            <div class="profile-user-info profile-user-info-striped">
                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> Supplier ID </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable" id="username"><?php echo $supplierDetails->supID ?></span>
                                                    </div>
                                                </div>
                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> Supplier Name </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable" id="username"><?php echo $supplierDetails->supName ?></span>
                                                    </div>
                                                </div>
                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> Supplier Email </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable" id="username"><?php echo $supplierDetails->supEmail ?></span>
                                                    </div>
                                                </div>
                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> Supplier Phone </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable" id="username"><?php echo $supplierDetails->supPhone ?></span>
                                                    </div>
                                                </div>


                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> Address </div>

                                                    <div class="profile-info-value">
                                                        <i class="fa fa-map-marker light-orange bigger-110"></i>
                                                        <span class="editable" id="country"><?php echo $supplierDetails->supAddress ?></span>
                                                        <span class="editable" id="city">Bangladesh</span>
                                                    </div>
                                                </div>

                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> Supplier Status </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable" id="username">
                                                            <?php
                                                            if ($supplierDetails->status == 1):
                                                                ?>
                                                                <a href="javascript:void(0)"  class="label label-danger arrowed">
                                                                    <i class="ace-icon fa fa-fire bigger-110"></i>
                                                                    Inactive</a>
                                                            <?php else: ?>
                                                                <a href="javascript:void(0)"  class="label label-success arrowed">
                                                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                                                    Active
                                                                </a>
                                                            <?php endif; ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>




                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.page-content -->
    </div>


