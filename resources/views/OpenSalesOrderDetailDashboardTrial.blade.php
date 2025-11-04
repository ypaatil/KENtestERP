@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp                
<!-- end page title -->
@php
if($job_status_id==1) { @endphp
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Sales Order: Open</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Sales Order : Open</li>
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
                  <p class="  fw-medium" style="color:#fff;">No. of Orders</p>
                  <h4 class="mb-0" style="color:#fff;">{{number_format($NoOfOrderc)}} </h4>
               </div>
               <div class="flex-shrink-0 align-self-center">
                  <div class="  avatar-sm rounded-circle bg-primary" style="background-color:#152d9f;">
                     <span class="avatar-title" style="background-color:#152d9f;">
                     <i class="bx bx-copy-alt font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#556ee6;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;" >Order Qty(Lakh)</p>
                  <h4 class="mb-0" style="color:#fff;" >{{number_format((double)($total_qtyc/100000), 2, '.', '')}} </h4>
               </div>
               <div class="flex-shrink-0 align-self-center ">
                  <div class="avatar-sm rounded-circle bg-primary  ">
                     <span class="avatar-title  " style="background-color:#556ee6;" >
                     <i class="bx bx-purchase-tag-alt font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#008116;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">Shipped Qty(Lakh)</p>
                  <h4 class="mb-0" style="color:#fff;">  {{number_format((double)($shipped_qtyc/100000), 2, '.', '')}}  </h4>
               </div>
               <div class="flex-shrink-0 align-self-center">
                  <div class="avatar-sm rounded-circle bg-primary  " >
                     <span class="avatar-title  " style="background-color:#008116;">
                     <i class="bx bx-archive-in font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#f79733;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">Open Qty(Lakh)</p>
                  <h4 class="mb-0" style="color:#fff;">{{number_format((double)($open_qtyc/100000), 2, '.', '')}} </h4>
               </div>
               <div class="flex-shrink-0 align-self-center">
                  <div class="avatar-sm rounded-circle bg-primary  " >
                     <span class="avatar-title" style="background-color:#f79733;">
                     <i class="bx bx-archive-in font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@php 
}
@endphp                          
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body"> 
           <div class="col-md-6">
              <div class="card">
                 <form action="OpenSalesOrderDetailDashboardTrial" method="GET">
                 <div class="card-body row"> 
                    <div class="col-md-6">
                        <p class="fw-medium">Date</p>
                        <input type="date" name="currentDate" id="currentDate"  value="{{$currentDate}}"  class="form-control">
                    </div>
                    <div class="col-md-3" style="margin-top: 32px;"> 
                         <button class="btn btn-primary form-control" type="submit">Search</button>
                    </div>
                    <!--<div class="col-md-3" style="margin-top: 32px;"> -->
                    <!--     <a href="OpenSalesOrderDetailDashboardTrial"><button class="btn btn-danger form-control" type="button">Clear</button></a>-->
                    <!--</div>-->
                 </div>
                 </form>
              </div>
           </div>
            <div class="table-responsive">
               <table id="datatable-buttons" class="table table-bordered nowrap w-100">
                  <thead>
                    <tr style="text-align:center; white-space:nowrap;background: bisque;" id="total_head">
                        <th colspan="9"></th>
                        <th style="text-align: right;">Total : </th>
                        <th id="head_total_order_value">0</th>
                        <th id="head_total_order_qty">0</th>
                        <th id="head_total_Min_qty">0</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th id="head_total_cut_qty">0</th>
                        <th id="head_total_production_qty">0</th>
                        <th id="head_total_prod_bal_qty">0</th>
                        <th id="head_total_prod_min">0</th>
                        <th id="head_total_reject_qty">0</th>
                        <th id="head_total_packing_qty">0</th>
                        <th id="head_total_ship_qty">0</th>
                        <th id="head_total_ship_min">0</th>
                        <th id="head_total_ship_value">0</th>
                        <th id="head_total_excess_cut_qty">0</th>
                        <th id="head_total_bal_ship_qty">0</th>
                        <th id="head_total_fg_stock_qty">0</th>
                        <th id="head_total_bal_ship_min">0</th>
                        <th id="head_total_bal_prod">0</th>
                        <th id="head_total_bal_prod_min">0</th>
                        <th id="head_total_short_close_qty">0</th>
                        <th id="head_total_bal_qty_prod_actual">0</th>
                        <th id="head_total_bal_min_prod_actual">0</th>
                        <th>-</th>
                        <th>-</th>
                        <th>-</th>
                        <th>-</th>
                        <th>-</th>
                        <th>-</th>
                        <th>-</th>
                     </tr>
                        
                     <tr style="text-align:center; white-space:nowrap">
                        <th>Code</th>
                        <th>PO status</th>
                        <th>Entry Date</th>
                        <th>Buyer Name</th>
                        <th>PO No</th>
                        <th>Buyer Brand</th>
                        <th>Main Style Category</th>
                        <th>Style Name</th>
                        <th>SAM</th>
                        <th>Order Rate</th>
                        <th>Order value  </th>
                        <th>Order Qty  </th>
                        <th>Order Min  </th>
                        <th>Fabric issue meter</th>
                        <th>Consumption</th>
                        <th>Cut Panel</th>
                        <th>Cut Qty  </th>
                        <th>Production Qty</th>
                        <th>Prod. Bal Qty</th>
                        <th>Produced Minutes</th>
                        <th>Rejection Qty</th>
                        <th>Packing Qty</th>
                        <th>Shipped Qty</th>
                        <th>Shipped Min</th>
                        <th>Shipped Value </th>
                        <th>Excess Cut Qty</th>
                        <th>Balance to ship Qty</th>
                        <th>FG Stock Qty</th>
                        <th>Balance to ship Min</th>
                        <th>Balance to Produce</th>
                        <th>Balance to Produce Min</th>
                        <th>Short Close Qty</th>
                        <th>Balance Qty to Produce Actual</th>
                        <th>Balance Minutes to Produce Actual</th>
                        <th>CMOHP</th>
                        <th>CMOHP Value</th>
                        <th>Plan Cut Date</th>
                        <th>Shipment Date</th>
                        <th>Ship Month</th>
                        <th>Bulk Merchant</th>
                        <th>Remark</th>
                     </tr>
                  </thead>
                  <tbody>
                     @php 
                        $FGStock = 0;
                        $total_order_val = 0;
                        $total_order_qty = 0;
                        $total_order_min = 0;
                        $total_cut_qty  = 0;
                        $total_prod_qty = 0;
                        $total_prod_bal_qty = 0;
                        $total_prod_min = 0;
                        $total_reject_qty = 0;
                        $total_ship_qty = 0;
                        $total_ship_min = 0;
                        $total_ship_val = 0;
                        $total_excess_qty = 0;
                        $total_bal_to_ship_qty = 0;
                        $total_fg_stock_qty = 0;             
                        $total_bal_ship_min = 0;
                        $total_bal_to_produce = 0;
                        $total_bal_to_produce_min = 0;
                        $total_short_close_qty = 0;
                        $total_bal_qty_prod_actual = 0;
                        $total_bal_min_prod_actual = 0;
                        $total_packing_qty = 0;
                     @endphp 
                     @foreach($Buyer_Purchase_Order_List as $row)    
                     @php
                        
                        if($currentDate!="")
                        {
                            $filterDate1 = " AND FG.entry_date <= '".$currentDate."'";
                            $filterDate2 = " AND transfer_packing_inhouse_size_detail2.tpki_date <= '".$currentDate."'";
                            $filterDate3 = " AND qcstitching_inhouse_reject_detail.qcsti_date <= '".$currentDate."'";
                            $filterDate4 = " AND carton_packing_inhouse_size_detail2.cpki_date <= '".$currentDate."'";
                        }
                        else
                        {
                            $filterDate1 = "";
                            $filterDate2 = "";
                            $filterDate3 = "";
                            $filterDate4 = "";
                        } 
                          //DB::enableQueryLog();
                        $FGStockData = DB::select("SELECT ifnull(sum(FG.`size_qty`),0)  as packing_qty, ifnull((SELECT  sum(d2.size_qty) from FGStockDataByOne as d2 where d2.data_type_id=2 and d2.sales_order_no=FG.sales_order_no 
                        ),0) as carton_pack_qty, ifnull((SELECT  sum(d1.size_qty)from FGStockDataByOne as d1 where d1.data_type_id=3 
                        and d1.sales_order_no=FG.sales_order_no and d1.color_id=FG.color_id and d1.size_id=FG.size_id),0)  as transfer_qty   
                        FROM FGStockDataByOne as`FG` where FG.data_type_id=1 and  FG.sales_order_no = '". $row->tr_code."' ".$filterDate1);
                            // dd(DB::getQueryLog());  
                            
                        if($FGStockData != null)
                        { 
                            $FGStock = $FGStockData[0]->packing_qty - $FGStockData[0]->carton_pack_qty -  $FGStockData[0]->transfer_qty;
                        }
                        
                         $rejectionData = DB::select("SELECT ifnull(sum(size_qty_total),0) as reject_qty FROM qcstitching_inhouse_reject_detail WHERE sales_order_no = '". $row->tr_code."'".$filterDate3);
                         //$Ship=isset($row->shipped_qty2) ? $row->shipped_qty2 : 0;
                         
                         $reject_qty = isset($rejectionData[0]->reject_qty) ? $rejectionData[0]->reject_qty : 0;
                         
                         $excessData = $row->cut_qty - $row->total_qty;
                         if($excessData < 0)
                         {
                             $excess_qty = 0;
                         }
                         else
                         {
                            $excess_qty = $excessData;
                         }
                         
                          $consData = DB::table('sales_order_fabric_costing_details')
                            ->where('sales_order_no', $row->tr_code)
                            ->whereIN('class_id', [1,2]) 
                            ->first();
                         
                         $cons = isset($consData->consumption) ? $consData->consumption: 0;
                         if($row->fabric_issued>0 && $cons>0)
                         {
                                $cutpanel = $row->fabric_issued/$cons;
                         }
                         else
                         {
                                $cutpanel = 0;
                         }
                         
                        $profit_value=0.0;
                        $profit_value=  ($row->order_rate - $row->total_cost_value);
                        
                        $cmohp1 = $row->production_value + $profit_value + $row->other_value;
                        $cmohp2 = $row->sam;
                        if($cmohp1 && $cmohp2)
                        {
                            $cmohp = $cmohp1/$cmohp2;
                        }
                        else
                        {
                            $cmohp = 0;
                         }
                         
                         $orderMin = ($row->sam * $row->total_qty);
                         
                         @endphp
                         <tr>
                            <td style="text-align:center; white-space:nowrap"> {{ $row->tr_code  }} </td>
                            <td style="text-align:center; white-space:nowrap"> {{ $row->job_status_name  }} </td>
                            <td style="text-align:center; white-space:nowrap">  {{ date('Y-m-d', strtotime($row->order_received_date)) }}   </td>
                            <td style="text-align:center; white-space:nowrap"> {{ $row->Ac_name  }} </td>
                            <td style="text-align:center; white-space:nowrap"> {{ $row->po_code  }} </td>
                            <td style="text-align:center; white-space:nowrap"> {{ $row->brand_name  }} </td>
                            <td style="text-align:center; white-space:nowrap"> {{ $row->mainstyle_name  }} </td>
                            <td style="text-align:center; white-space:nowrap"> {{ $row->style_no  }} </td>
                            <td style="text-align:center; white-space:nowrap"> {{ $row->sam  }} </td>
                            <td style="text-align:right;"> {{ money_format('%!i',$row->order_rate ) }} </td>
                            <td style="text-align:right;"> {{ money_format('%!i',$row->order_value)  }} </td> 
                            <td style="text-align:right;"> {{ money_format('%!.0n',$row->total_qty) }} </td> 
                            <td style="text-align:right;" > {{ money_format('%!.0n',($orderMin)) }} </td> 
                            <td style="text-align:right;"> {{ money_format('%!.0n',$row->fabric_issued) }} </td> 
                            <td style="text-align:right;"> {{$cons}} </td> 
                            <td style="text-align:right;"> {{ money_format('%!.0n',$cutpanel) }} </td> 
                            <td style="text-align:right;"> {{ money_format('%!.0n',$row->cut_qty) }} </td> 
                            <td style="text-align:right;"> {{ money_format('%!.0n',$row->prod_qty) }} </td> 
                            <td style="text-align:right;"> {{ money_format('%!.0n',($row->total_qty-$row->prod_qty)) }}</td> 
                            <td style="text-align:right;"> {{ money_format('%!.0n',($row->prod_qty * $row->sam)) }} </td> 
                            <td style="text-align:right;"> {{ money_format('%!.0n',($reject_qty)) }} </td> 
                            <td style="text-align:right;"> {{ money_format('%!.0n',$row->shipped_qty2) }} </td> 
                            <td style="text-align:right;"> {{ money_format('%!.0n',$row->shipped_qty) }} </td> 
                            <td style="text-align:right;"> {{ money_format('%!.0n',$row->shipped_qty * $row->sam) }} </td>
                            <td style="text-align:right;"> {{money_format('%!i', ($row->shipped_qty * $row->order_rate))  }} </td> 
                            <td style="text-align:right;"> {{money_format('%!i', ($excess_qty))  }}</td> 
                            <td style="text-align:right;"> {{ money_format('%!.0n',$row->total_qty - $row->shipped_qty - $row->adjust_qty + ($excess_qty) - $reject_qty) }} </td>
                            <td style="text-align:right;"> {{ money_format('%!.0n', $FGStock) }} </td> 
                            <td style="text-align:right;"> {{ money_format('%!.0n',(($row->total_qty - $row->shipped_qty - $row->adjust_qty + ($excess_qty) - $reject_qty)) * $row->sam) }} </td>
                            <td style="text-align:right;">{{ money_format('%!.0n',$row->total_qty-$row->prod_qty) }}</td>
                            <td style="text-align:right;">{{ money_format('%!.0n',$row->sam * ($row->total_qty - $row->prod_qty)) }}</td>
                            <td style="text-align:right;">{{ $row->adjust_qty }}</td>
                            <td style="text-align:right;">{{ $row->total_qty - $row->prod_qty - $row->adjust_qty + ($excess_qty)}}</td>
                            <td style="text-align:right;">{{ ($row->total_qty - $row->prod_qty - $row->adjust_qty + ($excess_qty)) * $row->sam }}</td>
                            <td style="text-align:right;">{{number_format($cmohp,2)}}</td>
                            <td style="text-align:right;">{{number_format(($orderMin *  number_format($cmohp,2)),2)}}</td>
                            <td>  {{ date('Y-m-d', strtotime($row->plan_cut_date)) }} </td>
                            <td>  {{ date('Y-m-d', strtotime($row->shipment_date)) }} </td>
                            <td>  {{ date('M-Y', strtotime($row->shipment_date)) }} </td>
                            <td style="text-align:center; white-space:nowrap"> {{ $row->merchant_name  }} </td>
                            <td style="text-align:center; white-space:nowrap"> {{ $row->remark  }} </td>
                         </tr>
                         @php
                            $total_order_val = $total_order_val + $row->order_value;
                            $total_order_qty = $total_order_qty + $row->total_qty;
                            $total_order_min = $total_order_min + ($row->sam * $row->total_qty);
                            $total_cut_qty = $total_cut_qty + $row->cut_qty;
                            $total_prod_qty = $total_prod_qty + $row->prod_qty;
                            $total_prod_bal_qty = $total_prod_bal_qty + ($row->total_qty-$row->prod_qty);
                            $total_prod_min = $total_prod_min + ($row->prod_qty * $row->sam);
                            $total_reject_qty = $total_reject_qty + $reject_qty;
                            $total_packing_qty = $total_packing_qty + $row->shipped_qty2;
                            $total_ship_qty = $total_ship_qty + $row->shipped_qty;
                            $total_ship_min = $total_ship_min + ($row->shipped_qty * $row->sam);
                            $total_ship_val = $total_ship_val + ($row->shipped_qty * $row->order_rate);
                            $total_excess_qty = $total_excess_qty + ($excess_qty);
                            $total_bal_to_ship_qty = $total_bal_to_ship_qty + ($row->total_qty - $row->shipped_qty2 - $row->adjust_qty + ($excess_qty) - $reject_qty);
                            $total_fg_stock_qty = $total_fg_stock_qty + $FGStock;             
                            $total_bal_ship_min = $total_bal_ship_min + (($row->total_qty - $row->shipped_qty2 - $row->adjust_qty + ($excess_qty) - $reject_qty) * $row->sam);
                            $total_bal_to_produce = $total_bal_to_produce + ($row->total_qty - $row->prod_qty);
                            $total_bal_to_produce_min = $total_bal_to_produce_min + ($row->sam * ($row->total_qty - $row->prod_qty));
                            $total_short_close_qty = $total_short_close_qty + $row->adjust_qty;
                            $total_bal_qty_prod_actual = $total_bal_qty_prod_actual + ($row->total_qty - $row->prod_qty - $row->adjust_qty + ($excess_qty));
                            $total_bal_min_prod_actual = $total_bal_min_prod_actual + (($row->total_qty - $row->prod_qty - $row->adjust_qty + ($excess_qty)) * $row->sam);
                         @endphp
                         @endforeach
                         <input type="hidden" id="total_order_val" value="{{money_format('%!.0n',round($total_order_val))}}">
                         <input type="hidden" id="total_order_qty" value="{{money_format('%!.0n',round($total_order_qty))}}">
                         <input type="hidden" id="total_order_min" value="{{money_format('%!.0n',round($total_order_min))}}">
                         <input type="hidden" id="total_cut_qty" value="{{money_format('%!.0n',round($total_cut_qty))}}">
                         <input type="hidden" id="total_prod_qty" value="{{money_format('%!.0n',round($total_prod_qty))}}">
                         <input type="hidden" id="total_prod_bal_qty" value="{{money_format('%!.0n',round($total_prod_bal_qty))}}">
                         <input type="hidden" id="total_prod_min" value="{{money_format('%!.0n',round($total_prod_min))}}">
                         <input type="hidden" id="total_reject_qty" value="{{money_format('%!.0n',round($total_reject_qty))}}">
                         <input type="hidden" id="total_packing_qty" value="{{money_format('%!.0n',round($total_packing_qty))}}">
                         <input type="hidden" id="total_ship_qty" value="{{money_format('%!.0n',round($total_ship_qty))}}">
                         <input type="hidden" id="total_ship_min" value="{{money_format('%!.0n',round($total_ship_min))}}">
                         <input type="hidden" id="total_ship_val" value="{{money_format('%!.0n',round($total_ship_val))}}">
                         <input type="hidden" id="total_excess_qty" value="{{money_format('%!.0n',round($total_excess_qty))}}">
                         <input type="hidden" id="total_bal_to_ship_qty" value="{{money_format('%!.0n',round($total_bal_to_ship_qty))}}">
                         <input type="hidden" id="total_fg_stock_qty" value="{{money_format('%!.0n',round($total_fg_stock_qty))}}">
                         <input type="hidden" id="total_bal_ship_min" value="{{money_format('%!.0n',round($total_bal_ship_min))}}">
                         <input type="hidden" id="total_bal_to_produce" value="{{money_format('%!.0n',round($total_bal_to_produce))}}">
                         <input type="hidden" id="total_bal_to_produce_min" value="{{money_format('%!.0n',round($total_bal_to_produce_min))}}">
                         <input type="hidden" id="total_short_close_qty" value="{{money_format('%!.0n',round($total_short_close_qty))}}">
                         <input type="hidden" id="total_bal_qty_prod_actual" value="{{money_format('%!.0n',round($total_bal_qty_prod_actual))}}">
                         <input type="hidden" id="total_bal_min_prod_actual" value="{{money_format('%!.0n',round($total_bal_min_prod_actual))}}">
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
   <!-- end col money_format('%!.0n',$row->balance_qty - $FGStock) -->
