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
         <h4 class="mb-sm-0 font-size-18">Production Pending Bundle No.</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">PCs Rate Prod.</a></li>
               <li class="breadcrumb-item active">Production Pending Bundle No.</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body"> 
                <form action="/bundle_pending_for_production" method="GET" enctype="multipart/form-data">
                    <div class="row"> 
    
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="sales_order_no" class="form-label">Order No.</label>
                                <select class="form-control select2" id="sales_order_no" name="sales_order_no" onchange="GetBuyerPurchaseData(this.value);get_styles(this.value);">
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
                                     <div class="col-md-3">
                            <div class="mb-3">
                                <label for="employeeCode" class="form-label">Style</label>
                                <select class="form-control select2" id="mainstyle_id" name="mainstyle_id">
                                    <option value="">--Select--</option>
                                    
                                </select>
                            </div>
                        </div>
                                      <div class="col-md-2">
                            <div class="mb-3">
                                <label for="sales_order_no" class="form-label">Unit</label>
                                <select class="form-control select2" id="vendorId" name="vendorId">
                                    <option value="">--Select--</option>
                                     @foreach($unitList as $fetch_unit) 
                                       <option value="{{$fetch_unit->ac_code }}" {{ $fetch_unit->ac_code == $vendorId ? 'selected="selected"' : '' }} >{{$fetch_unit->ac_name}}</option>      
                                     @endforeach
                                </select>
                            </div>
                        </div>   
 
                        
                        <div class="col-md-2 mt-2">
                            <label for="formrow-inputState" class="form-label"></label>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary w-md">Search</button>
                                <a href="/bundle_pending_for_production" class="btn btn-danger w-md">Cancel</a>
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
             <button class="btn btn-info" onclick="GoToPrint();" style="margin-left: 20%;"> Print</button>
               <table  id="datatable-buttons" class="table table-bordered nowrap w-100">
                  <thead>
                     <tr>
                        <th class="text-center">Bundle No</th>
                        
                        @foreach($operationList as $rowOP)
                         <th nowrap class="text-center">{{ $rowOP->operation_name }}</th>
                        @endforeach
                       
                        <th nowrap class="text-center">Total</th>  
                       
                     </tr>
                  </thead>
                  <tbody>
                        @php
                            $srno = 1; 
                            $total_qty1 = 0;
                            $total_amount1 = 0;
                            $total_qty=0;
                            
                            
                           
                        
            $filter = DB::table('daily_production_entry_details AS dps')
             ->leftJoin('daily_production_entry_masters','daily_production_entry_masters.daily_pr_entry_id','=','dps.dailyProductionEntryId')
                ->join('assigned_to_orders', 'assigned_to_orders.sales_order_no', '=', 'dps.sales_order_no')
            ->join('ob_details', function ($join) {
            $join->on('ob_details.operation_id', '=', 'dps.operationNameId')
            ->whereColumn('ob_details.mainstyle_id', '=', 'assigned_to_orders.mainstyle_id_operation');
            })    
            ->select(
            'dps.bundleNo',
            'dps.operationNameId',
            'dps.dailyProductionEntryDate',
            'dps.sales_order_no','dps.color_id','ob_details.mainstyle_id',DB::raw('sum(dps.stiching_qty) as stiching_qty,dps.vendorId'))
            ->where('dps.sales_order_no',$sales_order_no)
            ->where('dps.vendorId',$vendorId)  
            ->where('dps.color_id',$color_id)   
            ->where('ob_details.mainstyle_id',$mainstyle_id)   
            ->groupBy('dps.bundleNo','dps.operationNameId')
            ->get();   
           
            
            $dataMAP=[];
            
            foreach($filter as $rowMap)
            {
              
              $dataMAP[$rowMap->sales_order_no][$rowMap->vendorId][$rowMap->color_id][$rowMap->mainstyle_id][$rowMap->operationNameId][$rowMap->bundleNo][]=[
              "stiching_qty"=>$rowMap->stiching_qty
              ];
            
            
            }
            

        
                            
                           
                        @endphp
                        @foreach($data as $emp)     
                        
                 
                        
                        <tr>
                            <td class="text-center">{{ $emp->bundleNo }}</td>
                            
                            @php   $grandTotal=0; @endphp
                             @foreach($operationList as $rowOP)
                             
                             @php $TotalQty=0; @endphp
                             
                             @if(isset($dataMAP[$emp->sales_order_no][$emp->vendorId][$emp->color_id][$emp->mainstyle_id][$rowOP->operation_id][$emp->bundleNo]))
                             
                             @foreach($dataMAP[$emp->sales_order_no][$emp->vendorId][$emp->color_id][$emp->mainstyle_id][$rowOP->operation_id][$emp->bundleNo] as $rowQty)
                             
                           @php  $TotalQty+=$rowQty['stiching_qty'];
                           
                            $grandTotal=$grandTotal+$TotalQty;
                           @endphp
                                  
                             @endforeach
                             @endif
                            <td class="text-center">{{ $TotalQty }}</td>
                          
                             @endforeach
                            <td class="text-right">{{ $grandTotal }}</td> 
                        </tr>
                        @php
                          
                          
                        @endphp
                        @endforeach
                  </tbody> 
          
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
   
    function GoToPrint()
    {
 
          var  sales_order_no = $('#sales_order_no').val();
          var  color_id = {{ $color_id ? $color_id : 0; }}
          var  mainstyle_id = {{ $mainstyle_id ? $mainstyle_id : 0; }}
          var  vendorId = {{ $vendorId ? $vendorId : 0; }}
        
        window.location.href = "/bundle_pending_for_production_print?sales_order_no="+sales_order_no+"&color_id="+color_id+"&mainstyle_id="+mainstyle_id+"&vendorId="+vendorId;
    }
    
    
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
   
          function get_styles(sales_order_no)
    {
       
        
        $.ajax({
          type: "POST",
          url: "{{ route('get_styles') }}",
          data:{'sales_order_no':sales_order_no,"_token":"{{ csrf_token(); }}"},
          success: function(data)
          { 
             
               $('#mainstyle_id').html(data.html); 
           
          }
        });
   } 
   
</script>
@endsection