@extends('layouts.master') 
@section('content')   
<!-- end page title -->
@php
if($job_status_id==1) { @endphp
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Daily Production Detail Dashboard</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Daily Production Detail Dashboard</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-md-2">
      <div class="card mini-stats-wid" style="background-color:#152d9f;" >
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">Cutting Qty</p>
                  <h4 class="mb-0" style="color:#fff;" id="Cutting">0.00</h4>
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
   <div class="col-md-2">
      <div class="card mini-stats-wid" style="background-color:#556ee6;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;" >Line Issue(Lakh)</p>
                  <h4 class="mb-0" style="color:#fff;" id="line_issue">0.00 </h4>
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
   <div class="col-md-2">
      <div class="card mini-stats-wid" style="background-color:#008116;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">Stitching Qty(Lakh)</p>
                  <h4 class="mb-0" style="color:#fff;" id="stitching">     </h4>
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
   <div class="col-md-2">
      <div class="card mini-stats-wid" style="background-color:#f79733;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">Rejection Qty(Lakh)</p>
                  <h4 class="mb-0" style="color:#fff;"  id="rejection"> </h4>
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
   
   <div class="col-md-2">
      <div class="card mini-stats-wid" style="background-color:#5e1a21;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">Packing Qty(Lakh)</p>
                  <h4 class="mb-0" style="color:#fff;" id="packing"> </h4>
               </div>
               <div class="flex-shrink-0 align-self-center">
                  <div class="avatar-sm rounded-circle bg-primary  " >
                     <span class="avatar-title  " style="background-color:#5e1a21;">
                     <i class="bx bx-archive-in font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   
   <div class="col-md-2">
      <div class="card mini-stats-wid" style="background-color:#068bb3;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">Shipment Qty(Lakh)</p>
                  <h4 class="mb-0" style="color:#fff;"  id="shipement"> </h4>
               </div>
               <div class="flex-shrink-0 align-self-center">
                  <div class="avatar-sm rounded-circle bg-primary  " >
                     <span class="avatar-title  " style="background-color:#068bb3;">
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
            <div class="table-responsive">
               <table id="datatable-buttons" class="table   table-bordered">
                  <thead>
                     <tr style="text-align:center">
                        <th rowspan="2">Order No</th>
                        <th rowspan="2">Main Style Category</th>
                        <th rowspan="2">Style Name</th>
                        <th rowspan="2">Buyer PO No</th>
                        <th rowspan="2"> Color</th>
                        <th rowspan="2">Order Qty</th>
                        <th colspan="3">Cutting</th>
                        <th colspan="3">Line Issue</th>
                        <th colspan="3">Stitching</th>
                        <th colspan="3">Rejection</th>
                        <th colspan="3">Packing</th>
                        <th colspan="3">Shipment Qty</th>
                        <th rowspan="2">Bulk Merchant</th>
                     </tr>
                     <tr>
                        <th>Last Day</th>
                        <th>Total</th>
                        <th>Balance</th>
                        <th>Last Day</th>
                        <th>Total</th>
                        <th>Balance</th>
                        <th>Last Day</th>
                        <th>Total</th>
                        <th>Balance</th>
                        <th>Last Day</th>
                        <th>Total</th>
                        <th>Balance</th>
                        <th>Last Day</th>
                        <th>Total</th>
                        <th>Balance</th>
                        <th>Last Day</th>
                        <th>Total</th>
                        <th>Balance</th>
                     </tr>
                  </thead>
                  <tbody>
                     @php
                        $total_cutting_qty = 0;
                        $total_line_issue = 0;
                        $total_stitching_qty = 0;
                        $total_rejection_qty = 0;
                        $total_packing_qty = 0;
                        $total_shipment_qty = 0;
                     @endphp
                     @foreach($ProductionOrderDetailList as $row) 
                     @php 
                     
                     $TodayCutQty=DB::select("select ifnull(sum(size_qty_total),0) as today_cutting_qty from cut_panel_grn_detail where
                     cut_panel_grn_detail.color_id='".$row->color_id."' and
                     cut_panel_grn_detail.sales_order_no='".$row->tr_code."'
                     and cut_panel_grn_detail.cpg_date='".date('Y-m-d',strtotime("-1 days"))."'"); 
                     $TotalCutQty=DB::select("select  ifnull(sum(size_qty_total),0)  as total_cutting_qty from cut_panel_grn_detail where
                     cut_panel_grn_detail.color_id='".$row->color_id."' and
                     cut_panel_grn_detail.sales_order_no='".$row->tr_code."'");  
                     $TodayCutIssueQty=DB::select("select ifnull(sum(size_qty_total),0) as today_cut_panel_issue from cut_panel_issue_detail where
                     cut_panel_issue_detail.color_id='".$row->color_id."' and
                     cut_panel_issue_detail.sales_order_no='".$row->tr_code."'  
                     and cut_panel_issue_detail.cpi_date='".date('Y-m-d',strtotime("-1 days"))."'");
                     $TotalCutIssueQty=DB::select("select ifnull(sum(size_qty_total),0) as total_cut_panel_issue  from cut_panel_issue_detail where
                     cut_panel_issue_detail.color_id='".$row->color_id."' and
                     cut_panel_issue_detail.sales_order_no='".$row->tr_code."'");
                     $TodayStitchQty=DB::select("select ifnull(sum(size_qty_total),0) as today_stitching_qty  from stitching_inhouse_detail where
                     stitching_inhouse_detail.color_id='".$row->color_id."' and
                     stitching_inhouse_detail.sales_order_no='".$row->tr_code."'
                     and stitching_inhouse_detail.sti_date='".date('Y-m-d',strtotime("-1 days"))."'");   
                     $TotalStitchQty=DB::select("select ifnull(sum(size_qty_total),0) as total_stitching_qty from stitching_inhouse_detail where
                     stitching_inhouse_detail.color_id='".$row->color_id."' and
                     stitching_inhouse_detail.sales_order_no='".$row->tr_code."'"); 
                     $TodayQCQty=DB::select("select ifnull(sum(size_qty_total),0) as today_qcstitching_reject_qty from qcstitching_inhouse_reject_detail where
                     qcstitching_inhouse_reject_detail.color_id='".$row->color_id."' 
                     and qcstitching_inhouse_reject_detail.sales_order_no='".$row->tr_code."'
                     and qcstitching_inhouse_reject_detail.qcsti_date='".date('Y-m-d',strtotime("-1 days"))."'");
                     $TotalQCQty=DB::select("select ifnull(sum(size_qty_total),0) as total_qcstitching_reject_qty from qcstitching_inhouse_reject_detail where
                     qcstitching_inhouse_reject_detail.color_id='".$row->color_id."' and 
                     qcstitching_inhouse_reject_detail.sales_order_no='".$row->tr_code."'");
                     $TodayPackQty=DB::select("select ifnull(sum(size_qty_total),0)  as today_packing_qty from packing_inhouse_detail
                     inner join packing_inhouse_master on packing_inhouse_master.pki_code= packing_inhouse_detail.pki_code
                     where packing_inhouse_detail.color_id='".$row->color_id."' 
                     and packing_inhouse_detail.sales_order_no='".$row->tr_code."' and 
                     packing_inhouse_detail.pki_date='".date('Y-m-d' ,strtotime("-1 days"))."'");
                     $TotalPackQty=DB::select("select ifnull(sum(size_qty_total),0) as total_packing_qty from packing_inhouse_detail
                     inner join packing_inhouse_master on packing_inhouse_master.pki_code= packing_inhouse_detail.pki_code
                     where   packing_inhouse_detail.color_id='".$row->color_id."'
                     and packing_inhouse_detail.sales_order_no='".$row->tr_code."'");
                     $TodayShipQty=DB::select("select ifnull(sum(size_qty_total),0) as today_shipment_qty from carton_packing_inhouse_detail
                     inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code= carton_packing_inhouse_detail.cpki_code
                     where carton_packing_inhouse_detail.color_id='".$row->color_id."' 
                     and carton_packing_inhouse_detail.sales_order_no='".$row->tr_code."'   
                     and carton_packing_inhouse_master.endflag=1 and
                     carton_packing_inhouse_detail.cpki_date='".date('Y-m-d' ,strtotime("-1 days"))."'");
                     $TotalShipQty=DB::select("select ifnull(sum(size_qty_total),0) as total_shipment_qty from carton_packing_inhouse_detail
                     inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code = carton_packing_inhouse_detail.cpki_code
                     where   carton_packing_inhouse_detail.color_id='".$row->color_id."'
                     and carton_packing_inhouse_detail.sales_order_no='".$row->tr_code."'
                     and carton_packing_inhouse_master.endflag=1");  
                     @endphp                                    
                     <tr>
                        <td style="text-align:center; white-space:nowrap"> {{ $row->tr_code  }} </td>
                        <td style="text-align:center; white-space:nowrap"> {{ $row->mainstyle_name  }} </td>
                        <td style="text-align:center; white-space:nowrap"> {{ $row->style_no  }} </td>
                        <td style="text-align:center; white-space:nowrap"> {{ $row->po_code  }} </td>
                        <td style="text-align:center; white-space:nowrap"> {{ $row->color_name  }} </td>
                        <td style="text-align:right;"> {{ $row->order_qty }} </td>
                        <td style="text-align:right;"> {{ $TodayCutQty[0]->today_cutting_qty  }} </td>
                        <td style="text-align:right;"> {{ $TotalCutQty[0]->total_cutting_qty  }}</td>
                        <td style="text-align:right;"> {{ $row->order_qty - $TotalCutQty[0]->total_cutting_qty  }}</td>
                        <td style="text-align:right;"> {{ $TodayCutIssueQty[0]->today_cut_panel_issue  }} </td>
                        <td style="text-align:right;"> {{ $TotalCutIssueQty[0]->total_cut_panel_issue  }}</td>
                        <td style="text-align:right;"> {{ $row->order_qty- $TotalCutIssueQty[0]->total_cut_panel_issue }} </td>
                        <td style="text-align:right;"> {{ $TodayStitchQty[0]->today_stitching_qty  }} </td>
                        <td style="text-align:right;"> {{ $TotalStitchQty[0]->total_stitching_qty  }}</td>
                        <td style="text-align:right;"> {{ $row->order_qty-$TotalStitchQty[0]->total_stitching_qty  }} </td>
                        <td style="text-align:right;"> {{ $TodayQCQty[0]->today_qcstitching_reject_qty  }} </td>
                        <td style="text-align:right;"> {{ $TotalQCQty[0]->total_qcstitching_reject_qty  }} </td>
                        <td style="text-align:right;"> {{ $row->order_qty- $TotalQCQty[0]->total_qcstitching_reject_qty  }} </td>
                        <td style="text-align:right;"> {{ $TodayPackQty[0]->today_packing_qty  }} </td>
                        <td style="text-align:right;"> {{ $TotalPackQty[0]->total_packing_qty  }}</td>
                        <td style="text-align:right;"> {{ $row->order_qty-$TotalPackQty[0]->total_packing_qty  }} </td>
                        <td style="text-align:right;"> {{ $TodayShipQty[0]->today_shipment_qty  }} </td>
                        <td style="text-align:right;"> {{ $TotalShipQty[0]->total_shipment_qty  }}</td>
                        <td style="text-align:right;"> {{ $row->order_qty-$TotalShipQty[0]->total_shipment_qty  }} </td>
                        <td style="text-align:center; white-space:nowrap"> {{ $row->merchant_name  }} </td>
                     </tr>
                     @php
                            $total_cutting_qty += $TodayCutQty[0]->today_cutting_qty;
                            $total_line_issue += $TodayCutIssueQty[0]->today_cut_panel_issue;
                            $total_stitching_qty += $TodayStitchQty[0]->today_stitching_qty;
                            $total_rejection_qty += $TodayQCQty[0]->today_qcstitching_reject_qty;
                            $total_packing_qty += $TodayPackQty[0]->today_packing_qty;
                            $total_shipment_qty += $TodayShipQty[0]->today_shipment_qty;
                     @endphp
                     @endforeach
                     <input type="hidden" id="total_cutting_qty" value="{{ money_format('%!.0n',round($total_cutting_qty))}}">
                     <input type="hidden" id="total_line_issue" value="{{ money_format('%!.0n',round($total_line_issue))}}">
                     <input type="hidden" id="total_stitching_qty" value="{{money_format('%!.0n',round($total_stitching_qty))}}">
                     <input type="hidden" id="total_rejection_qty" value="{{money_format('%!.0n',round($total_rejection_qty))}}">
                     <input type="hidden" id="total_packing_qty" value="{{money_format('%!.0n',round($total_packing_qty))}}">
                     <input type="hidden" id="total_shipment_qty" value="{{money_format('%!.0n',round($total_shipment_qty))}}">
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
    $("#Cutting").html($('#total_cutting_qty').val()).toLocaleString('en-IN');
    $("#line_issue").html($('#total_line_issue').val()).toLocaleString('en-IN');
    $("#stitching").html($('#total_stitching_qty').val()).toLocaleString('en-IN');
    $("#rejection").html($('#total_rejection_qty').val()).toLocaleString('en-IN');
    $("#packing").html($('#total_packing_qty').val()).toLocaleString('en-IN');
    $("#shipement").html($('#total_shipment_qty').val()).toLocaleString('en-IN');
</script>
@endsection