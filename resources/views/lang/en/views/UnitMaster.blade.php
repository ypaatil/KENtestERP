@extends('layouts.master') 

@section('content')
<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
<h4 class="mb-sm-0 font-size-18">Unit Master</h4>

<div class="page-title-right">
<ol class="breadcrumb m-0">
<li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
<li class="breadcrumb-item active">Unit Master</li>
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
<h4 class="card-title mb-4">Unit</h4>
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

@if(isset($UnitList))
<form action="{{ route('Unit.update',$UnitList) }}" method="POST">
@method('put')

@csrf 
<div class="row">

<div class="col-md-6">
<div class="mb-3">
<label for="formrow-unit_name-input" class="form-label">Unit</label>
<input type="text" name="unit_name" class="form-control" id="formrow-unit_name-input" value="{{ $UnitList->unit_name }}">
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
<input type="hidden" name="created_at" class="form-control" id="created_at" value="{{ $UnitList->created_at }}">
</div>
</div>
</div>
<div class="col-md-3">
<div class="mb-3">
<label for="formrow-email-input" class="form-label"></label>
<button type="submit" class="btn btn-primary w-md">Submit</button>

</div>
</div>

 
</form>


@else
<form action="{{route('Unit.store')}}" method="POST">
@csrf 
<div class="row">

<div class="col-md-6">
<div class="mb-3">
<label for="formrow-unit_name-input" class="form-label">Unit</label>
<input type="text" name="unit_name" class="form-control" id="formrow-unit_name-input" value="">
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
 
</div>
</div>
</div>
<div class="col-md-3">
<div class="mb-3">
<label for="formrow-email-input" class="form-label"></label>
<button type="submit" class="btn btn-primary w-md">Submit</button>

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