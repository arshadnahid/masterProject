<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<style>

</style>

<?php
$ProductSubCategory=get_property_list_for_show_hide(6);
$Color=get_property_list_for_show_hide(7);
$Size=get_property_list_for_show_hide(8);
$ProductSubCategory_Lable=$ProductSubCategory=='dont_have_this_property'?'':$ProductSubCategory;
$Color_Lable=$Color=='dont_have_this_property'?'':$Color;
$Size_Lable=$Size=='dont_have_this_property'?'':$Size;
$label='Product '.$ProductSubCategory_Lable." ".$Color_Lable." ".$Size_Lable;

?>
<div class="container">
    <div class="page-header">
        <h1><?php echo $label?></h1>
    </div>
    <div class="row">
    	<div class="col-md-8">
            <div class="panel with-nav-tabs panel-default">
                <div class="panel-heading">
                        <ul class="nav nav-tabs">

                            <li class="active" ><a href="#modelAdd" data-toggle="tab"> Model</a></li>
                            <li  style="<?php echo $ProductSubCategory=='dont_have_this_property'?'display: none':''?>"><a href="#ProductSubCategory" data-toggle="tab"><?php echo get_phrase($ProductSubCategory_Lable); ?></a></li>
                            <li style="<?php echo $Color=='dont_have_this_property'?'display: none':''?>"><a href="#Color" data-toggle="tab"><?php echo get_phrase($Color_Lable); ?></a></li>

                            <li style="<?php echo $Size=='dont_have_this_property'?'display: none':''?>"><a href="#Size" data-toggle="tab"><?php echo get_phrase($Size_Lable); ?></a></li>


                        </ul>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade " id="ProductSubCategory">
                            <h2><?php echo get_phrase($ProductSubCategory_Lable) ." Add "; ?></h2>
                          <form action="" method="post" class="form-inline" enctype="multipart/form-data">
                            <div class="form-group">
                              <label for="Category"><?php echo get_phrase($ProductSubCategory_Lable) ; ?></label>
                              <input type="text" class="form-control" id="SubCatName" placeholder="Enter Sub Category" require name="SubCatName">
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                          </form>
                          <br>  <br>
                           <table id="example" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?php echo get_phrase($ProductSubCategory_Lable) ; ?></th>
                                <th> Active/InActive</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($getSubCatList as $key => $value): ?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td><?php echo $value->SubCatName; ?></td>
                                    <td><?php if ($value->IsActive == 1) { ?>
                                            <a href="<?php echo site_url($this->project . '/statusSubCat/' . $value->SubCatID) ?>">
                                                <span class="label label-danger">Inactive</span></a>
                                        <?php } else {
                                            ?>
                                            <a href="<?php echo site_url($this->project . '/statusSubCat2/' . $value->SubCatID) ?>"><span
                                                        class="label label-success" >Active</span></a>
                                        <?php }
                                        ?></td>
                                    <td>
                                        <div class="hidden-sm hidden-xs action-buttons">

                                            <a class="btn btn-icon-only red" href="<?php echo site_url($this->project.'/subCatEdit/' . $value->SubCatID ); ?>">
                                                <i class="ace-icon fa fa-pencil bigger-130"></i>
                                            </a>
                                            <a class="btn btn-icon-only red" href="<?php echo site_url($this->project.'/subCatDelete/' . $value->SubCatID ); ?>">
                                                <i class="ace-icon fa fa-trash bigger-130"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                        </div>
                        <div class="tab-pane fade in active" id="modelAdd">
                        <h2>Model form</h2>
                        <form action="<?php echo site_url($this->project . '/modelAdd/') ?>" method="post" class="form-inline" enctype="multipart/form-data">
                          <div class="form-group">
                            <label for="Model">Model Add :</label>
                            <input type="text" class="form-control" id="Model" placeholder="Enter Model" require name="Model">
                          </div>
                          <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                        <br>  <br>
                         <table id="example2" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Model </th>
                                <th> Active/InActive</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($getModelList as $key => $value): ?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td><?php echo $value->Model; ?></td>
                                    <td><?php if ($value->IsActive == 1) { ?>
                                            <a href="<?php echo site_url($this->project . '/statusModel/' . $value->ModelID) ?>">
                                                <span class="label label-danger">Inactive</span></a>
                                        <?php } else {
                                            ?>
                                            <a href="<?php echo site_url($this->project . '/statusModel2/' . $value->ModelID) ?>"><span
                                                        class="label label-success" >Active</span></a>
                                        <?php }
                                        ?></td>
                                    <td>
                                        <div class="hidden-sm hidden-xs action-buttons">

                                            <a class="btn btn-icon-only red" href="<?php echo site_url($this->project.'/modelEdit/' . $value->ModelID ); ?>">
                                                <i class="ace-icon fa fa-pencil bigger-130"></i>
                                            </a>
                                            <a class="btn btn-icon-only red" href="<?php echo site_url($this->project.'/modelDelete/' . $value->ModelID ); ?>">
                                                <i class="ace-icon fa fa-trash bigger-130"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table></div>
                        <div class="tab-pane fade" id="Color">
                            <h2><?php echo get_phrase($Color_Lable) ; ?></h2>
                          <form action="<?php echo site_url($this->project . '/colorAdd/') ?>" method="post" class="form-inline" enctype="multipart/form-data">
                            <div class="form-group">
                              <label for="Color"><?php echo get_phrase($Color_Lable) ; ?>:</label>
                              <input type="text" class="form-control" id="Color" placeholder="Enter Color" require name="Color">
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                          </form>
                          <br>  <br>
                           <table id="example3" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?php echo get_phrase($Color_Lable) ; ?> </th>
                                <th> Active/InActive</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($getColorList as $key => $value): ?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td><?php echo $value->Color; ?></td>
                                    <td><?php if ($value->IsActive == 1) { ?>
                                            <a href="<?php echo site_url($this->project . '/statusColor/' . $value->ColorID) ?>">
                                                <span class="label label-danger">Inactive</span></a>
                                        <?php } else {
                                            ?>
                                            <a href="<?php echo site_url($this->project . '/statusColor2/' . $value->ColorID) ?>"><span
                                                        class="label label-success" >Active</span></a>
                                        <?php }
                                        ?></td>
                                    <td>
                                        <div class="hidden-sm hidden-xs action-buttons">

                                            <a class="btn btn-icon-only red" href="<?php echo site_url($this->project.'/colorEdit/' . $value->ColorID ); ?>">
                                                <i class="ace-icon fa fa-pencil bigger-130"></i>
                                            </a>
                                            <a class="btn btn-icon-only red" href="<?php echo site_url($this->project.'/colorDelete/' . $value->ColorID ); ?>">
                                                <i class="ace-icon fa fa-trash bigger-130"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                        </div>
                        <div class="tab-pane fade" id="Size">
                            <h2><?php echo get_phrase($Size_Lable) ; ?></h2>
                          <form action="<?php echo site_url($this->project . '/sizeAdd/') ?>" method="post" class="form-inline" enctype="multipart/form-data">
                            <div class="form-group">
                              <label for="Size"><?php echo get_phrase($Size_Lable) ; ?>:</label>
                              <input type="text" class="form-control" id="Size" placeholder="Enter Size" require name="Size">
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                          </form>
                          <br>  <br>
                           <table id="example4" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?php echo get_phrase($Size_Lable) ; ?> </th>
                                <th> Active/InActive</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($getSizeList as $key => $value): ?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td><?php echo $value->Size; ?></td>
                                    <td><?php if ($value->IsActive == 1) { ?>
                                            <a href="<?php echo site_url($this->project . '/statusSize/' . $value->SizeID) ?>">
                                                <span class="label label-danger">Inactive</span></a>
                                        <?php } else {
                                            ?>
                                            <a href="<?php echo site_url($this->project . '/statusSize2/' . $value->SizeID) ?>"><span
                                                        class="label label-success" >Active</span></a>
                                        <?php }
                                        ?></td>
                                    <td>
                                        <div class="hidden-sm hidden-xs action-buttons">

                                            <a class="btn btn-icon-only red" href="<?php echo site_url($this->project.'/sizeEdit/' . $value->SizeID ); ?>">
                                                <i class="ace-icon fa fa-pencil bigger-130"></i>
                                            </a>
                                            <a class="btn btn-icon-only red" href="<?php echo site_url($this->project.'/sizeDelete/' . $value->SizeID ); ?>">
                                                <i class="ace-icon fa fa-trash bigger-130"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                        </div>

                    </div>
                </div>
            </div>
        </div>

	</div>
</div>



<script>
$(document).ready(function() {
    $('#example').DataTable();
    $('#example2').DataTable();
    $('#example3').DataTable();
    $('#example4').DataTable();
} );


</script>