@extends('layouts.master') 
@section('content')   
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">OCR  Summary Report-1</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">OCR  Summary Report-1</li>
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
                     <th>Order No.</th>
                     <th>Order Status</th>
                     <th>Order Close Date</th>
                     <th>Buyer Name</th>
                     <th>Buyer Brand</th>
                     <th>Style</th>
                     <th>Main Category</th>
                     <th>SAM</th>
                     <th>FOB</th>
                     <th>Dispatch Qty</th>
                     <th>Order Received Date</th>
                     <th>First Dispatch Date</th>
                     <th>Last Dispatch Date</th>
                     <th>Washing Cost</th>
                     <th>Embroidery Cost</th>
                     <th>Printing Cost</th>
                     <th>Transport Cost</th>
                     <th>Testing Cost</th>
                     <th>IXD Cost</th>
                     <th>Commision Cost</th>
                     <th>Fabric Issued</th>
                     <th>Trims Issued</th>
                     <th>CM</th>
                     <th>Sales</th>
                     <th>Left Over Fabric</th>
                     <th>Left Over Trims</th>
                     <th>Left Over FG</th>
                     <th>Profit</th>
                  </thead>
                  <tbody>
                    @php
                        $fabric_Issued = 0;
                        $total_left_over_fabric = 0;
                        $total_sales = 0;
                        $total_cm = 0;
                        $total_trim = 0;
                        $total_over_trim = 0;
                        $total_over_trim1 = 0;
                        $total_Sewing_trim = 0;
                        $total_FG = 0;
                        $total_Profit = 0;
                        $total_order_qty = 0;
                    @endphp
                    @foreach($SalesOrderList as  $row)
                    @php 
                            $FabricList = DB::select("select bom_fabric_details.bom_code, bom_fabric_details.item_code, item_name ,class_name,bom_fabric_details.bom_qty,
                              (select sum(meter) from inward_details
                               where
                               inward_details.item_code=bom_fabric_details.item_code and
                               po_code in (SELECT pur_code FROM `purchaseorder_detail` WHERE
                               FIND_IN_SET(bom_fabric_details.bom_code,purchaseorder_detail.bom_code) and 
                               purchaseorder_detail.item_code=bom_fabric_details.item_code and purchaseorder_detail.sales_order_no='".$row->sales_order_no."')) as GRNQty,
                              
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
                              where bom_fabric_details.sales_order_no = '".$row->sales_order_no."'");
                              
                        foreach($FabricList as $rowDetail)  
                        {
                            $IssueMeter=DB::select("select sum(fabric_outward_details.meter) as  issue_meter from fabric_outward_details
                              inner join vendor_purchase_order_master on vendor_purchase_order_master.vpo_code=fabric_outward_details.vpo_code
                              where
                              vendor_purchase_order_master.sales_order_no='".$row->sales_order_no ."' and
                              track_code in
                              (select distinct track_code from fabric_checking_details
                              inner join fabric_checking_master on fabric_checking_master.chk_code=fabric_checking_details.chk_code
                              where fabric_checking_details.item_code='".$rowDetail->item_code."' and
                              fabric_checking_master.in_code in (
                              select in_code from inward_master where po_code in 
                              (SELECT pur_code FROM `purchaseorder_detail` WHERE
                              FIND_IN_SET('".$rowDetail->bom_code."'  ,purchaseorder_detail.bom_code) and 
                              purchaseorder_detail.item_code='".$rowDetail->item_code."' and purchaseorder_detail.sales_order_no='".$row->sales_order_no ."')))");
                              
                              foreach($IssueMeter as $issues)
                              {
                                $fabric_Issued = $fabric_Issued  + ($issues->issue_meter * $rowDetail->GRNRate);
                                $total_left_over_fabric = $total_left_over_fabric + ($rowDetail->GRNQty/$rowDetail->order_count) - $issues->issue_meter;
                              }
                        }
                         $SaleTransactionDetails = App\Models\SaleTransactionDetailModel::select('amount','order_qty')
                         ->leftJoin('sale_transaction_master','sale_transaction_master.sale_code', '=', 'sale_transaction_detail.sale_code')
                         ->where('sales_order_no','=', $row->sales_order_no)
                         ->groupBy('sale_transaction_master.sale_code')
                         ->get();
                         
                        foreach($SaleTransactionDetails as $SaleTransaction)
                        {
                            $total_sales = $total_sales  + $SaleTransaction->amount;
                            $total_order_qty = $total_order_qty  + $SaleTransaction->order_qty;
                        }
                        
                         $SalesOrderCostingMaster = App\Models\SalesOrderCostingMasterModel::join('usermaster', 'usermaster.userId', '=', 'sales_order_costing_master.userId')
                            ->leftJoin('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'sales_order_costing_master.sales_order_no')
                            ->where('sales_order_costing_master.delflag','=', '0')
                            ->where('sales_order_costing_master.sales_order_no','=', $row->sales_order_no)
                            ->get(['sales_order_costing_master.*','buyer_purchse_order_master.total_qty']);
                      
                        
                        foreach($SalesOrderCostingMaster as $CMCost)
                        {
                            $total_cm = $total_cm  + ($CMCost->total_qty * $CMCost->production_value);
                        }
                        
                        $PackingList = DB::select("select bom_packing_trims_details.bom_code, bom_packing_trims_details.item_code,
                          item_name ,class_name,bom_packing_trims_details.bom_qty,
                           (SELECT distinct item_rate FROM `purchaseorder_detail` WHERE
                          bom_packing_trims_details.sales_order_no='".$row->sales_order_no."' and 
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
                          where bom_packing_trims_details.sales_order_no = '".$row->sales_order_no."'");
                        
                            foreach($PackingList as $rowPackDetail)  
                            {
                                 $TrimIssueMeter=DB::select("select sum(trimsOutwardDetail.item_qty) as  issue_qty from trimsOutwardDetail
                                   inner join vendor_purchase_order_master on vendor_purchase_order_master.vpo_code=trimsOutwardDetail.vpo_code where
                                  vendor_purchase_order_master.sales_order_no='".$row->sales_order_no."'
                                  and  po_code in  (SELECT pur_code FROM `purchaseorder_detail` WHERE
                                  FIND_IN_SET('".$rowPackDetail->bom_code."'  ,purchaseorder_detail.bom_code) and 
                                  purchaseorder_detail.item_code='".$rowPackDetail->item_code."' and purchaseorder_detail.sales_order_no='".$row->sales_order_no."')");
                                 
                                 foreach($TrimIssueMeter as $TrimIssue)  
                                 {
                                        $total_trim = $total_trim + ($TrimIssue->issue_qty * $rowPackDetail->GRNRate);
                                        $total_over_trim1 = $total_over_trim1 + ($rowPackDetail->GRNQty - $TrimIssue->issue_qty);
                                 }
                            }
                        
                            $SewingList = DB::select("select bom_sewing_trims_details.bom_code, bom_sewing_trims_details.item_code,
                                item_name ,class_name,bom_sewing_trims_details.bom_qty,
                                 (SELECT distinct item_rate FROM `purchaseorder_detail` WHERE
                                bom_sewing_trims_details.sales_order_no='".$row->sales_order_no."' and 
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
                              where bom_sewing_trims_details.sales_order_no = '".$row->sales_order_no."'");

                             foreach($SewingList as $rowSewing)  
                              { 
                                   $IssueSewingMeter=DB::select("select sum(trimsOutwardDetail.item_qty) as  issue_qty from trimsOutwardDetail
                                        inner join vendor_purchase_order_master on vendor_purchase_order_master.vpo_code=trimsOutwardDetail.vpo_code
                                        where vendor_purchase_order_master.sales_order_no='".$row->sales_order_no."' and po_code in
                                        (SELECT pur_code FROM `purchaseorder_detail` WHERE FIND_IN_SET('".$rowSewing->bom_code."'  ,purchaseorder_detail.bom_code) and 
                                           purchaseorder_detail.item_code='".$rowSewing->item_code."' and purchaseorder_detail.sales_order_no='".$row->sales_order_no."')");
                                 
                                  foreach($IssueSewingMeter as $rowIssue)  
                                  {
                                          $total_Sewing_trim = $total_Sewing_trim + ($rowSewing->GRNQty - $rowIssue->issue_qty);
                                   }
                             }
                        $total_over_trim = $total_over_trim + $total_over_trim1 + $total_Sewing_trim;
                        
                        $FinishedGoodsStock = DB::select("SELECT  ifnull(sum(packing_inhouse_size_detail2.size_qty),0) as 'packing_grn_qty',
                                (SELECT ifnull(sum(size_qty),0) from carton_packing_inhouse_size_detail2 
                                inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_size_detail2.cpki_code
                                where carton_packing_inhouse_size_detail2.color_id=packing_inhouse_size_detail2.color_id and 
                                carton_packing_inhouse_size_detail2.sales_order_no=packing_inhouse_size_detail2.sales_order_no 
                                and carton_packing_inhouse_size_detail2.size_id=packing_inhouse_size_detail2.size_id
                                and carton_packing_inhouse_master.endflag=1
                                ) as 'carton_pack_qty',
                                 (SELECT ifnull(sum(size_qty),0) from transfer_packing_inhouse_size_detail2 
                                inner join transfer_packing_inhouse_master on transfer_packing_inhouse_master.tpki_code=transfer_packing_inhouse_size_detail2.tpki_code
                                where transfer_packing_inhouse_size_detail2.main_sales_order_no=packing_inhouse_size_detail2.sales_order_no 
                                and transfer_packing_inhouse_size_detail2.usedFlag=1
                                ) as 'transfer_qty',order_rate FROM `packing_inhouse_size_detail2`
                                LEFT JOIN buyer_purchse_order_master on  buyer_purchse_order_master.tr_code=packing_inhouse_size_detail2.sales_order_no
                                LEFT JOIN main_style_master on main_style_master.mainstyle_id=packing_inhouse_size_detail2.mainstyle_id WHERE packing_inhouse_size_detail2.sales_order_no = '".$row->sales_order_no."'
                                GROUP by packing_inhouse_size_detail2.sales_order_no, packing_inhouse_size_detail2.color_id, packing_inhouse_size_detail2.size_id");
                                
                               foreach($FinishedGoodsStock as $rowFG)  
                                {
                                    $total_FG = $total_FG + ($rowFG->packing_grn_qty - $rowFG->carton_pack_qty - $rowFG->transfer_qty) * ($rowFG->order_rate); 
                               }  
                               $total_Profit = $total_Profit + ($fabric_Issued + $total_trim + $total_cm + $total_left_over_fabric + $total_over_trim + $total_FG) - $total_sales;
                              
                               $transactionData =  DB::select("SELECT min(sale_date) as first_date, max(sale_date) as last_date FROM sale_transaction_detail WHERE sales_order_no='".$row->sales_order_no."'");
                               $first_Dispatch_Date = isset($transactionData[0]->first_date) ? $transactionData[0]->first_date : "-";
                               $last_Dispatch_Date = isset($transactionData[0]->last_date) ? $transactionData[0]->last_date : "-";
                    @endphp
                        <tr>
                            <td class="text-center">{{ $row->sales_order_no }}</td>
                            <td class="text-center">{{ $row->job_status_name }}</td>
                            <td class="text-center">{{ $row->order_close_date }}</td>
                            <td class="text-center">{{ $row->ac_name }}</td>
                            <td class="text-center">{{ isset($row->brand_name) ? $row->brand_name : "-"}}</td>
                            <td class="text-center">{{ $row->fg_name }}</td>
                            <td class="text-center">{{$row->mainstyle_name}}</td>
                            <td class="text-center">{{$row->sam}}</td>
                            <td class="text-center">{{$row->inr_rate}}</td>
                            <td class="text-center">{{$row->shipped_qty}}</td>
                            <td class="text-center">{{$row->order_received_date}}</td>
                            <td class="text-center">{{$first_Dispatch_Date}}</td>
                            <td class="text-center">{{$last_Dispatch_Date}}</td>
                            <td class="text-center">{{number_format($row->dbk_value)}}</td>
                            <td class="text-center">{{number_format($row->embroidery_value)}}</td>
                            <td class="text-center">{{number_format($row->printing_value)}}</td>
                            <td class="text-center">{{number_format($row->transport_ocr_cost)}}</td>
                            <td class="text-center">{{number_format($row->testing_ocr_cost)}}</td>
                            <td class="text-center">{{number_format($row->ixd_value * $total_order_qty)}}</td>
                            <td class="text-center">{{number_format($row->agent_commision_value * $total_order_qty)}}</td>
                            <td class="text-center">{{number_format(round($fabric_Issued))}}</td>
                            <td class="text-center">{{number_format($total_trim)}}</td>
                            <td class="text-center">{{number_format($total_cm)}}</td>
                            <td class="text-center">{{number_format($total_sales)}}</td>
                            <td class="text-center">{{number_format(round($total_left_over_fabric))}}</td>
                            <td class="text-center">{{number_format(round($total_over_trim))}}</td>
                            <td class="text-center">{{number_format(round($total_FG))}}</td>
                            <td class="text-center">{{number_format(round($total_Profit))}}</td>
                        </tr>
                    @php 
                        $fabric_Issued = 0;
                        $total_left_over_fabric = 0;
                        $total_sales = 0;
                        $total_cm = 0;
                        $total_trim = 0;
                        $total_over_trim = 0;
                        $total_over_trim1 = 0;
                        $total_Sewing_trim = 0;
                        $total_Profit = 0;
                        $total_FG = 0;
                        $total_order_qty = 0;
                    @endphp
                    @endforeach
                  </tbody>
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
@endsection