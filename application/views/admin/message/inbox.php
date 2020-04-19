<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Message</a>
                </li>
                <li class="active">Message List</li>
            </ul>
            <span style="padding-top: 5px!important;">
                <a style="border-radius:100px 0 100px 0;" href="<?php echo site_url('inbox_add'); ?>" class="btn btn-success pull-right">
                    <i class="ace-icon fa fa-plus"></i>
                    Add New
                </a>
            </span>
        </div>


        <div class="page-content">
            <div class="row">
                <div class="table-header">
                    Message List
                </div>
                <div>

                    <table id="example" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Date</th>
                                <th>Recipient</th>
                                <th>Message Subject</th>
                                <th>Message Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>


                            <?php
                            foreach ($inboxList as $key => $value):

                                $allData = explode('-', $value->msgTo);
                               // dumpVar($allData);
                                ?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td><?php echo $value->date; ?></td>
                                    <td><?php 
                                    foreach($allData as $eachInfo):
                                       echo $this->Common_model->tableRow('tbl_distributor','dist_id',$eachInfo)->dist_name.'<br>';
                                    endforeach;
                                    
                                    ?></td>
                                    
                                    <td><?php echo $value->msgSubject; ?></td>
                                    <td><?php echo $value->msgDescrip; ?></td>
                                    <td>
                                        <div class="hidden-sm hidden-xs action-buttons">
                                            <!--                                                <a class="blue" href="#">
                                                                                                <i class="ace-icon fa fa-search-plus bigger-130"></i>
                                                                                            </a>-->

                                            <a class="green" href="<?php echo site_url('offer_edit/' . $value->offerId); ?>">
                                                <i class="ace-icon fa fa-pencil bigger-130"></i>
                                            </a>

                                            <a class="red" href="javascript:void(0)" onclick="deleteData('supplier','sup_id','supplierList','<?php echo $value->offerId; ?>')">
                                                <i class="ace-icon fa fa-trash-o bigger-130"></i>
                                            </a>


                                            <a onclick="return isconfirm()" class="red" href="#">

                                            </a>
                                        </div>


                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <!--                    -->
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div><script src="<?php echo base_url('assets/setup.js'); ?>"></script>

