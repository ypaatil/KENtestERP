@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Perticular Edit</h4>
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
            @if(isset($PerticularMaster))
            <form action="{{ route('Perticular.update',$PerticularMaster) }}" method="POST" enctype="multipart/form-data" id="frmData">
               @method('put')
               @csrf
                  <input type="hidden" name="perticular_id" class="form-control" id="perticular_id" value="{{ $PerticularMaster->perticular_id }}">
                   <div class="row"> 
                      <div class="col-md-4">
                         <div class="mb-3">
                            <label for="perticular_name" class="form-label">Perticular Name</label>
                            <input type="text" name="perticular_name" class="form-control" id="perticular_name" value="{{$PerticularMaster->perticular_name}}">
                         </div>
                      </div>
                      <div class="col-md-4">
                         <div class="mb-3">
                            <label for="perticular_code" class="form-label">Perticular Code</label>
                            <input type="text" name="perticular_code" class="form-control" id="perticular_code" value="{{$PerticularMaster->perticular_code}}">
                         </div>
                      </div>
                       <div class="col-sm-6">
                          <label for="formrow-inputState" class="form-label"></label>
                          <div class="form-group">
                             <button type="submit" class="btn btn-primary w-md" id="Submit" >Update</button>
                             <a href="{{ Route('Perticular.index') }}" class="btn btn-warning w-md">Cancel</a>
                          </div>
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
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
@endsection