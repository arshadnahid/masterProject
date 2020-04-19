<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 4/29/2019
 * Time: 12:10 PM
 */?>
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="<?php echo site_url('DistributorDashboard/2'); ?>">Sales</a>
                </li>
                <li class="active">Customer Due Collection List</li>
            </ul>
            <ul class="breadcrumb pull-right">
                <li>
                    <a class=" green saleAddPermission" href="<?php echo site_url('customer_due_collection'); ?>">
                        <i class="ace-icon fa fa-plus"></i>
                        Customer Due Collection
                    </a>
                </li>
            </ul>
        </div>
        <div class="page-content">
            <div class="row">
                <div class="table-header">
                    Customer List
                </div>
                <div>
                    <table id="customerDatatable" class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Date</th>
                            <th>Customer ID</th>
                            <th>Due Collection No</th>
                            <th>Payment Type</th>
                            <th>Amount</th>

                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>
<script src="<?php echo base_url('assets/setup.js'); ?>"></script>
<script>
    function deleteCustomer(id){

        swal({
                title: "Are you sure ?",
                text: "You won't be able to revert this!",
                showCancelButton: true,
                confirmButtonColor: '#73AE28',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: "No",
                closeOnConfirm: true,
                closeOnCancel: true,
                type: 'warning'
            },
            function (isConfirm) {
                if (isConfirm) {
                    // var base_u = $('#baseUrl').val();
                    var main_url = '<?php echo site_url(); ?>' + 'SetupController/customerDelete';
                    $.ajax({
                        url: main_url,
                        type: 'post',
                        data: {
                            id: id,
                        },
                        success: function(data) {
                            if(data == 1){
                                setTimeout(function(){
                                    window.location.reload(1);
                                }, 100);
                                window.location.replace('<?php echo site_url(); ?>'+'customerList');
                            }
                        }
                    });
                }else{
                    return false;
                }
            });
    }



    $(document).ready(function() {
        //datatables
        var table = $('#customerDatatable').DataTable({
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.
            "ordering" : false,
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('ServerFilterController/customer_due_collection_list') ?>",
                "type": "POST"
            },
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": [ 0 ], //first column / numbering column
                    "orderable": false, //set not orderable
                },
            ],
            //            "columns": [
            //                {data: 'brandName'},
            //                ]
        });
    });

</script>
