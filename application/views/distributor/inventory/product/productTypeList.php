<div class="row">
<div class="col-md-12">
    <div class="portlet box blue">
        <div class="portlet-title" style="min-height:21px">
            <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                Product Type List </div>
        </div>
        <div class="portlet-body">       
            <div class="row">
                <table id="productDatatable" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Product Category</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>           
            </div><!-- /.col -->
        </div><!-- /.col -->
    </div><!-- /.col -->
</div><!-- /.col -->
</div><!-- /.col -->
<!-- /.row -->
<!-- /.page-content -->

<script src="<?php echo base_url('assets/setup.js'); ?>"></script>
<script>
    function deleteProduct(deleteId) {
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
                window.location.href = "deleteProduct/" + deleteId;
            } else {
                return false;
            }
        });
    }
</script>
<script>
    $(document).ready(function () {
        //datatables
        var table = $('#productDatatable').DataTable({
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "ordering": false,
            //"order": [],
            //   "order": [], //Initial no order.
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('ServerFilter_productTypeList') ?>",
                "type": "POST"
            },
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": [0], //first column / numbering column
                    "orderable": false, //set not orderable
                },
            ],
            //            "columns": [
            //                {data: 'brandName'},
            //                ]
        });
    });
    function productTypeStatusChange(product_type_id, supStatus) {

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
                var main_url = base_u + 'lpg/InvProductController/productTypeStatusChange';
                $.ajax({
                    url: main_url,
                    type: 'post',
                    data: {
                        product_type_id: product_type_id,
                        status: supStatus
                    },
                    success: function (data) {
                        setTimeout(function () {
                            window.location.reload(1);
                        }, 100);
                        if (data == 1) {

                            window.location.replace(base_u + "productType");
                        }
                    }
                });

            } else {
                return false;
            }
        });
    }
    function deleteProductType(deleteId) {
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
                window.location.href = "deleteProductType/" + deleteId;
            } else {
                return false;
            }
        });
    }

</script>
