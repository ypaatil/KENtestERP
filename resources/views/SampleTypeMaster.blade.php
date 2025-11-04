@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Sample Type Master</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
               <li class="breadcrumb-item active">Sample Type Master</li>
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
            @if(isset($SampleType))
            <form action="{{ route('SampleType.update',$SampleType) }}" method="POST">
               @method('put')
               @csrf 
               <div class="row">
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="sample_type_name" class="form-label">Sample Type</label>
                        <input type="text" name="sample_type_name" class="form-control" id="sample_type_name" value="{{ $SampleType->sample_type_name }}">
                        <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="userId">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="dept_type_id" class="form-label">Department Type</label>
                        <select name="dept_type_id" class="form-select" id="dept_type_id">
                           <option value="">--- Select ---</option>
                           @foreach($DepartmentTypelist as  $row)
                                <option value="{{ $row->dept_type_id }}" {{ $row->dept_type_id == $SampleType->dept_type_id ? 'selected="selected"' : '' }}>{{ $row->dept_type_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
               </div>
                <div class="row">
                   <div class="col-md-6">
                      <div class="mb-3">
                         <label for="formrow-email-input" class="form-label">&nbsp;</label>
                         <button type="submit" class="btn btn-primary w-md" >Submit</button>
                         <a href="{{ Route('SampleType.index') }}"  class="btn btn-warning w-md">Cancel</a>
                      </div>
                   </div>
                </div> 
            </form>
            @else
            <form action="{{route('SampleType.store')}}" method="POST">
               @csrf 
               <div class="row">
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="sample_type_name" class="form-label">Sample Type</label>
                        <input type="text" name="sample_type_name" class="form-control" id="sample_type_name">
                        <input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="userId">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="dept_type_id" class="form-label">Department Type</label>
                        <select name="dept_type_id" class="form-select" id="dept_type_id">
                           <option value="">--- Select ---</option>
                           @foreach($DepartmentTypelist as  $row)
                           {
                           <option value="{{ $row->dept_type_id }}">{{ $row->dept_type_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
               </div>
                <div class="row">
                   <div class="col-md-6">
                      <div class="mb-3">
                         <label for="formrow-email-input" class="form-label">&nbsp;</label>
                         <button type="submit" class="btn btn-primary w-md" >Submit</button>
                         <a href="{{ Route('SampleType.index') }}"  class="btn btn-warning w-md">Cancel</a>
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