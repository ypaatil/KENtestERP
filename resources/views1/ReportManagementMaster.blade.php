@extends('layouts.master') 

@section('content')
<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
<h4 class="mb-sm-0 font-size-18">Report Management Master</h4>

<div class="page-title-right">
<ol class="breadcrumb m-0">
<li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
<li class="breadcrumb-item active">Report Management Master</li>
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

@if(isset($ReportList))
<form action="{{ route('ReportViewer.update',$ReportList) }}" method="POST">
<input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="userId">
@method('put')

@csrf 
<div class="row">

      <div class="col-md-4">
          <div class="mb-3">
           <label for="formrow-email-input" class="form-label">Module</label>
           <select class="form-control select2" name="moduleId" id="moduleId">
               <option>--Select Module--</option>
               @foreach($moduleList as $modules)
               {
                <option value="{{$modules->moduleId}}" {{ $modules->moduleId == $ReportList->moduleId ? 'selected="selected"' : '' }} >{{$modules->moduleName}}</option>
               }
               @endforeach
           </select>
        </div>
      </div>
      <div class="col-md-4">
          <div class="mb-3">
            <label for="formrow-email-input" class="form-label">Reports</label>
            <select class="form-control select2" name="form_code" id="form_code">
               <option>--Select Report--</option>
               @foreach($formList as $forms)
               {
                <option value="{{$forms->form_code}}"  {{ $forms->form_code == $ReportList->form_code ? 'selected="selected"' : '' }}>{{$forms->form_label}}</option>
               }
               @endforeach
            </select>
        </div>
      </div>
      <div class="col-md-4">
          <div class="mb-3">
           <label for="formrow-email-input" class="form-label">Description</label>
          <textarea name="description" class="form-control">{{$ReportList->description}}</textarea>
        </div>
      </div>
</div>
<button type="submit" class="btn btn-primary w-md">Submit</button>
</div>
</form>
@else
<form action="{{route('ReportViewer.store')}}" method="POST">
<input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="userId">
@csrf 
<div class="row">
       <div class="col-md-4">
          <div class="mb-3">
           <label for="formrow-email-input" class="form-label">Module</label>
            <select class="form-control select2" name="moduleId" id="moduleId">
               <option>--Select Module--</option>
               @foreach($moduleList as $modules)
               {
                <option value="{{$modules->moduleId}}">{{$modules->moduleName}}</option>
               }
               @endforeach
            </select>
        </div>
      </div>
      <div class="col-md-4">
          <div class="mb-3">
           <label for="formrow-email-input" class="form-label">Reports</label>
           <select class="form-control select2" name="form_code" id="form_code">
               <option>--Select Report--</option>
               @foreach($formList as $forms)
               {
                <option value="{{$forms->form_code}}">{{$forms->form_label}}</option>
               }
               @endforeach
            </select>
        </div>
      </div>
      <div class="col-md-4">
          <div class="mb-3">
           <label for="formrow-email-input" class="form-label">Description</label>
          <textarea name="description" class="form-control"></textarea>
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