<!DOCTYPE html>
<html lang="en">
   <head>
      @php setlocale(LC_MONETARY, 'en_IN'); @endphp
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
                  <div class="row" style="border: #000000 solid 1px;">
                     <div  class="col-md-4">
                        <b style=" text-align: left;" class="mt-1">Sales Order No:</b>  <span style="display: inline-block;text-align: right;">{{$Buyer_Purchase_Order_List[0]->tr_code}}</span> <br>
                        <b style=" text-align: left;" class="mt-1">Buyer Name:</b>  <span style="display: inline-block;text-align: right;">{{$Buyer_Purchase_Order_List[0]->Ac_name}}</span><br>
                        <b style=" text-align: left;" class="mt-1">Main Style:</b>  <span style="display: inline-block;text-align: right;">{{$Buyer_Purchase_Order_List[0]->mainstyle_name}}</span><br>
                     </div>
                     <div  class="col-md-3" >
                     </div>
                     <div  class="col-md-5">        
                        <b style=" text-align: left;" class="mt-1">Job Style:</b>  <span style="display: inline-block;text-align: right;">{{$Buyer_Purchase_Order_List[0]->fg_name}}</span><br>
                        <b style=" text-align: left;" class="mt-1">Style No:</b>  <span style="display: inline-block;text-align: right;">{{$Buyer_Purchase_Order_List[0]->style_no}}</span><br>
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
                     <th>Balance Qty</th>
                     <th>Pack Qty</th>
                     <th>Packing Balance Qty</th>
                     <th>Shipment Qty</th>
                     <th>Shipment Bal Qty</th>
                     <th>Left Over Pcs</th>
                     <th>Cut To Ship %</th>
                     <th>Order To Ship %</th>
                  </thead>
                  <tbody>
                     @php
                     //DB::enableQueryLog();
                     $Buyer_Purchase_Order_Detail_List = DB::table('buyer_purchase_order_detail')->
                     select('buyer_purchase_order_detail.*','color_master.color_name',
                     DB::raw('(select ifnull(sum(size_qty_total),0) from vendor_work_order_detail where
                     vendor_work_order_detail.sales_order_no=buyer_purchase_order_detail.tr_code and
                     vendor_work_order_detail.color_id=buyer_purchase_order_detail.color_id
                     ) as work_order_qty'),
                     DB::raw('(select ifnull(sum(size_qty_total),0) from cut_panel_grn_detail where
                     cut_panel_grn_detail.sales_order_no=buyer_purchase_order_detail.tr_code and
                     cut_panel_grn_detail.color_id=buyer_purchase_order_detail.color_id
                     ) as cut_order_qty'),
                     DB::raw('(select ifnull(sum(size_qty_total),0) from qcstitching_inhouse_detail where
                     qcstitching_inhouse_detail.sales_order_no=buyer_purchase_order_detail.tr_code and
                     qcstitching_inhouse_detail.color_id=buyer_purchase_order_detail.color_id
                     ) as pass_order_qty'),
                     DB::raw('(select ifnull(sum(size_qty),0) from qcstitching_inhouse_size_reject_detail2 where
                     qcstitching_inhouse_size_reject_detail2.sales_order_no= buyer_purchase_order_detail.tr_code and
                     qcstitching_inhouse_size_reject_detail2.color_id=buyer_purchase_order_detail.color_id
                     ) as reject_order_qty'),
                     DB::raw('(select ifnull(sum(size_qty_total),0) from packing_inhouse_detail where
                     packing_inhouse_detail.sales_order_no=buyer_purchase_order_detail.tr_code and
                     packing_inhouse_detail.color_id=buyer_purchase_order_detail.color_id
                     ) as packing_order_qty'),
                     DB::raw('(select ifnull(sum(size_qty_total),0) from carton_packing_inhouse_detail
                     inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_detail.cpki_code
                     where
                     carton_packing_inhouse_detail.sales_order_no=buyer_purchase_order_detail.tr_code and
                     carton_packing_inhouse_detail.color_id=buyer_purchase_order_detail.color_id  
                     and carton_packing_inhouse_master.endflag=1 ) as invoice_qty')
                     )
                     ->join('color_master','color_master.color_id', '=', 'buyer_purchase_order_detail.color_id')
                     ->where('buyer_purchase_order_detail.tr_code','=', $Buyer_Purchase_Order_List[0]->tr_code)
                     ->get();
                     
                    //dd(DB::getQueryLog());
                     //  DB::raw('(select ifnull(sum(size_qty_total),0) from finishing_inhouse_detail where
                     //finishing_inhouse_detail.sales_order_no=buyer_purchase_order_detail.tr_code and
                     // finishing_inhouse_detail.color_id=buyer_purchase_order_detail.color_id
                     // ) as finish_order_qty'),
                     
                 
                     $no=1;    $passQty=0;$rejectQty=0; @endphp
                     <input type="hidden" id="s1AllCount" value="{{count($Buyer_Purchase_Order_Detail_List)}}">
                     @foreach($Buyer_Purchase_Order_Detail_List as $List)
                     @php
                  // DB::enableQueryLog();
                        
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
          //dd(DB::getQueryLog());
                       $ProductionOrderData = DB::select("SELECT sum(size_qty_total) as prod_qty from stitching_inhouse_detail WHERE sales_order_no = '".$List->tr_code."' AND color_id = '".$List->color_id."'");
                      
                       //$SaleTransactionData = DB::select("SELECT order_qty from sale_transaction_detail WHERE sales_order_no = '".$List->tr_code."' AND  sale_code = '".$List->sale_code."' group by sale_transaction_detail.sale_code");
                    
                     @endphp
                     <tr>
                        <td>{{$List->color_name  }}</td>
                        <td style="text-align: right;">{{number_format($List->size_qty_total)}}</td>
                        <td style="text-align: right;">{{number_format($List->size_qty_total + round(($List->size_qty_total) * ($List->shipment_allowance/100)))  }}</td>
                        <td style="text-align: right;">{{number_format($List->work_order_qty)  }}</td>
                        <td style="text-align: right;">{{number_format($List->cut_order_qty)  }}</td>
                        <td style="text-align: right;">{{number_format($ProductionOrderData[0]->prod_qty)}}</td>
                        <td style="text-align: right;">{{number_format($List->pass_order_qty)  }}</td>
                        <td style="text-align: right;">{{number_format($reject_qty)  }}</td>
                        <td style="text-align: right;">{{number_format(($List->pass_order_qty + $reject_qty))}}</td>
                        <td style="text-align: right;">{{number_format($List->cut_order_qty-($List->pass_order_qty + $reject_qty))  }}</td>
                        <td style="text-align: right;">{{number_format($List->packing_order_qty)  }}</td>
                        <td style="text-align: right;">{{number_format($List->cut_order_qty - $List->packing_order_qty)  }}</td>
                        <td style="text-align: right;">{{number_format($List->invoice_qty)}}</td>
                        <td style="text-align: right;">{{number_format($List->packing_order_qty -$List->invoice_qty)}}</td>
                        <td style="text-align: right;">{{number_format($List->packing_order_qty -$List->invoice_qty)}}</td>
                        @if($List->invoice_qty > 0 && $List->cut_order_qty > 0)
                        
                        <td style="text-align: right;">{{round((($List->invoice_qty/$List->cut_order_qty)*100),2)  }}</td>
                        @else
                        <td style="text-align: right;">0</td>
                        @endif
                        @if($List->invoice_qty > 0 && $List->size_qty_total > 0)
                        <td style="text-align: right;">{{round((($List->invoice_qty/$List->size_qty_total)*100),2)  }}</td>
                        @else
                        <td style="text-align: right;">0</td>
                        @endif
                     </tr>
                     @php
                     $passQty=$passQty + $List->pass_order_qty;
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
                           <th>PO No.</th>
                           <th>Item Name</th>
                           <th>Classification</th>
                           <th>Description</th>
                           <th>BOM Qty</th>
                           <th>PO Qty</th>
                           <th>PO Rate</th>
                           <th>Allocated Stock</th>
                           <th>Associated Stock</th>
                           <th>GRN Value</th>
                           <th>Pass Qty</th>
                           <th>Reject Qty</th>
                           <th>Issue Qty</th>
                           <th>Issue Value</th>
                           <th>Left Over Stock</th>
                           <th>Left Over Stock Value</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php 
                        // DB::enableQueryLog();
                        $FabricList = DB::select("select bom_fabric_details.bom_code, bom_fabric_details.item_code, item_name ,class_name,bom_fabric_details.bom_qty,
                        (SELECT distinct item_rate FROM `purchaseorder_detail` WHERE
                        bom_fabric_details.sales_order_no='".$Buyer_Purchase_Order_List[0]->tr_code."' and 
                        purchaseorder_detail.item_code=bom_fabric_details.item_code Limit 0,1
                        ) as PORate,
                        (SELECT ifnull(sum(item_qty),0) FROM `purchaseorder_detail` WHERE
                        FIND_IN_SET(bom_fabric_details.bom_code,purchaseorder_detail.bom_code) and 
                        purchaseorder_detail.item_code=bom_fabric_details.item_code
                        ) as POQty,
                        (select sum(meter) from inward_details
                        where
                        inward_details.item_code=bom_fabric_details.item_code and
                        po_code in (SELECT pur_code FROM `purchaseorder_detail` WHERE
                        FIND_IN_SET(bom_fabric_details.bom_code,purchaseorder_detail.bom_code) and 
                        purchaseorder_detail.item_code=bom_fabric_details.item_code and purchaseorder_detail.sales_order_no='".$Buyer_Purchase_Order_List[0]->tr_code."')) as GRNQty,
                        ifnull((SELECT (LENGTH(purchaseorder_detail.bom_code) - LENGTH(REPLACE(purchaseorder_detail.bom_code, ',', '')) + 1) FROM `purchaseorder_detail` WHERE
                        FIND_IN_SET(bom_fabric_details.bom_code,purchaseorder_detail.bom_code) and 
                        purchaseorder_detail.item_code=bom_fabric_details.item_code limit 0,1),1) as order_count,
                        (select  item_rate from inward_details
                        where
                        inward_details.item_code=bom_fabric_details.item_code and
                        po_code in (SELECT pur_code FROM `purchaseorder_detail` WHERE
                        FIND_IN_SET(bom_fabric_details.bom_code,purchaseorder_detail.bom_code) and 
                        purchaseorder_detail.item_code=bom_fabric_details.item_code) Limit 0,1) as GRNRate,
                        bom_fabric_details.description from  bom_fabric_details 
                        left join item_master on item_master.item_code=bom_fabric_details.item_code
                        left join classification_master on classification_master.class_id=bom_fabric_details.class_id
                        left join unit_master on unit_master.unit_id=bom_fabric_details.unit_id
                        where bom_fabric_details.sales_order_no = '".$Buyer_Purchase_Order_List[0]->tr_code."'"); 
                       
                        $no=1; 
                        @endphp
                        @foreach($FabricList as $rowDetail)  
                        @php
                            $POCodeData = DB::select("SELECT pur_code from purchaseorder_detail 
                            WHERE sales_order_no = '".$List->tr_code."' AND item_code = '".$rowDetail->item_code."'");
                            
                            if(count($POCodeData) > 0)
                            {
                                $PO_code = $POCodeData[0]->pur_code;
                            }
                            else
                            {
                                $PO_code = "-";
                            }
                        @endphp
                        <tr>
                           <td>{{ $no }}</td>
                           <td>{{ $rowDetail->item_code }}</td>
                           <td nowrap>{{ $PO_code}}</td>
                           <td nowrap>{{ $rowDetail->item_name }}</td>
                           @php
                           $QCData=DB::select("select sum(meter) as PassMeter,  sum(reject_short_meter) as RejectMeter from fabric_checking_details
                           inner join fabric_checking_master on fabric_checking_master.chk_code=fabric_checking_details.chk_code
                           where
                           fabric_checking_details.item_code='".$rowDetail->item_code."' and
                           fabric_checking_master.in_code in (
                           select in_code from inward_master where po_code in 
                           (SELECT pur_code FROM `purchaseorder_detail` WHERE
                           FIND_IN_SET('".$rowDetail->bom_code."'  ,purchaseorder_detail.bom_code) and 
                           purchaseorder_detail.item_code='".$rowDetail->item_code."'))");
                           
                          // $IssueMeter=DB::select("select sum(fabric_outward_details.meter) as  issue_meter from fabric_outward_details
                          // inner join vendor_purchase_order_master on vendor_purchase_order_master.vpo_code=fabric_outward_details.vpo_code
                          // where
                          // vendor_purchase_order_master.sales_order_no='".$Buyer_Purchase_Order_List[0]->tr_code."' and
                          // track_code in
                          // (select distinct track_code from fabric_checking_details
                           //inner join fabric_checking_master on fabric_checking_master.chk_code=fabric_checking_details.chk_code
                           //where fabric_checking_details.item_code='".$rowDetail->item_code."' and
                           //fabric_checking_master.in_code in (
                          // select in_code from inward_master where po_code in 
                          // (SELECT pur_code FROM `purchaseorder_detail` WHERE
                          // FIND_IN_SET('".$rowDetail->bom_code."'  ,purchaseorder_detail.bom_code) and 
                           //purchaseorder_detail.item_code='".$rowDetail->item_code."' and purchaseorder_detail.sales_order_no='".$Buyer_Purchase_Order_List[0]->tr_code."')))")
                           
                            $IssueMeter=DB::select("select sum(fabric_outward_details.meter) as  issue_meter from fabric_outward_details 
                                            INNER JOIN vendor_purchase_order_master ON  vendor_purchase_order_master.vpo_code = fabric_outward_details.vpo_code
                                            WHERE fabric_outward_details.item_code=".$rowDetail->item_code." AND vendor_purchase_order_master.sales_order_no='".$Buyer_Purchase_Order_List[0]->tr_code."'");
                                 
                            $associationData=DB::select("SELECT item_master.item_name,sta.po_code,sta.po_date, sta.bom_code,sta.sales_order_no,sta.item_code, 
                                ifnull(sum(sta.qty),0) as allocated_qty, (SELECT ifnull(SUM(stock_association_for_fabric.qty),0)
                                FROM stock_association_for_fabric WHERE  stock_association_for_fabric.po_code= sta.po_code 
                                AND stock_association_for_fabric.bom_code = sta.bom_code AND stock_association_for_fabric.item_code =  sta.item_code AND stock_association_for_fabric.tr_type = 2) as assoc_qty
                                FROM stock_association_for_fabric as sta INNER JOIN item_master ON item_master.item_code = sta.item_code 
                                
                                WHERE sta.po_code='".$PO_code."' AND sta.item_code='".$rowDetail->item_code."' AND sta.tr_type = 1  GROUP BY sta.bom_code,sta.item_code");    
                          
                           @endphp
                           <td nowrap>{{ $rowDetail->class_name }}</td>
                           <td>{{ $rowDetail->description  }}</td>
                           <td style="text-align:right"> {{number_format($rowDetail->bom_qty)}}</td>
                           <td style="text-align:right"> {{number_format($rowDetail->POQty)}}</td>
                           <td style="text-align:right"> {{number_format($rowDetail->PORate,4)}}</td>
                           <td style="text-align:right"> {{number_format(isset($associationData[0]->allocated_qty) ? $associationData[0]->allocated_qty : 0)}}</td>
                           <td style="text-align:right"> {{number_format(isset($associationData[0]->assoc_qty) ? $associationData[0]->assoc_qty : 0)}}</td>
                           <td style="text-align:right"> {{number_format(($rowDetail->GRNQty/$rowDetail->order_count) * $rowDetail->GRNRate)}}</td>
                           <td style="text-align:right"> {{number_format($QCData[0]->PassMeter)}}</td>
                           <td style="text-align:right"> {{number_format($QCData[0]->RejectMeter)}}</td>
                           <td style="text-align:right"> {{number_format($IssueMeter[0]->issue_meter)}}</td>
                           <td style="text-align:right"> {{number_format($IssueMeter[0]->issue_meter * $rowDetail->GRNRate)}}</td>
                           <td style="text-align:right"> {{number_format(($rowDetail->GRNQty/$rowDetail->order_count) - $IssueMeter[0]->issue_meter)}}</td>
                           <td style="text-align:right"> {{number_format((($rowDetail->GRNQty/$rowDetail->order_count) - $IssueMeter[0]->issue_meter) * $rowDetail->GRNRate)}}</td>
                        </tr>
                        @php
                        $no=$no+1;
                        @endphp
                        @endforeach
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
                           <th>PO No.</th>
                           <th>Item Name</th>
                           <th>Classification</th>
                           <th>Description</th>
                           <th>BOM Qty</th>
                           <th>PO Qty</th>
                           <th>PO Rate</th>
                           <th>Allocated Stock</th>
                           <th>Allocated Value</th>
                           <th>Associated Stock</th>
                           <th>GRN Value</th>
                           <th>Pass  Qty</th>
                           <th>Issue  Qty</th>
                           <th>Issue  Value</th>
                           <th>Left Over Stock</th>
                           <th>Left Over Stock Value</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php 
                       // DB::enableQueryLog();
                        $SewingList = DB::select("select bom_sewing_trims_details.bom_code,bom_sewing_trims_details.sales_order_no, bom_sewing_trims_details.item_code,
                        item_name ,class_name,bom_sewing_trims_details.bom_qty,
                        (SELECT distinct item_rate FROM `purchaseorder_detail` WHERE
                        bom_sewing_trims_details.sales_order_no='".$Buyer_Purchase_Order_List[0]->tr_code."' and 
                        purchaseorder_detail.item_code=bom_sewing_trims_details.item_code Limit 0,1
                        ) as PORate,
                        (SELECT ifnull(sum(item_qty),0) FROM `purchaseorder_detail` WHERE
                        FIND_IN_SET(bom_sewing_trims_details.bom_code,purchaseorder_detail.bom_code) and 
                        purchaseorder_detail.item_code=bom_sewing_trims_details.item_code
                        ) as POQty,
                        (select sum(item_qty) from trimsInwardDetail
                        where
                        trimsInwardDetail.item_code=bom_sewing_trims_details.item_code and
                        po_code in (SELECT pur_code FROM `purchaseorder_detail` WHERE
                        FIND_IN_SET(bom_sewing_trims_details.bom_code,purchaseorder_detail.bom_code) and 
                        purchaseorder_detail.item_code=bom_sewing_trims_details.item_code)) as GRNQty,
                        (select  item_rate from trimsInwardDetail
                        where
                        trimsInwardDetail.item_code=bom_sewing_trims_details.item_code and
                        po_code in (SELECT pur_code FROM `purchaseorder_detail` WHERE
                        FIND_IN_SET(bom_sewing_trims_details.bom_code,purchaseorder_detail.bom_code) and 
                        purchaseorder_detail.item_code=bom_sewing_trims_details.item_code) Limit 0,1) as GRNRate,
                        bom_sewing_trims_details.description from  bom_sewing_trims_details 
                        left join item_master on item_master.item_code=bom_sewing_trims_details.item_code
                        left join classification_master on classification_master.class_id=bom_sewing_trims_details.class_id
                        left join unit_master on unit_master.unit_id=bom_sewing_trims_details.unit_id
                        where bom_sewing_trims_details.sales_order_no = '".$Buyer_Purchase_Order_List[0]->tr_code."'"); 
                       //dd(DB::getQueryLog()); 
                        $no=1; 
                        @endphp
                        @foreach($SewingList as $rowDetail)  
                        @php
                            $POCodeData = DB::select("SELECT pur_code from purchaseorder_detail 
                            WHERE sales_order_no = '".$List->tr_code."' AND item_code = '".$rowDetail->item_code."'");
                            
                            if(count($POCodeData) > 0)
                            {
                                $PO_code = $POCodeData[0]->pur_code;
                            }
                            else
                            {
                                $PO_code = "-";
                            }
                        @endphp
                        <tr>
                           <td>{{ $no }}</td>
                           <td>{{ $rowDetail->item_code }}</td>
                           <td nowrap>{{ $PO_code }}</td>
                           <td nowrap>{{ $rowDetail->item_name }}</td>
                           @php
                           
                       
                           //$IssueMeter=DB::select("select sum(trimsOutwardDetail.item_qty) as  issue_qty from trimsOutwardDetail
                          // inner join vendor_purchase_order_master on vendor_purchase_order_master.vpo_code=trimsOutwardDetail.vpo_code
                          // where
                          // vendor_purchase_order_master.sales_order_no='".$Buyer_Purchase_Order_List[0]->tr_code."'
                           //and po_code in
                          // (SELECT pur_code FROM `purchaseorder_detail` WHERE
                           //FIND_IN_SET('".$rowDetail->bom_code."'  ,purchaseorder_detail.bom_code) and 
                           //purchaseorder_detail.item_code='".$rowDetail->item_code."' and purchaseorder_detail.sales_order_no='".$Buyer_Purchase_Order_List[0]->tr_code."')");
                       
                                                    
                            $IssueMeter=DB::select("select sum(trimsOutwardDetail.item_qty) as  issue_qty from trimsOutwardDetail 
                                            INNER JOIN vendor_work_order_master ON  vendor_work_order_master.vw_code = trimsOutwardDetail.vw_code
                                            WHERE trimsOutwardDetail.item_code=".$rowDetail->item_code." AND vendor_work_order_master.sales_order_no='".$Buyer_Purchase_Order_List[0]->tr_code."'");
                           
                            $SewingAssociationData=DB::select("SELECT item_master.item_name,sta.po_code,sta.po_date, sta.bom_code,sta.sales_order_no,sta.item_code, ifnull(sum(sta.qty),0) as allocated_qty,
                                    (SELECT ifnull(SUM(stock_association.qty),0)
                                    FROM stock_association WHERE  stock_association.po_code= sta.po_code 
                                    AND stock_association.bom_code = sta.bom_code AND stock_association.item_code =  sta.item_code AND stock_association.tr_type = 2) as assoc_qty
                                    FROM stock_association as sta INNER JOIN item_master ON item_master.item_code = sta.item_code 
                                    WHERE sta.sales_order_no='".$rowDetail->sales_order_no."' AND sta.item_code='".$rowDetail->item_code."' AND sta.tr_type = 1  GROUP BY sta.bom_code,sta.item_code");  
                                
                           @endphp
                           <td nowrap>{{ $rowDetail->class_name }}</td>
                           <td>{{ $rowDetail->description  }}</td>
                           <td style="text-align:right"> {{number_format($rowDetail->bom_qty)}}</td>
                           <td style="text-align:right"> {{number_format($rowDetail->POQty)}}</td>
                           <td style="text-align:right"> {{number_format($rowDetail->PORate,4)}}</td>
                           <td style="text-align:right"> {{number_format((isset($SewingAssociationData[0]->allocated_qty) ? $SewingAssociationData[0]->allocated_qty : 0) - (isset($SewingAssociationData[0]->assoc_qty) ? $SewingAssociationData[0]->assoc_qty : 0))}}</td>
                           <td style="text-align:right"> {{number_format((isset($SewingAssociationData[0]->allocated_qty) ? $SewingAssociationData[0]->allocated_qty : 0) * $rowDetail->PORate)}}</td>
                           <td style="text-align:right"> {{number_format(isset($SewingAssociationData[0]->assoc_qty) ? $SewingAssociationData[0]->assoc_qty : 0)}}</td>
                           <td style="text-align:right"> {{number_format($rowDetail->GRNQty * $rowDetail->GRNRate)}}</td>
                           <td style="text-align:right"> {{number_format($rowDetail->GRNQty)}}</td>
                           <td style="text-align:right"> {{number_format($IssueMeter[0]->issue_qty)}}</td>
                           <td style="text-align:right"> {{number_format($IssueMeter[0]->issue_qty * $rowDetail->GRNRate)}}</td>
                           <td style="text-align:right"> {{number_format(((isset($SewingAssociationData[0]->allocated_qty) ? $SewingAssociationData[0]->allocated_qty : 0) - (isset($SewingAssociationData[0]->assoc_qty) ? $SewingAssociationData[0]->assoc_qty : 0)) - $IssueMeter[0]->issue_qty)}}</td>
                           <td style="text-align:right"> {{number_format((((isset($SewingAssociationData[0]->allocated_qty) ? $SewingAssociationData[0]->allocated_qty : 0) - (isset($SewingAssociationData[0]->assoc_qty) ? $SewingAssociationData[0]->assoc_qty : 0)) - $IssueMeter[0]->issue_qty) * $rowDetail->GRNRate)}}</td>
                        </tr>
                        @php
                        $no=$no+1;
                        @endphp
                        @endforeach
                     </tbody>
                  </table>
               </div>
               <div id="Packing">
                  <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Packing Trims Details:</h4>
                  <table class="table table-bordered text-1 table-sm" style="height:10vh; "  id="Summary3">
                     <thead>
                        <tr  style="background-color:#eee; text-align:center;">
                           <th>SrNo</th>
                           <th>Item Code</th>
                           <th>PO No.</th>
                           <th>Item Name</th>
                           <th>Classification</th>
                           <th>Description</th>
                           <th>BOM Qty</th>
                           <th>PO Qty</th>
                           <th>PO Rate</th>
                           <th>Allocated Stock</th>
                           <th>Associated Stock</th>
                           <th>GRN Value</th>
                           <th>Pass  Qty</th>
                           <th>Issue  Qty</th>
                           <th>Issue  Value</th>
                           <th>Left Over Stock</th>
                           <th>Left Over Stock Value</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php 
                        // DB::enableQueryLog();
                        $PackingList = DB::select("select bom_packing_trims_details.bom_code, bom_packing_trims_details.item_code,
                        item_name ,class_name,bom_packing_trims_details.bom_qty,
                        (SELECT distinct item_rate FROM `purchaseorder_detail` WHERE
                        bom_packing_trims_details.sales_order_no='".$Buyer_Purchase_Order_List[0]->tr_code."' and 
                        purchaseorder_detail.item_code=bom_packing_trims_details.item_code Limit 0,1
                        ) as PORate,
                        (SELECT ifnull(sum(item_qty),0) FROM `purchaseorder_detail` WHERE
                        FIND_IN_SET(bom_packing_trims_details.bom_code,purchaseorder_detail.bom_code) and 
                        purchaseorder_detail.item_code=bom_packing_trims_details.item_code
                        ) as POQty,
                        (select sum(item_qty) from trimsInwardDetail
                        where
                        trimsInwardDetail.item_code=bom_packing_trims_details.item_code and
                        po_code in (SELECT pur_code FROM `purchaseorder_detail` WHERE
                        FIND_IN_SET(bom_packing_trims_details.bom_code,purchaseorder_detail.bom_code) and 
                        purchaseorder_detail.item_code=bom_packing_trims_details.item_code)) as GRNQty,
                        (select  item_rate from trimsInwardDetail
                        where
                        trimsInwardDetail.item_code=bom_packing_trims_details.item_code and
                        po_code in (SELECT pur_code FROM `purchaseorder_detail` WHERE
                        FIND_IN_SET(bom_packing_trims_details.bom_code,purchaseorder_detail.bom_code) and 
                        purchaseorder_detail.item_code=bom_packing_trims_details.item_code) Limit 0,1) as GRNRate,
                        bom_packing_trims_details.description from  bom_packing_trims_details 
                        left join item_master on item_master.item_code=bom_packing_trims_details.item_code
                        left join classification_master on classification_master.class_id=bom_packing_trims_details.class_id
                        left join unit_master on unit_master.unit_id=bom_packing_trims_details.unit_id
                        where bom_packing_trims_details.sales_order_no = '".$Buyer_Purchase_Order_List[0]->tr_code."'"); 
                        // $query = DB::getQueryLog();
                        // $query = end($query);
                        //dd($query); 
                        $no=1; 
                        @endphp
                        @foreach($PackingList as $rowDetail) 
                        @php
                            $POCodeData = DB::select("SELECT pur_code from purchaseorder_detail 
                            WHERE sales_order_no = '".$List->tr_code."' AND item_code = '".$rowDetail->item_code."'");
                            
                            if(count($POCodeData) > 0)
                            {
                                $PO_code = $POCodeData[0]->pur_code;
                            }
                            else
                            {
                                $PO_code = "-";
                            }
                        @endphp
                        <tr>
                           <td>{{ $no }}</td>
                           <td>{{ $rowDetail->item_code }}</td>
                           <td nowrap>{{ $PO_code }}</td>
                           <td nowrap>{{ $rowDetail->item_name }}</td>
                           @php
                           
                          // $IssueMeter=DB::select("select sum(trimsOutwardDetail.item_qty) as  issue_qty from trimsOutwardDetail
                          // inner join vendor_purchase_order_master on vendor_purchase_order_master.vpo_code=trimsOutwardDetail.vpo_code
                          // where
                          // vendor_purchase_order_master.sales_order_no='".$Buyer_Purchase_Order_List[0]->tr_code."'
                          // and
                          // po_code in
                          // (SELECT pur_code FROM `purchaseorder_detail` WHERE
                          // FIND_IN_SET('".$rowDetail->bom_code."'  ,purchaseorder_detail.bom_code) and 
                         //  purchaseorder_detail.item_code='".$rowDetail->item_code."' and purchaseorder_detail.sales_order_no='".$Buyer_Purchase_Order_List[0]->tr_code."'      ) 
                         //  ")
                           
                            $IssueMeter=DB::select("select sum(trimsOutwardDetail.item_qty) as  issue_qty from trimsOutwardDetail 
                                            INNER JOIN vendor_purchase_order_master ON  vendor_purchase_order_master.vpo_code = trimsOutwardDetail.vpo_code
                                            WHERE trimsOutwardDetail.item_code=".$rowDetail->item_code." AND vendor_purchase_order_master.sales_order_no='".$Buyer_Purchase_Order_List[0]->tr_code."'");
                            
                            $PackinggAssociationData=DB::select("SELECT item_master.item_name,sta.po_code,sta.po_date, sta.bom_code,sta.sales_order_no,sta.item_code, ifnull(sum(sta.qty),0) as allocated_qty,
                                    (SELECT ifnull(SUM(stock_association.qty),0)
                                    FROM stock_association WHERE  stock_association.po_code= sta.po_code 
                                    AND stock_association.bom_code = sta.bom_code AND stock_association.item_code =  sta.item_code AND stock_association.tr_type = 2) as assoc_qty
                                    FROM stock_association as sta INNER JOIN item_master ON item_master.item_code = sta.item_code 
                                    WHERE sta.po_code='".$PO_code."' AND sta.item_code='".$rowDetail->item_code."' AND sta.tr_type = 1  AND sta.cat_id = 3 GROUP BY sta.bom_code,sta.item_code");  
                                                
                           @endphp
                           <td nowrap>{{ $rowDetail->class_name }}</td>
                           <td>{{ $rowDetail->description  }}</td>
                           <td style="text-align:right"> {{number_format($rowDetail->bom_qty)}}</td>
                           <td style="text-align:right"> {{number_format($rowDetail->POQty)}}</td>
                           <td style="text-align:right"> {{number_format($rowDetail->PORate,4)}}</td>
                           <td style="text-align:right"> {{number_format(isset($PackinggAssociationData[0]->allocated_qty) ? $PackinggAssociationData[0]->allocated_qty : 0)}}</td>
                           <td style="text-align:right"> {{number_format(isset($PackinggAssociationData[0]->assoc_qty) ? $PackinggAssociationData[0]->assoc_qty : 0)}}</td>
                           <td style="text-align:right"> {{number_format($rowDetail->GRNQty * $rowDetail->GRNRate)}}</td>
                           <td style="text-align:right"> {{number_format($rowDetail->GRNQty)}}</td>
                           <td style="text-align:right"> {{number_format($IssueMeter[0]->issue_qty)}}</td>
                           <td style="text-align:right"> {{number_format($IssueMeter[0]->issue_qty * $rowDetail->GRNRate)}}</td>
                           <td style="text-align:right"> {{number_format($rowDetail->GRNQty - $IssueMeter[0]->issue_qty)}}</td>
                           <td style="text-align:right"> {{number_format(($rowDetail->GRNQty - $IssueMeter[0]->issue_qty) * $rowDetail->GRNRate)}}</td>
                        </tr>
                        @php
                        $no=$no+1;
                        @endphp
                        @endforeach
                     </tbody>
                  </table>
               </div>
               <div id="Sales">
                  <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Sales Details:</h4>
                  <table class="table table-bordered text-1 table-sm" style="height:10vh; "  id="Summary4">
                     <thead>
                        <tr  style="background-color:#eee; text-align:center;">
                           <th>SrNo</th>
                           <th>Order Recd.Date</th>
                           <th>Buyer's PO No</th>
                           <th>Sales Order No</th>
                           <th>Buyer</th>
                           <th>Main Category</th>
                           <th>FOB Rate</th>
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
                           <th>Status</th>
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
                        //  DB::enableQueryLog();
                        $SaleTransactionDetails = App\Models\SaleTransactionDetailModel::select(
                        'buyer_purchse_order_master.*',  'fg_master.fg_name','merchant_master.merchant_name',
                        'job_status_master.job_status_name','main_style_master.mainstyle_name',
                        'lm1.Ac_name', 'sale_transaction_master.freight_charges',
                        'sale_transaction_master.sale_code', 'sale_transaction_master.sale_date', 'sale_transaction_detail.Ac_code', 'sales_order_no', 'hsn_code',
                         DB::raw('sum(order_qty) as order_qty'), 'sale_transaction_detail.order_rate', 'disc_per', 'disc_amount', 'sale_cgst',
                        'camt', 'sale_sgst', 'samt', 'sale_igst', 'iamt', 'amount', 'total_amount','sale_transaction_master.narration as saleNarration' 
                        )
                        ->leftJoin('sale_transaction_master','sale_transaction_master.sale_code', '=', 'sale_transaction_detail.sale_code')
                        ->leftJoin('ledger_master as lm1','lm1.Ac_code', '=', 'sale_transaction_detail.Ac_code')
                        ->leftJoin('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'sale_transaction_detail.sales_order_no')
                        ->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')
                        ->leftJoin('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
                        ->leftJoin('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id', 'left outer')
                        ->leftJoin('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id', 'left outer')
                        ->where('sales_order_no','=', $Buyer_Purchase_Order_List[0]->tr_code)
                        ->groupBy('sale_transaction_master.sale_code')
                        ->get();
                        // $query = DB::getQueryLog();
                        // $query = end($query);
                        // dd($query);
                        @endphp
                        @foreach($SaleTransactionDetails as $sales)
                        <tr>
                           <td style="text-align:right">{{ $no }}</td>
                           <td style="text-align:right">{{ $sales->order_received_date }}</td>
                           <td style="text-align:right">{{ $sales->po_code }}</td>
                           <td style="text-align:right">{{ $sales->tr_code }}</td>
                           <td style="text-align:right">{{ $sales->Ac_name }}</td>
                           <td style="text-align:right">{{ $sales->mainstyle_name }}</td>
                           <td style="text-align:right" nowrap>{{ number_format($sales->order_rate,2) }}</td>
                           <td style="text-align:right">{{ $sales->sale_date  }}</td>
                           <td style="text-align:right">{{ $sales->saleNarration  }}</td>
                           <td style="text-align:right">{{ $sales->sale_code }}</td>
                           <td style="text-align:right">{{ number_format($sales->order_qty) }}</td>
                           <td style="text-align:right">{{ number_format($sales->order_rate,2) }}</td>
                           <td style="text-align:right">{{ number_format($sales->amount) }}</td>
                           <td style="text-align:right" style="text-align:right">{{ number_format($sales->freight_charges) }}</td>
                           <td style="text-align:right">{{ number_format(round(($sales->camt + $sales->samt + $sales->iamt),2))  }}</td>
                           <td style="text-align:right">{{ number_format($sales->total_amount) }}</td>
                           <td style="text-align:right">{{ number_format($sales->total_qty - $sales->order_qty) }}</td>
                           <td style="text-align:right">{{$sales->job_status_name}}</td>
                        </tr>
                        @php
                        $no=$no+1;
                        @endphp
                        @endforeach
                     </tbody>
                  </table>
               </div>
               <input type="hidden" value="{{$fg_stock}}" id="fg_stock">
               <input type="hidden" value="{{$SalesOrderCostingMaster[0]->production_value}}" id="cm_val">
               <input type="hidden" value="{{$SalesOrderCostingMaster[0]->dbk_value}}" id="garment_washing_cost">
               <input type="hidden" value="{{$SalesOrderCostingMaster[0]->printing_value}}" id="printing_cost">
               <input type="hidden" value="{{$SalesOrderCostingMaster[0]->embroidery_value}}" id="embroidery_cost">
               <input type="hidden" value="{{$SalesOrderCostingMaster[0]->ixd_value}}" id="ixd_value">
               <input type="hidden" value="{{$SalesOrderCostingMaster[0]->agent_commision_value}}" id="commision_cost">
               <input type="hidden" value="{{$SalesOrderCostingMaster[0]->transport_ocr_cost}}" id="transport_ocr_cost">
               <input type="hidden" value="{{$SalesOrderCostingMaster[0]->testing_ocr_cost}}" id="testing_ocr_cost">
                <div class="col-md-6" id="sale_Summary">
                  <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Sales Summary Details:</h4>
                   <table class="table table-bordered text-1 table-sm">
                      <tr>
                        <th style="width: 224px;">Fabric Issued Value</th>
                        <td id="fabric_issue" class="text-right">-</td>
                        <th style="width: 224px;">Washing Cost</th>
                        <td id="washing_cost_val" class="text-right">-</td>
                      </tr>
                      <tr>
                        <th style="width: 224px;">Trims Issued Value</th>
                        <td id="trim_issue" class="text-right">-</td>
                        <th style="width: 224px;">Embroidery Cost</th>
                        <td id="embroidery_cost_val" class="text-right">-</td>
                      </tr>
                      <tr>
                        <th style="width: 224px;">CM Value</th>
                        <td id="cm_value" class="text-right"></td>
                        <th style="width: 224px;">Printing Cost</th>
                        <td id="printing_cost_val" class="text-right">-</td>
                      </tr>
                      <tr>
                        <th style="width: 224px;">Sales Value</th>
                        <td id="sales_value" class="text-right">-</td>
                        <th style="width: 224px;">Transport Cost</th>
                        <td class="text-right" id="transport_cost_val">-</td>
                      </tr>
                      <tr>
                        <th style="width: 224px;">Left Over Fabric Value</th>
                        <td id="left_over_fabric" class="text-right">-</td>
                        <th style="width: 224px;">Testing Cost</th>
                        <td class="text-right" id="testing_cost_val">-</td>
                      </tr>
                      <tr>
                        <th style="width: 224px;">Left Over Trims Value</th>
                        <td id="left_over_trim" class="text-right">-</td>
                        <th style="width: 224px;">IXD Cost</th>
                        <td id="IXD_cost_val" class="text-right">-</td>
                      </tr>
                      <tr>
                        <th style="width: 224px;">Left Over FG Value</th>
                        <td id="fg_stock_val" class="text-right">-</td>
                        <th style="width: 224px;">Commision Cost</th>
                        <td id="Commision_cost_val" class="text-right">-</td>
                      </tr>
                      <tr>
                        <th style="width: 224px;">Profit Value</th>
                        <td class="text-right">-</td>
                        <th style="width: 224px;">-</th>
                        <td class="text-right">-</td>
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
      <p class="text-center d-print-none"><a href="/SalesOrderCosting">&laquo; Back to List</a></p>
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
      
      $(document).ready(function()
      {
          /******************Summury**************************************/
         	    var cosp = "";
         	    var arrVal = 0;
         	    var s1AllCount = $("#s1AllCount").val();
         	    for(var i=0; i<=3;i++)
         	    {
                     var result = [];
                     $('#Summary'+i+' tr').each(function()
                     {
                         
                       $('td', this).each(function(index, val)
                       {
                           if(!result[index]) result[index] = 0;
                           if(i == 4)
                           {
                              if(index == 8 || index == 12)
                              {
                                  arrVal = "-";
                              }
                              else
                              {
                                 arrVal = $(val).html().replace(/,/g , ''); 
                              }
                           }
                           else
                           {
                                arrVal = $(val).html().replace(/,/g , ''); 
                           }
                           
                           result[index] += parseFloat(arrVal);
                         
                            
                       });
                     });
                     
                     if(i==0)
                     {
                         result.shift();
                     }
                     else if(i==1 || i==2 || i==3)
                     {
                         result.shift(); result.shift(); result.shift(); result.shift(); result.shift();result.shift();
                         cosp= '<td colspan="5"></td>';
                     }
                     else
                     {
                         cosp='';
                     }
                     
                     $('#Summary'+i).append('<tr>'+cosp+'<td class="text-right"><strong>Total : </strong></td></tr>');
                     $(result).each(function(ix)
                     {
                         var x=this;
                         x=x.toString();
                         var lastThree = x.substring(x.length-3);
                         var otherNumbers = x.substring(0,x.length-3);
                         if(otherNumbers != '')
                         var output = x.split('.')[1];
                         if(output > 0)
                         {  
                             if(x.length > 3)
                             {
                                 $('#Summary'+i+' tr').last().append('<td class="text-right" ><strong>'+(otherNumbers/s1AllCount).toFixed(2)%+'</strong></td>');
                             }
                             else
                             {
                                 $('#Summary'+i+' tr').last().append('<td class="text-right"><strong>'+(x/s1AllCount).toFixed(2)+'%</strong></td>');
                             }
                         }
                         else
                         {
                             lastThree = ',' + lastThree;
                             if(x.length > 3)
                             {
                                 res = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",")+ lastThree;
                             }
                             else
                             {
                                 res = x;
                             }
                           
                             if(res == 'NaN')
                             {
                                res = "-";  
                             }
                             else
                             {
                                 res = res;
                             }
                             $('#Summary'+i+' tr').last().append('<td class="text-right total_'+i+'_'+ix+'"><strong>'+res+'</strong></td>');
                           
                         }
                       
                     });
         	    }

                var cm = $("#cm_val").val();
                var garment_washing_cost = $("#garment_washing_cost").val();
                var printing_cost = $("#printing_cost").val();
                var embroidery_cost = $("#embroidery_cost").val();
                var ixd_value = $("#ixd_value").val();
                var commision_cost = $("#commision_cost").val();
                var transport_ocr_cost = $("#transport_ocr_cost").val();
                var testing_ocr_cost = $("#testing_ocr_cost").val();
                
                var sewing = $(".total_2_7 strong").text().replace(/,/g , ''); 
                var packing = $(".total_3_7 strong").text().replace(/,/g , ''); 
                var total_prod_qty = $(".total_0_7 strong").text().replace(/,/g , ''); 
                var total_qty = parseInt({{$passQty}}) + parseInt({{$rejectQty}}) ; 
                
                var taxable_amount = $(".total_4_6 strong").text().replace(/,/g , ''); 
                var left_over_stock_value = $(".total_1_9 strong").text().replace(/,/g , ''); 
                var left_over_stock_Sewing_Trims = $(".total_2_9 strong").text().replace(/,/g , ''); 
                var left_over_stock_Packing_Trims = $(".total_3_9 strong").text().replace(/,/g , ''); 
                var ixd_dispatch = $(".total_4_4 strong").text().replace(/,/g , ''); 
                var fg_stock = $("#fg_stock").val(); 
         	    
                $("#fabric_issue").text($(".total_1_8 strong").text());
                $("#trim_issue").text(changeCurrency(parseInt(sewing) + parseInt(packing)));
                $("#cm_value").text(changeCurrency(total_qty * cm));
                $("#left_over_fabric").text(changeCurrency(left_over_stock_value));
                $("#left_over_trim").text(changeCurrency(parseInt(left_over_stock_Sewing_Trims) + parseInt(left_over_stock_Packing_Trims)));
                $('#fg_stock_val').text(changeCurrency(fg_stock));
                
                $("#washing_cost_val").text(changeCurrency(total_prod_qty * garment_washing_cost));
                $("#embroidery_cost_val").text(changeCurrency(total_prod_qty * embroidery_cost));
                $("#printing_cost_val").text(changeCurrency(total_prod_qty * printing_cost));
                $("#printing_cost_val").text(changeCurrency(total_prod_qty * printing_cost));
                $("#IXD_cost_val").text(changeCurrency(ixd_dispatch * ixd_value));
                $("#Commision_cost_val").text(changeCurrency(ixd_dispatch * commision_cost));
                $("#transport_cost_val").text(changeCurrency(transport_ocr_cost));
                $("#testing_cost_val").text(changeCurrency(testing_ocr_cost));
                

             });
             
             $(document).ready(function(){
                    var result = [];
                    $('#Summary4 tr').each(function(){
                       $('td', this).each(function(index, val){
                         if(!result[index]) result[index] = 0;
                            var str = $(val).text().replace(/,/g , '');
                            var intRegex = /^\d+$/;
                            var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;
                            
                            if(intRegex.test(str) || floatRegex.test(str)) 
                            {
                                result[index] += parseFloat(str);
                            }
                        
                       });
                    });
                    result.shift();
                    result.shift();
                    result.shift();
                    result.shift();
                    result.shift();
                    result.shift();
                    result.shift();
                    result.shift();
                    result.shift();
                    result.shift();
                    $('#Summary4').append('<tr><td colspan="10" class="text-right"><strong>Total : </strong></td></tr>');
                    $(result).each(function(){
                       $('#Summary4 tr').last().append('<td class="text-center"><strong>'+this.toFixed(2)+'</strong></td>')
                    });
                    
                    var tx_amt = $("#Summary4 tbody tr:last").find('td:nth-child(4) strong').html();
                    $("#sales_value").text(changeCurrency(tx_amt));
              });
              
            //  $('#printInvoice').click(function(){
            //      Popup($('.invoice')[0].outerHTML);
            //      function Popup(data) 
            //      {
            //          window.print();
            //          return true;
            //      }
            //  });
      	
      	
   </script>
</html>