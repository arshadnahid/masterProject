<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    Employee Import
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="publicForm" action="" method="post" class="form-horizontal"
                              enctype="multipart/form-data">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Employee
                                        CSV
                                        File</label>
                                    <div class="col-sm-6">
                                        <input type="file" id="form-field-1" name="proImport" value=""
                                               class="form-control"/>
                                    </div>
                                    <button id="subBtn" class="btn btn-xs btn-info" type="submit">
                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                        Save
                                    </button>
                                    <button class="btn btn-xs" type="reset">
                                        <i class="ace-icon fa fa-undo bigger-110"></i>
                                        Reset
                                    </button>
                                     <a type="button" class="btn btn-xs btn-info"
                                       href="<?php echo base_url() ?>excelfiles/employee.csv"><i
                                                class="fa fa-cloud-download"></i> &nbsp; Download CSV Format</a>

                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Employee
                                        Excel
                                        File</label>
                                    <div class="col-sm-6">
                                        <input type="file" id="form-field-1" name="employeeImportExcel" value=""
                                               class="form-control"/>
                                    </div>
                                    <button id="subBtn" class="btn btn-xs btn-info" type="submit">
                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                        Save
                                    </button>
                                    <button class="btn btn-xs" type="reset">
                                        <i class="ace-icon fa fa-undo bigger-110"></i>
                                        Reset
                                    </button>
                                     <a type="button" class="btn btn-xs btn-info"
                                       href="<?php echo base_url() ?>excelfiles/employeeImportExcel.xls"><i
                                                class="fa fa-cloud-download"></i> &nbsp; Download Excel Format</a>

                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div><!-- /.col -->
                <div class="row">

                    <table id="example" class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th><strong>#</strong></th>
                            <th><strong>Employee Name</strong></th>
                            <th><strong>Employee No</strong></th>
                            <th><strong>Father Name</strong></th>
                            <th><strong>Mother Name</strong></th>
                            <th><strong>Present Address</strong></th>
                            <th><strong>Permanent Address</strong></th>
                            <th><strong>Date Of Birth</strong></th>
                            <th><strong>National ID</strong></th>
                            <th><strong>Mobile No</strong></th>
                            <th><strong>Join Date</strong></th>
                            <th><strong>Salary</strong></th>
                            <th><strong>Education</strong></th>
                            <th><strong>Department</strong></th>
                            <th><strong>Designation</strong></th>
                            <th><strong>Action</strong></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $condition = 'NOERROR';
                        foreach ($emplyeeList as $key => $value):
                            ?>
                            <tr>
                                <td><?php echo $key + 1; ?></td>
                                <td><?php echo $value->name; ?></td>
                                <td><?php echo $value->employeeId; ?></td>
                                <td><?php echo $value->fathersName; ?></td>
                                <td><?php echo $value->mothersName; ?></td>
                                <td><?php echo $value->presentAddress; ?></td>
                                <td><?php echo $value->permanentAddress; ?></td>
                                <td><?php echo $value->dateOfBirth; ?></td>
                                <td><?php echo $value->nationalId; ?></td>
                                <td><?php echo $value->personalMobile; ?></td>
                                <td><?php echo $value->joiningDate; ?></td>
                                <td><?php echo $value->salary; ?></td>
                                <td><?php echo $value->education; ?></td>
                                <td><?php
                                    if ($value->department == 0) {
                                        $condition = 'ERROR';
                                        echo "<span style='color:red;'>Not Match</span>";
                                    } else {
                                        echo $value->deptName;
                                    }
                                    ?></td>
                                <td><?php
                                    if ($value->designation == 0) {
                                        $condition = 'ERROR';
                                        echo "<span style='color:red;'>Not Match</span>";
                                    } else {
                                        echo $value->desiName;
                                    }
                                    ?></td>


                                <td>
                                    <div class="hidden-sm hidden-xs action-buttons">
                                      <a href="#" data-toggle="modal" data-target="#myModal" class="btn btn-icon-only green"
                                           onclick="callEditModel('<?php echo $value->importid; ?>')">
                                            <i class="ace-icon fa fa-pencil bigger-130"></i>
                                        </a>
                                        <a class="btn btn-icon-only red" href="javascript:void(0)"
                                           onclick="deleteEmployee('<?php echo $value->importid ?>')">
                                            <i class="fa fa-trash-o bigger-130"></i></a>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        endforeach;
                        ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="16">
                                <?php
                                if ($condition == 'ERROR') {
                                    echo 'Please Check The red Mark Column';
                                } else if (!empty($emplyeeList)) {
                                    ?>
                                    <a
                                            href="<?php echo site_url('lpg/ImportEmployeeController/saveImportEmplloyee'); ?>"
                                            id="showSaveBtn"
                                            class="btn btn-xs btn-success"><i class="fa fa-check"></i> Save</a>
                                    <?php
                                }
                                ?>
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div>
</div>
<div class="modal" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Update Employee Info</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <span id="loadEditMode"></span>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<script>
    <?php if (count($employeeInfo) == 0): ?>
    $("#showSaveBtn").show();
    <?php endif; ?>
    function callEditModel(importid) {
        var main_url = '<?php echo site_url(); ?>' + 'lpg/ImportEmployeeController/updateEmployeeInfo';
        $.ajax({
            url: main_url,
            type: 'post',
            data: {
                importid: importid
            },
            success: function (data) {
                $("#loadEditMode").html(data);
            }
        });
    }

    function deleteEmployee(deleteId) {
        var url1 = '<?php echo site_url()?>lpg/ImportEmployeeController/deleteImportEmployee/';
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
                } else {
                    return false;
                }
            });
    }
</script>
