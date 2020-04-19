<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 3/25/2019
 * Time: 9:45 AM
 */
?>



<div class="main-content">
    <div class="main-content-inner">

        <div class="page-content">
            <div class="row">
                <div class="table-header">
                    <?php echo get_phrase('Package  List')?>
                </div>
                <div>
                    <table id="productDatatable" class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th><?php echo get_phrase('Sl')?></th>
                            <th><?php echo get_phrase('Package Name')?></th>

                            <th><?php echo get_phrase('Status')?></th>
                            <th><?php echo get_phrase('Action')?></th>
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
    function productPackageStatusChange(package_id,supStatus){

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

                    var base_u = $('#baseUrl').val();
                    var main_url = base_u +'ProductPackageController/productPackageStatusChange';
                    $.ajax({
                        url: main_url,
                        type: 'post',
                        data: {
                            package_id: package_id,
                            status: supStatus
                        },
                        success: function(data) {
                            setTimeout(function(){
                                window.location.reload(1);
                            }, 100);
                            if(data == 1){

                                window.location.replace(base_u +"productPackageList");
                            }
                        }
                    });

                }else{
                    return false;
                }
            });
    }
    function deleteProductpackage(deleteId){
        var url1='<?php echo site_url("lpg/ProductPackageController/product_package_delete/")?>';
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
                type: 'success'
            },
            function (isConfirm) {
                if (isConfirm) {
                    window.location.href = url1 + deleteId;
                }else{
                    return false;
                }
            });
    }

</script>
<script>
    $(document).ready(function() {
        //datatables
        var table = $('#productDatatable').DataTable({
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "ordering" : false,
            //"order": [],
            //   "order": [], //Initial no order.
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('lpg/ServerFilterController/productPackageList') ?>",
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

