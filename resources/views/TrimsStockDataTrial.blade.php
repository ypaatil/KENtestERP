@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp
<!-- end page title -->
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Trims Stock Detail</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Trims Stock Detail</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="row">
@php
$Amt=0;                        
foreach($TrimsInwardDetails1 as $row)  
{
$Amt=$Amt + round(($row->item_qty-$row->out_qty)*$row->item_rate);
}
@endphp
<div class="col-md-3">
   <div class="card mini-stats-wid" style="background-color:#152d9f;" >
      <div class="card-body">
         <div class="d-flex">
            <div class="flex-grow-1">
               <p class="  fw-medium" style="color:#fff;">Total Amount(In Lakh)</p>
               <h4 class="mb-0" style="color:#fff;">{{money_format('%!i',round($Amt/100000,2))}}</h4>
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
<div class="col-md-4">
   <div class="card mini-stats-wid" style="background-color:#152d9f;" >
      <div class="card-body">
          <form action="/TrimsStockDataTrial" method="GET">
              <div class="row">
                  <div class="col-md-6">
                        <label><b style="color:#fff!important">Stock as On</b></label>
                        <input type="date" name="currentDate" value="{{$currentDate}}" class="form-control"> 
                  </div>
                  <div class="col-md-6 mt-4"> 
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="/TrimsStockDataTrial" class="btn btn-warning">Clear</a>
                  </div>
              </div>
          </form>
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
                        <th colspan="7"></th>
                        <th style="text-align: right;">Total : </th>
                        <th id="head_total_value">{{money_format('%!i',round($Amt/100000,2))}}</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                     </tr>
                     <tr style="text-align:center;">
                        <th>Supplier Name</th>
                        <th>Buyer Name</th>
                        <th>PO Status</th>
                        <th>PO No</th>
                        <th>Item Code</th>
                        <th>Item Name</th>
                        <th>Stock Qty</th>
                        <th>rate</th>
                        <th>Value</th>
                        <th>Width</th>
                        <th>Color</th>
                        <th>Item Description</th>
                        <th>Rack Name</th>
                     </tr>
                  </thead>
                  <tbody>
                    @php
                        $total_stock_qty = 0;
                        $total_value = 0;
                        $total_value1 = 0;
                    @endphp
                     @foreach($TrimsInwardDetails1 as $row)   
                     @php
                     if($isOpening == 1)
                     {
                        $po_status = ' AND po_status = 1';
                     }
                     else if($isOpening == 2)
                     {
                        $po_status = ' AND po_status = 2';
                     }
                     else
                     {
                        $po_status = "";
                     }
                     
                     $StatusData = DB::select("select ifnull(purchase_order.po_status,0) as po_status
                     from purchase_order WHERE purchase_order.pur_code = '".$row->po_code."'".$po_status);
                     if(count($StatusData) > 0)
                     {
                     $po_status = $StatusData[0]->po_status;
                     }
                     else
                     {
                     $po_status = 0;
                     }
                     $JobStatusList=DB::select('select job_status_id,  job_status_name from job_status_master WHERE job_status_id ='.$po_status);
                     if(count($JobStatusList) > 0)
                     {
                     $job_status_name = $JobStatusList[0]->job_status_name;
                     }
                     else
                     {
                     $job_status_name = "-";
                     }
                     $salesOrderNo=DB::select("select distinct sales_order_no from purchaseorder_detail where  pur_code='".$row->po_code."'");
                     if(count($salesOrderNo) > 0)
                     {
                     $buyerData = DB::select("select ledger_master.ac_name from buyer_purchse_order_master 
                     INNER JOIN  ledger_master ON ledger_master.ac_code = buyer_purchse_order_master.Ac_code
                     where buyer_purchse_order_master.tr_code='".$salesOrderNo[0]->sales_order_no."'");
                     if(count($buyerData) > 0)
                     {
                     $buyer_name = $buyerData[0]->ac_name;
                     }
                     else
                     {
                     $buyer_name = "-";
                     }
                     }
                     else
                     {
                     $buyer_name = "-";
                     }
                     
                     $values = ($row->item_qty-$row->out_qty)*$row->item_rate;
                     @endphp
                     <tr>
                        <td style="text-align:center; white-space:nowrap">{{$row->ac_name}}</td>
                        <td style="text-align:center; white-space:nowrap">{{$buyer_name}}</td>
                        <td style="text-align:center; white-space:nowrap">{{$job_status_name}}</td>
                        <td style="text-align:center; white-space:nowrap"> {{ $row->po_code  }} </td>
                        <td> {{ $row->item_code  }} </td>
                        <td style="text-align:center; white-space:nowrap"> {{ $row->item_name  }} </td>
                        <td style="text-align:right;"> {{ number_format($row->item_qty - $row->out_qty) }} </td>
                        <td style="text-align:right;"> {{ $row->item_rate }} </td>
                        <td style="text-align:right;"> {{ number_format( round($values))}} </td>
                        <td style="text-align:right;"> {{ $row->dimension }} </td>
                        <td style="text-align:center; white-space:nowrap"> {{ $row->color_name }} </td>
                        <td style="text-align:center; white-space:nowrap"> {{ $row->item_description }} </td>
                        <td style="text-align:center; white-space:nowrap"> {{ $row->rack_name }} </td>
                     </tr>
                     @php
                        $total_value += $values;
                     @endphp
                     @endforeach 
                     @if($isOpening == 2)
                     @foreach($TrimsInwardDetails2 as $row)   
                     @php
                     
                     if($isOpening == 1)
                     {
                        $po_status = ' AND po_status = 1';
                     }
                     else if($isOpening == 2)
                     {
                        $po_status = ' AND po_status = 2';
                     }
                     else
                     {
                        $po_status = "";
                     }
                     
                     $StatusData = DB::select("select ifnull(purchase_order.po_status,0) as po_status
                     from purchase_order WHERE purchase_order.pur_code = '".$row->po_code."'".$po_status);
                     if(count($StatusData) > 0)
                     {
                     $po_status = $StatusData[0]->po_status;
                     }
                     else
                     {
                     $po_status = 0;
                     }
                     $JobStatusList=DB::select('select job_status_id,  job_status_name from job_status_master WHERE job_status_id ='.$po_status);
                     if(count($JobStatusList) > 0)
                     {
                     $job_status_name = $JobStatusList[0]->job_status_name;
                     }
                     else
                     {
                     $job_status_name = "-";
                     }
                     $salesOrderNo=DB::select("select distinct sales_order_no from purchaseorder_detail where  pur_code='".$row->po_code."'");
                     if(count($salesOrderNo) > 0)
                     {
                     $buyerData = DB::select("select ledger_master.ac_name from buyer_purchse_order_master 
                     INNER JOIN  ledger_master ON ledger_master.ac_code = buyer_purchse_order_master.Ac_code
                     where buyer_purchse_order_master.tr_code='".$salesOrderNo[0]->sales_order_no."'");
                     if(count($buyerData) > 0)
                     {
                     $buyer_name = $buyerData[0]->ac_name;
                     }
                     else
                     {
                     $buyer_name = "-";
                     }
                     }
                     else
                     {
                     $buyer_name = "-";
                     }   
                     
                     $values1 = ($row->item_qty-$row->out_qty)*$row->item_rate;
                     @endphp
                     <tr>
                        <td style="text-align:center; white-space:nowrap">{{$row->ac_name}}</td>
                        <td style="text-align:center; white-space:nowrap">{{$buyer_name}}</td>
                        <td style="text-align:center; white-space:nowrap">{{$job_status_name}}</td>
                        <td style="text-align:center; white-space:nowrap"> {{ $row->po_code  }} </td>
                        <td> {{ $row->item_code  }} </td>
                        <td style="text-align:center; white-space:nowrap"> {{ $row->item_name  }} </td>
                        <td style="text-align:right;"> {{ number_format($row->item_qty - $row->out_qty) }} </td>
                        <td style="text-align:right;"> {{ $row->item_rate }} </td>
                        <td style="text-align:right;"> {{ number_format( round($values1))}} </td>
                        <td style="text-align:right;"> {{ $row->dimension }} </td>
                        <td style="text-align:center; white-space:nowrap"> {{ $row->color_name }} </td>
                        <td style="text-align:center; white-space:nowrap"> {{ $row->item_description }} </td>
                        <td style="text-align:center; white-space:nowrap"> {{ $row->rack_name }} </td>
                     </tr>
                     @php
                           $total_value1 += $values1;
                     @endphp
                     @endforeach
                      @endif
                      @php
                            $overall = $total_value1 + $total_value;
                      
                      @endphp
                     <input type="hidden" id="total_value" value="{{round($overall,2)}}">
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
    //$('#head_total_value').html($('#total_value').val());
</script>
@endsection