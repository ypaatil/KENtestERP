@extends('layouts.master') 
@section('content')   
<!-- end page title -->
@php
if($job_status_id==1) { @endphp
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Order Progress Detail Dashboard</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Order Progress Detail Dashboard</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#152d9f;" >
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">No. of Orders</p>
                  <h4 class="mb-0" style="color:#fff;">  </h4>
               </div>
               <div class="flex-shrink-0 align-self-center">
                  <div class="  avatar-sm rounded-circle bg-primary" style="background-color:#152d9f;">
                     <span class="avatar-title" style="background-color:#152d9f;">
                     <i class="bx bx-copy-alt font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#556ee6;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;" >Order Qty(Lakh)</p>
                  <h4 class="mb-0" style="color:#fff;" >  </h4>
               </div>
               <div class="flex-shrink-0 align-self-center ">
                  <div class="avatar-sm rounded-circle bg-primary  ">
                     <span class="avatar-title  " style="background-color:#556ee6;" >
                     <i class="bx bx-purchase-tag-alt font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#008116;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">Shipped Qty(Lakh)</p>
                  <h4 class="mb-0" style="color:#fff;">     </h4>
               </div>
               <div class="flex-shrink-0 align-self-center">
                  <div class="avatar-sm rounded-circle bg-primary  " >
                     <span class="avatar-title  " style="background-color:#008116;">
                     <i class="bx bx-archive-in font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#f79733;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">Open Qty(Lakh)</p>
                  <h4 class="mb-0" style="color:#fff;"> </h4>
               </div>
               <div class="flex-shrink-0 align-self-center">
                  <div class="avatar-sm rounded-circle bg-primary  " >
                     <span class="avatar-title  " style="background-color:#f79733;">
                     <i class="bx bx-archive-in font-size-24"></i>
                     </span>
                  </div>
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
            <div class="table-responsive">
               <table id="dt" class="table   table-bordered">
                  <thead>
                     <tr style="text-align:center">
                        <th rowspan="2">Work Order No</th>
                        <th rowspan="2">Status</th>
                        <th rowspan="2">Vendor Name</th>
                        <th rowspan="2">Order No</th>
                        <th rowspan="2">Main Style Category</th>
                        <th rowspan="2">Buyer PO No</th>
                        <th rowspan="2">Color</th>
                        <th rowspan="2">Order Qty</th>
                        <th colspan="2">Cut Panel Issue</th>
                        <th colspan="2">Stitching</th>
                        <th colspan="2">Rejection</th>
                        <th colspan="2">Pass Pcs</th>
                     </tr>
                     <tr>
                        <th>Total</th>
                        <th>Balance</th>
                        <th>Total</th>
                        <th>Balance</th>
                        <th>Total</th>
                        <th>Balance</th>
                        <th>Total</th>
                        <th>Balance</th>
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
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script>

    function tableData() 
    {
         var currentURL = window.location.href; 
         
      	 $('#dt').DataTable().clear().destroy();
        
          var table = $('#dt').DataTable({
            ajax: currentURL,
            dom: 'lBfrtip',
            buttons: [
                { extend: 'copyHtml5', footer: true },
                { extend: 'excelHtml5', footer: true },
                { extend: 'csvHtml5', footer: true },
                { extend: 'pdfHtml5', footer: true }
            ],
            "footerCallback": function (row, data, start, end, display) {                
                 var total_meter = 0;             
                 var total_value = 0;
                    
                for (var i = 0; i < data.length; i++) {
                    total_meter += parseFloat(data[i].meter);
                    total_value += parseFloat(data[i].item_value);
                }
                
                $('#head_total_outward_qty').html(total_meter.toFixed(2));
                $('#head_total_value').html(total_value.toFixed(2));
                
              },
            columns: [
                  {data: 'vw_code', name: 'vw_code'},
                  {data: 'job_status_name', name: 'job_status_name'},
                  {data: 'vendorName', name: 'vendorName'},
                  {data: 'sales_order_no', name: 'sales_order_no'},
                  {data: 'mainstyle_name', name: 'mainstyle_name'},
                  {data: 'po_code', name: 'po_code'},
                  {data: 'color_name', name: 'color_name'},
                  {data: 'order_qty', name: 'order_qty'},
                  {data: 'total_cut_panel_issue', name: 'total_cut_panel_issue'},
                  {data: 'bal_total_cut_panel_issue', name: 'bal_total_cut_panel_issue'},
                  {data: 'total_stitching_qty', name: 'total_stitching_qty'},
                  {data: 'bal_total_stitching_qty', name: 'bal_total_stitching_qty'},
                  {data: 'total_qcstitching_reject_qty', name: 'total_qcstitching_reject_qty'},
                  {data: 'bal_total_qcstitching_reject_qty', name: 'bal_total_qcstitching_reject_qty'},
                  {data: 'total_qcstitching_pass_qty', name: 'total_qcstitching_pass_qty'},
                  {data: 'bal_total_qcstitching_pass_qty', name: 'bal_total_qcstitching_pass_qty'},
            ]
        });
    }
    
    
    $( document ).ready(function() 
    { 
        tableData();
       
    });
</script>
@endsection