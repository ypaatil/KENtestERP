@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">GET DEVIATION - PPC REPORT</h4>
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
            <form action="rptDeviationPPC" method="POST" enctype="multipart/form-data">
               @csrf 
               <div class="row">
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="vendorId" class="form-label">Vendor Name</label>
                        <select name="vendorId" class="form-control select2" id="vendorId" >
                           <option value="">--Vendor--</option>
                           @foreach($Ledger as  $rowvendor)
                           {
                           <option value="{{ $rowvendor->ac_code }}">{{ $rowvendor->ac_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                   <div class="col-md-4">
                     <div class="mb-3">
                        <label for="month" class="form-label">Month</label>
                        <select name="month" class="form-control select2" id="month" >
                           <option value="">--Select--</option>
                           @php 
                                 $no = 1;
                           @endphp
                           @foreach($monthArr as $month)
                           {
                               <option value="{{ $no++ }}">{{ $month}}</option>
                           }
                           @endforeach
                        </select>
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