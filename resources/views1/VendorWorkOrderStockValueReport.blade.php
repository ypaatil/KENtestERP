@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN');  @endphp
<!-- end page title -->
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Vendor Work Order Stock Value</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Vendor Work Order Stock Value </li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
             <button class="btn btn-warning" onclick="pushData();">Push Data</button> 
            <table id="datatable-buttons" class="table table-bordered dt-responsive w-100">
               <thead>
                  <tr style="text-align:center;">
                     <th>SrNo</th>
                     <th>Vendor Name</th>
                     <th>Sales Order No</th>
                     <th>Work/Process Order No</th>
                     <th>Work/Process Order Status</th>
                     <th>Buyer Name</th>
                     <th>Last FG Received Date</th>
                     <th>Item Code</th>
                     <th>Classification</th>
                     <th>Item Name</th>
                     <th>Item Description</th>
                     <th>UOM</th>
                     <th>Color Name</th>
                     <th>Garment Color</th>
                     <th>BOM Cons (Mtr/Nos)</th>
                     <th>BOM Qty</th>
                     <th>Qty Delivered</th>
                     <th>PO Rate</th>
                     <th>PO Qty</th>
                     <th>Value Delivered</th>
                     <th>Actual Cons.</th>
                     <th>Received Qty</th>
                     <th>Balance Qty</th>
                     <th>Balance Cons.</th>
                     <th>Value</th>
                  </tr>
               </thead>
               <tbody>
                  @php $no=1; @endphp
                  @foreach($VendorCutProcessOrderList as $row)   
                  @php
                  
                  $colorData = DB::select("SELECT sum(size_qty_total) as po_qty, vendor_purchase_order_detail.vpo_code,vendor_purchase_order_detail.item_code,
                      vendor_purchase_order_master.vendorId,vendor_purchase_order_detail.sales_order_no,
                      vendor_purchase_order_detail.color_id,color_master.color_name FROM vendor_purchase_order_detail 
                      INNER JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = vendor_purchase_order_detail.vpo_code
                      INNER JOIN color_master ON color_master.color_id = vendor_purchase_order_detail.color_id
                      WHERE vendor_purchase_order_detail.sales_order_no = '".$row->sales_order_no."' AND vendor_purchase_order_master.vendorId='".$row->vendorId."' 
                      AND vendor_purchase_order_master.vpo_code='".$row->vpo_code."' GROUP BY vendor_purchase_order_detail.color_id");
               
                  @endphp
                  @foreach($colorData AS $array) 
                  @php
                     
                     
                      $POData = DB::select("SELECT sum(size_qty_total) as po_qty FROM vendor_purchase_order_detail
                                WHERE vendor_purchase_order_detail.color_id IN('".$array->color_id."') AND vendor_purchase_order_detail.vpo_code='".$array->vpo_code."'  
                                AND vendor_purchase_order_detail.item_code ='".$array->item_code."' GROUP BY vendor_purchase_order_detail.color_id");
                        
                   
                      $FabOut=DB::select("SELECT fabric_outward_details.item_code,item_master.color_name,item_master.item_name, class_name, item_description,  
                          ifnull(sum(fabric_outward_details.meter),0) as IssueQty,ifnull(sum(fabric_outward_details.meter * fabric_outward_details.item_rate),0) as valueDelivered,
                          inward_details.item_rate as po_rate,
                          ifnull((select ROUND(AVG(final_cons),4) from vendor_purchase_order_fabric_details 
                          where vendor_purchase_order_fabric_details.vpo_code='".$array->vpo_code."'  
                          and vendor_purchase_order_fabric_details.item_code=fabric_outward_details.item_code),0) as Consumption,
                          ifnull((select sum(bom_qty) from vendor_purchase_order_fabric_details 
                          where vendor_purchase_order_fabric_details.vpo_code='".$array->vpo_code."'   
                          and vendor_purchase_order_fabric_details.item_code=fabric_outward_details.item_code),0) as BOMQTY 
                          FROM fabric_outward_details  
                          inner join item_master on item_master.item_code=fabric_outward_details.item_code
                          inner join classification_master on classification_master.class_id=item_master.class_id
                          INNER join inward_details on inward_details.track_code=fabric_outward_details.track_code
                          where vpo_code='".$array->vpo_code."' AND  fabric_outward_details.item_code ='".$array->item_code."'
                          group by  fabric_outward_details.item_code");
                       
                      $LastFGDate=DB::select("select max(pki_date) as LastFGDate 
                      from packing_inhouse_master where vendorId='".$row->vendorId."' and sales_order_no='".$row->sales_order_no."'");
                      
                      $FGQty=DB::select("select ifnull(sum(size_qty_total),0) as PKQty
                      from packing_inhouse_detail
                      where vpo_code in (select vendor_purchase_order_master.vpo_code from vendor_purchase_order_fabric_details
                      inner join vendor_purchase_order_master on vendor_purchase_order_master.vpo_code=vendor_purchase_order_fabric_details.vpo_code
                      where item_code='".$array->item_code."' and  vendor_purchase_order_master.sales_order_no='".$row->sales_order_no."' 
                      and vendor_purchase_order_master.vendorId='".$row->vendorId."' and color_id='".$array->color_id."') 
                      group by item_code");
                      
                      $FGCons=  isset($FabOut[0]->Consumption) ? $FabOut[0]->Consumption : 0 ;
                      $PKQty=  isset($FGQty[0]->PKQty) ? $FGQty[0]->PKQty : 0;
                      $FGRM = round($FGCons * $PKQty);
                      //DB::enableQueryLog();
                      
                      $colorArr3 =  rtrim($array->color_id,",");
                      $colorArr3 =  ltrim($colorArr3,",");
                    
                      $cutPanelGRNData =DB::select("select ifnull(sum(size_qty_total),0) as cutQty
                          from cut_panel_grn_detail where vpo_code='".$array->vpo_code."' 
                          and color_id IN('".$colorArr3."') group by vpo_code");
                       //   dd(DB::getQueryLog());
                       
                      if((isset($FabOut[0]->IssueQty) ? $FabOut[0]->IssueQty : 0) > 0 && (isset($POData[0]->po_qty) ? $POData[0]->po_qty : 0) > 0)
                      {
                            $actualConsPur = (isset($FabOut[0]->IssueQty) ? $FabOut[0]->IssueQty : 0) / (isset($POData[0]->po_qty) ? $POData[0]->po_qty : 0);
                      }
                      else
                      {
                            $actualConsPur = 0;
                      }
                      
                      $balanceQtyPur = (isset($POData[0]->po_qty) ? $POData[0]->po_qty : 0) - (isset($cutPanelGRNData[0]->cutQty) ? ($cutPanelGRNData[0]->cutQty) : 0);
                      if((isset($FabOut[0]->valueDelivered) ? $FabOut[0]->valueDelivered : 0) > 0 && (isset($FabOut[0]->IssueQty) ? $FabOut[0]->IssueQty : 0) > 0)
                      {
                         $po_rate_process_order = (isset($FabOut[0]->valueDelivered) ? $FabOut[0]->valueDelivered : 0)/(isset($FabOut[0]->IssueQty) ? $FabOut[0]->IssueQty : 0);
                      }
                      else
                      {
                        $po_rate_process_order = 0;
                      }
                  @endphp
                  <tr>
                     <td style="text-align:center; white-space:nowrap"> {{ $no  }} </td>
                     <td style="text-align:center; white-space:nowrap"> {{ $row->vendorName  }} </td>
                     <td style="text-align:center; white-space:nowrap"> {{ $row->sales_order_no  }} </td>
                     <td style="text-align:center; white-space:nowrap"> {{ $row->vpo_code  }} </td>
                     <td style="text-align:center; white-space:nowrap"> {{ $row->job_status_name  }} </td>
                     <td style="text-align:center; white-space:nowrap"> {{ $row->BuyerName  }} </td>
                     <td style="text-align:center; white-space:nowrap"> {{ $LastFGDate[0]->LastFGDate  }} </td>
                     <td style="text-align:center; white-space:nowrap"> {{ isset($FabOut[0]->item_code) ? $FabOut[0]->item_code : ""  }} </td>
                     <td style="text-align:center; white-space:nowrap"> {{ isset($FabOut[0]->class_name) ? $FabOut[0]->class_name : ""  }} </td>
                     <td style="text-align:center; white-space:nowrap"> {{ isset($FabOut[0]->item_name) ? $FabOut[0]->item_name : ""  }} </td>
                     <td style="text-align:center; white-space:nowrap"> {{ isset($FabOut[0]->item_description) ? $FabOut[0]->item_description  : ""  }} </td>
                     <td style="text-align:center; white-space:nowrap"> Meter </td>
                     <td style="text-align:center; white-space:nowrap"> {{ isset($FabOut[0]->color_name) ? $FabOut[0]->color_name : "" }} </td>
                     <td style="text-align:center; white-space:nowrap">{{$array->color_name}}</td>
                     <td style="text-align:center; white-space:nowrap"> {{ number_format(isset($FabOut[0]->Consumption) ? $FabOut[0]->Consumption : 0,2) }}   </td>
                     <td style="text-align:right;"> {{ money_format('%!i',round(isset($FabOut[0]->BOMQTY) ? $FabOut[0]->BOMQTY : 0,2)) }} </td>
                     <td style="text-align:right;"> {{ money_format('%!i',round(isset($FabOut[0]->IssueQty) ? $FabOut[0]->IssueQty : 0,2))  }} </td>
                     <td style="text-align:right;"> {{$po_rate_process_order }} </td>
                     <td style="text-align:right;"> {{money_format('%!.0n',isset($POData[0]->po_qty) ? $POData[0]->po_qty : 0)}}</td> 
                     <td style="text-align:right;">  {{ money_format('%!i',round((isset($FabOut[0]->valueDelivered) ? $FabOut[0]->valueDelivered : 0),2)) }}</td> 
                     <td style="text-align:right;"> {{money_format('%!i',round($actualConsPur,2))}}</td> 
                     <td style="text-align:right;"> {{money_format('%!.0n',isset($cutPanelGRNData[0]->cutQty) ? ($cutPanelGRNData[0]->cutQty) : 0)}} </td>
                     <td style="text-align:right;"> {{money_format('%!.0n',$balanceQtyPur )}} </td>
                     <td style="text-align:right;"> {{money_format('%!i',round($balanceQtyPur * $actualConsPur,2)) }} </td>
                     <td style="text-align:right;"> {{money_format('%!i',round(($balanceQtyPur * $actualConsPur) * (isset($FabOut[0]->po_rate) ? $FabOut[0]->po_rate : 0 ),2))}} </td>
                  </tr>
                   @php $no=$no+1; @endphp
                  @endforeach
                  @endforeach
                  <!--Sewing Trims          -->
                  @foreach($VendorWorkOrderList as $row)   
                  @php
                  
                   //   DB::enableQueryLog();
                  
                     $colorData = DB::select("SELECT sum(vendor_work_order_sewing_trims_details.bom_qty) as po_qty1 ,vendor_work_order_sewing_trims_details.*,bom_sewing_trims_details.color_id,
                                bom_sewing_trims_details.size_array
                                FROM `vendor_work_order_sewing_trims_details` 
                                INNER JOIN bom_sewing_trims_details ON bom_sewing_trims_details.sales_order_no = vendor_work_order_sewing_trims_details.sales_order_no 
                                AND bom_sewing_trims_details.item_code = vendor_work_order_sewing_trims_details.item_code 
                                WHERE vendor_work_order_sewing_trims_details.sales_order_no ='".$row->sales_order_no."' 
                                AND vendor_work_order_sewing_trims_details.vw_code = '".$row->vw_code."' GROUP BY vendor_work_order_sewing_trims_details.item_code");
                                
                    // dd(DB::getQueryLog());
                      
                      //$colorData = DB::select("SELECT sum(size_qty_total) as po_qty1, vendor_work_order_detail.vw_code,vendor_work_order_detail.item_code,
                      //vendor_work_order_detail.color_id FROM vendor_work_order_detail 
                    //  INNER JOIN vendor_work_order_master ON vendor_work_order_master.vw_code = vendor_work_order_detail.vw_code
                     // WHERE vendor_work_order_detail.sales_order_no = '".$row->sales_order_no."' AND vendor_work_order_master.vendorId = '".$row->vendorId."'
                     // AND vendor_work_order_master.vw_code='".$row->vw_code."'  GROUP BY vendor_work_order_detail.item_code");
                 
                  $arrCount = 0;
                 
                  @endphp
                  @foreach($colorData AS $array1) 
                  @php
                    $PD1 = 0;
                    
                    $colorArr =  rtrim($array1->color_id,",");
                    $colorArr =  ltrim($colorArr,",");
                   // DB::enableQueryLog();      
                    $vendorData = DB::select("SELECT size_qty_total FROM vendor_work_order_size_detail2 WHERE vw_code ='".$array1->vw_code."' 
                    AND color_id IN(".$colorArr.") GROUP BY color_id");  
                   // dd(DB::getQueryLog());
                    foreach($vendorData as $vendorD)
                    {
                        $PD1 += $vendorD->size_qty_total;
                    }
                    
                  //DB::enableQueryLog();
                    $FabOut2 = DB::select("SELECT trimsOutwardDetail.vw_code,
                      trimsOutwardDetail.item_code,item_master.color_name,item_master.item_name, class_name, item_description, 
                      ifnull(sum(trimsOutwardDetail.item_qty),0) as IssueQty,ifnull(sum(trimsOutwardDetail.item_qty * trimsOutwardDetail.item_rate),0) as valueDelivered,
                      trimsOutwardDetail.item_rate as po_rate,
                      ifnull((select ROUND(AVG(final_cons),4) from vendor_work_order_sewing_trims_details 
                      where vendor_work_order_sewing_trims_details.vw_code='".$array1->vw_code."'  
                      and vendor_work_order_sewing_trims_details.item_code=trimsOutwardDetail.item_code),0) as Consumption,
                      ifnull((select sum(bom_qty) from vendor_work_order_sewing_trims_details 
                      where vendor_work_order_sewing_trims_details.vw_code='".$array1->vw_code."'  
                      and vendor_work_order_sewing_trims_details.item_code=trimsOutwardDetail.item_code),0) as BOMQTY 
                      FROM trimsOutwardDetail  
                      inner join item_master on item_master.item_code=trimsOutwardDetail.item_code
                      inner join classification_master on classification_master.class_id=item_master.class_id
                      where trimsOutwardDetail.vw_code='".$array1->vw_code."' AND trimsOutwardDetail.item_code=".$array1->item_code);
                     // dd(DB::getQueryLog());
                      $LastFGDate=DB::select("select max(pki_date) as LastFGDate 
                      from packing_inhouse_master where vendorId='".$row->vendorId."' and sales_order_no='".$row->sales_order_no."'");
                      
                      if(isset($FabOut2[0]->vw_code) && isset($row->sales_order_no))
                      { 
                         //   DB::enableQueryLog();
                          $FGQty=DB::select("select ifnull(sum(size_qty_total),0) as sQty,stitching_inhouse_detail.color_id,color_master.color_name  from stitching_inhouse_detail
                                inner join color_master ON color_master.color_id = stitching_inhouse_detail.color_id
                                inner join stitching_inhouse_master ON stitching_inhouse_master.sti_code = stitching_inhouse_detail.sti_code
                                where stitching_inhouse_detail.vw_code='".$row->vw_code."' and stitching_inhouse_master.vendorId=".$row->vendorId." 
                          and  stitching_inhouse_detail.color_id in (".$array1->color_id.")");
                          
                          //dd(DB::getQueryLog());
                        
                          $FGCons= $FabOut2[0]->Consumption ? $FabOut2[0]->Consumption : 0 ;
                          $sQty= isset($FGQty[0]->sQty) ? $FGQty[0]->sQty : 0;
                          $FGRM = round($FGCons * $sQty);
                    
                           $colorId = isset($stichingInhouseData[0]->color_id) ? $stichingInhouseData[0]->color_id : 0;
                           $colorName = DB::select("select color_name from color_master where color_id =".$colorId);  
                  
                          if((isset($FabOut2[0]->IssueQty) ? $FabOut2[0]->IssueQty : 0) > 0 && (isset($PD1) ? $PD1 : 0) > 0)
                          {
                             $actualWork = (isset( $FabOut2[0]->IssueQty) ? $FabOut2[0]->IssueQty : 0)/(isset($PD1) ? $PD1 : 0);
                          }
                          else
                          {
                             $actualWork = 0;
                          }
                          
                          $balanceWork = (isset($PD1) ? $PD1 : 0) - (isset($FGQty[0]->sQty) ? $FGQty[0]->sQty : 0);
                          if((isset($FabOut2[0]->valueDelivered) ? $FabOut2[0]->valueDelivered : 0) > 0 && (isset($FabOut2[0]->IssueQty) ? $FabOut2[0]->IssueQty : 0) > 0)
                          {
                             $po_rate_process_order1 = (isset($FabOut2[0]->valueDelivered) ? $FabOut2[0]->valueDelivered : 0)/(isset($FabOut2[0]->IssueQty) ? $FabOut2[0]->IssueQty : 0);
                          }
                          else
                          {
                            $po_rate_process_order1 = 0;
                          }
                  @endphp
                  <tr>
                     <td style="text-align:center; white-space:nowrap"> {{ $no }} </td>
                     <td style="text-align:center; white-space:nowrap"> {{ $row->vendorName  }} </td>
                     <td style="text-align:center; white-space:nowrap"> {{ $row->sales_order_no  }} </td>
                     <td style="text-align:center; white-space:nowrap"> {{ $row->vw_code  }} </td>
                     <td style="text-align:center; white-space:nowrap"> {{ $row->job_status_name  }} </td>
                     <td style="text-align:center; white-space:nowrap"> {{ $row->BuyerName  }} </td>
                     <td style="text-align:center; white-space:nowrap"> {{ isset($LastFGDate[0]->LastFGDate) ? $LastFGDate[0]->LastFGDate : "-"  }} </td>
                     <td style="text-align:center; white-space:nowrap"> {{ isset($FabOut2[0]->item_code) ? $FabOut2[0]->item_code : ""  }} </td>
                     <td style="text-align:center; white-space:nowrap"> {{ isset($FabOut2[0]->class_name) ?  $FabOut2[0]->class_name : "" }} </td>
                     <td style="text-align:center; white-space:nowrap"> {{ isset($FabOut2[0]->item_name) ? $FabOut2[0]->item_name : ""  }} </td>
                     <td style="text-align:center; white-space:nowrap"> {{ isset($FabOut2[0]->item_description) ?  $FabOut2[0]->item_description : "" }} </td>
                     <td style="text-align:center; white-space:nowrap"> Meter </td>
                     <td style="text-align:center; white-space:nowrap"> {{ isset($FabOut2[0]->color_name) ? $FabOut2[0]->color_name : ""  }} </td>
                     <td style="text-align:center; white-space:nowrap">{{ isset($FGQty[0]->color_name) ? $FGQty[0]->color_name : "-"  }} </td>
                     <td style="text-align:center; white-space:nowrap"> {{number_format(isset($FabOut2[0]->Consumption) ? $FabOut2[0]->Consumption : 0,2)  }}   </td>
                     <td style="text-align:right;"> {{ money_format('%!i',(isset($FabOut2[0]->BOMQTY) ? $FabOut2[0]->BOMQTY : 0)) }} </td>
                     <td style="text-align:right;"> {{money_format('%!i',(isset($FabOut2[0]->IssueQty) ? $FabOut2[0]->IssueQty : 0 ))  }} </td>
                     <td style="text-align:right;"> {{$po_rate_process_order1}} </td>
                     <td style="text-align:right;"> {{money_format('%!.0n',isset($PD1) ? $PD1 : 0)}}</td>  
                     <td style="text-align:right;"> {{ money_format('%!i',round((isset($FabOut2[0]->valueDelivered) ? $FabOut2[0]->valueDelivered : 0),2)) }}</td>
                     <td style="text-align:right;"> {{money_format('%!i',round($actualWork,2))}}</td> 
                     <td style="text-align:right;"> {{money_format('%!.0n',isset($FGQty[0]->sQty) ? $FGQty[0]->sQty : 0)}} </td>
                     <td style="text-align:right;"> {{money_format('%!.0n',$balanceWork)}} </td>
                     <td style="text-align:right;"> {{money_format('%!i',round($balanceWork * $actualWork,2))}} </td>
                     <td style="text-align:right;"> {{money_format('%!i',round(($balanceWork * $actualWork) * (isset($FabOut2[0]->po_rate) ? $FabOut2[0]->po_rate : 0 ),2))}} </td>
                  </tr>
                  @php 
                        $no=$no+1;
                    }
                  @endphp
                  @endforeach  
                  @endforeach 
                  <!--Packing Trims--------->
                  @foreach($VendorPackProcessOrderList as $row)   
                  @php 
                  $colorArr = "";  
                  if(isset($row->sales_order_no) && isset($row->vpo_code))
                  { 
                   
                      //$colorData = DB::select("SELECT sum(size_qty) as po_qty2,color_master.color_name,vendor_purchase_order_packing_trims_details.vpo_code,
                       //   vendor_purchase_order_packing_trims_details.item_code,vendor_purchase_order_packing_trims_details.color_id FROM vendor_purchase_order_packing_trims_details 
                        //  INNER JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = vendor_purchase_order_packing_trims_details.vpo_code
                       //   INNER JOIN color_master ON color_master.color_id = vendor_purchase_order_packing_trims_details.color_id
                         // WHERE vendor_purchase_order_packing_trims_details.sales_order_no = '".$row->sales_order_no."' AND vendor_purchase_order_master.vendorId='".$row->vendorId."' 
                       //   AND vendor_purchase_order_master.vpo_code='".$row->vpo_code."' GROUP BY vendor_purchase_order_packing_trims_details.item_code");
                       
                  //DB::enableQueryLog();

                     $colorData1 = DB::select("SELECT sum(vendor_purchase_order_packing_trims_details.bom_qty) as po_qty2 ,vendor_purchase_order_packing_trims_details.*,
                                bom_packing_trims_details.color_id ,bom_packing_trims_details.size_array
                                FROM `vendor_purchase_order_packing_trims_details` 
                                INNER JOIN bom_packing_trims_details ON bom_packing_trims_details.sales_order_no = vendor_purchase_order_packing_trims_details.sales_order_no 
                                AND bom_packing_trims_details.item_code = vendor_purchase_order_packing_trims_details.item_code 
                                WHERE vendor_purchase_order_packing_trims_details.sales_order_no ='".$row->sales_order_no."' 
                                AND vendor_purchase_order_packing_trims_details.vpo_code = '".$row->vpo_code."' GROUP BY vendor_purchase_order_packing_trims_details.item_code");
                                
                  //dd(DB::getQueryLog());
                  @endphp
                  @foreach($colorData1 AS $array2) 
                  @php
                    $PD2 = 0;
                    if (substr($array2->color_id, -1, 1) == ',')
                    {
                       $colorArr1 = substr($array2->color_id,0,-1);
                    }
                    else
                    {
                         $colorArr1 = $array2->color_id;
                    }
                    
                    //$colorArr1 =  rtrim($array2->color_id,",");
                    //$colorArr1 =  ltrim($array2->color_id,",");
                    
                    //DB::enableQueryLog();      
                    $purchaseData = DB::select("SELECT size_qty_total  FROM vendor_purchase_order_size_detail2 WHERE vpo_code ='".$array2->vpo_code."' 
                    AND color_id IN(".$colorArr1.") GROUP BY color_id");  
                    //dd(DB::getQueryLog());
                    foreach($purchaseData as $purchaseD)
                    {
                        $PD2 += $purchaseD->size_qty_total;
                    }
                   
                    //$PD2 = DB::select("SELECT sum(size_qty) as size_qty FROM vendor_purchase_order_size_detail2 
                     //     WHERE size_id IN(".$array2->size_array.") AND vpo_code = '".$array2->vpo_code."' AND color_id IN(".$array2->color_id.")");   
                                
                    $FabOut1=DB::select("SELECT trimsOutwardDetail.item_code,item_master.color_name,item_master.item_name, class_name, item_description, 
                      ifnull(sum(trimsOutwardDetail.item_qty),0) as IssueQty,ifnull(sum(trimsOutwardDetail.item_qty * trimsOutwardDetail.item_rate),0) as valueDelivered,
                      trimsOutwardDetail.item_rate as po_rate,
                      ifnull((select ROUND(AVG(final_cons),4) from vendor_purchase_order_packing_trims_details 
                      where vendor_purchase_order_packing_trims_details.vpo_code='".$row->vpo_code."'  
                      and vendor_purchase_order_packing_trims_details.item_code=trimsOutwardDetail.item_code),0) as Consumption,
                      ifnull((select sum(bom_qty) from vendor_purchase_order_packing_trims_details 
                      where vendor_purchase_order_packing_trims_details.vpo_code='".$row->vpo_code."'  
                      and vendor_purchase_order_packing_trims_details.item_code=trimsOutwardDetail.item_code),0) as BOMQTY 
                      FROM trimsOutwardDetail  
                      inner join item_master on item_master.item_code=trimsOutwardDetail.item_code
                      inner join classification_master on classification_master.class_id=item_master.class_id
                      where trimsOutwardDetail.vpo_code='".$row->vpo_code."'  AND  trimsOutwardDetail.item_code ='".$array2->item_code."'");
                      
                      $LastFGDate=DB::select("select max(pki_date) as LastFGDate 
                      from packing_inhouse_master where vendorId='".$row->vendorId."' and sales_order_no='".$row->sales_order_no."'");
                      // DB::enableQueryLog();
                     
                      $FGQty3=DB::select("select ifnull(sum(size_qty_total),0) as PKQty,color_master.color_name from packing_inhouse_detail 
                              INNER JOIN packing_inhouse_master ON packing_inhouse_master.pki_code = packing_inhouse_detail.pki_code
                              INNER JOIN color_master ON color_master.color_id = packing_inhouse_detail.color_id
                              where packing_inhouse_detail.vpo_code='".$row->vpo_code."'  and packing_inhouse_detail.color_id IN (".$colorArr1.") 
                              AND packing_inhouse_master.vendorId=".$row->vendorId);
                       
                          
                       //dd(DB::getQueryLog());        
                      $FGCons=  isset($FabOut1[0]->Consumption) ? $FabOut1[0]->Consumption : 0 ;
                      $PKQty=  isset($FGQty3[0]->PKQty) ? $FGQty3[0]->PKQty : 0;
                      $FGRM = round($FGCons * $PKQty);
              
                   //DB::enableQueryLog();
                    $ColorListpacking =DB::select("select DISTINCT(color_master.color_id), color_name FROM  vendor_purchase_order_detail 
                    INNER JOIN color_master ON color_master.color_id = vendor_purchase_order_detail.color_id 
                    WHERE vendor_purchase_order_detail.sales_order_no = '".$row->sales_order_no."' 
                    AND vendor_purchase_order_detail.vpo_code = '".$row->vpo_code."' AND
                    vendor_purchase_order_detail.color_id IN (".$colorArr1.") AND delflag=0");
                  
                   // dd(DB::getQueryLog());
                    $colorspk='';
                    foreach($ColorListpacking as $colorpk)
                    {
                        $colorspk=$colorspk.$colorpk->color_name.', ';
                    }

                    if((isset($FabOut1[0]->IssueQty) ? $FabOut1[0]->IssueQty : 0) > 0 && (isset($PD2) ? $PD2 : 0) > 0)
                    {
                        $actualPur1 = (isset($FabOut1[0]->IssueQty) ? $FabOut1[0]->IssueQty : 0) / (isset($PD2) ? $PD2 : 0);
                    }
                    else
                    {
                        $actualPur1 = 0; 
                    }
                    
                    $balancePur1 = (isset($PD2) ? $PD2 : 0) - (isset($FGQty3[0]->PKQty) ? $FGQty3[0]->PKQty : 0);
                    if((isset($FabOut1[0]->valueDelivered) ? $FabOut1[0]->valueDelivered : 0) > 0 && (isset($FabOut1[0]->IssueQty) ? $FabOut1[0]->IssueQty : 0) > 0)
                    {
                        $po_rate_process_order2 = (isset($FabOut1[0]->valueDelivered) ? $FabOut1[0]->valueDelivered : 0)/(isset($FabOut1[0]->IssueQty) ? $FabOut1[0]->IssueQty : 0);
                    }
                    else
                    {
                        $po_rate_process_order2 = 0;
                    }
                  @endphp
                  <tr>
                     <td style="text-align:center; white-space:nowrap"> {{ $no  }} </td>
                     <td style="text-align:center; white-space:nowrap"> {{ $row->vendorName  }} </td>
                     <td style="text-align:center; white-space:nowrap"> {{ $row->sales_order_no  }} </td>
                     <td style="text-align:center; white-space:nowrap"> {{ $row->vpo_code  }} </td>
                     <td style="text-align:center; white-space:nowrap"> {{ $row->job_status_name  }} </td>
                     <td style="text-align:center; white-space:nowrap"> {{ $row->BuyerName  }} </td>
                     <td style="text-align:center; white-space:nowrap"> {{ isset($LastFGDate[0]->LastFGDate) ? $LastFGDate[0]->LastFGDate : "-"}} </td>
                     <td style="text-align:center; white-space:nowrap"> {{ isset($FabOut1[0]->item_code) ? $FabOut1[0]->item_code : ""  }} </td>
                     <td style="text-align:center; white-space:nowrap"> {{ isset($FabOut1[0]->class_name) ?  $FabOut1[0]->class_name : "" }} </td>
                     <td style="text-align:center; white-space:nowrap"> {{ isset($FabOut1[0]->item_name) ? $FabOut1[0]->item_name : ""  }} </td>
                     <td style="text-align:center; white-space:nowrap"> {{ isset($FabOut1[0]->item_description) ? $FabOut1[0]->item_description : "" }} </td>
                     <td style="text-align:center; white-space:nowrap"> Meter </td>
                     <td style="text-align:center; white-space:nowrap"> {{ isset($FabOut1[0]->color_name) ? $FabOut1[0]->color_name : "" }} </td>
                     <td style="text-align:center; white-space:nowrap"> {{ isset($colorspk) ? $colorspk : "-" }}</td>
                     <td style="text-align:center; white-space:nowrap"> {{ number_format(isset($FabOut1[0]->Consumption) ? $FabOut1[0]->Consumption : 0,2)  }}   </td>
                     <td style="text-align:right;"> {{money_format('%!i',(isset($FabOut1[0]->BOMQTY) ? $FabOut1[0]->BOMQTY : 0)) }} </td>
                     <td style="text-align:right;"> {{money_format('%!i',(isset($FabOut1[0]->IssueQty) ? $FabOut1[0]->IssueQty : 0))  }} </td>
                     <td style="text-align:right;"> {{$po_rate_process_order2}} </td>
                     <td style="text-align:right;"> {{money_format('%!.0n',isset($PD2) ? $PD2 : 0)}}</td> 
                     <td style="text-align:right;"> {{ money_format('%!i',round((isset($FabOut1[0]->valueDelivered) ? $FabOut1[0]->valueDelivered : 0),2)) }} </td>
                     <td style="text-align:right;"> {{money_format('%!i',$actualPur1)}}</td>
                     <td style="text-align:right;"> {{money_format('%!.0n',isset($FGQty3[0]->PKQty) ? $FGQty3[0]->PKQty : 0)}} </td>
                     <td style="text-align:right;"> {{$balancePur1}}</td>
                     <td style="text-align:right;">{{money_format('%!i',round(($balancePur1 * $actualPur1),2))}}</td>
                     <td style="text-align:right;"> {{money_format('%!i',round(($balancePur1 * $actualPur1) * (isset($FabOut1[0]->po_rate) ? $FabOut1[0]->po_rate : 0),2))}} </td>
                  </tr>
                  @php $no=$no+1; @endphp
                  @endforeach
                  @php } @endphp
                  @endforeach      
               </tbody>
            </table>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<!-- end row -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<script>

    function pushData()
    {
         $.ajax({
            type: "GET",
            url: "{{ route('pushDataInTable') }}",
            success: function(data){
                console.log('Data imported done...!');
            }
        });
    }
//     $('#datatable-buttons').datatable({
//     "bProcessing": true,
//     "sAutoWidth": false,
//     "bDestroy":true,
//     "sPaginationType": "bootstrap", // full_numbers
//     "iDisplayStart ": 10,
//     "iDisplayLength": 10,
//     "bPaginate": false, //hide pagination
//     "bFilter": false, //hide Search bar
//     "bInfo": false, // hide showing entries
// })
    
</script>
@endsection