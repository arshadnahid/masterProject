<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 1/2/2020
 * Time: 10:04 AM
 */



?>


<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    <?php echo get_phrase($link_page_name) ?></div>

            </div>

            <div class="portlet-body">
                <form action="<?php echo site_url($this->project . ''); ?>">
               <table id="adjustmentList" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
            	<th width="10%">#</th>

                <th width="30%">Invoice NO</th>
                <th width="20%">From Date</th>
                 <th width="20%">To Date</th>
                <th width="20%">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($newProduct as $key => $value):
             ?>
            <tr>
                <td><?php echo $key + 1; ?></td>
                <td><?php echo  $value->invoice_no; ?></td>
                <td><?php echo  $value->from_date; ?></td>
                <td><?php echo  $value->to_date; ?></td>

                <td class="pull-right">
                    <button type="button" name="view" id="" class="btn btn-xs btn-primary btn_view"><a style="border-radius:100px 0 100px 0;" href="<?php echo site_url($this->project . '/invoiceView/' . $value->incentive_info_id) ?>" class="btn btn-primary pull-right"><i class="fa fa-search-plus"></i> View </a></button>
                    <button type="button" name="view" id="" class="btn btn-xs btn-success btn_edit"><a style="border-radius:100px 0 100px 0;" href="<?php echo site_url($this->project . '/editNewProduct/' . $value->incentive_info_id) ?>" class="btn btn-success pull-right"><i class="fa fa-pencil"></i>Edit </a></button>
                    <button type="button" name="delete" id="" class="btn btn-xs btn-danger delete"><span ><a href="<?php echo site_url($this->project . '/newProductDelete/' . $value->incentive_info_id) ?>" class="btn btn-danger pull-right"><i class="fa fa-remove"></i>Delete </a></span></button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
            	<th>#</th>

                <th>Invoice NO</th>
                <th>From Date</th>
                 <th>To Date</th>
                <th>Action</th>
            </tr>
        </tfoot>
    </table>

  </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

	$(document).ready(function() {
    $('#adjustmentList').DataTable();
});





</script>

</div><script src="<?php echo base_url('assets/setup.js'); ?>"></script>







