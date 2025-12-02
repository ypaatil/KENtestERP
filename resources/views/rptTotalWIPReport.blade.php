@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp
<!-- end page title -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css"> 
<style>
    .hide{
        display:none;
    }
    
    .text-right{
        text-align:right;
    }
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Total WIP Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Total WIP Report</li>
            </ol>
         </div>
      </div>
   </div>
</div> 
<div class="row">
    <div class="col-md-2">
       <div class="card mini-stats-wid" style="background-color:#152d9f;" >
          <div class="card-body">
             <div class="d-flex">
                <div class="flex-grow-1">
                   <p class="  fw-medium" style="color:#fff;">Total WIP(In Lakh)</p>
                   <h4 class="mb-0" style="color:#fff;" id="head_total_WIP">0</h4>
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
    <div class="col-md-2">
       <div class="card mini-stats-wid" style="background-color:#152d9f;" >
          <div class="card-body">
             <div class="d-flex">
                <div class="flex-grow-1">
                   <p class="  fw-medium" style="color:#fff;">Total Value(In Lakh)</p>
                   <h4 class="mb-0" style="color:#fff;" id="head_total_WIP_value">0</h4>
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
    <div class="col-md-12">
       <div class="card mini-stats-wid">
          <div class="card-body">
              <form action="/rptTotalWIPReport" method="GET">
                  <div class="row">  
                      <div class="col-md-3 hide">
                         <div class="mb-3">
                            <label for="fromDate" class="form-label">From Date</label>
                            <input type="date" class="form-control" name="fromDate" id="fromDate" value="{{$fromDate}}">
                         </div>
                      </div>
                      <div class="col-md-3">
                         <div class="mb-3">
                            <label for="toDate" class="form-label">Date</label>
                            <input type="date" class="form-control" name="toDate" id="toDate" value="{{$toDate}}">
                         </div>
                      </div>
                       <div class="col-md-3">
                         <div class="mb-3">
                            <label for="job_status_id" class="form-label">Job Status Name</label>
                            <select name="job_status_id[]" id="job_status_id" class="form-control select2" multiple>
                                <option value="">--Select--</option>
                                @foreach($jobStatusList as $row)
                                    <option value="{{$row->job_status_id}}" >{{$row->job_status_name}}</option>
                                @endforeach
                            </select>
                         </div>
                       </div> 
                       <div class="col-md-3">
                         <div class="mb-3">
                            <label for="orderTypeId" class="form-label">Order Type</label>
                            <select name="orderTypeId[]" id="orderTypeId" class="form-control select2" multiple>
                                <option value="">--Select--</option>
                                @foreach($orderTypeList as $row)
                                    <option value="{{$row->orderTypeId}}">{{$row->order_type}}</option>
                                @endforeach
                            </select>
                         </div>
                       </div> 
                       <div class="col-md-3 hide">
                         <div class="mb-3">
                            <label for="vendorId" class="form-label">Vendor</label>
                            <select name="vendorId" id="vendorId" class="form-control select2">
                                <option value="">--Select--</option>
                                @foreach($vendorList as $row)
                                    <option value="{{$row->ac_code}}"  {{ $row->ac_code == $vendorId ? 'selected="selected"' : '' }}  >{{$row->ac_name}}</option>
                                @endforeach
                            </select>
                         </div>
                       </div> 
                      <div class="col-md-6 mt-3"> 
                            <button type="submit" class="btn btn-primary">Search</button>
                            <a href="/rptTotalWIPReport" class="btn btn-warning">Clear</a>
                      </div>
                  </div>
              </form>
          </div>
       </div>
    </div>
