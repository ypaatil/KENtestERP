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
    
    thead
    {
        background: blue!impotant;
    }
    
    table thead th:nth-child(1) {
      position: sticky;
      left: 0;
      z-index: 2;
    }
     table tbody td:nth-child(1) {
      position: sticky;
      left: 0;
      z-index: 2;
    }
</style>
@php
if($job_status_id==1) { @endphp
<div class="row">
   <div class="col-md-12">
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
<div class="col-md-2"> </div>
  <div class="col-md-8" style="display:flex;margin-left: 17.5%;">  
       <div class="col-md-3 text-center">
          <div class="card mini-stats-wid" style="background-image: linear-gradient(red, yellow);width: 75%;" >
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
       <div class="col-md-3 text-center" style="margin-left: 1%;">
          <div class="card mini-stats-wid" style="background-image: linear-gradient(blue, pink);width: 75%;" >
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
       <div class="col-md-3 text-center" style="margin-left: 1%;">
          <div class="card mini-stats-wid" style="background-image: linear-gradient(orange, black);width: 75%;" >
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
       <div class="col-md-3 text-center" style="margin-left: 1%;">
          <div class="card mini-stats-wid" style="background-image: linear-gradient(green, pink);width: 75%;" >
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
    
<div class="col-md-2"> </div>
@php 
}
@endphp                          
<div class="row">
   <div class="col-md-2"></div>
   <div class="col-md-8">
      <div class="card">
         <div class="card-body">
            <!--<div class="col-md-12" style="position: absolute;margin-left: 22%;">-->
            <!--    <div class="row" style="margin-left: 10px;"> -->
            <!--        <div class="col-md-2 p-0"><label class="form-label">Form Date:</label><input type="date" id="fromDate" class="form-control" value="{{date('Y-m-01')}}"></div>-->
            <!--        <div class="col-md-2 ml-1"><label class="form-label">To Date: </label><input type="date" id="toDate" class="form-control" onchange="LoadCostingOHPDashboard(1);" value="{{date('Y-m-d')}}"></div>-->
            <!--        <div class="col-md-2 mt-4"><button class="btn btn-soft-primary waves-effect waves-light success-main-checkmark main_check" id="openBtn" style="width: 150px;font-size: 18px;" onclick="LoadCostingOHPDashboard(1);"><b>Open</b></button></div>-->
            <!--        <div class="col-md-2 mt-4"><button class="btn btn-soft-primary waves-effect waves-light main_check" style="width: 150px;font-size: 18px;" id="closeBtn" onclick="LoadCostingOHPDashboard(2);"><b>Close</b></button></div>-->
            <!--    </div> -->
            <!--</div>-->
            
             <div id="loader">
                  <div class="col-md-12" style="text-align:center;"><img src="/images/loading5.gif" width="300" height="200"></div>
             </div>
            <div class="table-responsive">
              <table data-page-length='10' id="costing_table" class="table table-bordered nowrap w-100">
                  <thead>
                     <tr style="text-align:center; white-space:nowrap">
                        <th style="background: #d8cfe094;"></th>
                        <th style="background: #d8cfe094;"></th>
                        <th style="background: #d8cfe094;"></th>
                        <th style="background: #d8cfe094;"></th>
                        <th style="background: #d8cfe094;"></th>
                        <th id="head_order_qty" style="background: #d8cfe094;">0.00</th>
                        <th id="head_order_value" style="background: #d8cfe094;">0.00</th> 
                        <th id="head_cmohp_value" style="background: #d8cfe094;">0.00</th>
                        <th id="head_fob" style="background: #d8cfe094;">0.00</th> 
                     </tr>
                  </thead>
                  <thead>
                     <tr style="text-align:center; white-space:nowrap">
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>Order Qty</th>
                        <th>Order Value</th> 
                        <th>CMOHP</th>
                        <th>CMOHP/FOB</th> 
                     </tr>
                     <tr style="text-align:center; white-space:nowrap">
                        <th>Order No</th>
                        <th>Order Date</th>
                        <th>Brand</th>
                        <th>Buyer Name</th>
                        <th>Category</th>
                        <th>L PCS</th>
                        <th>Rs. Cr.</th> 
                        <th>₹/min</th>
                        <th>%</th> 
                     </tr>
                  </thead>
                  <tbody></tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-2"></div>
   <!-- end col -->
