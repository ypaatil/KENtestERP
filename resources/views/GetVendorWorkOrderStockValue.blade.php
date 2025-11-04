@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Get VENDOR WORK ORDER STOCK VALUE REPORT</h4>
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
            <form action="VendorWorkOrderStock" method="POST" enctype="multipart/form-data">
               @csrf 
               <div class="row">
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="vendorId" class="form-label">Vendor Name</label>
                        <select name="vendorId" class="form-control select2" id="vendorId" onchange="GetSalesOrderNoList(this.value);" >
                           <option value="All">All</option>
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
                        <label for="line_id" class="form-label">Sales Order No.</label>
                        <select name="sales_order_no" class="form-control select2" id="sales_order_no" required>
                            <option value="">--Sales Order No--</option>
                          
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="endflag" class="form-label">Order Status</label>
                        <select name="endflag" class="form-control select2" id="endflag" required>
                           <option value="">--Order Status--</option>
                           @foreach($JobStatusList as  $row)
                           <option value="{{ $row->job_status_id }}">{{ $row->job_status_name }}</option>
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
      GetSalesOrderNoList('All');
      function GetSalesOrderNoList(ele)
      {
            $.ajax({
                dataType: "json",
                url: "{{ route('SalesOrderNoList') }}",
                data:{'vendorId':ele},
                success: function(data){
                $('#sales_order_no').html(data.html);
               }
            });
     }
   
     function GetWorkOrderList(sales_order_no)
     {
        var vendorId =  $('#vendorId').val();
        $.ajax({
            dataType: "json",
            url: "{{ route('VendorAllWorkOrders') }}",
            data:{'sales_order_no':sales_order_no,vendorId:vendorId},
            success: function(data){
            $("#vw_code").html(data.html);
            }
        });
     }
</script>
<!-- end row -->
@endsection