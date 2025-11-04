@extends('layouts.master') 

@section('content')

<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
<h4 class="mb-sm-0 font-size-18">Commission Master</h4>

<div class="page-title-right">
<ol class="breadcrumb m-0">
<li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
<li class="breadcrumb-item active">Commission Master</li>
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
<h4 class="card-title mb-4">Commission</h4>
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
 
@if(isset($CommissionList))
<form action="{{ route('Commission.update',$CommissionList) }}" method="POST">
@method('put')

@csrf 
<div class="row">

<div class="col-md-6">
<div class="mb-3">
<label for="coms_name" class="form-label">Commission</label>
<input type="text" name="coms_name" class="form-control" id="color_name" value="{{ $CommissionList->coms_name }}">
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
<input type="hidden" name="created_at" class="form-control" id="created_at" value="{{ $CommissionList->created_at }}">
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
<form action="{{route('Commission.store')}}" method="POST">
@csrf 
<div class="row">

<div class="col-md-6">
<div class="mb-3">
<label for="coms_name" class="form-label">Commission</label>
<input type="text" name="coms_name" class="form-control" id="coms_name" value="">
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
 
  <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">-->
  <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>-->
  <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>-->
 
<!-- end row -->
@endsection