
function chartStatusChange(chartid,chartStatus){

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
        type: 'warning'
    },
    function (isConfirm) {
        if (isConfirm) {

            var base_u = $('#baseUrl').val();
            var main_url = base_u +'FinaneController/changeChartStatus';
            $.ajax({
                url: main_url,
                type: 'post',
                data: {
                    chartid: chartid,
                    chartStatus: chartStatus
                },
                success: function(data) {
                    setTimeout(function(){
                        window.location.reload(1);
                    }, 100);
                    if(data == 1){
                        window.location.replace(base_u +"listChartOfAccount");
                    }
                }
            });

        }else{
            return false;
        }
    });
}
function supplierStatusChange(supID,supStatus){

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
        type: 'warning'
    },
    function (isConfirm) {
        if (isConfirm) {

            var base_u = $('#baseUrl').val();
            var main_url = base_u +'suplierStatusChange';

            $.ajax({
                url: main_url,
                type: 'post',
                data: {
                    supID: supID,
                    status: supStatus
                },
                success: function(data) {
                    setTimeout(function(){
                        window.location.reload(1);
                    }, 100);
                    if(data == 1){

                        window.location.replace(base_u +"supplierList");
                    }
                }
            });

        }else{
            return false;
        }
    });
}


function productStatusChange(productid,supStatus){

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
        type: 'warning'
    },
    function (isConfirm) {
        if (isConfirm) {

            var base_u = $('#baseUrl').val();
            var main_url = base_u +'productStatusChange';
            $.ajax({
                url: main_url,
                type: 'post',
                data: {
                    productid: productid,
                    status: supStatus
                },
                success: function(data) {
                    setTimeout(function(){
                        window.location.reload(1);
                    }, 100);
                    if(data == 1){

                        window.location.replace(base_u +"productList");
                    }
                }
            });

        }else{
            return false;
        }
    });
}
function deleteData(table,column,url,id,type){



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
        type: 'warning'
    },
    function (isConfirm) {
        if (isConfirm) {

            var base_u = $('#baseUrl').val();
            var main_url = base_u +'SetupController/deletedata';
            $.ajax({
                url: main_url,
                type: 'post',
                data: {
                    table: table,
                    column: column,
                    id: id,
                    type: type
                },
                success: function(data) {
                    if(data == 1){

                        setTimeout(function(){
                            window.location.reload(1);
                        }, 100);
                        window.location.replace(base_u +url);
                    }
                }
            });

        }else{
            return false;
        }
    });
}