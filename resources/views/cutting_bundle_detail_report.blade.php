@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp
<style>
    .text-right
    {
        text-align:right;
    }
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Cutting Bundle  Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">MIS Reports</a></li>
               <li class="breadcrumb-item active">Cutting Bundle  Report</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body"> 
                <form action="/cutting_bundle_report" method="GET" enctype="multipart/form-data">
                    <div class="row"> 
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="fromDate" class="form-label">From date</label>
                                <input type="date" name="fromDate" class="form-control" id="fromDate" value="{{ isset($fromDate) ? $fromDate : date('Y-m-01')}}" required> 
                            </div>
                        </div> 
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="toDate" class="form-label">To Date</label>
                                <input type="date" name="toDate" class="form-control" id="toDate" value="{{ isset($toDate) ? $toDate : date('Y-m-d')}}" required>
                            </div>
                        </div> 
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="sales_order_no" class="form-label">Unit</label>
                                <select class="form-control select2" id="vendorId" name="vendorId">
                                    <option value="">--Select--</option>
                                     @foreach($unitList as $fetch_unit) 
                                       <option value="{{$fetch_unit->ac_code }}" >{{$fetch_unit->ac_name}}</option>      
                                     @endforeach
                                </select>
                            </div>
                        </div>    
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="sales_order_no" class="form-label">Order No.</label>
                                <select class="form-control select2" id="sales_order_no" name="sales_order_no" onchange="GetBuyerPurchaseData(this.value);">
                                    <option value="">--Select--</option>
                                     @foreach($salesOrderList as $sales) 
                                       <option value="{{$sales->tr_code}}" {{ $sales->tr_code == $sales_order_no ? 'selected="selected"' : '' }} >{{$sales->tr_code}}</option>      
                                     @endforeach
                                </select>
                            </div>
                        </div> 
                        
                         <div class="col-md-2">
                            <div class="mb-3">
                                <label for="color_id" class="form-label">Color.</label>
                                <select class="form-control select2" id="color_id" name="color_id">
                                    <option value="">--Select--</option>
                                  
                                </select>
                            </div>
                        </div> 
                        
                              <div class="col-md-2">
                            <div class="mb-3">
                                <label for="bundleNo" class="form-label">Bundle No.</label>
                                <input type="text" name="bundleNo" class="form-control" id="bundleNo" value="" >
                            </div>
                        </div> 
            
                        <div class="col-md-2 mt-2">
                            <label for="formrow-inputState" class="form-label"></label>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary w-md">Search</button>
                                <a href="/cutting_bundle_report" class="btn btn-danger w-md">Cancel</a>
                            </div>
                        </div>
                    </div> 
                </form> 
            </div>
        </div>
    </div>
</div>
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <div class="table-responsive">
               <table  id="datatable-buttons" class="table table-bordered nowrap w-100">
                  <thead>
                     <tr>
                        <th class="text-center">Sr No</th>
                        <th nowrap class="text-center">Cutting ID</th> 
                        <th nowrap class="text-center">Date</th>
                        <th nowrap class="text-center">Unit</th> 
                        <th nowrap class="text-center">Sales Order No.</th>
                        <th nowrap class="text-center">Garment Name</th> 
                        <th nowrap class="text-center">Lot No</th> 
                        <th nowrap class="text-center">Bundle No</th> 
                        <th nowrap class="text-center">Size</th>  
                        <th nowrap class="text-center">Cut Panel Qty</th> 
                    
                     
                        <th nowrap class="text-center">Track Code</th> 
                     </tr>
                  </thead>
                  <tbody>
                        @php
                            $srno = 1; 
                            $total_qty1 = 0;
                            $total_amount1 = 0;
                        @endphp
                        @foreach($data as $emp)      
                        <tr>
                            <td nowrapclass="text-center">{{$srno++}}</td>
                             <td nowrap class="text-center">{{$emp->cuttingEntryId}}</td>
                            <td nowrap>{{date("d-m-Y", strtotime($emp->cuttingEntryDate))}}</td>
                             <td nowrap class="text-center">{{$emp->ac_short_name}}</td>
                            <td nowrap class="text-center">{{$emp->sales_order_no}}</td>
                            <td nowrap >{{$emp->color_name}}</td>  
                            <td nowrap class="text-right">{{$emp->lotNo}}</td>  
                            <td nowrap class="text-right">{{$emp->bundleNo}}</td> 
                            <td nowrap class="text-center">{{$emp->size_name}}</td>
                            <td nowrap class="text-right">{{ number_format($emp->stiching_qty, 0, '.', ',')}}</td> 
                        
                            <td nowrap>{{$emp->bundle_track_code}}</td>
                        </tr>
                        @php
                            $total_qty1 += $emp->stiching_qty;
                         
                             
                        @endphp
                        @endforeach 
                  </tbody> 
                  <tfoot>
                      <tr>
                          <th colspan="9" class="text-right">Total : </th>
                          <th class="text-right">{{number_format($total_qty1, 0, '.', ',')}}</th>
                          
                        
                      </tr>
                  </tfoot>
               </table>
            </div>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>


<script>
   function GetBuyerPurchaseData(sales_order_no)
   {
       
        
        $.ajax({
          type: "GET",
          dataType:"json",
          url: "{{ route('GetBuyerPurchaseData') }}",
          data:{'sales_order_no':sales_order_no},
          success: function(data)
          { 
             
               $('#color_id').html(data.colorHtml); 
           
          }
        });
   }    
    
</script>

@endsection