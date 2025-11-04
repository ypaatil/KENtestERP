@extends('layouts.master') 

@section('content')
<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
<h4 class="mb-sm-0 font-size-18">Line Master</h4>

<div class="page-title-right">
<ol class="breadcrumb m-0">
<li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
<li class="breadcrumb-item active">Line Master</li>
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
<h4 class="card-title mb-4">Line</h4>
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

@if(isset($LineList))
<form action="{{ route('Line.update',$LineList) }}" method="POST">
@method('put')

@csrf 
<div class="row">

<div class="col-md-4">
<div class="mb-3">
<label for="Ac_code" class="form-label">Party Name</label>
<select name="Ac_code" class="form-select select2" id="Ac_code">
<option value="">--Type--</option>
@foreach($Ledger as  $row)
{
<option value="{{ $row->ac_code }}"

{{ $row->ac_code == $LineList->Ac_code ? 'selected="selected"' : '' }}

>{{ $row->ac_name }}</option>

}
@endforeach
</select>
</div>
</div>

<div class="col-md-4">
<div class="mb-3">
<label for="formrow-line_name-input" class="form-label"> Line</label>
<input type="text" name="line_name" class="form-control" id="formrow-line_name-input" value="{{ $LineList->line_name }}">
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="userId">
<input type="hidden" name="created_at" class="form-control" id="created_at" value="{{ $LineList->created_at }}">
   
</div>
</div>
 
 

</div>
<div class="row"> 
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">&nbsp;</label>
<button type="submit" class="btn btn-primary w-md">Submit</button>
</div>
</div>



 
</form>


@else
<form action="{{route('Line.store')}}" method="POST">
@csrf 
<div class="row">

<div class="row">

<div class="col-md-4">
<div class="mb-3">
<label for="Ac_code" class="form-label">Party Name</label>
<select name="Ac_code" class="form-select select2" id="Ac_code">
<option value="">--Select Party--</option>
@foreach($Ledger as  $row)
{
<option value="{{ $row->ac_code }}">{{ $row->ac_name }}</option>

}
@endforeach
</select>
</div>
</div>

<div class="col-md-4">
<div class="mb-3">
<label for="formrow-line_name-input" class="form-label"> Line Name</label>
<input type="text" name="line_name" class="form-control" id="formrow-line_name-input" value="">
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="userId">

   
</div>
</div>
 
</div>
<div class="row"> 
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">&nbsp;</label>
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