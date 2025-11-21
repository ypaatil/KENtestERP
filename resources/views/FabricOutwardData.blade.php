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
                         <button type="button" onclick="tableData(1);" class="btn btn-primary w-md">Search</button>
                         <a href="javascript:void(0);" onclick="ClearReport(0);" class="btn btn-danger w-md">Cancel</a>
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
         <div class="card-body">
            <div class="table-responsive">
               <table id="dt" data-order='[[ 5, "desc" ]]' data-page-length='10'  class="table table-bordered  nowrap w-100">
                  <thead>
                     <tr style="text-align:center; white-space:nowrap;background: bisque;" id="total_head">
                        <th colspan="13"></th>
                        <th style="text-align: right;">Total : </th>
                        <th id="head_total_outward_qty">0</th>
                        <th></th>
                        <th id="head_total_value">0</th>
                     </tr>
                     <tr>
                        <th>Vendor Name <span class="filter-icon">🔽</span><div class="filter-menu vendor-name"></div></th>
                        <th>Buyer Name <span class="filter-icon">🔽</span><div class="filter-menu buyer-name"></div></th>
                        <th>Process Order No <span class="filter-icon">🔽</span><div class="filter-menu process-order-no"></div></th>
                        <th>Sales Order No <span class="filter-icon">🔽</span><div class="filter-menu sales-order-no"></div></th>
                        <th>Outward No. <span class="filter-icon">🔽</span><div class="filter-menu outward-no"></div></th>
                        <th>Outward Type <span class="filter-icon">🔽</span><div class="filter-menu outward-type"></div></th>
                        <th>Outward Date <span class="filter-icon">🔽</span><div class="filter-menu outward-date"></div></th>
                        <th>Supplier PO No. <span class="filter-icon">🔽</span><div class="filter-menu supplier-po-no"></div></th>
                        <th>Supplier Name <span class="filter-icon">🔽</span><div class="filter-menu supplier-name"></div></th>
                        <th>Bill To <span class="filter-icon">🔽</span><div class="filter-menu bill-to"></div></th>
                        <th>Item Code <span class="filter-icon">🔽</span><div class="filter-menu item-code"></div></th>
                        <th>Item Name <span class="filter-icon">🔽</span><div class="filter-menu item-name"></div></th>
                        <th>Quality Name <span class="filter-icon">🔽</span><div class="filter-menu quality-name"></div></th>
                        <th>Track Code <span class="filter-icon">🔽</span><div class="filter-menu track-code"></div></th>
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

    function tableData(ele) 
    {
        var fromDate = $("#fromDate").val();
        var toDate = $("#toDate").val();

         var currentURL = "";

         if(ele == 1)
         {
               currentURL = "FabricOutwardData?fromDate="+fromDate+"&toDate="+toDate;  
         }
         else
         { 
               currentURL = window.location.href+"?fromDate="+fromDate+"&toDate="+toDate; 
         } 

         
      	 $('#dt').DataTable().clear().destroy();
          const today = new Date();
         const day = String(today.getDate()).padStart(2, '0');
         const month = String(today.getMonth() + 1).padStart(2, '0');
         const year = today.getFullYear();
         const formattedDate = `${day}-${month}-${year}`;
         const exportTitle = 'Fabric Inward Report (' + formattedDate + ')';
        
          var table = $('#dt').DataTable({
            ajax: {
                url: currentURL,
                type: "GET"
            },
            dom: 'lBfrtip',
            buttons: [
                { extend: 'copyHtml5', footer: true ,title: exportTitle, exportOptions: commonExportOptions() },
                { extend: 'excelHtml5', footer: true ,title: exportTitle, exportOptions: commonExportOptions() },
                { extend: 'csvHtml5', footer: true ,title: exportTitle, exportOptions: commonExportOptions() },
                { extend: 'pdfHtml5', footer: true ,title: exportTitle, exportOptions: commonExportOptions() }
            ],
            initComplete: function () {
                  buildAllMenusFabricOutwardDataReport();
            },
            "footerCallback": function (row, data, start, end, display) {                
                 var total_meter = 0;             
                 var total_value = 0;
               
                for (var i = 0; i < data.length; i++) {
                  /*  total_meter += parseFloat(data[i].meter);
                    total_value += parseFloat(data[i].item_value);*/
                  

                  const qty  = String(data[i].meter).replace(/,/g, "");
                     const val  = String(data[i].item_value).replace(/,/g, "");
                     total_meter  += parseFloat(qty) || 0;
                     total_value += parseFloat(val) || 0; 
                }

                let formatted_meter = parseFloat(total_meter).toLocaleString('en-IN', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                
                let formatted_value = parseFloat(total_value).toLocaleString('en-IN', {
                    currency: 'INR',
                    minimumFractionDigits: 2
                });                               

                $('#head_total_outward_qty').html(formatted_meter);
                $('#head_total_value').html(formatted_value);
                
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
    
         // Start script for filter search and apply        
         $(document).on('click', '.apply-btn', function() {
         const menu = $(this).closest('.filter-menu');
       
         if (!validateFilterMenu(menu)) {
               return;
         }

         if (menu.hasClass('vendor-name')) applySimpleFilter(0, menu);
         else if (menu.hasClass('buyer-name')) applySimpleFilter(1, menu);
         else if (menu.hasClass('process-order-no')) applySimpleFilter(2, menu);
         else if (menu.hasClass('sales-order-no')) applySimpleFilter(3, menu);
         else if (menu.hasClass('outward-no')) applySimpleFilter(4, menu);
         else if (menu.hasClass('outward-type')) applySimpleFilter(5, menu);
         else if (menu.hasClass('outward-date')) applyDateFilter(6, menu);   // date column (recommended)
         else if (menu.hasClass('supplier-po-no')) applySimpleFilter(7, menu);
         else if (menu.hasClass('supplier-name')) applySimpleFilter(8, menu);
         else if (menu.hasClass('bill-to')) applySimpleFilter(9, menu);
         else if (menu.hasClass('item-code')) applySimpleFilter(10, menu);
         else if (menu.hasClass('item-name')) applySimpleFilter(11, menu);
         else if (menu.hasClass('quality-name')) applySimpleFilter(12, menu);
         else if (menu.hasClass('track-code')) applySimpleFilter(13, menu); 

         $('.filter-menu').hide();
         
         buildAllMenusFabricOutwardDataReport(); 
         updateFooterForFabricOutwardDataReport();           
         });
        // End script for filter search and apply
    
      function ClearReport()
    {
         removeFilterColor();
         tableData(0);
    }

    $( document ).ready(function() 
    { 
      removeFilterColor();
      tableData(0);       
    });
</script>
@endsection