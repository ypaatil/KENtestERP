@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp  
<!-- end page title -->
<style>
    #lblSync
    {
        background: #a8e94269;
        padding: 10px;
        font-weight: 900;
        background-position: left top;
        padding-top:95px;
        margin-bottom:60px;
        -webkit-animation-duration: 10s;animation-duration: 10s;
        -webkit-animation-fill-mode: both;animation-fill-mode: both;
    }
    .hide
    {
        display:none;
    }
    .text-right
    {
        text-align:end;
    }
    th
    {  
        font-weight: 800;font-size: 18px;
    }
    td
    {
        font-weight: 500;font-size: 16px;
    }
    
    .sticky_row
    {
          position: sticky;
    }
    table.scroll {
        width: 100%;
        border-spacing: 0;
        border: 2px solid black;
    }
 
    
    /*table.scroll thead tr {*/
        /* fallback */
    /*    width: 97%;*/
        /* minus scroll bar width */
    /*    width: -webkit-calc(100% - 16px);*/
    /*    width:    -moz-calc(100% - 16px);*/
    /*    width:         calc(100% - 16px);*/
    /*}*/
    
    /*table.scroll tr:after {*/
    /*    content: ' ';*/
    /*    display: block;*/
    /*    visibility: hidden;*/
    /*    clear: both;*/
    /*}*/
    
  /*.table-responsive {*/
  /*      min-height: .01%;*/
  /*      overflow-x: auto;*/
  /*  }*/
    
  /*  table.table-condensed.table-striped {*/
  /*      border-collapse: collapse;*/
  /*      width: 1200px;*/
  /*      overflow-x: scroll;*/
  /*      display: block;*/
  /*  }*/
  /*  .table-condensed.table-striped thead, .table-condensed.table-striped tbody {*/
  /*      display: block;*/
  /*  }*/
  /*  .table-condensed.table-striped tbody {*/
  /*      overflow-y: scroll;*/
  /*      overflow-x: hidden;*/
  /*      height: 400px;*/
  /*  }*/
  /*  .table>thead>tr>th {*/
  /*      vertical-align: bottom;*/
  /*      border-bottom: 2px solid transparent;*/
  /*  }*/
  /*  .table-condensed.table-striped td, .table-condensed.table-striped th {*/
  /*      min-width: 150px;*/
  /*      height: 25px;*/
  /*      overflow:hidden;*/
  /*      text-overflow: ellipsis;*/
  /*      max-width: 150px;*/
  /*  } */
  /*  .tablehead {*/
  /*      background-color: #5e5e60;*/
  /*      color: #fff;*/ 
  /*  }*/
  /*  .table-condensed>thead.tablehead>tr>th {*/
  /*      padding: 20px 10px 20px 20px;*/
  /*      text-transform: uppercase;*/
  /*      font-weight: 400;*/
  /*      font-size: 14px;*/
  /*  }*/
  /*  .table-condensed>tbody.tablebody>tr>td {*/
  /*      padding: 15px 10px 15px 20px;*/
  /*      text-transform: capitalize;*/
  /*      font-weight: 400;*/
  /*      font-size: 14px;*/
  /*      color: #4d4d4f;*/
  /*  }*/
  /*  .table-striped>tbody>tr:nth-of-type(even) {*/
  /*      background-color: #e4e4e5;*/
  /*      min-width: 100%;*/
  /*      display: inline-block;*/
  /*      border-bottom: 2px solid #fff;*/
  /*  }*/
  /*  .table-striped>tbody>tr:nth-of-type(odd) {*/
  /*      background-color: #f6f6f6;*/
  /*      min-width: 100%;*/
  /*      display: inline-block;*/
  /*      border-bottom: 2px solid #fff;*/
  /*  }*/

    .wrapper {
      position: relative;
      overflow: auto;
      border: 1px solid black;
      white-space: nowrap;
    }
    
    .sticky-col {
      position: -webkit-sticky;
      position: sticky;
      background-color: #4d4a45!important;
      color:#fff;
    }
    
    .first-col {
      left: 0px;
      z-index: 999;
    }
    
    .second-col {
      left: 70px;
      z-index: 999;
    }
       
    .third-col {
      left: 252px;
      z-index: 999;
    }
    
    tbody {
        overflow-y: scroll;
        overflow-x: hidden;
        height: auto;
    }
