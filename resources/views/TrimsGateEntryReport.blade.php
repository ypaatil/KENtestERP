@extends('layouts.master') 
@section('content')   
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
@php
setlocale(LC_MONETARY, 'en_IN');   
ini_set('memory_limit', '10G'); 
@endphp
@if(session()->has('message'))
<div class="col-md-3">
   <div class="alert alert-success">
      {{ session()->get('message') }}
   </div>
</div>
@endif
@if(session()->has('messagedelete'))
<div class="col-md-3">
   <div class="alert alert-danger">
      {{ session()->get('messagedelete') }}
   </div>
</div>
@endif
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Trims Gate Entry Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Reports</a></li>
               <li class="breadcrumb-item active">Trims Gate Entry Report</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-md-12">
      <div class="card">
         <div class="card-body"> 
               <div class="row">
                   <div class="col-md-2">
                     <div class="mb-3">
                        <label for="fromDate" class="form-label">From</label>
                        <input type="date" class="form-control" name="fromDate" id="fromDate" value="{{ isset($fromDate) ? $fromDate : date('Y-m-01')}}">
                     </div>
                   </div>
                   <div class="col-md-2">
                     <div class="mb-3">
                        <label for="toDate" class="form-label">To</label>
                        <input type="date" class="form-control" name="toDate" id="toDate" value="{{ isset($toDate) ? $toDate : date('Y-m-d')}}">
                     </div>
                   </div>  
                   <div class="col-sm-5">
                      <label for="formrow-inputState" class="form-label"></label>
                      <div class="form-group">
                         <button type="button" onclick="FilterReport();" class="btn btn-primary w-md">Search</button>
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
         <div class="card-body table-responsive">
            <table id="dt" class="table table-bordered dt-responsive nowrap w-100 ">
               <thead>
                  <tr style="text-align:center;">
                    <th nowrap>TGE Code <span class="filter-icon">🔽</span><div class="filter-menu tge-code"></div></th>
                     <th nowrap>Date <span class="filter-icon">🔽</span><div class="filter-menu date"></div></th>
                     <th nowrap>PO No <span class="filter-icon">🔽</span><div class="filter-menu po-no"></div></th>
                     <th nowrap>Manual PO No <span class="filter-icon">🔽</span><div class="filter-menu manual-po-no"></div></th>
                     <th nowrap>DC No <span class="filter-icon">🔽</span><div class="filter-menu dc-no"></div></th>
                     <th nowrap>DC Date <span class="filter-icon">🔽</span><div class="filter-menu dc-date"></div></th>
                     <th nowrap>Invoice No <span class="filter-icon">🔽</span><div class="filter-menu invoice-no"></div></th>
                     <th nowrap>Invoice Date <span class="filter-icon">🔽</span><div class="filter-menu invoice-date"></div></th>
                     <th nowrap>Supplier <span class="filter-icon">🔽</span><div class="filter-menu supplier"></div></th>
                     <th nowrap>Bill To <span class="filter-icon">🔽</span><div class="filter-menu bill-to"></div></th>
                     <th nowrap>Location/Warehouse <span class="filter-icon">🔽</span><div class="filter-menu location"></div></th>
                     <th nowrap>LR No <span class="filter-icon">🔽</span><div class="filter-menu lr-no"></div></th>
                     <th nowrap>Transport Name <span class="filter-icon">🔽</span><div class="filter-menu transport-name"></div></th>
                     <th nowrap>Vehicle No <span class="filter-icon">🔽</span><div class="filter-menu vehicle-no"></div></th>
                     <th nowrap>Item Name <span class="filter-icon">🔽</span><div class="filter-menu item-name"></div></th>
                     <th nowrap>Item Code <span class="filter-icon">🔽</span><div class="filter-menu item-code"></div></th>
                     <th nowrap>Item Description <span class="filter-icon">🔽</span><div class="filter-menu item-description"></div></th>
                     <th nowrap>Challan Qty <span class="filter-icon">🔽</span><div class="filter-menu challan-qty"></div></th>
                     <th nowrap>Rate <span class="filter-icon">🔽</span><div class="filter-menu rate"></div></th>
                     <th nowrap>Amount <span class="filter-icon">🔽</span><div class="filter-menu amount"></div></th>
                     <th nowrap>Remark <span class="filter-icon">🔽</span><div class="filter-menu remark"></div></th>
                  </tr>
               </thead>
               <tbody>
               </tbody>
            </table>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script type="text/javascript">
 
   
   function FilterReport()
   {    removeFilterColor();
        var sales_order_no = $("#sales_order_no").val();
        var fromDate = $("#fromDate").val();
        var toDate = $("#toDate").val();
        var URL = "";
        
        URL = "TrimsGateEntryReport?fromDate="+fromDate+"&toDate="+toDate;  
        
        
        $('#dt').DataTable().clear().destroy();
     
        const today = new Date();
        const day = String(today.getDate()).padStart(2, '0');
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const year = today.getFullYear();
        const formattedDate = `${day}-${month}-${year}`;
        const exportTitle = 'Trims Gate Entry Report (' + formattedDate + ')';
         
        var table = $('#dt').DataTable({
         //processing: true,
        // serverSide: true,
        // "pageLength": 10,
         ajax: {
            url: URL,
            type: "GET"  // Change to POST if your server expects POST
         },
         dom: 'lBfrtip',
         buttons: [
             { extend: 'copyHtml5', footer: true, title: exportTitle,exportOptions: commonExportOptions() },
             { extend: 'excelHtml5', footer: true, title: exportTitle,exportOptions: commonExportOptions() },
             { extend: 'csvHtml5', footer: true, title: exportTitle,exportOptions: commonExportOptions() },
             { extend: 'pdfHtml5', footer: true, title: exportTitle,exportOptions: commonExportOptions() }
         ], 
         initComplete: function () {
                  buildAllMenusTrimsGateEntryReport();
            },
         columns: [
           {data: 'tge_code', name: 'tge_code', className: 'no-wrap'},
           {data: 'tge_date', name: 'tge_date', className: 'no-wrap'},
           {data: 'po_code', name: 'po_code'},
           {data: 'po_code2', name: 'po_code2'},
           {data: 'dc_no', name: 'dc_no'},
           {data: 'dc_date', name: 'dc_date', className: 'no-wrap'},
           {data: 'invoice_no', name: 'invoice_no'},
           {data: 'invoice_date', name: 'invoice_date'},
           {data: 'supplier_name', name: "supplier_name"},
           {data: 'bill_name', name: "bill_name"},
           {data: 'location', name: "location"},
           {data: 'lr_no', name: "lr_no"},
           {data: 'transport_name', name: "transport_name"},
           {data: 'vehicle_no', name: "vehicle_no"},
           {data: 'item_name', name: 'item_name'},
           {data: 'item_code', name: 'item_code'},
           {data: 'item_description', name: 'item_description'},
           {data: 'challan_qty', name: 'challan_qty', className: 'text-right'},
           {data: 'rate', name: 'rate', className: 'text-right'},
           {data: 'amount', name: 'amount', className: 'text-right'},
           {data: 'remarks', name: 'remarks'},
         ]
     });
       
   }

       // Start script for filter search and apply        
         $(document).on('click', '.apply-btn', function() {
         const menu = $(this).closest('.filter-menu');
       
         if (!validateFilterMenu(menu)) {
               return;
         }

         if(menu.hasClass('tge-code')) applySimpleFilter(0, menu);
         else if(menu.hasClass('date')) applyDateFilter(1, menu);
         else if(menu.hasClass('po-no')) applySimpleFilter(2, menu);
         else if(menu.hasClass('manual-po-no')) applySimpleFilter(3, menu);
         else if(menu.hasClass('dc-no')) applySimpleFilter(4, menu);
         else if(menu.hasClass('dc-date')) applyDateFilter(5, menu);
         else if(menu.hasClass('invoice-no')) applySimpleFilter(6, menu);
         else if(menu.hasClass('invoice-date')) applyDateFilter(7, menu);
         else if(menu.hasClass('supplier')) applySimpleFilter(8, menu);
         else if(menu.hasClass('bill-to')) applySimpleFilter(9, menu);
         else if(menu.hasClass('location')) applySimpleFilter(10, menu);
         else if(menu.hasClass('lr-no')) applySimpleFilter(11, menu);
         else if(menu.hasClass('transport-name')) applySimpleFilter(12, menu);
         else if(menu.hasClass('vehicle-no')) applySimpleFilter(13, menu);
         else if(menu.hasClass('item-name')) applySimpleFilter(14, menu);
         else if(menu.hasClass('item-code')) applySimpleFilter(15, menu);
         else if(menu.hasClass('item-description')) applySimpleFilter(16, menu);
         else if(menu.hasClass('challan-qty')) applySimpleFilter(17, menu);
         else if(menu.hasClass('rate')) applySimpleFilter(18, menu);
         else if(menu.hasClass('amount')) applySimpleFilter(19, menu);
         else if(menu.hasClass('remark')) applySimpleFilter(20, menu);

                               
         $('.filter-menu').hide();
         
         buildAllMenusTrimsGateEntryReport();                   
         });
        // End script for filter search and apply
   
   $(function () 
   {
    removeFilterColor();
        FilterReport();
   });
</script> 
@endsection