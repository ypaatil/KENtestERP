@extends('layouts.master') 

@section('content')
<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
<h4 class="mb-sm-0 font-size-18">Business Type Master</h4>

<div class="page-title-right">
<ol class="breadcrumb m-0">
<li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
<li class="breadcrumb-item active">Business Type Master</li>
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
<h4 class="card-title mb-4">Business Type</h4>
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

@if(isset($business_type1))
<form action="{{ route('BusinessType.update',$business_type1) }}" method="POST">
@method('put')

@csrf 
<div class="row">

<div class="col-md-6">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Business Type</label>
<input type="text" name="Bt_name" class="form-control" id="formrow-email-input" value="{{ $business_type1->Bt_name }}">
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
<input type="hidden" name="created_at" class="form-control" id="created_at" value="{{ $business_type1->created_at }}">
   
</div>
</div>
<div class="col-md-6">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Description</label>
<input type="text" name="description" class="form-control" id="description" value="{{ $business_type1->description }}">

</div>
</div>

</div>
<div>
<button type="submit" class="btn btn-primary w-md">Submit</button>
</div>
</form>


@else
<form action="{{route('BusinessType.store')}}" method="POST">
@csrf 
<div class="row">

<div class="col-md-6">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Business Type</label>
<input type="text" name="Bt_name" class="form-control" id="formrow-email-input">
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
</div>
</div>
 

<div class="col-md-6">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Description</label>
<input type="text" name="description" class="form-control" id="description">

</div>
</div>
</div>

<div>
<button type="submit" class="btn btn-primary w-md">Submit</button>
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