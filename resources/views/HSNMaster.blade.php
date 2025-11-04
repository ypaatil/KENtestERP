@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">HSN Master</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
               <li class="breadcrumb-item active">HSN Master</li>
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
            @if(isset($HSN))
            <form action="{{ route('HSN.update',$HSN) }}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="userId" class="form-control" id="userId" value="{{ Session::get('userId') }}" required>
               @method('put')
               @csrf    
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
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="cat_id" class="form-label">Category</label>
                        <select name="cat_id" class="form-select select2" id="cat_id" required>
                           <option value="">Select Category</option>
                           @foreach($Categorylist as  $row) 
                           <option value="{{ $row->cat_id }}" {{ $row->cat_id == $HSN->cat_id ? 'selected="selected"' : '' }}>{{ $row->cat_name }}</option> 
                           @endforeach
                        </select>
                     </div>
                  </div>  
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="hsn_code" class="form-label">HSN Code</label>
                        <input type="text" name="hsn_code" class="form-control" id="hsn_code" value="{{ $HSN->hsn_code }}" required>
                     </div>
                  </div>
               </div> 
               <div>
                  <button type="submit" class="btn btn-primary w-md">Submit</button>
               </div>
            </form>
            @else
            <form action="{{route('HSN.store')}}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="userId" class="form-control" id="userId" value="{{ Session::get('userId') }}" required>
               @csrf 
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="cat_id" class="form-label">Category</label>
                        <select name="cat_id" class="form-select select2" id="cat_id" required>
                           <option value="">Select Category</option>
                           @foreach($Categorylist as  $row) 
                           <option value="{{ $row->cat_id }}">{{ $row->cat_name }}</option> 
                           @endforeach
                        </select>
                     </div>
                  </div> 
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="hsn_code" class="form-label">HSN Code</label>
                        <input type="text" name="hsn_code" class="form-control" id="hsn_code" required>
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