
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Distributor</a>
                </li>
                <li class="active">Password Change</li>
            </ul>

            
            <ul class="breadcrumb pull-right">
                <li>

                    <a  href="<?php echo site_url('distributor_profile'); ?>">
                        <i class="ace-icon fa fa-list "></i>
                        List
                    </a>
                </li>
                
            </ul>
            
            
        </div>
        <div class="page-content">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class=" col-md-8 col-md-offset-2" align="center">

                        <?php if (empty($adminInfo->image)): ?>
                            <img alt="User Pic" src="<?php echo base_url('assets/images/default.png'); ?>" class="img-circle img-responsive">
                        <?php else: ?>
                            <img alt="User Pic" src="<?php echo base_url('uploads/thumb/' . $adminInfo->image); ?>" class="img-circle img-responsive">
                        <?php endif; ?><hr>
                    </div>
                    <form class="form-horizontal" role="form" action="<?php echo base_url() ?>change_password" method="post" enctype="multipart/form-data">
                        <table class="table table-user-information">
                            <tbody>
                                <tr>
                                    <td>Name:</td>
                                    <td><?php echo $dist_info->name ?></td>
                                </tr>

                                <tr>
                                    <td>Email</td>
                                    <td><?php echo $dist_info->email ?></td>
                                </tr>
                                <tr>
                                    <td>Phone Number</td>
                                    <td><?php echo $dist_info->phone ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td> Address</td>
                                    <td><?php echo $dist_info->address ?></td>
                                </tr>
                                <tr>
                                    <td>Created Date:</td>
                                    <td><?php echo $dist_info->created_at ?></td>
                                </tr>

                                <tr>
                                    <td>Last Updated</td>
                                    <td><?php echo $adminInfo->lastUpdate ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Current Password <span style="color:red;"> *</span></td>
                                    <td><input type="password" class="form-control" name="currentPassword" value="" placeholder="Current Password" required/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>New Password <span style="color:red;"> *</span></td>
                                    <td><input type="password" class="form-control" name="admin_password1" value="" placeholder="New Password" required/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Confirm Password <span style="color:red;"> *</span></td>
                                    <td><input type="password" class="form-control" name="admin_password2" value="" placeholder="New Confirm Password" required/>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-key"></i> &nbsp;Change Password</button>
                    </form>
                </div>
            </div><!-- /.col -->
        </div>
    </div>
</div>






















