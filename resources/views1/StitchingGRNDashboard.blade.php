@extends('layouts.master') 
@section('content')   
@php
setlocale(LC_MONETARY, 'en_IN');  
@endphp
<!-- end page title -->
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Stitching GRN Detail Dashboard</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Stitching GRN Detail Dashboard</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#152d9f;" >
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">No. of Orders</p>
                  <h4 class="mb-0" style="color:#fff;"></h4>
               </div>
               <div class="flex-shrink-0 align-self-center">
                  <div class="  avatar-sm rounded-circle bg-primary" style="background-color:#152d9f;">
                     <span class="avatar-title" style="background-color:#152d9f;">
                     <i class="bx bx-copy-alt font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#556ee6;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;" >Order Qty(Lakh)</p>
                  <h4 class="mb-0" style="color:#fff;" > </h4>
               </div>
               <div class="flex-shrink-0 align-self-center ">
                  <div class="avatar-sm rounded-circle bg-primary  ">
                     <span class="avatar-title  " style="background-color:#556ee6;" >
                     <i class="bx bx-purchase-tag-alt font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#008116;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">Shipped Qty(Lakh)</p>
                  <h4 class="mb-0" style="color:#fff;">   </h4>
               </div>
               <div class="flex-shrink-0 align-self-center">
                  <div class="avatar-sm rounded-circle bg-primary  " >
                     <span class="avatar-title  " style="background-color:#008116;">
                     <i class="bx bx-archive-in font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#f79733;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">Open Qty(Lakh)</p>
                  <h4 class="mb-0" style="color:#fff;"> </h4>
               </div>
               <div class="flex-shrink-0 align-self-center">
                  <div class="avatar-sm rounded-circle bg-primary  " >
                     <span class="avatar-title  " style="background-color:#f79733;">
                     <i class="bx bx-archive-in font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
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
            <table id="dt" class="table table-responsive">
               <thead>
                   <tr style="text-align:center; white-space:nowrap;background: bisque;" id="total_head">
                        <th colspan="11"></th>
                        <th style="text-align: right;">Total : </th>
                        <th id="head_total_size_qty">0</th>
                        <th id="head_total_min">0</th>
                        <th></th>
                  </tr>
                  <tr style="text-align:center;">
                     <th nowrap>SrNo</th>
                     <th nowrap>GRN No</th>
                     <th nowrap>GRN Date</th>
                     <th nowrap>Sales Order No</th>
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
                     <th nowrap>Total Operator</th>
                  </tr>
               </thead>
               <tbody>
               </tbody>
            </table>
         </div>
      </div>
   </div>
   <input type="hidden" id="total_operator" value="0">
   <!-- end col -->
</div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script>
    
    function tableData() 
    {
         var tempop=0; var tempCode=''; flag=0;
         var currentURL = window.location.href; 
         
      	 $('#dt').DataTable().clear().destroy();
      	 var table1 = $('#example').DataTable();
 
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
            "footerCallback": function (row, data, start, end, display) 
            {                
                var total_size_qty = 0;             
                var total_min = 0;
                    
                for (var i = 0; i < data.length; i++) {
                    total_size_qty += parseFloat(data[i].size_qty);
                    total_min += parseFloat(data[i].Minutes);
                }
                $('#head_total_size_qty').html(total_size_qty.toFixed(2));
                $('#head_total_min').html(total_min.toFixed(2));
                
            },
            columnDefs: [{
              targets: 0,
              autoWidth: true,
              searchable: false,
              orderable: false,
              render: function(data, type, row, info) {
                  
                 return parseInt(info.row)+1;
              }   
            }], 
            columns: [
                  {data: 'srno', name: 'srno'},
                  {data: 'sti_code', name: 'sti_code'},
                  {data: 'sti_date', name: 'sti_date'},
                  {data: 'sales_order_no', name: 'sales_order_no'},
                  {data: 'sam', name: 'sam'},
                  {data: 'vw_code', name: 'vw_code'},
                  {data: 'vendor_name', name: 'vendor_name'},
                  {data: 'mainstyle_name', name: 'mainstyle_name'},
                  {data: 'style_no', name: 'style_no'},
                  {data: 'color_name', name: 'color_name'},
                  {data: 'line_no', name: 'line_no'},
                  {data: 'size_name', name: 'size_name'},
                  {data: 'size_qty', name: 'size_qty'},
                  {data: 'Minutes', name: 'Minutes'},
                  {data: 'total_operator1', name: 'total_operator1',class: 'text-center',
                      render: function (data, type, row, meta) {
                        return (
                            custum(row.total_operator1)
                        );
                      },
                  }
            ]
        });
    }
    function custum(ele)
    {   
        var temp = $("#total_operator").val();
        var td_val = ele;
        if(td_val != temp)
        {
            $("#total_operator").val(td_val);
            return ele = td_val;
        }
        else
        {
            return ele = 0;
        }
        temp = td_val;
    }
    
    $( document ).ready(function() 
    { 
        tableData();
    });
    
    
       
</script>
@endsection