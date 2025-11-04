@extends('layouts.master') 
@section('content')   
@php
setlocale(LC_MONETARY, 'en_IN');  
@endphp
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Fabric GRN Detail</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Fabric GRN Detail</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <div class="table-responsive">
               <table id="dt" class="table table-bordered   nowrap w-100">
                  <thead>
                     <tr style="text-align:center; white-space:nowrap;background: bisque;" id="total_head">
                        <th colspan="8"></th>
                        <th style="text-align: right;">Total : </th>
                        <th id="head_total_grn_qty">0</th>
                        <th></th>
                        <th id="head_total_value">0</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                     </tr>
                     <tr style="text-align:center; white-space:nowrap">
                        <th>Supplier Name</th>
                        <th>PO No</th>
                        <th>Sales Order No</th>
                        <th>GRN No.</th>
                        <th>GRN Date.</th>
                        <th>Invoice No.</th>
                        <th>Invoice Date.</th>
                        <th>Item Code</th>
                        <th>Item Name</th>
                        <th>GRN Qty   </th>
                        <th>Rate   </th>
                        <th>Value   </th>
                        <th>Width</th>
                        <th>Quality Name</th>
                        <th>Color</th>
                        <th>Item Description</th>
                        <th>Track Code</th>
                        <th>Rack Name</th>
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
                
                $('#head_total_grn_qty').html(total_meter.toFixed(2));
                $('#head_total_value').html(total_value.toFixed(2));
                
              },
            columns: [
                  {data: 'ac_name', name: 'ac_name'},
                  {data: 'po_code', name: 'po_code'},
                  {data: 'sales_order_no', name: 'sales_order_no'},
                  {data: 'in_code', name: 'in_code'},
                  {data: 'in_date', name: 'in_date'},
                  {data: 'invoice_no', name: 'invoice_no'},
                  {data: 'invoice_date', name: 'invoice_date'},
                  {data: 'item_code', name: 'item_code'},
                  {data: 'item_name', name: 'item_name'},
                  {data: 'meter', name: 'meter'},
                  {data: 'item_rate', name: 'item_rate'},
                  {data: 'item_value', name: 'item_value'},
                  {data: 'dimension', name: 'dimension'},
                  {data: 'quality_name', name: 'quality_name'},
                  {data: 'color_name', name: 'color_name'},
                  {data: 'item_description', name: 'item_description'},
                  {data: 'track_code', name: 'track_code'},
                  {data: 'rack_name', name: 'rack_name'}
            ]
        });
    }
    
    
    $( document ).ready(function() 
    { 
        tableData();
       
    });
</script>
@endsection