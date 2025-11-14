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
@endphp
<!-- end page title -->
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Trims GRN Detail</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Reports</a></li>
               <li class="breadcrumb-item active">Trims GRN Detail</li>
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
                        <th colspan="12"></th>
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
                        <th nowrap>Supplier Name<span class="filter-icon">🔽</span><div class="filter-menu supplier-name"></div></th>
                        <th nowrap>Bill To<span class="filter-icon">🔽</span><div class="filter-menu bill-to"></div></th>
                        <th nowrap>PO No<span class="filter-icon">🔽</span><div class="filter-menu po-no"></div></th>
                        <th nowrap>Sales Order No<span class="filter-icon">🔽</span><div class="filter-menu sales-order-no"></div></th>
                        <th nowrap>Buyer Name<span class="filter-icon">🔽</span><div class="filter-menu buyer-name"></div></th>
                        <th nowrap>Return WO no.<span class="filter-icon">🔽</span><div class="filter-menu return-wo-no"></div></th>
                        <th nowrap>Return Vendor Name<span class="filter-icon">🔽</span><div class="filter-menu return-vendor-name"></div></th> 
                        <th nowrap>GRN No.<span class="filter-icon">🔽</span><div class="filter-menu grn-no"></div></th>
                        <th nowrap>GRN Date.<span class="filter-icon">🔽</span><div class="filter-menu grn-date"></div></th>
                        <th nowrap>Invoice No.<span class="filter-icon">🔽</span><div class="filter-menu invoice-no"></div></th>
                        <th nowrap>Invoice Date.<span class="filter-icon">🔽</span><div class="filter-menu invoice-date"></div></th>
                        <th nowrap>Item Code<span class="filter-icon">🔽</span><div class="filter-menu item-code"></div></th>
                        <th nowrap>Item Name<span class="filter-icon">🔽</span><div class="filter-menu item-name"></div></th>
                        <th nowrap>GRN Qty </th>
                        <th nowrap>Rate </th>
                        <th nowrap>Value </th>
                        <th nowrap>Width</th>
                        <th nowrap>Color</th>
                        <th nowrap>Item Description<span class="filter-icon">🔽</span><div class="filter-menu item-description"></div></th>
                        <th nowrap>Rack Name<span class="filter-icon">🔽</span><div class="filter-menu rack-code"></div></th>
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
         removeFilterColor() ;
         
        var fromDate = $("#fromDate").val();
        var toDate = $("#toDate").val();
        var currentURL = "";
        
        if(ele == 1)
        {
            currentURL = "TrimsGRNData?fromDate="+fromDate+"&toDate="+toDate;  
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
         const exportTitle = 'Trims Inward Report (' + formattedDate + ')';
        
          var table = $('#dt').DataTable({
            ajax: {
                url: currentURL,
                type: "GET"  // Change to POST if your server expects POST
            },
            dom: 'lBfrtip',
            buttons: [
                { extend: 'copyHtml5', footer: true, title: exportTitle,exportOptions: commonExportOptions() },
                { extend: 'excelHtml5', footer: true, title: exportTitle ,exportOptions: commonExportOptions() },
                { extend: 'csvHtml5', footer: true, title: exportTitle ,exportOptions: commonExportOptions() },
                { extend: 'pdfHtml5', footer: true, title: exportTitle ,exportOptions: commonExportOptions() }
            ],
            initComplete: function () {
                  buildAllMenusTrimGRNDataReport();
            },
            "footerCallback": function (row, data, start, end, display) {                
                 var total_size_qty = 0;        
                 var total_item_value = 0;
                    
                for (var i = 0; i < data.length; i++) {               
                    const qty  = String(data[i].item_qty).replace(/,/g, "");
                     const val  = String(data[i].item_value).replace(/,/g, "");
                     total_size_qty  += parseFloat(qty) || 0;
                     total_item_value += parseFloat(val) || 0; 

                }
                
                let formatted_qty = parseFloat(total_size_qty).toLocaleString('en-IN', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                
                let formatted_value = parseFloat(total_item_value).toLocaleString('en-IN', {
                    currency: 'INR',
                    minimumFractionDigits: 2
                });
                
                // Update the HTML
                $('#head_total_qty').html(formatted_qty);
                $('#head_total_value').html(formatted_value);
                
              },
            columns: [
                  {data: 'ac_name', name: 'ac_name', className: 'no-wrap'},
                  {data: 'bill_name', name: 'bill_name', className: 'no-wrap'},
                  {data: 'po_code', name: 'po_code', className: 'no-wrap'},
                  {data: 'sales_order_no', name: 'sales_order_no',className: 'text-center,no-wrap'},
                  {data: 'buyer', name: 'buyer',className: 'no-wrap'},
                  {data: 'vw_code', name: 'vw_code',className: 'no-wrap'},
                  {data: 'vendorName', name: 'vendorName',className: 'no-wrap'},
                  {data: 'trimCode', name: 'trimCode',className: 'no-wrap'},
                  {data: 'trimDate', name: 'trimDate',className: 'no-wrap'},
                  {data: 'invoice_no', name: 'invoice_no',className: 'no-wrap'},
                  {data: 'invoice_date', name: 'invoice_date',className: 'no-wrap'},
                  {data: 'item_code', name: 'item_code',className: 'text-right no-wrap'},
                  {data: 'item_name', name: 'item_name',className: 'no-wrap'},
                  {data: 'item_qty', name: 'item_qty', className: 'text-right'},
                  {data: 'item_rate', name: 'item_rate', className: 'text-right'},
                  {data: 'item_value', name: 'item_value', className: 'text-right'},
                  {data: 'dimension', name: 'dimension'},
                  {data: 'color_name', name: 'color_name'},
                  {data: 'item_description', name: 'item_description'},
                  {data: 'rack_name', name: 'rack_name'}
            ]
        });



    }
    
           // Start script for filter search and apply
        
         $(document).on('click', '.apply-btn', function() {
         const menu = $(this).closest('.filter-menu');
       
         if (!validateFilterMenu(menu)) {
               return;
         }

         if(menu.hasClass('supplier-name')) applySimpleFilter(0, menu);
         else if(menu.hasClass('bill-to')) applySimpleFilter(1, menu); 
         else if(menu.hasClass('po-no')) applySimpleFilter(2, menu);            
         else if(menu.hasClass('sales-order-no')) applySimpleFilter(3,menu);
         else if(menu.hasClass('buyer-name')) applySimpleFilter(4,menu);
         else if(menu.hasClass('return-wo-no')) applySimpleFilter(5,menu);
         else if(menu.hasClass('return-vendor-name')) applySimpleFilter(6,menu);
         else if(menu.hasClass('grn-no')) applySimpleFilter(7, menu);    
         else if(menu.hasClass('grn-date')) applyDateFilter(8,menu);
         else if(menu.hasClass('invoice-no')) applySimpleFilter(9,menu);
         else if(menu.hasClass('invoice-date')) applyDateFilter(10,menu);    
         else if(menu.hasClass('item-code')) applySimpleFilter(11,menu);  
         else if(menu.hasClass('item-name')) applySimpleFilter(12,menu); 
         else if(menu.hasClass('item-description')) applySimpleFilter(18,menu);  
         else if(menu.hasClass('rack-code')) applySimpleFilter(19,menu);                         
         $('.filter-menu').hide();
         
         buildAllMenusTrimGRNDataReport(); 
         updateFooterForTrimGRNDataReport();           
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
         removeFilterColor();
         tableData(0);               
    });
    
</script>
@endsection