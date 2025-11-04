@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp                
<!-- end page title -->
@php
if($job_status_id==1) { @endphp
<style>
    .tr{
        background: #423434;
        color: #fff;
    }
    th{
        text-align:center;
    }
</style>
<div class="row">
   <div class="col-12 text-center"> 
          <h3><b>Live Running Order Status Report</b></h3> 
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
               <table id="liveTbl" class="DataTable table table-bordered   nowrap w-100">
                  <thead>
                     <tr nowrap class="tr">
                        <th nowrap>Sr No.</th>
                        <th nowrap>Buyer Brand</th>
                        <th nowrap>Order type </th>
                        <th nowrap>Order Qty</th>
                        <th nowrap>SAM</th>
                        <th nowrap>Order Min</th>
                        <th nowrap>Order</th>
                        <th nowrap>Fabric Issued</th>
                        <th nowrap>Cut Qty</th>
                        <th nowrap>Sew Qty</th>
                        <th nowrap>Packing Qty</th>
                        <th nowrap>FG Stock</th>
                        <th nowrap>Ship Qty</th>
                        <th nowrap>B2P</th>
                        <th nowrap>B2S</th>
                        <th nowrap>CMOHP</th>
                        <th nowrap>CMOHP</th>
                        <th nowrap>CMOHP</th>
                        <th nowrap>Cr. Days</th>
                        <th nowrap>Cut WIP</th>
                        <th nowrap>Sew WIP</th>
                        <th nowrap>Packing WIP</th>
                        <th nowrap>Total WIP</th>
                        <th nowrap>Live C2S</th>
                        <th nowrap>Order Cmpltn</th> 
                     </tr>
                  </thead>
                  <tbody>
                     <tr nowrap class="tr">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td nowrap class="text-center"><b>L Min</b></td>
                        <td nowrap class="text-center"><b>Pcs</b></td>
                        <td nowrap class="text-center"><b>Pcs</b></td>
                        <td nowrap class="text-center"><b>Pcs</b></td>
                        <td nowrap class="text-center"><b>Pcs</b></td>
                        <td nowrap class="text-center"><b>Pcs</b></td>
                        <td nowrap class="text-center"><b>Pcs</b></td>
                        <td nowrap class="text-center"><b>L Min</b></td>
                        <td nowrap class="text-center"><b>L Min</b></td>
                        <td></td>
                        <td nowrap class="text-center"><b>₹ In Lakhs</b></td>
                        <td nowrap class="text-center"><b>₹/Min</b></td>
                        <td></td>
                        <td nowrap class="text-center"><b>Pcs</b></td>
                        <td nowrap class="text-center"><b>Pcs</b></td>
                        <td nowrap class="text-center"><b>Pcs</b></td>
                        <td nowrap class="text-center"><b>Pcs</b></td>
                        <td nowrap class="text-center"><b>%</b></td>
                        <td nowrap class="text-center"><b>%</b></td> 
                     </tr>
                     @php 
                        $FGStock = 0; 
                        $total_order_qty = 0;
                        $total_order_min = 0;
                        $total_cut_qty  = 0;
                        $total_prod_qty = 0;
                        $total_fg_stock = 0;
                        $total_ship_qty = 0;
                        $total_fg_stock_qty = 0;             
                        $total_b2p = 0;
                        $total_b2s = 0;
                        $total_chmoh = 0;
                        $total_inlakh_chmoh = 0;
                        $total_packed_qty = 0;
                        $total_fabric_issued = 0;
                        $total_cut_WIP = 0;
                        $total_sew_WIP = 0;
                        $total_packing_WIP = 0;
                        $total_WIP = 0;
                        $total_cut_panel = 0;
                        $totalcmohpMin = 0;
                        $totaloverCmohpMin = 0;
                        $totalCrDays = 0;
                        $srno = 1;
                     @endphp 
                     @foreach($Buyer_Purchase_Order_List as $row)    
                     @php
                    
                      //DB::enableQueryLog();
                
                    $packingData = DB::select("SELECT ifnull(sum(packing_inhouse_master.total_qty),0)  as packing_qty FROM packing_inhouse_master  
                                   INNER JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = packing_inhouse_master.sales_order_no 
                                   where buyer_purchse_order_master.brand_id = '". $row->brand_id."' AND job_status_id = 1 
                                   AND og_id != 4 AND order_type=".$row->orderTypeId." AND pki_date<='".$date."' AND buyer_purchse_order_master.order_received_date<='".$date."'");
                    
                        // dd(DB::getQueryLog());  
                        
              
                    $FGStock = $packingData[0]->packing_qty - $row->shipped_qty;
                    
                   
                    $sam = ($row->orderMin/$row->order_qty);
                    
                    $profit_value=0.0;
                  
                     
                    if($row->shipped_qty && $row->cut_qty)
                    {
                        $livec2s = $row->shipped_qty/$row->cut_qty;
                    }
                    else
                    {
                        $livec2s = 0;
                    }  
                    
                     
                    if($row->shipped_qty && $row->order_qty)
                    {
                        $orderQtyCompletion = $row->shipped_qty/$row->order_qty;
                    }
                    else
                    {
                        $orderQtyCompletion = 0;
                    } 
                 //DB::enableQueryLog();
                      $buyerData = DB::table('buyer_purchse_order_master')
                        ->where('brand_id', $row->brand_id) 
                        ->where('order_type', $row->orderTypeId) 
                        ->where('buyer_purchse_order_master.delflag','=', '0')
                        ->where('buyer_purchse_order_master.og_id','!=', '4')
                        ->where('buyer_purchse_order_master.job_status_id','=', '1')
                        ->get();
                   
                   //dd(DB::getQueryLog());
                   
                    $cutpanel = 0;
                    $cmohp = 0; 
                    $cmohp2 = 0;
                    $orderMin = ($sam * $row->order_qty);
                     // DB::enableQueryLog();
                    $buyData = DB::select("select sum((total_qty*sales_order_costing_master.sam) 
                                            * round((( (buyer_purchse_order_master.order_rate-total_cost_value) 
                                            + production_value + other_value)/sales_order_costing_master.sam),2)) as chmoh 
                                            FROM buyer_purchse_order_master 
                                            LEFT JOIN sales_order_costing_master ON sales_order_costing_master.sales_order_no = buyer_purchse_order_master.tr_code 
                                            WHERE brand_id =".$row->brand_id." AND job_status_id = 1 AND og_id != 4 AND order_type=".$row->orderTypeId." AND soc_date<='".$date."' AND buyer_purchse_order_master.order_received_date<='".$date."'");
                     // dd(DB::getQueryLog());  
                    
                    $costingData = DB::select("SELECT * FROM sales_order_costing_master WHERE sales_order_no ='".$row->tr_code."' AND soc_date<='".$date."' AND delflag=0");
                     
                    $cmohp2 =  isset($buyData[0]->chmoh) ? $buyData[0]->chmoh : 0; 
                   
                        
                    foreach($buyerData as $buyer)
                    {
                        $consData = DB::table('sales_order_fabric_costing_details')
                          ->where('sales_order_no', $buyer->tr_code)
                          ->whereIN('class_id', [1,2]) 
                          ->first(); 
                          
                         $outward = DB::select("select ifnull(sum(total_meter),0) as total_meter from fabric_outward_master
                                    INNER JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = fabric_outward_master.vpo_code 
                                    WHERE vendor_purchase_order_master.sales_order_no ='".$buyer->tr_code."' AND fout_date<='".$date."'");
                                    
                        $total_meter =  isset($outward[0]->total_meter) ? $outward[0]->total_meter : 0; 
                        $cons = isset($consData->consumption) ? $consData->consumption: 0; 
                        
                        if($total_meter > 0 && $cons > 0)
                        {
                            $cutpanel = $cutpanel + ($total_meter/$cons);        
                        }
                        else
                        {
                            $cutpanel = $cutpanel + 0;   
                        }
                       
                     
                    }
                  
                    $cutWIP = $cutpanel-$row->cut_qty;
                    $cmohpMin = (($cmohp2/100000)/((($sam) * ($row->order_qty))/100000));
                    
                    $totalcmohpMin = ($cmohpMin * ((($sam) * ($row->order_qty))/100000));
                    $b2s = (($row->order_qty-$row->shipped_qty)*($sam/100000));
                    
                    $totalb2s = $b2s * $row->ptm_name;
                    if($cutWIP < 0)
                    {
                        $cutcolor = "color:red";
                    }
                    else
                    {
                        $cutcolor = "";
                    }
                    if(($row->cut_qty - $row->prod_qty) < 0)
                    {
                        $sewcolor = "color:red";
                    }
                    else
                    {
                        $sewcolor = "";
                    }
                    if(($row->prod_qty - $packingData[0]->packing_qty) < 0)
                    {
                        $packcolor = "color:red";
                    }
                    else
                    {
                        $packcolor = "";
                    }
                    
                    if(($cutWIP+($row->cut_qty - $row->prod_qty)+($row->prod_qty - $packingData[0]->packing_qty)) < 0)
                    {
                        $totalcolor = "color:red";
                    }
                    else
                    {
                        $totalcolor = "";
                    }
                    @endphp
                     <tr>
                        <td style="text-align:center; white-space:nowrap"> {{ $srno++  }} </td>
                        <td style="text-align:center; white-space:nowrap"> {{ $row->brand_name  }} </td>
                        <td style="text-align:center; white-space:nowrap">{{ $row->order_type  }}</td>
                        <td style="text-align:right;"> {{ money_format('%!.0n',$row->order_qty) }} </td>
                        <td style="text-align:right;"> {{  number_format($sam, 2) }}  </td>
                        <td style="text-align:right;" > {{ money_format('%!.0n',($orderMin)) }} </td> 
                        <td style="text-align:right;"> {{  number_format(((($sam) * ($row->order_qty))/100000),2) }} </td> 
                        <td style="text-align:right;"> {{ money_format('%!.0n',$cutpanel) }} </td> 
                        <td style="text-align:right;"> {{ money_format('%!.0n',$row->cut_qty) }} </td> 
                        <td style="text-align:right;"> {{ money_format('%!.0n',$row->prod_qty) }} </td> 
                        <td style="text-align:right;">{{ money_format('%!.0n',$packingData[0]->packing_qty) }}</td> 
                        <td style="text-align:right;"> {{money_format('%!.0n',$FGStock)}} </td>  
                        <td style="text-align:right;"> {{ money_format('%!.0n',$row->shipped_qty) }} </td> 
                        <td style="text-align:right;"> {{ number_format((($row->order_qty-$row->prod_qty)*($sam/100000)),2) }}  </td>
                        <td style="text-align:right;"> {{ number_format($b2s,2) }} </td> 
                        <td style="text-align:right;">{{ money_format('%!.0n', $cmohp2) }}</td>
                        <td style="text-align:right;"> {{number_format(($cmohp2/100000),2) }}</td>
                        <td style="text-align:right;"> {{number_format($cmohpMin,2) }} </td> 
                        <td style="text-align:right;"> {{$row->ptm_name}}</td>
                        <td style="text-align:right;{{$cutcolor}}"> {{ money_format('%!.0n',($cutWIP)) }} </td>
                        <td style="text-align:right;{{$sewcolor}}"> {{money_format('%!.0n',($row->cut_qty - $row->prod_qty))}} </td> 
                        <td style="text-align:right;{{$packcolor}}"> {{money_format('%!.0n',($row->prod_qty - $packingData[0]->packing_qty))}}</td>  
                        <td style="text-align:right;{{$totalcolor}}"> {{money_format('%!.0n',($cutWIP+($row->cut_qty - $row->prod_qty)+($row->prod_qty - $packingData[0]->packing_qty)))}}</td>
                        <td style="text-align:right;"> {{ number_format($livec2s*100,2) }} </td> 
                        <td style="text-align:right;">  {{ number_format($orderQtyCompletion*100,2) }} </td>  
                     </tr>
                     @php  
                        $total_order_qty = $total_order_qty + $row->order_qty;
                        $total_order_min = $total_order_min + (($sam) * ($row->order_qty)); 
                        $total_cut_qty = $total_cut_qty + $row->cut_qty;
                        $total_prod_qty = $total_prod_qty + $row->prod_qty;
                        $total_packed_qty = $total_packed_qty + ($packingData[0]->packing_qty);
                        $total_fg_stock = $total_fg_stock + ($FGStock);
                        $total_ship_qty = $total_ship_qty + $row->shipped_qty;
                        $total_b2p = $total_b2p + (($row->order_qty-$row->prod_qty)*($sam/100000));
                        $total_b2s = $total_b2s + (($row->order_qty-$row->shipped_qty)*($sam/100000));
                        $total_chmoh = $total_chmoh + $cmohp2;
                        $total_inlakh_chmoh = $total_inlakh_chmoh + ($cmohp2/100000);
                        $total_cut_WIP = $total_cut_WIP + $cutWIP;
                        $total_sew_WIP = $total_sew_WIP + ($row->cut_qty - $row->prod_qty);
                        $total_packing_WIP = $total_packing_WIP +($row->prod_qty - $packingData[0]->packing_qty);
                        $total_WIP = $total_WIP + ($cutWIP+($row->cut_qty - $row->prod_qty)+($row->prod_qty - $packingData[0]->packing_qty));
                        $total_cut_panel = $total_cut_panel + $cutpanel;
                        $totaloverCmohpMin = $totaloverCmohpMin + $totalcmohpMin;
                        $totalCrDays = $totalCrDays + $totalb2s;
                        $total_cost_value =  0; 
                        $production_value =  0;  
                        $order_rate =  0;   
                        $other_value = 0;    
                        $order_qty = 0;          
                        $buyerSAM = 0;     
                        $orMin = 0;
                        $profit_value =  0;
                        $cmohp1 = 0;
                        
                      @endphp
                     @endforeach
                  </tbody>
                     <tfoot>
                     <tr>
                         <th colspan="2"></th>
                         <th class="text-right"><b>Grand Total : </b></th>
                         <th class="text-right">{{money_format('%!.0n',($total_order_qty))}}</th>
                         <th class="text-right">{{number_format($total_order_min/$total_order_qty, 2)}}</th>
                         <th class="text-right">{{money_format('%!.0n',($total_order_min))}}</th>
                         <th class="text-right">{{number_format($total_order_min/100000, 2)}}</th>
                         <th class="text-right">{{money_format('%!.0n',($total_cut_panel))}}</th>
                         <th class="text-right">{{money_format('%!.0n',($total_cut_qty))}}</th>
                         <th class="text-right">{{money_format('%!.0n',($total_prod_qty))}}</th>
                         <th class="text-right">{{money_format('%!.0n',($total_packed_qty))}}</th>
                         <th class="text-right">{{money_format('%!.0n',($total_fg_stock))}}</th>
                         <th class="text-right">{{money_format('%!.0n',($total_ship_qty))}}</th>
                         <th class="text-right">{{money_format('%!.0n',($total_b2p))}}</th>
                         <th class="text-right">{{money_format('%!.0n',($total_b2s))}}</th>
                         <th class="text-right">{{money_format('%!.0n',($total_chmoh))}}</th>
                         <th class="text-right">{{money_format('%!.0n',($total_inlakh_chmoh))}}</th>
                         <th class="text-right">{{number_format($totaloverCmohpMin/($total_order_min/100000),2)}}</th>
                         <th class="text-right">{{number_format($totalCrDays/$total_b2s,2)}}</th>
                         <th class="text-right">{{money_format('%!.0n',($total_cut_WIP))}}</th>
                         <th class="text-right">{{money_format('%!.0n',($total_sew_WIP))}}</th>
                         <th class="text-right">{{money_format('%!.0n',($total_packing_WIP))}}</th>
                         <th class="text-right">{{money_format('%!.0n',($total_WIP))}}</th>
                         
                         @php
                            if($total_ship_qty > 0 && $total_cut_qty > 0)
                            {
                                $totalLive2s = ($total_ship_qty/$total_cut_qty)*100;
                            }
                            else
                            {
                                $totalLive2s = 0;
                            }
                            if($total_ship_qty > 0 && $total_order_qty> 0 )
                            {
                                $totalCompltion = ($total_ship_qty/$total_order_qty)*100;
                            }
                            else
                            {
                                 $totalCompltion = 0;
                            }
                         @endphp
                         <th class="text-right">{{number_format($totalLive2s,2)}}</th>
                         <th class="text-right">{{number_format($totalCompltion,2)}}</th>
                     </tr>
                     </tfoot>
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
 
 
      $(document).ready( function () {
         var table = $('#liveTbl').DataTable( {
            lengthChange: true,
            dom: 'lBfrtip',
            title: 'Live Running Order Status Report',
            // buttons: [ 'pageLength', 'copy', 'excel', 'print', 'pdf', 'colvis' ],
            buttons: [{ extend: 'excel', 
                header: true,
                footer: true,
                exportOptions: {
                    columns: ':visible'
                     }
             },
            'pageLength', 'copy', 'print', 'pdf', 'colvis',
             ],
            "bInfo": false, //Dont display info e.g. "Showing 1 to 4 of 4 entries"
            "paging": false,//Dont want paging                
            "bPaginate": false,//Dont want paging 
             order: [[22, 'desc']],
             order: [[0, 'asc']],
        });
       });
 </script>
@endsection