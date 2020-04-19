



<?php
//echo '<pre>';
//print_r($getAllsalaryByDateListView);
//exit;
?>
<style>
    body {
    color: #333;
    padding: 0!important;
    margin: 0!important;
    direction: "ltr";
    font-size: 11px;
}
table td{
     text-align: right;
}
tfoot tr, thead tr {
	background: lightblue;
}
tfoot td {
	font-weight:bold;

}
tfoot input {
        width: 100%;
        padding: 3px;
        box-sizing: border-box;
    }

div.dataTables_wrapper  div.dataTables_filter {
  
  float: none;
  text-align: left;
}
.page {
    width:100%;
    min-height: 100%;
    padding: 20mm;
    margin: 10mm auto;
    border: 1px black solid;
    border-radius: 5px;
    background: white;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
}
.subpage {
    padding: 1cm;
    border: 5px black solid;
    height: 257mm;
    outline: 2cm #FFEAEA solid;
}

@page {
    size: A4;
    size: landscape;
    margin: 0;



}

@media print {
    html, body {
        margin:0 !important;
        padding:0 !important;
        height:100% !important;
        zoom: 80%;


    }


    .page .subpage .col-md-12,.col-lg-12, .col-xl-12{
        float:left;
        width:100%;

    }
    .page .subpage {
        padding: 1cm;
        border: 5px black solid;
        height: 257mm;
        width: : 210mm;
        outline: 2cm #FFEAEA solid;
        position:absolute;
    }
    .page {
        visibility: visible;


    }

}

</style>
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-sm-12 col-md-12  col-lg-12 col-xl-12 ">
        <!-- Begin: life time stats -->
        <div class="portlet light portlet-fit portlet-datatable bordered">

            <div class="portlet-body">


                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-lg-12 col-xl-12">
                                <div class="portlet green-meadow box" style="border: 1px solid #3598dc;">
                                    <div class="portlet-title" style="background-color: #3598dc;">
                                        <div class="caption">
                                            <?php echo get_phrase('Salaray Details') ?>  </div>

                                    </div>

                                </div>
                            </div>
