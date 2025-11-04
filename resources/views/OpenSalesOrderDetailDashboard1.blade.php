@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp                
<!-- end page title -->
@php
if($job_status_id==1) { @endphp
<style>
    .text-left
    {
      text-align:left;
    }
  
    .text-right
    {
      text-align:right;
    }
  
    table th:nth-child(10),td:nth-child(10) 
    {
      position: sticky;
      left: 0;
      z-index: 2;
      background:#f4f2eef0;
    }
</style>
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
                  <p class="  fw-medium" style="color:#fff;" >Open Order Qty(Lakh)</p>
                  <h4 class="mb-0" style="color:#fff;" id="cardOpenOrderQty">0</h4>
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
                  <p class="  fw-medium" style="color:#fff;">Open Order Min(Lakh)</p>
                  <h4 class="mb-0" style="color:#fff;" id="cardOpenOrderMin">0</h4>
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
                  <p class="  fw-medium" style="color:#fff;">Open Order Value(Lakh)</p>
                  <h4 class="mb-0" style="color:#fff;" id="cardOpenOrderValue">0</h4>
               </div>
               <div class="flex-shrink-0 align-self-center">
                  <div class="avatar-sm rounded-circle bg-primary  " >
                     <span class="avatar-title  " style="background-color:#f79733;">
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
   <div class="col-md-12">
      <div class="card">
         <div class="card-body"> 
              <form action="{{route('OpenSalesOrderDetailDashboard')}}" method="GET" enctype="multipart/form-data">
                   @csrf 
                   <div class="row">
                       <div class="col-md-3">
                         <div class="mb-3">
                            <label for="ReportDate" class="form-label">Date</label>
                            <input type="date" class="form-control" name="ReportDate" id="ReportDate" value="{{ isset($ReportDate) ? $ReportDate : date('Y-m-01')}}">
                         </div>
                       </div> 
                       <div class="col-sm-9 mb-3">
                          <label for="formrow-inputState" class="form-label"></label>
                          <div class="form-group">
                             <button type="submit" class="btn btn-primary w-md">Search</button>
                            <a href="./OpenSalesOrderDetailDashboard?fob=1"><button type="button" class="btn btn-warning w-md">FOB</button></a> 
                            <a href="./OpenSalesOrderDetailDashboard?job_work=2"><button type="button" class="btn btn-info w-md">Job Work</button></a>  
                             <a href="/OpenSalesOrderDetailDashboard" class="btn btn-danger w-md">Clear</a>
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
               <table id="datatable-buttons" class="table table-bordered   nowrap w-100">
                  <thead>
                    <tr style="text-align:center; white-space:nowrap;background: bisque;" id="total_head">
                        <th colspan="10"></th>
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
                        <th id="head_total_bal_min_produced_actual">0</th>
                        <th id="head_total_bal_min_prod_actual">0</th>
                        <th>-</th>
                        <th id="head_total_cmohp_value">0</th>
                        <th>-</th>
                        <th>-</th>
                        <th>-</th>
                        <th>-</th>
                        <th>-</th>
                     </tr>
                     <tr style="text-align:center;white-space:nowrap;">
                        <th>Sr No.</th>
                        <th>Code</th>
                        <th>Order Group</th> 
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
                        <th>Balance to Produce Actual Value</th>
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
                        $total_bal_min_produced_actual = 0;
                        $total_packing_qty = 0;
                        $total_cmohp_value = 0;
                        $total_bal_to_produce_value = 0;
                        $srno = 1;
                     @endphp 
                     @foreach($Buyer_Purchase_Order_List as $row)    
                     @php
                    
                    
                    if($DFilter == 'd')
                    {
                        $filterDate1 = " AND FG.entry_date <= date('Y-m-d')";
                        
                        $filterDate2 = " AND transfer_packing_inhouse_size_detail2.tpki_date <= date('Y-m-d')";
                        
                        $filterDate3 = " AND qcstitching_inhouse_reject_detail.qcsti_date <= date('Y-m-d')";
                        
                        $filterDate4 = " AND carton_packing_inhouse_size_detail2.cpki_date <= date('Y-m-d')";
                    }
                    else if($DFilter == 'm')
                    {
                        $filterDate1 = ' AND MONTH(FG.entry_date)=MONTH(CURRENT_DATE()) and YEAR(packing_inhouse_size_detail2.pki_date)=YEAR(CURRENT_DATE()) AND packing_inhouse_size_detail2.pki_date !="'.date('Y-m-d').'"';
                        
                        $filterDate2 = ' AND MONTH(transfer_packing_inhouse_size_detail2.tpki_date)=MONTH(CURRENT_DATE()) and YEAR(transfer_packing_inhouse_size_detail2.tpki_date)=YEAR(CURRENT_DATE()) AND transfer_packing_inhouse_size_detail2.tpki_date!="'.date('Y-m-d').'"';  
                        
                        $filterDate3 = ' AND MONTH(qcstitching_inhouse_reject_detail.qcsti_date)=MONTH(CURRENT_DATE()) and YEAR(qcstitching_inhouse_reject_detail.qcsti_date)=YEAR(CURRENT_DATE()) AND qcstitching_inhouse_reject_detail.qcsti_date !="'.date('Y-m-d').'"';
                      
                        $filterDate4 = ' AND MONTH(carton_packing_inhouse_size_detail2.cpki_date)=MONTH(CURRENT_DATE()) and YEAR(carton_packing_inhouse_size_detail2.cpki_date)=YEAR(CURRENT_DATE()) AND carton_packing_inhouse_size_detail2.cpki_date !="'.date('Y-m-d').'"';
                   
                    }
                    else if($DFilter == 'y')
                    {
                        $filterDate1 = ' AND FG.entry_date between (select fdate from financial_year_master where financial_year_master.fin_year_id=6) 
                        and (select tdate from financial_year_master where financial_year_master.fin_year_id=6)';
                        
                        $filterDate2 = ' AND transfer_packing_inhouse_size_detail2.tpki_date between (select fdate from financial_year_master where financial_year_master.fin_year_id=6) 
                        and (select tdate from financial_year_master where financial_year_master.fin_year_id=6)';
                        
                        $filterDate3 = ' AND qcstitching_inhouse_reject_detail.qcsti_date between (select fdate from financial_year_master where financial_year_master.fin_year_id=6) 
                        and (select tdate from financial_year_master where financial_year_master.fin_year_id=6)';     
                        
                        $filterDate4 = ' AND carton_packing_inhouse_size_detail2.cpki_date between (select fdate from financial_year_master where financial_year_master.fin_year_id=6) 
                        and (select tdate from financial_year_master where financial_year_master.fin_year_id=6)';
                    
                    }
                    else
                    {
                        $filterDate1 = "";
                        $filterDate2 = "";
                        $filterDate3 = "";
                        $filterDate4 = "";
                    }
        
                 
                 $filter7 = "";
                 
                 if($ReportDate != "")
                 {
                    $filter7 .= " AND FG.entry_date <= '".$ReportDate."'";
                 } 
                 
                 
                     
                      //DB::enableQueryLog();
                    $FGStockData = DB::select("SELECT ifnull(sum(FG.size_qty),0) as packing_qty, ifnull((SELECT sum(d2.size_qty) from FGStockDataByOne as d2 where d2.data_type_id=2 and d2.sales_order_no=FG.sales_order_no 
                    ),0) as carton_pack_qty, ifnull((SELECT  sum(d1.size_qty)from FGStockDataByOne as d1 where d1.data_type_id=3 
                    and d1.sales_order_no=FG.sales_order_no and d1.color_id=FG.color_id and d1.size_id=FG.size_id),0)  as transfer_qty   
                    FROM FGStockDataByOne as`FG` where FG.data_type_id=1 and  FG.sales_order_no = '". $row->tr_code."' ".$filterDate1." ".$filter7);
                        // dd(DB::getQueryLog());  
                        
                    if($FGStockData != null)
                    { 
                        $FGStock = $FGStockData[0]->packing_qty - $FGStockData[0]->carton_pack_qty -  $FGStockData[0]->transfer_qty;
                    }
                    
                     $rejectionData = DB::select("SELECT ifnull(sum(size_qty_total),0) as reject_qty FROM qcstitching_inhouse_reject_detail WHERE sales_order_no = '". $row->tr_code."'");
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
                        <td style="text-align:center; white-space:nowrap"> {{ $srno++  }} </td>
                        <td style="text-align:center; white-space:nowrap"> {{ $row->tr_code  }} </td>
                        <td style="text-align:center; white-space:nowrap"> {{ $row->order_group_name  }} </td>  
                        <td style="text-align:center; white-space:nowrap">  {{ date('Y-m-d', strtotime($row->order_received_date)) }}   </td>
                        <td style="text-align:left; white-space:nowrap"> {{ $row->Ac_name  }} </td>
                        <td style="text-align:left; white-space:nowrap"> {{ $row->po_code  }} </td>
                        <td style="text-align:left; white-space:nowrap"> {{ $row->brand_name  }} </td>
                        <td style="text-align:left; white-space:nowrap"> {{ $row->mainstyle_name  }} </td>
                        <td style="text-align:left; white-space:nowrap; left:0;"> {{ $row->style_no  }} </td>
                        <td style="text-align:left; white-space:nowrap"> {{ round($row->sam,2)  }} </td>
                        <td style="text-align:right;"> {{ money_format('%!i',round($row->order_rate,2) ) }} </td>
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
                        <td style="text-align:right;">{{ ($row->total_qty - $row->prod_qty - $row->adjust_qty + ($excess_qty)) * $row->order_rate}}</td>
                        <td style="text-align:right;">{{ ($row->total_qty - $row->prod_qty - $row->adjust_qty + ($excess_qty)) * $row->sam }}</td>
                        <td style="text-align:right;">{{number_format($cmohp,2)}}</td>
                        <td style="text-align:right;">{{number_format(($orderMin * $cmohp),2)}}</td>
                        <td>  {{ date('Y-m-d', strtotime($row->plan_cut_date)) }} </td>
                        <td>  {{ date('Y-m-d', strtotime($row->shipment_date)) }} </td>
                        <td>  {{ date('M-Y', strtotime($row->shipment_date)) }} </td>
                        <td style="text-align:left; white-space:nowrap"> {{ $row->merchant_name  }} </td>
                        <td style="text-align:left; white-space:nowrap"> {{ $row->remark  }} </td>
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
                        $total_bal_to_ship_qty = $total_bal_to_ship_qty + ($row->total_qty - $row->shipped_qty - $row->adjust_qty + ($excess_qty) - $reject_qty);
                        $total_fg_stock_qty = $total_fg_stock_qty + $FGStock;             
                        $total_bal_ship_min = $total_bal_ship_min + (($row->total_qty - $row->shipped_qty - $row->adjust_qty + ($excess_qty) - $reject_qty) * $row->sam);
                        $total_bal_to_produce = $total_bal_to_produce + ($row->total_qty - $row->prod_qty);
                        $total_bal_to_produce_value = $total_bal_to_produce_value + ($row->total_qty - $row->prod_qty) * $row->order_rate;
                        $total_bal_to_produce_min = $total_bal_to_produce_min + ($row->sam * ($row->total_qty - $row->prod_qty));
                        $total_short_close_qty = $total_short_close_qty + $row->adjust_qty;
                        $total_bal_qty_prod_actual = $total_bal_qty_prod_actual + ($row->total_qty - $row->prod_qty - $row->adjust_qty + ($excess_qty));
                        $total_bal_min_prod_actual = $total_bal_min_prod_actual + (($row->total_qty - $row->prod_qty - $row->adjust_qty + ($excess_qty)) * $row->sam);
                        $total_bal_min_produced_actual = $total_bal_min_produced_actual + (($row->total_qty - $row->prod_qty - $row->adjust_qty + ($excess_qty)) * $row->order_rate);
                        $total_cmohp_value = $total_cmohp_value + ($orderMin * $cmohp);
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
                     <input type="hidden" id="total_bal_to_produce_value" value="{{money_format('%!.0n',round($total_bal_to_produce_value))}}">
                     <input type="hidden" id="total_bal_to_produce_min" value="{{money_format('%!.0n',round($total_bal_to_produce_min))}}">
                     <input type="hidden" id="total_short_close_qty" value="{{money_format('%!.0n',round($total_short_close_qty))}}">
                     <input type="hidden" id="total_bal_qty_prod_actual" value="{{money_format('%!.0n',round($total_bal_qty_prod_actual))}}">
                     <input type="hidden" id="total_bal_min_prod_actual" value="{{money_format('%!.0n',round($total_bal_min_prod_actual))}}">
                     <input type="hidden" id="total_bal_min_produced_actual" value="{{money_format('%!.0n',round($total_bal_min_produced_actual))}}">
                     <input type="hidden" id="total_cmohp_value" value="{{money_format('%!.0n',round($total_cmohp_value))}}">
                     <input type="hidden" id="total_bal_qty_prod_actual1" value="{{round($total_bal_qty_prod_actual)}}">
                     <input type="hidden" id="total_bal_min_prod_actual1" value="{{round($total_bal_min_prod_actual)}}">
                     <input type="hidden" id="total_bal_min_produced_actual1" value="{{round($total_bal_min_produced_actual)}}">
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
    $('#head_total_order_value').text($('#total_order_val').val());
    $('#head_total_order_qty').text($('#total_order_qty').val());
    $('#head_total_Min_qty').text($('#total_order_min').val());
    $('#head_total_cut_qty').text($('#total_cut_qty').val());
    $('#head_total_production_qty').text($('#total_prod_qty').val());
    $('#head_total_prod_bal_qty').text($('#total_prod_bal_qty').val());
    $('#head_total_prod_min').text($('#total_prod_min').val());
    $('#head_total_reject_qty').text($('#total_reject_qty').val());
    $('#head_total_packing_qty').text($('#total_packing_qty').val());
    $('#head_total_ship_qty').text($('#total_ship_qty').val());
    $('#head_total_ship_min').text($('#total_ship_min').val());
    $('#head_total_ship_value').text($('#total_ship_val').val());
    $('#head_total_excess_cut_qty').text($('#total_excess_qty').val());
    $('#head_total_bal_ship_qty').text($('#total_bal_to_ship_qty').val());
    $('#head_total_fg_stock_qty').text($('#total_fg_stock_qty').val());
    $('#head_total_bal_ship_min').text($('#total_bal_ship_min').val());
    $('#head_total_bal_prod').text($('#total_bal_to_produce').val());
    $('#head_total_bal_prod_value').text($('#total_bal_to_produce').val());
    $('#head_total_bal_prod_min').text($('#total_bal_to_produce_min').val());
    $('#head_total_short_close_qty').text($('#total_short_close_qty').val());
    $('#head_total_bal_qty_prod_actual').text($('#total_bal_qty_prod_actual').val());
    $('#head_total_bal_min_prod_actual').text($('#total_bal_min_prod_actual').val());
    $('#head_total_bal_min_produced_actual').text($('#total_bal_min_produced_actual').val());
    $('#head_total_cmohp_value').text($('#total_cmohp_value').val());
    
    $("#cardOpenOrderQty").text(($('#total_bal_qty_prod_actual1').val()/100000).toFixed(2));
    $("#cardOpenOrderMin").text(($('#total_bal_min_prod_actual1').val()/100000).toFixed(2));
    $("#cardOpenOrderValue").text(($('#total_bal_min_produced_actual1').val()/100000).toFixed(2));
    
</script>
@endsection