@extends('layouts.master') 
@section('content')   
@php
setlocale(LC_MONETARY, 'en_IN');  
@endphp
<!-- end page title -->
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Quality Control Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">MIS Reports</a></li>
               <li class="breadcrumb-item active">Quality Control Report</li>
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
            <div class="row">
               <div class="col-md-12">
                  <div class="card mini-stats-wid">
                     <div class="card-body">
                        <div class="d-flex"> 
                         <form action="QualityControlReport" method="GET">
                             <div class="row">
                                <div class="col-md-2">  
                                    <label><b>From Date</b></label><input type="date" class="form-control" name="fromDate" id="fromDate" value="{{$fromDate}}" required>
                                </div>
                                <div class="col-md-2">  
                                    <label><b>To Date</b></label><input type="date" class="form-control" name="toDate" id="toDate" value="{{$toDate}}" required>
                                </div>
                                <div class="col-md-3">
                                     <div class="mb-3">
                                        <label for="sales_order_no" class="form-label">KDPL No.</label>
                                        <select name="sales_order_no" class="form-control select2" id="sales_order_no">
                                           <option value="">--Select--</option>
                                           @foreach($SalesOrderList as  $row)
                                           <option value="{{ $row->sales_order_no }}" {{ $row->sales_order_no == $sales_order_no ? 'selected="selected"' : '' }} >{{ $row->sales_order_no }}</option>
                                           @endforeach
                                        </select>
                                     </div>
                                </div>
                                <div class="col-md-2">
                                     <div class="mb-3">
                                        <label for="vw_code" class="form-label">Work Order No</label>
                                        <select name="vw_code" class="form-control select2" id="vw_code">
                                           <option value="">--Select--</option>
                                           @foreach($VendorWorkOrderList as  $row)
                                           <option value="{{ $row->vw_code }}" {{ $row->vw_code == $vw_code ? 'selected="selected"' : '' }} >{{ $row->vw_code }}</option>
                                           @endforeach
                                        </select>
                                     </div>
                                </div>
                                <div class="col-md-3">
                                     <div class="mb-3">
                                        <label for="Ac_code" class="form-label">Buyer Name</label>
                                        <select name="Ac_code" class="form-control select2" id="Ac_code" onchange="GetPlanLineList(this.value);"  >
                                           <option value="">--Select--</option>
                                           @foreach($LedgerList as  $row)
                                           <option value="{{ $row->ac_code }}" {{ $row->ac_code == $Ac_code ? 'selected="selected"' : '' }} >{{ $row->ac_short_name }}</option>
                                           @endforeach
                                        </select>
                                     </div>
                                </div>
                                <div class="col-md-3">
                                     <div class="mb-3">
                                        <label for="brand_id" class="form-label">Buyer Brand</label>
                                        <select name="brand_id" class="form-control select2" id="brand_id">
                                           <option value="">--Select--</option>
                                           @foreach($BrandList as  $row)
                                           <option value="{{ $row->brand_id }}" {{ $row->brand_id == $brand_id ? 'selected="selected"' : '' }} >{{ $row->brand_name }}</option>
                                           @endforeach
                                        </select>
                                     </div>
                                </div>
                                <div class="col-md-2">
                                     <div class="mb-3">
                                        <label for="line_id" class="form-label">Line No</label>
                                        <select name="line_id" class="form-control select2" id="line_id">
                                           <option value="">--Select--</option>
                                        </select>
                                     </div>
                                </div>
                                <div class="col-md-4 mt-4">   
                                     <label></label><button type="submit" class="btn btn-primary">Search</button>
                                     <label></label><a href="QualityControlReport" class="btn btn-danger">Clear</a>
                                </div>
                             </div>
                         </form>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-md-12  table-responsive"> 
            <table id="dt" class="table">
               <thead>
                  <tr style="text-align:center;">
                     <th nowrap>SrNo</th>
                     <th nowrap>GRN Date</th>
                     <th nowrap>Type</th>
                     <th nowrap>Operations</th>
                     <th nowrap>Alter Type</th>
                     <th nowrap>Sales Order No</th>
                     <th nowrap>Buyer Name</th>
                     <th nowrap>Buyer Brand</th>
                     <th nowrap>SAM</th>
                     <th nowrap>Work Order No</th>
                     <th nowrap>Main Style Category</th>
                     <th nowrap>Style Name</th>
                     <th nowrap>Color</th>
                     <th nowrap>Line no</th>
                     <th nowrap>Sizes</th>
                     <th nowrap>Size Qty</th>
                     <th nowrap>Minutes</th>
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
    function GetPlanLineList(ele)
    {
        $.ajax({
            dataType: "json",
            url: "{{ route('GetPlanLineList') }}",
            data:{'Ac_code':ele},
            success: function(data){
            $('#line_id').html(data.html);
           }
        });
    }
   
    function tableData() 
    {
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
            columns: [
                  {data: 'srno', name: 'srno'},
                  {data: 'QualityControlDate', name: 'QualityControlDate'},
                  {data: 'type', name: 'type'},
                  {data: 'operation', name: 'operation'},
                  {data: 'alter_type', name: 'alter_type'},
                  {data: 'sales_order_no', name: 'sales_order_no'},
                  {data: 'buyer_name', name: 'buyer_name'},
                  {data: 'brand_name', name: 'brand_name'},
                  {data: 'sam', name: 'sam'},
                  {data: 'vw_code', name: 'vw_code'},
                  {data: 'mainstyle_name', name: 'mainstyle_name'},
                  {data: 'style_no', name: 'style_no'},
                  {data: 'color_name', name: 'color_name'},
                  {data: 'line_no', name: 'line_no'},
                  {data: 'size_name', name: 'size_name'},
                  {data: 'qty', name: 'qty'},
                  {data: 'Minutes', name: 'Minutes'}
            ] 
        });
        
    }
    
    $( document ).ready(function() 
    { 
        tableData(); 
    });
 
</script>
@endsection