<!--                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">

                                        </div>

                                    </div>

                                </div>
                            </div>-->

                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-lg-12 col-xl-12">

                                    <table id="example" class="table-bordered  "  >
                                        <caption><label><strong>Date:</strong><span><?php echo $getAllsalaryByDateListView[0]->date; ?></span></label>&nbsp <label><strong>Month:</strong><span><?php echo $getAllsalaryByDateListView[0]->month; ?></span>
                                            </label>&nbsp<label><strong>Year:</strong><span><?php echo $getAllsalaryByDateListView[0]->year; ?></span></label>
                                        </caption>
                                        <thead>
                                        <tr>
                                            <th width="2%"> # </th>
                                             <?php 
                                     foreach ($employeefield as $key => $value) {
                                         
                                     
                                 if($value->fieldName =='Employee Name') { ?>
                                <th> Name of Employee </th>
                                <?php }  
                                if($value->fieldName =='Designation') { ?>
                                <th> Designation </th>
                                <?php }  
                                if($value->fieldName =='Department/ Section') { ?>
                                <th> Department/ Section </th>
                                <?php }  
                                if($value->fieldName =='Payment Mode') { ?>
                                <th>Payment Mode</th>
                                <?php }  
                                if($value->fieldName =='Basic Salary') { ?>
                                <th> Basic Salary </th>
                                <?php }  
                                if($value->fieldName =='House Rant Allowance') { ?>
                                <th> House Rant Allowance </th>
                                <?php } 
                                if($value->fieldName =='Conveyance Allowance') { ?>
                                <th> Conveyance Allowance</th>
                                <?php }  
                                if($value->fieldName =='Medical Allowance') { ?>
                                <th> Medical Allowance </th>
                                <?php }  
                                if($value->fieldName =='Others') { ?>
                                <th> Others </th>
                                <?php } 
                                if($value->fieldName =='Gross Salary') { ?>
                                <th> Gross Salary </th>
                                <?php } 
                                if($value->fieldName =='Arrear Salary') { ?>
                                <th> Arrear Salary </th>
                                <?php }  
                                if($value->fieldName =='WPF Deduction') { ?>
                                <th> WPF Deduction </th>
                                <?php }  
                                if($value->fieldName =='Absent Deduction') { ?>
                                <th> Absent Deduction </th>
                                <?php }  
                                if($value->fieldName =='Loan Deduction') { ?>
                                <th> Loan Deduction </th>
                                <?php }  
                                if($value->fieldName =='AIT Deduction') { ?>
                                <th> AIT Deduction </th>
                               
                                <?php }  
                                if($value->fieldName =='Net Pay Amount') { ?>
                                <th> Net Pay Amount </th>
                                
                                 <?php }  }?>
                                            <th > Signature</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $graunatTotal = 0;
                                            foreach ($getAllsalaryByDateListView as $key => $value):
                                            $graunatTotal += $value->netPayAmount;
                                            $graunatTotalbasicSalary += $value->basicSalary;
                                            $graunatTotalhouseRant += $value->houseRant;
                                            $graunatTotalconveyanceAllowance += $value->conveyanceAllowance;
                                            $graunatTotalmedicalAllowance += $value->medicalAllowance;
                                            $graunatTotalothers += $value->others;
                                            $graunatTotalgrossSalary += $value->grossSalary;
                                            $graunatTotalarrearSalary += $value->arrearSalary;
                                            $graunatTotalwPFDeduction += $value->wPFDeduction;
                                            $graunatTotalabsentDeduction += $value->absentDeduction;
                                            $graunatTotalloanDeduction += $value->loanDeduction;
                                            $graunatTotalaITDeduction += $value->aITDeduction;
                                            $graunatTotalnetPayAmount += $value->netPayAmount;
                                             ?>
                                            <tr>
                                                <td ><?php echo $key + 1; ?></td>
                                                 <?php 
                                                   foreach ($employeefield as $key => $row) {
                                                  if($row->fieldName =='Employee Name') { ?>
                                                <td ><?php echo $value->name; ?></td>
                                                <?php }  
                                                if($row->fieldName =='Designation') { ?>
                                                <td ><?php echo $value->DesignationName; ?></td>
                                                <?php }  
                                if($row->fieldName =='Department/ Section') { ?>
                                                <td ><?php echo $value->DepartmentName; ?></td>
                                                <?php }  
                                if($row->fieldName =='Payment Mode') { ?>
                                                <td > <?php echo $value->paymentMode; ?></td>
                                                <?php }  
                                if($row->fieldName =='Basic Salary') { ?>
                                                <td align="right"><?php echo $value->basicSalary; ?></td>
                                                <?php }  
                                if($row->fieldName =='House Rant Allowance') { ?>
                                                <td align="right"><?php echo $value->houseRant; ?></td>
                                                <?php } 
                                if($row->fieldName =='Conveyance Allowance') { ?>
                                                <td align="right"><?php echo $value->conveyanceAllowance; ?></td>
                                                <?php }  
                                if($row->fieldName =='Medical Allowance') { ?>
                                                <td align="right"><?php echo $value->medicalAllowance; ?></td>
                                                <?php }  
                                if($row->fieldName =='Others') { ?>
                                                <td align="right"><?php echo $value->others; ?></td>
                                                <?php } 
                                if($row->fieldName =='Gross Salary') { ?>
                                                <td align="right"><?php echo $value->grossSalary; ?></td>
                                                <?php } 
                                if($row->fieldName =='Arrear Salary') { ?>
                                                <td align="right"><?php echo $value->arrearSalary; ?></td>
                                                 <?php }  
                                if($row->fieldName =='WPF Deduction') { ?>
                                                <td align="right"><?php echo $value->wPFDeduction; ?></td>
                                                <?php }  
                                if($row->fieldName =='Absent Deduction') { ?>
                                                <td align="right"><?php echo $value->absentDeduction; ?></td>
                                                <?php }  
                                if($row->fieldName =='Loan Deduction') { ?>
                                                <td align="right"><?php echo $value->loanDeduction; ?></td>
                                                <?php }  
                                if($row->fieldName =='AIT Deduction') { ?>
                                                <td align="right"><?php echo $value->aITDeduction; ?></td>
                                                <?php }  
                                if($row->fieldName =='Net Pay Amount') { ?>
                                                <td align="right"><?php echo $value->netPayAmount; ?></td>
                                                <?php }  }?>
                                                 <td ></td>


                                            </tr>

                                            <?php

                                        endforeach; ?>

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5" >
                                                    Total
                                                </td>
                                                <?php foreach ($employeefield as $key => $row) {  
                                if($row->fieldName =='Basic Salary') { ?>
                                                <td align="right"><?php  echo number_format($graunatTotalbasicSalary, 2);?></td>
                                                <?php }  
                                if($row->fieldName =='House Rant Allowance') { ?>
                                                <td align="right"><?php  echo number_format($graunatTotalhouseRant, 2);?></td>
                                                <?php } 
                                if($row->fieldName =='Conveyance Allowance') { ?>
                                                <td align="right"><?php  echo number_format($graunatTotalconveyanceAllowance, 2);?></td>
                                                <?php }  
                                if($row->fieldName =='Medical Allowance') { ?>
                                                <td align="right"><?php  echo number_format($graunatTotalmedicalAllowance, 2);?></td>
                                                <?php }  
                                if($row->fieldName =='Others') { ?>
                                                <td align="right"><?php  echo number_format($graunatTotalothers, 2);?></td>
                                                <?php }  
                                                if($row->fieldName =='Gross Salary') { ?>
                                                <td align="right"><?php  echo number_format($graunatTotalgrossSalary, 2);?></td>
                                                <?php } 
                                if($row->fieldName =='Arrear Salary') { ?>

                                                <td align="right"><?php  echo number_format($graunatTotalarrearSalary, 2);?></td>
                                                <?php }  
                                if($row->fieldName =='WPF Deduction') { ?>
                                                <td align="right"><?php  echo number_format($graunatTotalwPFDeduction, 2);?></td>
                                                <?php }  
                                if($row->fieldName =='Absent Deduction') { ?>
                                                <td align="right"><?php  echo number_format($graunatTotalabsentDeduction, 2);?></td>
                                                <?php }  
                                if($row->fieldName =='Loan Deduction') { ?>

                                                <td align="right"><?php  echo number_format($graunatTotalloanDeduction, 2);?></td>
                                                <?php }  
                                if($row->fieldName =='AIT Deduction') { ?>

                                                <td align="right"><?php  echo number_format($graunatTotalaITDeduction, 2);?></td>
                                                <?php }  
                                if($row->fieldName =='Net Pay Amount') { ?>

                                                <td align="right"><?php  echo number_format($graunatTotalnetPayAmount, 2);?></td>
                                                <?php }  }?>
                                                <td></td>
                                            </tr>

                                        </tfoot>
                                    </table>




                                 <div class="invoice-block pull-right">
                                     <a class="btn btn-lg blue hidden-print margin-bottom-5"
                                       onclick="javascript:window.print();"> <?php echo get_phrase('Print') ?>
                                        <i class="fa fa-print"></i>
                                    </a>

                                     