</div> 
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  
    $('title').html("Dashboard Costing Summary");

    $('.main_check').click(function()
    {
         $('.main_check').removeClass('success-main-checkmark');
         $(this).addClass('success-main-checkmark'); 
    });
     
    function getIndianFormat(str) 
    { 
          str = str.split(".");
          return str[0].replace(/(\d)(?=(\d\d)+\d$)/g, "$1,") + (str[1] ? ("."+str[1]): "");
    }
    
    function autorization(row)
    {
        var sales_order_no = $(row).attr('sales_order_no');
        var who = $(row).attr('who');
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
    $( document ).ready(function() 
    {
        LoadCostingOHPDashboard(1);
    });
     
    function LoadCostingOHPDashboard(row)
    { 
        var fromDate = $("#fromDate").val();
        var toDate = $("#toDate").val();
        $("#loader").removeClass('hide');
        var main_url = window.location.href
        var para1 = main_url.split('/')[4];
        var para2 = main_url.split('/')[5];
        $.ajax({
            dataType: "json",
            type: "GET",  
            data : { 'job_status_id' : row, 'fromDate' : fromDate, 'toDate' : toDate,'para1' : para1,'para2':para2},
            url: "{{ route('LoadCostingOHPDashboard1') }}",
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
                $("#headTotalOrderQty").html((data.totalOrderQty/100000).toFixed(2));
                $("#headTotalLakhMin").html(getIndianFormat((data.totalLakhMin).toFixed(2)));
                $("#headTotalOrderValue").html(getIndianFormat((data.totalOrderValue/10000000).toFixed(2)));
                $(this).addClass('success-checkmark');
                
                $("#head_order_qty").html(getIndianFormat((data.totalOrderQty/100000).toFixed(2)));
                $("#head_order_value").html(getIndianFormat((data.totalOrderValue/10000000).toFixed(2)));
                // $("#head_lakh_min").html(getIndianFormat((data.totalLakhMin).toFixed(2)));
                $("#head_cpm_value").html(getIndianFormat((data.totalcpmValue).toFixed(2)));
                $("#head_ohp_value").html(getIndianFormat((data.totalohpValue).toFixed(2)));
                $("#head_cmohp_value").html(getIndianFormat((data.totalcmohpValue).toFixed(2))); 
                $("#head_fob").html(getIndianFormat((data.head_fob).toFixed(2))+'%');
                
                $("#fromDate").val(data.fromDate);
                $("#toDate").val(data.toDate);
                $('.main_check').removeClass('success-main-checkmark');
                if(data.job_status_id == 1)
                {
                    $("#openBtn").addClass('success-main-checkmark');
                }
                else
                {
                    $("#closeBtn").addClass('success-main-checkmark');
                } 
                
                if(para1 == 'y' || para1 == 'm')
                {
                    $('.main_check').addClass('success-main-checkmark');
                }
            },
            complete: function() 
            {
                $("#loader").addClass('hide');
                
                var table = $('#costing_table').DataTable({
                    ordering: true,
                    "paging": true,
                    "lengthChange": true,
                    "pageLength": 10,
                    bDestroy: true,
                    dom: 'lBfrtip',
                    buttons: [
                        { extend: 'copyHtml5', footer: true },
                        { extend: 'excelHtml5', footer: true },
                        { extend: 'csvHtml5', footer: true },
                        { extend: 'pdfHtml5', footer: true }
                    ] 
                });
            },
        });
    }
</script>
@endsection