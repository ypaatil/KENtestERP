<!DOCTYPE html>
<html lang="en">
   <head>
      @php  setlocale(LC_MONETARY, 'en_IN'); @endphp
      <meta charset="utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Ken Enterprises Pvt. Ltd.</title>
      <meta name="author" content="">
      <!-- Web Fonts
         ======================= -->
      <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900' type='text/css'>
      <!-- Stylesheet
         ======================= -->
      <link rel="stylesheet" type="text/css" href="{{ URL::asset('InvoiceAssets/bootstrap.min.css') }}"/>
      <link rel="stylesheet" type="text/css" href="{{ URL::asset('InvoiceAssets/all.min.css') }}"/>
      <link rel="stylesheet" type="text/css" href="{{ URL::asset('InvoiceAssets/style.css') }}"/>
      <style>
         .table-bordered td, .table-bordered th {
         border: 1px solid #0c0c0c;
         body{
         font-family: "Times New Roman", Times, serif;
         }
         td{
         text-align: right;
         }
         }
      </style>
   </head>
   <body>
      <!-- Container -->
      <div style="margin:10px;">
          <a  href="javascript:window.print()" class="button_niks btn  btn-info btn-rounded "> Print</a>
          <button type="button" id="export_button" class="btn btn-warning">Export</button>
         <!-- Header -->
         <div class="invoice" id="invoice">
            <!-- Main Content -->
            <main >
               <!-- Item Details -->
               <div class="row">
                  <div class="col-md-4">
                     <p><img src="http://kenerp.com/logo/ken.jpeg"  alt="Ken Enterprise Pvt. Ltd." height="130" width="230"> </p>
                     @php $FirmDetail =  DB::table('firm_master')->first(); @endphp
                  </div>
                  <div class="col-md-6">
                     <h4 class="mb-0" style="font-weight:bold; text-center">KEN GLOBAL DESIGNS PRIVATE LIMITED</h4>
                     <h6 class="mb-0"><b>Address:</b> {{$FirmDetail->Address}}</h6>
                     <h6 class="mb-0" style="margin-left:40px;"><b>GST No:</b> {{$FirmDetail->gst_no}} <b>PAN No:</b> {{$FirmDetail->pan_no}}</h6>
                  </div>
                  <div class="col-md-2">
                     <h6  style="font-weight:bold;"> </h6>
                  </div>
               </div>
               <h4 class="text-4"></h4>
               <div class=""></div>
               <style>  
                  .table{
                  display: table;
                  width:100%;
                  border-collapse:collapse;
                  }
                  .tr {
                  display: table-row;
                  padding: 2px;
                  }
                  .tr p {
                  margin: 0px !important; 
                  }
                  .td {
                  display: table-cell;
                  padding: 8px;
                  width: 410px;
                  border: #000000 solid 1px;
                  }
                  @page{
                  margin: 5px !important;
                  }
                  .merged{
                  width:25%;
                  height:25%;
                  padding: 8px;
                  display: table-cell;
                  background-image: url('http://kenerp.com/logo/ken.jpeg');background-repeat: no-repeat;background-size:cover;
                  }
               </style>
               <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">OCR Report</h4>
               @if(count($Buyer_Purchase_Order_List)>0)
               <div id="printInvoice">
                  <div class="col-md-12">
                  <div class="row" style="border: #000000 solid 1px;">
                          <div  class="col-md-4">
                            <b style="display: inline-block;text-align: left;">Sales Order no:  </b> <span style="display: inline-block;text-align: right;"> {{ $Buyer_Purchase_Order_List[0]->tr_code }} </span></br>
                            <b style="display: inline-block;text-align: left;">Buyer:  </b> <span style="display: inline-block;text-align: right;"> {{ $Buyer_Purchase_Order_List[0]->Ac_name }} </span></br>
                            <b style="display: inline-block;text-align: left;">Style: </b>  <span style="display: inline-block;text-align: right;">{{  $Buyer_Purchase_Order_List[0]->style_no }} </span></br>
                            <b style="display: inline-block;text-align: left;">Currency:</b>    <span style="display: inline-block;text-align: right;">{{ $currency_name }} </span></br>   <b style="display: inline-block;text-align: left;">Rate ({{$currency_name}}):</b>    <span style="display: inline-block;text-align: right;">{{ money_format('%!i',$inr_rate) }} </span></br>
                         </div>
                         <div  class="col-md-4"> 
                            <b style="display: inline-block;text-align: left;">Exchange Rate:</b>    <span style="display: inline-block;text-align: right;">{{  $exchange_rate }} </span></br>
                            <b style="display: inline-block;text-align: left;">Order Received Date:</b>    <span style="display: inline-block;text-align: right;">{{ date("d-m-Y", strtotime($Buyer_Purchase_Order_List[0]->order_received_date)) }} </span></br>
                            <b style="display: inline-block;text-align: left;">Main Style Category: </b>  <span style="display: inline-block;text-align: right;">{{  $mainstyle_name }} </span></br>
                            <b style="display: inline-block;text-align: left;">Sub Style Category :</b>  <span style="display: inline-block;text-align: right;">{{ $substyle_name }} </span></br>
                            <b style="display: inline-block;text-align: left;">Style No:</b>    <span style="display: inline-block;text-align: right;">{{ $Buyer_Purchase_Order_List[0]->style_no }} </span></br>
                         </div>
                         <div  class="col-md-4">
                            <b style="display: inline-block;text-align: left;">Style Description:</b>  <span style="display: inline-block;text-align: right;">{{ $Buyer_Purchase_Order_List[0]->style_description }}</span></br>
                            <b style="display: inline-block;text-align: left;">Garment SAM:</b>    <span style="display: inline-block;text-align: right;">{{ $Buyer_Purchase_Order_List[0]->sam }} </span> </br>
                            <b style="display: inline-block;text-align: left;">Order Qty:</b>  <span style="display: inline-block;text-align: right;">{{ money_format('%!i',$Buyer_Purchase_Order_List[0]->total_qty) }}</span></br>
                            <b style=" text-align: left;" class="mt-1">FOB Rate:</b>  <span style="display: inline-block;text-align: right;" id="fob_rate">{{$Buyer_Purchase_Order_List[0]->order_rate}}</span><br>
                         </div>
                      </div> 
                  </div> 
               </div>
               <center>
                  <h4 style="font-weight:bold;">Summary Report</h4>
               </center>
               <table class="table table-bordered text-1 table-sm" id="Summary0" style="height:10vh; ">
                  <thead style="text-align:center;">
                     <th>Garment Color</th>
                     <th>Order Qty</th>
                     <th>Order Qty with Allowance </th>
                     <th>Work Order  Qty</th>
                     <th>CUT Qty</th>
                     <th>Production Qty</th>
                     <th>Pass Qty</th>
                     <th>Reject Qty</th>
                     <th>Total Prod Qty</th>
                     <th>Sewing Balance Qty</th>
                     <th>Washing Qty</th>
                     <th>Pack Qty</th>
                     <th>Packing Balance Qty</th>
                     <th>Shipment Qty</th>
                     <th>Shipment Bal Qty</th>
                     <th nowrap>Left Over QC<br/> Pass Pcs</th>
                     <th>Cut To Ship %</th>
                     <th>Order To Ship %</th>
                  </thead>
                  <tbody>
                     @php
                     //DB::enableQueryLog();
                   
                     //dd(DB::getQueryLog());
                     //  DB::raw('(select ifnull(sum(size_qty_total),0) from finishing_inhouse_detail where
                     //finishing_inhouse_detail.sales_order_no=buyer_purchase_order_detail.tr_code and
                     // finishing_inhouse_detail.color_id=buyer_purchase_order_detail.color_id
                     // ) as finish_order_qty'),
                     
                 
                     $no=1;    $passQty=0;$rejectQty=0;
                     $totalOrderQty = 0;
                     $leftOverPassPcsQty = 0;
                     $leftOverRejectionPcsQty = 0;
                     @endphp
                     <input type="hidden" id="s1AllCount" value="{{count($Buyer_Purchase_Order_List)}}">
                     @foreach($Buyer_Purchase_Order_List as $List)
                     @php
                    $work_order_qtyData = DB::select("select ifnull(sum(size_qty_total),0) as work_order_qty from vendor_work_order_detail where
                             vendor_work_order_detail.sales_order_no = '".$List->tr_code."' and
                             vendor_work_order_detail.color_id= '".$List->color_id."'");
                             
                    $cut_order_qtyData = DB::select("select ifnull(sum(size_qty_total),0) as cut_order_qty from cut_panel_grn_detail where
                             cut_panel_grn_detail.sales_order_no = '".$List->tr_code."' and
                             cut_panel_grn_detail.color_id = '".$List->color_id."'");
        
                    $pass_order_qtyData = DB::select("select ifnull(sum(size_qty_total),0) as pass_order_qty from qcstitching_inhouse_detail where
                             qcstitching_inhouse_detail.sales_order_no = '".$List->tr_code."' and
                             qcstitching_inhouse_detail.color_id = '".$List->color_id."'");        
                             
                    $reject_order_qtyData = DB::select("select ifnull(sum(size_qty),0) as reject_order_qty from qcstitching_inhouse_size_reject_detail2 where
                             qcstitching_inhouse_size_reject_detail2.sales_order_no = '".$List->tr_code."' and
                             qcstitching_inhouse_size_reject_detail2.color_id = '".$List->color_id."'");
                    
                    $packing_order_qtyData = DB::select("select ifnull(sum(size_qty_total),0) as packing_order_qty from packing_inhouse_detail where
                             packing_inhouse_detail.sales_order_no = '".$List->tr_code."' and
                             packing_inhouse_detail.color_id = '".$List->color_id."'");
                    // DB::enableQueryLog();
                    $invoice_qtyData = DB::select("SELECT ifnull(sum(size_qty),0) as invoice_qty FROM FGStockDataByTwo WHERE data_type_id = 2 AND is_sale=0 AND sales_order_no = '".$List->tr_code."' AND color_id = '".$List->color_id."' AND invoice_no IS NOT NULL");
                    // dd(DB::getQueryLog());
                        
                    $rejectedData = DB::select("SELECT sum((select qcstitching_inhouse_size_reject_detail2.size_qty
                        from  qcstitching_inhouse_size_reject_detail2 where qcsti_code=qcstitching_inhouse_size_detail2.qcsti_code
                        and qcstitching_inhouse_size_reject_detail2.color_id=qcstitching_inhouse_size_detail2.color_id  
                        and  qcstitching_inhouse_size_reject_detail2.size_id = qcstitching_inhouse_size_detail2.size_id )) as reject_order_qty
                        FROM qcstitching_inhouse_size_detail2
                        WHERE qcstitching_inhouse_size_detail2.sales_order_no = '".$List->tr_code."' AND qcstitching_inhouse_size_detail2.color_id=".$List->color_id);
                        
                        if(count($rejectedData) > 0)
                        {
                            $reject_qty = $rejectedData[0]->reject_order_qty;
                        }
                        else
                        {
                           $reject_qty = 0;
                        }
    
                    $ProductionOrderData = DB::select("SELECT sum(size_qty_total) as prod_qty from stitching_inhouse_detail WHERE sales_order_no = '".$List->tr_code."' AND color_id = '".$List->color_id."'");
                    
                    $WashingData = DB::select("SELECT sum(size_qty_total) as wash_qty from washing_inhouse_detail WHERE sales_order_no = '".$List->tr_code."' AND color_id = '".$List->color_id."'");
                  
                    $BuyerDetailData = DB::select("SELECT sum(size_qty_total) as order_qty,sum(shipment_allowance) as shipment_allowance from buyer_purchase_order_size_detail WHERE tr_code = '".$List->tr_code."' AND color_id = '".$List->color_id."'");
                     
                       //$SaleTransactionData = DB::select("SELECT order_qty from sale_transaction_detail WHERE sales_order_no = '".$List->tr_code."' AND  sale_code = '".$List->sale_code."' group by sale_transaction_detail.sale_code");
                    
                    $totalOrderQty += $List->size_qty_total;
                     
                    $cut_order_qty = isset($cut_order_qtyData[0]->cut_order_qty) ? $cut_order_qtyData[0]->cut_order_qty : 0;
                    @endphp
                     <tr>
                        <td>{{$List->color_name  }} - {{$List->color_id}}</td>
                        <td style="text-align: right;">{{money_format('%!i',$List->size_qty_total)}}</td> 
                        <td style="text-align: right;">{{number_format($List->size_qty_total + round(($List->size_qty_total) * ($List->shipment_allowance/100))) }}</td>
                        <td style="text-align: right;">{{money_format('%!i',$work_order_qtyData[0]->work_order_qty)  }}</td>
                        <td style="text-align: right;">{{money_format('%!i',$cut_order_qty)  }}</td>
                        <td style="text-align: right;">{{money_format('%!i',$ProductionOrderData[0]->prod_qty)}}</td>
                        <td style="text-align: right;">{{money_format('%!i',$pass_order_qtyData[0]->pass_order_qty)  }}</td>
                        <td style="text-align: right;">{{money_format('%!i',$reject_qty)  }}</td>
                        <td style="text-align: right;">{{money_format('%!i',($pass_order_qtyData[0]->pass_order_qty + $reject_qty))}}</td>
                        <td style="text-align: right;">{{money_format('%!i',$cut_order_qty-($pass_order_qtyData[0]->pass_order_qty + $reject_qty))  }}</td>
                        <td style="text-align: right;">{{money_format('%!i',$WashingData[0]->wash_qty)  }}</td>
                        <td style="text-align: right;">{{money_format('%!i',$packing_order_qtyData[0]->packing_order_qty)  }}</td>
                        <td style="text-align: right;">{{money_format('%!i',$cut_order_qty - $packing_order_qtyData[0]->packing_order_qty)  }}</td>
                        <td style="text-align: right;">{{money_format('%!i',$invoice_qtyData[0]->invoice_qty)}} </td>
                        <td style="text-align: right;">{{money_format('%!i',$packing_order_qtyData[0]->packing_order_qty -$invoice_qtyData[0]->invoice_qty)}}</td>
                        <td style="text-align: right;">{{money_format('%!i',$pass_order_qtyData[0]->pass_order_qty - $invoice_qtyData[0]->invoice_qty)}}</td>
                        @if($invoice_qtyData[0]->invoice_qty > 0 && $cut_order_qty > 0)
                        
                        <td style="text-align: right;">{{round((($invoice_qtyData[0]->invoice_qty/$cut_order_qty)*100),2)  }}</td>
                        @else
                        <td style="text-align: right;">0</td>
                        @endif
                        @if($invoice_qtyData[0]->invoice_qty > 0 && $List->size_qty_total > 0)
                        <td style="text-align: right;">{{round((($invoice_qtyData[0]->invoice_qty/$List->size_qty_total)*100),2)  }}</td>
                        @else
                        <td style="text-align: right;">0</td>
                        @endif
                     </tr>
                     @php
                     $passQty=$passQty + $List->pass_order_qty;
                     $leftOverPassPcsQty += (($pass_order_qtyData[0]->pass_order_qty - $invoice_qtyData[0]->invoice_qty) * $Buyer_Purchase_Order_List[0]->order_rate);
                     $leftOverRejectionPcsQty += $reject_qty * $Buyer_Purchase_Order_List[0]->order_rate;
                     @endphp
                     @endforeach
                  </tbody>
                  <tfoot></tfoot>
               </table>
               <div id="Fabric">
                  <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Fabric Details:</h4>
                  <table class="table table-bordered text-1 table-sm" style="height:10vh;" id="Summary1" >
                     <thead>
                        <tr style="background-color:#eee; text-align:center;">
                           <th>SrNo</th>
                           <th>Item Code</th>
                           <th>Classification</th> 
                           <th>Item Name</th>
                           <th>PO No.</th>
                           <th>PO Rate</th>
                           <th>Allocated Qty</th>
                           <th>Allocated Value</th>
                           <th>Issue Qty</th>
                           <th>Issue Value</th>
                           <th>Avaliable Qty</th>
                           <th>Avaliable Value</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php 
                     
                        //DB::enableQueryLog();
                        $FabricList = DB::select("SELECT sum(fabric_outward_details.meter) as issue_meter,avg(fabric_outward_details.item_rate) as avg_rate ,inward_details.po_code,inward_details.item_code,item_name,class_name FROM inward_details 
                                                INNER JOIN fabric_outward_details ON fabric_outward_details.track_code = inward_details.track_code 
                                                INNER JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = fabric_outward_details.vpo_code 
                                                INNER JOIN item_master ON item_master.item_code = inward_details.item_code
                                                INNER join classification_master on classification_master.class_id=item_master.class_id
                                                WHERE vendor_purchase_order_master.sales_order_no = '".$Buyer_Purchase_Order_List[0]->tr_code."' GROUP BY inward_details.po_code,inward_details.item_code");
                        
                        //dd(DB::getQueryLog());
                        $no=1; 
                        $totalAllocatedStockQty = 0;
                        $totalAllocatedStockValue = 0;
                        $totalIssueStockQty = 0;
                        $totalIssueStockValue = 0;
                        $totalAvaliableStockQty = 0;
                        $totalAvaliableStockValue = 0;
                        @endphp
                        @foreach($FabricList as $rowDetail)  
                        @php
                            
                           
                        // $stockData = DB::SELECT("SELECT sum(qty) as qty FROM stock_association_for_fabric WHERE `po_code` = '".$rowDetail->po_code."'  AND item_code='".$rowDetail->item_code."' AND sales_order_no='".$Buyer_Purchase_Order_List[0]->tr_code."' AND class_id=2 AND cat_id=1 AND tr_type=1");
                           $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association_for_fabric WHERE `po_code` = '".$rowDetail->po_code."'  AND item_code='".$rowDetail->item_code."' AND sales_order_no='".$Buyer_Purchase_Order_List[0]->tr_code."' AND tr_type=1");
                 
                           $allocated_qty = isset($allocatedStockData[0]->allocated_qty) ? $allocatedStockData[0]->allocated_qty : 0;
                           $avalibleData = DB::SELECT("SELECT  ifnull(sum(qty),0) as other_allocated_qty FROM stock_association_for_fabric WHERE po_code='".$rowDetail->po_code."' AND item_code='".$rowDetail->item_code."' AND sales_order_no!='".$Buyer_Purchase_Order_List[0]->tr_code."' AND tr_type = 1"); 
                           
                           $fabricOutwardData = DB::SELECT("SELECT ifnull(sum(fabric_outward_details.meter),0) as outward_qty FROM fabric_outward_details
                                    INNER JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = fabric_outward_details.vpo_code 
                                    INNER JOIN fabric_checking_details ON fabric_checking_details.track_code = fabric_outward_details.track_code 
                                    WHERE  fabric_outward_details.item_code='".$rowDetail->item_code."' and fabric_checking_details.po_code='".$rowDetail->po_code."' AND vendor_purchase_order_master.sales_order_no='".$Buyer_Purchase_Order_List[0]->tr_code."' GROUP BY fabric_outward_details.item_code"); 
                           
                           $otherAvaliableStock = isset($avalibleData[0]->other_allocated_qty) ? $avalibleData[0]->other_allocated_qty : 0;
                           $fabricOutwardStock = isset($fabricOutwardData[0]->outward_qty) ? $fabricOutwardData[0]->outward_qty : 0;
                         //  $totalAssoc = isset($stockData[0]->qty) ? $stockData[0]->qty : 0;
                           
                           
                            $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association_for_fabric WHERE po_code='".$rowDetail->po_code."'  AND item_code='".$rowDetail->item_code."' AND sales_order_no='".$Buyer_Purchase_Order_List[0]->tr_code."' AND tr_type = 2  AND tr_code IS NULL"); 
                            $eachAvaliableQty = isset($eachData[0]->each_qty) ? $eachData[0]->each_qty : 0;
                        
                           
                           $remainStock = $allocated_qty - $eachAvaliableQty;
                           $inwardData = DB::table('purchaseorder_detail')->select('item_rate')->where('item_code',"=",$rowDetail->item_code)->where('pur_code',"=",$rowDetail->po_code)->first(); 
                           
                           $inward_rate = isset($inwardData->item_rate) ? $inwardData->item_rate : 0;
                           $avilable_stock = $remainStock - $fabricOutwardStock;
                           
                           $fabricAllocated_value = $remainStock * $inward_rate;
                           $fabricOutwardValue = $fabricOutwardStock * $inward_rate;
                                
                           $fabricAllocated_qty = $remainStock; 
                           $fabricOutward_qty = $fabricOutwardStock; 
         
                        @endphp
                        <tr>
                           <td>{{ $no }}</td>
                           <td>{{ $rowDetail->item_code }}</td>
                           <td nowrap>{{ $rowDetail->class_name }}</td> 
                           <td nowrap>{{ $rowDetail->item_name }}</td>
                           <td nowrap>{{ $rowDetail->po_code}}</td> 
                           <td nowrap>{{ round($inward_rate,4)}}</td> 
                           <td style="text-align:right"> {{money_format('%!i',$fabricAllocated_qty)}}</td>
                           <td style="text-align:right"> {{money_format('%!i',$fabricAllocated_value)}}</td>
                           <td style="text-align:right"> {{money_format('%!i',$fabricOutward_qty)}}</td>
                           <td style="text-align:right"> {{money_format('%!i',$fabricOutwardValue)}}</td>
                           <td style="text-align:right"> {{money_format('%!i',$fabricAllocated_qty - $fabricOutward_qty)}}</td>
                           <td style="text-align:right"> {{money_format('%!i',$fabricAllocated_value - $fabricOutwardValue)}}</td>
                        </tr>
                        @php
                        $no=$no+1;
                        
                        $totalAllocatedStockQty += $fabricAllocated_qty;
                        $totalAllocatedStockValue += $fabricAllocated_value;
                        $totalIssueStockQty += $fabricOutward_qty;
                        $totalIssueStockValue += $fabricOutwardValue;
                        $totalAvaliableStockQty += $fabricAllocated_qty - $fabricOutward_qty;
                        $totalAvaliableStockValue += $fabricAllocated_value - $fabricOutwardValue;
                        @endphp
                        @endforeach
                        <tr> 
                           <td colspan="6"><b>Total</b></td> 
                           <td style="text-align:right"><b> {{money_format('%!i',$totalAllocatedStockQty)}}</b></td>
                           <td style="text-align:right"><b> {{money_format('%!i',$totalAllocatedStockValue)}}</b></td>
                           <td style="text-align:right"><b> {{money_format('%!i',$totalIssueStockQty)}}</b></td>
                           <td style="text-align:right"><b> {{money_format('%!i',$totalIssueStockValue)}}</b></td>
                           <td style="text-align:right"><b> {{money_format('%!i',$totalAvaliableStockQty)}}</b></td>
                           <td style="text-align:right"><b> {{money_format('%!i',$totalAvaliableStockValue)}}</b></td>
                        </tr> 
                     </tbody> 
                  </table>
               </div>
               <div id="Sewing">
                  <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Sewing Trims Details:</h4>
                  <table class="table table-bordered text-1 table-sm" style="height:10vh;" id="Summary2" >
                     <thead>
                        <tr  style="background-color:#eee; text-align:center;">
                           <th>SrNo</th>
                           <th>Item Code</th>
                           <th>Classification</th>
                           <th>Item Name</th> 
                           <th>PO No.</th>
                           <th>PO Rate</th>
                           <th>Allocated Qty</th>
                           <th>Allocated Value</th>
                           <th>Issue Qty</th>
                           <th>Issue Value</th>
                           <th>Avaliable Qty</th>
                           <th>Avaliable Value</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php 
                        
                            // DB::enableQueryLog();
                      $SewingList = DB::select("SELECT 
                                    SUM(stock_association.qty) AS issue_qty,
                                    stock_association.item_code,
                                    stock_association.po_code,
                                    item_master.item_name,
                                    classification_master.class_name,
                                    stock_association.sales_order_no,
                                    IFNULL(SUM(CASE WHEN stock_association.tr_type = 1 THEN stock_association.qty ELSE 0 END), 0) AS allocated_qty,
                                    IFNULL(SUM(CASE WHEN stock_association.sales_order_no != '".$Buyer_Purchase_Order_List[0]->tr_code."' THEN stock_association.qty ELSE 0 END), 0) AS other_allocated_qty,
                                    IFNULL
                                    ((SELECT SUM(tod.item_qty) 
                                                FROM trimsOutwardDetail tod
                                                INNER JOIN vendor_work_order_master vwo 
                                                    ON vwo.vw_code = tod.vw_code
                                                WHERE tod.item_code = stock_association.item_code 
                                                  AND tod.po_code =  stock_association.po_code
                                                  AND vwo.sales_order_no = '".$Buyer_Purchase_Order_List[0]->tr_code."'
                                                  AND tod.trim_type = 1
                                                UNION ALL
                                                SELECT SUM(tod.item_qty) 
                                                FROM trimsOutwardDetail tod
                                                INNER JOIN vendor_purchase_order_master vpo 
                                                    ON vpo.vpo_code = tod.vpo_code
                                                WHERE tod.item_code = stock_association.item_code 
                                                  AND tod.po_code =  stock_association.po_code
                                                  AND vpo.sales_order_no = '".$Buyer_Purchase_Order_List[0]->tr_code."'
                                                  AND tod.trim_type = 2 GROUP BY tod.item_code), 0) AS outward_qty,
                                    
                                    IFNULL(SUM(CASE WHEN stock_association.tr_type = 2 AND stock_association.tr_code IS NULL THEN stock_association.qty ELSE 0 END), 0) AS each_qty
                                FROM stock_association
                                LEFT JOIN item_master ON item_master.item_code = stock_association.item_code
                                LEFT JOIN classification_master ON classification_master.class_id = item_master.class_id
                                WHERE stock_association.sales_order_no = '".$Buyer_Purchase_Order_List[0]->tr_code."'
                                AND item_master.cat_id = 2
                                GROUP BY stock_association.item_code, stock_association.po_code
                            ");
                           //dd(DB::getQueryLog());
                        //$SewingList = DB::select("SELECT purchaseorder_detail.item_rate,item_master.item_name,class_name,dump_trims_stock_association.po_code,dump_trims_stock_association.bom_code,dump_trims_stock_association.item_code, 
                            //                    allocated_qty,totalAssoc,otherAvaliableStock,trimOutwardStock,eachAvaliableQty 
                            //                    FROM dump_trims_stock_association  
                            //                    LEFT JOIN item_master ON item_master.item_code = dump_trims_stock_association.item_code 
                            //                    LEFT join classification_master on classification_master.class_id=item_master.class_id 
                            //                    LEFT join purchaseorder_detail on purchaseorder_detail.item_code=dump_trims_stock_association.item_code 
                            //                    WHERE item_master.cat_id = 2 AND dump_trims_stock_association.sales_order_no= '".$Buyer_Purchase_Order_List[0]->tr_code."'
                             //                   GROUP BY dump_trims_stock_association.item_code,dump_trims_stock_association.po_code ORDER BY dump_trims_stock_association.item_code ASC");
                           
                        $no=1;
                        $totalTrimsAllocatedStockQty = 0;
                        $totalTrimsAllocatedStockValue = 0;
                        $totalTrimsIssueStockQty = 0;
                        $totalTrimsIssueStockValue = 0;
                        $totalTrimsAvaliableStockQty = 0;
                        $totalTrimsAvaliableStockValue = 0;
                        
                        @endphp
                        @foreach($SewingList as $rowDetail)  
                        @php
                        
                           
                           if($rowDetail->po_code != "" && $rowDetail->item_code > 0)
                           {  
                               // Calculate values for each item
                                $allocated_qty = $rowDetail->allocated_qty;
                                $other_allocated_qty = $rowDetail->other_allocated_qty;
                                $outward_qty = $rowDetail->outward_qty;
                                $each_qty = $rowDetail->each_qty;
                                
                                // Calculate remaining stock and other values
                                $remainStock = $allocated_qty - $each_qty;
                                $avilable_stock = $remainStock - $outward_qty;
                                
                                // Fetch inward rate for the item
                                $inwardData = DB::table('purchaseorder_detail')
                                    ->select('item_rate')
                                    ->where('item_code', '=', $rowDetail->item_code)
                                    ->where('pur_code', '=', $rowDetail->po_code)
                                    ->first();
                                
                                $inward_rate = isset($inwardData->item_rate) ? $inwardData->item_rate : 0;
                                
                                // Calculate allocated and outward values
                                $fabricAllocated_value = $remainStock * $inward_rate;
                                $fabricOutwardValue = $outward_qty * $inward_rate;
                                
                                // Trim stock calculations
                                $trimsRemainStock = $allocated_qty - $each_qty;
                                $trimsAllocated_value = $trimsRemainStock * $inward_rate;
                                $trimsOutwardValue = $outward_qty * $inward_rate;
                                
                                // Prepare the final values for each item
                                $trimAllocated_qty = $trimsRemainStock;
                                $trimOutward_qty = $outward_qty;
                                $trims_avilable_stock = ($trimsRemainStock - $outward_qty) * $inward_rate;
                               
                                $avaliableData = DB::SELECT("SELECT ((SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association WHERE po_code='".$rowDetail->po_code."'  AND item_code=".$rowDetail->item_code." AND sales_order_no= '".$Buyer_Purchase_Order_List[0]->tr_code."' AND tr_type = 1)
                                                            - (SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association WHERE po_code='".$rowDetail->po_code."'  AND item_code=".$rowDetail->item_code." AND sales_order_no= '".$Buyer_Purchase_Order_List[0]->tr_code."' AND tr_type = 2  AND tr_code IS NULL)) as avaliable");
                                                            
                                $avaliable_qty = isset($avaliableData[0]->avaliable) ? $avaliableData[0]->avaliable : 0;
                        @endphp
                        <tr>
                           <td>{{ $no++ }}</td>
                           <td>{{ $rowDetail->item_code }}</td>
                           <td nowrap>{{ $rowDetail->class_name }}</td> 
                           <td nowrap>{{ $rowDetail->item_name }}</td>
                           <td nowrap>{{ $rowDetail->po_code }}</td> 
                           <td style="text-align:right"> {{round($inward_rate, 4)}}</td>
                           <td style="text-align:right"> {{money_format('%!i',$avaliable_qty)}}</td>
                           <td style="text-align:right"> {{money_format('%!i',$trimsAllocated_value)}}</td>
                           <td style="text-align:right"> {{money_format('%!i',$outward_qty)}}</td>
                           <td style="text-align:right"> {{money_format('%!i',$trimsOutwardValue)}}</td>
                           <td style="text-align:right"> {{money_format('%!i',$avaliable_qty - $outward_qty)}}</td>
                           <td style="text-align:right"> {{money_format('%!i',$trimsAllocated_value - $trimsOutwardValue)}}</td>
                        
                        </tr>
                        @php
                            $totalTrimsAllocatedStockQty += $avaliable_qty;
                            $totalTrimsAllocatedStockValue += $trimsAllocated_value;
                            $totalTrimsIssueStockQty += $outward_qty;
                            $totalTrimsIssueStockValue += $trimsOutwardValue;
                            $totalTrimsAvaliableStockQty += $avaliable_qty - $outward_qty;
                            $totalTrimsAvaliableStockValue += $trimsAllocated_value - $trimsOutwardValue;

                        }    
                        @endphp 
                        @endforeach
                        <tr> 
                           <td colspan="6"><b>Total</b></td> 
                           <td style="text-align:right"><b> {{money_format('%!i',$totalTrimsAllocatedStockQty)}}</b></td>
                           <td style="text-align:right"><b> {{money_format('%!i',$totalTrimsAllocatedStockValue)}}</b></td>
                           <td style="text-align:right"><b> {{money_format('%!i',$totalTrimsIssueStockQty)}}</b></td>
                           <td style="text-align:right"><b> {{money_format('%!i',$totalTrimsIssueStockValue)}}</b></td>
                           <td style="text-align:right"><b> {{money_format('%!i',$totalTrimsAvaliableStockQty)}}</b></td>
                           <td style="text-align:right"><b> {{money_format('%!i',$totalTrimsAvaliableStockValue)}}</b></td>
                        </tr> 
                     </tbody>
                  </table>
               </div>
               <div id="Packing">
                  <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Packing Trims Details:</h4>
                  <table class="table table-bordered text-1 table-sm" style="height:10vh; "  id="Summary3">
                     <thead>
                        <tr  style="background-color:#eee; text-align:center;">
                           <th>Sr No</th>
                           <th>Item Code</th>
                           <th>Classification</th> 
                           <th>Item Name</th>
                           <th>PO No.</th>
                           <th>PO Rate</th>
                           <th>Allocated Qty</th>
                           <th>Allocated Value</th>
                           <th>Issue Qty</th>
                           <th>Issue Value</th>
                           <th>Avaliable Qty</th>
                           <th>Avaliable Value</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php 
                    
                         //$PackingList = DB::select("SELECT sum(trimsOutwardDetail.item_qty) as issue_qty,trimsOutwardDetail.item_code,trimsOutwardDetail.po_code,item_name,class_name FROM trimsOutwardDetail 
                            //                INNER JOIN vendor_purchase_order_master ON  vendor_purchase_order_master.vpo_code = trimsOutwardDetail.vpo_code
                            //                INNER JOIN item_master ON item_master.item_code = trimsOutwardDetail.item_code 
                           //                 INNER join classification_master on classification_master.class_id=item_master.class_id 
                            //                WHERE vendor_purchase_order_master.sales_order_no = '".$Buyer_Purchase_Order_List[0]->tr_code."' AND item_master.cat_id = 3
                             //               GROUP BY trimsOutwardDetail.item_code,trimsOutwardDetail.po_code");
                        
                        
                             
                        $PackingList = DB::select("SELECT 
                                            SUM(stock_association.qty) AS issue_qty,
                                            stock_association.item_code,
                                            stock_association.po_code,
                                            item_master.item_name,
                                            classification_master.class_name,
                                            stock_association.sales_order_no,
                                            IFNULL(SUM(CASE WHEN stock_association.tr_type = 1 THEN stock_association.qty ELSE 0 END), 0) AS allocated_qty,
                                            IFNULL(SUM(CASE WHEN stock_association.sales_order_no != '".$Buyer_Purchase_Order_List[0]->tr_code."' THEN stock_association.qty ELSE 0 END), 0) AS other_allocated_qty,
                                            IFNULL((SELECT sum(trimsOutwardDetail.item_qty) FROM trimsOutwardDetail INNER JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = trimsOutwardDetail.vpo_code
                                            WHERE trimsOutwardDetail.item_code=stock_association.item_code AND trimsOutwardDetail.po_code=stock_association.po_code AND vendor_purchase_order_master.sales_order_no = '".$Buyer_Purchase_Order_List[0]->tr_code."'), 0) AS outward_qty,
                                            IFNULL(SUM(CASE WHEN stock_association.tr_type = 3 AND stock_association.tr_code IS NULL THEN stock_association.qty ELSE 0 END), 0) AS each_qty
                                        FROM stock_association
                                        LEFT JOIN item_master ON item_master.item_code = stock_association.item_code 
                                        LEFT JOIN classification_master ON classification_master.class_id = item_master.class_id 
                                        WHERE stock_association.sales_order_no = '".$Buyer_Purchase_Order_List[0]->tr_code."'
                                        AND item_master.cat_id = 3 GROUP BY stock_association.item_code, stock_association.po_code");

                        
                         
                                     
                        //$PackingList = DB::select("SELECT purchaseorder_detail.item_rate,item_master.item_name,class_name,dump_trims_stock_association.po_code,dump_trims_stock_association.bom_code,dump_trims_stock_association.item_code, 
                         //                       allocated_qty,totalAssoc,otherAvaliableStock,trimOutwardStock,eachAvaliableQty 
                          //                      FROM dump_trims_stock_association  
                           //                     LEFT JOIN item_master ON item_master.item_code = dump_trims_stock_association.item_code 
                           //                     LEFT join classification_master on classification_master.class_id=item_master.class_id 
                            //                    LEFT join purchaseorder_detail on purchaseorder_detail.item_code=dump_trims_stock_association.item_code 
                            //                    WHERE item_master.cat_id = 3  AND dump_trims_stock_association.sales_order_no= '".$Buyer_Purchase_Order_List[0]->tr_code."' GROUP BY dump_trims_stock_association.item_code  ORDER BY dump_trims_stock_association.item_code ASC");
                           
                        $no=1; 
                        $totalPackingTrimsAllocatedStockQty = 0;
                        $totalPackingTrimsAllocatedStockValue = 0;
                        $totalPackingTrimsIssueStockQty = 0;
                        $totalPackingTrimsIssueStockValue = 0;
                        $totalPackingTrimsAvaliableStockQty = 0;
                        $totalPackingTrimsAvaliableStockValue = 0;
                        @endphp
                        @foreach($PackingList as $rowDetail) 
                        @php
                           if($rowDetail->po_code != "")
                           {
                              
                                $allocated_qty = $rowDetail->allocated_qty;
                                $other_allocated_qty = $rowDetail->other_allocated_qty;
                                $outward_qty = $rowDetail->outward_qty;
                                $each_qty = $rowDetail->each_qty;
                                
                                $remainStock = $allocated_qty - $each_qty;
                                $avilable_stock = $remainStock - $outward_qty;
                                
                                // Fetch inward rate
                                $inwardData = DB::table('purchaseorder_detail')
                                    ->select('item_rate')
                                    ->where('item_code', $rowDetail->item_code)
                                    ->where('pur_code', $rowDetail->po_code)
                                    ->first();
                                
                                $item_ratePacking = isset($inwardData->item_rate) ? $inwardData->item_rate : 0;
                                
                                $trimsRemainStockPacking = $allocated_qty - $each_qty;
                                $trimsAllocated_value = $rowDetail->allocated_qty * $item_ratePacking;
                                $trimsOutwardValue = $outward_qty * $item_ratePacking;
                                $trimsPacking_avilable_stock = ($trimsRemainStockPacking - $outward_qty) * $item_ratePacking;
                                
                                
                                $avaliableData = DB::SELECT("SELECT ((SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association WHERE po_code='".$rowDetail->po_code."'  AND item_code=".$rowDetail->item_code." AND sales_order_no= '".$Buyer_Purchase_Order_List[0]->tr_code."' AND tr_type = 1)
                                                            - (SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association WHERE po_code='".$rowDetail->po_code."'  AND item_code=".$rowDetail->item_code." AND sales_order_no= '".$Buyer_Purchase_Order_List[0]->tr_code."' AND tr_type = 2  AND tr_code IS NULL)) as avaliable");
                                                            
                                $avaliable_qty = isset($avaliableData[0]->avaliable) ? $avaliableData[0]->avaliable : 0;
                                
                        @endphp
                        <tr>
                           <td>{{ $no++ }}</td>
                           <td>{{ $rowDetail->item_code }}</td>
                           <td nowrap>{{ $rowDetail->class_name }}</td> 
                           <td nowrap>{{ $rowDetail->item_name }}</td>
                           <td nowrap>{{ $rowDetail->po_code }}</td>
                           <td style="text-align:right"> {{round($item_ratePacking, 4)}} </td>
                           <td style="text-align:right"> {{money_format('%!i',$avaliable_qty)}}</td>
                           <td style="text-align:right"> {{money_format('%!i',$avaliable_qty * $item_ratePacking )}}</td>
                           <td style="text-align:right"> {{money_format('%!i',$outward_qty)}}</td>
                           <td style="text-align:right"> {{money_format('%!i',$trimsOutwardValue)}}</td>
                           <td style="text-align:right"> {{money_format('%!i',$avaliable_qty - $outward_qty)}}</td>
                           <td style="text-align:right"> {{money_format('%!i',$trimsAllocated_value - $trimsOutwardValue)}}</td>
                        
                        </tr>
                        @php
                            $totalPackingTrimsAllocatedStockQty += $avaliable_qty;
                            $totalPackingTrimsAllocatedStockValue += $avaliable_qty * $item_ratePacking;
                            $totalPackingTrimsIssueStockQty += $outward_qty;
                            $totalPackingTrimsIssueStockValue += $trimsOutwardValue;
                            $totalPackingTrimsAvaliableStockQty += $avaliable_qty - $outward_qty;
                            $totalPackingTrimsAvaliableStockValue += $trimsAllocated_value - $trimsOutwardValue;

                        }    
                        @endphp 
                        @endforeach
                        <tr> 
                           <td colspan="6"><b>Total</b></td> 
                           <td style="text-align:right"><b> {{money_format('%!i',$totalPackingTrimsAllocatedStockQty)}}</b></td>
                           <td style="text-align:right"><b> {{money_format('%!i',$totalPackingTrimsAllocatedStockValue)}}</b></td>
                           <td style="text-align:right"><b> {{money_format('%!i',$totalPackingTrimsIssueStockQty)}}</b></td>
                           <td style="text-align:right"><b> {{money_format('%!i',$totalPackingTrimsIssueStockValue)}}</b></td>
                           <td style="text-align:right"><b> {{money_format('%!i',$totalPackingTrimsAvaliableStockQty)}}</b></td>
                           <td style="text-align:right"><b> {{money_format('%!i',$totalPackingTrimsAvaliableStockValue)}}</b></td>
                        </tr> 
                     </tbody>
                  </table>
               </div>
               <div id="Sales">
                  <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Sales Details:</h4>
                  <table class="table table-bordered text-1 table-sm" style="height:10vh; "  id="Summary4">
                     <thead>
                        <tr  style="background-color:#eee; text-align:center;">
                           <th>SrNo</th>
                           <th>Invoice Date</th>
                           <th>Invoice No/Narration</th>
                           <th>ERP Invoice No</th>
                           <th>Dispatch  Qty</th>
                           <th>Invoice Rate</th>
                           <th>Taxable Amount</th>
                           <th>Freight  Amt</th>
                           <th>GST</th>
                           <th>Total Amount</th>
                           <th>Balance Qty</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php
                        $no=1;
                        // $Buyer_Purchase_Order_List = App\Models\BuyerPurchaseOrderMasterModel::join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId', 'left outer')
                        //->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')
                        //->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code', 'left outer')
                        //->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
                        //->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id', 'left outer')
                        //->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id', 'left outer')
                        //->where('buyer_purchse_order_master.tr_code','=', $Buyer_Purchase_Order_List[0]->tr_code)
                        //->get(['buyer_purchse_order_master.*','usermaster.username','ledger_master.Ac_name','fg_master.fg_name','merchant_master.merchant_name','job_status_master.job_status_name','main_style_master.mainstyle_name' ]);
                         // DB::enableQueryLog();
                        $SaleTransactionDetails = App\Models\SaleTransactionDetailModel::select(
                        'buyer_purchse_order_master.*',  'fg_master.fg_name','merchant_master.merchant_name', 'sale_transaction_master.freight_charges',
                        'sale_transaction_master.sale_code', 'sale_transaction_master.sale_date', 'sale_transaction_detail.Ac_code', 'sales_order_no', 'hsn_code',
                         DB::raw('sum(order_qty) as order_qty'), 'sale_transaction_detail.order_rate', 'disc_per', 'disc_amount', 'sale_cgst',
                        'camt', 'sale_sgst', 'samt', 'sale_igst', 'iamt', 'amount', 'total_amount','sale_transaction_master.narration as saleNarration' 
                        )
                        ->join('sale_transaction_master','sale_transaction_master.sale_code', '=', 'sale_transaction_detail.sale_code')
                        ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'sale_transaction_detail.sales_order_no')
                        ->join('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')
                        ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id', 'left outer')
                        ->where('sales_order_no','=', $Buyer_Purchase_Order_List[0]->tr_code)
                        ->groupBy('sale_transaction_master.sale_code')
                        ->get();
                        //dd(DB::getQueryLog());
                        $order_qty = $totalOrderQty;
                        @endphp
                        @foreach($SaleTransactionDetails as $sales)
                        <tr>
                           <td style="text-align:right">{{ $no }}</td>
                           <td style="text-align:right" nowrap>{{ date("d-m-Y",strtotime($sales->sale_date))  }}</td>
                           <td style="text-align:right">{{ $sales->saleNarration  }}</td>
                           <td style="text-align:right">{{ $sales->sale_code }}</td>
                           <td style="text-align:right">{{ money_format('%!i',round($sales->order_qty))}}</td>
                           <td style="text-align:right">{{money_format('%!i',round($sales->order_rate,2))}}</td>
                           <td style="text-align:right">{{ money_format('%!i',round($sales->amount))}}</td>
                           <td style="text-align:right" style="text-align:right">{{ money_format('%!i',round($sales->freight_charges))}}</td>
                           <td style="text-align:right">{{  money_format('%!i',round(round(($sales->camt + $sales->samt + $sales->iamt),2)))}}</td>
                           <td style="text-align:right">{{ money_format('%!i',round($sales->total_amount))}}</td>
                           <td style="text-align:right">{{ money_format('%!i',round($order_qty-$sales->order_qty))}}</td>
                        </tr>
                        @php
                        $order_qty = ($order_qty - $sales->order_qty);
                        $no=$no+1;
                        @endphp
                        @endforeach
                     </tbody>
                  </table>
               </div>
               @php
                    $percentageData=DB::select("SELECT * FROM kdpl_wise_set_percentage WHERE sales_order_no='".$Buyer_Purchase_Order_List[0]->tr_code."'");
                    $left_over_fabric_per = isset($percentageData[0]->leftover_fabric_value) ? $percentageData[0]->leftover_fabric_value : 0;
                    $left_over_trims_per = isset($percentageData[0]->leftover_trims_value) ? $percentageData[0]->leftover_trims_value : 0;
                    $left_pass_pcs_per = isset($percentageData[0]->left_pcs_value) ? $percentageData[0]->left_pcs_value : 0;
                    $left_reject_pcs_per = isset($percentageData[0]->rejection_pcs_value) ? $percentageData[0]->rejection_pcs_value : 0;
                    
                    $OCRData=DB::select("SELECT sum(transport_qty) as total_transport_qty, sum(testing_qty) as total_testing_qty FROM ocr_mater WHERE sales_order_no='".$Buyer_Purchase_Order_List[0]->tr_code."'");
                   
                    $total_transport_qty = isset($OCRData[0]->total_transport_qty) ? $OCRData[0]->total_transport_qty : 0;
                    $total_testing_qty = isset($OCRData[0]->total_testing_qty) ? $OCRData[0]->total_testing_qty : 0;
               @endphp
               <input type="hidden" value="{{$fg_stock}}" id="fg_stock">
               <input type="hidden" value="{{$left_over_fabric_per}}" id="left_over_fabric_per">
               <input type="hidden" value="{{$left_over_trims_per}}" id="left_over_trims_per">
               <input type="hidden" value="{{$left_pass_pcs_per}}" id="left_pass_pcs_per">
               <input type="hidden" value="{{$left_reject_pcs_per}}" id="left_reject_pcs_per">
               <input type="hidden" value="{{$production_value}}" id="cm_val">
               <input type="hidden" value="{{$dbk_value}}" id="garment_washing_cost">
               <input type="hidden" value="{{$printing_value}}" id="printing_cost">
               <input type="hidden" value="{{$embroidery_value}}" id="embroidery_cost">
               <input type="hidden" value="{{$ixd_value}}" id="ixd_value">
               <input type="hidden" value="{{$agent_commision_value}}" id="commision_cost">
               <input type="hidden" value="{{$total_transport_qty}}" id="transport_ocr_cost">
               <input type="hidden" value="{{$total_testing_qty}}" id="testing_ocr_cost">
                <div class="col-md-8" id="sale_Summary">
                  <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">OCR Summary Details:</h4>
                   <table class="table table-bordered text-1 table-sm">
                       <tr>
                        <th colspan="2" class="text-center">A</th>
                        <th colspan="2" class="text-center">B</th>
                      </tr>
                      <tr>
                        <th style="width: 40%;">Sales Value</th>
                        <td id="sales_value" class="text-right">-</td>
                        <th style="width: 40%;">Fabric Issued Value</th>
                        <td class="text-right" id="fabric_issued">{{ money_format('%!i',sprintf("%.2f",$totalAllocatedStockValue))}}</td>
                      </tr>
                      <tr>
                        <th style="width: 40%;">Left Over Fabric Value({{$left_over_fabric_per}})</th>
                        <td class="text-right">{{ money_format('%!i',sprintf("%.2f",$totalAvaliableStockValue*($left_over_fabric_per/100)))}}</td>
                        <th style="width: 40%;">Trims Issued Value</th>
                        <td class="text-right" id="trim_issued">{{ money_format('%!i',sprintf("%.2f", $totalTrimsAllocatedStockValue+$totalPackingTrimsAllocatedStockValue))}}</td>
                      </tr> 
                      <tr>
                        <th style="width: 40%;">Left Over Trims Value({{$left_over_trims_per}})</th>
                        <td class="text-right">{{  money_format('%!i',sprintf("%.2f",($totalTrimsAvaliableStockValue + $totalPackingTrimsAvaliableStockValue)*($left_over_trims_per/100))) }}</td>
                        <th style="width: 40%;">CM Value({{$production_value}})</th>
                        <td id="cm_value" class="text-right"></td>
                      </tr>
                      <tr>
                        <th style="width: 40%;">Left Over QC Pass FG Value({{$left_pass_pcs_per}})</th>
                        <td  class="text-right">{{ money_format('%!i',sprintf("%.2f",$leftOverPassPcsQty * ($left_pass_pcs_per/100)))}}</td>
                        <th style="width: 40%;">Washing Cost({{$dbk_value}})</th>
                        <td id="washing_cost_val" class="text-right"></td>
                      </tr>
                      <tr>
                        <th style="width: 40%;">Left Over QC Reject FG Value({{$left_reject_pcs_per}})</th>
                        <td class="text-right">{{ money_format('%!i',sprintf("%.2f",$leftOverRejectionPcsQty * ($left_reject_pcs_per/100)))}}</td>
                        <th style="width: 40%;">Embroidery Cost({{$embroidery_value}})</th>
                        <td id="embroidery_cost_val" class="text-right"></td>
                      </tr> 
                      <tr>
                        <th style="width: 40%;"></th>
                        <td class="text-right"></td>
                        <th style="width: 40%;">Printing Cost({{$printing_value}})</th>
                        <td id="printing_cost_val" class="text-right"></td>
                      </tr>
                      <tr>
                        <th style="width: 40%;"></th>
                        <td></td>
                        <th style="width: 40%;">Transport Cost</th>
                        <td class="text-right" id="transport_cost_val"></td>
                      </tr>
                      <tr>
                        <th style="width: 40%;"></th>
                        <td></td>
                        <th style="width: 40%;">Testing Cost</th>
                        <td class="text-right" id="testing_cost_val"></td>
                      </tr>
                      <tr>
                        <th style="width: 40%;"></th>
                        <td></td>
                        <th style="width: 40%;">IXD Cost({{$ixd_value}})</th>
                        <td id="IXD_cost_val" class="text-right"></td>
                      </tr>
                      <tr>
                        <th style="width: 40%;"></th>
                        <td></td>
                        <th style="width: 40%;">Commision Cost({{$agent_commision_value}})</th>
                        <td id="Commision_cost_val" class="text-right"></td>
                      </tr>
                      <tr style="background: antiquewhite;">
                        <th style="width: 40%;" class="text-right">Total(A)</th>
                        @php
                            $totalA = ($totalAvaliableStockValue*($left_over_fabric_per/100)) + (($totalTrimsAvaliableStockValue + $totalPackingTrimsAvaliableStockValue)*($left_over_trims_per/100)) + ($leftOverPassPcsQty * ($left_pass_pcs_per/100)) + ($leftOverRejectionPcsQty * ($left_reject_pcs_per/100));
                            $totalB = $totalIssueStockValue+$totalTrimsIssueStockValue + $totalPackingTrimsIssueStockValue+$production_value;
                        @endphp
                        <td class="text-right"><b  id="total_A">{{ money_format('%!i',sprintf("%.2f",$totalA))}}</b></td>
                        <th style="width: 40%;" class="text-right">Total(B)</th>
                        <td class="text-right"><b id="total_B">{{money_format('%!i',sprintf("%.2f", $totalB))}}</b></td>
                      </tr>
                      <tr>
                        <th style="width: 40%;" colspan="4" class="text-center">Profit Value (A - B):  <b id="total_A_B"></b></th> 
                      </tr> 
                    </table>
               </div>
               @else
               <center>
                  <h4 style="font-weight:bold;">Record Not Found</h4>
               </center>
               @endif
               <div class="row">
                  <!-- Fare Details -->
                  <div class="col-md-3">
                     <h4 class="text-4 mt-2">Prepared By:</h4>
                  </div>
                  <div class="col-md-3">
                     <h4 class="text-4 mt-2">Checked By:</h4>
                  </div>
                  <div class="col-md-3">
                     <h4 class="text-4 mt-2">Approved By:</h4>
                  </div>
                  <div class="col-md-3">
                     <h4 class="text-4 mt-2">Authorized By:</h4>
                  </div>
               </div>
               <br>
               <!-- Footer -->
         </main>
         </div>
      </div>
      </div> 
   </body>
   <script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
   <script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
   <script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
   <script> 
    
     function html_table_to_excel(type)
     {
        var data = document.getElementById('invoice');

        var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});

        XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });

        XLSX.writeFile(file, 'OCR REPORT.' + type);
      }

      const export_button = document.getElementById('export_button');
    
      export_button.addEventListener('click', () =>  {
            html_table_to_excel('xlsx');
      });
      function changeCurrency(ele)
      {
            var x=Math.round(ele);
            x=x.toString();
            var lastThree = x.substring(x.length-3);
            var otherNumbers = x.substring(0,x.length-3);
            if(otherNumbers != '')
                lastThree = ',' + lastThree;
            var res = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree;
                    
            return res;
      }
      
    //   $(document).ready(function()
    //   {
    //      	    var cosp = "";
    //      	    var arrVal = 0;
    //      	    var s1AllCount = $("#s1AllCount").val();
    //      	    for(var i=2; i<=3;i++)
    //      	    {
    //                  var result = [];
    //                  $('#Summary'+i+' tr').each(function()
    //                  {
                         
    //                   $('td', this).each(function(index, val)
    //                   {
    //                       if(!result[index]) result[index] = 0;
    //                       if(i == 3)
    //                       {
    //                           if(index == 8 || index == 12)
    //                           {
    //                               arrVal = "-";
    //                           }
    //                           else
    //                           {
    //                              arrVal = $(val).html().replace(/,/g , ''); 
    //                           }
    //                       }
    //                       else
    //                       {
    //                             arrVal = $(val).html().replace(/,/g , ''); 
    //                       }
                           
    //                       result[index] += parseFloat(arrVal);
                         
                            
    //                   });
    //                  });
                     
    //                  if(i==0)
    //                  {
    //                      result.shift();
    //                  }
    //                  else if(i==1 || i==2 || i==3)
    //                  {
    //                      result.shift(); result.shift(); result.shift(); result.shift();
    //                      cosp= '<td colspan="3"></td>';
    //                  }
    //                  else
    //                  {
    //                      cosp='';
    //                  }
                     
    //                  $('#Summary'+i).append('<tr>'+cosp+'<td class="text-right"><strong>Total : </strong></td></tr>');
    //                  $(result).each(function(ix)
    //                  {
    //                      var x=this;
    //                      x=x.toString();
    //                      var lastThree = x.substring(x.length-3);
    //                      var otherNumbers = x.substring(0,x.length-3);
    //                      if(otherNumbers != '')
    //                      var output = x.split('.')[1];
    //                      if(output > 0)
    //                      {  
    //                          if(x.length > 3)
    //                          {
    //                              $('#Summary'+i+' tr').last().append('<td class="text-right" ><strong>'+(otherNumbers/s1AllCount).toFixed(2)%+'</strong></td>');
    //                          }
    //                          else
    //                          {
    //                              $('#Summary'+i+' tr').last().append('<td class="text-right"><strong>'+(x/s1AllCount).toFixed(2)+'%</strong></td>');
    //                          }
    //                      }
    //                      else
    //                      {
    //                          lastThree = ',' + lastThree;
    //                          if(x.length > 3)
    //                          {
    //                              res = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",")+ lastThree;
    //                          }
    //                          else
    //                          {
    //                              res = x;
    //                          }
                           
    //                          if(res == 'NaN')
    //                          {
    //                             res = "-";  
    //                          }
    //                          else
    //                          {
    //                              res = res;
    //                          }
    //                          $('#Summary'+i+' tr').last().append('<td class="text-right total_'+i+'_'+ix+'"><strong>'+res+'</strong></td>');
    //                      }
                       
    //                  });
    //      	    }

                

    //          });
              
              
            //  $('#printInvoice').click(function(){
            //      Popup($('.invoice')[0].outerHTML);
            //      function Popup(data) 
            //      {
            //          window.print();
            //          return true;
            //      }
            //  });
      	
      	    $(document).ready(function(){
                    var result = [];
                    $('#Summary0 tr').each(function(){
                        $('td', this).each(function(index, val){
                            if (typeof result[index] === 'undefined') result[index] = 0;
                            
                            // Clean up and extract numeric value
                            var str = $(val).text().replace(/,/g, '').replace(/\u00a0/g, '').trim();
                            str = str.replace(/[^0-9.-]+/g, ''); // Remove any symbols
                    
                            if (!isNaN(str) && str !== '') {
                                result[index] += parseFloat(str);
                            }
                        });
                    });

                    result.shift(); 
                    $('#Summary0').append('<tr><td colspan="1" class="text-right"><strong>Total : </strong></td></tr>');
                    $(result).each(function(i){
                       // console.log(this);
                       $('#Summary0 tr').last().append('<td class="text-right total_summary_'+i+'"><strong>'+changeCurrency(this)+'</strong></td>')
                    });
                    var order_qty = $('#Summary0 tr').last().find('td:nth-child(2) strong').html().replace(/,/g , '');
                    var cut_qty = $('#Summary0 tr').last().find('td:nth-child(5) strong').html().replace(/,/g , '');
                    var ship_qty = $('#Summary0 tr').last().find('td:nth-child(14) strong').html().replace(/,/g , '');
                    var cut_to_ship = parseFloat(ship_qty/cut_qty)*100;
                    var order_to_ship = parseFloat(ship_qty/order_qty)*100;
                    var tx_amt = $("#Summary0 tbody tr:last").find('td:nth-child(4) strong').html(); 
                    $('#Summary0 tr').last().find('td:nth-child(17)').html('<b>'+(cut_to_ship).toFixed(2)+'</b>');
                    $('#Summary0 tr').last().find('td:nth-child(18)').html('<b>'+(order_to_ship).toFixed(2)+'</b>'); 
                    
                    
                     var result1 = [];
                    $('#Summary4 tr').each(function(){
                       $('td', this).each(function(index, val){
                         if(!result1[index]) result1[index] = 0;
                            var str = $(val).text().replace(/,/g , '');
                            var intRegex = /^\d+$/;
                            var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;
                            
                            if(intRegex.test(str) || floatRegex.test(str)) 
                            {
                                result1[index] += parseFloat(str);
                            }
                        
                       });
                    }); 
                    result1.shift();
                    result1.shift();
                    result1.shift();
                    result1.shift();
                    $('#Summary4').append('<tr><td colspan="4" class="text-right"><strong>Total : </strong></td></tr>');
                    var total_summary_0 = $('#Summary0 > .total_summary_0').find('strong').text(); 
                    $(result1).each(function(i){
                           
                       if(result1.length-1 === i)
                       {
                           var balance = (result[0] - result1[0]);
                            $('#Summary4 tr').last().append('<td class="text-right total_sales_'+i+'"><strong>'+changeCurrency(balance.toFixed(2))+'</strong></td>');
                       }
                       else
                       {
                            $('#Summary4 tr').last().append('<td class="text-right total_sales_'+i+'"><strong>'+changeCurrency(this.toFixed(2))+'</strong></td>');
                       }
                    });
                    
                    var tx_amt = $('.total_sales_2 strong').text().replace(/,/g , ''); 
                    setTimeout(function() {
                        var cm_value = $("#cm_value").text().replace(/,/g , '');
                        var washing_cost_val = $("#washing_cost_val").text().replace(/,/g , '');
                        var fabric_issue = $("#fabric_issued").text().replace(/,/g , '');
                        var trim_issue = $("#trim_issued").text().replace(/,/g , '');
                        var cm_wash = parseFloat(cm_value) + parseFloat(washing_cost_val) + parseFloat(fabric_issue) + parseFloat(trim_issue);
                        var total_A = $("#total_A").text().replace(/,/g , '');
                    
                        $("#total_A").text(changeCurrency(parseFloat(tx_amt) + parseFloat(total_A))); 
                        $("#total_B").text(changeCurrency(cm_wash)); 
                        $("#total_A_B").text(changeCurrency((parseFloat(tx_amt) + parseFloat(total_A)) - parseFloat(cm_wash)));
                    }, 800);
                    $("#sales_value").text(changeCurrency(tx_amt)); 
                    
                    
                    
                    
                var cm = $("#cm_val").val();
                var ixd_value = $("#ixd_value").val();
                var garment_washing_cost = $("#garment_washing_cost").val();
                var printing_cost = $("#printing_cost").val();
                var embroidery_cost = $("#embroidery_cost").val();
                var left_over_fabric_per = $('#left_over_fabric_per').val();
                var left_over_trims_per = $('#left_over_trims_per').val();
                var left_pass_pcs_per = $('#left_pass_pcs_per').val();
                var left_reject_pcs_per = $('#left_reject_pcs_per').val();
                var fob_rate = $('#fob_rate').text();
                var total_qty = $(".total_summary_4").text().replace(/,/g , '');
                var cut_qty = $(".total_summary_3").text().replace(/,/g , '');
                var ixd_dispatch = $(".total_summary_11").text().replace(/,/g , '');  
    
 
 
                var commision_cost = $("#commision_cost").val();
                var transport_ocr_cost = $("#transport_ocr_cost").val();
                var testing_ocr_cost = $("#testing_ocr_cost").val();
                var fg_stock = $("#fg_stock").val();
                
                var sewing = $(".total_2_3 strong").text().replace(/,/g , ''); 
                var packing = $(".total_3_3 strong").text().replace(/,/g , ''); 
                var total_prod_qty = $(".total_0_7 strong").text().replace(/,/g , ''); 
                
                var total_qty = $(".total_summary_7").text().replace(/,/g , '');  
         	   
                var taxable_amount = $(".total_4_6 strong").text().replace(/,/g , ''); 
                var left_over_stock_value = $(".total_1_5 strong").text().replace(/,/g , ''); 
                var left_over_stock_Sewing_Trims = $(".total_2_5 strong").text().replace(/,/g , ''); 
                var left_over_stock_Packing_Trims = $(".total_3_5 strong").text().replace(/,/g , ''); 
                var ixd_dispatch = $(".total_4_4 strong").text().replace(/,/g , ''); 
                var fg_qc_pass_left_over = $(".total_summary_13").text().replace(/,/g , ''); 
                var fg_qc_reject_left_over = $(".total_summary_6").text().replace(/,/g , ''); 
                
                $("#fabric_issue").text($(".total_1_3 strong").text());
                $("#trim_issue").text(changeCurrency(parseInt(sewing ? sewing : 0) + parseInt(packing ? packing : 0)));
                $("#left_over_fabric").text(changeCurrency((left_over_stock_value * (left_over_fabric_per/100))));
                $("#left_over_trim").text(changeCurrency((parseInt(left_over_stock_Sewing_Trims ? left_over_stock_Sewing_Trims : 0) + parseInt(left_over_stock_Packing_Trims ? left_over_stock_Packing_Trims : 0)) * (left_over_trims_per/100)));
                $('#fg_stock_val').text(changeCurrency((fg_qc_pass_left_over * fob_rate) * (left_pass_pcs_per/100)));
                $('#fg_stock_reject_val').text(changeCurrency((fg_qc_reject_left_over * fob_rate) * (left_reject_pcs_per/100)));
                
                $("#Commision_cost_val").text(changeCurrency(ixd_dispatch * commision_cost));
                $("#transport_cost_val").text(changeCurrency(transport_ocr_cost));
                $("#testing_cost_val").text(changeCurrency(testing_ocr_cost));
                
                $("#cm_value").text(changeCurrency(total_qty * cm));
                $("#IXD_cost_val").text(changeCurrency(ixd_dispatch * ixd_value));
                $("#washing_cost_val").text(changeCurrency(total_qty * garment_washing_cost)); 
                $("#embroidery_cost_val").text(changeCurrency(cut_qty * embroidery_cost));
                $("#printing_cost_val").text(changeCurrency(cut_qty * printing_cost));
                
                var sales_value = $("#sales_value").text().replace(/,/g , ''); 
                var left_over_fabric = $("#left_over_fabric").text().replace(/,/g , ''); 
                var left_over_trim = $("#left_over_trim").text().replace(/,/g , ''); 
                var fg_stock_val = $("#fg_stock_val").text().replace(/,/g , ''); 
                var fg_stock_reject_val = $("#fg_stock_reject_val").text().replace(/,/g , ''); 
                
                var fabric_issue = $("#fabric_issue").text().replace(/,/g , '');
                var trim_issue = $("#trim_issue").text().replace(/,/g , '');
                var cm_value = $("#cm_value").text().replace(/,/g , '');
                var washing_cost_val = $("#washing_cost_val").text().replace(/,/g , '');
                var embroidery_cost_val = $("#embroidery_cost_val").text().replace(/,/g , '');
                var printing_cost_val = $("#printing_cost_val").text().replace(/,/g , '');
                var transport_cost_val = $("#transport_cost_val").text().replace(/,/g , '');
                var testing_cost_val = $("#testing_cost_val").text().replace(/,/g , '');
                var IXD_cost_val = $("#IXD_cost_val").text().replace(/,/g , '');
                var Commision_cost_val = $("#Commision_cost_val").text().replace(/,/g , '');
                
                var total_A = parseFloat(sales_value) + parseFloat(left_over_fabric) + parseFloat(left_over_trim) + parseFloat(fg_stock_val) + parseFloat(fg_stock_reject_val);
                var total_B = parseFloat(fabric_issue) + parseFloat(trim_issue) + parseFloat(cm_value) + parseFloat(washing_cost_val) + parseFloat(embroidery_cost_val) + parseFloat(printing_cost_val) + parseFloat(transport_cost_val) + parseFloat(testing_cost_val) + parseFloat(IXD_cost_val) + parseFloat(Commision_cost_val);
         	  //  $('#total_A').text(changeCurrency(total_A));
         	    $('#total_B').text(changeCurrency(total_B)); 
         	    var profit = parseFloat(total_A) - parseFloat(total_B);
         	    if(profit > 0)
         	    {
         	        var sign = "+";
         	    }
         	    else
         	    {
         	         var sign = "-";
         	    }
         	    $('#total_profit_value').text(" "+sign+" "+changeCurrency(profit));
            });
      	
   </script>
</html>