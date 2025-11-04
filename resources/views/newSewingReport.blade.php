@extends('layouts.master') 
@section('content')   
@php
setlocale(LC_MONETARY, 'en_IN');   
ini_set('memory_limit', '10G'); 
@endphp
<style>
    .center-cell {
        vertical-align: middle !important;
        text-align: center !important; 
    }

</style>
<!-- end page title -->
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Sewing Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">MIS Reports</a></li>
               <li class="breadcrumb-item active">Sewing Report</li>
            </ol>
         </div>
      </div>
   </div>
</div>
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
      <div class="card">
         <div class="card-body">
            <div class="row" style="margin-bottom: -8%;">
               <div class="col-md-4"></div>
               <div class="col-md-4">
                  <div class="card mini-stats-wid">
                     <div class="card-body">
                        <div class="d-flex"> 
                         <form action="newSewingReport" method="GET">
                             <div class="row">
                                <div class="col-md-4">  
                                    <label><b>From Date</b></label><input type="date" class="form-control" name="fromDate" id="fromDate" value="{{$fromDate}}" required>
                                </div>
                                <div class="col-md-4">  
                                    <label><b>To Date</b></label><input type="date" class="form-control" name="toDate" id="toDate" value="{{$toDate}}" required>
                                </div>
                                <div class="col-md-4 mt-4">   
                                     <label></label><button type="submit" class="btn btn-primary">Search</button>
                                     <label></label><a href="newSewingReport" class="btn btn-danger">Clear</a>
                                </div>
                             </div>
                         </form>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-md-4"></div>
            </div>
            <div class="col-md-12  table-responsive">
            <table id="dt" class="table">
               <thead>
                   <tr style="text-align:center; white-space:nowrap;background: bisque;" id="total_head">
                        <th colspan="15"></th>
                        <th style="text-align: right;">Total : </th>
                        <th id="head_total_size_qty">0</th>
                        <th id="head_total_min">0</th>
                        <th></th>
                        <th></th>
                  </tr>
                  <tr style="text-align:center;">
                     <th nowrap>SrNo</th>
                     <th nowrap>GRN No</th>
                     <th nowrap>GRN Date</th>
                     <th nowrap>Sales Order No</th>
                     <th nowrap>Buyer Name</th>
                     <th nowrap>Buyer Brand</th>
                     <th nowrap>SAM</th>
                     <th nowrap>Work Order No</th>
                     <th nowrap>Vendor Name</th>
                     <th nowrap>Main Style Category</th>
                     <th nowrap>Style Name</th>
                     <th nowrap>Color</th>
                     <th nowrap>Line no</th>
                     <th nowrap>Sizes</th>
                     <th nowrap>Size Qty</th>
                     <th nowrap>Minutes</th>
                     <th nowrap>FOB Rate</th>
                     <th nowrap>Value Of Production</th>
                     <th nowrap>CMOHP</th>
                     <th nowrap>CMOHP Value</th>
                  </tr>
               </thead>
               <tbody>
               </tbody>
            </table>
            </div>
         </div>
      </div>
   </div>
   <input type="hidden" id="h_total_operator" value="0">
   <input type="hidden" id="h_sales_order_no" value="">
   <!-- end col -->
</div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script>
    
    function tableData() 
    {
        var currentURL = window.location.href;
    
        $('#dt').DataTable().clear().destroy();
    
        var table = $('#dt').DataTable({
            ajax: currentURL,
            dom: 'lBfrtip',
            processing: true,
            stateSave: true,
            buttons: [
                { extend: 'copyHtml5', footer: true },
                { extend: 'excelHtml5', footer: true },
                { extend: 'csvHtml5', footer: true },
                { extend: 'pdfHtml5', footer: true }
            ],
            footerCallback: function (row, data, start, end, display) {
                var total_size_qty = 0;
                var total_min = 0;
    
                for (var i = 0; i < data.length; i++) {
                    total_size_qty += parseFloat(data[i].size_qty || 0);
                    total_min += parseFloat(data[i].Minutes || 0);
                }
    
                $('#head_total_size_qty').html(total_size_qty.toFixed(2));
                $('#head_total_min').html(total_min.toFixed(2));
            },
            columnDefs: [{
                targets: 0,
                autoWidth: true,
                searchable: false,
                orderable: false,
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                }
            }],
            createdRow: function (row, data, dataIndex) {
                $(row).addClass('sales_order_' + data.sales_order_no);
                $(row).addClass('total_op_' + data.total_operator1);
            },
            columns: [
                { data: null, name: 'srno' },
                { data: 'sti_code', name: 'sti_code' },
                { data: 'sti_date', name: 'sti_date' },
                { data: 'sales_order_no', name: 'sales_order_no' },
                { data: 'buyer_name', name: 'buyer_name' },
                { data: 'brand_name', name: 'brand_name' },
                { data: 'sam', name: 'sam' },
                { data: 'vw_code', name: 'vw_code' },
                { data: 'vendor_name', name: 'vendor_name' },
                { data: 'mainstyle_name', name: 'mainstyle_name' },
                { data: 'style_no', name: 'style_no' },
                { data: 'color_name', name: 'color_name' },
                { data: 'line_no', name: 'line_no' },
                { data: 'size_name', name: 'size_name' },
                { data: 'size_qty', name: 'size_qty' },
                { data: 'Minutes', name: 'Minutes' },
                { data: 'fob_rate', name: 'fob_rate' },
                { data: 'valueOfProduction', name: 'valueOfProduction' },
                { data: 'CMOHP', name: 'CMOHP' },
                { data: 'CMOHP_Value', name: 'CMOHP_Value' }
            ]
        });
    }


    function custum(ele)
    {    
        var temp = $("#h_total_operator").val();
        var td_val = ele;
        if(td_val != temp)
        {
            $("#h_total_operator").val(td_val);
            return ele = td_val;
        }
        else
        {
            return ele = 0;
        }
        temp = td_val;
        $("#h_total_operator").val("");
    }
    
    $( document ).ready(function() 
    { 
        tableData(); 
    });
 
</script>
@endsection