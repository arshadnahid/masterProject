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
      <label for="designation">Designation Name:</label>
      <input type="text" class="form-control" id="designation" value="<?php echo $getdesignationById[0]->DesignationName;?>" placeholder="Enter Designation" require name="designation">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
</div>
