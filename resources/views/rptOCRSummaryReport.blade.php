@extends('layouts.master') 
@section('content')
@php   
setlocale(LC_MONETARY, 'en_IN');  
@endphp
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">OCR  Summary  Dashboard</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">OCR  Summary  Dashboard</li>
            </ol>
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
                  <thead style="text-align:center;">
                      
                       <tr style="text-align:center; white-space:nowrap;background: bisque;" id="total_head">
                        <th colspan="7"></th>
                        <th style="text-align: right;">Total : </th>
                        <th id="head_order_qty">0</th>
                        <th id="head_order_qty_with_allow">0</th>
                        <th id="head_work_order_qty">0</th>
                        <th id="head_cut_qty">0</th>
                        <th id="head_production_qty">0</th>
                        <th id="head_pass_qty">0</th>
                        <th id="head_reject_qty">0</th>
                        <th id="head_total_production_qty">0</th>
                        <th id="head_balance_qty">0</th>
                        <th id="head_pack_qty">0</th>
                        <th id="head_pack_bal_qty">0</th>
                        <th id="head_shipment_qty">0</th>
                        <th id="head_ship_bal_qty">0</th>
                        <th id="head_leftover_qty">0</th>
                        <th id="head_cuttopack_per">0</th>
                        <th id="head_ordertopack_per">0</th>
                     
                     </tr>
                      
                  <tr>    
                     <th>Sales Order No</th>.
                     <th>Order Status</th>
                     <th>Order Close Date</th>
                     <th>Buyer Name</th>
                     <th>Main Style</th>
                     <th>Job Style</th>
                     <th>Style No</th>
                     <th>Garment Color</th>
                     <th>Order Qty</th>
                     <th>Order Qty with Allowance </th>
                     <th>Work Order  Qty</th>
                     <th>CUT Qty</th>
                     <th>Production Qty</th>
                     <th>Pass Qty</th>
                     <th>Reject Qty</th>
                     <th>Total Prod Qty</th>
                     <th>Balance Qty</th>
                     <th>Pack Qty</th>
                     <th> Packing Balance Qty</th>
                     <th>Shipment Qty</th>
                     <th>Shipment Bal Qty</th>
                     <th>Left Over Pcs</th>
                     <th>Cut To Ship %</th>
                     <th>Order To Ship %</th>
                 </tr> 
                 
                 </thead>
                  <tbody>
                     @php
                    
                        
                     $statusFilter = ' AND buyer_purchse_order_master.job_status_id IN ('.$job_status_id.')';
                     
                     if($orderTypeId != "")
                     {
                        $orderTypeFilter = ' AND buyer_purchse_order_master.order_type IN ('.$orderTypeId.')';
                     }
                     else
                     {
                        $orderTypeFilter = '';
                     }
                     
                     if($fromDate != "" && $toDate != "")
                     {
                        $dateFilter = " AND buyer_purchse_order_master.order_close_date BETWEEN '".$fromDate."' AND '".$toDate."'";
                     }
                     else
                     {
                        $dateFilter = '';
                     }
                     
          
                     // DB::enableQueryLog();

                     $Buyer_Purchase_Order_Detail_List = DB::SELECT("select buyer_purchase_order_detail.*, Ac_name,  item_master.item_name,mainstyle_name,fg_name,item_master.item_description,
                     item_master.dimension,color_master.color_name,job_status_master.job_status_name,buyer_purchse_order_master.order_close_date FROM buyer_purchase_order_detail 
                     INNER JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = buyer_purchase_order_detail.tr_code LEFT JOIN merchant_master ON merchant_master.merchant_id = buyer_purchse_order_master.merchant_id
                     LEFT JOIN ledger_master ON ledger_master.Ac_code = buyer_purchse_order_master.Ac_code LEFT JOIN main_style_master ON main_style_master.mainstyle_id = buyer_purchse_order_master.mainstyle_id 
                     LEFT JOIN fg_master ON fg_master.fg_id = buyer_purchse_order_master.fg_id LEFT JOIN job_status_master ON job_status_master.job_status_id = buyer_purchse_order_master.job_status_id 
                     LEFT JOIN item_master ON item_master.item_code = buyer_purchase_order_detail.item_code LEFT JOIN color_master ON color_master.color_id = buyer_purchase_order_detail.color_id 
                     WHERE buyer_purchse_order_master.og_id != 4 ".$statusFilter."".$orderTypeFilter."".$dateFilter);
                     
                     // dd(DB::getQueryLog());
                     
             
                     
                     $no=1; 
                     $CutToShipAvg=0;
                     $OrderToShipAvg=0;
                     
                        $totalorder_qty=0;
                        $totalorder_qty_with_allow=0;
                        $totalwork_order_qty=0;
                        $totalcut_qty=0;
                        $totalproduction_qty=0;
                        $totalpass_qty=0;
                        $totalreject_qty=0;
                        $totaltotal_production_qty=0;
                        $totalbalance_qty=0;
                        $totalpack_qty=0;
                        $totalpack_bal_qty=0;
                        $totalshipment_qty=0;
                        $totalship_bal_qty=0;
                        $totalleftover_qty=0;
                        $totalcuttopack_per=0;
                        $totalordertopack_per=0;
                        
                     @endphp
                     @foreach($Buyer_Purchase_Order_Detail_List as $List)
                     @php
                         //DB::enableQueryLog();
                         $WorkOrderQty=DB::select("select ifnull(sum(size_qty_total),0) as work_order_qty from vendor_work_order_detail where
                         vendor_work_order_detail.sales_order_no='".$List->tr_code."' and
                         vendor_work_order_detail.color_id='".$List->color_id."'");
                        
                         $CutOrderQty=  DB::select("select ifnull(sum(size_qty_total),0) as 'cut_order_qty' from cut_panel_grn_detail where
                         cut_panel_grn_detail.sales_order_no='".$List->tr_code."' and
                         cut_panel_grn_detail.color_id='".$List->color_id."'");
                         
                         $PassOrderQty=  DB::select("select ifnull(sum(size_qty_total),0) as 'pass_order_qty' from qcstitching_inhouse_detail where
                         qcstitching_inhouse_detail.sales_order_no='".$List->tr_code."' and
                         qcstitching_inhouse_detail.color_id='".$List->color_id."'");
                         
                         $RejectOrderQty= DB::select("select ifnull(sum(size_qty_total),0) as 'reject_order_qty' from qcstitching_inhouse_reject_detail where
                         qcstitching_inhouse_reject_detail.sales_order_no='".$List->tr_code."' and
                         qcstitching_inhouse_reject_detail.color_id='".$List->color_id."'");
                         
                         //$FinishOrderQty= DB::select("select ifnull(sum(size_qty_total),0) as 'finish_order_qty'from finishing_inhouse_detail where
                         //finishing_inhouse_detail.sales_order_no='".$List->tr_code."' and
                         // finishing_inhouse_detail.color_id='".$List->color_id."'");
                         
                         $PackingOrderQty=  DB::select("select ifnull(sum(size_qty_total),0) as 'packing_order_qty' from packing_inhouse_detail where
                         packing_inhouse_detail.sales_order_no='".$List->tr_code."' and
                         packing_inhouse_detail.color_id='".$List->color_id."'");
                         
                         $InvoiceQty=DB::select("select ifnull(sum(size_qty_total),0)  as 'invoice_qty' from carton_packing_inhouse_detail
                         inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_detail.cpki_code
                         where carton_packing_inhouse_detail.sales_order_no='".$List->tr_code."' and
                         carton_packing_inhouse_detail.color_id='".$List->color_id."' and
                         carton_packing_inhouse_master.endflag=1
                         ");
                        $ProductionOrderData = DB::select("SELECT sum(size_qty_total) as prod_qty from stitching_inhouse_detail WHERE sales_order_no = '".$List->tr_code."' AND color_id = '".$List->color_id."'");
                      
                      
                     @endphp
                     <tr>
                        <td style="white-space:nowrap;">{{$List->tr_code}}</td>
                        <td class="text-center">{{ $List->job_status_name }}</td>
                        <td class="text-center">{{ $List->order_close_date }}</td>
                        <td style="white-space:nowrap;"> {{$List->Ac_name}}</td>
                        <td style="white-space:nowrap;">{{$List->mainstyle_name}}</td>
                        <td style="white-space:nowrap;">{{$List->fg_name}}</td>
                        <td style="white-space:nowrap;">{{$List->style_no}}</td>
                        <td style="white-space:nowrap;">{{$List->color_name  }}</td>
                        <td style="text-align: right;">{{number_format($List->size_qty_total)}}</td>
                        <td style="text-align: right;">{{number_format($List->size_qty_total + round(($List->size_qty_total) * ($List->shipment_allowance/100)))  }}</td>
                        <td style="text-align: right;">{{number_format($WorkOrderQty[0]->work_order_qty)  }}</td>
                        <td style="text-align: right;">{{number_format($CutOrderQty[0]->cut_order_qty)  }}</td>
                        <td style="text-align: right;">{{number_format($ProductionOrderData[0]->prod_qty)  }}</td>
                        <td style="text-align: right;">{{number_format($PassOrderQty[0]->pass_order_qty)  }}</td>
                        <td style="text-align: right;">{{number_format($RejectOrderQty[0]->reject_order_qty)  }}</td>
                        <td style="text-align: right;">{{number_format(($PassOrderQty[0]->pass_order_qty + $RejectOrderQty[0]->reject_order_qty))}}</td>
                        <td style="text-align: right;">{{number_format($CutOrderQty[0]->cut_order_qty-($PassOrderQty[0]->pass_order_qty + $RejectOrderQty[0]->reject_order_qty))  }}</td>
                        <!--<td style="text-align: right;"> </td>-->
                        <td style="text-align: right;">{{number_format($PackingOrderQty[0]->packing_order_qty)  }}</td>
                        <td style="text-align: right;">{{number_format($CutOrderQty[0]->cut_order_qty - $PackingOrderQty[0]->packing_order_qty)  }}</td>
                        <td style="text-align: right;">{{number_format($InvoiceQty[0]->invoice_qty)}}</td>
                        <td style="text-align: right;">{{$PackingOrderQty[0]->packing_order_qty - $InvoiceQty[0]->invoice_qty}}</td>
                        <td style="text-align: right;">{{$PackingOrderQty[0]->packing_order_qty - $InvoiceQty[0]->invoice_qty}}</td>
                        @if($InvoiceQty[0]->invoice_qty!=0 && $CutOrderQty[0]->cut_order_qty!=0)
                        <td style="text-align: right;">{{round((($InvoiceQty[0]->invoice_qty/$CutOrderQty[0]->cut_order_qty)*100),2)  }}</td>
                        @php $CutToShipAvg=$CutToShipAvg + round((($InvoiceQty[0]->invoice_qty/$CutOrderQty[0]->cut_order_qty)*100),2); @endphp
                        @else
                        <td style="text-align: right;">0</td>
                        @endif
                        @if($InvoiceQty[0]->invoice_qty> 0 && $List->size_qty_total > 0)
                        <td style="text-align: right;">{{round((($InvoiceQty[0]->invoice_qty/$List->size_qty_total)*100),2)  }}</td>
                        @php $OrderToShipAvg=$OrderToShipAvg + round((($InvoiceQty[0]->invoice_qty/$List->size_qty_total)*100),2); @endphp
                        @else
                        <td style="text-align: right;">0</td>
                        @endif
                     </tr>
                     @php
                     $no=$no+1;
                     
                        $totalorder_qty=$totalorder_qty + $List->size_qty_total;
                        $totalorder_qty_with_allow= $totalorder_qty_with_allow + $List->size_qty_total + round(($List->size_qty_total) * ($List->shipment_allowance/100));
                        $totalwork_order_qty=$totalwork_order_qty + $WorkOrderQty[0]->work_order_qty;
                        $totalcut_qty=$totalcut_qty + $CutOrderQty[0]->cut_order_qty;
                        $totalproduction_qty=$totalproduction_qty + $ProductionOrderData[0]->prod_qty;
                        $totalpass_qty=$totalpass_qty + $PassOrderQty[0]->pass_order_qty;
                        $totalreject_qty= $totalreject_qty + $RejectOrderQty[0]->reject_order_qty;
                        $totaltotal_production_qty=$totaltotal_production_qty + 0;
                        $totalbalance_qty=$totalbalance_qty + $PassOrderQty[0]->pass_order_qty + $RejectOrderQty[0]->reject_order_qty;
                        $totalpack_qty=$totalpack_qty + $PackingOrderQty[0]->packing_order_qty;
                        $totalpack_bal_qty=$totalpack_bal_qty + $CutOrderQty[0]->cut_order_qty - $PackingOrderQty[0]->packing_order_qty;
                        $totalshipment_qty=$totalshipment_qty + $InvoiceQty[0]->invoice_qty;
                        $totalship_bal_qty= $totalship_bal_qty + $PackingOrderQty[0]->packing_order_qty - $InvoiceQty[0]->invoice_qty;
                        $totalleftover_qty=$totalleftover_qty + $PackingOrderQty[0]->packing_order_qty - $InvoiceQty[0]->invoice_qty;
                             
                     @endphp
                     @endforeach
                     @php
                     if(($no-1)!=0)
                     {
                         if($totalshipment_qty > 0 && $totalcut_qty > 0)
                         {
                            $totalcuttopack_per = round(($totalshipment_qty/$totalcut_qty)*100,2);
                         }
                         else
                         {
                            $totalcuttopack_per = 0;
                         }
                        
                         if($totalshipment_qty > 0 && $totalorder_qty > 0)
                         {
                            $totalordertopack_per= round(($totalshipment_qty/$totalorder_qty)*100,2);
                         }
                         else
                         {
                            $totalordertopack_per = 0;
                         }
                      
                     
                     }
                     @endphp
                     
                     
                     <input type="hidden" id="head_order_qty1"   value="{{money_format('%!.0n',round($totalorder_qty))}}">
                        <input type="hidden" id="head_order_qty_with_allow1"   value="{{money_format('%!.0n',round($totalorder_qty_with_allow))}}">
                        <input type="hidden" id="head_work_order_qty1"   value="{{money_format('%!.0n',round($totalwork_order_qty))}}">
                        <input type="hidden" id="head_cut_qty1"   value="{{money_format('%!.0n',round($totalcut_qty))}}">
                        <input type="hidden" id="head_production_qty1"   value="{{money_format('%!.0n',round($totalproduction_qty))}}">
                        <input type="hidden" id="head_pass_qty1"   value="{{money_format('%!.0n',round($totalpass_qty))}}">
                        <input type="hidden" id="head_reject_qty1"   value="{{money_format('%!.0n',round($totalreject_qty))}}">
                        <input type="hidden" id="head_total_production_qty1"   value="{{money_format('%!.0n',round($totaltotal_production_qty))}}">
                        <input type="hidden" id="head_balance_qty1"   value="{{money_format('%!.0n',round($totalbalance_qty))}}">
                        <input type="hidden" id="head_pack_qty1"   value="{{money_format('%!.0n',round($totalpack_qty))}}">
                        <input type="hidden" id="head_pack_bal_qty1"   value="{{money_format('%!.0n',round($totalpack_bal_qty))}}">
                        <input type="hidden" id="head_shipment_qty1"   value="{{money_format('%!.0n',round($totalshipment_qty))}}">
                        <input type="hidden" id="head_ship_bal_qty1"   value="{{money_format('%!.0n',round($totalship_bal_qty))}}">
                        <input type="hidden" id="head_leftover_qty1"   value="{{money_format('%!.0n',round($totalleftover_qty))}}">
                        <input type="hidden" id="head_cuttopack_per1"   value="{{money_format('%!i',$totalcuttopack_per)}}">
                        <input type="hidden" id="head_ordertopack_per1"   value="{{money_format('%!i',$totalordertopack_per)}}">
                  </tbody>
                  <tfoot>
                  </tfoot>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<!-- end row -->
<div class="row">
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#152d9f;" >
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">Cut To Ship Average</p>
                  <h4 class="mb-0" style="color:#fff;">{{number_format($totalcuttopack_per,2)}}% </h4>
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
                  <p class="  fw-medium" style="color:#fEf;" >Order To Ship Average</p>
                  <h4 class="mb-0" style="color:#fff;" >{{number_format($totalordertopack_per,2)}} %</h4>
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
</div>
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>

<script>
  
$('#head_order_qty').html($('#head_order_qty1').val());
$('#head_order_qty_with_allow').html($('#head_order_qty_with_allow1').val());
$('#head_work_order_qty').html($('#head_work_order_qty1').val());
$('#head_cut_qty').html($('#head_cut_qty1').val());
$('#head_production_qty').html($('#head_production_qty1').val());
$('#head_pass_qty').html($('#head_pass_qty1').val());
$('#head_reject_qty').html($('#head_reject_qty1').val());
$('#head_total_production_qty').html($('#head_total_production_qty1').val());
$('#head_balance_qty').html($('#head_balance_qty1').val());
$('#head_pack_qty').html($('#head_pack_qty1').val());
$('#head_pack_bal_qty').html($('#head_pack_bal_qty1').val());
$('#head_shipment_qty').html($('#head_shipment_qty1').val());
$('#head_ship_bal_qty').html($('#head_ship_bal_qty1').val());
$('#head_leftover_qty').html($('#head_leftover_qty1').val());
$('#head_cuttopack_per').html($('#head_cuttopack_per1').val());
$('#head_ordertopack_per').html($('#head_ordertopack_per1').val());
    
    
    
    
    
    
    
</script>



@endsection