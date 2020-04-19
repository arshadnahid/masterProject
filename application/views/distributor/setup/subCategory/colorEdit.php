<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="container">
  <h2>Color Update</h2>
  <form action="" method="post" class="form-horizontal" enctype="multipart/form-data">
    <div class="form-group">
      <label for="Color">Color:</label>
      <input type="text" class="form-control" id="Color" value="<?php echo $getSingleColor[0]->Color;?>" placeholder="Enter Color" require name="Color">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
</div>