</div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script>
    $('#head_total_order_value').html($('#total_order_val').val());
    $('#head_total_order_qty').html($('#total_order_qty').val());
    $('#head_total_Min_qty').html($('#total_order_min').val());
    $('#head_total_cut_qty').html($('#total_cut_qty').val());
    $('#head_total_production_qty').html($('#total_prod_qty').val());
    $('#head_total_prod_bal_qty').html($('#total_prod_bal_qty').val());
    $('#head_total_prod_min').html($('#total_prod_min').val());
    $('#head_total_reject_qty').html($('#total_reject_qty').val());
    $('#head_total_packing_qty').html($('#total_packing_qty').val());
    $('#head_total_ship_qty').html($('#total_ship_qty').val());
    $('#head_total_ship_min').html($('#total_ship_min').val());
    $('#head_total_ship_value').html($('#total_ship_val').val());
    $('#head_total_excess_cut_qty').html($('#total_excess_qty').val());
    $('#head_total_bal_ship_qty').html($('#total_bal_to_ship_qty').val());
    $('#head_total_fg_stock_qty').html($('#total_fg_stock_qty').val());
    $('#head_total_bal_ship_min').html($('#total_bal_ship_min').val());
    $('#head_total_bal_prod').html($('#total_bal_to_produce').val());
    $('#head_total_bal_prod_min').html($('#total_bal_to_produce_min').val());
    $('#head_total_short_close_qty').html($('#total_short_close_qty').val());
    $('#head_total_bal_qty_prod_actual').html($('#total_bal_qty_prod_actual').val());
    $('#head_total_bal_min_prod_actual').html($('#total_bal_min_prod_actual').val());
</script>
@endsection