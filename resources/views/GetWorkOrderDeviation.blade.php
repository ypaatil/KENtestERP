@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Get Work Order Deviation Report</h4>
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
            <form action="{{route('rptWorkOrderDeviation')}}" method="POST" enctype="multipart/form-data">
               @csrf 
               <div class="row">
                   <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sales_order_no" class="form-label">Sales Order No.</label>
                        <select name="sales_order_no" class="form-control select2" id="sales_order_no">
                           <option value="">--Select--</option>
                           @foreach($SalesOrderList as $row)
                             <option value="{{$row->sales_order_no}}">{{$row->sales_order_no}}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="cat_id" class="form-label">Vendor Name</label>
                        <select name="vendorId" class="form-control select2" id="vendorId"  onchange="GetPlanLineList(this.value);" >
                           <option value="">--Vendor--</option>
                           @foreach($Ledger as $row)
                             <option value="{{$row->ac_code}}">{{$row->ac_name}}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                   <div class="col-md-2">
                     <div class="mb-3">
                        <label for="vw_code" class="form-label">Work Order No.</label>
                        <select name="vw_code" class="form-control select2" id="vw_code">
                           <option value="">--Select--</option>
                           @foreach($vendorWorkOrderList as $row)
                             <option value="{{$row->vw_code}}">{{$row->vw_code}}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                   <div class="col-md-2">
                     <div class="mb-3">
                        <label for="color_id" class="form-label">Garment Color</label>
                        <select name="color_id" class="form-control select2" id="color_id">
                           <option value="0">--Select--</option>
                           @foreach($colorList as $row)
                             <option value="{{$row->color_id}}">{{$row->color_name}}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="line_id" class="form-label">Line No.</label>
                        <select name="line_id" class="form-control select2" id="line_id" >
                            <option value="0">--Line No--</option>
                            @foreach($lineList as $row)
                             <option value="{{$row->line_id}}">{{$row->line_name}}</option>
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