@extends('layouts.master') 
@section('content')   
@php
setlocale(LC_MONETARY, 'en_IN');  
@endphp
<!-- end page title -->
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Trims GRN Detail</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Trims GRN Detail</li>
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
               <table id="dt" class="table table-bordered nowrap w-100">
                  <thead>
                      <tr style="text-align:center; white-space:nowrap;background: bisque;" id="total_head">
                        <th colspan="9"></th>
                        <th style="text-align: right;">Total : </th>
                        <th id="head_total_qty">0</th>
                        <th></th>
                        <th id="head_total_value"></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                     </tr>
                     <tr style="text-align:center;">
                        <th>Supplier Name</th>
                        <th>PO No</th>
                        <th>Sales Order No</th>
                        <th>Buyer Name</th>
                        <th>GRN No.</th>
                        <th>GRN Date.</th>
                        <th>Invoice No.</th>
                        <th>Invoice Date.</th>
                        <th>Item Code</th>
                        <th>Item Name</th>
                        <th>GRN Qty </th>
                        <th>Rate </th>
                        <th>Value </th>
                        <th>Width</th>
                        <th>Color</th>
                        <th>Item Description</th>
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
                 var total_size_qty = 0;        
                 var total_item_value = 0;
                    
                for (var i = 0; i < data.length; i++) {
                    total_size_qty += parseFloat(data[i].item_qty);
                    total_item_value += parseFloat(data[i].item_value);
                }
                
                $('#head_total_qty').html(total_size_qty);
                $('#head_total_value').html(total_item_value);
              },
            columns: [
                  {data: 'ac_name', name: 'ac_name'},
                  {data: 'po_code', name: 'po_code'},
                  {data: 'sales_order_no', name: 'sales_order_no',class: 'text-center'},
                  {data: 'buyer', name: 'buyer',class: 'text-center'},
                  {data: 'trimCode', name: 'trimCode'},
                  {data: 'trimDate', name: 'trimDate'},
                  {data: 'invoice_no', name: 'invoice_no'},
                  {data: 'invoice_date', name: 'invoice_date'},
                  {data: 'item_code', name: 'item_code'},
                  {data: 'item_name', name: 'item_name'},
                  {data: 'item_qty', name: 'item_qty'},
                  {data: 'item_rate', name: 'item_rate'},
                  {data: 'item_value', name: 'item_value'},
                  {data: 'dimension', name: 'dimension'},
                  {data: 'color_name', name: 'color_name'},
                  {data: 'item_description', name: 'item_description'},
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