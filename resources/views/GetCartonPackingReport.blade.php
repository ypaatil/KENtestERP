@extends('layouts.master') 
@section('content')
<div class="row">
<div class="col-xl-12">
<div class="card">
<div class="card-body">
<h4 class="card-title mb-4">Carton Packing Report</h4>
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
<form action="/CartonPackingReport" method="GET" enctype="multipart/form-data">
@csrf 
<div class="row">

<div class="col-md-2">
 <div class="mb-3">
    <label for="fdate" class="form-label">From Date</label>
    <input type="date" name="fdate" class="form-control" value="{{date('Y-m-d')}}">
 </div>
</div>
<div class="col-md-2">
 <div class="mb-3">
    <label for="tdate" class="form-label">To Date</label>
    <input type="date" name="tdate" class="form-control" value="{{date('Y-m-d')}}">
 </div>
</div>    
    
<div class="col-md-2">
    <div class="mb-3">
        <label for="job_status_id" class="form-label">Order Status</label>
        <!--<input type="text" name="sales_order_no" class="form-control" id="sales_order_no" value="" required onfocusout="getSalesOrderDetails(this.value);">-->
        <select name="job_status_id" class="form-control select2" id="job_status_id" >
            <option value="2">All</option>
            <option value="1">Shipped</option>
            <option value="0">Packed</option>
        </select>
    </div>
</div>
      
<div class="col-md-2">
    <div class="mb-3">
        <label for="sales_order_no" class="form-label">Sales Order no</label>
        <!--<input type="text" name="sales_order_no" class="form-control" id="sales_order_no" value="" required onfocusout="getSalesOrderDetails(this.value);">-->
       <select name="sales_order_no" class="form-control select2" id="sales_order_no" >
<option value="0">--All--</option>
@foreach($SalesOrderList as  $row)
{
    <option value="{{ $row->sales_order_no }}">{{ $row->sales_order_no }}</option>
}
@endforeach
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
 
<!-- end row -->
@endsection