<!--                                    <a class="btn btn-lg blue hidden-print margin-bottom-5"
                                       href="<?php echo site_url($this->project.'/salaryViewCashPdf/' . $getAllsalaryByDateListView[0]->date.'/'.$getAllsalaryByDateListView[0]->month.'/'.$getAllsalaryByDateListView[0]->year.'/'.Cash ); ?>"> <?php echo get_phrase('Cash Wise Print') ?>
                                        <i class="fa fa-print"></i>
                                    </a>
                                     <a class="btn btn-lg blue hidden-print margin-bottom-5"
                                       href="<?php echo site_url($this->project.'/salaryViewBankPdf/' . $getAllsalaryByDateListView[0]->date.'/'.$getAllsalaryByDateListView[0]->month.'/'.$getAllsalaryByDateListView[0]->year.'/'.Bank ); ?>"> <?php echo get_phrase('Bank Wise Print') ?>
                                        <i class="fa fa-print"></i>
                                    </a>-->

                                 </div>
                            </div>
                        </div>





            <!-- End: life time stats -->
        </div>
    </div>
</div>
</div>

<script>
// $(document).ready(function() {
// 	// DataTable initialisation
// 	$('#example').DataTable(
// 		{
// 			"paging": true,
//                      //   "ordering": false,
// 			"autoWidth": false,
// 			"footerCallback": function ( row, data, start, end, display ) {
// 				var api = this.api();
// 				nb_cols = api.columns().nodes().length;
// 				var j = 3;
// 				while(j < nb_cols){
// 					var pageTotal = api
//                 .column( j, { page: 'current'} )
//                 .data()
//                 .reduce( function (a, b) {

