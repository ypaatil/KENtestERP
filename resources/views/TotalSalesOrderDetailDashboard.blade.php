@extends('layouts.master') 
@section('content')   
<!-- end page title -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">All Orders Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Reports</a></li>
               <li class="breadcrumb-item active">All Orders Report</li>
            </ol>
         </div>
      </div>
   </div>
</div>
@php
setlocale(LC_MONETARY, 'en_IN');  
if($job_status_id==1) { @endphp
<style>
    #total_head th{
        font-weight : 800;
    }
</style>
<div class="row">
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#152d9f;" >
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">No. of Orders</p>
                  <h4 class="mb-0" style="color:#fff;">{{number_format($NoOfOrderc)}} </h4>
               </div>
               <div class="flex-shrink-0 align-self-center">
                  <div class="avatar-sm rounded-circle bg-primary" style="background-color:#152d9f;">
                     <span class="avatar-title" style="background-color:#152d9f;">
                     <i class="bx bx-copy-alt font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#556ee6;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;" >Order Qty(Lakh)</p>
                  <h4 class="mb-0" style="color:#fff;" >{{number_format((double)($total_qtyc/100000), 2, '.', '')}} </h4>
               </div>
               <div class="flex-shrink-0 align-self-center ">
                  <div class="avatar-sm rounded-circle bg-primary  ">
                     <span class="avatar-title  " style="background-color:#556ee6;" >
                     <i class="bx bx-purchase-tag-alt font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#008116;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">Shipped Qty(Lakh)</p>
                  <h4 class="mb-0" style="color:#fff;">  {{number_format((double)($shipped_qtyc/100000), 2, '.', '')}}  </h4>
               </div>
               <div class="flex-shrink-0 align-self-center">
                  <div class="avatar-sm rounded-circle bg-primary  " >
                     <span class="avatar-title  " style="background-color:#008116;">
                     <i class="bx bx-archive-in font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#f79733;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">Open Qty(Lakh)</p>
                  <h4 class="mb-0" style="color:#fff;">{{number_format((double)($open_qtyc/100000), 2, '.', '')}} </h4>
               </div>
               <div class="flex-shrink-0 align-self-center">
                  <div class="avatar-sm rounded-circle bg-primary  " >
                     <span class="avatar-title  " style="background-color:#f79733;">
                     <i class="bx bx-archive-in font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@php 
}
@endphp    
<div class="row">
   <div class="col-md-12">
      <div class="card">
         <div class="card-body"> 
              <form action="{{route('TotalSalesOrderDetailDashboard')}}" method="GET" enctype="multipart/form-data">
                   @csrf 
                   <div class="row">
                       <label for="" class="form-label"><b>Order Receive</b></label>
                       <div class="col-md-2">
                         <div class="mb-3">
                            <label for="receivedFromDate" class="form-label">From</label>
                            <input type="date" class="form-control" name="receivedFromDate" id="receivedFromDate" value="{{ isset($receivedFromDate) ? $receivedFromDate : ""}}">
                         </div>
                       </div>
                       <div class="col-md-2">
                         <div class="mb-3">
                            <label for="receivedToDate" class="form-label">To</label>
                            <input type="date" class="form-control" name="receivedToDate" id="receivedToDate" value="{{ isset($receivedToDate) ? $receivedToDate :  ""}}">
                         </div>
                       </div> 
                       <div class="col-md-3 hide">
                         <div class="mb-3">
                            <label for="OrderFromDate" class="form-label">Order Close From Date</label>
                            <input type="date" class="form-control" name="OrderFromDate" id="OrderFromDate" value="{{ isset($OrderFromDate) ? $OrderFromDate : ""}}">
                         </div>
                       </div>
                       <div class="col-md-3 hide">
                         <div class="mb-3">
                            <label for="OrderToDate" class="form-label">Order Close To Date</label>
                            <input type="date" class="form-control" name="OrderToDate" id="OrderToDate" value="{{ isset($OrderToDate) ? $OrderToDate :  ""}}">
                         </div>
                       </div>   
                       <div class="col-md-3 hide">
                         <div class="mb-3">
                            <label for="sales_order_no" class="form-label">Sales Order No</label>
                            <select name="sales_order_no" id="sales_order_no" class="form-control select2">
                                <option value="">--Select--</option>
                                @foreach($salesOrderList as $row)
                                    <option value="{{$row->tr_code}}" {{ $row->tr_code == $sales_order_no ? 'selected="selected"' : '' }} >{{$row->tr_code}}</option>
                                @endforeach
                            </select>
                         </div>
                       </div> 
                       <div class="col-md-3 hide">
                         <div class="mb-3">
                            <label for="job_status_id" class="form-label">Job Status Name</label>
                            <select name="job_status_id" id="job_status_id" class="form-control select2">
                                <option value="">--Select--</option>
                                @foreach($jobStatusList as $row)
                                    <option value="{{$row->job_status_id}}" {{ $row->job_status_id == $job_status_id ? 'selected="selected"' : '' }} >{{$row->job_status_name}}</option>
                                @endforeach
                            </select>
                         </div>
                       </div> 
                       <div class="col-md-3 hide">
                         <div class="mb-3">
                            <label for="orderTypeId" class="form-label">Order Type</label>
                            <select name="orderTypeId" id="orderTypeId" class="form-control select2">
                                <option value="">--Select--</option>
                                @foreach($orderTypeList as $row)
                                    <option value="{{$row->orderTypeId}}" {{ $row->orderTypeId == $orderTypeId ? 'selected="selected"' : '' }} >{{$row->order_type}}</option>
                                @endforeach
                            </select>
                         </div>
                       </div> 
                       <div class="col-md-3 hide">
                         <div class="mb-3">
                            <label for="ac_code" class="form-label">Buyer Name</label>
                            <select name="ac_code" id="ac_code" class="form-control select2">
                                <option value="">--Select--</option>
                                @foreach($buyerList as $row)
                                    <option value="{{$row->ac_code}}" {{ $row->ac_code == $Ac_code ? 'selected="selected"' : '' }} >{{$row->ac_name}}</option>
                                @endforeach
                            </select>
                         </div>
                       </div> 
                        <div class="col-md-3 hide">
                         <div class="mb-3">
                            <label for="po_code" class="form-label">PO NO</label>
                            <select name="po_code" id="po_code" class="form-control select2">
                                <option value="">--Select--</option>
                                @foreach($poList as $row)
                                    <option value="{{$row->po_code}}"  {{ $row->po_code == $po_code ? 'selected="selected"' : '' }}  >{{$row->po_code}}</option>
                                @endforeach
                            </select>
                         </div>
                       </div> 
                       <div class="col-md-3 hide">
                         <div class="mb-3">
                            <label for="brand_id" class="form-label">Brand</label>
                            <select name="brand_id" id="brand_id" class="form-control select2">
                                <option value="">--Select--</option>
                                @foreach($brandList as $row)
                                    <option value="{{$row->brand_id}}"  {{ $row->brand_id == $brand_id ? 'selected="selected"' : '' }}  >{{$row->brand_name}}</option>
                                @endforeach
                            </select>
                         </div>
                       </div>  
                       <div class="col-md-3 hide">
                         <div class="mb-3">
                            <label for="mainstyle_id" class="form-label">Main Style Category</label>
                            <select name="mainstyle_id" id="mainstyle_id" class="form-control select2">
                                <option value="">--Select--</option>
                                @foreach($mainStyleList as $row)
                                    <option value="{{$row->mainstyle_id}}"  {{ $row->mainstyle_id == $mainstyle_id ? 'selected="selected"' : '' }} >{{$row->mainstyle_name}}</option>
                                @endforeach
                            </select>
                         </div>
                       </div>
                       <div class="col-md-3 hide">
                         <div class="mb-3">
                            <label for="fg_id" class="form-label">Style Name</label>
                            <select name="fg_id" id="fg_id" class="form-control select2">
                                <option value="">--Select--</option>
                                @foreach($styleList as $row)
                                    <option value="{{$row->fg_id}}"   {{ $row->fg_id == $fg_id ? 'selected="selected"' : '' }}  >{{$row->fg_name}}</option>
                                @endforeach
                            </select>
                         </div>
                       </div> 
                       <div class="col-md-3 hide">
                         <div class="mb-3">
                            <label for="merchant_id" class="form-label">Bulk Merchant</label>
                            <select name="merchant_id" id="merchant_id" class="form-control select2">
                                <option value="">--Select--</option>
                                @foreach($merchantList as $row)
                                    <option value="{{$row->merchant_id}}" {{ $row->merchant_id == $merchant_id ? 'selected="selected"' : '' }}>{{$row->merchant_name}}</option>
                                @endforeach
                            </select>
                         </div>
                       </div> 
                       <div class="col-sm-6">
                          <label for="formrow-inputState" class="form-label"></label>
                          <div class="form-group">
                             <button type="submit" class="btn btn-primary w-md">Search</button>
                             <a href="/TotalSalesOrderDetailDashboard" class="btn btn-danger w-md">Cancel</a>
                          </div>
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
            <div class="table-responsive">
               <table id="dt" class="table table-bordered nowrap w-100">
                  <thead>
                    <tr style="text-align:center; white-space:nowrap;background: bisque;" id="total_head">
                        <th colspan="18"></th>
                        <th style="text-align: right;">Total : </th>
                        <th style="text-align:right;" id="head_total_qty">0</th>
                        <th style="text-align:right;" id="head_total_Min_qty">0</th>
                        <th style="text-align:right;" id="head_total_order_value">0</th>
                        <th style="text-align:right;" id="head_total_ship_qty">0</th>
                        <th style="text-align:right;" id="head_total_bal_qty">0</th>
                        <th></th>
                        <th></th>
                        <th></th>
                     </tr>
                     <tr class="text-center" style="white-space:nowrap">
                        <th>Sr.No.</th>
                        <th>Order No.<span class="filter-icon">🔽</span><div class="filter-menu order-no"></div></th>
                        <th>Inhouse/Outsource<span class="filter-icon">🔽</span><div class="filter-menu inhouse-outsource"></div></th>
                        <th>Order Group<span class="filter-icon">🔽</span><div class="filter-menu order-group"></div></th>
                        <th>Order Category<span class="filter-icon">🔽</span><div class="filter-menu order-category"></div></th>
                        <th>PO status<span class="filter-icon">🔽</span><div class="filter-menu po-status"></div></th>
                        <th>Order Type<span class="filter-icon">🔽</span><div class="filter-menu order-type"></div></th>
                        <th>Recd. Date<span class="filter-icon">🔽</span><div class="filter-menu recd-date"></div></th>
                        <th>Plan Cut Date<span class="filter-icon">🔽</span><div class="filter-menu plancut-date"></div></th>
                        <th>Shipment Date<span class="filter-icon">🔽</span><div class="filter-menu shipment-date"></div></th>
                        <th>Close Date<span class="filter-icon">🔽</span><div class="filter-menu close-date"></div></th>
                        <th>Buyer Name<span class="filter-icon">🔽</span><div class="filter-menu buyer-name"></div></th>
                        <th>Buyer Brand<span class="filter-icon">🔽</span><div class="filter-menu buyer-brand"></div></th>
                        <th>Style<span class="filter-icon">🔽</span><div class="filter-menu style"></div></th>
                        <th>Sub Style<span class="filter-icon">🔽</span><div class="filter-menu sub-style"></div></th>
                        <th>Style Name<span class="filter-icon">🔽</span><div class="filter-menu style-name"></div></th>
                        <th>SAM</th>
                        <th>Cons.</th>
                        <th>Rate</th>
                        <th>Qty  </th>
                        <th>Min </th>
                        <th>Value  </th>
                        <th>Shipment Qty </th>
                        <th>Bal. Qty  </th>
                        <th>Costing Entry<span class="filter-icon">🔽</span><div class="filter-menu costing-entry"></div></th>
                        <th>Costing Status<span class="filter-icon">🔽</span><div class="filter-menu costing-status"></div></th>
                        <th>Bulk Merchant<span class="filter-icon">🔽</span><div class="filter-menu bulk-merchant"></div></th>
                     </tr>
                  </thead>
                  <tbody>
                     @php
                         $totalQty = 0;
                         $totalMinutes = 0;
                         $orderValue = 0;
                         $totalShipped_qty = 0;
                         $totalBalance_qty= 0;
                         $srno = 1;
                     @endphp 
                     @foreach($Buyer_Purchase_Order_List as $row)    
                     @php
                        if($row->order_type == 1)
                        {
                            $orderType = 'Fresh';
                        }
                        else if($row->order_type == 2)
                        {
                            $orderType = 'Stock';
                        }
                        else if($row->order_type == 3)
                        {
                            $orderType = 'Job Work';
                        }
                        else
                        {
                            $orderType = '';
                        }
                        
                        $BalanceShip=DB::table('sale_transaction_detail')->select(DB::raw('sum(order_qty) as shipped_qty1'))
                                    ->join('sale_transaction_master', 'sale_transaction_master.sale_code','=', 'sale_transaction_detail.sale_code')
                                    ->where('sale_transaction_detail.sales_order_no',$row->tr_code)
                                    ->whereIn('sale_transaction_master.sales_head_id',[1, 2, 3, 5, 6, 8])
                                    ->get();
                        
                        if($row->order_close_date != "")
                        {
                            $order_close_date =  date("d-m-Y", strtotime($row->order_close_date));
                        }
                        else
                        {
                            $order_close_date = "-";
                        }
                        $salesOrderCostingData = DB::SELECT("SELECT count(*) as total_count,is_approved FROM sales_order_costing_master WHERE sales_order_no='".$row->tr_code."'");
                        $costing_count = isset($salesOrderCostingData[0]->total_count) ? $salesOrderCostingData[0]->total_count : 0;
                        $is_approved = isset($salesOrderCostingData[0]->is_approved) ? $salesOrderCostingData[0]->is_approved : 0;
                        
                        if($row->in_out_id == 1)
                        {
                            $in_out_id = 'Inhouse';
                        }
                        else if($row->in_out_id == 2)
                        {
                            $in_out_id = 'Outsource';
                        }
                        else  
                        {
                            $in_out_id = '';
                        }
                     @endphp
                     <tr>
                        <td style="text-align:left; white-space:nowrap"> {{ $srno++  }} </td>
                        <td style="text-align:left; white-space:nowrap"> {{ $row->tr_code  }} </td>
                        <td style="text-align:left; white-space:nowrap"> {{ $in_out_id  }} </td>
                        <td style="text-align:left; white-space:nowrap"> {{ $row->order_group_name  }} </td>
                        <td style="text-align:left; white-space:nowrap"> {{ $row->OrderCategoryName  }} </td>
                        <td style="text-align:left;"> {{ $row->job_status_name  }} </td>
                        <td style="text-align:left;"> {{ $orderType  }} </td>
                        @if(!empty($row->order_received_date) && strtotime($row->order_received_date))
                            <td style="text-align:center; white-space:nowrap">  {{ date('d-M-Y', strtotime($row->order_received_date)) }} </td>
                        @else
                            <td style="text-align:center; white-space:nowrap">-</td>
                        @endif
                        @if(!empty($row->plan_cut_date) && strtotime($row->plan_cut_date))
                            <td style="text-align:center; white-space:nowrap">  {{ date('d-M-Y', strtotime($row->plan_cut_date)) }} </td>
                        @else
                            <td style="text-align:center; white-space:nowrap">-</td>
                        @endif
                        @if(!empty($row->shipment_date) && strtotime($row->shipment_date))
                            <td style="text-align:center; white-space:nowrap">  {{ date('d-M-Y', strtotime($row->shipment_date)) }} </td>
                        @else
                            <td style="text-align:center; white-space:nowrap">-</td>
                        @endif
                        @if(!empty($row->order_close_date) && strtotime($row->order_close_date))
                            <td style="text-align:center; white-space:nowrap">  {{ date('d-M-Y', strtotime($row->order_close_date)) }} </td>
                        @else
                            <td style="text-align:center; white-space:nowrap">-</td>
                        @endif
                        <td style="text-align:left; white-space:nowrap"> {{ $row->ac_short_name  }} </td>
                        <td style="text-align:left; white-space:nowrap"> {{ $row->brand_name  }} </td>
                        <td style="text-align:left; white-space:nowrap"> {{ $row->mainstyle_name  }} </td>
                        <td style="text-align:left; white-space:nowrap"> {{ $row->substyle_name  }}</td>
                        <td style="text-align:left; white-space:wrap"> {{ $row->style_no  }} </td>
                        <td style="text-align:right; white-space:nowrap"> {{ sprintf('%.2f', round($row->sam,2)) }} </td>
                        <td style="text-align:right; white-space:nowrap"> {{ sprintf('%.2f', round($row->consumption,2)) }} </td>
                        <td style="text-align:right;"> {{ money_format('%!i',$row->order_rate)  }} </td>
                        <td style="text-align:right;"> {{ money_format('%!.0n',($row->total_qty)) }} </td>
                        <td style="text-align:right;"> {{ explode('.',money_format('%!i',round($row->total_qty * $row->sam)))[0] }} </td>
                        <td style="text-align:right;"> {{ explode('.',money_format('%!i',round($row->total_qty*$row->order_rate)))[0] }}</td>
                        <td style="text-align:right;"> {{ money_format('%!.0n', round(( $BalanceShip[0]->shipped_qty1)))}} </td>
                        <td style="text-align:right;"> {{ money_format('%!.0n', round($row->total_qty - $BalanceShip[0]->shipped_qty1))}} </td>
                        <td> @if($costing_count > 0) <span class="badge badge-pill badge-soft-success font-size-11" >{{'Completed'}} </span> @else <span class="badge badge-pill badge-soft-danger font-size-11">{{'Not Done'}} </span> @endif </td>
                        <td> @if($is_approved == 2) <span class="badge badge-pill badge-soft-success font-size-11" >{{'Approved'}} </span> @else <span class="badge badge-pill badge-soft-danger font-size-11">{{'Not Approved'}} </span> @endif </td>
                        <td style="text-align:left; white-space:nowrap"> {{ $row->merchant_name  }} </td>
                     </tr> 
                     @php 
                            $totalQty = $totalQty + $row->total_qty;
                            $totalMinutes = $totalMinutes + $row->total_qty * $row->sam;
                            $orderValue = $orderValue + $row->total_qty*$row->order_rate;
                            $totalShipped_qty = $totalShipped_qty + $BalanceShip[0]->shipped_qty1;
                            $totalBalance_qty = $totalBalance_qty + ($row->total_qty - $BalanceShip[0]->shipped_qty1);
                     @endphp
                     @endforeach
                     <input type="hidden" id="totalQty" value="{{money_format('%!.0n',round($totalQty))}}">
                     <input type="hidden" id="totalMinutes" value="{{money_format('%!.0n',round($totalMinutes))}}">
                     <input type="hidden" id="orderValue" value="{{money_format('%!.0n',round($orderValue))}}">
                     <input type="hidden" id="totalShipped_qty" value="{{money_format('%!.0n',round($totalShipped_qty))}}">
                     <input type="hidden" id="totalBalance_qty" value="{{money_format('%!.0n',round($totalBalance_qty))}}">
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
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>

    $('#head_total_qty').html($('#totalQty').val());
    $('#head_total_Min_qty').html($('#totalMinutes').val());
    $('#head_total_order_value').html($('#orderValue').val());
    $('#head_total_ship_qty').html($('#totalShipped_qty').val());
    $('#head_total_bal_qty').html($('#totalBalance_qty').val());
       
    $(document).ready(function() 
    {
        if ($.fn.DataTable.isDataTable('#dt')) {
            $('#dt').DataTable().clear().destroy();
        } 

        const today = new Date();
        const day = String(today.getDate()).padStart(2, '0');
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const year = today.getFullYear();
        const formattedDate = `${day}-${month}-${year}`;
        const exportTitle = 'All Orders Report (' + formattedDate + ')';
        
        const table = $('#dt').DataTable({
            destroy: true,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copyHtml5',
                    text: 'Copy',
                    title: exportTitle,
                    exportOptions: commonExportOptions([]),
                },
                {
                    extend: 'excelHtml5',
                    text: 'Excel',
                    title: exportTitle,
                    exportOptions: commonExportOptions([7,8,9,10]),
                },
                {
                    extend: 'csvHtml5',
                    text: 'CSV',
                    title: exportTitle,
                    exportOptions: commonExportOptions([7,8,9,10]),
                },
                {
                    extend: 'pdfHtml5',
                    text: 'PDF',
                    title: exportTitle,
                    exportOptions: commonExportOptions([]),
                    orientation: 'landscape',     // or 'portrait'
                    pageSize: 'A4',               // A4, A3, etc.
                    customize: function (doc) {
                        doc.defaultStyle.fontSize = 10; // PDF text size
                    }
                },
                {
                    extend: 'print',
                    text: 'Print Table',
                    title: exportTitle,
                    exportOptions: commonExportOptions([])
                }
            ]

        });

   // New script added 11-11-2025   
  buildAllMenusTotalsSalesOrderDetailDashboard(); 

  $(document).on('click', '.apply-btn', function() {
    const menu = $(this).closest('.filter-menu');
    if(menu.hasClass('order-no')) applySimpleFilter(1, menu);
    else if(menu.hasClass('inhouse-outsource')) applySimpleFilter(2, menu);
    else if(menu.hasClass('order-group')) applySimpleFilter(3, menu);
    else if(menu.hasClass('order-category')) applySimpleFilter(4,menu);
    else if(menu.hasClass('po-status')) applySimpleFilter(5,menu);
    else if(menu.hasClass('order-type')) applySimpleFilter(6,menu);
    else if(menu.hasClass('recd-date')) applyDateFilter( 7,menu);
    else if(menu.hasClass('plancut-date')) applyDateFilter( 8,menu); 
    else if(menu.hasClass('shipment-date')) applyDateFilter(9, menu);
    else if(menu.hasClass('close-date')) applyDateFilter(10, menu);     
   else if(menu.hasClass('buyer-name')) applySimpleFilter(11,menu);
    else if(menu.hasClass('buyer-brand')) applySimpleFilter(12,menu);
   else if(menu.hasClass('style')) applySimpleFilter(13,menu);
   else if(menu.hasClass('sub-style')) applySimpleFilter(14,menu);
   else if(menu.hasClass('style-name')) applySimpleFilter(15,menu);
   else if(menu.hasClass('costing-entry')) applyColouredFilter(24,menu);
   else if(menu.hasClass('costing-status')) applyColouredFilter(25,menu);
   else if(menu.hasClass('bulk-merchant')) applySimpleFilter(26,menu);
    
    $('.filter-menu').hide();
    buildAllMenusTotalsSalesOrderDetailDashboard();   
    updateTotalsSalesOrderDetailDashboard();
   });

  $(document).on('click', '.clear-btn', function(){
    table.search('').columns().search('').draw();
    buildAllMenusTotalsSalesOrderDetailDashboard();    
    updateTotalsSalesOrderDetailDashboard();
  });

   });
</script>
@endsection