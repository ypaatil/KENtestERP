@extends('layouts.master') 
@section('content')   
@php
setlocale(LC_MONETARY, 'en_IN');  
@endphp
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Fabric Stock Detail</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Fabric Stock Detail</li>
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
                  <p class="  fw-medium" style="color:#fff;">QC Final GRN Qty</p>
                  <h4 class="mb-0" style="color:#fff;" id="qc_grn_qty">0</h4>
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
                  <p class="  fw-medium" style="color:#fff;" >Pending For QC Qty</p>
                  <h4 class="mb-0" style="color:#fff;" id="pending_for_qc_qty">0</h4>
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
      <div class="card mini-stats-wid" style="background-color:#f79733;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">Total Stock Qty</p>
                  <h4 class="mb-0" style="color:#fff;" id="total_Stock_qty">0</h4>
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
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#008116;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">Value</p>
                  <h4 class="mb-0" style="color:#fff;" id="all_value">0</h4>
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
</div>
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="col-md-12">
            <div class="card-title" style="text-align: end;margin: 20px;"><button class="btn btn-warning" onclick="DumpData();"><b>Refresh</b></button></div>
         </div>
         <div class="card-body">
            <div class="table-responsive">
               <table id="tbl" class="table table-bordered   nowrap w-100">
                  <thead>
                     <tr style="text-align:center; white-space:nowrap;background: bisque;" id="total_head">
                        <th colspan="16"></th>
                        <th style="text-align: right;">Total : </th>
                        <th id="head_total_grn_qty">0</th>
                        <th id="head_total_qc_qty"></th>
                        <th id="head_total_outward_qty"></th>
                        <th id="head_total_stock_qty">0</th>
                        <th></th>
                        <th id="head_total_value">0</th>
                     </tr>
                     <tr style="text-align:center; white-space:nowrap">
                        <th>Supplier Name</th>
                        <th>Buyer Name</th>
                        <th>PO Status</th>
                        <th>PO No</th>
                        <th>GRN No</th>
                        <th>Invoice No</th>
                        <th>Item Code</th>
                        <th>Preview</th>
                        <th>Shade No.</th>
                        <th>Item Name</th>
                        <th>Width</th>
                        <th>Quality Name</th>
                        <th>Color</th>
                        <th>Item Description</th>
                        <th>Status</th>
                        <th>Track Code</th>
                        <th>Rack Name</th>
                        <th>GRN Qty</th>
                        <th>QC Qty</th>
                        <th>Outward Qty</th>
                        <th>Stock Qty</th>
                        <th>Rate</th>
                        <th>Value</th>
                     </tr>
                  </thead>
                  <tbody>
                   
                  </tbody>
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

    function DumpData()
    {
        $.ajax({
            dataType: "json",
            url: "{{ route('FabricStocks') }}",
            success: function(data)
            {
                location.reload();
            }
        });
    }
    function table1()
    {
        $.ajax({
            dataType: "json",
            url: "{{ route('loadDumpFabricStockData') }}",
            success: function(data)
            {
               // $('#head_total_value').html(data.overall);
                $('tbody').html(data.html1); 
                
                $('#head_total_grn_qty').html(data.total_grn_qty.toFixed(2));
                $('#head_total_qc_qty').html(data.totalQc_qty.toFixed(2));
                $('#head_total_outward_qty').html(data.totalOutward_qty.toFixed(2));
                $('#head_total_stock_qty').html(data.totalStockQty.toFixed(2));
                $('#head_total_value').html(data.totalvalue.toFixed(2));
                    
                $("#qc_grn_qty").html(data.total_grn_qty.toFixed(2));
                $("#pending_for_qc_qty").html(data.totalQc_qty.toFixed(2));
                $("#total_outward_qty").html(data.totalOutward_qty.toFixed(2));
                $("#total_Stock_qty").html(data.totalStockQty.toFixed(2));
                $("#all_value").html(data.totalvalue.toFixed(2));
    
                    var table = $('#tbl').DataTable({
                	"dom": "Bfrtip", 
                	"serverSide": false,
                	"bDestroy":true,
                	"bPaginate": false,
                	"extend": "excelHtml5",
                     "exportOptions": {
                         "modifier" : {
                            "order" : "index", 
                            "page" : "all", 
                            "search" : "applied"
                         }
                     },
                	"extend": "pdfHtml5",
                     "exportOptions": {
                         "modifier" : {
                            "order" : "index", 
                            "page" : "all", 
                            "search" : "applied"
                         }
                     },
                	"extend": "csvHtml5",
                     "exportOptions": {
                         "modifier" : {
                            "order" : "index", 
                            "page" : "all", 
                            "search" : "applied"
                         }
                     },
                	"extend": "copyHtml5",
                     "exportOptions": {
                         "modifier" : {
                            "order" : "index", 
                            "page" : "all", 
                            "search" : "applied"
                         }
                     }
                });
                
            }
        });
    }
    
  $(function () 
  {
  	 table1();
  });
</script>
@endsection