</div>
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <!--<div class="col-md-12 hide text-center"  id="waiting"><img src="{{ URL::asset('images/loading-waiting.gif')}}" width="300" height="300"></div>-->
            <div class="table-responsive">
               
                     <table id="dt" class="table table-bordered nowrap w-100">
                         <thead>
                              <tr style="background-color:#eee;"> 
                                   <th nowrap>Sr. No.<span class="filter-icon hide">🔽</span><div class="filter-menu sr-no"></div></th>
                                    <th nowrap>Order No<span class="filter-icon ">🔽</span><div class="filter-menu order-no"></div></th>
                                    <th nowrap>PO Status<span class="filter-icon ">🔽</span><div class="filter-menu po-status"></div></th>
                                    <th nowrap>Order Type<span class="filter-icon ">🔽</span><div class="filter-menu order-type"></div></th>
                                    <th nowrap>Buyer Brand<span class="filter-icon ">🔽</span><div class="filter-menu buyer-brand"></div></th>
                                    <th nowrap>Category<span class="filter-icon ">🔽</span><div class="filter-menu category"></div></th>
                                    <th class="text-center" nowrap>Order Qty<span class="filter-icon hide">🔽</span><div class="filter-menu order-qty"></div></th>
                                    <th class="text-center" nowrap>Work Order Qty<span class="filter-icon hide">🔽</span><div class="filter-menu work-order-qty"></div></th>
                                    <th class="text-center" nowrap>Cutting Qty<span class="filter-icon hide">🔽</span><div class="filter-menu cutting-qty"></div></th>
                                    <th class="text-center" nowrap>Cutting WIP<span class="filter-icon hide">🔽</span><div class="filter-menu cutting-wip"></div></th>
                                    <th class="text-center" nowrap>Sewing Qty<span class="filter-icon hide">🔽</span><div class="filter-menu sewing-qty"></div></th>
                                    <th class="text-center" nowrap>Sewing WIP<span class="filter-icon hide">🔽</span><div class="filter-menu sewing-wip"></div></th>
                                    <th class="text-center" nowrap>Packing Qty<span class="filter-icon hide">🔽</span><div class="filter-menu packing-qty"></div></th>
                                    <th class="text-center" nowrap>Packing WIP<span class="filter-icon hide">🔽</span><div class="filter-menu packing-wip"></div></th>
                                    <th class="text-center" nowrap>Rejection Qty<span class="filter-icon hide">🔽</span><div class="filter-menu rejection-qty"></div></th>
                                    <th class="text-center" nowrap>WIP Adjust Qty<span class="filter-icon hide">🔽</span><div class="filter-menu wip-adjust-qty"></div></th>
                                    <th class="text-center" nowrap>Total WIP<span class="filter-icon hide">🔽</span><div class="filter-menu total-wip"></div></th>
                                    <th class="text-center" nowrap>Order Rate<span class="filter-icon hide">🔽</span><div class="filter-menu order-rate"></div></th>
                                    <th class="text-center" nowrap>Total WIP Value<span class="filter-icon hide">🔽</span><div class="filter-menu total-wip-value"></div></th>

                              </tr>
                         </thead>
                         <tbody>
                           
                        <?php

$head_total_WIPQty = 0;
$head_total_WIPValue = 0;
$srno = 1;

// --------------------------------------------------
// Collect all sales_order_no
// --------------------------------------------------
$salesOrders = array_map(fn($x) => $x->tr_code, $Buyer_Purchase_Order_List);
$soList = "'" . implode("','", $salesOrders) . "'";

$vendorFilter = ($vendorId > 0) ? " AND vendor_work_order_master.vendorId = $vendorId " : "";
$genericVendorFilter = ($vendorId > 0) ? " AND vendorId = $vendorId " : "";

