@extends('layouts.master') 

@section('content')
<div class="row">
<div class="col-xl-12">
<div class="card">
<div class="card-body">
<h4 class="card-title mb-4">Fabric Stock  Report</h4>
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

<div class="row">
    <div class="col-12 col-md-6">
       <div id="dateError"></div>
    </div>
    <div class="col-12 col-md-6">       
    </div>
</div>

<form action="{{route('FabricInOutStockReport')}}" method="GET" enctype="multipart/form-data">
@csrf 
<div class="row">

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">From date</label>
<input type="date" name="fdate" class="form-control" id="fdate" value="{{date('Y-m-01')}}" required>

</div>
</div>
 
<div class="col-md-2">
    <div class="mb-3">
        <label for="po_date" class="form-label">To Date</label>
        <input type="date" name="tdate" class="form-control" id="tdate" value="{{date('Y-m-d')}}" required>
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
 
<script>
document.querySelector("form").addEventListener("submit", function(e) {
    let fromDate = document.getElementById("fdate").value;
    let toDate   = document.getElementById("tdate").value;

    let errorBox = document.getElementById("dateError");

    // Clear previous message
    errorBox.innerHTML = "";

    if (toDate < fromDate) {
        e.preventDefault();
        errorBox.innerHTML = `
            <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                <strong>Error:</strong> To Date cannot be less than From Date.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
    }
    else{
        errorBox.innerHTML = "";
    }
});
</script>

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