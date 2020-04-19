<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>


<div class="container">
    <div class="row">
        <div class="col-lg-6">


  <h2>Designation form</h2>
  <form action="" method="post" class="form-inline" enctype="multipart/form-data">
    <div class="form-group">
      <label for="designation">Designation Name:</label>
      <input type="text" class="form-control" id="designation" placeholder="Enter Department" require name="designation">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
  <br>  <br>
   <table id="example" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Designation Name</th>
                                <th> Active/InActive</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($designationList as $key => $value): ?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td><?php echo $value->DesignationName; ?></td>
                                    <td><?php if ($value->IsActive == 1) { ?>
                                            <a href="<?php echo site_url($this->project . '/statusdesignationDepartment/' . $value->DesignationID) ?>">
                                                <span class="label label-success">Inactive</span></a>
                                        <?php } else {
                                            ?>
                                            <a href="<?php echo site_url($this->project . '/statusdesignationDepartment2/' . $value->DesignationID) ?>"><span
                                                        class="label label-danger" style="background:red">Active</span></a>
                                        <?php }
                                        ?></td>
                                    <td>
                                        <div class="hidden-sm hidden-xs action-buttons">

                                            <a class="btn btn-icon-only red" href="<?php echo site_url($this->project.'/designationEdit/' . $value->DesignationID ); ?>">
                                                <i class="ace-icon fa fa-pencil bigger-130"></i>
                                            </a>
                                            <a class="btn btn-icon-only red" href="<?php echo site_url($this->project.'/designationDelete/' . $value->DesignationID ); ?>">
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