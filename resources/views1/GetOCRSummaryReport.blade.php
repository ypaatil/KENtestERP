@extends('layouts.master') 
@section('content')
<div class="row">
<div class="col-xl-12">
<div class="card">
<div class="card-body">
<h4 class="card-title mb-4">OCR Summary Report</h4>
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
<form action="/OCRSummaryReport" method="GET" enctype="multipart/form-data">
@csrf 
<div class="row">
<div class="col-md-2">
    <div class="mb-3">
        <label for="job_status_id" class="form-label">Order Status</label>
        <!--<input type="text" name="sales_order_no" class="form-control" id="sales_order_no" value="" required onfocusout="getSalesOrderDetails(this.value);">-->
       <select name="job_status_id" class="form-control select2" id="job_status_id" required   >
<option value="">--Status--</option>
@foreach($JobStatusList as  $row)
{
    <option value="{{ $row->job_status_id }}">{{ $row->job_status_name }}</option>
}
@endforeach
</select>
    </div>
</div>
 
 
<div class="col-md-2">
    <div class="mb-3">
        <label for="job_status_id" class="form-label">Month Filter</label>
       <select name="month_id" class="form-control select2" id="month_id" required   >
        <option value="6">All</option>
        <option value="5">Yesterday</option>
        <option value="1">Current Month</option>
        <option value="2">Previous Month</option>
        <option value="3">Last 2 Months</option>
        <option value="4">Last 3 Month</option>
        
        </select>
    </div>
</div



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