// --------------------------------------------------
// 1. Vendor Work Order Qty
// --------------------------------------------------
$VendorDataList = DB::select("
    SELECT 
        vendor_work_order_detail.sales_order_no,
        SUM(vendor_work_order_detail.size_qty_total) AS work_order_qty
    FROM vendor_work_order_detail
    INNER JOIN vendor_work_order_master 
        ON vendor_work_order_master.vw_code = vendor_work_order_detail.vw_code
    WHERE vendor_work_order_detail.sales_order_no IN ($soList)
        AND vendor_work_order_master.vw_date <= '$toDate'
        $vendorFilter
    GROUP BY vendor_work_order_detail.sales_order_no
");

$VendorDataMap = collect($VendorDataList)->keyBy('sales_order_no');


// --------------------------------------------------
// 2. Cut Panel Qty
// --------------------------------------------------
$CutPanelList = DB::select("
    SELECT 
        cut_panel_grn_size_detail2.sales_order_no,
        SUM(cut_panel_grn_size_detail2.size_qty) AS total_qty
    FROM cut_panel_grn_size_detail2
    WHERE cut_panel_grn_size_detail2.sales_order_no IN ($soList)
        AND cut_panel_grn_size_detail2.cpg_date <= '$toDate'
        $genericVendorFilter
    GROUP BY cut_panel_grn_size_detail2.sales_order_no
");
$CutPanelMap = collect($CutPanelList)->keyBy('sales_order_no');


// --------------------------------------------------
// 3. Stitching Qty
// --------------------------------------------------
$StitchingList = DB::select("
    SELECT 
        stitching_inhouse_master.sales_order_no,
        SUM(stitching_inhouse_master.total_qty) AS stiching_qty
    FROM stitching_inhouse_master
    WHERE stitching_inhouse_master.sales_order_no IN ($soList)
        AND stitching_inhouse_master.sti_date <= '$toDate'
        $genericVendorFilter
    GROUP BY stitching_inhouse_master.sales_order_no
");
$StitchMap = collect($StitchingList)->keyBy('sales_order_no');


// --------------------------------------------------
// 4. Packing Qty
// --------------------------------------------------
$PackingList = DB::select("
    SELECT 
        packing_inhouse_master.sales_order_no,
        SUM(packing_inhouse_master.total_qty) AS pack_qty
    FROM packing_inhouse_master
    WHERE packing_inhouse_master.sales_order_no IN ($soList)
        AND packing_inhouse_master.pki_date <= '$toDate'
        AND packing_inhouse_master.packing_type_id = 4
        $genericVendorFilter
    GROUP BY packing_inhouse_master.sales_order_no
");
$PackingMap = collect($PackingList)->keyBy('sales_order_no');


// --------------------------------------------------
// 5. Sales Costing
// --------------------------------------------------
$CostingList = DB::select("
    SELECT 
        sales_order_costing_master.sales_order_no,
        sales_order_costing_master.fabric_value,
        sales_order_costing_master.sewing_trims_value,
        sales_order_costing_master.packing_trims_value
    FROM sales_order_costing_master
    WHERE sales_order_costing_master.sales_order_no IN ($soList)
");
$CostingMap = collect($CostingList)->keyBy('sales_order_no');


// --------------------------------------------------
// 6. WIP Adjustable
// --------------------------------------------------
$WIPAdjustList = DB::select("
    SELECT 
        WIP_Adjustable_Qty_detail.sales_order_no,
        SUM(WIP_Adjustable_Qty_detail.size_qty_total) AS wip_adj
    FROM WIP_Adjustable_Qty_detail
    WHERE WIP_Adjustable_Qty_detail.sales_order_no IN ($soList)
    GROUP BY WIP_Adjustable_Qty_detail.sales_order_no
");
$WIPAdjustMap = collect($WIPAdjustList)->keyBy('sales_order_no');


// --------------------------------------------------
// 7. Rejection Qty
// --------------------------------------------------
$RejectionList = DB::select("
    SELECT 
        qcstitching_inhouse_reject_detail.sales_order_no,
        SUM(qcstitching_inhouse_reject_detail.size_qty_total) AS reject_qty
    FROM qcstitching_inhouse_reject_detail
    WHERE qcstitching_inhouse_reject_detail.sales_order_no IN ($soList)
        AND qcstitching_inhouse_reject_detail.qcsti_date <= '$toDate'
        $genericVendorFilter
    GROUP BY qcstitching_inhouse_reject_detail.sales_order_no
");
$RejectMap = collect($RejectionList)->keyBy('sales_order_no');


// ===================================================================
//                          MAIN FOREACH LOOP
// ===================================================================
foreach ($Buyer_Purchase_Order_List as $row) {

    $tr = $row->tr_code;

    // Safe lookup with fallback
    $work_order_qty     = $VendorDataMap[$tr]->work_order_qty ?? 0;
    $cutPanelIssueQty   = $CutPanelMap[$tr]->total_qty ?? 0;
    $stichingQty        = $StitchMap[$tr]->stiching_qty ?? 0;
    $pack_order_qty     = $PackingMap[$tr]->pack_qty ?? 0;
    $fabric_value       = $CostingMap[$tr]->fabric_value ?? 0;
    $sewing_trims_value = $CostingMap[$tr]->sewing_trims_value ?? 0;
    $packing_trims_value= $CostingMap[$tr]->packing_trims_value ?? 0;
    $WIP_Adjust_qty     = $WIPAdjustMap[$tr]->wip_adj ?? 0;
    $rejectionQty       = $RejectMap[$tr]->reject_qty ?? 0;

    // Order type
    $order_type = match($row->order_type){
        1 => 'Fresh',
        2 => 'Stock',
        3 => 'Job Work',
        default => ''
    };

    $sewing  = $cutPanelIssueQty - $stichingQty;

    $WIPQty  = ($work_order_qty - $cutPanelIssueQty)
               + $sewing
               + ($stichingQty - $pack_order_qty - $rejectionQty)
               - $WIP_Adjust_qty;

    $rate    = $fabric_value + $sewing_trims_value + $packing_trims_value;
    $WIPValue = $rate * $WIPQty;

?>
 <tr>
    <td nowrap class="text-right">{{ $srno++ }}</td>
    <td nowrap>{{ $row->tr_code }}</td>
    <td nowrap>{{ $row->job_status_name }}</td>
    <td nowrap>{{ $order_type }}</td>
    <td nowrap>{{ $row->brand_name }}</td>
    <td nowrap>{{ $row->mainstyle_name }}</td>

<td class="text-right" nowrap>{{ indian_number_format_for_value($row->total_qty, 2) }}</td>

<td class="text-right" nowrap>{{ indian_number_format_for_value($work_order_qty, 2) }}</td>

<td class="text-right" nowrap>{{ indian_number_format_for_value($cutPanelIssueQty, 2) }}</td>

<td class="text-right" nowrap>{{ indian_number_format_for_value(($work_order_qty - $cutPanelIssueQty), 2) }}</td>

<td class="text-right" nowrap>{{ indian_number_format_for_value($stichingQty, 2) }}</td>

<td class="text-right" nowrap>{{ indian_number_format_for_value($sewing, 2) }}</td>

<td class="text-right" nowrap>{{ indian_number_format_for_value(($pack_order_qty + $rejectionQty), 2) }}</td>

<td class="text-right" nowrap>{{ indian_number_format_for_value(($stichingQty - $pack_order_qty - $rejectionQty), 2) }}</td>

<td class="text-right" nowrap>{{ indian_number_format_for_value($rejectionQty, 2) }}</td>

<td class="text-right" nowrap>{{ indian_number_format_for_value($WIP_Adjust_qty, 2) }}</td>

<td class="text-right" nowrap>
    {{ indian_number_format_for_value(
        ($work_order_qty - $cutPanelIssueQty)
        + $sewing
        + ($stichingQty - $pack_order_qty - $rejectionQty)
        - $WIP_Adjust_qty,
        2
    ) }}
</td>

    <td class="text-right" nowrap>{{ sprintf('%0.2f', $rate) }}</td>

    <td class="text-right" nowrap>{{ indian_number_format_for_value($WIPValue, 2) }}</td>
</tr>

<?php

    $head_total_WIPQty   += $WIPQty;
    $head_total_WIPValue += $WIPValue;

} // foreach end

?>

                         </tbody>
                      </table>
            </div>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<!-- end row -->
<input type="hidden" id="hidden_WIP_Qty" value="{{number_format((float)$head_total_WIPQty/100000, 2, '.', '')}}">
<input type="hidden" id="hidden_WIP_Value" value="{{number_format((float)$head_total_WIPValue/100000, 2, '.', '')}}">
<script src="https://code.jquery.com/jquery-1.12.3.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" ></script> 
<script>  

    $(document).ready(function()
    { 
        $("#head_total_WIP").text($("#hidden_WIP_Qty").val());
        $("#head_total_WIP_value").text($("#hidden_WIP_Value").val()); 
        $('#job_status_id').val([{{$Status}}]).trigger('change');
        $('#orderTypeId').val([{{$orderType}}]).trigger('change');
                  
        if ($.fn.DataTable.isDataTable('#dt')) {
            $('#dt').DataTable().clear().destroy();
        } 

        const today = new Date();
        const day = String(today.getDate()).padStart(2, '0');
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const year = today.getFullYear();
        const formattedDate = `${day}-${month}-${year}`;
        const exportTitle = 'Total WIP Report (' + formattedDate + ')';
        
        $('#dt').DataTable({
            destroy: true,
            dom: 'Bfrtip',
             initComplete: function () {
               buildAllMenusWIPReport();
            },
            buttons: [
                {
                    extend: 'copyHtml5',
                    text: 'Copy',
                    title: exportTitle, exportOptions: commonExportOptions()
                },
                {
                    extend: 'excelHtml5',
                    text: 'Excel',
                    title: exportTitle, exportOptions: commonExportOptions()
                },
                {
                    extend: 'csvHtml5',
                    text: 'CSV',
                    title: exportTitle, exportOptions: commonExportOptions()
                },
                {
                    extend: 'pdfHtml5',
                    text: 'PDF',
                    title: exportTitle,
                    exportOptions: commonExportOptions(),
                    orientation: 'landscape',     // or 'portrait'
                    pageSize: 'A4',               // A4, A3, etc.
                    customize: function (doc) {
                        doc.defaultStyle.fontSize = 10; // PDF text size
                    }
                },
                {
                    extend: 'print',
                    text: 'Print Table',
                    title: exportTitle
                }
            ]

        }); 
    }); 

             // Start script for filter search and apply        
         $(document).on('click', '.apply-btn', function() {
         const menu = $(this).closest('.filter-menu');
       
         if (!validateFilterMenu(menu)) {
               return;
         }

         if (menu.hasClass('sr-no')) { applySimpleFilter(0, menu); } 
         else if (menu.hasClass('order-no')) { applySimpleFilter(1, menu); } 
         else if (menu.hasClass('po-status')) { applySimpleFilter(2, menu); } 
         else if (menu.hasClass('order-type')) { applySimpleFilter(3, menu); } 
         else if (menu.hasClass('buyer-brand')) { applySimpleFilter(4, menu); } 
         else if (menu.hasClass('category')) { applySimpleFilter(5, menu); } 
         else if (menu.hasClass('order-qty')) { applySimpleFilter(6, menu); } 
         else if (menu.hasClass('work-order-qty')) { applySimpleFilter(7, menu); } 
         else if (menu.hasClass('cutting-qty')) { applySimpleFilter(8, menu); } 
         else if (menu.hasClass('cutting-wip')) { applySimpleFilter(9, menu); } 
         else if (menu.hasClass('sewing-qty')) { applySimpleFilter(10, menu); } 
         else if (menu.hasClass('sewing-wip')) { applySimpleFilter(11, menu); } 
         else if (menu.hasClass('packing-qty')) { applySimpleFilter(12, menu); } 
         else if (menu.hasClass('packing-wip')) { applySimpleFilter(13, menu); } 
         else if (menu.hasClass('rejection-qty')) { applySimpleFilter(14, menu); } 
         else if (menu.hasClass('wip-adjust-qty')) { applySimpleFilter(15, menu); } 
         else if (menu.hasClass('total-wip')) { applySimpleFilter(16, menu); } 
         else if (menu.hasClass('order-rate')) { applySimpleFilter(17, menu); } 
         else if (menu.hasClass('total-wip-value')) { applySimpleFilter(18, menu); }

         $('.filter-menu').hide();         
         buildAllMenusWIPReport();       
         });
         // End script for filter search and apply

    
   </script>
@endsection