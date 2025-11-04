@extends('layouts.master') 
@section('content')   
@php
      
ini_set('memory_limit', '10G'); 
setlocale(LC_MONETARY, 'en_IN');  
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
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Fabric GRN Detail</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Fabric GRN Detail</li>
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
               <table id="dt" class="table table-bordered   nowrap w-100">
                  <thead>
                     <tr style="text-align:center; white-space:nowrap;background: bisque;" id="total_head">
                        <th colspan="11"></th>
                        <th style="text-align: right;">Total : </th>
                        <th id="head_total_grn_qty">0</th>
                        <th></th>
                        <th id="head_total_value">0</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                     </tr>
                     <tr style="text-align:center; white-space:nowrap">
                        <th>Supplier Name</th>
                        <th>Bill To</th>
                        <th>Buyer Name</th>
                        <th>PO No</th>
                        <th>GRN No.</th>
                        <th>GRN Date.</th>
                        <th>Return CPO no.</th>
                        <th>Return Vendor Name</th> 
                        <th>Invoice No.</th>
                        <th>Invoice Date.</th>
                        <th>Item Code</th>
                        <th>Item Name</th>
                        <th>GRN Qty</th>
                        <th>Rate</th>
                        <th>Value</th>
                        <th>Width</th>
                        <th>Quality Name</th>
                        <th>Color</th>
                        <th>Item Description</th>
                        <th>Track Code</th>
                        <th>Rack Name</th>
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
            currentURL = "FabricGRNData?fromDate="+fromDate+"&toDate="+toDate;  
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
         const exportTitle = 'Fabric Inward Report (' + formattedDate + ')';
        
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
                 var total_meter = 0;             
                 var total_value = 0;
                    
                for (var i = 0; i < data.length; i++) {
                    total_meter += parseFloat(data[i].meter);
                    total_value += parseFloat(data[i].item_value);
                } 
                
                let formatted_qty = parseFloat(total_meter).toLocaleString('en-IN', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                
                let formatted_value = parseFloat(total_value).toLocaleString('en-IN', {
                    currency: 'INR',
                    minimumFractionDigits: 2
                });
                
                // Update the HTML
                $('#head_total_grn_qty').html(formatted_qty);
                $('#head_total_value').html(formatted_value);
                
                
              },
            columns: [
                  {data: 'ac_short_name', name: 'ac_short_name'},
                  {data: 'bill_to', name: 'bill_to'},
                  {data: 'buyer', name: 'buyer'},
                  {data: 'po_code', name: 'po_code'},
                  {data: 'in_code', name: 'in_code'},
                  {data: 'in_date', name: 'in_date'},
                  {data: 'vw_code', name: 'vw_code'},
                  {data: 'vendorName', name: 'vendorName'},
                  {data: 'invoice_no', name: 'invoice_no'},
                  {data: 'invoice_date', name: 'invoice_date'},
                  {data: 'item_code', name: 'item_code', className: 'text-right'},
                  {data: 'item_name', name: 'item_name'},
                  {data: 'meter', name: 'meter', className: 'text-right'},
                  {data: 'item_rate', name: 'item_rate', className: 'text-right'},
                  {data: 'item_value', name: 'item_value', className: 'text-right'},
                  {data: 'dimension', name: 'dimension'},
                  {data: 'quality_name', name: 'quality_name'},
                  {data: 'color_name', name: 'color_name'},
                  {data: 'item_description', name: 'item_description'},
                  {data: 'track_code', name: 'track_code'},
                  {data: 'rack_name', name: 'rack_name'}
            ]
        });
    }
    
    function ClearReport()
    {
         tableData(0);
    }
    
    $( document ).ready(function() 
    { 
        tableData(0);
       
    });
</script>
@endsection