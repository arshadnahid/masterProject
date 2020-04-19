<div class="row">    <div class="col-md-12">        <div class="portlet box blue">            <div class="portlet-title" style="min-height:21px">                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">                    <?php echo  get_phrase('Chart List')?> </div>            </div>            <div class="portlet-body">                <div class="row">                    <table id="example" class="table table-striped table-bordered table-hover">                        <thead>                            <tr>                                <th><?php echo  get_phrase('Sl')?></th>                                <th><?php echo  get_phrase('Account Code')?></th>                                <th><?php echo  get_phrase('Account Name')?></th>                                <th class="hidden-480"><?php echo  get_phrase('Status')?></th>                                <th><?php echo  get_phrase('Action')?></th>                            </tr>                        </thead>                        <tbody>                            <?php                            foreach ($chartList as $key => $value):                                //$menuName = $this->Common_model->get_single_data_by_single_column('chartofaccount', 'chart_id', $value->parentId);                                ?>                                <tr>                                    <td><?php echo $key + 1; ?></td>                                    <td><?php                                            echo $value->code;                                        ?></td>                                    <td><?php echo get_phrase($value->parent_name); ?></td>                                    <td> <?php                                            if ($value->status == 1):                                                ?>                                                <?php if (!empty($value->if_insert_data)) { ?>                                                <div class="mydiv"></div>                                            <?php } else { ?>                                                <a href="javascript:void(0)" onclick="chartStatusChange('<?php echo $value->id; ?>', '2')" class="label label-danger arrowed">                                                    <i class="ace-icon fa fa-fire bigger-110"></i>                                                    <?php echo get_phrase('Inactive')?></a>                                            <?php } ?>                                            <?php else: ?>                                                <a href="javascript:void(0)" onclick="chartStatusChange('<?php echo $value->id; ?>', '1')" class="label label-success arrowed">                                                    <i class="ace-icon fa fa-check bigger-110"></i>                                                    <?php echo get_phrase('Active')?>                                                </a>                                            <?php                                            endif;                                            ?>                                    </td>                                    <td>                                        <div class="hidden-sm hidden-xs action-buttons">                                            <!--  <a class="blue" href="#">                                               <i class="ace-icon fa fa-search-plus bigger-130"></i>                                                    </a>-->                                            <a  class="btn btn-icon-only red"  <?php  ?> href="<?php echo site_url($this->project.'/editChartOfAccount/' . $value->id); ?>"<?php   ?>>                                                <i class="fa fa-pencil" style="color:#fff"></i>                                            </a>                                <!--   <a class="red" href="javascript:void(0)" onclick="deleteData('supplier','sup_id','supplierList','<?php echo $value->chart_id; ?>')">                                  <i class="ace-icon fa fa-trash-o bigger-130"></i>                                     </a>-->                                            <!--  <a onclick="return isconfirm()" class="red" href="#">                                           </a>-->                                        </div>                                    </td>                                </tr>                            <?php endforeach; ?>                        </tbody>                    </table>                </div><!-- /.col -->            </div>        </div>    </div></div></div><script src="<?php echo base_url(); ?>assets/setu1p.js"></script><script type="text/javascript">    function chartStatusChange(chartid,chartStatus){        swal({                title: "Are you sure ?",                text: "You won't be able to revert this!",                showCancelButton: true,                confirmButtonColor: '#73AE28',                cancelButtonColor: '#d33',                confirmButtonText: 'Yes',                cancelButtonText: "No",                closeOnConfirm: true,                closeOnCancel: true,                type: 'warning'            },            function (isConfirm) {                if (isConfirm) {                    var base_u = $('#baseUrl').val();                    var main_url = base_u +'lpg/FinaneController/changeChartStatus';                    $.ajax({                        url: main_url,                        type: 'post',                        data: {                            chartid: chartid,                            chartStatus: chartStatus                        },                        success: function(data) {                            setTimeout(function(){                                window.location.reload(1);                            }, 100);                            if(data == 1){                                window.location.replace(base_u +"listChartOfAccount");                            }                        }                    });                }else{                    return false;                }            });    }</script><!-- <script src="<?php echo base_url('assets/setup.js'); ?>"></script> -->