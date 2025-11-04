@extends('layouts.master') 

@section('content')
<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
<h4 class="mb-sm-0 font-size-18">District Master</h4>

<div class="page-title-right">
<ol class="breadcrumb m-0">
<li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
<li class="breadcrumb-item active">District Master</li>
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
<h4 class="card-title mb-4">District Master</h4>
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

@if(isset($District))
<form action="{{ route('District.update',$District) }}" method="POST">
@method('put')

@csrf 
<div class="row">
<div class="col-md-4">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Country</label>
<select name="c_id" class="form-select" id="c_id">
<option value="">--- Select Country ---</option>
@foreach($Countrylist as  $row)
{
<option value="{{ $row->c_id }}"

{{ $row->c_id == $District->c_id ? 'selected="selected"' : '' }}

>{{ $row->c_name }}</option>

}
@endforeach
</select>
</div>
</div>

<div class="col-md-4">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">State</label>
<select name="state_id" class="form-select" id="state_id">
<option value="">--- Select State ---</option>
@foreach($statelist as  $row2)
{
<option value="{{ $row2->state_id }}"

{{ $row2->state_id == $District->state_id ? 'selected="selected"' : '' }}

>{{ $row2->state_name }}</option>

}
@endforeach
</select>
</div>
</div>

<div class="col-md-4">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">District Name</label>
<input type="text" name="d_name" class="form-control" id="formrow-email-input" value="{{ $District->d_name }}">
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
</div>
</div>
</div>
<div>
<button type="submit" class="btn btn-primary w-md">Submit</button>
</div>
</form>

@else
<form action="{{route('District.store')}}" method="POST">
@csrf 
<div class="row">
<div class="col-md-4">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Country</label>
<select name="c_id" class="form-select" id="c_id">
<option value="">--- Select Country ---</option>
@foreach($Countrylist as  $row)
{
<option value="{{ $row->c_id }}">{{ $row->c_name }}</option>

}
@endforeach
</select>
</div>
</div>
<div class="col-md-4">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">State</label>
<select name="state_id" class="form-select" id="state_id">
<option value="">--- Select State ---</option>
@foreach($statelist as  $row2)
{
<option value="{{ $row2->state_id }}">{{ $row2->state_name }}</option>

}
@endforeach
</select>
</div>
</div>
<div class="col-md-4">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">District Name</label>
<input type="text" name="d_name" class="form-control" id="formrow-email-input">
<input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="formrow-email-input">
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