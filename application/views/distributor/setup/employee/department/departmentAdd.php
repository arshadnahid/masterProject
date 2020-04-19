<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>


<div class="container">
    <div class="row">
        <div class="col-lg-6">


  <h2>Department form</h2>
  <form action="" method="post" class="form-inline" enctype="multipart/form-data">
    <div class="form-group">
      <label for="department">Department Name:</label>
      <input type="text" class="form-control" id="department" placeholder="Enter Department" require name="department">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
  <br>  <br>
   <table id="example" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Department Name</th>
                                <th> Active/InActive</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($departmentList as $key => $value): ?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td><?php echo $value->DepartmentName; ?></td>
                                    <td><?php if ($value->IsActive == 1) { ?>
                                            <a href="<?php echo site_url($this->project . '/statusDepartment/' . $value->DepartmentID) ?>">
                                                <span class="label label-success">Inactive</span></a>
                                        <?php } else {
                                            ?>
                                            <a href="<?php echo site_url($this->project . '/statusDepartment2/' . $value->DepartmentID) ?>"><span
                                                        class="label label-danger" style="background:red">Active</span></a>
                                        <?php }
                                        ?></td>
                                    <td>
                                        <div class="hidden-sm hidden-xs action-buttons">

                                            <a class="btn btn-icon-only red" href="<?php echo site_url($this->project.'/departmentEdit/' . $value->DepartmentID ); ?>">
                                                <i class="ace-icon fa fa-pencil bigger-130"></i>
                                            </a>
                                            <a class="btn btn-icon-only red" href="<?php echo site_url($this->project.'/departmentDelete/' . $value->DepartmentID ); ?>">
                                                <i class="ace-icon fa fa-trash bigger-130"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
  </div></div>
</div>
<script>
$(document).ready(function() {
    $('#example').DataTable();
} );


</script>