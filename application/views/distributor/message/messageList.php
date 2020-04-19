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
<!--            <span style="padding-top: 5px!important;">
                <a  style="border-radius:100px 0 100px 0;" href="<?php echo site_url('addProduct'); ?>" class="btn btn-success pull-right">
                    <i class="ace-icon fa fa-plus"></i>
                    Add New
                </a>
            </span>-->
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
                                <th>Message Title</th>
                                <th>Message Description</th>

                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            foreach ($allMessage as $key => $value):

//     dumpVar($value);
                                ?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td><?php echo $value->date; ?></td>
                                    <td><?php echo $value->msgSubject; ?></td>
                                    <td><?php echo $value->msgDescrip; ?></td>


                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div><script src="<?php echo base_url('assets/setup.js'); ?>"></script>




