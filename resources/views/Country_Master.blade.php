@extends('layouts.master') 

@section('content')
<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
<h4 class="mb-sm-0 font-size-18">Country Master</h4>

<div class="page-title-right">
<ol class="breadcrumb m-0">
<li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
<li class="breadcrumb-item active">Country Master</li>
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
<h4 class="card-title mb-4">Form Grid Layout</h4>
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

@if(isset($country))
<form action="{{ route('Country.update',$country) }}" method="POST">
@method('put')

@csrf 
<div class="row">

<div class="col-md-6">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Country Name</label>
<input type="text" name="c_name" class="form-control" id="formrow-email-input" value="{{ $country->c_name }}">
<input type="hidden" name="user_id" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
</div>
</div>
</div>
<div>
<button type="submit" class="btn btn-primary w-md">Submit</button>
</div>
</form>


@else
<form action="{{route('Country.store')}}" method="POST">
@csrf 
<div class="row">

<div class="col-md-6">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Country Name</label>
<input type="text" name="c_name" class="form-control" id="formrow-email-input">
<input type="hidden" name="user_id" value="{{ Session::get('userId')}}" class="form-control" id="formrow-email-input">
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