</style>
  <div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body"> 
                   <form action="/QuantitativeInventoryReport" method="GET">
                        <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="fin_year_id" class="form-label">Financial Year</label>
                                    <select name="fin_year_id" id="fin_year_id" class="form-control">
                                        @foreach($Financial_Year1 as $years)
                                            <option value="{{$years->fin_year_id}}" {{ $years->fin_year_id == $fin_year_id ? 'selected="selected"' : '' }} >{{$years->fin_year_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 mt-4">
                                   <button type="submit" class="btn btn-primary w-md">Search</button>
                                </div>
                        </div>
                    </form>
                <div class="card-title tablewrapper" style="text-align: center;background: #ff8c0040;"><h1><b>QUANTITATIVE INVENTORY</b><h1></div>
                <div class="table-responsive">
                    <table id="tbl" class="table-condensed table-striped nowrap w-100">
                      <thead class="tablehead"> 
                      <tr style="text-align:center; white-space:nowrap">
						    <th style="background: #0006;color: #fff;border-top: 3px solid black;" class="sticky-col first-col"></th>
						    <th style="background: #0006;color: #fff;border-top: 3px solid black;" class="sticky-col second-col">MONTHS</th>
						    <th style="background: #0006;color: #fff;border-top: 3px solid black;" class="sticky-col third-col">Units</th> 
						     @php
						        $colorCtr = 0;
						        
                                foreach($period as $key1=>$dates)
                                {  
                                  $yrdata= strtotime($dates."-01");
                                  $monthName = date('F', $yrdata);  
                              
                            @endphp
						    <th colspan="2" style="background:{{$colorArr[0]}};border-top: 3px solid black;">{{$monthName}}(₹ in lakhs)</th>
						    @php  
                               }   
                            @endphp
                        </tr>
                        <tr style="text-align:center; white-space:nowrap"> 
						    <th style="background: #0006;color: #fff;border-bottom: 3px solid black;" class="sticky-col first-col">ITEMS</th>
						    <th style="background: #0006;color: #fff;border-bottom: 3px solid black;" class="sticky-col second-col">Headers</th>
						    <th style="background: #0006;color: #fff;border-bottom: 3px solid black;" class="sticky-col third-col"></th>
						    @php
						      $colorCtr1 = 0;
                                foreach($period as $key=>$dates)
                                {  
                            @endphp
						    <th style="background:{{$colorArr[0]}};border-bottom: 3px solid black;" class="sticky_row">Quantity</th> 
						    <th style="background:{{$colorArr[0]}};border-bottom: 3px solid black;" class="sticky_row">Value</th>
						    @php 
						      $colorCtr1++;
                               }   
                            @endphp
                        </tr>
                        </thead>
                       <tbody class="tablebody"> 
                            <tr>
                                <td style="background: antiquewhite;" class="sticky-col first-col"></td>
                                <td style="background: antiquewhite;" nowrap class="sticky-col second-col">Opening Stock</td>
                                <td style="background: antiquewhite;" nowrap  class="sticky-col third-col">meters</td>
                                @php
                                $OpeningFabricQtyArr = [];
                                $InwardFabricQtyArr = [];
                                $OutwardFabricQtyArr = [];
                                $ClosingFabricQtyArr = [];
                                
                                
                                $OpeningFabricValueArr = [];
                                $InwardFabricValueArr = [];
                                $OutwardFabricValueArr = [];
                                $ClosingFabricValueArr = [];
                                
                                
                                $OpeningTrimsQtyArr = [];
                                $InwardTrimsQtyArr = [];
                                $OutwardTrimsQtyArr = [];
                                $ClosingTrimsQtyArr = [];
                                
                                
                                $OpeningTrimsValueArr = [];
                                $InwardTrimsValueArr = [];
                                $OutwardTrimsValueArr = [];
                                $ClosingTrimsValueArr = [];
                                
                                $OpeningWIPQtyArr = [];
                                $InwardWIPQtyArr = [];
                                $OutwardWIPQtyArr = [];
                                $ClosingWIPQtyArr = [];
                                
                                
                                $OpeningWIPValueArr = [];
                                $InwardWIPValueArr = [];
                                $OutwardWIPValueArr = [];
                                $ClosingWIPValueArr = [];
                                
                                $CuttingWIPPCSArr = [];
                                $CuttingWIPMinArr = [];
                                $SewingWIPPCSArr = [];
                                $SewingWIPMinArr = [];
                                $PackingWIPPCSArr = [];
                                $PackingWIPMinArr = [];
                                $WIPPCSArr = [];
                                $WIPMinArr = [];
                                
                                $CuttingWIPValueArr = [];
                                $SewingWIPValueArr = [];
                                $PackingWIPValueArr = [];
                                $WIPValueArr = [];

                                $closingStock = 0;
                                $openingStock1 = 0;
                                $cntr1 = 0;
                                foreach($period as $dates)
                                {  
                                    $firstDate = $dates."-01";
                                    $lastDate = date("Y-m-t", strtotime( $dates."-01"));
                                  
                                    $total_value = 0;
                                    $total_stock = 0; 
                                    
                                    $FabricInwardDetails1 =DB::select("SELECT dump_fabric_stock_data.*, (SELECT sum(grn_qty) FROM dump_fabric_stock_data AS df WHERE df.track_name= dump_fabric_stock_data.track_name AND df.in_date = dump_fabric_stock_data.in_date AND df.in_date < '".$firstDate."' ) as gq,
                                            (SELECT sum(outward_qty) FROM dump_fabric_stock_data AS df1 WHERE df1.track_name= dump_fabric_stock_data.track_name AND df1.fout_date = dump_fabric_stock_data.fout_date  AND df1.fout_date < '".$firstDate."') as oq 
                                            FROM dump_fabric_stock_data WHERE in_date < '".$firstDate."'");
                                        
                                    foreach ($FabricInwardDetails1 as $row1) 
                                    {
                                        $outward_qty1 = $row1->oq ?? 0;
                                        $grn_qty1 = $row1->gq ?? 0;
                                        $ind_outward2 = explode(",", $row1->ind_outward_qty);
                                        $q_qty1 = 0;
                                        $rate = $row1->rate;
                                        
                                        foreach ($ind_outward2 as $indu1) 
                                        {
                                            $ind_outward_parts = explode("=>", $indu1);
                                            if (count($ind_outward_parts) == 2) 
                                            {
                                                [$ind_outward_key, $ind_outward_value] = $ind_outward_parts;
                                                $q_qty1 += ($ind_outward_key < $firstDate) ? $ind_outward_value : 0;
                                            }
                                        }
                                        
                                        $stocks = ($row1->qc_qty > 0) ? $row1->qc_qty - $q_qty1 : $grn_qty1 - $q_qty1;
                                        
                                        $total_stock += $stocks;
                                        $total_value += $stocks * $rate;
                                    }


                                    $FabricopeningStockQty = $total_stock; 
                                    
                                    $FabricopeningStockValue = $total_value; 
                              
                                    
                                    $FabInOutStockList1=DB::select("select   
                                        (select ifnull(sum(inward_details.meter),sum(fabric_checking_details.meter)) as meter from inward_details
                                        left join fabric_checking_details on fabric_checking_details.track_code=inward_details.track_code where in_date BETWEEN '".$firstDate."' AND '".$lastDate."') as InwardQty,
                                        
                                        (select ifnull(sum(inward_details.meter * inward_details.item_rate),sum(fabric_checking_details.meter * inward_details.item_rate)) as meter from inward_details
                                        left join fabric_checking_details on fabric_checking_details.track_code=inward_details.track_code where in_date BETWEEN '".$firstDate."' AND '".$lastDate."') as InwardValue");
                                       
                                     
                                    $FabricInwardQty = isset($FabInOutStockList1[0]->InwardQty) ? $FabInOutStockList1[0]->InwardQty : 0; 
                                    $FabricInwardValue = isset($FabInOutStockList1[0]->InwardValue) ? $FabInOutStockList1[0]->InwardValue : 0; 
                                    
                                    $InwardFabricQtyArr[] = $FabricInwardQty;
                                    $InwardFabricValueArr[] = $FabricInwardValue;
                                     
                                    $FabInOutStockList2=DB::select("select (select ifnull(sum(meter),0) as meter from fabric_outward_details where fout_date BETWEEN '".$firstDate."' AND '".$lastDate."') as OutwardQty,
                                        (select ifnull(sum(meter * fabric_outward_details.item_rate),0) as meter from fabric_outward_details where fout_date BETWEEN '".$firstDate."' AND '".$lastDate."') as OutwardValue");
                                    
                                    $FabricOutwardQty = isset($FabInOutStockList2[0]->OutwardQty) ? $FabInOutStockList2[0]->OutwardQty : 0; 
                                    $FabricOutwardValue = isset($FabInOutStockList2[0]->OutwardValue) ? $FabInOutStockList2[0]->OutwardValue : 0;  
                                    
                                    $OutwardFabricQtyArr[] = $FabricOutwardQty;
                                    $OutwardFabricValueArr[] = $FabricOutwardValue;
                                    
                                    $OpeningsQty = $OpeningsValue = 0;
                                    
                                    if ($firstDate == date('Y-04-01')) {
                                        $OpeningsQty = $FabricopeningStockQty;
                                        $OpeningsValue = $FabricopeningStockValue;
                                    } else {
                                        $OpeningsQty = $FabricopeningStockQty + $FabricInwardQty - $FabricOutwardQty;
                                        $OpeningsValue = $FabricopeningStockValue + $FabricInwardValue - $FabricOutwardValue;
                                    }
                                    
                                    if ($cntr1 == 0) {
                                        $openingStockQty = $OpeningsQty;
                                        $openingStockValue = $OpeningsValue;
                                    } else {
                                        $openingStockQty = $FabricopeningStockQty;
                                        $openingStockValue = $FabricopeningStockValue;
                                    }
                                    
                                    $ClosingFabricQtyArr[] = $openingStockQty + $FabricInwardQty - $FabricOutwardQty;
                                    $ClosingFabricValueArr[] = $openingStockValue + $FabricInwardValue - $FabricOutwardValue;
                                    
                                    if ($cntr1 == 0) {
                                        $OpeningFabricQtyArr[] = $openingStockQty;
                                        $OpeningFabricValueArr[] = $openingStockValue;
                                    } else {
                                        $OpeningFabricQtyArr[] = $ClosingFabricQtyArr[$cntr1 - 1];
                                        $OpeningFabricValueArr[] = $ClosingFabricValueArr[$cntr1 - 1];
                                    }

                               
                                    $TrimOpeningData =DB::select("SELECT dump_trim_stock_data.*, sum(grn_qty) as gq,sum(outward_qty) as oq FROM dump_trim_stock_data WHERE trimDate < '".$firstDate."' GROUP BY po_no,item_code");     
                                    
                                    $total_opening_value = 0;
                                  
                                    
                                    foreach($TrimOpeningData as $row)
                                    {
                                        $q_qty = 0;   
                                        $ind_outward1 = (explode(",",$row->ind_outward_qty));
                                        
                                        foreach($ind_outward1 as $indu)
                                        {
                                            
                                             $ind_outward2 = (explode("=>",$indu));
                                              
                                             if($ind_outward2[0] < $firstDate)
                                             {
                                                $ind_out = isset($ind_outward2[1]) ? $ind_outward2[1] : 0; 
                                                $q_qty = $q_qty + $ind_out;
                                               
                                             }
                                        } 
                                      
                                        $stocks =  $row->gq - $q_qty; 
                                        $total_opening_value += ($stocks * $row->rate);
                                    }
                
                
                                    $TrimInwardData = DB::select("select sum(trimsInwardDetail.item_qty * trimsInwardDetail.item_rate)  as Inward from trimsInwardDetail INNER JOIN item_master ON item_master.item_code = trimsInwardDetail.item_code 
                                            where item_master.cat_id !=4 AND trimDate BETWEEN '".$firstDate."' AND '".$lastDate."'");
                                            
                                    $TrimsOutwardData = DB::SELECT("SELECT trimsOutwardDetail.item_qty,trimsOutwardDetail.item_code,trimsOutwardDetail.po_code FROM trimsOutwardDetail 
                                            INNER JOIN item_master ON item_master.item_code = trimsOutwardDetail.item_code 
                                            WHERE item_master.cat_id != 4 AND trimsOutwardDetail.tout_date BETWEEN '".$firstDate."' AND '".$lastDate."'");
                                    
                                            
                                    $outward_qty = 0;
                                    foreach($TrimsOutwardData as $row)
                                    {
                                        $TrimsInwardData = DB::SELECT("SELECT trimsInwardDetail.item_rate FROM trimsInwardDetail  
                                            INNER JOIN item_master ON item_master.item_code = trimsInwardDetail.item_code 
                                            WHERE item_master.cat_id != 4 AND trimsInwardDetail.item_code = '".$row->item_code."' AND po_code='".$row->po_code."'");
                                        
                                        $item_rate = isset($TrimsInwardData[0]->item_rate) ? $TrimsInwardData[0]->item_rate: 0;  
                                        $outward_qty += ($row->item_qty * $item_rate);
                                    }
                                         
                                  
                                    $openingStock = $total_opening_value; 
                                    $inwardQty = isset($TrimInwardData[0]->Inward) ? $TrimInwardData[0]->Inward: 0;   
                                    $outwardQty = $outward_qty;      
                             
                                    
                                    $ClosingTrimsValueArr[] = $openingStock + $inwardQty - $outwardQty;
                                    if($cntr1 == 0)
                                    { 
                                        $OpeningTrimsValueArr[] = $openingStock; 
                                    }
                                    else
                                    {  
                                        $OpeningTrimsValueArr[] = $ClosingTrimsValueArr[$cntr1-1];
                                         
                                    }
                                    
                                    
                                    $InwardTrimsValueArr[] = $inwardQty;
                                    $OutwardTrimsValueArr[] = $outwardQty;


                                $Buyer_Purchase_Order_List = DB::select("SELECT * FROM buyer_purchse_order_master 
                                        WHERE 
                                            (
                                                order_received_date <= '".$lastDate."' 
                                                AND buyer_purchse_order_master.job_status_id = 1 
                                                AND og_id != 4
                                                AND order_type != 2
                                            ) 
                                            OR 
                                                (
                                                    order_close_date = '".$lastDate."'
                                                    AND og_id != 4 
                                                    AND buyer_purchse_order_master.delflag = 0  
                                                )  
                                             AND order_type != 2
                                        ORDER BY buyer_purchse_order_master.tr_code");
                                    
                                $totalCuttingWIPPCS = 0;
                                $totalCuttingWIPMin = 0;
                                $totalSewingWIPPCS = 0;
                                $totalSewingWIPMin = 0;
                                $totalPackingWIPPCS = 0;
                                $totalPackingWIPMin = 0;
                                $totalWIPPCS = 0;
                                $totalWIPMin = 0;
                                $totalCuttingWIPValue = 0;
                                $totalSewingWIPValue = 0;
                                $totalPackingWIPValue = 0;
                                $totalWIPValue = 0;
                                
                                foreach($Buyer_Purchase_Order_List as $row)  
                                {
                                 
                                        $VendorData=DB::select("SELECT ifnull(sum(size_qty_total),0) as work_order_qty from vendor_work_order_detail  
                                             where  sales_order_no='".$row->tr_code."' AND vw_date <= '".$lastDate."'");
                                        
                                        
                                        $CutPanelData = DB::select("SELECT ifnull(sum(size_qty),0) as total_qty  from cut_panel_grn_size_detail2  
                                              where cut_panel_grn_size_detail2.sales_order_no = '".$row->tr_code."' AND cpg_date <= '".$lastDate."'");
                                        
                                        if(count($CutPanelData) > 0)
                                        {
                                                $cutPanelIssueQty = $CutPanelData[0]->total_qty;
                                        }
                                        else
                                        {
                                                $cutPanelIssueQty = 0;
                                        } 
                                        
                                        $StichingData=DB::select("SELECT ifnull(sum(total_qty),0) as stiching_qty from stitching_inhouse_master  
                                             where  sales_order_no='".$row->tr_code."' AND sti_date <='".$lastDate."'");
                                        
                                        if(count($StichingData) > 0)
                                        {
                                                $stichingQty = $StichingData[0]->stiching_qty;
                                        }
                                        else
                                        {
                                                $stichingQty = 0;
                                        }
                                        
                                        
                                       $PackingData = DB::select("SELECT ifnull(sum(total_qty),0) as total_qty  from packing_inhouse_master  
                                                    WHERE packing_inhouse_master.sales_order_no = '".$row->tr_code."' AND pki_date <='".$lastDate."' AND packing_type_id=4");
              
                                       if(count($PackingData) > 0)
                                       {
                                             $pack_order_qty = $PackingData[0]->total_qty;
                                       }
                                       else
                                       {
                                             $pack_order_qty = 0;
                                       }
                                     
                                      
                                
                                      $sewing = $cutPanelIssueQty - $stichingQty;
                                      
                                       
                                     // $WIPAdjustQtyData=DB::select("SELECT ifnull(sum(size_qty_total),0) as WIP_adjust_qty from WIP_Adjustable_Qty_detail where sales_order_no='".$row->tr_code."'");
                            
                                     // $total_adjustable_qty = isset($WIPAdjustQtyData[0]->WIP_adjust_qty) ? $WIPAdjustQtyData[0]->WIP_adjust_qty : 0; 
                                      $total_adjustable_qty = 0;  
                                      $totalWIPPCS += (($VendorData[0]->work_order_qty - $cutPanelIssueQty) + $sewing +($stichingQty - $pack_order_qty)-$total_adjustable_qty);
                                      $totalWIPMin += ((($VendorData[0]->work_order_qty - $cutPanelIssueQty) + $sewing +($stichingQty - $pack_order_qty)-$total_adjustable_qty) * $row->sam);
                                      
                                      $SalesCostingData = DB::select("SELECT * from sales_order_costing_master WHERE sales_order_no = '".$row->tr_code."'");
                              
                                      $fabric_value = isset($SalesCostingData[0]->fabric_value) ? $SalesCostingData[0]->fabric_value : 0;  
                                      $sewing_trims_value = isset($SalesCostingData[0]->sewing_trims_value) ? $SalesCostingData[0]->sewing_trims_value : 0;
                                      $packing_trims_value = isset($SalesCostingData[0]->packing_trims_value) ? $SalesCostingData[0]->packing_trims_value : 0;        
                              
                                      $totalCuttingWIPPCS += ($VendorData[0]->work_order_qty - $cutPanelIssueQty);
                                      $totalCuttingWIPMin += ($VendorData[0]->work_order_qty - $cutPanelIssueQty) * $row->sam;
                                      $totalCuttingWIPValue += ($fabric_value +  $sewing_trims_value + $packing_trims_value) * ($VendorData[0]->work_order_qty - $cutPanelIssueQty);
                                      
                                      $totalSewingWIPPCS += $sewing;
                                      $totalSewingWIPMin += $sewing * $row->sam;
                                      $totalSewingWIPValue +=  ($fabric_value +  $sewing_trims_value + $packing_trims_value) * $sewing;
                                      
                                      $totalPackingWIPPCS += $stichingQty - $pack_order_qty;
                                      $totalPackingWIPMin += ($stichingQty - $pack_order_qty)  * $row->sam;
                                      $totalPackingWIPValue +=  ($fabric_value +  $sewing_trims_value + $packing_trims_value) * ($stichingQty - $pack_order_qty);
                                      
                                      $totalWIPValue += (($fabric_value +  $sewing_trims_value + $packing_trims_value) * (($VendorData[0]->work_order_qty - $cutPanelIssueQty) + $sewing +($stichingQty - $pack_order_qty)-$total_adjustable_qty));
                                      
                                      
                                     
                                 }           
                                 
                                $CuttingWIPPCSArr[] =  $totalCuttingWIPPCS;
                                $CuttingWIPMinArr[] =  $totalCuttingWIPMin;
                                $CuttingWIPValueArr[] =  $totalCuttingWIPValue;
                                                                
                                $SewingWIPPCSArr[] =  $totalSewingWIPPCS;
                                $SewingWIPMinArr[] =  $totalSewingWIPMin;
                                $SewingWIPValueArr[] =  $totalSewingWIPValue;
                                
                                $PackingWIPPCSArr[] =  $totalPackingWIPPCS;
                                $PackingWIPMinArr[] =  $totalPackingWIPMin;
                                $PackingWIPValueArr[] =  $totalPackingWIPValue;
                                
                                $WIPPCSArr[] =  $totalWIPPCS;
                                $WIPMinArr[] =  $totalWIPMin;
                                $WIPValueArr[] =  $totalWIPValue;
                               
                                    

                                @endphp
                                    <td class="text-right" style="background:{{$colorArr[0]}};">{{round($OpeningFabricQtyArr[$cntr1]/100000,2)}}</td> 
                                    <td class="text-right" style="background:{{$colorArr[0]}};">{{round(($OpeningFabricValueArr[$cntr1]/100000),2)}}</td>
                                @php 
                                    $cntr1++;
                                }
                              
                                @endphp 
                            </tr>
                            <tr>
                                <td style="background: antiquewhite;" nowrap class="sticky-col first-col">FABRIC</td>
                                <td style="background: antiquewhite;" nowrap class="sticky-col second-col">Inward</td>
                                <td style="background: antiquewhite;" class="sticky-col third-col">meters</td>
                                @php
                                for($i = 0; $i< count($period);$i++)
                                {  
                                @endphp
                                <td class="text-right" style="background:{{$colorArr[0]}};">{{round($InwardFabricQtyArr[$i]/100000,2)}}</td> 
                                <td class="text-right" style="background:{{$colorArr[0]}};">{{round(($InwardFabricValueArr[$i]/100000),2)}}</td>
                                @php 
                                }
                                @endphp
                            </tr>
                            <tr>
                                <td style="background: antiquewhite;" class="sticky-col first-col"></td>
                                <td style="background: antiquewhite;" nowrap class="sticky-col second-col">Outward</td>
                                <td style="background: antiquewhite;" class="sticky-col third-col">meters</td>
                                @php
                                for($i = 0; $i< count($period);$i++)
                                {  
                                @endphp
                                <td class="text-right" style="background:{{$colorArr[0]}};">{{round($OutwardFabricQtyArr[$i]/100000,2)}}</td> 
                                <td class="text-right" style="background:{{$colorArr[0]}};">{{round(($OutwardFabricValueArr[$i]/100000),2)}}</td>
                                @php 
                                }
                                @endphp
                            </tr>
                            <tr>
                                <td style="background: antiquewhite;border-bottom: 3px solid black;" class="sticky-col first-col"></td>
                                <td style="background: antiquewhite;border-bottom: 3px solid black;" nowrap class="sticky-col second-col">Closing Stock</td>
                                <td style="background: antiquewhite;border-bottom: 3px solid black;" class="sticky-col third-col">meters</td>
                                @php
                                    for($i = 0; $i< count($period);$i++)
                                    {
                                @endphp
                                <td class="text-right" style="background:{{$colorArr[0]}};border-bottom: 3px solid black;">{{round($ClosingFabricQtyArr[$i]/100000,2)}}</td> 
                                <td class="text-right" style="background:{{$colorArr[0]}};border-bottom: 3px solid black;">{{round(($ClosingFabricValueArr[$i]/100000),2)}}</td>
                                @php 
                                }
                                @endphp
                            </tr>
                            <tr>
                                <td style="background: #87ceeba1;" class="sticky-col first-col"></td>
                                <td style="background: #87ceeba1;" nowrap class="sticky-col second-col">Opening Stock</td> 
                                <td style="background: #87ceeba1;" class="sticky-col third-col">meters</td> 
                                @php 
                                
                                for($i = 0; $i< count($period);$i++)
                                {   
                                @endphp
                                <td class="text-right" style="background:{{$colorArr[0]}};">-</td> 
                                <td class="text-right" style="background:{{$colorArr[0]}};">{{round($OpeningTrimsValueArr[$i]/100000,2)}}</td>
                                @php 
                                }
                                @endphp
                            </tr>
                            <tr>
                                <td style="background: antiquewhite;" nowrap class="sticky-col first-col">TRIMS</td>
                                <td style="background: antiquewhite;" nowrap class="sticky-col second-col">Inward</td>
                                <td style="background: antiquewhite;" class="sticky-col third-col">meters</td>
                                @php
                                for($i = 0; $i< count($period);$i++)
                                {  
                                @endphp
                                <td class="text-right" style="background:{{$colorArr[0]}};">-</td> 
                                <td class="text-right" style="background:{{$colorArr[0]}};">{{round($InwardTrimsValueArr[$i]/100000,2)}}</td>
                                @php 
                                }
                                @endphp
                            </tr>
                            <tr>
                                <td style="background: antiquewhite;" class="sticky-col first-col"></td>
                                <td style="background: antiquewhite;" nowrap class="sticky-col second-col">Outward</td>
                                <td style="background: antiquewhite;" class="sticky-col third-col">meters</td>
                                @php
                                for($i = 0; $i< count($period);$i++)
                                {  
                                @endphp
                                <td class="text-right" style="background:{{$colorArr[0]}};">-</td> 
                                <td class="text-right" style="background:{{$colorArr[0]}};">{{round($OutwardTrimsValueArr[$i]/100000,2)}}</td>
                                @php 
                                }
                                @endphp
                            </tr>
                            <tr>
                                <td style="background: antiquewhite;border-bottom: 3px solid black;" class="sticky-col first-col"></td>
                                <td style="background: antiquewhite;border-bottom: 3px solid black;" nowrap class="sticky-col second-col">Closing Stock</td>
                                <td style="background: antiquewhite;border-bottom: 3px solid black;" class="sticky-col third-col">meters</td>
                                @php
                                    for($i = 0; $i< count($period);$i++)
                                    {
                                @endphp
                                <td class="text-right" style="background:{{$colorArr[0]}};border-bottom: 3px solid black;">-</td> 
                                <td class="text-right" style="background:{{$colorArr[0]}};border-bottom: 3px solid black;">{{round($ClosingTrimsValueArr[$i]/100000,2)}}</td>
                                @php 
                                }
                                @endphp
                            </tr> 
                            <tr>
                                <td class="sticky-col first-col" nowrap>Cutting WIP</td>
                                <td nowrap class="sticky-col second-col">L Pcs</td>
                                <td class="sticky-col third-col">meters</td>
                                @php
                               
                                for($i = 0; $i< count($period);$i++)
                                { 
                                @endphp
                                <td class="text-right" style="background:{{$colorArr[0]}};">{{round($CuttingWIPPCSArr[$i]/100000,2)}}</td> 
                                <td class="text-right" style="background:{{$colorArr[0]}};">{{round($CuttingWIPValueArr[$i]/100000,2)}}</td>
                                @php 
                                 
                                }
                                @endphp
                            </tr> 
                            <tr>
                                <td class="sticky-col first-col" nowrap>Cutting WIP</td>
                                <td nowrap class="sticky-col second-col">L Min</td>
                                <td class="sticky-col third-col">meters</td>
                                @php
                               
                                for($i = 0; $i< count($period);$i++)
                                { 
                                @endphp
                                <td class="text-right" style="background:{{$colorArr[0]}};">{{round($CuttingWIPMinArr[$i]/100000,2)}}</td> 
                                <td class="text-right" style="background:{{$colorArr[0]}};">-</td>
                                @php 
                                 
                                }
                                @endphp
                            </tr>
                            <tr>
                                <td class="sticky-col first-col" nowrap>Sewing WIP</td>
                                <td nowrap class="sticky-col second-col">L Pcs</td>
                                <td class="sticky-col third-col">meters</td>
                                @php
                               
                                for($i = 0; $i< count($period);$i++)
                                { 
                                @endphp
                                <td class="text-right" style="background:{{$colorArr[0]}};">{{round($SewingWIPPCSArr[$i]/100000,2)}}</td> 
                                <td class="text-right" style="background:{{$colorArr[0]}};">{{round($SewingWIPValueArr[$i]/100000,2)}}</td>
                                @php 
                                 
                                }
                                @endphp
                            </tr>
                            <tr>
                                <td class="sticky-col first-col" nowrap>Sewing WIP</td>
                                <td nowrap class="sticky-col second-col">L Min</td>
                                <td class="sticky-col third-col">meters</td>
                                @php
                               
                                for($i = 0; $i< count($period);$i++)
                                { 
                                @endphp
                                <td class="text-right" style="background:{{$colorArr[0]}};">{{round($SewingWIPMinArr[$i]/100000,2)}}</td> 
                                <td class="text-right" style="background:{{$colorArr[0]}};">-</td>
                                @php 
                                 
                                }
                                @endphp
                            </tr> 
                            <tr>
                                <td class="sticky-col first-col" nowrap>Packing WIP</td>
                                <td nowrap class="sticky-col second-col">L Pcs</td>
                                <td class="sticky-col third-col">meters</td>
                                @php
                               
                                for($i = 0; $i< count($period);$i++)
                                { 
                                @endphp
                                <td class="text-right" style="background:{{$colorArr[0]}};">{{round($PackingWIPPCSArr[$i]/100000,2)}}</td> 
                                <td class="text-right" style="background:{{$colorArr[0]}};">{{round($PackingWIPValueArr[$i]/100000,2)}}</td>
                                @php 
                                 
                                }
                                @endphp
                            </tr> 
                            <tr>
                                <td class="sticky-col first-col" nowrap>Packing WIP</td>
                                <td nowrap class="sticky-col second-col">L Min</td>
                                <td class="sticky-col third-col">meters</td>
                                @php
                               
                                for($i = 0; $i< count($period);$i++)
                                { 
                                @endphp
                                <td class="text-right" style="background:{{$colorArr[0]}};">{{round($PackingWIPMinArr[$i]/100000,2)}}</td> 
                                <td class="text-right" style="background:{{$colorArr[0]}};">-</td>
                                @php 
                                 
                                }
                                @endphp
                            </tr> 
                            <tr>
                                <td class="sticky-col first-col" nowrap>Total WIP</td>
                                <td nowrap class="sticky-col second-col">L Pcs</td>
                                <td class="sticky-col third-col">meters</td>
                                @php
                               
                                for($i = 0; $i< count($period);$i++)
                                { 
                                @endphp
                                <td class="text-right" style="background:{{$colorArr[0]}};">{{round($WIPPCSArr[$i]/100000,2)}}</td> 
                                <td class="text-right" style="background:{{$colorArr[0]}};">{{round($WIPValueArr[$i]/100000,2)}}</td>
                                @php 
                                 
                                }
                                @endphp
                            </tr>
                            <tr>
                                <td class="sticky-col first-col" nowrap  style="background: #7fff0073;border-bottom: 3px solid black;">Total WIP</td>
                                <td nowrap class="sticky-col second-col"  style="background: #7fff0073;border-bottom: 3px solid black;">L Min</td>
                                <td class="sticky-col third-col"  style="background: #7fff0073;border-bottom: 3px solid black;">meters</td>
                                @php
                               
                                for($i = 0; $i< count($period);$i++)
                                { 
                                @endphp
                                <td class="text-right" style="background:{{$colorArr[0]}};border-bottom: 3px solid black;">{{round($WIPMinArr[$i]/100000,2)}}</td> 
                                <td class="text-right" style="background:{{$colorArr[0]}};border-bottom: 3px solid black;">-</td>
                                @php 
                                 
                                }
                                @endphp
                            </tr>     
                            <tr>
                                <td class="sticky-col first-col"></td>
                                <td nowrap class="sticky-col second-col">Opening Stock</td>
                                <td class="sticky-col third-col">piece</td>
                                @php
                                
                                $OpeningFGQtyArr = [];
                                $InwardFGQtyArr = [];
                                $OutwardFGQtyArr = [];
                                $TransferFGQtyArr = [];
                                $ClosingFGQtyArr = [];
                                
                                
                                $OpeningFGValueArr = [];
                                $InwardFGValueArr = [];
                                $OutwardFGValueArr = [];
                                $TransferFGValueArr = [];
                                $ClosingFGValueArr = [];
                                
                                $OpeningFGQtyArr1 = [];
                                $OpeningFGValueArr1 = [];
                                
                                $cntr2 = 0;
                                
                                
                                foreach($period as $dates)
                                {     
                                
                                    $openingQtyFG = 0;
                                    $openingValueFG = 0;
                                    $packingQtyFG = 0;
                                    $packingValueFG = 0;
                                    $cartonQtyFG = 0;
                                    $cartonValueFG = 0;
                                    $transferQtyFG = 0;
                                    $transferValueFG = 0;
                                
                                    $firstDate = date($dates."-01");
                                    $lastDate = date("Y-m-t", strtotime( $dates."-01"));
                                     
                            
                                    $FinishedGoodsStock = DB::select("SELECT FG.entry_date,
                                        SUM(CASE WHEN FG.entry_date BETWEEN '".$firstDate."' AND '".$lastDate."' AND FG.data_type_id IN (1) THEN FG.size_qty ELSE 0 END) AS total_packing_qty,
                                        SUM(CASE WHEN FG.entry_date BETWEEN '".$firstDate."' AND '".$lastDate."' AND FG.data_type_id IN (1) THEN (FG.size_qty * IFNULL(sales_order_costing_master.total_cost_value, buyer_purchse_order_master.order_rate)) ELSE 0 END) AS total_packing_value,
                                        SUM(CASE WHEN FG.entry_date BETWEEN '".$firstDate."' AND '".$lastDate."' AND FG.data_type_id IN (2) THEN FG.size_qty ELSE 0 END) AS total_carton_qty,
                                        SUM(CASE WHEN FG.entry_date BETWEEN '".$firstDate."' AND '".$lastDate."' AND FG.data_type_id IN (2) THEN (FG.size_qty * IFNULL(sales_order_costing_master.total_cost_value, buyer_purchse_order_master.order_rate)) ELSE 0 END) AS total_carton_value,
                                        SUM(CASE WHEN FG.entry_date BETWEEN '".$firstDate."' AND '".$lastDate."' AND FG.data_type_id IN (3) THEN FG.size_qty ELSE 0 END) AS total_transfer_qty,
                                        SUM(CASE WHEN FG.entry_date BETWEEN '".$firstDate."' AND '".$lastDate."' AND FG.data_type_id IN (3) THEN (FG.size_qty * IFNULL(sales_order_costing_master.total_cost_value, buyer_purchse_order_master.order_rate)) ELSE 0 END) AS total_transfer_value,
                                        SUM(FG.size_qty) AS total_stock,
                                        SUM(FG.size_qty * IFNULL(sales_order_costing_master.total_cost_value, buyer_purchse_order_master.order_rate)) AS total_stock_value,
                                        (SUM(CASE WHEN FG.data_type_id IN (1) AND FG.entry_date < '".$firstDate."' THEN FG.size_qty ELSE 0 END) - SUM(CASE WHEN FG.data_type_id IN (2) AND FG.entry_date < '".$firstDate."' THEN FG.size_qty ELSE 0 END) - SUM(CASE WHEN FG.data_type_id IN (3) AND FG.entry_date < '".$firstDate."' THEN FG.size_qty ELSE 0 END)) AS opening_stock,
                                        (SUM(CASE WHEN FG.data_type_id IN (1) AND FG.entry_date < '".$firstDate."' THEN (FG.size_qty * IFNULL(sales_order_costing_master.total_cost_value, buyer_purchse_order_master.order_rate)) ELSE 0 END) - SUM(CASE WHEN FG.data_type_id IN (2) AND FG.entry_date < '".$firstDate."' THEN (FG.size_qty * IFNULL(sales_order_costing_master.total_cost_value, buyer_purchse_order_master.order_rate)) ELSE 0 END) - SUM(CASE WHEN FG.data_type_id IN (3) AND FG.entry_date < '".$firstDate."' THEN (FG.size_qty * IFNULL(sales_order_costing_master.total_cost_value, buyer_purchse_order_master.order_rate)) ELSE 0 END)) AS opening_value
                                    FROM 
                                        FGStockDataByTwo AS FG
                                    INNER JOIN 
                                        buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = FG.sales_order_no 
                                    INNER JOIN 
                                        job_status_master ON job_status_master.job_status_id = buyer_purchse_order_master.job_status_id
                                    INNER JOIN 
                                        brand_master ON brand_master.brand_id = buyer_purchse_order_master.brand_id
                                    LEFT JOIN 
                                        order_type_master ON order_type_master.orderTypeId = buyer_purchse_order_master.order_type
                                    LEFT JOIN 
                                        sales_order_costing_master ON sales_order_costing_master.sales_order_no = FG.sales_order_no
                                    WHERE 
                                        FG.data_type_id IN (1, 2, 3)
                                        AND (FG.entry_date BETWEEN '".$firstDate."' AND '".$lastDate."' OR FG.entry_date < '".$firstDate."')");

                                       
                                        foreach($FinishedGoodsStock as $row)
                                        {
                                            $openingQtyFG += $row->opening_stock;
                                            $openingValueFG += $row->opening_value;
                                            
                                            $packingQtyFG += $row->total_packing_qty;
                                            $packingValueFG += $row->total_packing_value;
                                            
                                            $cartonQtyFG += $row->total_carton_qty;
                                            $cartonValueFG += $row->total_carton_value;
                                            
                                            $transferQtyFG += $row->total_transfer_qty;
                                            $transferValueFG += $row->total_transfer_value;
                                        }
                                       
                                        
                                        $InwardFGQtyArr[] = $packingQtyFG;
                                        $InwardFGValueArr[] = $packingValueFG;
                                        $TransferFGQtyArr[] = $transferQtyFG;  
                                        $TransferFGValueArr[] = $transferValueFG;  
                                        $OutwardFGQtyArr[] = $cartonQtyFG;   
                                        $OutwardFGValueArr[] = $cartonValueFG;  
                                         
                                    
                                        
                                        $ClosingFGQtyArr[] = $openingQtyFG + $packingQtyFG - $cartonQtyFG - $transferQtyFG;
                                        $ClosingFGValueArr[] = $openingValueFG + $packingValueFG - $cartonValueFG - $transferValueFG;
                                     
                                   
                                        $OpeningFGQtyArr[] = $openingQtyFG;
                                        $OpeningFGValueArr[] = $openingValueFG;
                                     
                                        
                                    $cntr2++;
                                 } 
                                 
                              
                                
                                 for($i = 0; $i< count($period);$i++)  
                                 {  
                                @endphp
                                <td class="text-right" style="background:{{$colorArr[0]}};border-bottom: 3px solid black;">{{round($OpeningFGQtyArr[$i]/100000,2)}}</td> 
                                <td class="text-right" style="background:{{$colorArr[0]}};border-bottom: 3px solid black;">{{round($OpeningFGValueArr[$i]/100000,2)}}</td>
                                @php 
                               
                                } 
                                @endphp
                            </tr>
                            <tr>
                                <td style="background: #7fff0073;" class="sticky-col first-col">FG</td>
                                <td style="background: #7fff0073;" class="sticky-col second-col">Production</td>
                                <td style="background: #7fff0073;" class="sticky-col third-col">piece</td>
                                @php
                                for($i = 0; $i< count($period);$i++)
                                {     
                                @endphp
                                <td class="text-right" style="background:{{$colorArr[0]}};">{{round($InwardFGQtyArr[$i]/100000,2)}}</td> 
                                <td class="text-right" style="background:{{$colorArr[0]}};">{{round(($InwardFGValueArr[$i])/100000,2)}}</td>
                                @php 
                                }
                                @endphp
                            </tr>
                            <tr>
                                <td style="background: #7fff0073;" class="sticky-col first-col"></td>
                                <td style="background: #7fff0073;" class="sticky-col second-col">Transfer</td>
                                <td style="background: #7fff0073;" class="sticky-col third-col">piece</td>
                                @php
                                for($i = 0; $i< count($period);$i++)
                                {   
                                @endphp
                                <td class="text-right" style="background:{{$colorArr[0]}};">{{round($TransferFGQtyArr[$i]/100000,2)}}</td> 
                                <td class="text-right" style="background:{{$colorArr[0]}};">{{round(($TransferFGValueArr[$i])/100000,2)}}</td>
                                @php 
                                }
                                @endphp
                            </tr>
                            <tr>
                                <td style="background: #7fff0073;" class="sticky-col first-col"></td>
                                <td style="background: #7fff0073;" class="sticky-col second-col">Outward</td>
                                <td style="background: #7fff0073;" class="sticky-col third-col">piece</td>
                                @php
                                for($i = 0; $i< count($period);$i++)
                                {  
                                    
                                @endphp
                                <td class="text-right" style="background:{{$colorArr[0]}};">{{round($OutwardFGQtyArr[$i]/100000,2)}}</td> 
                                <td class="text-right" style="background:{{$colorArr[0]}};">{{round(($OutwardFGValueArr[$i])/100000,2)}}</td>
                                @php 
                                }
                                @endphp
                            </tr> 
                            <tr>
                                <td style="background: #7fff0073;border-bottom: 3px solid black;" class="sticky-col first-col"></td>
                                <td style="background: #7fff0073;border-bottom: 3px solid black;" nowrap class="sticky-col second-col">Closing Stock</td>
                                <td style="background: #7fff0073;border-bottom: 3px solid black;" class="sticky-col third-col">piece</td>
                                @php
                                
                                for($i=0;$i< count($period); $i++)
                                {     
                                @endphp
                                <td class="text-right" style="background:{{$colorArr[0]}};border-bottom: 3px solid black;">{{round(($ClosingFGQtyArr[$i])/100000,2)}}</td> 
                                <td class="text-right" style="background:{{$colorArr[0]}};border-bottom: 3px solid black;">{{round(($ClosingFGValueArr[$i])/100000,2)}}</td>
                                @php 
                                }
                                @endphp
                            </tr> 
                            <tr>
                                <td style="background: #7fff0073;border-bottom: 3px solid black;" class="sticky-col first-col"></td>
                                <td style="background: #7fff0073;" nowrap class="sticky-col second-col">TOTAL (Fabric + WIP)</td>
                                <td style="background: #7fff0073;" class="sticky-col third-col"></td> 
                                @php
                                
                                for($i=0;$i< count($period); $i++)
                                {    
                                     $WIPClosingStockQty = $OpeningFabricQtyArr[$i] + $InwardFabricQtyArr[$i] - $OutwardFabricQtyArr[$i];
                                     $WIPClosingStockValue = $OpeningFabricValueArr[$i] + $InwardFabricValueArr[$i] - $OutwardFabricValueArr[$i];
                                   
                                    
                                     $totalFabricFGQty = $ClosingFabricQtyArr[$i] + $WIPClosingStockQty;
                                     $totalFabricFGValue = $ClosingFabricValueArr[$i] + $WIPClosingStockValue;
                                     
                                @endphp
                                <td class="text-right" style="background:{{$colorArr[0]}};border-bottom: 3px solid black;">{{round($totalFabricFGQty/100000,2)}}</td> 
                                <td class="text-right" style="background:{{$colorArr[0]}};border-bottom: 3px solid black;">{{round($totalFabricFGValue/100000,2)}}</td>
                                @php 
                                }
                                @endphp
                            </tr> 
                            <tr>
                                <td style="background: #7fff0073;border-top: 3px solid black;" class="sticky-col first-col"></td>
                                <td style="background: #7fff0073;border-bottom: 3px solid black;" nowrap class="sticky-col second-col">TOTAL FG</td>
                                <td style="background: #7fff0073;border-bottom: 3px solid black;" class="sticky-col third-col"></td>
                                @php
                                
                                for($i=0;$i< count($period); $i++)
                                {   
                                       
                                       if($i == 0)
                                       {
                                            $closingFG1Qty = $OpeningFGQtyArr[0] + $InwardFGQtyArr[0] - $TransferFGQtyArr[0] - $OutwardFGQtyArr[0];
                                            $closingFG1Value = $OpeningFGValueArr[0] + $InwardFGValueArr[0] - $TransferFGValueArr[0] - $OutwardFGValueArr[0];
                                            
                                            $closingFGQty = $closingFG1Qty;
                                            $closingFGValue = $closingFG1Value;
                                       }
                                       else
                                       {
                                            $closingFG1Qty = $OpeningFGQtyArr[$i-1] + $InwardFGQtyArr[$i-1] - $TransferFGQtyArr[$i-1] - $OutwardFGQtyArr[$i-1];
                                            $closingFG1Value = $OpeningFGValueArr[$i-1] + $InwardFGValueArr[$i-1] - $TransferFGValueArr[$i-1] - $OutwardFGValueArr[$i-1];
                                            
                                            $closingFGQty = $closingFG1Qty + $InwardFGQtyArr[$i] - $TransferFGQtyArr[$i] - $OutwardFGQtyArr[$i];
                                            $closingFGValue = $closingFG1Value + $InwardFGValueArr[$i] - $TransferFGValueArr[$i] - $OutwardFGValueArr[$i];
                                       }
                                @endphp
                                <td class="text-right" style="background:{{$colorArr[0]}};border-bottom: 3px solid black;">{{round($closingFGQty/100000,2)}}</td> 
                                <td class="text-right" style="background:{{$colorArr[0]}};border-bottom: 3px solid black;">{{round($closingFGValue/100000,2)}}</td>
                                @php 
                                }
                                @endphp
                            </tr> 
                            <tr>
                                <td style="background: #7fff0073;border-bottom: 3px solid black;" class="sticky-col first-col"></td>
                                <td style="background: #7fff0073;border-bottom: 3px solid black;" nowrap  class="sticky-col second-col">GRAND TOTAL</td>
                                <td style="background: #7fff0073;border-bottom: 3px solid black;" class="sticky-col third-col"></td>
                                @php
                                
                                for($i=0;$i< count($period); $i++)
                                {   
                                    
                                     $WIPClosingStockQty = $OpeningFabricQtyArr[$i] + $InwardFabricQtyArr[$i] - $OutwardFabricQtyArr[$i];
                                     $WIPClosingStockValue = $OpeningFabricValueArr[$i] + $InwardFabricValueArr[$i] - $OutwardFabricValueArr[$i];
                                   
                                    
                                     $totalFabricFGQty = $ClosingFabricQtyArr[$i] + $WIPClosingStockQty;
                                     $totalFabricFGValue = $ClosingFabricValueArr[$i] + $WIPClosingStockValue;
                                     
                                     if($i == 0)
                                     {
                                        $closingFG1Qty = $OpeningFGQtyArr[0] + $InwardFGQtyArr[0] - $TransferFGQtyArr[0] - $OutwardFGQtyArr[0];
                                        $closingFG1Value = $OpeningFGValueArr[0] + $InwardFGValueArr[0] - $TransferFGValueArr[0] - $OutwardFGValueArr[0];
                                        
                                        $closingFGQty = $closingFG1Qty;
                                        $closingFGValue = $closingFG1Value;
                                     }
                                     else
                                     {
                                        $closingFG1Qty = $OpeningFGQtyArr[$i-1] + $InwardFGQtyArr[$i-1] - $TransferFGQtyArr[$i-1] - $OutwardFGQtyArr[$i-1];
                                        $closingFG1Value = $OpeningFGValueArr[$i-1] + $InwardFGValueArr[$i-1] - $TransferFGValueArr[$i-1] - $OutwardFGValueArr[$i-1];
                                        
                                        $closingFGQty = $closingFG1Qty + $InwardFGQtyArr[$i] - $TransferFGQtyArr[$i] - $OutwardFGQtyArr[$i];
                                        $closingFGValue = $closingFG1Value + $InwardFGValueArr[$i] - $TransferFGValueArr[$i] - $OutwardFGValueArr[$i];
                                     }
                                       
                                @endphp
                                <td class="text-right" style="background:{{$colorArr[0]}};border-bottom: 3px solid black;">{{round(($totalFabricFGQty+$closingFGQty)/100000,2)}}</td> 
                                <td class="text-right" style="background:{{$colorArr[0]}};border-bottom: 3px solid black;">{{round(($totalFabricFGValue+$closingFGValue)/100000,2)}}</td>
                                @php 
                                }
                                @endphp
                            </tr> 
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>  
</div>   
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>

<script type="text/javascript">

    $('table').on('scroll', function () {
      
        $("#"+this.id+" > *").width($(this).width() + $(this).scrollLeft());
    });
    
   let page = 1;

   function tableData() 
   {
         var currentURL = window.location.href; 
                        
         var totalpacking_qty = 0;
         var totalcarton_pack_qty = 0;
         var totaltransfer_qty = 0;
         var totalstock = 0;
       
         
      	 //$('#tbl').DataTable().clear().destroy();
        
        //   var table = $('#tbl').DataTable({
        //     ajax: currentURL,    
        //     dom : 'Bfrtip',
        //     processing: true,
        //     serverSide: false,
        //     buttons: [
        //         { extend: 'copyHtml5', footer: true },
        //         { extend: 'excelHtml5', footer: true },
        //         { extend: 'csvHtml5', footer: true },
        //         { extend: 'pdfHtml5', footer: true }
        //     ],
        //      "footerCallback": function (row, data, start, end, display) 
        //      {   
                  
        //         // var totalValue = 0;
        //         // if(data.length > 0)
        //         // {
        //         //     for (var i = 0; i < data.length; i++) 
        //         //     {
        //         //         // totalpacking_qty += parseFloat(data[i].packing_qty);
        //         //         // totalcarton_pack_qty += parseFloat(data[i].carton_pack_qty);
        //         //         // totaltransfer_qty += parseFloat(data[i].transfer_qty);
        //         //         // totalstock += parseFloat(data[i].stock);
        //         //         totalValue += parseFloat(data[i].Value);
        //         //     }
        //         //     console.log(totalValue);
        //         // }
              
        //         //$('#head_packing_grn_qty').html(totalpacking_qty.toLocaleString('en-IN'));
        //         // $('#head_carton_packing_qty').html(totalcarton_pack_qty.toLocaleString('en-IN'));
        //         // $('#head_transfered_qty').html(totaltransfer_qty.toLocaleString('en-IN'));
        //         // $('#head_fg_stock').html(totalstock.toLocaleString('en-IN'));
        //         // $('#head_value').html(totalSum.toLocaleString('en-IN'));
        //         // $("#totalFGStock").html('<b>Total Stock : </b><b>'+totalstock/100000+'</b>');
        //         // $("#totalFGValue").html('<b>Total Value : </b><b>'+totalValue/100000+'</b>');
        //       },
        //       columns: [
        //           {data: 'ac_name', name: 'ac_name'},
        //           {data: 'sales_order_no', name: 'sales_order_no'},
        //           {data: 'sam', name: 'sam'},
        //           {data: 'job_status_name', name: 'job_status_name'},
        //           {data: 'brand_name', name: "brand_name"},
        //           {data: 'mainstyle_name', name: 'mainstyle_name'}, 
        //           {data: 'color_name', name: 'color_name'},
        //           {data: 'size_name', name: 'size_name'},
        //           {data: 'packing_qty', name: 'packing_qty'},
        //           {data: 'carton_pack_qty', name: 'carton_pack_qty'},
        //           {data: 'transfer_qty', name: 'transfer_qty'},
        //           {data: 'stock', name: 'stock'},
        //           {data: 'fob_rate', name: 'fob_rate'},
        //           {data: 'Value', name: 'Value'},
        //     ]
        // });
        var currentDate = $("#currentDate").val();
        // $.ajax({
        //     dataType: "json", 
        //     url: "{{ route('LoadFGStockReportTrial') }}", 
        //     data:{'currentDate':currentDate},
        //     success: function(data)
        //     {
        //         console.log(data); 
                    
        //     } 
        // });
         
            
            $.ajax({
                url:  "{{ route('LoadFGStockReportTrial') }}",
                type: 'GET',
                data: { 'page': page,'currentDate':currentDate },
                complete: function(data){
                     var table = $('#tbl').DataTable({  
                            dom : 'Bfrtip',
                            processing: true,
                            serverSide: false,
                            buttons: [
                                { extend: 'copyHtml5', footer: true },
                                { extend: 'excelHtml5', footer: true },
                                { extend: 'csvHtml5', footer: true },
                                { extend: 'pdfHtml5', footer: true }
                            ],
                     });
                },
                success: function(data) 
                { 
                   
                    // if(lastRow != 'undefined')
                    // {
                    //     lastRow.after(data); 
                    //     setTimeout(function() 
                    //     { 
                    //         page++;  
                    //         tableData(); 
                            
                    //     }, 2500);
                        
                    // }
                    // else
                    // {
                     
                        $('tbody').append(data.html);
                        
                        $("#head_packing_grn_qty").html(data.total_packing);
                        $("#head_carton_packing_qty").html(data.total_carton);
                        $("#head_transfered_qty").html(data.total_transfer);
                        $("#head_fg_stock").html(data.total_stock);
                        $("#head_value").html(data.total_value);
                        $("#totalFGStock").html('<b>Total Stock(In Lakh): '+data.total_stock+'</b>');
                        $("#totalFGValue").html('<b>Total Value(In Lakh): '+data.total_value+'</b>');
                        // setTimeout(function() 
                        // { 
                            // page++;  
                           
                            
                        // }, 2500);
                    // } 
                }
            });
    }
    
    
    $( document ).ready(function() 
    { 
       //tableData();
       
    });
    
    var xhr;
    function syncData()
    {
         xhr = $.ajax({
            dataType: "json",
            url: "{{ route('DumpFGData') }}",
            beforeSend: function() 
            {
                $("#sync").attr('disabled','disabled');
            },
            complete: function(data)
            {
                $("#sync").removeAttr('disabled');
                setTimeout(function() 
                { 
                    $(".alert-success").addClass('hide'); 
                    
                }, 2500);
            },
            success: function(data)
            {
                tableData();
                $(".alert-success").removeClass('hide'); 
                    
            },
            error: function (error) 
            {
            }
        });
    }
   
    function abort()
    {
        console.log("abort");
        xhr.abort();
    }
  
</script>                                        
@endsection