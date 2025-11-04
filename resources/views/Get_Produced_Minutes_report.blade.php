@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Get Produced Minutes Report</h4>
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
            <form action="{{route('rptProducedMinutes')}}" method="POST" enctype="multipart/form-data">
               @csrf 
               <div class="row">
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="fromDate" class="form-label">From Date</label>
                        <input type="date" name="fromDate" class="form-control" id="fromDate" value="{{date('Y-m-01')}}"> 
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="toDate" class="form-label">To Date</label>
                        <input type="date" name="toDate" class="form-control" id="toDate" value="{{date('Y-m-d')}}"> 
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