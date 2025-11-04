@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp
<!-- end page title -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css"> 
<style>
    .hide{
        display:none;
    }
    
    .text-right{
        text-align:right;
    }
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">FG Moving Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">MIS Reports</a></li>
               <li class="breadcrumb-item active">FG Moving Report</li>
            </ol>
         </div>
      </div>
   </div>
</div> 

<div class="row">
    <div class="col-md-3">
       <div class="card mini-stats-wid" style="background-color:#152d9f;" >
          <div class="card-body">
             <div class="d-flex">
                <div class="flex-grow-1">
                   <p class="fw-medium" style="color:#fff;">Total FG(In Lakh)</p>
                   <h4 class="mb-0" style="color:#fff;" id="head_total_FGQty">0</h4>
                </div>
             </div>
          </div>
       </div>
    </div>
    <div class="col-md-3">
       <div class="card mini-stats-wid" style="background-color:#152d9f;" >
          <div class="card-body">
             <div class="d-flex">
                <div class="flex-grow-1">
                   <p class="fw-medium" style="color:#fff;">Total FG Value(In Lakh)</p>
                   <h4 class="mb-0" style="color:#fff;" id="head_total_FGValue">0</h4>
                </div> 
             </div>
          </div>
       </div>
    </div>
    <div class="col-md-12">
       <div class="card mini-stats-wid">
          <div class="card-body">
              <form action="/rptFGMovingReport" method="GET">
                  <div class="row">  
                      <div class="col-md-3 hide">
                         <div class="mb-3">
                            <label for="fromDate" class="form-label">From Date</label>
                            <input type="date" class="form-control" name="fromDate" id="fromDate" value="{{$fromDate}}">
                         </div>
                      </div>
                      <div class="col-md-3">
                         <div class="mb-3">
                            <label for="toDate" class="form-label">Date</label>
                            <input type="date" class="form-control" name="toDate" id="toDate" value="{{$toDate}}">
                         </div>
                      </div>
                       <div class="col-md-3">
                         <div class="mb-3">
                            <label for="job_status_id" class="form-label">Job Status Name</label>
                            <select name="job_status_id[]" id="job_status_id" class="form-control select2" multiple>
                                <option value="">--Select--</option>
                                @foreach($jobStatusList as $row)
                                    <option value="{{$row->job_status_id}}" >{{$row->job_status_name}}</option>
                                @endforeach
                            </select>
                         </div>
                       </div> 
                       <div class="col-md-3">
                         <div class="mb-3">
                            <label for="orderTypeId" class="form-label">Order Type</label>
                            <select name="orderTypeId[]" id="orderTypeId" class="form-control select2" multiple>
                                <option value="">--Select--</option>
                                @foreach($orderTypeList as $row)
                                    <option value="{{$row->orderTypeId}}">{{$row->order_type}}</option>
                                @endforeach
                            </select>
                         </div>
                       </div> 
                      <div class="col-md-6 mt-3"> 
                            <button type="submit" class="btn btn-primary">Search</button>
                            <a href="/rptTotalWIPReport" class="btn btn-warning">Clear</a>
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
            <!--<div class="col-md-12 hide text-center"  id="waiting"><img src="{{ URL::asset('images/loading-waiting.gif')}}" width="300" height="300"></div>-->
            <div class="table-responsive">
               
                     <table id="datatable-buttons" class="table table-bordered   nowrap w-100">
                         <thead>
                              <tr style="background-color:#eee;"> 
                                   <th nowrap>Sr. No.</th>
                                   <th nowrap>Order No</th>
                                   <th nowrap>PO Status</th>
                                   <th nowrap>Order Type</th>
                                   <th class="text-center" nowrap>Buyer Brand</th>
                                   <th class="text-center" nowrap>Category</th>
                                   <th class="text-center" nowrap>Order Qty</th>
                                   <th class="text-center" nowrap>Work Order Qty</th> 
                                   <th class="text-center" nowrap>Cutting Qty</th>
                                   <th class="text-center" nowrap>Sewing Qty</th>
                                   <th class="text-center" nowrap>Packing Qty</th>
                                   <th class="text-center" nowrap>Rejection Qty</th> 
                                   <th class="text-center" nowrap>WIP Adjustment Qty</th> 
                                   <th class="text-center" nowrap>Shipment Qty</th> 
                                   <th class="text-center" nowrap>FG Moving Qty</th> 
                                   <th class="text-center" nowrap>Order Rate</th>
                                   <th class="text-center" nowrap>FG Moving Value</th> 
                              </tr>
                         </thead>
                         <tbody>
                            @php
                                $head_total_FGQty = 0;
                                $head_total_FGValue = 0;
                                $srno = 1;
                            @endphp
                            @foreach($Buyer_Purchase_Order_List as $row)  
                            @php
                                $VendorData=DB::select("SELECT ifnull(sum(size_qty_total),0) as work_order_qty from vendor_work_order_detail  
                                     where  sales_order_no='".$row->tr_code."' AND vw_date <= '".$toDate."'");
                                
                                
                                $CutPanelData = DB::select("SELECT ifnull(sum(size_qty),0) as total_qty  from cut_panel_grn_size_detail2  
                                      where cut_panel_grn_size_detail2.sales_order_no = '".$row->tr_code."' AND cpg_date <= '".$toDate."'");
                                
                                if(count($CutPanelData) > 0)
                                {
                                        $cutPanelIssueQty = $CutPanelData[0]->total_qty;
                                }
                                else
                                {
                                        $cutPanelIssueQty = 0;
                                } 
                                
                                $StichingData=DB::select("SELECT ifnull(sum(total_qty),0) as stiching_qty from stitching_inhouse_master  
                                     where  sales_order_no='".$row->tr_code."' AND sti_date <='".$toDate."'");
                                
                                if(count($StichingData) > 0)
                                {
                                        $stichingQty = $StichingData[0]->stiching_qty;
                                }
                                else
                                {
                                        $stichingQty = 0;
                                }
                                
                                
                               $PackingData = DB::select("SELECT ifnull(sum(total_qty),0) as total_qty  from packing_inhouse_master  
                                            WHERE packing_inhouse_master.sales_order_no = '".$row->tr_code."' AND pki_date <='".$toDate."'");
      
                               if(count($PackingData) > 0)
                               {
                                     $pack_order_qty = $PackingData[0]->total_qty;
                               }
                               else
                               {
                                     $pack_order_qty = 0;
                               }
                               
                                //dd(DB::getQueryLog());
                             
                              //dd(DB::getQueryLog()); 
                              
                              if($row->order_type == 1)
                              {
                                  $order_type = 'Fresh';
                              }
                              else if($row->order_type == 2)
                              {
                                  $order_type = 'Stock';
                              }
                              else if($row->order_type == 3)
                              {
                                  $order_type = 'Job Work';
                              }
                              else
                              {
                                  $order_type = '';
                              }
                              
                            //  $packingOrderQty = DB::select("SELECT ifnull(sum(final_bom_qty),0) as pack_order_qty from vendor_purchase_order_master  
                            //     where vendor_purchase_order_master.sales_order_no='".$row->tr_code."' AND process_id=3");
                              
                            //  if(count($packingOrderQty) > 0)
                           //   {
                            //    $pack_order_qty = isset($packingOrderQty[0]->pack_order_qty) ? $packingOrderQty[0]->pack_order_qty : 0;
                             // }
                             // else
                              //{
                               // $pack_order_qty = 0;
                              //}
                              $sewing = $cutPanelIssueQty - $stichingQty;
                              
                              $totalWIP = ($VendorData[0]->work_order_qty - $cutPanelIssueQty) + $stichingQty + $pack_order_qty;
                              
                              $SalesCostingData = DB::select("SELECT * from sales_order_costing_master WHERE sales_order_no = '".$row->tr_code."'");
                              
                              $fabric_value = isset($SalesCostingData[0]->fabric_value) ? $SalesCostingData[0]->fabric_value : 0;  
                              $sewing_trims_value = isset($SalesCostingData[0]->sewing_trims_value) ? $SalesCostingData[0]->sewing_trims_value : 0;
                              $packing_trims_value = isset($SalesCostingData[0]->packing_trims_value) ? $SalesCostingData[0]->packing_trims_value : 0;             
                              
                              $WIPAdjustQtyData=DB::select("SELECT ifnull(sum(size_qty_total),0) as WIP_adjust_qty from WIP_Adjustable_Qty_detail  
                                     where  sales_order_no='".$row->tr_code."'");
                            
                               if(count($WIPAdjustQtyData) > 0)
                               {
                                     $WIP_Adjust_qty = $WIPAdjustQtyData[0]->WIP_adjust_qty;
                               }
                               else
                               {
                                     $WIP_Adjust_qty = 0;
                               }
                               
                                $rejectionData=DB::select("SELECT ifnull(sum(size_qty_total),0) as reject_qty from qcstitching_inhouse_reject_detail  
                                     where  sales_order_no='".$row->tr_code."' AND qcsti_date <='".$toDate."'");
                              
                                $rejectionQty = isset($rejectionData[0]->reject_qty) ? $rejectionData[0]->reject_qty : 0;  
                               
                               
                                $shipmentData=DB::select("SELECT ifnull(sum(order_qty),0) as shipment_qty from sale_transaction_detail  
                                     where  sales_order_no='".$row->tr_code."' AND sale_date <='".$toDate."'");
                              
                                $shipmentQty = isset($shipmentData[0]->shipment_qty) ? $shipmentData[0]->shipment_qty : 0;      
                                 
                                if($row->total_cost_value == 0)
                                {
                                     $fob_rate =  number_format($row->order_rate,2);
                                     $fob_rate1 =  round($row->order_rate,2);
                                }
                                else
                                { 
                                    $fob_rate = number_format($row->total_cost_value,2);
                                    $fob_rate1 = round($row->total_cost_value,2);
                                } 
                            
                                $pck_reject = $pack_order_qty + $rejectionQty;
                            @endphp
                            <tr>
                               <td nowrap>{{ $srno++ }}</td>
                               <td nowrap>{{ $row->tr_code  }}</td>
                               <td nowrap>{{ $row->job_status_name  }}</td>
                               <td nowrap>{{ $order_type  }}</td>
                               <td class="text-center" nowrap> {{ $row->brand_name  }} </td>
                               <td class="text-center" nowrap>{{ $row->mainstyle_name  }}</td>
                               <td class="text-right" nowrap>{{money_format("%!.0n",$row->total_qty) }} </td>
                               <td class="text-right" nowrap>{{money_format("%!.0n",$VendorData[0]->work_order_qty)}}</td>
                               <td nowrap class="text-right">{{ money_format("%!.0n",$cutPanelIssueQty) }}</td>
                               <td class="text-right" nowrap>{{ money_format("%!.0n",($stichingQty))}}</td>
                               <td nowrap class="text-right">{{ money_format("%!.0n",($pack_order_qty)) }}</td>
                               <td class="text-right">{{ money_format("%!.0n",($rejectionQty)) }} </td>
                               <td class="text-right">{{ money_format("%!.0n",($WIP_Adjust_qty)) }} </td>
                               <td class="text-right">{{ money_format("%!.0n",($shipmentQty)) }}</td>
                               <td class="text-right">{{ money_format("%!.0n",(($pck_reject - $WIP_Adjust_qty) - $shipmentQty)) }}</td>
                               <td class="text-right" nowrap>{{ $fob_rate }}</td>
                               <td class="text-right">{{ money_format("%!.0n",((($pck_reject - $WIP_Adjust_qty) - $shipmentQty)*$fob_rate1)) }}</td>
                             </tr>
                             @php
                              $head_total_FGQty += (($pck_reject - $WIP_Adjust_qty) - $shipmentQty);
                              $head_total_FGValue += ((($pck_reject - $WIP_Adjust_qty) - $shipmentQty)*$fob_rate1);
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
<input type="hidden" id="hidden_FG_Qty" value="{{number_format((float)$head_total_FGQty/100000, 2, '.', '')}}">
<input type="hidden" id="hidden_FG_Value" value="{{number_format((float)$head_total_FGValue/100000, 2, '.', '')}}">
<script src="https://code.jquery.com/jquery-1.12.3.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" ></script> 
<script>  

    $(document).ready(function()
    { 
        $("#head_total_FGQty").text($("#hidden_FG_Qty").val());
        $("#head_total_FGValue").text($("#hidden_FG_Value").val()); 
        $('#job_status_id').val([{{$Status}}]).trigger('change');
        $('#orderTypeId').val([{{$orderType}}]).trigger('change');
    }); 
   </script>
@endsection