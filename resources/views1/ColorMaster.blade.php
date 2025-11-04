@extends('layouts.master') 

@section('content')

<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
<h4 class="mb-sm-0 font-size-18">Color Master</h4>

<div class="page-title-right">
<ol class="breadcrumb m-0">
<li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
<li class="breadcrumb-item active">Color Master</li>
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
<h4 class="card-title mb-4">Color</h4>
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
 



@if(isset($ColorList))
<form action="{{ route('Color.update',$ColorList) }}" method="POST" enctype="multipart/form-data">
@method('put')

@csrf 
<div class="row">

<div class="col-md-6">
<div class="mb-3">
<label for="color_name" class="form-label">Color</label>
<input type="text" name="color_name" class="form-control" id="color_name" value="{{ $ColorList->color_name }}">
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
<input type="hidden" name="created_at" class="form-control" id="created_at" value="{{ $ColorList->created_at }}">
</div>
</div>


<div class="col-md-3">
<div class="mb-3">
<label for="style_img_path" class="form-label">Fabric Color Image</label>
<input type="file" name="style_img_path" class="form-control" id="style_img_path"  >
<input type="hidden" name="style_img_pathold" class="form-control" id="style_img_pathold" value="{{ $ColorList->style_img_path }}"  >
</div>
</div>



<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Preview: </label>
@if($ColorList->style_img_path!='') 
 <a href="{{url('images/'.$ColorList->style_img_path)}}" target="_blank"><img  src="{{url('thumbnail/'.$ColorList->style_img_path)}}" height="60" width="50" > </a>
 @else
 <label for="NoImage" class="form-label">No Item Image</label>
 @endif
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
<form action="{{route('Color.store')}}" method="POST" enctype="multipart/form-data">
@csrf 
<div class="row">

<div class="col-md-6">
<div class="mb-3">
<label for="color_name" class="form-label">Color</label>
<input type="text" name="color_name" class="form-control" id="color_name" value="">
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
 
</div>
</div>

<div class="col-md-3">
<div class="mb-3">
<label for="style_img_path" class="form-label">Fabric Color Image</label>
<input type="file" name="style_img_path" class="form-control" id="style_img_path"  >
<input type="hidden" name="style_img_pathold" class="form-control" id="style_img_pathold" >
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