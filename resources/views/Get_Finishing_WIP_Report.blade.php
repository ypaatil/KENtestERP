@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Get Finishing WIP Report</h4>
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
            <form action="{{route('rptFinishingWIP')}}" method="GET" enctype="multipart/form-data">
               @csrf 
                <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="job_status_id" class="form-label">PO Status</label>
                        <select name="job_status_id[]" id="job_status_id" class="form-select select2" required multiple >
                             <option value="">--Select--</option>
                             <option value="1">Open</option>
                             <option value="2">Close</option>
                             <option value="5">Pending For OCR</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="order_type" class="form-label">Order Type</label>
                        <select name="order_type[]" class="form-select select2"  id="order_type" required multiple>
                           <option value="">--Select--</option>
                           <option value="1">Fresh</option>
                           <option value="2">Stock</option>
                           <option value="3">Job Work</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="vendorId" class="form-label">Vendor Name</label>
                        <select name="vendorId" class="form-control select2" id="vendorId" onchange="GetSalesOrderNoList();" required>
                           <option value="">--Select--</option>
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
                        <label for="vendorId" class="form-label">Sales Order No</label>
                        <select name="sales_order_no" class="form-control select2" id="sales_order_no" required multiple onchange="selectSalesOrder(this.value);">
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
    
    function selectSalesOrder(ele)
    { 
           $("#sales_order_no option:first").prop("selected", true);

          // Select all options
          $("#sales_order_no option").prop("selected", true);
    }
    

    function GetSalesOrderNoList()
    {
        var vendorId = $('#vendorId').val();  
        var job_status_id = $('#job_status_id').val();  
        var order_type = $('#order_type').val();  
      
        $.ajax({ 
            dataType: "json",
            type: "GET",
            data: {'vendorId':vendorId,'job_status_id':job_status_id,'order_type':order_type},
            url: "{{ route('GetSalesOrderNoList') }}",
            success: function(data)
            {
                $('#sales_order_no').html(data.html);
            }
        });
    }
    
</script>
<!-- end row -->
@endsection