@extends('layouts.master') 
@section('content')   
<!-- end page title -->
<link rel="stylesheet" href="https://themesbrand.com/skote/layouts/assets/css/bootstrap.min.css">
<style>
    .hide{
        display:none;
    } 
    
    .success-main-checkmark:after {
      content: '✔';
      position: absolute;
      left:4px; 
      top: 10px;
      width: 27px; 
      height: 26px;
      text-align: center;
      border: 1px solid #aaa;
      background: #0b0b0b;
      border-radius: 50%;
      box-shadow: inset 0 1px 3px rgba(0,0,0,.3)
    }
    
        
    .success-sub-checkmark:after {
      content: '✔';
      position: absolute;
      left:4px; 
      top: 10px;
      width: 27px; 
      height: 26px;
      text-align: center;
      border: 1px solid #aaa;
      background: #0b0b0b;
      border-radius: 50%;
      box-shadow: inset 0 1px 3px rgba(0,0,0,.3)
    }
    
    table thead th:nth-child(1){
      position: sticky;
      left: 0;
      z-index: 2;
    }
     table tbody td:nth-child(1){
      position: sticky;
      left: 0;
      z-index: 2;
    }
</style>
@php
if($job_status_id==1) { @endphp
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Costing Summary</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Costing Summary</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-md-3 text-center">
      <div class="card mini-stats-wid" style="background-image: linear-gradient(red, yellow)" >
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <h4 style="color:#fff;">No. of Orders</h4>
                  <h2 class="mb-0" style="color:#fff;" id="headTotalOrder">0</h2>
               </div> 
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-3 text-center">
      <div class="card mini-stats-wid" style="background-image: linear-gradient(blue, pink)" >
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <h4 style="color:#fff;" >Order Qty (Lakh)</h4>
                  <h2 class="mb-0" style="color:#fff;" id="headTotalOrderQty" >0</h2>
               </div> 
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-3 text-center">
      <div class="card mini-stats-wid" style="background-image: linear-gradient(orange, black)" >
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <h4 style="color:#fff;">Order Value(Cr.)</h4>
                  <h2 class="mb-0" style="color:#fff;" id="headTotalOrderValue">0</h2>
               </div> 
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-3 text-center">
      <div class="card mini-stats-wid" style="background-image: linear-gradient(green, pink)" >
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <h4 style="color:#fff;">Min(Lakh)</h4>
                  <h2 class="mb-0" style="color:#fff;" id="headTotalLakhMin">0</h2>
               </div> 
            </div>
         </div>
      </div>
   </div>
</div>
@php 
}
@endphp                          
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <div class="col-md-12" style="position: absolute;margin-left: 22%;">
                <div class="row" style="margin-left: 10px;"> 
                    <div class="col-md-2 p-0"><label class="form-label">Form Date:</label><input type="date" id="fromDate" class="form-control" value="{{date('Y-m-01')}}"></div>
                    <div class="col-md-2 ml-1"><label class="form-label">To Date: </label><input type="date" id="toDate" class="form-control" onchange="LoadCostingOHPDashboard(1);" value="{{date('Y-m-d')}}"></div>
                    <div class="col-md-2 p-0"><button class="btn btn-soft-primary waves-effect waves-light success-main-checkmark main_check" style="width: 150px;font-size: 18px;" order_type="1" onclick="LoadCostingOHPDashboard(1);"><b>FOB</b></button></div>
                    <div class="col-md-2 p-0"><button class="btn btn-soft-primary waves-effect waves-light main_check" style="width: 150px;font-size: 18px;" order_type="3" onclick="LoadCostingOHPDashboard(1);"><b>Jobwork</b></button></div>
                </div>
                <div class="row mt-2" style="margin-left: 5px;">
                    <div class="col-md-2 p-0"><button class="btn btn-soft-warning waves-effect waves-light success-sub-checkmark sub_check" onclick="LoadCostingOHPDashboard(1);" style="width: 150px;font-size: 18px;"><b>Pending</b></button></div>
                    <div class="col-md-2 p-0"><button class="btn btn-soft-success waves-effect waves-light sub_check" onclick="LoadCostingOHPDashboard(2);" style="width: 150px;font-size: 18px;"><b style="margin-left: 20px;">Approved</b></button></div>
                    <div class="col-md-2 p-0"><button class="btn btn-soft-info waves-effect waves-light sub_check" onclick="LoadCostingOHPDashboard(3);" style="width: 150px;font-size: 18px;"><b>Sample</b></button></div>
                    <div class="col-md-2 p-0"><button class="btn btn-soft-danger waves-effect waves-light sub_check" onclick="LoadCostingOHPDashboard(4);" style="width: 150px;font-size: 18px;"><b>Cancel</b></button></div>
                    <div class="col-md-2 p-0"><button class="btn btn-soft-secondary waves-effect waves-light sub_check" onclick="LoadCostingOHPDashboard(5);" style="width: 150px;font-size: 18px;"><b>Rejected</b></button></div>
                    <div class="col-md-2 p-0"><button class="btn btn-soft-primary waves-effect waves-light sub_check" style="width: 150px;font-size: 18px;" onclick="LoadCostingOHPDashboard(0);"><b>All</b></button></div> 
                </div>
            </div>
            
             <div id="loader">
                  <div class="col-md-12 mt-5" style="text-align:center;"><img src="/images/loading5.gif" width="300" height="200"></div>
             </div>
            <div class="table-responsive">
              
              <table  data-page-length='10' id="costing_table" class="table table-bordered nowrap w-100">
                  <thead>
                     <tr style="text-align:center; white-space:nowrap">
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th id="head_order_qty">0.00</th>
                        <th id="head_order_value">0.00</th>
                        <th id="head_lakh_min">0.00</th>
                        <th id="head_cmohp_value1">0.00</th>
                        <th id="head_cmohp_value">0.00</th>
                        <th id="head_fob">0.00</th>
                        <th></th>
                        <th></th>
                     </tr>
                  </thead>
                  <thead>
                     <tr style="text-align:center; white-space:nowrap">
                        <th>Order No</th>
                        <th>Order Date</th>
                        <th>Brand</th>
                        <th>Buyer Name</th>
                        <th>Category</th>
                        <th>Order Qty</th>
                        <th>Order Value</th>
                        <th>Total Min</th>
                        <th>CMOHP</th>
                        <th>CMOHP</th>
                        <th>CMOHP/FOB</th>
                        <th>Marketing Person</th>
                        <th id="ceoHead">CEO</th>
                        <th id="reasonHead" class="hide">Reason</th>
                     </tr>
                     <tr style="text-align:center; white-space:nowrap">
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>In Lakh</th>
                        <th>In Cr.</th>
                        <th>In Lakh</th>
                        <th>In Lakh</th>
                        <th>₹/min</th>
                        <th>%</th>
                        <th></th>
                        <th></th>
                     </tr>
                  </thead>
                  <tbody></tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
   <!-- end col -->
