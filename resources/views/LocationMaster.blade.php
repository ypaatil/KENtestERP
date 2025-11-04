@extends('layouts.master') 

@section('content')
<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
<h4 class="mb-sm-0 font-size-18">Location Master</h4>

<div class="page-title-right">
<ol class="breadcrumb m-0">
<li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
<li class="breadcrumb-item active">Location Master</li>
</ol>
</div>

</div>
</div>
</div>
<!-- end page title -->

<div class="row">
<div class="col-xl-12">
<div class="card">
<div class="card-body">
<h4 class="card-title mb-4">Location</h4>
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

@if(isset($LocationList))
<form action="{{ route('Location.update',$LocationList) }}" method="POST">
@method('put')

@csrf 
<div class="row">

<div class="col-md-6">
<div class="mb-3">
<label for="formrow-dept_name-input" class="form-label">Location</label>
<input type="text" name="location" class="form-control" id="formrow-location-input" value="{{ $LocationList->location }}">
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
</div>
</div>
<div class="col-md-6">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Address</label>
<input type="text" name="loc_inc" class="form-control" id="loc_inc" value="{{ $LocationList->loc_inc }}">
<input type="hidden" name="created_at" class="form-control" id="created_at" value="{{ $LocationList->created_at }}">

</div>
</div>

<div class="col-md-3">
<div class="mb-3">
<label for="gst_no" class="form-label">GST NO.</label>
<input type="text" name="gst_no" class="form-control" id="gst_no" value="{{ $LocationList->gst_no }}"> 

</div>
</div>

<div class="col-md-3">
<div class="mb-3">
<label for="pan_no" class="form-label">PAN NO.</label>
<input type="text" name="pan_no" class="form-control" id="pan_no" value="{{ $LocationList->pan_no }}"> 

</div>
</div>

<div class="col-md-6">
<div class="mb-3 mt-4">
<label for="formrow-email-input" class="form-label"></label>
<button type="submit" class="btn btn-primary w-md">Submit</button>
<a href="{{ Route('Location.index') }}" class="btn btn-warning w-md">Cancel</a>
</div>
</div>
</div>
 
</form>


@else
<form action="{{route('Location.store')}}" method="POST">
@csrf 
<div class="row">

<div class="col-md-6">
<div class="mb-3">
<label for="formrow-location-input" class="form-label">Location</label>
<input type="text" name="location" class="form-control" id="formrow-location-input" value="">
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-location-input">
</div>
</div>
<div class="col-md-6">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Address</label>
<input type="text" name="loc_inc" class="form-control" id="details" value="">

</div>
</div>

<div class="col-md-3">
<div class="mb-3">
<label for="gst_no" class="form-label">GST NO.</label>
<input type="text" name="gst_no" class="form-control" id="gst_no" value=""> 

</div>
</div>

<div class="col-md-3">
<div class="mb-3">
<label for="pan_no" class="form-label">PAN NO.</label>
<input type="text" name="pan_no" class="form-control" id="pan_no" value=""> 

</div>
</div>


<div class="col-md-6">
<div class="mb-3 mt-4">
<label for="formrow-email-input" class="form-label"></label>
<button type="submit" class="btn btn-primary w-md">Submit</button>
<a href="{{ Route('Location.index') }}" class="btn btn-warning w-md">Cancel</a>

</div>
</div>
</div>
</form>
@endif


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


<!-- end row -->
@endsection