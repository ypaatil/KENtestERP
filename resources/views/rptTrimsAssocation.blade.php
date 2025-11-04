@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp                
<!-- end page title -->
 
<style>
   .text-right
   {
        text-align:right;
   }
   .no-wrap
   {
   white-space: nowrap;
   }
</style> 
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Trims Association Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Reports</a></li>
               <li class="breadcrumb-item active">Trims Association Report</li>
            </ol>
         </div>
      </div>
   </div>
</div>                       
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <!--<div class="col-md-12"><button class="btn btn-warning" onclick="DumpData();"></button></div>-->
            <div class="table-responsive">
               <table id="ocrTbl" class="DataTable table table-bordered nowrap w-100">
                  <thead>
                     <tr nowrap class="tr">
                        <th nowrap class="text-center">Sr No</th>
                        <th nowrap class="text-center">Sales Order No</th>
                        <th nowrap class="text-center">Item Code</th>
                        <th nowrap class="text-center">Item Category</th>
                        <th nowrap class="text-center">PO Code</th>
                        <th nowrap class="text-center">Supplier Name</th>
                        <th nowrap class="text-center">Bill To</th>
                        <th nowrap class="text-center">Item Name</th>
                        <th nowrap class="text-center">Total Asso.</th> 
                        <th nowrap class="text-center">Allocated Stock</th> 
                        <th nowrap class="text-center">Issue Stock</th>
                        <th nowrap class="text-center">Avaliable Stock</th>
                     </tr>
                  </thead>
                  <tbody></tbody>
               </table>
            </div>
         </div>
      </div>
   </div>  
</div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
 <script>
  
   $(document).ready( function () 
   {
         tableData(); 
   }); 


   function DumpData()
   {
      $.ajax({
            dataType: "json",
            type: "GET", 
            url: "{{ route('DumpTrimsStockAssocation') }}",
            success: function(data)
            {
                location.reload(0);
            }
        });
   }
   
   function tableData() 
   {
         var currentURL = window.location.href; 
         
      	 $('#ocrTbl').DataTable().clear().destroy();
         const today = new Date();
         const day = String(today.getDate()).padStart(2, '0');
         const month = String(today.getMonth() + 1).padStart(2, '0');
         const year = today.getFullYear();
         const formattedDate = `${day}-${month}-${year}`;
         const exportTitle = 'Trims Association Report (' + formattedDate + ')';
        
          var table = $('#ocrTbl').DataTable({
            ajax: currentURL,
            // pageLength: 10,
            processing: false,
            serverSide: false,
            dom: 'lBfrtip',
            buttons: [
                { extend: 'copyHtml5', footer: true, title: exportTitle },
                {  
                    extend: 'excel', title: exportTitle, 
                    exportOptions: {
                     modifier : {
                         order : 'index',  
                         page : 'all', 
                         search : 'none'  
                     },
                     columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15 ]
                 }
                },
                { extend: 'csvHtml5', footer: true, title: exportTitle  },
                { extend: 'pdfHtml5', footer: true, title: exportTitle  }
            ],
            columns: [
                  {data: 'srno', name: 'srno', className: 'text-right'}, 
                  {data: 'sales_order_no', name: 'sales_order_no'},
                  {data: 'item_code', name: 'item_code',class: 'text-right'},
                  {data: 'cat_name', name: 'cat_name'},
                  {data: 'po_code', name: 'po_code',class: 'no-wrap'},
                  {data: 'supplier_name', name: 'supplier_name',class: 'no-wrap'},
                  {data: 'bill_to', name: 'bill_to',class: 'no-wrap'},
                  {data: 'item_name', name: 'item_name'},
                  {data: 'totalAssoc', name: 'totalAssoc', className: 'text-right'},
                  {data: 'remainStock', name: 'remainStock', className: 'text-right'},
                  {data: 'trimOutwardStock', name: 'trimOutwardStock', className: 'text-right'},
                  {data: 'avilable_stock', name: 'avilable_stock', className: 'text-right'},
            ]
        });
         
    }
    
 </script>
@endsection