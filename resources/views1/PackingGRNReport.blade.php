@extends('layouts.master') 
@section('content')  
@php
setlocale(LC_MONETARY, 'en_IN');  
@endphp  
<!-- end page title -->


<style>
   tfoot {
        display: table-header-group;
    }
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Packing GRN Summary</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Packing GRN Summary</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <table id="dt" class="table table-responsive nowrap w-100">
               <thead>
                  <tr style="text-align:center; white-space:nowrap">
                     <th>GRN No</th>
                     <th>Date</th>
                     <th>Packing PO No</th>
                     <th>Status</th>
                     <th>Style Category</th>
                     <th>Sales Order No</th>
                     <th>Item Name</th>
                     <th>Vendor Name</th>
                     <th>Style No</th>
                     <th>Garment Color</th>
                     <th>Size</th>
                     <th>Total GRN</th>
                  </tr>
               </thead>
               <tbody>
                          
               </tbody>
               
                <tfoot style="background-color:#d7ed92; font-weight:bold;">
                  <td colspan="10"></td>
                  <td class="text-right">Total</td>
                  <td class="text-right" id="head_packing_grn_qty"></td>
                 </tfoot>
            </table>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script>

    $('tfoot').each(function () {
        $(this).insertBefore($(this).siblings('thead'));
    });
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
                    total_size_qty += parseFloat(data[i].size_qty);
                }
                
                $('#head_packing_grn_qty').html(total_size_qty);
              },
            columns: [
                  {data: 'pki_code', name: 'pki_code'},
                  {data: 'pki_date', name: 'pki_date'},
                  {data: 'vpo_code', name: 'vpo_code',class: 'text-center'},
                  {data: 'job_status_name', name: 'job_status_name',class: 'text-center'},
                  {data: 'mainstyle_name', name: 'mainstyle_name'},
                  {data: 'sales_order_no', name: 'sales_order_no'},
                  {data: 'item_name', name: 'item_name'},
                  {data: 'vendor_name', name: 'vendor_name'},
                  {data: 'fg_name', name: 'fg_name'},
                  {data: 'color_name', name: 'color_name'},
                  {data: 'size_name', name: 'size_name'},
                  {data: 'size_qty', name: 'size_qty',class: 'text-center'}
            ]
        });
    }
    
    
    $( document ).ready(function() 
    { 
        tableData();
       
    });
    
    
    

   
</script>
@endsection