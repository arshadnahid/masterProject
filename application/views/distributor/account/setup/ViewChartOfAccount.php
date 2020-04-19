<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title" style="min-height:21px">
                <div class="caption" style="font-size: 14px;padding:1px 0 1px;">
                    View Chart of Account
                </div>
            </div>
            <div class="portlet-body">

                <div class="row">


                    <table class="table table-bordered" id="example">

                        <thead>

                        <tr>

                            <td align="center"><strong>Account Group [ Root - Parent - Child ]</strong></td>

                            <td  align="center"><strong>Range of Code</strong></td>
                            <th align="center"><?php echo  get_phrase('Action')?></th>





                        </tr>

                        </thead>
                        <tbody>
                        <?php
                        foreach ($chartList as $key => $value) {
                            $style=0;
                            $textColor="";
                            $textSize="";
                            $fontWeight="";
                            if($value['label']==0){
                                $style="10";
                                $textColor='red';
                                $fontWeight='800';
                                $textSize='22px;';
                            }elseif ($value['label']==1){
                                $style=(20 * ($value['label']));
                                $textColor='#000';
                                //$fontWeight='500';
                                $textSize='19px';
                            }elseif ($value['label']==2){
                                $style=(20 * ($value['label']));
                                $textColor='#000';
                                //$fontWeight='500';
                                $textSize='16px';
                            }elseif ($value['label']==3){
                                $style=(20 * ($value['label']));
                                $textColor='#000';
                                //$fontWeight='500';
                                $textSize='14px';
                            }else{
                                $style=(20 * ($value['label']));
                                $textColor='#000';
                                //$fontWeight='500';
                                $textSize='12px';
                            }
                            ?>

                            <tr>
                                <td align="left"
                                    style="
                                            padding-left:<?php echo $style . 'px' ?>!important;
                                            color: <?php echo  $textColor;?>;
                                            font-weight: <?php echo  $fontWeight;?>;
                                            font-size: <?php echo  $textSize?>;">
                                    <?php echo $value['parent_name'] ?>
                                </td>

                                <td class="text-left">
                                    <?php echo $value['code'] ?>
                                </td>
                                <td align="center">

                                    <div class="hidden-sm hidden-xs action-buttons">
                                        <a  class="btn btn-icon-only red"  <?php  ?> href="<?php echo site_url($this->project.'/editChartOfAccount/' . $value['id']); ?>"<?php   ?>>
                                            <i class="fa fa-pencil" style="color:#fff"></i>

                                        </a>

                                    </div>


                                </td>


                            </tr>

                        <?php } ?>
                        </tbody>


                    </table>


                </div>
            </div>
        </div>
    </div>
</div>


</div>
<script src="<?php echo base_url('assets/setup.js'); ?>"></script>

<script type="text/javascript">
    /*$(document).ready(function() {
        $('#example').DataTable( {
            dom: 'Bfrtip',
            paging: false,
            ordering: false,
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        } );
    } );*/
</script>







