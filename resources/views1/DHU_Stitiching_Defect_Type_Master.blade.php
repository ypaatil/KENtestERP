@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">DHU - Stitiching Defect Type</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
               <li class="breadcrumb-item active">DHU - Stitiching Defect Type</li>
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
            <form action="{{ route('DHUStitichingDefectType.update',$dhuList) }}" method="POST">
               @method('put')
               @csrf 
               <div class="row">
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Main Style Name</label>
                        <select name="mainstyle_id" class="form-control select2" id="mainstyle_id">
                            <option>--Select--</option>
                            @foreach($MainStyleList as $row)
                            <option value="{{$row->mainstyle_id}}" {{ $row->mainstyle_id == $dhuList->mainstyle_id ? 'selected="selected"' : '' }}>{{$row->mainstyle_name}}</option>
                            @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">DHU - Stitiching Operation Name</label>
                        <select name="dhu_so_Id" class="form-control select2" id="dhu_so_Id">
                            <option>--Select--</option>
                            @foreach($dhuOperationList as $row)
                            <option value="{{$row->dhu_so_Id}}" {{ $row->dhu_so_Id == $dhuList->dhu_so_Id ? 'selected="selected"' : '' }}>{{$row->dhu_so_Name}}</option>
                            @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">DHU - Stitiching Defect Type Name</label>
                        <input type="hidden" name="dhu_sdt_Id" class="form-control" id="dhu_sdt_Id" value="{{$dhuList->dhu_sdt_Id}}" >
                        <input type="text" name="dhu_sdt_Name" class="form-control" id="dhu_sdt_Name" value="{{$dhuList->dhu_sdt_Name}}" >
                        <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="userId">
                     </div>
                  </div>
               </div>
               <div>
                  <button type="submit" class="btn btn-primary w-md">Submit</button>
                  <a href="{{route('DHUStitichingDefectType.index')}}" class="btn btn-danger w-md">Cancel</a>
               </div>
            </form>
            @else
            <form action="{{route('DHUStitichingDefectType.store')}}" method="POST">
               @csrf 
               <div class="row">
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Main Style Name</label>
                        <select name="mainstyle_id" class="form-control select2" id="mainstyle_id">
                            <option>--Select--</option>
                            @foreach($MainStyleList as $row)
                            <option value="{{$row->mainstyle_id}}" >{{$row->mainstyle_name}}</option>
                            @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">DHU - Stitiching Operation Name</label>
                        <select name="dhu_so_Id" class="form-control select2" id="dhu_so_Id">
                            <option>--Select--</option>
                            @foreach($dhuOperationList as $row)
                            <option value="{{$row->dhu_so_Id}}">{{$row->dhu_so_Name}}</option>
                            @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">DHU - Stitiching Defect Type Name</label>
                        <input type="text" name="dhu_sdt_Name" class="form-control" id="dhu_sdt_Name">
                        <input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="userId">
                     </div>
                  </div>
               </div>
               <div>
                  <button type="submit" class="btn btn-primary w-md">Submit</button>
                  <a href="{{route('DHUStitichingDefectType.index')}}" class="btn btn-danger w-md">Cancel</a>
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