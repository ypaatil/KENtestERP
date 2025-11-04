@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp
<!-- end page title -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
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
         <h4 class="mb-sm-0 font-size-18">Partial Dispatch Costing Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Reports</a></li>
               <li class="breadcrumb-item active">Partial Dispatch Costing Report</li>
            </ol>
         </div>
      </div>
   </div>
</div>  
    <div class="col-md-12">
       <div class="card mini-stats-wid">
          <div class="card-body">
              <form action="/PartialDispatchCostingReport" method="GET">
                  <div class="row">  
                      <div class="col-md-3">
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
                      <div class="col-md-6 mt-3"> 
                            <button type="submit" class="btn btn-primary">Search</button>
                            <a href="/PartialDispatchCostingReport" class="btn btn-warning">Clear</a>
                      </div>
                  </div>
              </form>
          </div>
       </div>
    </div>
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
               <div id="Fabric" class="table-responsive">
                  <?php
                    // Pre-fetch everything at the top (controller or start of view if inline logic is used)
                    
                    $salesOrderNos = array_column($Buyer_Purchase_Order_List->toArray(), 'tr_code');
                    $itemCodeNos = array_column($Buyer_Purchase_Order_List->toArray(), 'item_code');
                    $ColorIdNos = array_column($Buyer_Purchase_Order_List->toArray(), 'color_id');
                    
                    // 1. Fabric List
                  $fabricData = DB::table('bom_fabric_details')  
                        ->join('item_master', 'item_master.item_code', '=', 'bom_fabric_details.item_code')
                        ->join('classification_master', 'classification_master.class_id', '=', 'item_master.class_id')
                        
                        ->join('stock_association_for_fabric', function ($join) {
                            $join->on('stock_association_for_fabric.sales_order_no', '=', 'bom_fabric_details.sales_order_no')
                                 ->on('stock_association_for_fabric.item_code', '=', 'item_master.item_code');
                        })
                        ->select(
                            'bom_fabric_details.sales_order_no',
                            'stock_association_for_fabric.po_code',
                            'bom_fabric_details.item_code',
                            'item_master.item_name',
                            'classification_master.class_name',
                            'bom_fabric_details.consumption'
                        )
                        ->whereIn('bom_fabric_details.sales_order_no', $salesOrderNos)
                        ->groupBy(
                            'bom_fabric_details.sales_order_no',
                            'bom_fabric_details.item_code',
                            'bom_fabric_details.color_id'
                        )
                        ->get()
                        ->groupBy('sales_order_no');

                    
                    // 2. Sewing List
                    //DB::enableQueryLog();
                    $sewingData = DB::table('stock_association')
                        ->leftJoin('item_master', 'item_master.item_code', '=', 'stock_association.item_code')
                        ->leftJoin('classification_master', 'classification_master.class_id', '=', 'item_master.class_id')
                        ->leftJoin('bom_sewing_trims_details', function ($join) {
                            $join->on('bom_sewing_trims_details.bom_code', '=', 'stock_association.bom_code')
                                 ->on('bom_sewing_trims_details.item_code', '=', 'stock_association.item_code');
                        })
                        ->select(
                            'stock_association.sales_order_no',
                            'stock_association.item_code',
                            'stock_association.po_code',
                            DB::raw('SUM(CASE WHEN stock_association.tr_type = 1 THEN stock_association.qty ELSE 0 END) as allocated_qty'),
                            DB::raw('SUM(CASE WHEN stock_association.tr_type = 2 AND stock_association.tr_code IS NULL THEN stock_association.qty ELSE 0 END) as each_qty'),
                            DB::raw('SUM(CASE WHEN stock_association.sales_order_no != stock_association.sales_order_no THEN stock_association.qty ELSE 0 END) as other_allocated_qty'),
                            'item_master.item_name',
                            'classification_master.class_name',
                            'bom_sewing_trims_details.consumption'
                        )
                        ->whereIn('stock_association.sales_order_no', $salesOrderNos)
                        ->where('item_master.cat_id', 2) 
                        ->groupBy('bom_sewing_trims_details.sales_order_no', 'bom_sewing_trims_details.item_code', 'bom_sewing_trims_details.color_id')
                        ->get()
                        ->groupBy('sales_order_no');
                    
                        //dd(DB::getQueryLog());
                    // 3. Packing List
                    $packingData = DB::table('stock_association')
                        ->leftJoin('item_master', 'item_master.item_code', '=', 'stock_association.item_code')
                        ->leftJoin('classification_master', 'classification_master.class_id', '=', 'item_master.class_id')
                        ->leftJoin('bom_packing_trims_details', function ($join) {
                            $join->on('bom_packing_trims_details.bom_code', '=', 'stock_association.bom_code')
                                 ->on('bom_packing_trims_details.item_code', '=', 'stock_association.item_code');
                        })
                        ->select(
                            'stock_association.sales_order_no',
                            'stock_association.item_code',
                            'stock_association.po_code',
                            DB::raw('SUM(CASE WHEN stock_association.tr_type = 1 THEN stock_association.qty ELSE 0 END) as allocated_qty'),
                            DB::raw('SUM(CASE WHEN stock_association.tr_type = 3 AND stock_association.tr_code IS NULL THEN stock_association.qty ELSE 0 END) as each_qty'),
                            DB::raw('SUM(CASE WHEN stock_association.sales_order_no != stock_association.sales_order_no THEN stock_association.qty ELSE 0 END) as other_allocated_qty'),
                            'item_master.item_name',
                            'classification_master.class_name',
                            'bom_packing_trims_details.consumption'
                        )
                        ->whereIn('stock_association.sales_order_no', $salesOrderNos)
                        ->where('item_master.cat_id', 3) 
                        ->groupBy('bom_packing_trims_details.sales_order_no', 'bom_packing_trims_details.item_code', 'bom_packing_trims_details.color_id')
                        ->get()
                        ->groupBy('sales_order_no');
                    
                    // Inward Rate Lookup
                    $inwardRates = DB::table('purchaseorder_detail')
                        ->select('item_code', 'pur_code', 'item_rate')
                        ->get()
                        ->keyBy(function ($item) {
                            return $item->item_code . '|' . $item->pur_code;
                        });
                    
                    function getRate($item_code, $po_code, $inwardRates) {
                        $key = $item_code . '|' . $po_code;
                        return $inwardRates[$key]->item_rate ?? 0;
                    }
                    ?>
                    
                    <table class="table table-bordered text-1 table-sm" id="Summary1">
                        <thead>
                            <tr style="background-color:#eee; text-align:center;">
                                <th>Sr No</th>
                                <th>Invoice Date</th>
                                <th>Sales Order No</th>
                                <th>BOM Type</th>
                                <th>Item Code</th>
                                <th>Classification</th>
                                <th>Item Name</th>
                                <th>Color Name</th>
                                <th>PO No.</th>
                                <th>PO Rate</th>
                                <th>Cons.</th>
                                <th>Shipment Qty</th>
                                <th>Issue Qty</th>
                                <th>Issue Value</th>
                            </tr>
                        </thead>
                        <tbody>
                        @php
                            $no = 1;
                        @endphp
                        @foreach($Buyer_Purchase_Order_List as $row)
                    
                            @foreach(['Fabric' => $fabricData, 'Sewing' => $sewingData, 'Packing' => $packingData] as $bomType => $dataGroup)
                                @php
                                    $bomList = $dataGroup[$row->tr_code] ?? collect();
                                    $totalIssueQty = 0;
                                    $totalIssueValue = 0;
                                @endphp
                                @foreach($bomList as $rowDetail)
                                    @php
                                        $item_code = $rowDetail->item_code;
                                        $po_code = $rowDetail->po_code; 
                                        $rate = getRate($item_code, $po_code, $inwardRates);
                                    @endphp
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $row->sale_date }}</td>
                                        <td>{{ $row->tr_code }}</td>
                                        <td>{{ $bomType }}</td>
                                        <td>{{ $item_code }}</td>
                                        <td nowrap>{{ $rowDetail->class_name }}</td>
                                        <td nowrap>{{ $rowDetail->item_name }}</td>
                                        <td nowrap>{{ $row->color_name }}</td>
                                        <td nowrap>{{ $po_code }}</td>
                                        <td nowrap>{{ round($rate, 4) }}</td>
                                        <td nowrap>{{ round($rowDetail->consumption, 4) }}</td>
                                        <td style="text-align:right">{{ number_format($row->total_order_qty, 2) }}</td>
                                        <td style="text-align:right">{{ number_format($row->total_order_qty * $rowDetail->consumption, 2) }}</td>
                                        <td style="text-align:right">{{ number_format($row->total_order_qty * $rate, 2) }}</td>
                                    </tr>
                                @endforeach 
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>
               </div> 
         </div>
      </div>
   </div>
   <!-- end col -->
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- Buttons Extension -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>

<!-- JSZip and pdfmake for export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>  
    $(document).ready(function(){
        $('#Summary1').DataTable({
            dom: 'Bfrtip', // Show Buttons, filter, table, pagination
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print', 'colvis'
            ],
            pageLength: 25,
            lengthMenu: [5, 10, 25, 50, 100],
            ordering: true,
            responsive: true
        });
    });

</script>
@endsection