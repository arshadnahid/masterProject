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


                    <table class="table table-bordered">

                        <thead>

                        <tr>

                            <td align="center"><strong>Account Group [ Root - Parent - Child ]</strong></td>

                            <td align="center"><strong>Range of Code</strong></td>

                            <td align="center"><strong>Child Account</strong></td>

                            <td align="center"><strong>Head Account</strong></td>

                            <td align="center"><strong>Total</strong></td>

                        </tr>

                        </thead>

                        <tbody>

                        <!-- Assets -->

                        <tr class="item-row">

                            <td colspan="5" style="color:#d68080!important;"><strong>A. Assets</strong></td>

                        </tr>

                        <!-- chart_class -->

                        <?php

                        $total_control = '';

                        $total_subsidiary = '';

                        $onea = 1;

                        $query_cca = $this->Finane_Model->getChartListbyId(1);

                        foreach ($query_cca as $row_cca):

                            ?>

                            <tr class="item-row">

                                <td style="padding-left: 20px!important;" colspan="5"><strong>A.<?php

                                        if ($row_cca['chart_id']): echo $onea;

                                        endif;

                                        ?>. <?php echo $row_cca['title']; ?></strong></td>

                            </tr>

                            <!-- chart_type -->

                            <?php

                            $con_ac = '';

                            $sub_ac = '';

                            $twoa = 1;

                            $query_cta = $this->Finane_Model->getChartListbyId($row_cca['chart_id']);

                            foreach ($query_cta as $row_cta):

                                ?>

                                <tr class="item-row">

                                    <td style="padding-left: 40px;"><a style="color:green!important;font-weight: bold;"
                                                                       title="Show Chart of View" href="#"
                                                                       onclick="showChartHead('<?php echo $row_cta['chart_id']; ?>')"
                                                                       data-toggle="modal"
                                                                       data-target="#myModal">A.<?php

                                            if ($row_cca['chart_id']): echo $onea;

                                            endif;

                                            ?>.<?php

                                            if ($row_cta['chart_id']): echo $twoa;

                                            endif;

                                            ?>. <?php echo $row_cta['title']; ?></a>

                                    </td>


                                    <td align="center">[<?php echo $row_cta['accountCode']; ?>] <i
                                                class="fa fa-arrows-h"></i>
                                        [<?php echo $this->Finane_Model->getMaxidByCoaID($row_cta['chart_id']); ?>]
                                    </td>

                                    <td align="center"><?php

                                        if ($row_cta['accountCode']):

                                            $con_ac = 1;

                                            echo $con_ac;

                                        else:

                                            $con_ac = 0;

                                            echo $con_ac;

                                        endif;

                                        $total_control += $con_ac;

                                        ?></td>

                                    <td align="center"><a title="Show Chart of View" href="#"
                                                          onclick="showChartHead('<?php echo $row_cta['chart_id']; ?>')"
                                                          data-toggle="modal" data-target="#myModal"><?php

                                            $sub_ac = $this->Finane_Model->getTotalheadAccount($row_cta['chart_id']);

                                            echo $sub_ac;

                                            $total_subsidiary += $sub_ac;

                                            ?></a>

                                    </td>

                                    <td align="center"><?php echo $con_ac + $sub_ac; ?></td>

                                </tr>

                                <?php

                                $con_ac1 = '';

                                $sub_ac1 = '';

                                $three = 1;

                                $query_ctt = $this->Finane_Model->getChartListbyId($row_cta['chart_id']);

                                if (!empty($query_ctt)):

                                    foreach ($query_ctt as $row_ctt):

                                        ?>

                                        <tr class="item-row">

                                            <td style="padding-left: 60px;"><a title="Show Chart of View" href="#"
                                                                               onclick="showChartHead('<?php echo $row_ctt['chart_id']; ?>')"
                                                                               data-toggle="modal"
                                                                               data-target="#myModal">A.<?php

                                                    if ($row_ctt['chart_id']): echo $onea;

                                                    endif;

                                                    ?>.<?php

                                                    if ($row_ctt['chart_id']): echo $twoa;

                                                    endif;

                                                    ?>.

                                                    <?php

                                                    if ($row_ctt['chart_id']): echo $three;

                                                    endif;

                                                    ?>.


                                                    <?php echo $row_ctt['title']; ?></a>

                                            </td>

                                            <td align="center">[<?php echo $row_cta['accountCode']; ?>] <i
                                                        class="fa fa-arrows-h"></i>
                                                [<?php echo $this->Finane_Model->getMaxidByCoaID($row_cta['chart_id']); ?>
                                                ]
                                            </td>

                                            <td align="center"><?php

                                                if ($row_cta['accountCode']):

                                                    $con_ac = 1;

                                                    echo $con_ac;

                                                else:

                                                    $con_ac = 0;

                                                    echo $con_ac;

                                                endif;

                                                $total_control += $con_ac;

                                                ?></td>

                                            <td align="center"><a title="Show Chart of View" href="#"
                                                                  onclick="showChartHead('<?php echo $row_cta['chart_id']; ?>')"
                                                                  data-toggle="modal" data-target="#myModal"><?php

                                                    $sub_ac = $this->Finane_Model->getTotalheadAccount($row_cta['chart_id']);

                                                    echo $sub_ac;

                                                    $total_subsidiary += $sub_ac;

                                                    ?></a>

                                            </td>

                                            <td align="center"><?php echo $con_ac + $sub_ac; ?></td>

                                        </tr>

                                        <?php

                                        $three++;

                                    endforeach;


                                endif;

                                ?>

                                <?php $twoa++; ?>

                            <?php endforeach; ?>

                            <!-- /chart_type -->

                            <?php $onea++; ?>

                        <?php endforeach; ?>

                        <!-- Liabilities & Equity-->

                        <tr class="item-row">

                            <td colspan="5" style="color:#d68080!important;"><strong>B. Capital &amp;
                                    Liabilities</strong></td>

                        </tr>

                        <!-- chart_class -->

                        <?php

                        $onea = 1;

                        $query_cca = $this->Finane_Model->getChartListbyId(2);

                        foreach ($query_cca as $row_cca):

                            ?>

                            <tr class="item-row">

                                <td colspan="5" style="padding-left: 20px!important;"><strong>B.<?php

                                        if ($row_cca['chart_id']): echo $onea;

                                        endif;

                                        ?>. <?php echo $row_cca['title']; ?></strong></td>

                            </tr>

                            <!-- chart_type -->

                            <?php

                            $con_ac = '';

                            $sub_ac = '';

                            $twoa = 1;


                            $query_cta = $this->Finane_Model->getChartListbyId($row_cca['chart_id']);

                            foreach ($query_cta as $row_cta):

                                ?>

                                <tr class="item-row">

                                    <td style="padding-left: 40px;"><a style="color:green!important;font-weight: bold;"
                                                                       href="#"
                                                                       onclick="showChartHead('<?php echo $row_cta['chart_id']; ?>')"
                                                                       data-toggle="modal"
                                                                       data-target="#myModal">B.<?php

                                            if ($row_cca['chart_id']): echo $onea;

                                            endif;

                                            ?>.<?php

                                            if ($row_cta['chart_id']): echo $twoa;

                                            endif;

                                            ?>. <?php echo $row_cta['title']; ?></a>

                                    </td>


                                    <td align="center">[<?php echo $row_cta['accountCode']; ?>] <i
                                                class="fa fa-arrows-h"></i>
                                        [<?php echo $this->Finane_Model->getMaxidByCoaID($row_cta['chart_id']); ?>]
                                    </td>


                                    <td align="center"><?php

                                        if ($row_cta['accountCode']):

                                            $con_ac = 1;

                                            echo $con_ac;

                                        else:

                                            $con_ac = 0;

                                            echo $con_ac;

                                        endif;

                                        $total_control += $con_ac;

                                        ?></td>


                                    <td align="center"><?php

                                        $sub_ac = $this->Finane_Model->getTotalheadAccount($row_cta['chart_id']);

                                        echo $sub_ac;

                                        $total_subsidiary += $sub_ac;

                                        ?></td>

                                    <td align="center"><?php echo $con_ac + $sub_ac; ?></td>

                                </tr>


                                <?php

                                $con_ac1 = '';

                                $sub_ac1 = '';

                                $three = 1;


                                $query_ctt = $this->Finane_Model->getChartListbyId($row_cta['chart_id']);

                                if (!empty($query_ctt)):

                                    foreach ($query_ctt as $row_ctt):

                                        ?>


                                        <tr class="item-row">

                                            <td style="padding-left: 60px;"><a title="Show Chart of View" href="#"
                                                                               onclick="showChartHead('<?php echo $row_ctt['chart_id']; ?>')"
                                                                               data-toggle="modal"
                                                                               data-target="#myModal">B.<?php

                                                    if ($row_ctt['chart_id']): echo $onea;

                                                    endif;

                                                    ?>.<?php

                                                    if ($row_ctt['chart_id']): echo $twoa;

                                                    endif;

                                                    ?>.

                                                    <?php

                                                    if ($row_ctt['chart_id']): echo $three;

                                                    endif;

                                                    ?>.


                                                    <?php echo $row_ctt['title']; ?></a>

                                            </td>


                                            <td align="center">[<?php echo $row_ctt['accountCode']; ?>] <i
                                                        class="fa fa-arrows-h"></i>
                                                [<?php echo $this->Finane_Model->getMaxidByCoaID($row_cta['chart_id']); ?>
                                                ]
                                            </td>

                                            <td align="center"><?php

                                                if ($row_ctt['accountCode']):

                                                    $con_ac = 1;

                                                    echo $con_ac;

                                                else:

                                                    $con_ac = 0;

                                                    echo $con_ac;

                                                endif;

                                                $total_control += $con_ac;

                                                ?></td>

                                            <td align="center"><a title="Show Chart of View" href="#"
                                                                  onclick="showChartHead('<?php echo $row_ctt['chart_id']; ?>')"
                                                                  data-toggle="modal" data-target="#myModal"><?php

                                                    $sub_ac = $this->Finane_Model->getTotalheadAccount($row_ctt['chart_id']);

                                                    echo $sub_ac;

                                                    $total_subsidiary += $sub_ac;

                                                    ?></a>

                                            </td>

                                            <td align="center"><?php echo $con_ac + $sub_ac; ?></td>

                                        </tr>


                                        <?php

                                        $three++;

                                    endforeach;


                                endif;

                                ?>


                                <?php $twoa++; ?>

                            <?php endforeach; ?>

                            <!-- /chart_type -->

                            <?php $onea++; ?>

                        <?php endforeach; ?>


                        <!-- Income-->


                        <tr class="item-row">

                            <td colspan="5" style="color:#d68080!important;"><strong>C. Income</strong></td>

                        </tr>

                        <!-- chart_class -->

                        <?php

                        $onea = 1;

                        $query_cca = $this->Finane_Model->getChartListbyId(3);

                        foreach ($query_cca as $row_cca):

                            ?>

                            <tr class="item-row">

                                <td colspan="5" style="padding-left: 20px!important;"><strong>C.<?php

                                        if ($row_cca['chart_id']): echo $onea;

                                        endif;

                                        ?>. <?php echo $row_cca['title']; ?></strong></td>

                            </tr>

                            <!-- chart_type -->

                            <?php

                            $con_ac = '';

                            $sub_ac = '';

                            $twoa = 1;


                            $query_cta = $this->Finane_Model->getChartListbyId($row_cca['chart_id']);

                            foreach ($query_cta as $row_cta):

                                ?>

                                <tr class="item-row">

                                    <td style="padding-left: 40px;"><a style="color:green!important;font-weight: bold;"
                                                                       href="#"
                                                                       onclick="showChartHead('<?php echo $row_cta['chart_id']; ?>')"
                                                                       data-toggle="modal" data-target="#myModal">
                                            C.<?php

                                            if ($row_cca['chart_id']): echo $onea;

                                            endif;

                                            ?>.<?php

                                            if ($row_cta['chart_id']): echo $twoa;

                                            endif;

                                            ?>. <?php echo $row_cta['title']; ?></a>

                                    </td>


                                    <td align="center">[<?php echo $row_cta['accountCode']; ?>] <i
                                                class="fa fa-arrows-h"></i>
                                        [<?php echo $this->Finane_Model->getMaxidByCoaID($row_cta['chart_id']); ?>]
                                    </td>


                                    <td align="center"><?php

                                        if ($row_cta['accountCode']):

                                            $con_ac = 1;

                                            echo $con_ac;

                                        else:

                                            $con_ac = 0;

                                            echo $con_ac;

                                        endif;

                                        $total_control += $con_ac;

                                        ?></td>


                                    <td align="center"><?php

                                        $sub_ac = $this->Finane_Model->getTotalheadAccount($row_cta['chart_id']);

                                        echo $sub_ac;

                                        $total_subsidiary += $sub_ac;

                                        ?></td>

                                    <td align="center"><?php echo $con_ac + $sub_ac; ?></td>

                                </tr>


                                <?php

                                $con_ac1 = '';

                                $sub_ac1 = '';

                                $three = 1;


                                $query_ctt = $this->Finane_Model->getChartListbyId($row_cta['chart_id']);

                                if (!empty($query_ctt)):

                                    foreach ($query_ctt as $row_ctt):

                                        ?>


                                        <tr class="item-row">

                                            <td style="padding-left: 60px;"><a title="Show Chart of View" href="#"
                                                                               onclick="showChartHead('<?php echo $row_ctt['chart_id']; ?>')"
                                                                               data-toggle="modal"
                                                                               data-target="#myModal">C.<?php

                                                    if ($row_ctt['chart_id']): echo $onea;

                                                    endif;

                                                    ?>.<?php

                                                    if ($row_ctt['chart_id']): echo $twoa;

                                                    endif;

                                                    ?>.

                                                    <?php

                                                    if ($row_ctt['chart_id']): echo $three;

                                                    endif;

                                                    ?>.


                                                    <?php echo $row_ctt['title']; ?></a>

                                            </td>


                                            <td align="center">[<?php echo $row_ctt['accountCode']; ?>] <i
                                                        class="fa fa-arrows-h"></i>
                                                [<?php echo $this->Finane_Model->getMaxidByCoaID($row_cta['chart_id']); ?>
                                                ]
                                            </td>

                                            <td align="center"><?php

                                                if ($row_ctt['accountCode']):

                                                    $con_ac = 1;

                                                    echo $con_ac;

                                                else:

                                                    $con_ac = 0;

                                                    echo $con_ac;

                                                endif;

                                                $total_control += $con_ac;

                                                ?></td>

                                            <td align="center"><a title="Show Chart of View" href="#"
                                                                  onclick="showChartHead('<?php echo $row_ctt['chart_id']; ?>')"
                                                                  data-toggle="modal" data-target="#myModal"><?php

                                                    $sub_ac = $this->Finane_Model->getTotalheadAccount($row_ctt['chart_id']);

                                                    echo $sub_ac;

                                                    $total_subsidiary += $sub_ac;

                                                    ?></a>

                                            </td>

                                            <td align="center"><?php echo $con_ac + $sub_ac; ?></td>

                                        </tr>


                                        <?php

                                        $three++;

                                    endforeach;


                                endif;

                                ?>


                                <?php $twoa++; ?>

                            <?php endforeach; ?>

                            <!-- /chart_type -->

                            <?php $onea++; ?>

                        <?php endforeach; ?>


                        <tr class="item-row">

                            <td colspan="5" style="color:#d68080!important;"><strong>D. Expense</strong></td>

                        </tr>

                        <!-- chart_class -->

                        <?php

                        $onea = 1;

                        $query_cca = $this->Finane_Model->getChartListbyId(4);

                        foreach ($query_cca as $row_cca):

                            ?>

                            <tr class="item-row">

                                <td colspan="5" style="padding-left: 20px!important;"><strong>D.<?php

                                        if ($row_cca['chart_id']): echo $onea;

                                        endif;

                                        ?>. <?php echo $row_cca['title']; ?></strong></td>

                            </tr>

                            <!-- chart_type -->

                            <?php

                            $con_ac = '';

                            $sub_ac = '';

                            $twoa = 1;


                            $query_cta = $this->Finane_Model->getChartListbyId($row_cca['chart_id']);

                            foreach ($query_cta as $row_cta):

                                ?>

                                <tr class="item-row">

                                    <td style="padding-left: 40px;"><a style="color:green!important;font-weight: bold;"
                                                                       href="#"
                                                                       onclick="showChartHead('<?php echo $row_cta['chart_id']; ?>')"
                                                                       data-toggle="modal"
                                                                       data-target="#myModal">D.<?php

                                            if ($row_cca['chart_id']): echo $onea;

                                            endif;

                                            ?>.<?php

                                            if ($row_cta['chart_id']): echo $twoa;

                                            endif;

                                            ?>. <?php echo $row_cta['title']; ?></a>

                                    </td>


                                    <td align="center">[<?php echo $row_cta['accountCode']; ?>] <i
                                                class="fa fa-arrows-h"></i>
                                        [<?php echo $this->Finane_Model->getMaxidByCoaID($row_cta['chart_id']); ?>]
                                    </td>


                                    <td align="center"><?php

                                        if ($row_cta['accountCode']):

                                            $con_ac = 1;

                                            echo $con_ac;

                                        else:

                                            $con_ac = 0;

                                            echo $con_ac;

                                        endif;

                                        $total_control += $con_ac;

                                        ?></td>


                                    <td align="center"><?php

                                        $sub_ac = $this->Finane_Model->getTotalheadAccount($row_cta['chart_id']);

                                        echo $sub_ac;

                                        $total_subsidiary += $sub_ac;

                                        ?></td>

                                    <td align="center"><?php echo $con_ac + $sub_ac; ?></td>

                                </tr>


                                <?php

                                $con_ac1 = '';

                                $sub_ac1 = '';

                                $three = 1;


                                $query_ctt = $this->Finane_Model->getChartListbyId($row_cta['chart_id']);

                                if (!empty($query_ctt)):

                                    foreach ($query_ctt as $row_ctt):

                                        ?>


                                        <tr class="item-row">

                                            <td style="padding-left: 60px;"><a title="Show Chart of View" href="#"
                                                                               onclick="showChartHead('<?php echo $row_ctt['chart_id']; ?>')"
                                                                               data-toggle="modal"
                                                                               data-target="#myModal">D.<?php

                                                    if ($row_ctt['chart_id']): echo $onea;

                                                    endif;

                                                    ?>.<?php

                                                    if ($row_ctt['chart_id']): echo $twoa;

                                                    endif;

                                                    ?>.

                                                    <?php

                                                    if ($row_ctt['chart_id']): echo $three;

                                                    endif;

                                                    ?>.


                                                    <?php echo $row_ctt['title']; ?></a>

                                            </td>


                                            <td align="center">[<?php echo $row_ctt['accountCode']; ?>] <i
                                                        class="fa fa-arrows-h"></i>
                                                [<?php echo $this->Finane_Model->getMaxidByCoaID($row_cta['chart_id']); ?>
                                                ]
                                            </td>

                                            <td align="center"><?php

                                                if ($row_ctt['accountCode']):

                                                    $con_ac = 1;

                                                    echo $con_ac;

                                                else:

                                                    $con_ac = 0;

                                                    echo $con_ac;

                                                endif;

                                                $total_control += $con_ac;

                                                ?></td>

                                            <td align="center"><a title="Show Chart of View" href="#"
                                                                  onclick="showChartHead('<?php echo $row_ctt['chart_id']; ?>')"
                                                                  data-toggle="modal" data-target="#myModal"><?php

                                                    $sub_ac = $this->Finane_Model->getTotalheadAccount($row_ctt['chart_id']);

                                                    echo $sub_ac;

                                                    $total_subsidiary += $sub_ac;

                                                    ?></a>

                                            </td>

                                            <td align="center"><?php echo $con_ac + $sub_ac; ?></td>

                                        </tr>


                                        <?php

                                        $three++;

                                    endforeach;


                                endif;

                                ?>


                                <?php $twoa++; ?>

                            <?php endforeach; ?>

                            <!-- /chart_type -->

                            <?php $onea++; ?>

                        <?php endforeach; ?>

                        <tr>

                            <td align="right" colspan="2"><strong>Total =</strong></td>

                            <td align="center"><strong><?php echo $total_control; ?></strong></td>

                            <td align="center"><strong><?php echo $total_subsidiary; ?></strong></td>

                            <td align="center"><strong><?php echo $total_control + $total_subsidiary; ?></strong></td>

                        </tr>

                        </tbody>

                    </table>


                </div>
            </div>
        </div>
    </div>
</div>


</div>
<script src="<?php echo base_url('assets/setup.js'); ?>"></script>









