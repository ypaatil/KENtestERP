@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Get Color Wise BOM Details</h4>
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
            <form action="rptColorWiseBOMDetail" method="POST" enctype="multipart/form-data">
               @csrf 
               <div class="row">
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="sales_order_no" class="form-label">BOM</label>
                        <select name="sales_order_no" class="form-control select2" id="sales_order_no" onchange="GetBOMWiseColorList(this.value);" >
                           <option value="">--Select--</option>
                           @foreach($bomList as  $bom)
                           {
                           <option value="{{ $bom->sales_order_no }}">{{ $bom->bom_code }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="color_id" class="form-label">Color</label>
                        <select name="color_id" class="form-control select2" id="color_id"    >
                           <option value="">--Select--</option>
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
  function GetBOMWiseColorList(sales_order_no)
  {
      
        $.ajax({
            dataType: "json",
            url: "{{ route('GetBOMWiseColorList') }}",
            data:{'sales_order_no':sales_order_no},
            success: function(data)
            {
                $('#color_id').html(data.html);
            }
        });
   }
</script>
<!-- end row -->
@endsection