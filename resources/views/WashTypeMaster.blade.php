@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Wash Type Master</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
               <li class="breadcrumb-item active">Wash Type Master</li>
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
   @if(isset($WashTypeList))
   <form action="{{ route('WashType.update',$WashTypeList) }}" method="POST">
      @method('put')
      @csrf 
      <div class="row">
         <div class="col-md-4">
            <div class="mb-3">
               <label for="WashTypeName" class="form-label"> Wash Type</label>
               <input type="text" name="WashTypeName" class="form-control" id="WashTypeName" value="{{ $WashTypeList->WashTypeName }}">
               <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="userId">
               <input type="hidden" name="created_at" class="form-control" id="created_at" value="{{ $WashTypeList->created_at }}">
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
   <form action="{{route('WashType.store')}}" method="POST">
   @csrf 
   <div class="row">
   <div class="col-md-4">
   <div class="mb-3">
   <label for="WashTypeName" class="form-label"> Wash Type</label>
   <input type="text" name="WashTypeName" class="form-control" id="WashTypeName" value="">
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
   <!-- end card -->
   </div>
   <!-- end col -->
   <!-- end col -->
</div>
<!-- end row -->
<!-- end row -->
<!-- end row -->
@endsection