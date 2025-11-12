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
  
    /*table th:nth-child(10),td:nth-child(10) */
    /*{*/
    /*  position: sticky;*/
    /*  left: 0;*/
    /*  z-index: 2;*/
    /*  background:#f4f2eef0;*/
    /*}*/
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
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
                  <p class="  fw-medium" style="color:#fff;" >Qty (Lakh)</p>
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
                  <p class="  fw-medium" style="color:#fff;">Min (Lakh)</p>
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
                  <p class="  fw-medium" style="color:#fff;">Value (Cr.)</p>
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
                       <div class="col-md-2">
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
               <table id="dt" class="table table-bordered   nowrap w-100">
                  <thead>
                    <tr style="text-align:center; white-space:nowrap;background: bisque;" id="total_head">
                        <th colspan="9"></th>
                        <th style="text-align: right;">Total : </th>
                        <th style="text-align: right;" id="head_total_order_value">0</th>
                        <th style="text-align: right;" id="head_total_order_qty">0</th>
                        <th style="text-align: right;" id="head_total_Min_qty">0</th>
                        <th style="text-align: right;" id="head_total_cut_qty">0</th>
                        <th style="text-align: right;" id="head_total_production_qty">0</th>
                        <th style="text-align: right;" id="head_total_prod_bal_qty">0</th>
                        <th style="text-align: right;" id="head_total_prod_min">0</th>
                        <th style="text-align: right;" id="head_total_reject_qty">0</th>
                        <th style="text-align: right;" id="head_total_packing_qty">0</th>
                        <th style="text-align: right;" id="head_total_ship_qty">0</th>
                        <th style="text-align: right;" id="head_total_ship_min">0</th>
                        <th style="text-align: right;" id="head_total_excess_cut_qty">0</th>
                        <th style="text-align: right;" id="head_total_bal_ship_qty">0</th>
                        <th style="text-align: right;" id="head_total_bal_ship_min">0</th>
                        <th style="text-align: right;" id="head_total_bal_prod">0</th>
                        <th style="text-align: right;" id="head_total_bal_prod_min">0</th>
                        <th style="text-align: right;" id="head_total_short_close_qty">0</th>
                        <th style="text-align: right;" id="head_total_bal_qty_prod_actual">0</th>
                        <th style="text-align: right;" id="head_total_bal_min_produced_actual">0</th>
                        <th style="text-align: right;" id="head_total_bal_min_prod_actual">0</th>
                        <th style="text-align: right;">-</th>
                        <th style="text-align: right;" id="head_total_cmohp_value">0</th>
                        <th style="text-align: right;">-</th>
                        <th style="text-align: right;">-</th>
                        <th style="text-align: right;">-</th>
                        <th style="text-align: right;">-</th>
                        <th style="text-align: right;">-</th>
                     </tr>
                        
                     <tr style="text-align:center;white-space:nowrap;">
                        <th style="text-align:center;">Sr No.</th>
                        <th style="text-align:center;">Order No.<span class="filter-icon">🔽</span><div class="filter-menu order-no"></div></th>
                        <th style="text-align:center;">Order Group<span class="filter-icon">🔽</span><div class="filter-menu order-group"></div></th> 
                        <th style="text-align:center;">Order Recd. Date<span class="filter-icon">🔽</span><div class="filter-menu order-rec-date"></div></th>
                        <th style="text-align:center;">Buyer Name<span class="filter-icon">🔽</span><div class="filter-menu buyer-name"></div></th>
                        <th style="text-align:center;">Buyer Brand<span class="filter-icon">🔽</span><div class="filter-menu buyer-brand"></div></th>
                        <th style="text-align:center;">Style Category<span class="filter-icon">🔽</span><div class="filter-menu style-category"></div></th>
                        <th style="text-align:center;">Style Name<span class="filter-icon">🔽</span><div class="filter-menu style-name"></div></th>
                        <th style="text-align:center;">SAM</th>
                        <th style="text-align:center;">Rate</th>
                        <th style="text-align:center;">Value  </th>
                        <th style="text-align:center;">Qty  </th>
                        <th style="text-align:center;">Min  </th>
                        <th style="text-align:center;">Cut Qty  </th>
                        <th style="text-align:center;">Production Qty</th>
                        <th style="text-align:center;">Prod. Bal Qty</th>
                        <th style="text-align:center;">Produced Min</th>
                        <th style="text-align:center;">Rejection Qty</th>
                        <th style="text-align:center;">Packing Qty</th>
                        <th style="text-align:center;">Shipment Qty</th>
                        <th style="text-align:center;">Shipment Min</th>
                        <th style="text-align:center;">Excess Cut Qty</th>
                        <th style="text-align:center;">B 2 Ship Qty</th>
                        <th style="text-align:center;">B 2 ship Min</th>
                        <th style="text-align:center;">B 2 Produce</th>
                        <th style="text-align:center;">B 2 Produce Min</th>
                        <th style="text-align:center;">Short Close Qty</th>
                        <th style="text-align:center;">B 2 Produce Actual Qty</th>
                        <th style="text-align:center;">B 2 Produce Actual Value</th>
                        <th style="text-align:center;">B 2 Produce Actual Min</th>
                        <th style="text-align:center;">CMOHP</th>
                        <th style="text-align:center;">CMOHP Value</th>
                        <th style="text-align:center;">Plan Cut Date<span class="filter-icon">🔽</span><div class="filter-menu plan-cut-date"></div></th>
                        <th style="text-align:center;">Shipment Date<span class="filter-icon">🔽</span><div class="filter-menu shipment-date"></div></th>
                        <th style="text-align:center;">Ship Month<span class="filter-icon">🔽</span><div class="filter-menu shipment-month"></div></th>
                        <th style="text-align:center;">Bulk Merchant<span class="filter-icon">🔽</span><div class="filter-menu bulk-merchant"></div></th>
                        <th style="text-align:center;">Remark</th>
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
                        $filterDate1 = ' AND FG.entry_date between (select fdate from financial_year_master where financial_year_master.fin_year_id=3) 
                        and (select tdate from financial_year_master where financial_year_master.fin_year_id=3)';
                        
                        $filterDate2 = ' AND transfer_packing_inhouse_size_detail2.tpki_date between (select fdate from financial_year_master where financial_year_master.fin_year_id=3) 
                        and (select tdate from financial_year_master where financial_year_master.fin_year_id=3)';
                        
                        $filterDate3 = ' AND qcstitching_inhouse_reject_detail.qcsti_date between (select fdate from financial_year_master where financial_year_master.fin_year_id=3) 
                        and (select tdate from financial_year_master where financial_year_master.fin_year_id=3)';     
                        
                        $filterDate4 = ' AND carton_packing_inhouse_size_detail2.cpki_date between (select fdate from financial_year_master where financial_year_master.fin_year_id=3) 
                        and (select tdate from financial_year_master where financial_year_master.fin_year_id=3)';
                    
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
                     $rejectionData = DB::select("SELECT ifnull(sum(size_qty_total),0) as reject_qty FROM qcstitching_inhouse_reject_detail WHERE sales_order_no = '". $row->tr_code."' AND qcstitching_inhouse_reject_detail.qcsti_date <= '".$ReportDate."'");
                     //dd(DB::getQueryLog());
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
                        <td style="text-align:right; white-space:nowrap"> {{ $srno++  }} </td>
                        <td style="text-align:left; white-space:nowrap"> {{ $row->tr_code  }} </td>
                        <td style="text-align:left; white-space:nowrap"> {{ $row->order_group_name  }} </td>  
                        <td style="text-align:center; white-space:nowrap">  {{ date('d-M-Y', strtotime($row->order_received_date)) }}   </td>
                        <td style="text-align:left; white-space:nowrap"> {{ $row->ac_short_name  }} </td>
                        <td style="text-align:left; white-space:nowrap"> {{ $row->brand_name  }} </td>
                        <td style="text-align:left; white-space:nowrap"> {{ $row->mainstyle_name  }} </td>
                        <td style="text-align:left; white-space:wrap; left:0;"> {{ $row->style_no  }} </td>
                        <td style="text-align:right; white-space:nowrap"> {{ sprintf('%.2f', round($row->sam,2))  }} </td>
                        <td style="text-align:right;"> {{ money_format('%!i',$row->order_rate) }} </td>
                        <td style="text-align:right;"> {{ money_format('%!.0n',round($row->order_value))  }} </td> 
                        <td style="text-align:right;"> {{ money_format('%!.0n',$row->total_qty) }} </td> 
                        <td style="text-align:right;" > {{ money_format('%!.0n',($orderMin)) }} </td> 
                        <td style="text-align:right;"> {{ money_format('%!.0n', round($row->cut_qty)) }} </td> 
                        <td style="text-align:right;"> {{ money_format('%!.0n', round($row->prod_qty)) }} </td> 
                        <td style="text-align:right;"> {{ money_format('%!.0n',($row->total_qty-$row->prod_qty)) }}</td> 
                        <td style="text-align:right;"> {{ money_format('%!.0n',($row->prod_qty * $row->sam)) }} </td> 
                        <td style="text-align:right;"> {{ money_format('%!.0n',round($reject_qty)) }} </td> 
                        <td style="text-align:right;"> {{ money_format('%!.0n',$row->shipped_qty2) }} </td> 
                        <td style="text-align:right;"> {{ money_format('%!.0n',$row->shipped_qty) }} </td> 
                        <td style="text-align:right;"> {{ money_format('%!.0n',$row->shipped_qty * $row->sam) }} </td>
                        <td style="text-align:right;"> {{ money_format('%!.0n', round($excess_qty))  }}</td> 
                        <td style="text-align:right;"> {{ money_format('%!.0n',$row->total_qty - $row->shipped_qty - $row->adjust_qty + ($excess_qty) - $reject_qty) }} </td>
                        <td style="text-align:right;"> {{ money_format('%!.0n',(($row->total_qty - $row->shipped_qty - $row->adjust_qty + ($excess_qty) - $reject_qty)) * $row->sam) }} </td>
                        <td style="text-align:right;">{{ money_format('%!.0n',$row->total_qty-$row->prod_qty) }}</td>
                        <td style="text-align:right;">{{ money_format('%!.0n',$row->sam * ($row->total_qty - $row->prod_qty)) }}</td>
                        <td style="text-align:right;">{{ money_format('%!.0n', round($row->adjust_qty)) }}</td>
                        <td style="text-align:right;">{{ money_format('%!.0n', round($row->total_qty - $row->prod_qty - $row->adjust_qty + ($excess_qty)))}}</td>
                        <td style="text-align:right;">{{  money_format('%!.0n', round(($row->total_qty - $row->prod_qty - $row->adjust_qty + ($excess_qty)) * $row->order_rate))}}</td>
                        <td style="text-align:right;">{{  money_format('%!.0n', round(($row->total_qty - $row->prod_qty - $row->adjust_qty + ($excess_qty)) * $row->sam)) }}</td>
                        <td style="text-align:right;">{{money_format('%!i', $cmohp)}}</td>
                        <td style="text-align:right;">{{money_format('%!.0n', round($orderMin * $cmohp))}}</td>
                        <td style="text-align:center; white-space:nowrap">  {{ date('d-M-Y', strtotime($row->plan_cut_date)) }} </td>
                        <td style="text-align:center; white-space:nowrap">  {{ date('d-M-Y', strtotime($row->shipment_date)) }} </td>
                        <td style="text-align:left; white-space:nowrap">  {{ date('M-Y', strtotime($row->shipment_date)) }} </td>
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
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
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
    $("#cardOpenOrderValue").text(($('#total_bal_min_produced_actual1').val()/10000000).toFixed(2));
   
    $(document).ready(function() 
    {
        if ($.fn.DataTable.isDataTable('#dt')) {
            $('#dt').DataTable().clear().destroy();
        }
        const today = new Date();
        const day = String(today.getDate()).padStart(2, '0');
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const year = today.getFullYear();
        const formattedDate = `${day}-${month}-${year}`;
        const exportTitle = 'Open Order Report (' + formattedDate + ')';

        $('#dt').DataTable({
            destroy: true,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copyHtml5',
                    text: 'Copy',
                    title: exportTitle,
                    exportOptions: commonExportOptions
                },
                {
                    extend: 'excelHtml5',
                    text: 'Excel',
                    title: exportTitle,
                    exportOptions: commonExportOptions
                },
                {
                    extend: 'csvHtml5',
                    text: 'CSV',
                    title: exportTitle,
                    exportOptions: commonExportOptions
                },
                {
                    extend: 'pdfHtml5',
                    text: 'PDF',
                    title: exportTitle,
                    exportOptions: commonExportOptions,
                    orientation: 'landscape',     // or 'portrait'
                    pageSize: 'A4',               // A4, A3, etc.
                    customize: function (doc) {
                        doc.defaultStyle.fontSize = 10; // PDF text size
                    }
                },
                {
                    extend: 'print',
                    text: 'Print Table',
                    title: exportTitle,
                    exportOptions: commonExportOptions
                }
            ]

        });

           // New script added 11-11-2025   
            buildAllMenusOpenSalesDetailOrderDetailDashboard();

         $(document).on('click', '.apply-btn', function() {
            const menu = $(this).closest('.filter-menu');
            if(menu.hasClass('order-no')) applySimpleFilter(1, menu);
            else if(menu.hasClass('order-group')) applySimpleFilter(2, menu);
            else if(menu.hasClass('order-rec-date')) applyDateFilter(3, menu);
            else if(menu.hasClass('buyer-name')) applySimpleFilter(4,menu);
            else if(menu.hasClass('buyer-brand')) applySimpleFilter(5,menu);
            else if(menu.hasClass('style-category')) applySimpleFilter(6,menu);
            else if(menu.hasClass('style-name')) applySimpleFilter(7,menu);

            else if(menu.hasClass('plan-cut-date')) applyDateFilter(32,menu); 
            else if(menu.hasClass('shipment-date')) applyDateFilter(33, menu);
            else if(menu.hasClass('shipment-month')) applyDateFilter(34, menu);     
            else if(menu.hasClass('bulk-merchant')) applySimpleFilter(35,menu);            
    
            $('.filter-menu').hide();
            buildAllMenusOpenSalesDetailOrderDetailDashboard();   
            updateTotalsOpenSalesOrderDetailDashboard();
      });

      $(document).on('click', '.clear-btn', function(){
         table.search('').columns().search('').draw();
         buildAllMenusOpenSalesDetailOrderDetailDashboard();    
         updateTotalsOpenSalesOrderDetailDashboard();
      });
    });
</script>
@endsection