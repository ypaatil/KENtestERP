@extends('layouts.master') 

@section('content')
<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
<h4 class="mb-sm-0 font-size-18">Main Style Master</h4>

<div class="page-title-right">
<ol class="breadcrumb m-0">
<li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
<li class="breadcrumb-item active">Main Style Master</li>
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
<h4 class="card-title mb-4">Main Style</h4>
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

@if(isset($MainStyleList))
<form action="{{ route('MainStyle.update',$MainStyleList) }}" method="POST">
@method('put')

@csrf 
<div class="row">
 

<div class="col-md-4">
<div class="mb-3">
<label for="formrow-mainstyle_name-input" class="form-label">Main Style</label>
<input type="text" name="mainstyle_name" class="form-control" id="formrow-mainstyle_name-input" value="{{ $MainStyleList->mainstyle_name }}">
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control"  >
<input type="hidden" name="created_at" class="form-control" id="created_at" value="{{ $MainStyleList->created_at }}">
   
</div>
</div>
  
</div>
<div class="row"> 
<div class="col-md-5">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">&nbsp;</label>
<button type="submit" class="btn btn-primary w-md">Submit</button>
<a href="{{ Route('MainStyle.index') }}"  class="btn btn-warning w-md">Cancel</a>
</div>
</div>
</div>


 
</form>


@else
<form action="{{route('MainStyle.store')}}" method="POST">
@csrf 
 

<div class="row">
<div class="col-md-4">
<div class="mb-3">
<label for="formrow-mainstyle_name-input" class="form-label">Main Style</label>
<input type="text" name="mainstyle_name" class="form-control" id="formrow-mainstyle_name-input" value="">
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control"  >
</div>
</div>
 
</div>
<div class="row"> 
<div class="col-md-6">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">&nbsp;</label>
<button type="submit" class="btn btn-primary w-md">Submit</button>
<a href="{{ Route('MainStyle.index') }}"  class="btn btn-warning w-md">Cancel</a>
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