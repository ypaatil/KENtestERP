@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Operation Name Master</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
               <li class="breadcrumb-item active">Operation Name Master</li>
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
         <h4 class="card-title mb-4">Operation Name Master</h4>
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
         <form action="{{route('OperationName.store')}}" method="POST">
            @csrf 
            <div class="row">
               <div class="col-md-4">
                  <div class="mb-3">
                     <label for="formrow-inputState" class="form-label">Main Style</label>
                     <select name="mainstyle_id" class="form-select" id="mainstyle_id"  >
                        <option value="">--Main Style--</option>
                        @foreach($MainStyleList as  $row)
                        {
                        <option value="{{ $row->mainstyle_id }}">{{ $row->mainstyle_name }}</option>
                        }
                        @endforeach
                     </select>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="mb-3">
                     <label for="formrow-operation_name-input" class="form-label">Operation Name</label>
                     <input type="text" name="operation_name" class="form-control" id="formrow-operation_name-input" value="">
                     <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control"  >
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6">
                  <div class="mb-3">
                     <label for="formrow-email-input" class="form-label">&nbsp;</label>
                     <button type="submit" class="btn btn-primary w-md">Submit</button>
                     <a href="{{ Route('OperationName.index') }}"  class="btn btn-warning w-md">Cancel</a>
                  </div>
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
<!-- end row -->
<!-- end row -->
@endsection