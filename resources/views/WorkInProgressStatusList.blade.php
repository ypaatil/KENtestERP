<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js?v=1"></script>

<!-- Include DataTables plugin -->
<script src="https://cdn.datatables.net/1.11.6/js/jquery.dataTables.min.js"></script>

<!-- Apply CSS and jQuery -->
<style>
    /* Define your CSS classes here */
    .text-right {
        text-align: right;
    }
</style>
     @php
         $no = 1;
          
    
         $JobWorkers=DB::select('SELECT DISTINCT COALESCE(vwom.vendorId, pim.vendorId) AS vendorId, ledger_master.ac_name
                        FROM buyer_purchse_order_master bpm
                        LEFT JOIN vendor_work_order_master vwom ON bpm.tr_code = vwom.sales_order_no
                        LEFT JOIN packing_inhouse_master pim ON bpm.tr_code = pim.sales_order_no
                        LEFT JOIN ledger_master ON (
                            ledger_master.ac_code = vwom.vendorId 
                            OR (vwom.vendorId IS NULL AND ledger_master.ac_code = pim.vendorId)
                        )
                        WHERE (
                            (bpm.order_received_date <= "'.$WorkProgressDate.'" AND bpm.job_status_id = 1 AND bpm.og_id != 4)
                            OR
                            (
                                bpm.order_close_date = "'.$WorkProgressDate.'"
                                AND bpm.og_id != 4
                                AND bpm.order_type IN (1, 3)
                                AND bpm.delflag = 0
                                AND bpm.job_status_id IN (1, 2, 4, 5)
                            )
                        )
                        AND (vwom.sales_order_no IS NOT NULL OR pim.sales_order_no IS NOT NULL)');
    
        $totalWorkOrderQty = 0;
        $totalOrderQty = 0;
        $totalWIPQty = 0;
     @endphp   
  <table id="example" class="display" cellspacing="0" width="100%">
		<thead>
			<tr>
                <th>Sr. No</th>
                <th>Vendor Name</th>
                <th>Work Order Qty</th>
                <th>Garment  Inward </th>
                <th>WIP</th>
             </tr>
             <tr>
                <th></th>
                <th></th>
                <th>PCS</th>
                <th>PCS</th>
                <th>PCS</th>
             </tr>
		</thead>
		<tbody>
            @foreach($JobWorkers as $row)
            @php 
                $combinedData = DB::SELECT("SELECT  
                     sum((SELECT IFNULL(SUM(final_bom_qty),0) FROM vendor_work_order_master WHERE vendor_work_order_master.sales_order_no = bpm.tr_code AND vw_date <= '".$WorkProgressDate."' AND vendorId=".$row->vendorId.")) AS work_order_qty,
                     sum((SELECT IFNULL(SUM(total_qty),0) FROM packing_inhouse_master WHERE packing_inhouse_master.sales_order_no = bpm.tr_code AND pki_date <= '".$WorkProgressDate."' AND vendorId=".$row->vendorId.")) AS total_qty,
                     sum((SELECT IFNULL(SUM(size_qty_total),0) FROM qcstitching_inhouse_reject_detail WHERE qcstitching_inhouse_reject_detail.sales_order_no = bpm.tr_code AND qcsti_date <=  '".$WorkProgressDate."' AND vendorId=".$row->vendorId.")) AS total_reject_qty,
                     sum((SELECT IFNULL(SUM(total_qty),0) FROM WIP_Adjustable_Qty WHERE WIP_Adjustable_Qty.sales_order_no = bpm.tr_code AND vendorId=".$row->vendorId.")) AS total_adjustable_qty
                    
                    FROM 
                        buyer_purchse_order_master as bpm 
                    WHERE 
                            (
                                (bpm.order_received_date <= '".$WorkProgressDate."' AND bpm.job_status_id = 1 AND bpm.og_id != 4)
                                OR
                                (
                                    bpm.order_close_date = '".$WorkProgressDate."'
                                    AND bpm.og_id != 4
                                    AND bpm.order_type IN (1, 3)
                                    AND bpm.delflag = 0
                                    AND bpm.job_status_id IN (1, 2, 4, 5)
                                )
                    )");
                 $pack_order_qty = isset($combinedData[0]->total_qty) ? $combinedData[0]->total_qty : 0; 
                 $work_order_qty = isset($combinedData[0]->work_order_qty) ? $combinedData[0]->work_order_qty : 0; 
                 $total_reject_qty = isset($combinedData[0]->total_reject_qty) ? $combinedData[0]->total_reject_qty : 0; 
                 $total_adjustable_qty = isset($combinedData[0]->total_adjustable_qty) ? $combinedData[0]->total_adjustable_qty : 0; 
                 
            @endphp
		    <tr>
                <td class="text-center"> {{$no++}}</td>
                <td> {{$row->ac_name}}</td>
                <td class="text-right"> {{money_format("%!.0n",($work_order_qty - $total_adjustable_qty))}}</td>
                <td class="text-right"> {{money_format("%!.0n",($pack_order_qty))}}</td> 
                <td class="text-right"><a href="{{ url('WIPDetailReport', [0]) }}" target="_blank"> {{money_format("%!.0n",($work_order_qty - $pack_order_qty - $total_adjustable_qty + $total_reject_qty))}} </a></td>
            </tr>
            @php
                $totalWorkOrderQty += $work_order_qty - $total_adjustable_qty;
                $totalOrderQty += $pack_order_qty;
                $totalWIPQty += ($work_order_qty - $pack_order_qty - $total_adjustable_qty + $total_reject_qty);
                
                $pack_order_qty = 0;
                $work_order_qty = 0;
            @endphp
            @endforeach
            
		</tbody>
		
		<tfoot>
			<tr>
				<th></th>
				<th>Total :</th>
				<th class="text-right">{{money_format("%!.0n",($totalWorkOrderQty))}}</th>
				<th class="text-right">{{money_format("%!.0n",($totalOrderQty))}}</th>
				<th class="text-right">{{money_format("%!.0n",($totalWIPQty))}}</th> 
			</tr>
		</tfoot>
	</table> 
	
<script>
    $(document).ready(function() {
        // Initialize DataTables
        $('#example').DataTable({
            "order": [[4, "desc"]], // Set initial sorting
            "iDisplayStart ": 14, // Starting row index for display
            "iDisplayLength": 14 // Number of rows to display
        });
    });
</script>