</div> 
<input type="hidden" id="Ac_code" value="{{$Ac_code}}">
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  
    $('title').html("Costing Summary");
    // $("#headTotalOrder").html($("#countKDPL").val());
    // $("#headTotalOrderQty").html(($("#totalOrderQty").val()/100000).toFixed(2));
    // $("#headTotalLakhMin").html(getIndianFormat(($("#totalLakhMin").val()/100000).toFixed(2)));
    // $("#headTotalOrderValue").html(getIndianFormat(($("#totalOrderValue").val()/100000).toFixed(2)));
    
    // $(function () 
    // {
    //     // $('#costing_table').dataTable({
    //     //     "bDestroy": true
    //     // }).fnDestroy();
        
    //   var table = $('#costing_table').DataTable({
    //         processing: true,
    //         serverSide: false,
    //         ordering: false,
    //         dom: 'lBfrtip',
    //         buttons: [
    //             { extend: 'copyHtml5', footer: true },
    //             { extend: 'excelHtml5', footer: true },
    //             { extend: 'csvHtml5', footer: true },
    //             { extend: 'pdfHtml5', footer: true }
    //         ] 
    //     });
    // });
   
    $('.main_check').click(function()
    {
         $('.main_check').removeClass('success-main-checkmark');
         $(this).addClass('success-main-checkmark');
         $('.sub_check').removeClass('success-sub-checkmark');
         $('.sub_check.btn-soft-warning').addClass('success-sub-checkmark');
    });
    
    $('.sub_check').click(function()
    {
         $('.sub_check').removeClass('success-sub-checkmark');
         $(this).addClass('success-sub-checkmark');
    });
    function getIndianFormat(str) 
    { 
          str = str.split(".");
          return str[0].replace(/(\d)(?=(\d\d)+\d$)/g, "$1,") + (str[1] ? ("."+str[1]): "");
    }

    function MarketAutorization(row)
    { 
        
        var chk_value = $(row).val();
        var sales_order_no = $(row).attr('sales_order_no');
        if(chk_value == 3 || chk_value == 4)
        { 
            Swal.fire({
              title: "Reason?",
              showDenyButton: true,
              showCancelButton: false,
              confirmButtonText: "Save",
              denyButtonText: `Cancel`, 
              input: 'text',
              icon:'warning',
            }).then((result) => {
              if (result.isConfirmed)
              { 
                var reason = result.value;
                $.ajax({
                    dataType: "json",
                    type: "GET", 
                    data : { 'sales_order_no' : sales_order_no, 'chk_value' : chk_value, 'who' : 1,'reason':reason},
                    url: "{{ route('ApprovedAutorizedPerson') }}",
                    success: function(data)
                    {
                        if(chk_value == 3)
                        {
                            Swal.fire({
                              title: "Cancel!",
                              text: "Order Transfer to Cancel Tab!",
                              icon: "warning"
                            });  
                        }
                        else
                        {
                            
                            Swal.fire({
                              title: "Rejected!",
                              text: "Order Transfer to Rejected Tab!",
                              icon: "warning"
                            });
                        }

                    }
                });  
            
              } 
              else if (result.isDenied) {
                Swal.fire("Changes are not saved", "", "info");
              }
            });
        }
        else
        { 
            
            var reason = '';
            $.ajax({
                dataType: "json",
                type: "GET", 
                data : { 'sales_order_no' : sales_order_no, 'chk_value' : chk_value, 'who' : 1,'reason':reason},
                url: "{{ route('ApprovedAutorizedPerson') }}",
                success: function(data)
                {
                    if(chk_value == 1)
                    {
                        Swal.fire({
                          title: "Approved!",
                          text: "You are approved!",
                          icon: "success"
                        });  
                    }
                    else
                    {
                        
                        Swal.fire({
                          title: "Not Approved!",
                          text: "You are not approved!",
                          icon: "error"
                        });
                    }
                    
                }
            });  
        }
    }
    
    function autorization(row)
    { 
        var who = $(row).attr('who');
        var sales_order_no = $(row).attr('sales_order_no');
        if ($(row).is(':checked')) 
        {
           var chk_value = 1;
        } 
        else 
        {
           var chk_value = 0;
        }
        $.ajax({
            dataType: "json",
            type: "GET", 
            data : { 'sales_order_no' : sales_order_no, 'chk_value' : chk_value,'who':who },
            url: "{{ route('ApprovedAutorizedPerson') }}",
            success: function(data)
            {
                if(chk_value == 1)
                {
                    Swal.fire({
                      title: "Approved!",
                      text: "You are approved!",
                      icon: "success"
                    });  
                }
                else
                {
                    
                    Swal.fire({
                      title: "Not Approved!",
                      text: "You are not approved!",
                      icon: "error"
                    });
                }
            }
        });
    }
    $(function()
    {
        LoadCostingOHPDashboard(1);
    }) ;
    function LoadCostingOHPDashboard(row)
    { 
        if(row == 3 || row == 4)
        {
            $("#ceoHead").text("Reason"); 
        }
        else
        {
             $("#ceoHead").text("CEO"); 
        }
        setTimeout(function () 
        {
            var order_type = $('.success-main-checkmark').attr('order_type');
            var fromDate = $("#fromDate").val();
            var toDate = $("#toDate").val();
            var Ac_code = $("#Ac_code").val();
            $("#loader").removeClass('hide');
            $.ajax({
                dataType: "json",
                type: "GET",  
                data : { 'filter' : row, 'order_type' : order_type, 'fromDate' : fromDate, 'toDate' : toDate, 'Ac_code' : Ac_code},
                url: "{{ route('LoadCostingOHPDashboard') }}",
                beforeSend: function() 
                { 
                    $("#loader").removeClass('hide');
                    $('#costing_table').dataTable({
                        "bDestroy": true
                    }).fnDestroy();
            
                },
                success: function(data)
                { 
                    $('tbody').html(data.html);
                    $("#headTotalOrder").html(data.countKDPL);
                    $("#headTotalOrderQty").html((data.totalOrderQty).toFixed(2));
                    $("#headTotalLakhMin").html(getIndianFormat((data.totalLakhMin).toFixed(2)));
                    $("#headTotalOrderValue").html(getIndianFormat((data.totalOrderValue).toFixed(2)));
                    $(this).addClass('success-checkmark');
                     
                    $("#head_order_qty").html(getIndianFormat((data.totalOrderQty).toFixed(2)));
                    $("#head_order_value").html(getIndianFormat((data.totalOrderValue).toFixed(2)));
                    $("#head_lakh_min").html(getIndianFormat((data.totalLakhMin).toFixed(2)));
                    $("#head_cpm_value").html(getIndianFormat((data.totalcpmValue).toFixed(2)));
                    $("#head_ohp_value").html(getIndianFormat((data.totalohpValue).toFixed(2)));
                    $("#head_cmohp_value").html(getIndianFormat((data.totalcmohpValue).toFixed(2)));
                    $("#head_cmohp_value1").html(getIndianFormat((data.totalcmohpValue1).toFixed(2)));
                    $("#head_fob").html(getIndianFormat(((data.totalcmohpValue1/data.totalOrderRate)*100).toFixed(2))+'%');
                },
                complete: function() 
                {
                    $("#loader").addClass('hide');
                    
                    const today = new Date();
                    const day = String(today.getDate()).padStart(2, '0');
                    const month = String(today.getMonth() + 1).padStart(2, '0');
                    const year = today.getFullYear();
                    const formattedDate = `${day}-${month}-${year}`;
                    const exportTitle = 'Buyer Sales Order Size Wise Report (' + formattedDate + ')';
        
                    var table = $('#costing_table').DataTable({
                        // ordering: true,
                        "paging": true,
                        "lengthChange": true,
                        "pageLength": 10,
                        bDestroy: true,
                        dom: 'lBfrtip',
                        buttons: [
                             { extend: 'copyHtml5', footer: true, title: exportTitle },
                             { extend: 'excelHtml5', footer: true, title: exportTitle },
                             { extend: 'csvHtml5', footer: true, title: exportTitle },
                             { extend: 'pdfHtml5', footer: true, title: exportTitle }
                        ] 
                    });
                },
            });
        }, 500);
    }
</script>
@endsection