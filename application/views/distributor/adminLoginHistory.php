<div class="row">    <div class="col-md-12">        <div class="portlet box blue">            <div class="portlet-title" style="min-height:21px">                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">                     Admin Login History </div>                      </div>                      <div class="portlet-body">                    <table id="example" class="table table-striped table-bordered table-hover">                        <thead>                            <tr>                                <th><strong><?php echo get_phrase('SL')?></strong></th>                                <th><strong><?php echo get_phrase('Date')?></strong></th>                                <th><strong><?php echo get_phrase('IP_Address')?></strong></th>                                <th><strong><?php echo get_phrase('User_Name')?></strong></th>                                <th><strong><?php echo get_phrase('Log_In')?></strong></th>                                <th><strong><?php echo get_phrase('Log_Out')?></strong></th>                                <th><strong><?php echo get_phrase('Total_Stay')?></strong></th>                            </tr>                        </thead>                        <tbody>                            <?php                            foreach ($adminInfo as $key => $value):                                ?>                                <tr>                                    <td><?php echo $key + 1; ?></td>                                    <td><?php echo date('M d, Y', strtotime($value->date)); ?></td>                                    <td><?php echo $value->ipAddress; ?></td>                                    <td><?php echo $this->Common_model->tableRow('admin', 'admin_id', $value->adminId)->name; ?></td>                                    <td><?php echo $value->logIn; ?></td>                                    <td><?php echo $value->logOut; ?></td>                                    <td><?php                            if (!empty($value->logOut)):                                $date1 = date_create($value->logIn);                                $date2 = date_create($value->logOut);                                $diff = date_diff($date1, $date2);                                echo $diff->format("%H:%I:%S");                            else:                                echo "<span style='color:red;'>Not Signout</span>";                            endif;                                ?></td>                                </tr>                            <?php endforeach; ?>                        </tbody>                    </table>                </div>            </div><!-- /.col -->        </div><!-- /.row -->    </div><!-- /.page-content -->