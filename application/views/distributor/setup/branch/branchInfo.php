<div class="main-content">
    <div class="main-content-inner">

        <div class="page-content">


            <div class="row" id="formDiv">
                <div class="col-md-12">

                    <form id="publicForm" action="" method="post" class="form-horizontal">
                        <input type="hidden" name="company_id" value="2"/>
                        <!--<div class="form-group" >
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Company<span style="color:red!important"> *</span></label>
                            <div class="col-sm-6">
                                <select  id="companyId"  name="company_id"  class="chosen-select form-control" data-placeholder="Search Company">
                                    <option></option>
                                    <?php /*foreach ($companyList as $each_info): */ ?>
                                        <option <?php
                        /*                                        if ($updateData->company_id == $each_info->dist_id) {
                                                                    echo "selected";
                                                                }
                                                                */ ?> value="<?php /*echo $each_info->dist_id; */ ?>"><?php /*echo $each_info->companyName; */ ?></option>
                                    <?php /*endforeach; */ ?>
                                </select>
                            </div>
                        </div>-->

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Branch
                                Code </label>
                            <div class="col-sm-6">
                                <input type="text" id="form-field-1" name="branch_code" readonly value="<?php
                                if (!empty($updateData->branch_code)) {
                                    echo $updateData->branch_code;
                                } else {
                                    echo $branchCode;
                                };
                                ?>" class="form-control" placeholder="Branch Code"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Branch Name <span
                                        style="color:red!important"> *</span></label>
                            <div class="col-sm-6">
                                <input type="text" id="form-field-1" onblur="checkDuplicateBranch(this.value)"
                                       name="branch_name" value="<?php echo $updateData->branch_name; ?>"
                                       class="form-control" placeholder="Type Branch Name"/>
                                <span id="errorMsg" style="color:red;display: none;font-weight: bold;">This Branch Name Already Exits!!</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Phone </label>
                            <div class="col-sm-6">
                                <input type="text" id="form-field-1" name="phone"
                                       value="<?php echo $updateData->phone; ?>" class="form-control"
                                       placeholder="01710000000"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Address </label>
                            <div class="col-sm-6">
                                <textarea id="form-field-1" name="branch_address" class="form-control"
                                          placeholder="Address"><?php echo $updateData->branch_address; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Remark </label>
                            <div class="col-sm-6">
                                <textarea id="form-field-1" name="remarks" class="form-control"
                                          placeholder="Remark"><?php echo $updateData->remarks; ?></textarea>
                            </div>
                        </div>
                        <input type="hidden" name="activeValue" value="1">
                        <!--<div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Is
                                Active </label>
                            <div class="col-sm-6">
                                <div class="checkbox">
                                    <label>
                                        <input name="activeValue" type="checkbox" <?php
/*                                        if ($updateData->is_active == '1') {
                                            echo "checked";
                                        }
                                        */?> value="1" class="ace">
                                        <span class="lbl"> </span>
                                    </label>
                                </div>
                            </div>
                        </div>-->

                        <div class="clearfix form-actions">
                            <div class="col-md-offset-3 col-md-9">

                                <?php if (empty($updateData)): ?>

                                    <button onclick="return confirmSwat()" id="subBtn" class="btn btn-info"
                                            type="button">
                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                        Save
                                    </button>

                                <?php else: ?>
                                    <button onclick="return confirmSwat()" id="subBtnUp" class="btn btn-info"
                                            type="button">
                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                        Update
                                    </button>

                                <?php endif; ?>

                                &nbsp; &nbsp; &nbsp;
                                <a class="btn" href="<?php echo site_url('branchInfo'); ?>">
                                    <i class="ace-icon fa fa-undo bigger-110"></i>
                                    Refresh
                                </a>


                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="table-header">
                        Branch List
                    </div>
                    <div>
                        <table id="example" class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Sl</th>
                                <!-- <th>Company</th>-->
                                <th>Branch Code</th>
                                <th>Branch</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Remark</th>

                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            //  dumpVar($branchList);
                            foreach ($branchList as $key => $eachBranch):
                                ?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <!-- <td><?php /*echo $eachBranch->companyName; */
                                    ?></td>-->
                                    <td><?php echo $eachBranch->branch_code; ?></td>
                                    <td><?php echo $eachBranch->branch_name; ?></td>
                                    <td><?php echo $eachBranch->phone; ?></td>
                                    <td><?php echo $eachBranch->branch_address; ?></td>
                                    <td><?php echo $eachBranch->remarks; ?></td>

                                    <td>
                                        <a class="blue"
                                           href="<?php echo site_url($this->project . '/branchInfo/' . $eachBranch->branch_id) ?>">
                                            <i class="ace-icon fa fa-pencil bigger-130"></i>
                                        </a>
                                        <?php

                                        if ($eachBranch->branch_id != 1) {
                                            ?>
                                            <a class="red inventoryDeletePermission"
                                               href="<?php echo site_url($this->project . '/deleteBranch/' . $eachBranch->branch_id) ?>">
                                                <i class="ace-icon fa fa-trash-o bigger-130"></i>
                                            </a>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>
<style>

    .chosen-container {
        width: 100% !important;

    }


</style>


<script>


    function showHide() {
        $("#formDiv").toggle();

    }


    function checkDuplicateBranch(branch) {




        <?php if (empty($updateData)): ?>
        var url = '<?php echo site_url("lpg/BranchController/checkDuplicateBranch") ?>';
        <?php else: ?>
        var url = '<?php echo site_url("lpg/BranchController/checkDuplicateBranchUpdate") ?>';
        <?php endif; ?>


        $.ajax({
            type: 'POST',
            url: url,
            <?php if (empty($updateData)): ?>
            data: {'branch': branch},
            <?php else: ?>
            data: {'branch': branch, 'branch_id': '<?php echo $updateData->branch_id; ?>'},
            <?php endif; ?>
            success: function (data) {
                if (data == 1) {
                    $("#subBtn").attr('disabled', true);
                    $("#errorMsg").show();
                } else {
                    $("#subBtn").attr('disabled', false);
                    $("#errorMsg").hide();
                }
            }
        });
    }


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

