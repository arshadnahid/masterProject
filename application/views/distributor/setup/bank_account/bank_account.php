<?php
/**
 * Created by PhpStorm.
 * User: Nahid
 * Date: 11/24/2019
 * Time: 9:00 PM
 */
?>
<div class="main-content">
    <div class="main-content-inner">

        <div class="page-content">


            <div class="row" id="formDiv" >
                <div class="col-md-12">

                    <form id="publicForm" action=""  method="post" class="form-horizontal">





                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="bank_name"> Bank Name <span style="color:red!important"> *</span></label>
                            <div class="col-sm-6">
                                <input type="text" id="bank_name" onblur="checkDuplicateBranch()" name="bank_name"  value="<?php echo $updateData->bank_name; ?>" class="form-control" placeholder="Type Bank Name" />
                                <span id="errorMsg" style="color:red;display: none;font-weight: bold;">This Bank Name Already Exits!!</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="account_no"> Account No </label>
                            <div class="col-sm-6">
                                <input type="text" id="account_no" name="account_no" onblur="checkDuplicateBranch()"  value="<?php echo $updateData->account_no; ?>" class="form-control" placeholder="Bank Account No" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="address"> Address </label>
                            <div class="col-sm-6">
                                <textarea  id="address" name="address"  class="form-control" placeholder="Address" ><?php echo $updateData->address; ?></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="is_active"> Is Active </label>
                            <div class="col-sm-6">
                                <div class="checkbox">
                                    <label>
                                        <input name="is_active"  type="checkbox" <?php
                                        if ($updateData->is_active == 'Y') {
                                            echo "checked";
                                        }
                                        ?> value="Y" class="ace">
                                        <span class="lbl"> </span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="clearfix form-actions" >
                            <div class="col-md-offset-3 col-md-9">

                                <?php if (empty($updateData)): ?>

                                    <button onclick="return confirmSwat()"   id="subBtn" class="btn btn-info" type="button">
                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                        Save
                                    </button>

                                <?php else: ?>
                                    <button onclick="return confirmSwat()"   id="subBtnUp" class="btn btn-info" type="button">
                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                        Update
                                    </button>

                                <?php endif; ?>

                                &nbsp; &nbsp; &nbsp;




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
                                <th>Bank Name</th>
                                <th>Account No</th>
                                <th>Address</th>
                                <th>Status</th>
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
                                    <td><?php echo $eachBranch->bank_name; ?></td>
                                    <td><?php echo $eachBranch->account_no; ?></td>
                                    <td><?php echo $eachBranch->address; ?></td>
                                    <td>
                                        <?php
                                         if($eachBranch->is_active=='Y'){
                                             echo "Active";
                                         }else{
                                             echo "In Active";
                                         }

                                        ?>
                                    </td>



                                    <td>
                                        <a class="blue" href="<?php echo site_url($this->project.'/bankAccountInfo/' . $eachBranch->	bank_account_info_id) ?>">
                                            <i class="ace-icon fa fa-pencil bigger-130"></i>
                                        </a>
                                        <a class="red inventoryDeletePermission" href="<?php echo site_url($this->project.'/deletebankAccountInfo/' . $eachBranch->	bank_account_info_id) ?>" >
                                            <i class="ace-icon fa fa-trash-o bigger-130"></i>
                                        </a>
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

    .chosen-container{
        width: 100% !important;

    }


</style>


<script>





    function showHide(){
        $("#formDiv").toggle();

    }


    function checkDuplicateBranch1(){};


    function checkDuplicateBranch1(){




        <?php if (empty($updateData)): ?>
        var url = '<?php echo site_url("lpg/BankBranchController/checkDuplicateBranch") ?>';
        <?php else: ?>
        var url = '<?php echo site_url("lpg/BankBranchController/checkDuplicateBranchUpdate") ?>';
        <?php endif; ?>


        $.ajax({
            type: 'POST',
            url: url,
            <?php if (empty($updateData)): ?>
            data:{ 'branch': branch},
            <?php else: ?>
            data:{ 'branch': branch,'branch_id':'<?php echo $updateData->branch_id; ?>'},
            <?php endif; ?>
            success: function (data)
            {
                if(data == 1){
                    $("#subBtn").attr('disabled',true);
                    $("#errorMsg").show();
                }else{
                    $("#subBtn").attr('disabled',false);
                    $("#errorMsg").hide();
                }
            }
        });
    }


    function deleteProduct(deleteId){

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
                }else{
                    return false;
                }
            });
    }
</script>


