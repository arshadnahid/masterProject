<div class="main-content">
    <div class="main-content-inner">

        <div class="page-content">
            <div class="row">
                <div class="table-header">
                    Employee List
                </div>

                <div>
                    <table id="example" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Name</th>
                                <th>Emp ID</th>
                                <th>National ID</th>
                                <th>Salary</th>
                                <th>Personal Mobile</th>
                                <th>Department</th>
                                <th>Designation</th>
                                <th>Present Address</th>
                                <th>Permanent Address</th>
                                <th>Active/Inactive</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($getAllEmployeewiseD as $key => $value): ?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td><?php echo $value->name; ?></td>
                                    <td><?php echo $value->employeeId; ?></td>
                                    <td><?php echo $value->nationalId; ?></td>
                                    <td><?php echo $value->salary; ?></td>
                                    <td><?php echo $value->personalMobile; ?></td>
                                    <td><?php echo $value->DepartmentName; ?></td>
                                    <td><?php echo $value->DesignationName; ?></td>
                                    <td><?php echo $value->presentAddress; ?></td>
                                    <td><?php echo $value->permanentAddress; ?></td>
                                    <td><?php echo $value->empStatus; ?></td>
                                    <td>
                                        <div class="hidden-sm hidden-xs action-buttons">
                                            <a class="btn btn-icon-only blue" href="<?php echo site_url($this->project.'/employeeEdit/' . $value->id); ?>">
                                                <i class="ace-icon fa fa-pencil bigger-130"></i>
                                            </a>
                                            <a class="btn btn-icon-only red" href="<?php echo site_url($this->project.'/employeeDelete/' . $value->id); ?>">
                                                <i class="ace-icon fa fa-trash bigger-130"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>
<script>
$(document).ready(function() {
    $('#example').DataTable();
} );


</script>