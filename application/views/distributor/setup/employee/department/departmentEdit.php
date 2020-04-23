<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>


<div class="container">
  <h2>Department form</h2>
  <form action="" method="post" class="form-horizontal" enctype="multipart/form-data">
    <div class="form-group">
      <label for="department">Department Name:</label>
      <input type="text" class="form-control" id="department" value="<?php echo $getDepartmentById[0]->DepartmentName;?>" placeholder="Enter Department" require name="department">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
</div>
