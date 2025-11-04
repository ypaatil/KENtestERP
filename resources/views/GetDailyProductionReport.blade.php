@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Sale Report</h4>
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
            <form action="/DailyProductionReport" method="GET" enctype="multipart/form-data">
               @csrf 
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="fdate" class="form-label">From date</label>
                        <input type="date" name="fdate" class="form-control" id="fdate" value="" required>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="tdate" class="form-label">To Date</label>
                        <input type="date" name="tdate" class="form-control" id="tdate" value="{{date('Y-m-d')}}" required>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="vendorId" class="form-label">Vendor Name</label>
                        <select name="vendorId" class="form-control select2" id="vendorId" onchange="GetPlanLineList(this.value);"  >
                           <option value="">--Vendor--</option>
                           @foreach($LedgerList as  $row)
                           {
                           <option value="{{ $row->ac_code }}">{{ $row->ac_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="line_id" class="form-label">Line</label>
                        <select name="line_id" class="form-control select2" id="line_id"    >
                           <option value="">--Line No.--</option>
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

  function GetPlanLineList(ele)
  {
        $.ajax({
            dataType: "json",
            url: "{{ route('GetPlanLineList') }}",
            data:{'Ac_code':ele},
            success: function(data){
            $('#line_id').html(data.html);
           }
        });
   }
   
</script>
<!-- end row -->
@endsection