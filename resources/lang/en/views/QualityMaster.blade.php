@extends('layouts.master') 

@section('content')
<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
<h4 class="mb-sm-0 font-size-18">Quality Master</h4>

<div class="page-title-right">
<ol class="breadcrumb m-0">
<li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
<li class="breadcrumb-item active">Quality Master</li>
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
<h4 class="card-title mb-4">Quality</h4>
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

@if(isset($QualityList))
<form action="{{ route('Quality.update',$QualityList) }}" method="POST">
@method('put')

@csrf 
<div class="row">
 
<div class="col-md-6">
<div class="mb-3">
<label for="quality_name" class="form-label">Quality</label>
<input type="text" name="quality_name" class="form-control" id="quality_name" required value="{{ $QualityList->quality_name }}">
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="userId">
<input type="hidden" name="created_at" class="form-control" id="created_at" value="{{ $QualityList->created_at }}">
   
</div>
</div>
  

</div>
<div>
<button type="submit" class="btn btn-primary w-md">Submit</button>
</div>
</form>


@else
<form action="{{route('Quality.store')}}" method="POST">
@csrf 
<div class="row">
 

<div class="col-md-6">
<div class="mb-3">
<label for="quality_name" class="form-label">Quality</label>
<input type="text" name="quality_name" class="form-control" id="quality_name" required>
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="userId">
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