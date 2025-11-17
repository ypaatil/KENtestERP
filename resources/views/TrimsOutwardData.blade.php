@extends('layouts.master') 
@section('content')   
@php 
    setlocale(LC_MONETARY, 'en_IN');
    ini_set('memory_limit', '10G');
@endphp
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
<!-- end page title -->
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Trims Outward Detail</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Reports</a></li>
               <li class="breadcrumb-item active">Trims Outward Detail</li>
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
               <table id="dt" class="table table-bordered nowrap w-100">
                  <thead>
                      <tr style="text-align:center; white-space:nowrap;background: bisque;" id="total_head">
                        <th colspan="13"></th>
                        <th style="text-align: right;">Total : </th>
                        <th id="head_total_qty">0</th>
                        <th></th>
                        <th id="head_total_amount">0</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                     </tr>
                     <tr style="text-align:center; white-space:nowrap">
                        <th nowrap>Vendor Name<span class="filter-icon">🔽</span><div class="filter-menu vendor-name"></div></th>

                           <th nowrap>Process Order No<span class="filter-icon">🔽</span><div class="filter-menu process-order-no"></div></th>

                           <th nowrap>Work Order No<span class="filter-icon">🔽</span><div class="filter-menu work-order-no"></div></th>

                           <th nowrap>Sample Indent Code<span class="filter-icon">🔽</span><div class="filter-menu sample-indent-code"></div></th>

                           <th nowrap>Trims Type<span class="filter-icon">🔽</span><div class="filter-menu trims-type"></div></th>

                           <th nowrap>Sales Order No<span class="filter-icon">🔽</span><div class="filter-menu sales-order-no"></div></th>

                           <th nowrap>Buyer Name<span class="filter-icon">🔽</span><div class="filter-menu buyer-name"></div></th>

                           <th nowrap>Out No.<span class="filter-icon">🔽</span><div class="filter-menu out-no"></div></th>

                           <th nowrap>Out Date.<span class="filter-icon">🔽</span><div class="filter-menu out-date"></div></th>

                           <th nowrap>PO Code<span class="filter-icon">🔽</span><div class="filter-menu po-code"></div></th>

                           <th nowrap>Supplier<span class="filter-icon">🔽</span><div class="filter-menu supplier"></div></th>

                           <th nowrap>Bill To<span class="filter-icon">🔽</span><div class="filter-menu bill-to"></div></th>

                           <th nowrap>Item Code<span class="filter-icon">🔽</span><div class="filter-menu item-code"></div></th>

                           <th nowrap>Item Name<span class="filter-icon">🔽</span><div class="filter-menu item-name"></div></th>

                           <th nowrap>Out Qty<span class="filter-icon">🔽</span><div class="filter-menu out-qty"></div></th>

                           <th nowrap>Rate<span class="filter-icon">🔽</span><div class="filter-menu rate"></div></th>

                           <th nowrap>Amount<span class="filter-icon">🔽</span><div class="filter-menu amount"></div></th>

                           <th nowrap>Width<span class="filter-icon">🔽</span><div class="filter-menu width"></div></th>

                           <th nowrap>Quality Name<span class="filter-icon">🔽</span><div class="filter-menu quality-name"></div></th>

                           <th nowrap>Color<span class="filter-icon">🔽</span><div class="filter-menu color"></div></th>

                           <th nowrap>Item Description<span class="filter-icon">🔽</span><div class="filter-menu item-description"></div></th>

                     </tr>
                  </thead>
                  <tbody></tbody>
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
        removeFilterColor() ;
        if(ele == 1)
        {
            currentURL = "TrimsOutwardData?fromDate="+fromDate+"&toDate="+toDate;  
        }
        else
        { 
            currentURL = window.location.href; 
        }  
         
         
      	 $('#dt').DataTable().clear().destroy();
         const today = new Date();
         const day = String(today.getDate()).padStart(2, '0');
         const month = String(today.getMonth() + 1).padStart(2, '0');
         const year = today.getFullYear();
         const formattedDate = `${day}-${month}-${year}`;
         const exportTitle = 'Trims Outward Report (' + formattedDate + ')';
        
        var table = $('#dt').DataTable({
            ajax: {
                url: currentURL,
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
                  buildAllMenusTrimOutwardDataReport();
            },
            "footerCallback": function (row, data, start, end, display) {                
                 var total_size_qty = 0;
                  var total_amount = 0;  
                for (var i = 0; i < data.length; i++) {                     
                     const qty  = String(data[i].item_qty).replace(/,/g, "");
                     const val  = String(data[i].item_value).replace(/,/g, "");
                     total_size_qty  += parseFloat(qty) || 0;
                     total_amount += parseFloat(val) || 0; 
                    //total_size_qty += parseFloat(data[i].out_qty);
                   // total_amount += parseFloat(data[i].item_value);
                } 
                
                let formatted_qty = parseFloat(total_size_qty).toLocaleString('en-IN', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                
                let formatted_value = parseFloat(total_amount).toLocaleString('en-IN', {
                    currency: 'INR',
                    minimumFractionDigits: 2
                });
                
                // Update the HTML
                $('#head_total_qty').html(formatted_qty);
                $('#head_total_amount').html(formatted_value);
                
              },
            columns: [
                  {data: 'ac_name', name: 'ac_name', className: 'no-wrap'},
                  {data: 'vpo_code', name: 'vpo_code', className: 'no-wrap'},
                  {data: 'vw_code', name: 'vw_code', className: 'no-wrap'},
                  {data: 'sample_indent_code', name: 'sample_indent_code', className: 'no-wrap'},
                  {data: 'typeName', name: 'typeName',className: 'no-wrap'},
                  {data: 'sales_order_no', name: 'sales_order_no',className: 'no-wrap'},
                  {data: 'vw_ac_name', name: 'vw_ac_name',className: 'no-wrap'},
                  {data: 'trimOutCode', name: 'trimOutCode',className: 'no-wrap'},
                  {data: 'tout_date', name: 'tout_date',className: 'no-wrap'},
                  {data: 'po_code', name: 'po_code',className: 'no-wrap'},
                  {data: 'supplier', name: 'supplier',className: 'no-wrap'},
                  {data: 'bill_name', name: 'bill_name',className: 'no-wrap'},
                  {data: 'item_code', name: 'item_code', className: 'text-right no-wrap'},
                  {data: 'item_name', name: 'item_name',className: 'no-wrap'},
                  {data: 'out_qty', name: 'out_qty', className: 'text-right'},
                  {data: 'item_inward_rate', name: 'item_inward_rate', className: 'text-right'},
                  {data: 'item_value', name: 'item_value', className: 'text-right'},
                  {data: 'dimension', name: 'dimension',className: 'no-wrap'},
                  {data: 'quality_name', name: 'quality_name',className: 'no-wrap'},
                  {data: 'color_name', name: 'color_name',className: 'no-wrap'},
                  {data: 'item_description', name: 'item_description'}
            ]
        });
    }
    
      // Start script for filter search and apply        
         $(document).on('click', '.apply-btn', function() {
         const menu = $(this).closest('.filter-menu');
       
         if (!validateFilterMenu(menu)) {
               return;
         }

         if(menu.hasClass('vendor-name')) applySimpleFilter(0, menu);
         else if(menu.hasClass('process-order-no')) applySimpleFilter(1, menu);
         else if(menu.hasClass('work-order-no')) applySimpleFilter(2, menu);
         else if(menu.hasClass('sample-indent-code')) applySimpleFilter(3, menu);
         else if(menu.hasClass('trims-type')) applySimpleFilter(4, menu);
         else if(menu.hasClass('sales-order-no')) applySimpleFilter(5, menu);
         else if(menu.hasClass('buyer-name')) applySimpleFilter(6, menu);
         else if(menu.hasClass('out-no')) applySimpleFilter(7, menu);
         else if(menu.hasClass('out-date')) applyDateFilter(8, menu);
         else if(menu.hasClass('po-code')) applySimpleFilter(9, menu);
         else if(menu.hasClass('supplier')) applySimpleFilter(10, menu);
         else if(menu.hasClass('bill-to')) applySimpleFilter(11, menu);
         else if(menu.hasClass('item-code')) applySimpleFilter(12, menu);
         else if(menu.hasClass('item-name')) applySimpleFilter(13, menu);
         else if(menu.hasClass('out-qty')) applySimpleFilter(14, menu);
         else if(menu.hasClass('rate')) applySimpleFilter(15, menu);
         else if(menu.hasClass('amount')) applySimpleFilter(16, menu);
         else if(menu.hasClass('width')) applySimpleFilter(17, menu);
         else if(menu.hasClass('quality-name')) applySimpleFilter(18, menu);
         else if(menu.hasClass('color')) applySimpleFilter(19, menu);
         else if(menu.hasClass('item-description')) applySimpleFilter(20, menu);                       
         $('.filter-menu').hide();
         
         buildAllMenusTrimOutwardDataReport(); 
         updateFooterForTrimOutwardDataReport();           
         });
        // End script for filter search and apply

    function ClearReport()
    {
        removeFilterColor() ;
        $("#sales_order_no").val("").trigger('change');
        tableData(0);
    }
  
    $( document ).ready(function() 
    { 
       removeFilterColor() ;
        tableData(0);
       
    });
</script>
@endsection