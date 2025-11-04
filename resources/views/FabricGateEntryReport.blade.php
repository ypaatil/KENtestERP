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
         <h4 class="mb-sm-0 font-size-18">Fabric Gate Entry Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Reports</a></li>
               <li class="breadcrumb-item active">Fabric Entry Report</li>
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
         <div class="card-body">
            <table id="tbl" class="table table-bordered dt-responsive nowrap w-100 ">
               <thead>
                  <tr style="text-align:center;">
                     <th nowrap>FGE Code</th>
                     <th nowrap>Date</th>
                     <th nowrap>PO No</th>
                     <th nowrap>Manual PO No</th>
                     <th nowrap>DC No</th>
                     <th nowrap>DC Date</th>
                     <th nowrap>Invoice No</th>
                     <th nowrap>Invoice Date</th>
                     <th nowrap>Supplier</th>
                     <th nowrap>Bill To</th>
                     <th nowrap>Location/Warehouse</th>
                     <th nowrap>LR No</th>
                     <th nowrap>Transport Name</th>
                     <th nowrap>Vehicle No</th>
                     <th nowrap>Item Name</th>
                     <th nowrap>Item Code</th>
                     <th nowrap>Item Description</th>
                     <th nowrap>No.of Roll</th>
                     <th nowrap>Challan Qty</th>
                     <th nowrap>Rate</th>
                     <th nowrap>Amount</th>
                     <th nowrap>Remark</th>
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
   {
        var sales_order_no = $("#sales_order_no").val();
        var fromDate = $("#fromDate").val();
        var toDate = $("#toDate").val();
        var URL = "";
        
        URL = "FabricGateEntryReport?fromDate="+fromDate+"&toDate="+toDate;  
        
        
        $('#tbl').DataTable().clear().destroy();
     
        const today = new Date();
        const day = String(today.getDate()).padStart(2, '0');
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const year = today.getFullYear();
        const formattedDate = `${day}-${month}-${year}`;
        const exportTitle = 'Fabric Gate Entry Report (' + formattedDate + ')';
         
        var table = $('#tbl').DataTable({
         //processing: true,
        // serverSide: true,
        // "pageLength": 10,
         ajax: {
            url: URL,
            type: "GET"  // Change to POST if your server expects POST
         },
         dom: 'lBfrtip',
         buttons: [
             { extend: 'copyHtml5', footer: true, title: exportTitle },
             { extend: 'excelHtml5', footer: true, title: exportTitle },
             { extend: 'csvHtml5', footer: true, title: exportTitle },
             { extend: 'pdfHtml5', footer: true, title: exportTitle }
         ], 
         columns: [
           {data: 'fge_code', name: 'fge_code', className: 'no-wrap'},
           {data: 'fge_date', name: 'fge_date', className: 'no-wrap'},
           {data: 'po_code', name: 'po_code'},
           {data: 'po_code2', name: 'po_code2'},
           {data: 'dc_no', name: 'dc_no'},
           {data: 'dc_date', name: 'dc_date', className: 'no-wrap'},
           {data: 'invoice_no', name: 'invoice_no'},
           {data: 'invoice_date', name: 'invoice_date'},
           {data: 'supplier_name', name: "supplier_name"},
           {data: 'bill_to', name: "bill_to"},
           {data: 'location', name: "location"},
           {data: 'lr_no', name: "lr_no"},
           {data: 'transport_name', name: "transport_name"},
           {data: 'vehicle_no', name: "vehicle_no"},
           {data: 'item_name', name: 'item_name'},
           {data: 'item_code', name: 'item_code'},
           {data: 'item_description', name: 'item_description'},
           {data: 'total_roll', name: 'total_roll', className: 'text-right'},
           {data: 'challan_qty', name: 'challan_qty', className: 'text-right'},
           {data: 'rate', name: 'rate', className: 'text-right'},
           {data: 'amount', name: 'amount', className: 'text-right'},
           {data: 'remarks', name: 'remarks'},
         ]
     });
       
   }
   
   $(function () 
   {
        FilterReport();
   });
</script> 
@endsection