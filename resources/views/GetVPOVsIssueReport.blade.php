@extends('layouts.master') 
@section('content')
<div class="row">
<div class="col-xl-12">
<div class="card">
<div class="card-body">
<h4 class="card-title mb-4">PO Vs Material Issued Report</h4>
@if ($errors->any())
<div class="col-md-6">
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    </div>
@endif
<form action="/POVsMaterialIssueReport" method="GET" enctype="multipart/form-data">
@csrf 
<div class="row">
<div class="col-md-2">
    <div class="mb-3">
        <label for="sales_order_no" class="form-label">Sales Order no</label>
        <!--<input type="text" name="sales_order_no" class="form-control" id="sales_order_no" value="" required onfocusout="getSalesOrderDetails(this.value);">-->
       <select name="sales_order_no" class="form-control select2" id="sales_order_no" required   >
<option value="">--Sales Order No--</option>
@foreach($SalesOrderList as  $row)
{
    <option value="{{ $row->sales_order_no }}">{{ $row->sales_order_no }}</option>
}
@endforeach
</select>
    </div>
</div>

<div class="col-md-3">
    <div class="mb-3">
        <label for="vendorId" class="form-label">Vendor Name</label>
       <select name="vendorId" class="form-control select2" id="vendorId" required   onchange="GetCuttingPoList(this.value);">
        <option value="">-- Vendor Name. --</option>
 
@foreach($LedgerList as  $row)
{
    <option value="{{ $row->ac_code }}">{{ $row->ac_name }}</option>
}
@endforeach
        </select>
    </div>
</div>



<div class="col-md-2">
    <div class="mb-3">
        <label for="vpo_code" class="form-label">Process Order No.</label>
       <select name="vpo_code" class="form-control select2" id="vpo_code"     >
        <option value="">-- Process Order No. --</option>
        </select>
    </div>
</div>

<div class="col-md-2">
    <div class="mb-3">
        <label for="vw_code" class="form-label">Work Order No.</label>
      <select name="vw_code" class="form-control select2" id="vw_code"     >
        <option value="">-- Work Order No. --</option>
        </select>
    </div>
</div>


 
 

</div>

 

<div class="col-sm-2">
<label for="formrow-inputState" class="form-label"></label>
<div class="form-group">
<button type="submit" class="btn btn-primary w-md">Submit</button>
</div>
</div>

</div>

</form>
 


</div>
<!-- end card body -->
</div>
<!-- end card -->
</div>
<!-- end col -->


<!-- end col -->
</div>
<!-- end row -->

             
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<!-- end row -->
 
 
 <script>
     
     function GetCuttingPoList(vendorId)
     {
         var sales_order_no=$('#sales_order_no').val();
         $.ajax({
        dataType: "json",
        url: "{{ route('getVendorAllPO') }}",
        data:{'sales_order_no':sales_order_no,vendorId:vendorId},
        success: function(data){
        $("#vpo_code").html(data.html);
        }
        });
         
         
          $.ajax({
        dataType: "json",
        url: "{{ route('VendorAllWorkOrders') }}",
        data:{'sales_order_no':sales_order_no,vendorId:vendorId},
        success: function(data){
        $("#vw_code").html(data.html);
        }
        });
         
         
         
         
     }
     
     
 </script>
 
 
 
 
 
 
 
<!-- end row -->
@endsection