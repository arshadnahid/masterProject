<div class="main-content">
    <div class="main-content-inner">

        <div class="page-content">
            <div class="row">
                <div class="table-header">
                    Employee Salary List
                </div>
                <div>
                    <table id="example" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Date</th>
                                <th>Month</th>
                                <th>Year</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($getAllsalaryByDateList as $key => $value): ?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td><?php echo $value->date; ?></td>
                                    <td><?php echo $value->month; ?></td>
                                    <td><?php echo $value->year; ?></td>
                                    <td>
                                        <div class="hidden-sm hidden-xs action-buttons">
                                            <a class="btn btn-icon-pencil blue" href="<?php echo site_url($this->project.'/salaryEdit/' . $value->date.'/'.$value->month.'/'.$value->year ); ?>">
                                                <i class="ace-icon fa fa-eye bigger-130"></i>
                                            </a>
                                            <a class="btn btn-icon-only green" href="<?php echo site_url($this->project.'/salaryView/' . $value->date.'/'.$value->month.'/'.$value->year ); ?>">
                                                <i class="ace-icon fa fa-window-maximize bigger-130"></i>
                                            </a>

                                            <a class="btn btn-icon-only red" href="<?php echo site_url($this->project.'/salaryDelete/' . $value->date.'/'.$value->month.'/'.$value->year ); ?>">
                                                <i class="ace-icon fa fa-trash bigger-130"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>
