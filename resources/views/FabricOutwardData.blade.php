@extends('layouts.master') 
@section('content')    
@php  
    ini_set('memory_limit', '1G'); 
    setlocale(LC_MONETARY, 'en_IN');  
@endphp
<!-- end page title -->
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Fabric Outward Detail</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Fabric Outward Detail</li>
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
               <table id="dt" data-order='[[ 5, "desc" ]]' data-page-length='25'  class="table table-bordered  nowrap w-100">
                  <thead>
                     <tr style="text-align:center; white-space:nowrap;background: bisque;" id="total_head">
                        <th colspan="13"></th>
                        <th style="text-align: right;">Total : </th>
                        <th id="head_total_outward_qty">0</th>
                        <th></th>
                        <th id="head_total_value">0</th>
                     </tr>
                     <tr>
                        <th>Vendor Name</th>
                        <th>Buyer Name</th>
                        <th>Process Order No</th>
                        <th>Sales Order No</th>
                        <th>Outward No.</th>
                        <th>Outward Type</th>
                        <th>Outward Date.</th>
                        <th>Supplier PO No.</th>
                        <th>Supplier Name</th>
                        <th>Bill To</th>
                        <th>Item Code</th>
                        <th>Item Name</th>
                        <th>Quality Name</th>
                        <th>Track Code</th>
                        <th>Outward Qty</th>
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
                  {data: 'ac_short_name', name: 'ac_short_name'},
                  {data: 'vendorName', name: 'vendorName'},
                  {data: 'vpo_code', name: 'vpo_code'},
                  {data: 'sales_order_no', name: 'sales_order_no'},
                  {data: 'fout_code', name: 'fout_code'},
                  {data: 'out_type_name', name: 'out_type_name'},
                  {data: 'fout_date', name: 'fout_date'},
                  {data: 'po_code', name: 'po_code'},
                  {data: 'supplier_name', name: 'supplier_name'},
                  {data: 'bill_to', name: 'bill_to'},
                  {data: 'item_code', name: 'item_code'},
                  {data: 'item_name', name: 'item_name'},
                  {data: 'quality_name', name: 'quality_name'},
                  {data: 'track_code', name: 'track_code'},
                  {data: 'meter', name: 'meter'},
                  {data: 'item_rate', name: 'item_rate'},
                  {data: 'item_value', name: 'item_value'}
            ]
        });
    }
    
    
    $( document ).ready(function() 
    { 
        tableData();
       
    });
</script>
@endsection