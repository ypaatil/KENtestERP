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
         <h4 class="mb-sm-0 font-size-18">Sales Order Details Size Wise Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Reports</a></li>
               <li class="breadcrumb-item active">Sales Order Details Size Wise Report</li>
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
                   <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sales_order_no" class="form-label">Sales Order No</label>
                        <select name="sales_order_no" id="sales_order_no" class="form-control select2">
                            <option value="">--Select--</option>
                            @foreach($salesOrderList as $row)
                                <option value="{{$row->tr_code}}"  {{ $row->tr_code == $sales_order_no ? 'selected="selected"' : '' }} >{{$row->tr_code}}</option>
                            @endforeach
                        </select>
                     </div>
                   </div> 
                   <div class="col-sm-5">
                      <label for="formrow-inputState" class="form-label"></label>
                      <div class="form-group">
                         <button type="button" onclick="FilterReport(1);" class="btn btn-primary w-md">Search</button>
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
            <table id="tbl" class="table table-bordered dt-responsive nowrap w-100 ">
               <thead>
                  <tr style="text-align:center;">
                     <th nowrap>Buyer Name</th>
                     <th nowrap>Sales Order No</th>
                     <th nowrap>Order Date </th>
                     <th nowrap>PO Status</th>
                     <th nowrap>Brand Name</th>
                     <th nowrap>Main Style Name</th>
                     <th nowrap>Style Name</th>
                     <th nowrap>Garment Color</th>
                     <th nowrap>Color</th>
                     <th nowrap>Size</th>
                     <th nowrap>Order Qty</th>
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
 
   
   function FilterReport(ele)
   {
        var sales_order_no = $("#sales_order_no").val();
        var fromDate = $("#fromDate").val();
        var toDate = $("#toDate").val();
        var URL = "";
        
        if(ele == 0)
        {
            URL = "BuyerSalesOrderSizeQtyDashboard?fromDate="+fromDate+"&toDate="+toDate;  
        }
        else
        {
            URL = "BuyerSalesOrderSizeQtyDashboard?fromDate="+fromDate+"&toDate="+toDate+"&sales_order_no="+sales_order_no;
        }  
        
        $('#tbl').DataTable().clear().destroy();
     
        const today = new Date();
        const day = String(today.getDate()).padStart(2, '0');
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const year = today.getFullYear();
        const formattedDate = `${day}-${month}-${year}`;
        const exportTitle = 'Buyer Sales Order Size Wise Report (' + formattedDate + ')';
         
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
           {data: 'buyer', name: 'ledger_master.ac_short_name'},
           {data: 'tr_code', name: 'buyer_purchse_order_master.tr_code'},
           {
               data: 'tr_date',
               name: 'buyer_purchse_order_master.tr_date', 
               className: 'text-nowrap',
               render: function(data, type, row) {
                 if (!data) return '';
                 
                 const date = new Date(data);
                 const options = { day: '2-digit', month: 'short', year: 'numeric' };
                 return date.toLocaleDateString('en-GB', options).replace(/ /g, '-');
               }
           },
           {data: 'job_status_name', name: 'job_status_master.job_status_name'},
           {data: 'brand_name', name: 'brand_master.brand_name'},
           {data: 'mainstyle_name', name: 'main_style_master.mainstyle_name'},
           {data: 'fg_name', name: 'fg_master.fg_name'},
           {data: 'item_name', name: 'item_master.item_name'},
           {data: 'color_name', name: "color_master.color_name"},
           {data: 'size_name', name: 'size_detail.size_name', className: 'text-right'},
           {data: 'size_qty', name: 'buyer_purchase_order_size_detail.size_qty', className: 'text-right'},
         ]
     });
       
   }
   
   function ClearReport()
   {
        $("#sales_order_no").val("").trigger('change');
        FilterReport(0);
   }
 
   $(function () 
   {
        FilterReport(0);
   });
</script> 
@endsection