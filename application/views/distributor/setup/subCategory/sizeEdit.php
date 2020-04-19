<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="container">
  <h2>Size Update</h2>
  <form action="" method="post" class="form-horizontal" enctype="multipart/form-data">
    <div class="form-group">
      <label for="Size">Color:</label>
      <input type="text" class="form-control" id="Size" value="<?php echo $getSingleSize[0]->Size;?>" placeholder="Enter Color" require name="Size">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
</div>