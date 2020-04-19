
<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                        <?php echo get_phrase('Employee Voucher')?> </div>
                      </div>
                      <div class="portlet-body">
            <div class="row">



                    <table id="receiveDatatable" class="table table-striped table-bordered table-hover">

                        <thead>

                            <tr>

                                <th><?php echo get_phrase('Si')?></th>

                                <th><?php echo get_phrase('Voucher No')?></th>

                                <th><?php echo get_phrase('Date')?></th>

                                <th><?php echo get_phrase('Type')?></th>

                                <th><?php echo get_phrase('Branch')?></th>
                                <th><?php echo get_phrase('Voucher By')?></th>

                                <th><?php echo get_phrase('Memo')?></th>

                                <th><?php echo get_phrase('Amount')?></th>

                                <th><?php echo get_phrase('Action')?></th>

                            </tr>

                        </thead>

                        <tbody>

                        </tbody>

                    </table>



            </div><!-- /.col -->

        </div><!-- /.row -->

    </div><!-- /.page-content -->

</div>
</div>

<script>

    $(document).ready(function() {

        //datatables

        var table = $('#receiveDatatable').DataTable({

            "processing": true, //Feature control the processing indicator.

            "serverSide": true, //Feature control DataTables' server-side processing mode.

            "ordering" : false,

            //"order": [],

            //   "order": [], //Initial no order.



            // Load data for the table's content from an Ajax source

            "ajax": {

                "url": "<?php echo site_url('lpg/ServerFilterController/employeeList') ?>",

                "type": "POST"

            },

            //Set column definition initialisation properties.

            "columnDefs": [

                {

                    "targets": [ 0 ], //first column / numbering column

                    "orderable": false //set not orderable

                },

            ]

            //            "columns": [

            //                {data: 'brandName'},

            //                ]

        });

    });



</script>











