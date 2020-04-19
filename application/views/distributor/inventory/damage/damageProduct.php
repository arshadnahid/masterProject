
<div class="main-content">
    <div class="main-content-inner">
        <!--<div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Inventory</a>
                </li>
                <li>Damage</li>
                <li class="active">Damage Product List</li>
            </ul>
            <ul class="breadcrumb pull-right">
                <li>
                    <a class="saleAddPermission" href="<?php /*echo site_url($this->project . '/damageProductAdd'); */?>">
                        <i class="ace-icon fa fa-plus"></i>
                        Add
                    </a>
                </li>
            </ul>
        </div>-->
        <div class="page-content">
            <div class="row">
                <div class="table-header">
                    Damage Product List
                </div>
                <div>
                    <table id="example" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Date</th>
                                <th>Product</th>
                                <th style="text-align: right">Quantity</th>
                                <th style="text-align: right">Unit Price</th>
                                <th style="text-align: right">Total Price</th>
                                <th >Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($damageProduct as $key => $value):
                                ?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td><?php echo $value->date; ?></td>
                                    <td><?php echo $value->productName; ?></td>
                                    <td align="right"><?php echo $value->quantity; ?></td>
                                    <td align="right"><?php echo $value->unit_price; ?></td>
                                    <td align="right"><?php echo $value->quantity * $value->unit_price; ?></td>
                                    <td><a class="red" href="<?php echo site_url($this->project . '/deleteDamageProduct/'.$value->damage_id);?>"><span class="ace-icon fa fa-trash-o bigger-130"></span></a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>
