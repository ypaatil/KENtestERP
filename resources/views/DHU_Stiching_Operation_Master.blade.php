@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Stitching Defect Master</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
               <li class="breadcrumb-item active">Stitching Defect Master</li>
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
            @if(isset($dhuList))
            <form action="{{ route('StitchingDefect.update',$dhuList) }}" method="POST">
               @method('put')
               @csrf 
               <div class="row">
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Stitching Defect Name</label>
                        <input type="hidden" name="dhu_so_Id" class="form-control" id="dhu_so_Id" value="{{$dhuList->dhu_so_Id}}" >
                        <input type="text" name="dhu_so_Name" class="form-control" id="dhu_so_Name" value="{{$dhuList->dhu_so_Name}}" >
                        <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="userId">
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="dhu_so_marathi_Name" class="form-label">Stitching Defect Marathi Name</label> 
                        <input type="text" name="dhu_so_marathi_Name" class="form-control" id="dhu_so_marathi_Name" value="{{$dhuList->dhu_so_marathi_Name}}" > 
                     </div>
                  </div>
               </div>
               <div>
                  <button type="submit" class="btn btn-primary w-md">Submit</button>
                  <a href="{{route('StitchingDefect.index')}}" class="btn btn-danger w-md">Cancel</a>
               </div>
            </form>
            @else
            <form action="{{route('StitchingDefect.store')}}" method="POST">
               @csrf 
               <div class="row">
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Stitching Defect Name</label>
                        <input type="text" name="dhu_so_Name" class="form-control" id="dhu_so_Name">
                        <input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="userId">
                     </div>
                  </div>
               </div>
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="dhu_so_marathi_Name" class="form-label">Stitching Defect Marathi Name</label> 
                        <input type="text" name="dhu_so_marathi_Name" class="form-control" id="dhu_so_marathi_Name" value=""> 
                     </div>
                  </div>
               <div>
                  <button type="submit" class="btn btn-primary w-md">Submit</button>
                  <a href="{{route('StitchingDefect.index')}}" class="btn btn-danger w-md">Cancel</a>
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