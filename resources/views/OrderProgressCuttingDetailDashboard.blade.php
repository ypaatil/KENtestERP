@extends('layouts.master') 
@section('content')   
<!-- end page title -->
<style>
    .text-right
    {
        text-align: right;
    }
    .nowrap
    {
       text-wrap-mode: nowrap;
    }
</style>
@php
if($job_status_id==1) { @endphp
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Order Progress Cutting Detail Dashboard</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Order Progress Cutting Detail Dashboard</li>
            </ol>
         </div>
      </div>
   </div>
</div>
@php 
}
@endphp     
<div class="row">
   <div class="col-12">
       <div class="card-body">
              <form action="/OrderProgressCuttingDetailDashboard" method="GET">
                  <div class="row">  
                       <div class="col-md-3">
                         <div class="mb-3">
                            <label for="vendorId" class="form-label">Vendor</label>
                            <select name="vendorId" id="vendorId" class="form-control select2">
                                <option value="">--Select--</option>
                                @foreach($vendorList as $row)
                                    <option value="{{$row->ac_code}}" {{ $row->ac_code == $vendorId ? 'selected="selected"' : '' }} >{{$row->ac_name}}</option>
                                @endforeach
                            </select>
                         </div>
                       </div> 
                       <div class="col-md-3">
                         <div class="mb-3">
                            <label for="sales_order_no" class="form-label">Sales Order No</label>
                            <select name="sales_order_no" id="sales_order_no" class="form-control select2">
                                <option value="">--Select--</option>
                                @foreach($salesOrderList as $row)
                                    <option value="{{$row->tr_code}}" {{ $row->tr_code == $sales_order_no ? 'selected="selected"' : '' }} >{{$row->tr_code}}</option>
                                @endforeach
                            </select>
                         </div>
                       </div> 
                       <div class="col-md-3">
                         <div class="mb-3">
                            <label for="job_status_id" class="form-label">Job Status Name</label>
                            <select name="job_status_id" id="job_status_id" class="form-control select2">
                                <option value="0">--Select--</option>
                                @foreach($JobStatusList as $row)
                                    <option value="{{$row->job_status_id}}" {{ $row->job_status_id == $job_status_id ? 'selected="selected"' : '' }} >{{$row->job_status_name}}</option>
                                @endforeach
                            </select>
                         </div>
                       </div> 
                       <div class="col-md-3 mt-4"> 
                            <button type="submit" class="btn btn-primary">Search</button>
                            <a href="/OrderProgressCuttingDetailDashboard" class="btn btn-warning">Clear</a>
                       </div>
                  </div>
              </form>
          </div>
   </div>
</div>
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <div class="table-responsive">
               <table id="dt" class="table table-bordered">
                  <thead>
                     <tr style="text-align:center">
                        <th rowspan="2">Sr No</th>
                        <th rowspan="2">Cutting Order No</th>
                        <th rowspan="2">Sales Order No</th>
                        <th rowspan="2">Sales Order Status</th>
                        <th rowspan="2">Buyer Name</th>
                        <th rowspan="2">Buyer Brand</th>
                        <th rowspan="2">Style Category</th>
                        <th rowspan="2">Style Name</th>
                        <th rowspan="2">Style No</th>
                        <th rowspan="2">Vendor Name</th>
                        <th rowspan="2">Line No.</th>
                        <th rowspan="2">Color</th>
                        <th rowspan="2">Order Qty</th>
                        <th colspan="2">Cutting</th>
                     </tr>
                     <tr>
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
                //  var total_meter = 0;             
                //  var total_value = 0;
                    
                // for (var i = 0; i < data.length; i++) {
                //     total_meter += parseFloat(data[i].meter);
                //     total_value += parseFloat(data[i].item_value);
                // }
                
                // $('#head_total_outward_qty').html(total_meter.toFixed(2));
                // $('#head_total_value').html(total_value.toFixed(2));
                
              },
            columns: [
                    {data: 'sr_no', name: 'sr_no', className: 'nowrap'},
                    {data: 'vpo_code', name: 'vpo_code', className: 'nowrap'},
                    {data: 'sales_order_no', name: 'sales_order_no', className: 'nowrap'},
                    {data: 'job_status_name', name: 'job_status_name', className: 'nowrap'},
                    {data: 'buyer_name', name: 'buyer_name', className: 'nowrap'},
                    {data: 'brand_name', name: 'brand_name', className: 'nowrap'},
                    {data: 'mainstyle_name', name: 'mainstyle_name', className: 'nowrap'},
                    {data: 'fg_name', name: 'fg_name', className: 'nowrap'},
                    {data: 'style_no', name: 'style_no', className: 'nowrap'},
                    {data: 'vendorName', name: 'vendorName', className: 'nowrap'},
                    {data: 'line_name', name: 'line_name', className: 'nowrap'},
                    {data: 'color_name', name: 'color_name', className: 'nowrap'},
                    {data: 'order_qty', name: 'order_qty', className: 'text-right nowrap'},
                    {data: 'total_cutting_qty', name: 'total_cutting_qty', className: 'text-right nowrap'},
                    {data: 'bal_total_cutting_qty', name: 'bal_total_cutting_qty', className: 'text-right nowrap'} 
                ]

        });
    }
    
    
    $( document ).ready(function() 
    { 
        tableData();
       
    });
</script>
@endsection