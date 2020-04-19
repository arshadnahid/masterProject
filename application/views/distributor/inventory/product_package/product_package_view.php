<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 3/25/2019
 * Time: 3:23 PM
 */
?>
<?php /**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 3/25/2019
 * Time: 9:44 AM
 */ ?>




<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="<?php echo site_url('DistributorDashboard/3'); ?>">Inventory</a>
                </li>
                <li class="active">Add Product Package</li>
            </ul>
            <ul class="breadcrumb pull-right">
                <li>
                    <a href="<?php echo site_url('productPackageList'); ?>">
                        <i class="ace-icon fa fa-list"></i>
                        List
                    </a>
                </li>
            </ul>
        </div>
        <br>
        <div class="page-content">

            <div class="row">

                <div class="col-md-12">

                    <form id="publicForm" action=""  method="post" class="form-horizontal">



                        <div class="form-group">

                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Package Code <span> &nbsp;:&nbsp; </span></label>
                            
                            <div class="col-sm-6">

                                <input type="text" id="form-field-1" name="package_code" readonly value="<?php echo $package_details[0]->package_code; ?>" class="form-control" placeholder="Product Code" style="border:none;background: #FFF !important" />
                            </div>
                        </div>
                        <div class="form-group">

                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Package Name<span> &nbsp;:&nbsp; </span> </label>
                            <div class="col-sm-6">
                                <input type="hidden" name="package_id" id="package_id" value="<?php echo $package_details[0]->package_id ?>">
                                <input type="text" id="package_name" name="package_name"   class="form-control" placeholder="Package Name" value="<?php echo $package_details[0]->package_name ?>" style="border:none;background: #FFF !important"/>
                            </div>
                        </div>


                        




                        <div class="form-group">
                            <div class="col-sm-3" style="text-align: right"><label class="" for="form-field-1"> Package Product<span> &nbsp;:&nbsp; </span> </label></div>
                            <div class="col-sm-6">

                                <table border="1"  id="package_details"style="width:100%;background: white">
                                    <thead>
                                        <tr style="background:#F9FBFC">

                                            <th id="thd" style="height:25px;text-align: center;font-weight: bold;">
                                                Product Name
                                            </th>
                                            <th id="thd" style="height:25px;text-align: center;font-weight: bold;">
                                                Product Type
                                            </th>
                                           



                                        </tr>
                                    </thead>
                                    <tbody id="package_table">
                                        <?php
                                        foreach ($package_details as $key => $value):
                                            ?>

                                            <tr class='trClass'>


                                                <td>
                                                    <input type='hidden' name='product_id[]' id='productID_<?php echo $value->product_id ?>' value='<?php echo $value->product_id ?>'/>
                                                    <input type='hidden' name='package_products_id_<?php echo $value->package_products_id ?>' id='productID_<?php echo $value->product_id ?>' value='<?php echo $value->package_products_id ?>'/>
                                                    <?php echo $value->productName . ' [ ' . $value->brandName . ' ]' ?>
                                                </td>
                                                <td>
                                                    <?php echo $value->title ?>
                                                </td>
                                                


                                            </tr>






                                            <?php
                                        endforeach;
                                        ?>




                                    </tbody>
                                </table>


                            </div>
                            <div class="col-sm-3"></div>





                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="description">Description<span> &nbsp;:&nbsp; </span></label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <textarea name="description" class="form-control"id="description" rows="4" cols="129" style="border:none;background: #FFF !important" readonly=""></textarea>
                                </div>

                            </div>
                        </div>





                        
                    </form>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>













