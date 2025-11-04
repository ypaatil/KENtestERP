@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Spares Return Material Status</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
               <li class="breadcrumb-item active">Spares Return Material Status</li>
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
            <h4 class="card-title mb-4">Spares Return Material Status</h4>
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
            @if(isset($SparesReturnList))
            <form action="{{ route('SparesReturnMaterialStatus.update',$SparesReturnList) }}" method="POST">
               @method('put')
               @csrf 
               <div class="row">
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="spare_return_material_status_name" class="form-label">Category Name</label>
                        <input type="text" name="spare_return_material_status_name" class="form-control" id="spare_return_material_status_name" value="{{ $SparesReturnList->spare_return_material_status_name }}">
                        <input type="hidden" name="spare_return_material_status_id" value="{{ $SparesReturnList->spare_return_material_status_id }}" class="form-control" id="spare_return_material_status_id ">
                        <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="userId">
                     </div>
                  </div>
               </div>
               <div>
                  <button type="submit" class="btn btn-primary w-md">Submit</button>
               </div>
            </form>
            @else
            <form action="{{route('SparesReturnMaterialStatus.store')}}" method="POST">
               @csrf 
               <div class="row">
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="spare_return_material_status_name" class="form-label">Status Name</label>
                        <input type="text" name="spare_return_material_status_name" class="form-control" id="spare_return_material_status_name">
                        <input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="userId">
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