<div class="main-content">
    <div class="main-content-inner">
        <div class="page-content">
            <div class="row">

                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-3 toppad" >


                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">AEL</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class=" col-md-8 col-md-offset-2" align="center">

                                    <?php if (empty($adminInfo->image)): ?>
                                        <img alt="User Pic" src="<?php echo base_url('assets/images/default.png'); ?>" class="img-circle img-responsive">
                                    <?php else: ?>
                                        <img alt="User Pic" src="<?php echo base_url('uploads/thumb/' . $adminInfo->image); ?>" class="img-circle img-responsive">
                                    <?php endif; ?><hr>
                                </div>

                                
                                <div class=" col-md-8 col-md-offset-2"> 
                                    <form action="" method="POST" >
                                    <table class="table table-user-information">
                                        <tbody>
                                            <tr>
                                                <td>Name:</td>
                                                <td><?php echo $adminInfo->name ?></td>
                                            </tr>
                                            <tr>
                                                <td>Date:</td>
                                                <td><?php echo $adminInfo->created_at ?></td>
                                            </tr>

                                            <tr>
                                                <td> Address</td>
                                                <td><?php echo $adminInfo->address ?></td>
                                            </tr>
                                            <tr>
                                                <td>Email</td>
                                                <td><?php echo $adminInfo->email ?></td>
                                            </tr>
                                            <tr>
                                                <td>Phone Number</td>
                                                <td><?php echo $adminInfo->phone ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Last Updated</td>
                                                <td><?php echo $adminInfo->lastUpdate ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Current Password</td>
                                                <td><input type="password" class="form-control" name="oldPassword" value="" placeholder="Current Password"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>New Password</td>
                                                <td><input type="password" class="form-control" name="newPassword" value="" placeholder="New Password"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Confirm Password</td>
                                                <td><input type="password" class="form-control" name="confirmPassword" value="" placeholder="New Password"/>
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                    <button type="submit" class="btn btn-primary btn-block">Change Password</button>
                                    <!-- <a href="#" class="btn btn-primary">Team Sales Performance</a> -->
                                </form></div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <!--<a data-original-title="Broadcast Message" data-toggle="tooltip" type="button" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-envelope"></i></a>-->

                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>