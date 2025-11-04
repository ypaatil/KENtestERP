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

<!--<div class="container">-->
<!--  <h2>Accordion Example</h2>-->
<!--  <p><strong>Note:</strong> The <strong>data-parent</strong> attribute makes sure that all collapsible elements under the specified parent will be closed when one of the collapsible item is shown.</p>-->
<!--  <div class="panel-group" id="accordion">-->
<!--    <div class="panel panel-default">-->
<!--      <div class="panel-heading">-->
<!--        <h4 class="panel-title">-->
<!--          <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">Collapsible Group 1</a>-->
<!--        </h4>-->
<!--      </div>-->
<!--      <div id="collapse1" class="panel-collapse collapse in">-->
<!--        <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit,-->
<!--        sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,-->
<!--        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>-->
<!--      </div>-->
<!--    </div>-->
<!--    <div class="panel panel-default">-->
<!--      <div class="panel-heading">-->
<!--        <h4 class="panel-title">-->
<!--          <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">Collapsible Group 2</a>-->
<!--        </h4>-->
<!--      </div>-->
<!--      <div id="collapse2" class="panel-collapse collapse">-->
<!--        <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit,-->
<!--        sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,-->
<!--        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>-->
<!--      </div>-->
<!--    </div>-->
<!--    <div class="panel panel-default">-->
<!--      <div class="panel-heading">-->
<!--        <h4 class="panel-title">-->
<!--          <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">Collapsible Group 3</a>-->
<!--        </h4>-->
<!--      </div>-->
<!--      <div id="collapse3" class="panel-collapse collapse">-->
<!--        <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit,-->
<!--        sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,-->
<!--        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>-->
<!--      </div>-->
<!--    </div>-->
<!--  </div> -->
<!--</div>-->



@if(isset($ColorList))
<form action="{{ route('Color.update',$ColorList) }}" method="POST">
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
</div>
<div class="col-md-3">
<div class="mb-3">
<label for="formrow-email-input" class="form-label"></label>
<button type="submit" class="btn btn-primary w-md">Submit</button>

</div>
</div>

 
</form>


@else
<form action="{{route('Color.store')}}" method="POST">
@csrf 
<div class="row">

<div class="col-md-6">
<div class="mb-3">
<label for="color_name" class="form-label">Color</label>
<input type="text" name="color_name" class="form-control" id="color_name" value="">
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