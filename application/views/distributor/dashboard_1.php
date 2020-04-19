<style type="text/css">
    #container {
        min-width: 210px;
        max-width: 500px;
        height: 300px;
        margin: 0 auto
    }
    #container2 {
        min-width: 210px;
        max-width: 500px;
        height: 300px;
        margin: 0 auto
    }
    .mystyle{
        height: 78px!important;
    }
</style>
<div class="main-content">
    <div class="main-content-inner">
        <div class="page-content">
            <div class="page-header">
                <input type="hidden" id="com_per" value="<?php echo isset($com_per) ? $com_per : 0 ?>">
                <input type="hidden" id="user_per" value="<?php echo isset($user_per) ? $user_per : 0 ?>">
                <input type="hidden" id="emp_per" value="<?php echo isset($emp_per) ? $emp_per : 0 ?>">
                <input type="hidden" id="pro_per" value="<?php echo isset($pro_per) ? $pro_per : 0 ?>">
                <input type="hidden" id="sal_per" value="<?php echo isset($sal_per) ? $sal_per : 0 ?>">
            </div><!-- /.page-header -->
            <div class="row">
                <div class="col-md-12">
                    <div class="col-sm-6">
                        <div class="left">
                            <span class="btn btn-app btn-sm btn-light no-hover">
                                <span class="line-height-1 bigger-170 blue">
                                    <?php
                                    $thisdis = $this->session->userdata('dis_id');
                                    $this->db->from('supplier');
                                    $this->db->where('dist_id', $thisdis);
                                    echo $this->db->count_all_results();
                                    ?>
                                </span>

                                <br />
                                <span class="line-height-1 smaller-90"> Supplier </span>
                            </span>

                            <span class="btn btn-app btn-sm btn-yellow no-hover">
                                <span class="line-height-1 bigger-170">
                                    <?php
                                    $thisdis = $this->session->userdata('dis_id');
                                    $this->db->from('customer');
                                    $this->db->where('dist_id', $thisdis);
                                    echo $this->db->count_all_results();
                                    ?>
                                </span>

                                <br />
                                <span class="line-height-1 smaller-90"> Customer </span>
                            </span>

                            <span class="btn btn-app btn-sm btn-pink no-hover">
                                <span class="line-height-1 bigger-170">

                                    <?php
                                    $thisdis = $this->session->userdata('dis_id');
                                    $this->db->from('product');
                                    $this->db->where('dist_id', $thisdis);
                                    echo $this->db->count_all_results();
                                    ?>
                                </span>

                                <br />
                                <span class="line-height-1 smaller-90"> Product </span>
                            </span>

                            <span class="btn btn-app btn-sm btn-grey no-hover">
                                <span class="line-height-1 bigger-170">
                                    <?php
                                    $thisdis = $this->session->userdata('dis_id');
                                    $this->db->from('brand');
                                    $this->db->where('dist_id', $thisdis);
                                    echo $this->db->count_all_results();
                                    ?>
                                </span>

                                <br />
                                <span class="line-height-1 smaller-90"> Brand </span>
                            </span>

                            <span class="btn btn-app btn-sm btn-success no-hover">
                                <span class="line-height-1 bigger-170">
                                    <?php
                                    $thisdis = $this->session->userdata('dis_id');
                                    $this->db->from('unit');
                                    $this->db->where('dist_id', $thisdis);
                                    echo $values = $this->db->count_all_results();
                                    ?>
                                </span>

                                <br />
                                <span class="line-height-1 smaller-90"> Unit </span>
                            </span>

                            <span class="btn btn-app btn-sm btn-primary no-hover">
                                <span class="line-height-1 bigger-170">
                                    <?php
                                    $thisdis = $this->session->userdata('dis_id');
                                    $this->db->from('zone');
                                    echo $values = $this->db->count_all_results();
                                    ?>
                                </span>

                                <br />
                                <span class="line-height-1 smaller-90"> Zone </span>
                            </span>
                        </div>
                        <div class="hr hr-double hr8"></div>
                    </div>
                    <div class="col-sm-6">

                        <div class="infobox infobox-green mystyle">
                            <div class="infobox-icon">
                                <i class="ace-icon fa fa-shopping-cart"></i>
                            </div>

                            <div class="infobox-data">
                                <span class="infobox-data-number">
                                    <?php
                                    $thisdis = $this->session->userdata('dis_id');

                                    $this->db->select('sum(quantity) as qty');
                                    $this->db->from('stock');
                                    $this->db->where('dist_id', $thisdis);
                                    $this->db->where('form_id', 23);
                                    $values = $this->db->get()->row();
                                    if (empty($values->qty)) {
                                        echo '0';
                                    } else {

                                        echo $values->qty;
                                    }
                                    ?>
                                </span>
                                <div class="infobox-content">Cylinder Purchase</div>
                            </div>
                        </div>
                        <div class="infobox infobox-red mystyle">
                            <div class="infobox-icon">
                                <i class="ace-icon fa fa-shopping-cart"></i>
                            </div>

                            <div class="infobox-data">
                                <span class="infobox-data-number">
                                    <?php
                                    $thisdis = $this->session->userdata('dis_id');

                                    $this->db->select('sum(quantity) as qty');
                                    $this->db->from('stock');
                                    $this->db->where('dist_id', $thisdis);
                                    $this->db->where('form_id', 24);
                                    $values = $this->db->get()->row();
                                    if (empty($values->qty)) {
                                        echo '0';
                                    } else {

                                        echo $values->qty;
                                    }
                                    ?>
                                </span>
                                <div class="infobox-content">Cylinder Sale</div>
                            </div>
                        </div>
                        <div class="hr hr-double hr8"></div>


                    </div>
                    <div class="col-sm-12">
                        <div class="widget-box transparent" id="recent-box">
                            <div class="widget-header">
                                <h4 class="widget-title lighter smaller">
                                    <i class="ace-icon fa fa-rss orange"></i>RECENT
                                </h4>

                                <div class="widget-toolbar no-border">
                                    <ul class="nav nav-tabs" id="recent-tab">
                                        <li class="active">
                                            <a data-toggle="tab" href="#purchase-tab">Purchase</a>
                                        </li>

                                        <li>
                                            <a data-toggle="tab" href="#sales-tab">Sales</a>
                                        </li>

                                        <li>
                                            <a data-toggle="tab" href="#payment-tab">Payment</a>
                                        </li>

                                        <li>
                                            <a data-toggle="tab" href="#receive-tab">Receive</a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#journal-tab">Journal</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="widget-body">
                                <div class="widget-main padding-4">
                                    <div class="tab-content padding-8">
                                        <div id="purchase-tab" class="tab-pane active">
                                            <div class="page-content">
                                                <div class="row">
                                                    <h4 align="center" style="color: black; background : lightblue; padding: 10px 10px">Latest 5 Purchases List</h4>
                                                    <div class="table-header">
                                                        Purchases List
                                                    </div>
                                                    <div>
                                                        <table id="" class="table table-striped table-bordered table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th>Sl</th>
                                                                    <th>Date</th>
                                                                    <th>PV.No</th>
                                                                    <th>Type</th>
                                                                    <th>Supplier</th>
                                                                    <th>Amount</th>
                                                                    <th>Narration</th>

                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                foreach ($purchasesList as $key => $value):
                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo $key + 1; ?></td>
                                                                        <td><?php echo date('M d, Y', strtotime($value->date)); ?></td>
                                                                        <td><a class="blue" href="<?php echo site_url('viewPurchases/' . $value->generals_id); ?>"><?php echo $value->voucher_no; ?></a></td>
                                                                        <td><?php echo $this->Common_model->tableRow('form', 'form_id', $value->form_id)->name; ?></td>
                                                                        <td><a href="<?php echo site_url('supplierDashboard/' . $value->supplier_id); ?>"><?php
                                                                $suplierInfo = $this->Common_model->tableRow('supplier', 'sup_id', $value->supplier_id);

                                                                echo $suplierInfo->supID . ' [ ' . $suplierInfo->supName . ' ] ';
                                                                    ?></a></td>
                                                                        <td><?php echo number_format((float) $value->debit, 2, '.', ','); ?></td>
                                                                        <td><?php echo $value->narration; ?></td>

                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div><!-- /.col -->
                                            </div><!-- /.row -->
                                            <div class="hr hr-double hr8"></div>
                                        </div>

                                        <div id="sales-tab" class="tab-pane">
                                            <div class="page-content">
                                                <div class="row">
                                                    <h4 align="center" style="color: black; background : lightblue; padding: 10px 10px">Latest 5 Sales List</h4>
                                                    <div class="table-header">
                                                        Sales List
                                                    </div>
                                                    <div>
                                                        <table id="" class="table table-striped table-bordered table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th>Sl</th>
                                                                    <th>Date</th>
                                                                    <th>SV.No</th>
                                                                    <th>Type</th>
                                                                    <th>Customer</th>
                                                                    <th>Amount</th>
                                                                    <th>Memo</th>

                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                foreach ($saleslist as $key => $value):
                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo $key + 1; ?></td>
                                                                        <td><?php echo date('M d, Y', strtotime($value->date)); ?></td>
                                                                        <td><a title="view invoice" href="<?php echo site_url('salesInvoice_view/' . $value->generals_id); ?>"><?php echo $value->voucher_no; ?></a></td>
                                                                        <td><?php echo $this->Common_model->tableRow('form', 'form_id', $value->form_id)->name; ?></td>
                                                                        <td><a href="<?php echo site_url('customerDashboard/' . $value->customer_id); ?>"><?php
                                                                $customerInfo = $this->Common_model->tableRow('customer', 'customer_id', $value->customer_id);

                                                                echo $customerInfo->customerID . ' [ ' . $customerInfo->customerName . ' ] ';
                                                                    ?></a></td>
                                                                        <td><?php echo number_format((float) $value->debit, 2, '.', ','); ?></td>
                                                                        <td><?php echo $value->narration; ?></td>

                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div><!-- /.col -->
                                            </div><!-- /.row -->
                                            <div class="hr hr-double hr8"></div>
                                        </div>


                                        <div id="payment-tab" class="tab-pane">
                                            <div class="page-content">
                                                <div class="row">
                                                    <h4 align="center" style="color: black; background : lightblue; padding: 10px 10px">Latest 5 Payment voucher List</h4>
                                                    <div class="table-header">
                                                        Payment Voucher

                                                    </div>
                                                    <div>
                                                        <table id="" class="table table-striped table-bordered table-hover">
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
                                                                foreach ($paymentVoucher as $key => $value):
                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo $key + 1; ?></td>
                                                                        <td><?php echo $value->voucher_no; ?></td>
                                                                        <td><?php echo date('M d, Y', strtotime($value->date)); ?></td>
                                                                        <td><?php echo $this->Common_model->tableRow('form', 'form_id', $value->form_id)->name; ?></td>
                                                                        <td><?php echo number_format((float) $value->debit, 2, '.', ','); ?></td>
                                                                        <td><?php echo $value->narration; ?></td>

                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div><!-- /.col -->
                                            </div><!-- /.row -->
                                            <div class="hr hr-double hr8"></div>
                                        </div>
                                        <div id="receive-tab" class="tab-pane">
                                            <div class="page-content">
                                                <div class="row">
                                                    <h4 align="center" style="color: black; background : lightblue; padding: 10px 10px">Latest 5 Receive voucher List</h4>
                                                    <div class="table-header">
                                                        Receive Voucher
                                                    </div>
                                                    <div>
                                                        <table id="" class="table table-striped table-bordered table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th>Sl</th>
                                                                    <th>RV.No</th>
                                                                    <th>Date</th>
                                                                    <th>Type</th>
                                                                    <th>Amount</th>
                                                                    <th>Memo</th>

                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                foreach ($receiveVoucher as $key => $value):
                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo $key + 1; ?></td>
                                                                        <td><?php echo $value->voucher_no; ?></td>
                                                                        <td><?php echo date('M d, Y', strtotime($value->date)); ?></td>
                                                                        <td><?php echo $this->Common_model->tableRow('form', 'form_id', $value->form_id)->name; ?></td>

                                                                        <td><?php echo number_format((float) $value->debit, 2, '.', ','); ?></td>
                                                                        <td><?php echo $value->narration; ?></td>

                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div><!-- /.col -->
                                            </div><!-- /.row -->
                                            <div class="hr hr-double hr8"></div>
                                        </div>
                                        <div id="journal-tab" class="tab-pane">
                                            <div class="page-content">
                                                <div class="row">
                                                    <h4 align="center" style="color: black; background : lightblue; padding: 10px 10px">Latest 5 Journal voucher List</h4>
                                                    <div class="table-header">
                                                        Journal Voucher

                                                    </div>
                                                    <div>
                                                        <table id="" class="table table-striped table-bordered table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th>Sl</th>
                                                                    <th>JV.No</th>
                                                                    <th>Date</th>
                                                                    <th>Type</th>
                                                                    <th>Amount</th>
                                                                    <th>Memo</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                foreach ($journalVoucher as $key => $value):
                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo $key + 1; ?></td>
                                                                        <td><?php echo $value->voucher_no; ?></td>
                                                                        <td><?php echo date('M d, Y', strtotime($value->date)); ?></td>
                                                                        <td><?php echo $this->Common_model->tableRow('form', 'form_id', $value->form_id)->name; ?></td>
                                                                        <td><?php echo number_format((float) $value->debit, 2, '.', ','); ?></td>
                                                                        <td><?php echo $value->narration; ?></td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div><!-- /.col -->
                                            </div><!-- /.row -->
                                            <div class="hr hr-double hr8"></div>
                                        </div>
                                    </div>
                                </div><!-- /.widget-main -->
                            </div><!-- /.widget-body -->
                        </div><!-- /.widget-box -->
                    </div><!-- /.col -->
                  
                </div>
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div>












