@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Get WIP Total Report</h4>
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
            <form action="{{route('rptTotalWIPReport')}}" method="POST" enctype="multipart/form-data">
               @csrf 
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="job_status_id" class="form-label">PO Status</label>
                        <select name="job_status_id" class="form-select select2" >
                             <option value="0">--All--</option>
                             <option value="1">Open</option>
                             <option value="2">Close</option>
                             <option value="5">Pending For OCR</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="order_type" class="form-label">Order Type</label>
                        <select name="order_type" class="form-select select2"  id="order_type" required>
                           <option value="0">--All--</option>
                           <option value="1">Fresh</option>
                           <option value="2">Stock</option>
                           <option value="3">Job Work</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="currentDate" class="form-label">Date</label>
                        <input type="date" class="form-control" name="currentDate" value="{{date('Y-m-d')}}">
                     </div>
                  </div>
               </div>
               <div class="col-sm-2">
                  <label for="formrow-inputState" class="form-label"></label>
                  <div class="form-group">
                     <button type="submit" class="btn btn-primary w-md">Submit</button>
                  </div>
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
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<!-- end row -->
<script>
</script>
<!-- end row -->
@endsection