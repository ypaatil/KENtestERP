@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Form Master</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
               <li class="breadcrumb-item active">Form Master</li>
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
            <h4 class="card-title mb-4">Form Grid Layout</h4>
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
            @if(isset($form))
            <form action="{{ route('Form.update',$form) }}" method="POST">
               @method('put')
               @csrf 
               <div class="row">
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="form_label" class="form-label">Form Lable</label>
                        <input type="text" name="form_label" class="form-control" id="form_label" value="{{ $form->form_label }}">
                        <input type="hidden" name="user_id" value="{{ Session::get('userId')}}" class="form-control" id="user_id">
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="form_name" class="form-label">Form Route</label>
                        <input type="text" name="form_name" class="form-control" id="form_name" value="{{ $form->form_name }}">
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="head_id" class="form-label">Head Id</label>
                        <input type="text" name="head_id" class="form-control" id="head_id" value="{{ $form->head_id }}">
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="seq_no" class="form-label">Sequence No.</label>
                        <input type="text" name="seq_no" class="form-control" id="seq_no" value="{{ $form->seq_no }}" onchange="CheckFormSequence();">
                     </div>
                  </div>
               </div>
               <div>
                  <button type="submit" class="btn btn-primary w-md">Submit</button>
               </div>
            </form>
            @else
            <form action="{{route('Form.store')}}" method="POST">
               @csrf 
               <div class="row">
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="form_label" class="form-label">Form Lable</label>
                        <input type="text" name="form_label" class="form-control" id="form_label">
                        <input type="hidden" name="user_id" value="{{ Session::get('userId')}}" class="form-control" id="user_id">
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="form_name" class="form-label">Form Route</label>
                        <input type="text" name="form_name" class="form-control" id="form_name">
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="head_id" class="form-label">Head Id</label>
                        <input type="text" name="head_id" class="form-control" id="head_id">
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="seq_no" class="form-label">Sequence No.</label>
                        <input type="text" name="seq_no" class="form-control" id="seq_no" onchange="CheckFormSequence();">
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

<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script> 
<!-- end row -->
<script>

    function CheckFormSequence()
    {
        var head_id = $("#head_id").val(); 
        var seq_no = $("#seq_no").val(); 
        
        $.ajax({
           type: "GET",
           dataType:"json",
           url: "{{ route('CheckFormSequence') }}",
           data:{'head_id':head_id,'seq_no':seq_no},
           success: function(data)
           { 
               if(data.count > 0)
               {
                    alert("Already Assigned this sequence to - "+data.form_label);
                    $("#seq_no").val(seq_no);
               }
               else
               { 
                    $("#seq_no").val(seq_no);
               }
           }
        });
     
    }
   
</script>
@endsection