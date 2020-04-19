
<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    Incentive List </div>
                <div class="tools" style="padding:0px">
                    <a  data-toggle="modal" data-target="#myModal" href="#" style="color:#fff; text-decoration:none !important;">
                        <i class="ace-icon fa fa-plus"></i>
                        Add
                    </a>
                </div>
            </div>
            <div class="portlet-body">

                <div class="row">
                    <table id="customerDatatable2" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Company </th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Target Qty</th>
                                <th>Incentive</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($incentiveList as $key => $eachIncentive): ?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td><?php echo $this->Common_model->tableRow('brand', 'brandId', $eachIncentive->company)->brandName; ?></td>
                                    <td><?php echo $eachIncentive->start; ?></td>
                                    <td><?php echo $eachIncentive->end; ?></td>
                                    <td><?php echo $eachIncentive->targetQty; ?></td>
                                    <td><?php echo $eachIncentive->incentive; ?></td>
                                    <td><?php echo $eachIncentive->status; ?></td>
                                    <td>
                                        <a class="btn btn-icon-only blue" href="<?php site_url('brandEdit/' . $eachIncentive->incentive_id) ?>">
                                            <i class="ace-icon fa fa-pencil bigger-130"></i>
                                        </a>
                                        <a class="btn btn-icon-only red" href="javascript:void(0)" onclick="deleteBrand('<?php echo site_url($eachIncentive->incentive_id); ?>')">
                                            <i class="ace-icon fa fa-trash-o bigger-130"></i></a>

                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                </div><!-- /.col -->
            </div><!-- /.col -->
        </div><!-- /.col -->
    </div><!-- /.col -->
</div><!-- /.col -->


<script src="<?php echo base_url('assets/setup.js'); ?>"></script>

<div class="modal" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Add New Incentive</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <form id="publicForm" action=""  method="post" class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Company Name  <span style="color:red;"> *</span></label>
                            <div class="col-sm-8">
                                <select width="100%" id="company" name="company"  class="chosen-select form-control"  id="form-field-select-3" data-placeholder="Search Company Name">
                                    <option></option>
                                    <?php foreach ($companyList as $key => $each_info): ?>
                                        <option value="<?php echo $each_info->brandId; ?>"><?php echo $each_info->brandName; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Target Qty</label>
                            <div class="col-sm-8">
                                <input type="text" maxlength="11" id="form-field-1" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" onblur="checkDuplicatePhone(this.value)" name="targetQty" placeholder="Target Quantity" class="form-control" />

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Duration</label>
                            <div class="col-sm-8">
                                <div class="input-daterange input-group">
                                    <input type="text" class="input-sm form-control" name="start">
                                    <span class="input-group-addon">
                                        <i class="fa fa-exchange"></i>
                                    </span>
                                    <input type="text" class="input-sm form-control" name="end">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Incentive</label>
                            <div class="col-sm-8">
                                <input type="text" id="form-field-1" name="incentive" placeholder="Incentive" class="form-control" />
                            </div>
                        </div>
                        <div class="clearfix form-actions" >
                            <div class="col-md-offset-3 col-md-9">
                                <button onclick="return confirmSwat()"   id="subBtn" class="btn btn-info" type="button">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    Save
                                </button>
                                &nbsp; &nbsp; &nbsp;
                                <button data-dismiss="modal" class="btn" type="reset">
                                    <i class="ace-icon fa fa-undo bigger-110"></i>
                                    Reset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">

            </div>

        </div>
    </div>
</div>

<style>
    .chosen-container{
        width: 390px !important;
    }

</style>

<script>


    function deleteCustomer(id) {

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
                    success: function (data) {
                        if (data == 1) {
                            setTimeout(function () {
                                window.location.reload(1);
                            }, 100);
                            window.location.replace('<?php echo site_url(); ?>' + 'customerList');
                        }
                    }
                });
            } else {
                return false;
            }
        });
    }



    $(document).ready(function () {
        //datatables
        var table = $('#customerDatatable').DataTable({
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.
            "ordering": false,
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('ServerFilterController/customerList') ?>",
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

</script>
