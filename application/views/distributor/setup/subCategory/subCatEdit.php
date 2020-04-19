<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>


<div class="container">
  <h2>Sub Category Update</h2>
  <form action="" method="post" class="form-horizontal" enctype="multipart/form-data">
    <div class="form-group">
      <label for="SubCatName">Sub Category Name:</label>
      <input type="text" class="form-control" id="SubCatName" value="<?php echo $getSingleSubCat[0]->SubCatName;?>" placeholder="Enter SubCatName" require name="SubCatName">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
</div>
