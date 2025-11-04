@extends('layouts.master') 

@section('content')



<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
<h4 class="mb-sm-0 font-size-18">Shipment Mode Master</h4>

<div class="page-title-right">
<ol class="breadcrumb m-0">
<li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
<li class="breadcrumb-item active">Shipment Mode Master</li>
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
<h4 class="card-title mb-4">Shipment Mode</h4>
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

@if(isset($ShipmentModeList))
<form action="{{ route('ShipmentMode.update',$ShipmentModeList) }}" method="POST">
@method('put')

@csrf 
<div class="row">
 

<div class="col-md-4">
<div class="mb-3">
<label for="formrow-ship_mode_name-input" class="form-label">Shipment Mode</label>
<input type="text" name="ship_mode_name" class="form-control" id="formrow-ship_mode_name-input" value="{{ $ShipmentModeList->ship_mode_name }}">
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control"  >
<input type="hidden" name="created_at" class="form-control" id="created_at" value="{{ $ShipmentModeList->created_at }}">
   
</div>
</div>
  
</div>
<div class="row"> 
<div class="col-md-5">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">&nbsp;</label>
<button type="submit" class="btn btn-primary w-md">Submit</button>
<a href="{{ Route('ShipmentMode.index') }}"  class="btn btn-warning w-md">Cancel</a>
</div>
</div>



 
</form>


@else
<form action="{{route('ShipmentMode.store')}}" method="POST">
@csrf 
<div class="row">

<div class="row">
<div class="col-md-4">
<div class="mb-3">
<label for="formrow-ship_mode_name-input" class="form-label">Shipment Mode</label>
<input type="text" name="ship_mode_name" class="form-control" id="formrow-ship_mode_name-input" value="">
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control"  >
</div>
</div>
 
</div>
<div class="row"> 
<div class="col-md-6">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">&nbsp;</label>
<button type="submit" class="btn btn-primary w-md">Submit</button>
<a href="{{ Route('ShipmentMode.index') }}"  class="btn btn-warning w-md">Cancel</a>
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