//                     return parseFloat(a,2) + parseFloat(b,2);
//                 }, 0 );
//           // Update footer
//           $( api.column( j ).footer() ).html(pageTotal);
// 					j++;
// 				}
// 			}
// 		}
// 	);
// });
</script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap-colorpicker.min.js"></script>

<script >
   $(document).ready(function() {
    var groupColumn = 4;
    var table = $('#example222').DataTable({
        "paging": true,
        "autoWidth": false,
        "bLengthChange": false,
        "sScrollY": "100%",
        "paging":   false,
        "ordering": false,
        "info":     false,
        "columnDefs": [
            { "visible": false, "targets": groupColumn }
        ],
        "order": [[ groupColumn, 'asc' ]],
        "displayLength": 25,
        "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;
 
            api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        '<tr class="group"><td colspan="16">'+group+'</td></tr>'
                    );
 
                    last = group;
                }
            } );
        },
        "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api();
                nb_cols = api.columns().nodes().length;
                var j = 3;
                while(j < nb_cols){
                    var pageTotal = api
                .column( j, { page: 'current'} )
                .data()
                .reduce( function (a, b) {

                    return parseFloat(a,2) + parseFloat(b,2);
                }, 0 );
          // Update footer
          $( api.column( j ).footer() ).html(pageTotal);
                    j++;
                }
            },

    } );
 
    // Order by the grouping
    $('#example tbody').on( 'click', 'tr.group', function () {
        var currentOrder = table.order()[0];
        if ( currentOrder[0] === groupColumn && currentOrder[1] === 'asc' ) {
            table.order( [ groupColumn, 'desc' ] ).draw();
        }
        else {
            table.order( [ groupColumn, 'asc' ] ).draw();
        }
    } );
} );
</script>

