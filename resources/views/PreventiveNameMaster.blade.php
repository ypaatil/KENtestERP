@extends('layouts.master') 

@section('content')
<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
<h4 class="mb-sm-0 font-size-18">Preventive Name Master</h4>

<div class="page-title-right">
<ol class="breadcrumb m-0">
<li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
<li class="breadcrumb-item active">Preventive Name Master</li>
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
<h4 class="card-title mb-4">Preventive Name</h4>
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

@if(isset($preventivename))
<form action="{{ route('PreventiveName.update',$preventivename) }}" method="POST">
@method('put')

@csrf 
<div class="row">
<div class="col-md-6">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Preventive Name</label>
<input type="text" name="preventive_name" class="form-control" id="formrow-email-input" value="{{ $preventivename->preventive_name }}">
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
</div>
</div>
</div>
<div>
<button type="submit" class="btn btn-primary w-md">Submit</button>
<a href="{{ Route('PreventiveName.index') }}"  class="btn btn-warning w-md">Cancel</a>
</div>
</form>

@else
<form action="{{route('PreventiveName.store')}}" method="POST">
@csrf 
<div class="row">
<div class="col-md-6">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Preventive Name</label>
<input type="text" name="preventive_name" class="form-control" id="formrow-email-input" value="">
<input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="formrow-email-input">
</div>
</div>
</div>

<div>
<button type="submit" class="btn btn-primary w-md">Submit</button>
<a href="{{ Route('PreventiveName.index') }}"  class="btn btn-warning w-md">Cancel</a>
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