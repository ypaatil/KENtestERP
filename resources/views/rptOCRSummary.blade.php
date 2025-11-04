@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp                
<!-- end page title -->
 
<style>
    /*.tr{*/
    /*    background: #423434;*/
    /*    color: #fff;*/
    /*}*/
    .text-right{
        text-align:right;
    }
</style>
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<!-- Buttons CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- Buttons JS -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>

<!-- Dependencies for Excel/PDF export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<div class="row">
   <div class="col-12 text-center"> 
          <h3><b>OCR Summary Report</b></h3> 
   </div>
</div>                         
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <div class="table-responsive">
               <table id="ocrTbl" class="DataTable table table-bordered nowrap w-100">
                  <thead>
                     <tr nowrap class="tr">
                        <th nowrap>Sr No.</th>
                        <th nowrap>Customer</th>
                        <th nowrap>KDPL #</th>
                        <th nowrap>Style</th>
                        <th nowrap>FOB Value</th>
                        <th nowrap>SAM</th>
                        <th nowrap>Order Qty</th>
                        <th nowrap>Cut Qty</th>
                        <th nowrap>Ship Qty</th>
                        <th nowrap>Cut to ship Ratio in % </th>
                        <th nowrap>Order to ship Ratio in %</th>
                        <th nowrap>Fabric Allocated Stock Qty</th>
                        <th nowrap>Fabric Allocated Stock Value</th>
                        <th nowrap>Fabric Issue Stock Qty</th>
                        <th nowrap>Fabric Issue Stock Value</th>
                        <th nowrap>Fabric Avaliable Stock Qty</th>
                        <th nowrap>Fabric Avaliable Stock Value</th>
                        <th nowrap>Trims Allocated Stock Qty</th>
                        <th nowrap>Trims Allocated Stock Value</th>
                        <th nowrap>Trims Issue Stock Qty</th>
                        <th nowrap>Trims Issue Stock Value</th>
                        <th nowrap>Trims Avaliable Stock Qty</th>
                        <th nowrap>Trims Avaliable Stock Value</th>
                        <th nowrap>Washing cost</th>
                        <th nowrap>Emb cost</th>
                        <th nowrap>Printing cost</th>
                        <th nowrap>Testing Cost </th>
                        <th nowrap>Transport Cost</th>
                        <th nowrap>Commission cost</th>
                        <th nowrap>IXD cost</th>
                        <th nowrap>Discount if any</th>
                        <th nowrap>Sales value</th>
                        <th nowrap> Leftover Fabric value (%) </th>
                        <th nowrap> Leftover Trims value (%) </th>
                        <th nowrap> Left Pcs value (%) </th>
                        <th nowrap> Rejection Pcs Value ( % of FOB) </th>
                        <th nowrap> TOTAL CMPOH including Finance cost  </th>
                        <th nowrap> Finance cost as per costing  </th> 
                        <th nowrap> TOTAL CMPOH EXCLUDING Finance cost  </th> 
                        <th nowrap> CMPOH PER  PC</th> 
                        <th nowrap> CMPOH PER MINUTE </th> 
                        <th nowrap> Loose pcs </th> 
                        <th nowrap> Rejection Pcs  </th> 
                        <th nowrap> Rejection %</th>
                     </tr>
                  </thead>
                  <tbody>
                      @php
                        $srno = 1; 
                      @endphp
                      @foreach($buyerPurchaseOrderList as $row)
                      @php
                        
                        $cut_to_ship_qty = 0;
                        $order_to_ship_qty = 0;
                        $fabricOutwardStock = 0;
                        $remainStock = 0;
                        $avilable_stock = 0;
                        $fabricOutwardStock = 0;
                        $fabricOutwardValue = 0;
                        
                               
                        $trimsOutwardStock = 0;
                        $trimsRemainStock = 0;
                        $trims_avilable_stock = 0;
                        $trimsAllocated_value = 0;
                        $trimsOutwardValue =  0;
                        $trimsAllocated_qty = 0;
                        $trimsOutward_qty = 0;
                        $each_qty = 0;
                        $allocated_qty = 0;
                        
                        if($row->ship_qty > 0 && $row->cut_qty > 0)
                        {
                           $cut_to_ship_qty = ($row->ship_qty/$row->cut_qty);
                        } 
                        
                        if($row->ship_qty > 0 && $row->total_order_qty > 0)
                        {
                           $order_to_ship_qty = ($row->ship_qty/$row->total_order_qty);
                        } 
                        
                        $specificSO = $row->tr_code;
                        $allocated_qty = 0;
                        $eachAvaliableQty = 0;
                        $fabricAllocatedValue = 0;
                        
                        if(isset($GroupedData[$specificSO]))
                        { 
                            foreach($GroupedData[$specificSO] as $asso)
                            {
                            
                              $inwardData = DB::table('purchaseorder_detail')->select('item_rate')->where('item_code',"=",$asso->item_code)->where('pur_code',"=",$asso->po_code)->first(); 
                           
                              if($asso->tr_type==1)
                              {
                                    $allocated_qty += $asso->qty;
                              }
                              
                              if($asso->tr_type==2 && $asso->tr_type == NULL)
                              {
                                    $eachAvaliableQty += $asso->qty;
                              } 
                              
                              $inward_rate = isset($inwardData->item_rate) ? $inwardData->item_rate : 0;
                              $fabricAllocated_qty = $allocated_qty - $eachAvaliableQty;
                              $fabricAllocatedValue += $fabricAllocated_qty * $inward_rate;
                              
                            }
                        }
                        if(isset($OutwardGroupedData[$specificSO]))
                        { 
                            foreach($OutwardGroupedData[$specificSO] as $out)
                            {  
                              $fabricOutwardStock += $out->meter;
                              $fabricOutwardValue += $out->fabric_outward_value;
                              
                            }
                        } 
                       
                        $leftover_fabric_value = 0;  
                        $leftover_trims_value = 0;
                        $left_pcs_value = 0;
                        $rejection_pcs_value = 0;
                        $rejectionpcsvalue = 70;
                        $leftpcsvalue = 70;
                        $leftFabricValue = 50;
                        $lefttrimsvalue = 50;
                    
                        if(isset($KDPLGroupedData[$specificSO]))
                        { 
                            foreach($KDPLGroupedData[$specificSO] as $kdpl)
                            {
                            
                                $leftFabricValue = $kdpl->leftover_fabric_value;
                                if($leftFabricValue > 0) 
                                {
                                    $leftover_fabric_value = $leftFabricValue/100;
                                }
                                else
                                {
                                    $leftover_fabric_value = 0;  
                                }
                                
                                $lefttrimsvalue = $kdpl->leftover_trims_value; 
                                if($lefttrimsvalue > 0) 
                                {
                                    $leftover_trims_value = $lefttrimsvalue/100;
                                }
                                else
                                {
                                    $leftover_trims_value = 0;  
                                }
                                
                                 
                                $leftpcsvalue = $kdpl->left_pcs_value;
                                if($leftpcsvalue > 0) 
                                {
                                    $left_pcs_value = $leftpcsvalue/100;
                                }
                                else
                                {
                                    $left_pcs_value = 0;  
                                }
                                 
                                $rejectionpcsvalue = $kdpl->rejection_pcs_value;
                                if($rejectionpcsvalue > 0) 
                                {
                                    $rejection_pcs_value = $rejectionpcsvalue/100;
                                }
                                else
                                {
                                    $rejection_pcs_value = 0;  
                                }
                            }
                        }
                        
                        $left_over_fabric_value_per = ($fabricAllocatedValue - $fabricOutwardValue) * ($leftFabricValue/100);
                        $left_over_trims_value_per = (($trimsAllocated_value-$trimsOutwardValue) * ($leftover_trims_value/100));
                     
                        $left_pcs_value = 0; 
                        $pass_qty = 0;
                        $invoice_qty = 0;
                        if(isset($invoiceGroupedData[$specificSO]))
                        { 
                            foreach($invoiceGroupedData[$specificSO] as $invoice)
                            {
                                $invoice_qty += $invoice->size_qty_total;
                            }
                        }
                        
                        if(isset($PassGroupedData[$specificSO]))
                        { 
                            foreach($PassGroupedData[$specificSO] as $pass)
                            {
                                $pass_qty += $pass->size_qty_total;
                            }
                        }
                        
                        $left_pcs_value = ((($pass_qty - $invoice_qty)  * $row->order_rate) * ($leftpcsvalue/100))."(".$leftpcsvalue.")";
                        
                        $left_pcs_value1 = ((($pass_qty - $invoice_qty)  * $row->order_rate) * ($leftpcsvalue/100));
                        
                        $cmpoh_per_pc = 0;
                        $cmpoh_per_minutes = 0;
                      
                        $TOTALCMPOHIncludingFinanceCost = (
                                                    $row->sales_value 
                                                    - $fabricAllocatedValue 
                                                    - $trimsAllocated_value 
                                                    - $row->agent_commision_value 
                                                    - $row->ixd_value 
                                                    - $row->transport_qty 
                                                    - $row->testing_qty 
                                                    - $row->printing_value 
                                                    - $row->embroidery_value 
                                                    - $row->dbk_value
                                                ) + $left_over_fabric_value_per 
                                                  + $left_over_trims_value_per 
                                                  + $left_pcs_value1 
                                                  + $rejection_pcs_value;
                                                
                                                                
                
                        $finance_cost_per_costing = $row->finance_cost_value * $row->cut_qty;
                        $total_cmpoh_excluding_finance_cost = $TOTALCMPOHIncludingFinanceCost - $row->finance_cost_value;
                      
                        if($TOTALCMPOHIncludingFinanceCost > 0 && $row->cut_qty)
                        {
                            $cmpoh_per_pc = $TOTALCMPOHIncludingFinanceCost/$row->cut_qty; 
                        }
                        
                        if($cmpoh_per_pc > 0 && $row->sam)
                        {
                            $cmpoh_per_minutes = $cmpoh_per_pc/$row->sam;
                        }
                        
                        $reject_qty = 0;
                        $Rejection_per = 0;
                        
                        if(isset($RejectGroupedData[$specificSO]))
                        { 
                            foreach($RejectGroupedData[$specificSO] as $reject)
                            {
                                $reject_qty += $reject->size_qty_total;
                            }
                        }
                        
                        if($reject_qty > 0 && $row->cut_qty > 0)
                        { 
                            $Rejection_per = ($reject_qty/$row->cut_qty) * 100;
                        }
                        
                        $loose_pcs = $pass_qty - $invoice_qty;
                        $rejection_pcs = $reject_qty;
                        $rejection_per = $Rejection_per;
                        
                        if (isset($TrimGroupedData[$specificSO])) 
                        {
                            $rowDetail = $TrimGroupedData[$specificSO];
                        
                            $other_allocated_qty = 0;
                            $outward_qty = 0;
                        
                            $trimsAllocated_qty   = $rowDetail['total_qty'];
                            $trimsAllocated_value = $rowDetail['total_value'];
                            $trimsOutwardValue    = 0;
                        
                            $avaliable_qty = 0;
                        }

                        if (isset($TrimOutwardGroupedData[$specificSO])) 
                        {
                            $rowDetail = $TrimOutwardGroupedData[$specificSO];
                         
                            $trimsOutward_qty  = $rowDetail['total_qty'];
                            $trimsOutwardValue = $rowDetail['total_value']; 
                        }
                        

                      @endphp
                      <tr>
                        <td>{{$srno++}}</td>
                        <td>{{$row->customerName}}</td>
                        <td>{{$row->tr_code}}</td>
                        <td>{{$row->mainstyle_name}}</td>
                        <td class="text-right">{{ money_format('%!i',round($row->order_rate,2))}}</td>
                        <td class="text-right">{{ money_format('%!i',round($row->sam,2))}}</td>
                        <td class="text-right">{{ money_format('%!i',round($row->total_order_qty,2))}}</td>
                        <td class="text-right">{{ money_format('%!i',round($row->cut_qty,2))}}</td>
                        <td class="text-right">{{ money_format('%!i',round($row->ship_qty,2))}}</td>
                        <td class="text-right">{{ money_format('%!i',round($cut_to_ship_qty * 100,2))}}</td>
                        <td class="text-right">{{ money_format('%!i',round($order_to_ship_qty * 100,2))}}</td>
                        <td class="text-right">{{ money_format('%!i',round($fabricAllocated_qty,2))}}</td>
                        <td class="text-right">{{ money_format('%!i',round($fabricAllocatedValue,2))}}</td>
                        <td class="text-right">{{ money_format('%!i',round($fabricOutwardStock,2))}}</td>
                        <td class="text-right">{{ money_format('%!i',round($fabricOutwardValue,2))}}</td>
                        <td class="text-right">{{ money_format('%!i',round($fabricAllocated_qty-$fabricOutwardStock,2))}}</td>
                        <td class="text-right">{{ money_format('%!i',round($fabricAllocatedValue-$fabricOutwardValue,2))}}</td>
                        <td class="text-right">{{ money_format('%!i',round($trimsAllocated_qty,2))}}</td>
                        <td class="text-right">{{ money_format('%!i',round($trimsAllocated_value,2))}}</td>
                        <td class="text-right">{{ money_format('%!i',round($trimsOutward_qty,2))}}</td>
                        <td class="text-right">{{ money_format('%!i',round($trimsOutwardValue,2))}}</td>
                        <td class="text-right">{{ money_format('%!i',round($trimsAllocated_qty-$trimsOutward_qty,2))}}</td>
                        <td class="text-right">{{ money_format('%!i',round($trimsAllocated_value-$trimsOutwardValue,2))}}</td>
                        <td class="text-right">{{ money_format('%!i',round($row->cut_qty * ($row->dbk_value + $row->washing),2))}}</td>
                        <td class="text-right">{{ money_format('%!i',round($row->cut_qty * $row->embroidery_value,2))}}</td>
                        <td class="text-right">{{ money_format('%!i',round($row->cut_qty * $row->printing_value,2))}}</td>
                        <td class="text-right">{{ money_format('%!i',round($row->testing_qty,2))}}</td>
                        <td class="text-right">{{ money_format('%!i',round($row->transport_qty,2))}}</td>
                        <td class="text-right">{{ money_format('%!i',round($row->sales_value * $row->agent_commision_value/100,2))}}</td>
                        <td class="text-right">{{ money_format('%!i', round($row->sales_value * $row->ixd_value / 100, 2)) }}</td>
                        <td class="text-right">0</td>
                        <td class="text-right">{{ money_format('%!i',round($row->sales_value,2))}}</td>
                        <td class="text-right">{{ money_format('%!i',round($left_over_fabric_value_per,2))}}</td>
                        <td class="text-right">{{ money_format('%!i',round($left_over_trims_value_per,2))}}</td>
                        <td class="text-right">{{ money_format('%!i',round($left_pcs_value,2))}}</td>
                        <td class="text-right">{{ money_format('%!i',round($leftpcsvalue,2))}}</td>
                        <td class="text-right">{{ money_format('%!i',round($TOTALCMPOHIncludingFinanceCost,2))}}</td>
                        <td class="text-right">{{ money_format('%!i',round($finance_cost_per_costing,2))}}</td>
                        <td class="text-right">{{ money_format('%!i',round($total_cmpoh_excluding_finance_cost,2))}}</td>
                        <td class="text-right">{{ money_format('%!i',round($cmpoh_per_pc,2))}}</td>
                        <td class="text-right">{{ money_format('%!i',round($cmpoh_per_minutes,2))}}</td>
                        <td class="text-right">{{ money_format('%!i',round($loose_pcs,2))}}</td>
                        <td class="text-right">{{ money_format('%!i',round($rejection_pcs,2))}}</td>
                        <td class="text-right">{{ money_format('%!i',round($rejection_per,2))}}</td>
                      </tr>
                      @endforeach
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div> 
</div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
 <script>
    $(document).ready(function () {
        $('#ocrTbl').DataTable({
            responsive: true,
            pageLength: 50,
            ordering: true,
            searching: true,
            dom: 'Bfrtip',   // Buttons + filter + table + pagination
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    });
 </script>
@endsection