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
                       <th nowrap>Sr No<span class="filter-icon hide">🔽</span><div class="filter-menu sr-no"></div></th>
                        <th nowrap>Sales Order No<span class="filter-icon">🔽</span><div class="filter-menu sales-order-no"></div></th>
                        <th nowrap>Item Code<span class="filter-icon">🔽</span><div class="filter-menu item-code"></div></th>
                        <th nowrap>PO Code<span class="filter-icon">🔽</span><div class="filter-menu po-code"></div></th>
                        <th nowrap>Supplier Name<span class="filter-icon">🔽</span><div class="filter-menu supplier-name"></div></th>
                        <th nowrap>Bill To<span class="filter-icon">🔽</span><div class="filter-menu bill-to"></div></th>
                        <th nowrap>Item Name<span class="filter-icon">🔽</span><div class="filter-menu item-name"></div></th>
                        <th nowrap>Total Asso.<span class="filter-icon">🔽</span><div class="filter-menu total-asso"></div></th>
                        <th nowrap>Allocated Stock<span class="filter-icon">🔽</span><div class="filter-menu allocated-stock"></div></th>
                        <th nowrap>Issue Stock<span class="filter-icon">🔽</span><div class="filter-menu issue-stock"></div></th>
                        <th nowrap>Avaliable Stock<span class="filter-icon">🔽</span><div class="filter-menu available-stock"></div></th>                    
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
    {    removeFilterColor();
         const today = new Date();
         const day = String(today.getDate()).padStart(2, '0');
         const month = String(today.getMonth() + 1).padStart(2, '0');
         const year = today.getFullYear();
         const formattedDate = `${day}-${month}-${year}`;
         const exportTitle = 'Fabric Association Report (' + formattedDate + ')';
       
         
        var currentURL = window.location.href; 

      	 $('#ocrTbl').DataTable().clear().destroy();
        
          var table = $('#ocrTbl').DataTable({
            ajax: currentURL,
            // pageLength: 10,
            processing: false,
            serverSide: false,
            dom: 'lBfrtip',
            buttons: [
                { extend: 'copyHtml5', footer: true,title: exportTitle, exportOptions: commonExportOptions() },
                { extend: 'excelHtml5', footer: true, title: exportTitle,exportOptions: commonExportOptions() },
                /*{  
                    extend: 'excelHtml5',footer: true,title: exportTitle, exportOptions: commonExportOptions(), 
                    exportOptions: {
                     modifier : {
                         order : 'index',  
                         page : 'all', 
                         search : 'none'  
                     },
                     columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                 }
                },*/
                { extend: 'csvHtml5', footer: true,title: exportTitle, exportOptions: commonExportOptions() },
                { extend: 'pdfHtml5', footer: true,title: exportTitle, exportOptions: commonExportOptions() }
            ],
            initComplete: function () {
                  buildAllMenusFabricAssociationReport();
            },
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

             // Start script for filter search and apply        
         $(document).on('click', '.apply-btn', function() {
         const menu = $(this).closest('.filter-menu');
       
         if (!validateFilterMenu(menu)) {
               return;
         }

         if (menu.hasClass('sr-no')) applySimpleFilter(0, menu); 
         else if (menu.hasClass('sales-order-no')) applySimpleFilter(1, menu); 
         else if (menu.hasClass('item-code')) applySimpleFilter(2, menu); 
         else if (menu.hasClass('po-code')) applySimpleFilter(3, menu); 
         else if (menu.hasClass('supplier-name')) applySimpleFilter(4, menu); 
         else if (menu.hasClass('bill-to')) applySimpleFilter(5, menu); 
         else if (menu.hasClass('item-name')) applySimpleFilter(6, menu); 
         else if (menu.hasClass('total-asso')) applySimpleFilter(7, menu); 
         else if (menu.hasClass('allocated-stock')) applySimpleFilter(8, menu); 
         else if (menu.hasClass('issue-stock')) applySimpleFilter(9, menu); 
         else if (menu.hasClass('available-stock')) applySimpleFilter(10, menu);

         $('.filter-menu').hide();
         
         buildAllMenusFabricAssociationReport();                   
         });
        // End script for filter search and apply
    
      function ClearReport()
    {
         removeFilterColor();
         tableData();
    }
 </script>
@endsection