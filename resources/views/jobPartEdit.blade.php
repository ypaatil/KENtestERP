@extends('layouts.master') 

@section('content')
<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
<h4 class="mb-sm-0 font-size-18">Job Part Master</h4>

<div class="page-title-right">
<ol class="breadcrumb m-0">
<li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
<li class="breadcrumb-item active">Job Part Master</li>
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
<h4 class="card-title mb-4">Job Part</h4>
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
<form action="{{ route('JobPart.update',$JobPartList) }}" method="POST">
@method('put')

@csrf  
<div class="row"> 
<div class="col-md-2">
<div class="mb-3">
<label for="job_part_name" class="form-label">Job Part (Short Code)</label>
<input type="hidden" name="jpart_id" class="form-control" id="jpart_id" value="{{ $JobPartList->jpart_id }}">
<input type="text" name="jpart_name" class="form-control" id="jpart_name" value="{{ $JobPartList->jpart_name }}">
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="userId"> 
<input type="hidden" name="created_at" class="form-control" id="created_at" value="{{ $JobPartList->created_at }}">
   
</div>
</div>
 
 
<div class="col-md-2">
<div class="mb-3">
<label for="job_part_name" class="form-label">Job Part Description</label>
<input type="text" name="jpart_description" class="form-control" id="jpart_description" value="{{ $JobPartList->jpart_description }}">
    
</div>
</div>
 
 
 

</div>
<div>
<button type="submit" class="btn btn-primary w-md">Submit</button>
<a href="{{ Route('JobPart.index') }}" class="btn btn-warning w-md">Cancel</a>
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
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script>

 
    
</script>
<!-- end row -->


<!-- end row -->
@endsection