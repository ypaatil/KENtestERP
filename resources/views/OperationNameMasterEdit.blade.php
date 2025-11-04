@extends('layouts.master') 
@section('content')
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
            @if(isset($OperationList))
            <form action="{{ route('OperationName.update',$OperationList) }}" method="POST" enctype="multipart/form-data">  
            <input type="hidden" name="operationId" class="form-control" id="operationId" value="{{ $OperationList->operationId}}"> 
            <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
               @method('put')
               @csrf 
              <div class="row">
                   <div class="col-md-4">
                      <div class="mb-3">
                         <label for="formrow-inputState" class="form-label">Main Style</label>
                         <select name="main_style_id" class="form-select" id="main_style_id"  >
                            <option value="">--Main Style--</option>
                            @foreach($MainStyleList as  $row) 
                                 <option value="{{ $row->mainstyle_id }}"   {{ $row->mainstyle_id == $OperationList->main_style_id ? 'selected="selected"' : '' }}  >{{ $row->mainstyle_name }}</option> 
                            @endforeach
                         </select>
                      </div>
                   </div>
                   <div class="col-md-4">
                      <div class="mb-3">
                         <label for="formrow-operation_name-input" class="form-label">Operation Name</label>
                         <input type="text" name="operation_name" class="form-control" id="formrow-operation_name-input" value="{{$OperationList->operation_name}}">
                         <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control"  >
                      </div>
                   </div>
                </div>
               <div class="col-sm-6">
                  <label for="formrow-inputState" class="form-label"></label>
                  <div class="form-group">
                     <button type="submit" class="btn btn-primary w-md" >Submit</button>
                     <a href="{{ Route('OperationName.index') }}" class="btn btn-warning w-md">Cancel</a>
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
<!-- end row -->
<script>
   
   
   function recalcIdcone()
   {
       $.each($("#footable_3 tr"),function (i,el){
       $(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
       })
   }
   
   
</script>
<!-- end row -->
@endsection