<script type="text/javascript">
   $(document).ready(function () {
    $("#example input").on( 'keyup', function () {
    tableInstance.search( this.value ).draw(); // try this easy code and check if works at first
} );
     


    var $tableEle = $('#example');
    var groupCol = 4;


    //Initiate DataTable
    $tableList = $tableEle.DataTable({
            "retrieve": true,
            "searching": true,
            "bFiltered": true,
            "paging":   false,
            "ordering": false,
            "filter": true,
            "info":     false,
            "orderFixed": [
            [groupCol, 'asc']
        ],
            "columnDefs": [{
            "orderable": false,
            "targets": 5
        }, {
            "visible": false,
            "targets": groupCol
        }],
            "drawCallback": function (settings) {
            var that = this;
            if (settings.bSorted || settings.bFiltered) {
                this.$('td:first-child', {
                    "filter": "applied"
                }).each(function (i) {
                    that.fnUpdate(i + 1, this.parentNode, 0, false, false);
                });
            }

            var api = this.api();
            var rows = api.rows({
                page: 'current'
            }).nodes();
            var rowsData = api.rows({
                page: 'current'
            }).data();

            var last = null;
            var subTotal = new Array();
            var grandTotal = new Array();
            var groupID = -1;

            api.column(groupCol, {
                page: 'current'
            }).data().each(function (group, i) {
                if (last !== group) {
                    groupID++;
                    $(rows).eq(i).before("<tr  class='groupTR'><td>sub Total</td><td colspan='3' class='groupTitle'>" + group + "</td></tr>");
                    last = group;
                }

                //Sub-total of each column within the same grouping
                var val = api.row(api.row($(rows).eq(i)).index()).data(); //Current order index
                $.each(val, function (colIndex, colValue) {
                    if (typeof subTotal[groupID] == 'undefined') {
                        subTotal[groupID] = new Array();
                    }
                    if (typeof subTotal[groupID][colIndex] == 'undefined') {
                        subTotal[groupID][colIndex] = 0;
                    }
                    if (typeof grandTotal[colIndex] == 'undefined') {
                        grandTotal[colIndex] = 0;
                    }

                    value = colValue ? parseFloat(colValue) : 0;
                    subTotal[groupID][colIndex] += value;
                    grandTotal[colIndex] += value;
                });
            });

            $('tbody').find('.groupTR').each(function (i, v) {
                var rowCount = $(this).nextUntil('.groupTR').length;
                var subTotalInfo = "";
                for (var a = 5; a <= 16; a++) {
                    subTotalInfo += "<td  class='groupTD'>" + subTotal[i][a].toFixed(2) +  "</td>";
                }
                $(this).append(subTotalInfo);
            });
          
        },
        "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api();
                nb_cols = api.columns().nodes().length;
                var j = 3;
                while(j < nb_cols){
                    var pageTotal = api
                .column( j, { page: 'current'} )
                .data()
                .reduce( function (a, b) {

                    return parseFloat(a,2) + parseFloat(b,2);
                }, 0 );
          // Update footer
          $( api.column( j ).footer() ).html( parseFloat(pageTotal));
                    j++;
                }
            }
    });
});
</script>
<script type="text/javascript">
    $(document).ready(function() {
    var table = $('#example2').DataTable({
        "columnDefs": [
            { "visible": false, "targets": 4 }
        ],
        "order": [[ 2, 'asc' ]],
        "displayLength": 25,
       drawCallback: function(settings) {
    var api = this.api();
    var rows = api.rows({ page: 'current' }).nodes();
    var last = null;
    var cols = api.row(0).data().length;
    var totale = new Array();
    totale['Totale'] = new Array();
    var groupid = -1;
    var subtotale = new Array();
    var columns = x11;
    var columns1 = x111 , s=0 ;
    var colNo = [0,1,2];
 
    var count= 0, svalue= [];
 
 for (var c = 0; c < columns.length; c++) {
    colNo = columns[c];
    api.column(colNo, { page: 'current' }).data().each(function(group, i) {
        if (last !== group) {
        svalue.push(count);
        groupid++;
        $(rows).eq(i).before(
            '<tr class="group group-start"><td colspan="5">  ' + group + '</td></tr>'
            );
        last = group;
        count= i;      
        }
        else
        {
        count=count+1;
        }
 
 
 
    var val = api.row(api.row($(rows).eq(i)).index()).data();    
        $.each(val, function(index2, val2) {
        if(index2 == 4)
        {
            if (typeof subtotale[groupid] == 'undefined') {
                subtotale[groupid] = new Array();
            }
            if (typeof subtotale[groupid][index2] == 'undefined') {
                subtotale[groupid][index2] = 0;
            }
            var valore = Number(val2.replace(/[^\d]/g, '')*1);
            subtotale[groupid][index2] = +parseFloat(subtotale[groupid][index2] + valore).toFixed(2);
        }
        });
    });
}
    if(svalue.length>groupid)
    {
        svalue.push(count);
        groupid++;
    }
    for (var c = 0; c < svalue.length-1;) {
        var colNo2 = columns[c];
        api.column(colNo2, { page: 'current' }).data().each(function(group, i) {
            var  subtd = '';
            subtd = "Sum for "+group+" : "+ $.fn.dataTable.render.number(',', '.', 0).display(subtotale[s][4]) ;
        if(i == svalue[c+1])
        {  
            console.log("groupgroup222", group , last);
            $(rows).eq(i).after(
            '<tr class="group group-end"><td colspan="5">'+subtd+'</td></tr>');
            s++;
            c++;
        }
        });
    }
}
    } );

} );
</script>