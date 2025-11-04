@extends('layouts.master') 
@section('content')   
@php
setlocale(LC_MONETARY, 'en_IN');  
@endphp
<!-- end page title -->
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Cut Panel GRN Summary</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Cut Panel GRN Summary</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <table data-order='[[ 0, "desc" ]]' id="dt" class="table table-bordered nowrap w-100 table-responsive">
               <thead>
                   <tr style="text-align:center; white-space:nowrap;background: bisque;" id="total_head">
                        <th colspan="14"></th>
                        <th style="text-align: right;">Total : </th>
                        <th id="head_total_grn_qty">0</th>
                  </tr>
                  <tr style="text-align:center; white-space:nowrap">
                     <th>SrNo</th>
                     <th>GRN No</th>
                     <th>Date</th>
                     <th>Cutting PO No</th>
                     <th>PO Status</th>
                     <th>Vendor Name</th>
                     <th>Line No</th>
                     <th>Style Category</th>
                     <th>Sales Order No</th>
                     <th>Buyer Name</th>
                     <th>Buyer Brand</th>
                     <th>Item Name</th>
                     <th>Style No</th>
                     <th>Garment Color</th>
                     <th>Size</th>
                     <th>Total GRN</th>
                  </tr>
               </thead>
               <tbody></tbody>
            </table>
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
                    
                for (var i = 0; i < data.length; i++) {
                    total_size_qty += parseFloat(data[i].size_qty);                }
                
                $('#head_total_grn_qty').html(total_size_qty.toFixed(2));
                
              },
            columns: [
                  {data: 'cpg_code1', name: 'cpg_code1'},
                  {data: 'cpg_code', name: 'cpg_code'},
                  {data: 'cpg_date', name: 'cpg_date'},
                  {data: 'vpo_code', name: 'vpo_code'},
                  {data: 'job_status_name', name: 'job_status_name'},
                  {data: 'Ac_name', name: 'Ac_name'},
                  {data: 'line_name', name: 'line_name'},
                  {data: 'mainstyle_name', name: 'mainstyle_name'},
                  {data: 'sales_order_no', name: 'sales_order_no'},
                  {data: 'buyer_name', name: 'buyer_name'},
                  {data: 'brand_name', name: 'brand_name'},
                  {data: 'item_name', name: 'item_name'},
                  {data: 'Style_No', name: 'Style_No'},
                  {data: 'color_name', name: 'color_name'},
                  {data: 'size_name', name: 'size_name'},
                  {data: 'size_qty', name: 'size_qty'},
            ]
        });
    }
    
    
    $( document ).ready(function() 
    { 
        tableData();
       
    });
    
</script>
@endsection