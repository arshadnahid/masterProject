<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>


<div class="container">
  <h2>Model Update</h2>
  <form action="" method="post" class="form-horizontal" enctype="multipart/form-data">
    <div class="form-group">
      <label for="Model">Model:</label>
      <input type="text" class="form-control" id="Model" value="<?php echo $getSingleModel[0]->Model;?>" placeholder="Enter Model" require name="Model">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
</div>
