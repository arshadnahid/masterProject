<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    <?php echo get_phrase('Cylinder Opening List') ?> </div>
            </div>
            <div class="portlet-body">

                <table id="example" class="table table-striped table-bordered table-hover">

                    <thead>

                    <tr>

                        <th><?php echo get_phrase('Si') ?></th>

                        <th><?php echo get_phrase('Date') ?></th>

                        <th><?php echo get_phrase('PV_No') ?></th>

                        <th><?php echo get_phrase('Narration') ?></th>

                        <th><?php echo get_phrase('Action') ?></th>

                    </tr>

                    </thead>

                    <tbody>

                    <?php

                    foreach ($cylinderOpening as $key => $value):

                        ?>

                        <tr>

                            <td><?php echo $key + 1; ?></td>

                            <td><?php echo date('M d, Y', strtotime($value->date)); ?></td>

                            <td><?php echo $value->voucher_no; ?></td>

                            <td><?php echo $value->narration; ?></td>

                            <td>

                                <div class="hidden-sm hidden-xs action-buttons">

                                    <a class="btn btn-icon-only blue"
                                       href="<?php echo site_url($this->project.'/cylinderOpeningView/' . $value->exchange_info_id); ?>">

                                        <i class="ace-icon fa fa-search-plus bigger-130"></i>

                                    </a>

                                </div>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url('assets/setup.js'); ?>"></script>

<script>

    $(document).ready(function () {


        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        })

    });
</script>