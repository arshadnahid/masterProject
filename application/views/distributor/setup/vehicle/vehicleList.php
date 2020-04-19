  <div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    Vehicle List 
                </div>
                      </div>
                      <div class="portlet-body">
       
                 <div class="row">
                    <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Name</th>
                                <th>Model</th>
                                <th>Chassis Number</th>
                                <th>Number Plate</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($vehicleList as $key => $value): ?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td><?php echo $value->vehicleName; ?></td>
                                    <td><?php echo $value->vehicleModel; ?></td>
                                    <td><?php echo $value->chassisNumber; ?></td>
                                    <td><?php echo $value->numberPlate; ?></td>
                                    <td>
                                        <div class="hidden-sm hidden-xs action-buttons">
                                            <a class="btn btn-icon-only blue" href="<?php echo site_url('vehicleEdit/' . $value->id); ?>">
                                                <i class="ace-icon fa fa-pencil bigger-130"></i>
                                            </a>
                                            <a class="btn btn-icon-only red" href="<?php echo site_url('vehicleDelete/' . $value->id); ?>">
                                                <i class="ace-icon fa fa-trash bigger-130"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                
            </div><!-- /.col -->
       </div>
       </div>
       </div>
       </div>
  