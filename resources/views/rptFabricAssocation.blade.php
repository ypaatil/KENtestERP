@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp                
<!-- end page title -->
 
<style>
    /*.tr{*/
    /*    background: #423434;*/
    /*    color: #fff;*/
    /*}*/
    .text-right{
        text-align:right;
    }
</style>
<div class="row">
   <div class="col-12 text-center"> 
          <h3><b>Fabric Association Report</b></h3> 
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
                        <th nowrap>Sr No</th>
                        <th nowrap>Sales Order No</th>
                        <th nowrap>Item Code</th>
                        <th nowrap>PO Code</th>
                        <th nowrap>Supplier Name</th>
                        <th nowrap>Bill To</th>
                        <th nowrap>Item Name</th>
                        <th nowrap>Total Asso.</th> 
                        <th nowrap>Allocated Stock</th> 
                        <th nowrap>Issue Stock</th>
                        <th nowrap>Avaliable Stock</th>
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
                url: "{{ route('DumpFabricStockAssocation') }}",
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
        
          var table = $('#ocrTbl').DataTable({
            ajax: currentURL,
            // pageLength: 10,
            processing: false,
            serverSide: false,
            dom: 'lBfrtip',
            buttons: [
                { extend: 'copyHtml5', footer: true },
                {  
                    extend: 'excel', 
                    exportOptions: {
                     modifier : {
                         order : 'index',  
                         page : 'all', 
                         search : 'none'  
                     },
                     columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                 }
                },
                { extend: 'csvHtml5', footer: true },
                { extend: 'pdfHtml5', footer: true }
            ],
            columns: [
                  {data: 'srno', name: 'srno'}, 
                  {data: 'sales_order_no', name: 'sales_order_no'},
                  {data: 'item_code', name: 'item_code',class: 'text-center'},
                  {data: 'po_code', name: 'po_code'},
                  {data: 'supplier_name', name: 'supplier_name'},
                  {data: 'bill_to', name: 'bill_to'},
                  {data: 'item_name', name: 'item_name'},
                  {data: 'totalAssoc', name: 'totalAssoc',class: 'text-right'},
                  {data: 'remainStock', name: 'remainStock',class: 'text-right'},
                  {data: 'fabricOutwardStock', name: 'fabricOutwardStock',class: 'text-right'},
                  {data: 'avilable_stock', name: 'avilable_stock',class: 'text-right'}
            ]
        });
         
    }
    
 </script>
@endsection