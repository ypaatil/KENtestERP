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
                        <th nowrap>Supplier Name</th>
                        <th nowrap>Bill To</th>
                        <th nowrap>PO No</th>
                        <th nowrap>Sales Order No</th>
                        <th nowrap>Buyer Name</th>
                        <th nowrap>Return WO no.</th>
                        <th nowrap>Return Vendor Name</th> 
                        <th nowrap>GRN No.</th>
                        <th nowrap>GRN Date.</th>
                        <th nowrap>Invoice No.</th>
                        <th nowrap>Invoice Date.</th>
                        <th nowrap>Item Code</th>
                        <th nowrap>Item Name</th>
                        <th nowrap>GRN Qty </th>
                        <th nowrap>Rate </th>
                        <th nowrap>Value </th>
                        <th nowrap>Width</th>
                        <th nowrap>Color</th>
                        <th nowrap>Item Description</th>
                        <th nowrap>Rack Name</th>
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
                { extend: 'copyHtml5', footer: true, title: exportTitle },
                { extend: 'excelHtml5', footer: true, title: exportTitle },
                { extend: 'csvHtml5', footer: true, title: exportTitle },
                { extend: 'pdfHtml5', footer: true, title: exportTitle }
            ],
            "footerCallback": function (row, data, start, end, display) {                
                 var total_size_qty = 0;        
                 var total_item_value = 0;
                    
                for (var i = 0; i < data.length; i++) {
                    total_size_qty += parseFloat(data[i].item_qty);
                    total_item_value += parseFloat(data[i].item_value);
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
    
    
    function ClearReport()
    {
        $("#sales_order_no").val("").trigger('change');
        tableData(0);
    }
  
    $( document ).ready(function() 
    { 
        tableData(0);
       
    });
</script>
@endsection