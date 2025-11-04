@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">OCR Summary Report</h4>
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
            <form action="rptOCRSummary" method="GET" enctype="multipart/form-data">
               @csrf 
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                            <label for="job_status_id" class="form-label">Order Status</label>
                            <select name="job_status_id" class="form-control select2" id="job_status_id" >
                               <option value="">--Select--</option>
                               @foreach($JobStatusList as  $row)
                               <option value="{{ $row->job_status_id }}">{{ $row->job_status_name }}</option>
                               @endforeach
                            </select>
                      </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                            <label for="orderTypeId" class="form-label">Order Type</label>
                            <select name="orderTypeId" class="form-control select2" id="orderTypeId" >
                               <option value="">--Select--</option>
                               @foreach($OrderTypeList as  $row)
                               <option value="{{ $row->orderTypeId }}">{{ $row->order_type }}</option>
                               @endforeach
                            </select>
                      </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                            <label for="sales_order_no" class="form-label">Sales Order No.</label>
                            <select name="sales_order_no" class="form-control select2" id="sales_order_no" >
                               <option value="">--Select--</option>
                               @foreach($SalesOrderList as  $row)
                               <option value="{{ $row->sales_order_no }}">{{ $row->sales_order_no }}</option>
                               @endforeach
                            </select>
                      </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                            <label for="Ac_code" class="form-label">Buyer</label>
                            <select name="Ac_code" class="form-control select2" id="Ac_code" >
                               <option value="">--Select--</option>
                               @foreach($BuyerList as  $row)
                               <option value="{{ $row->ac_code }}">{{ $row->ac_short_name }}</option>
                               @endforeach
                            </select>
                      </div>
                  </div>
                  <div class="col-sm-2">
                     <label for="formrow-inputState" class="form-label"></label>
                     <div class="form-group">
                        <button type="submit" class="btn btn-primary w-md">Submit</button>
                     </div>
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
<!-- end row -->
@endsection