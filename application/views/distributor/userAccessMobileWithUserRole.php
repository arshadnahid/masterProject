<?php
/**
 * Created by PhpStorm.
 * User: Nahid
 * Date: 3/29/2020
 * Time: 9:54 AM
 */
?>
<div class="main-content">

    <div class="main-content-inner">


        <div class="page-content">

            <div class="row">

                <form id="publicForm" action="<?php echo site_url($this->project . '/insert_menu_accessListByUserRole'); ?>"
                      method="POST" class="form-horizontal" role="form">

                    <div class="col-xs-12">

                        <!-- PAGE CONTENT BEGINS -->

                        <div class="form-group">

                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">User List</label>

                            <div class="col-sm-6">

                                <select id="user_id" name="user_id" onchange="get_menu_list()"
                                        class="chosen-select form-control" id="form-field-select-3"
                                        data-placeholder="Select User">

                                    <option selected disabled></option>

                                    <?php
                                    // come from site_helper
                                    echo user_role_dropdown(null, null);
                                    ?>

                                </select>

                            </div>

                            <div class="col-sm-3">

                                <button onclick="return confirmSwat()" id="diabledBtn" disabled class="btn btn-info"
                                        type="button">

                                    <i class="ace-icon fa fa-check bigger-110"></i>

                                    Submit

                                </button>

                            </div>

                        </div>


                    </div>

                    <div class="col-xs-12">

                        <div id="new_data"></div>

                    </div>

                </form>

            </div><!-- /.col -->

        </div><!-- /.row -->

    </div><!-- /.page-content -->

</div>


<script>


    function get_menu_list() {
        $("#diabledBtn").attr('disabled', false);
        var url = '<?php echo site_url("lpg/HomeController/get_menu_list") ?>';
        var user_id = $('#user_id').val();
        $.ajax({
            type: 'POST',
            url: url,
            data:
                {
                    'user_id': user_id
                },
            success: function (data) {
                $("#new_data").html(data);
            }
        });
    }


</script>



