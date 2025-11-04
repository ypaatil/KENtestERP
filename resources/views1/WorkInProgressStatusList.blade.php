<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<link rel="shortcut icon" type="image/png" href="/media/images/favicon.png">
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="https://www.datatables.net/rss.xml">
	<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.jqueryui.min.css">
	<style type="text/css" class="init">
    .text-center
	{
	    text-align:center;
	}
	th
	{
        background: #152d9f!important;
        color: #fff!important;
	}
	</style>
	</script>
	<script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.12.3.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.12/js/dataTables.jqueryui.min.js">
	</script>
	<script type="text/javascript" class="init">
	
        $(document).ready(function() 
        {
           
            setTimeout(function() 
            {
                $("#wip").attr('class','ui-state-default sorting_desc');
                $("#wip").attr('aria-sort','descending');
                $("#wip").trigger('click');
                $("#wip").trigger('click');
            }, 1000);
            $('#example').DataTable({
                "bProcessing": true,
                "sAutoWidth": false,
                "bDestroy":true,
                "sPaginationType": "bootstrap", // full_numbers
                "iDisplayStart ": 10,
                "iDisplayLength": 10,
                "bPaginate": false, //hide pagination
                "bFilter": false, //hide Search bar
                "bInfo": false, // hide showing entries
            });
        });
	</script>
</head>
  
  <body class="wide comments example dt-example-jqueryui">
 @php
     $no = 1;
     // DB::enableQueryLog();
     $JobWorkers=DB::select('select distinct(vendorId) , Ac_name  from vendor_work_order_master left join ledger_master on ledger_master.Ac_code=vendor_work_order_master.vendorId');
     // $query = DB::getQueryLog();
     //    $query = end($query);
     //      dd($query);
     $total_WQty=0; $total_cut_panel_issue=0; $total_packing_qty=0; $total_WIP=0; $total_issue_meter=0;
 @endphp   
  <table id="example" class="display" cellspacing="0" width="100%">
		<thead>
			<tr>
                <th>SrNo</th>
                <th>Job Worker</th>
                <th colspan="2">Work Order Qty/ Cut Panel Issue</th>
                <th>Garment  Inward </th>
                <th>WIP</th>
             </tr>
             <tr>
                <th></th> 
                <th>Fabric</th>
                <th>CUT Panel </th>
                <th>Garments </th>
                <th>PCS </th>
                <th></th>
             </tr>
             <tr>
                <th> </th>
                <th>PCS</th>
                <th>PCS</th>
                <th>PCS</th>
                <th></th>
                <th id="wip"></th>
             </tr>
		</thead>
		<tbody>
                 @foreach($JobWorkers as $rowJW)
                 @php
                
                 $orders=DB::select("select ifnull(sum(vendor_work_order_detail.size_qty_total),0) as wqty,
                 (select ifnull(sum(meter),0) from fabric_outward_details
                 inner join vendor_purchase_order_master on  vendor_purchase_order_master.vpo_code=fabric_outward_details.vpo_code
                 where fabric_outward_details.vendorId='".$rowJW->vendorId."' and vendor_purchase_order_master.endflag=1)   as issue_meter ,
                 (select ifnull(sum(size_qty_total),0) from cut_panel_issue_detail
                 inner join vendor_work_order_master on vendor_work_order_master.vw_code=cut_panel_issue_detail.vw_code
                 where cut_panel_issue_detail.vendorId='".$rowJW->vendorId."' and vendor_work_order_master.endflag=1)   as cut_panel_issue_qty ,
                 (select ifnull(sum(size_qty_total),0) from packing_inhouse_detail
                 inner join vendor_purchase_order_master on  vendor_purchase_order_master.vpo_code=packing_inhouse_detail.vpo_code
                 where packing_inhouse_detail.vendorId='".$rowJW->vendorId."' and vendor_purchase_order_master.endflag=1)  as packing_grn_qty ,
                 (ifnull(sum(vendor_work_order_detail.size_qty_total),0) - (select ifnull(sum(packing_inhouse_detail.size_qty_total),0) from packing_inhouse_detail
                 inner join vendor_purchase_order_master on vendor_purchase_order_master.vpo_code=packing_inhouse_detail.vpo_code
                 where packing_inhouse_detail.vendorId='".$rowJW->vendorId."' and vendor_purchase_order_master.endflag=1) ) as WIP
                 from vendor_work_order_detail
                 inner join vendor_work_order_master on vendor_work_order_master.vw_code=vendor_work_order_detail.vw_code
                 where vendor_work_order_master.vendorId='".$rowJW->vendorId."' and vendor_work_order_master.endflag=1
                 order by WIP");
                
                 @endphp
                 @foreach($orders as $ow)
                 @php 
                 $total_WQty=$total_WQty + $ow->wqty;
                 $total_cut_panel_issue=$total_cut_panel_issue + $ow->cut_panel_issue_qty;
                 $total_packing_qty=$total_packing_qty + $ow->packing_grn_qty;
                 $total_WIP=$total_WIP + $ow->WIP;
                 $total_issue_meter=$total_issue_meter+$ow->issue_meter;
                 if((($ow->wqty) + ($ow->cut_panel_issue_qty)  + ($ow->packing_grn_qty)  + ($ow->WIP))!=0){
                 @endphp
		    <tr>
                <td class="text-center"> {{$no++}}</td>
                <td> {{$rowJW->Ac_name}}</td>
                <td class="text-center"> {{number_format($ow->wqty)}}</td>
                <td class="text-center"> {{number_format($ow->cut_panel_issue_qty)}}</td>
                <td class="text-center"> {{number_format($ow->packing_grn_qty)}}</td>
                <td class="text-center"><a href="{{ url('WIPDetailReport', [$rowJW->vendorId]) }}" target="_blank"> {{number_format($ow->WIP)}} </a></td>
            </tr>
            @php 
            }
            @endphp
            @endforeach
            @endforeach
            
		</tbody>
		
		<tfoot>
			<tr>
				<th></th>
				<th>Total :</th>
				<th>{{number_format($total_WQty)}}</th>
				<th>{{number_format($total_cut_panel_issue)}}</th>
				<th>{{number_format($total_packing_qty)}}</th>
				<th>{{number_format($total_WIP)}}</th>
			</tr>
		</tfoot>
	</table>
</body>
</html>