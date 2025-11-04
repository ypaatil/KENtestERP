@extends('layouts.master') 
@section('content')
<style>

    .navbar-brand-box
    {
        width: 266px !important;
    }
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Style No Master</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
               <li class="breadcrumb-item active">Style No Master</li>
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
            <h4 class="card-title mb-4">Style No</h4>
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
            @if(isset($StyleNoList))
            <form action="{{ route('StyleNo.update',$StyleNoList) }}" method="POST" enctype="multipart/form-data">
               @method('put')
               @csrf 
               <div class="row">
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="Ac_code" class="form-label">Buyer</label>
                        <select name="Ac_code" class="form-control" id="Ac_code" required  >
                           <option value="">--Select Buyer--</option>
                           @foreach($Ledger as  $row)
                                <option value="{{ $row->ac_code }}"   {{ $row->ac_code == $StyleNoList->Ac_code ? 'selected="selected"' : '' }} >{{ $row->ac_short_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="style_no" class="form-label">Style No</label>
                        <input type="text" name="style_no" class="form-control" id="style_no" value="{{ $StyleNoList->style_no }}">
                        <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="userId"> 
                     </div>
                  </div> 
               </div>
               <div class="col-md-3">
                  <div class="mb-3">
                     <label for="" class="form-label"></label>
                     <button type="submit" class="btn btn-primary w-md">Submit</button>
                  </div>
               </div>
            </form>
            @else
            <form action="{{route('StyleNo.store')}}" method="POST" enctype="multipart/form-data">
               @csrf 
               <div class="row">
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="Ac_code" class="form-label">Buyer</label>
                        <select name="Ac_code" class="form-control" id="Ac_code" required  >
                           <option value="">--Select Buyer--</option>
                           @foreach($Ledger as  $row)
                                <option value="{{ $row->ac_code }}">{{ $row->ac_short_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="style_no" class="form-label">Style No</label>
                        <input type="text" name="style_no" class="form-control" id="style_no" value="">
                        <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="userId">
                     </div>
                  </div> 
               </div>
               <div class="col-md-3">
                  <div class="mb-3">
                     <label for="" class="form-label"></label>
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
@endsection